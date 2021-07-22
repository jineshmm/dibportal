<?php

namespace App\Http\Controllers\Renewal;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use App;
//use App\Mail\dpportalmail;
use Mail;
use App\customer;
use App\Dpibquoterequest;
use App\crmMain;
use App\crmTask;
use App\crmRequest;
use App\Http\Controllers\Controller;
use Session;
use PDF;
use File;
use Illuminate\Support\Facades\Input;
use Excel;
use Illuminate\Support\Facades\Storage;

class RenewalController extends Controller {

    /**
     * Create customer page
     * @return type
     */
    public function renewalnotificationList() {
        $userGroup = array('' => '--- not set ---', 'corporate' => 'Corporate', 'retail' => 'Retail', 'sme' => 'SME');
        $sessionData = Session::get('renewaldaysfilter_' . Auth::user()->id);
        $formData = [];
        if ($sessionData != '') {
            $whereArray = json_decode($sessionData, true);
            $formData = json_decode(Session::get('renewaldaysfilterform_' . Auth::user()->id), true);
        }
        $lineofbusiness = DB::table('line_of_business')->distinct()->where('status', '1')->orderBy('title')->pluck('title', 'id')->toArray();
        $renewallist = DB::table('operation_renewal_notification as re')
                ->join('policies as p', 'p.id', '=', 're.policy_id')
                ->join('customers as c', 'c.id', '=', 're.customer_id')
                ->leftJoin('customer_contact_person_info as cp', 'c.id', '=', 'cp.customer_id')
                ->select('re.*', 'p.policy_number', 'p.start_date', 'p.end_date', 'c.name', 'p.policy_number', DB::raw("(case p.policy_type when 2 then 'Medical' when 3 then 'Motor' else 'General'  end) AS lobdata"), DB::raw('DATEDIFF(p.end_date,now()) as datediff'), 'c.type', 'c.id_code', 'c.customer_group', 'c.phone as customerPhone', 'cp.email as contactEmail', 'c.email as customerEmail', 'cp.phone as contactPhone', 'c.name')
                ->where('p.end_date', '>=', date('Y-m-d'))
                ->whereBetween(DB::raw('DATEDIFF(p.end_date,now())'), [1, 60])
                ->orderBy('datediff', 'asc')
                ->paginate(12);

        $data = array('renewalDatas' => $renewallist, 'usergroup' => $userGroup,'formData' => $formData, 'lineofbusiness' => $lineofbusiness);
        
        return view('Renewal/notificationlist', $data);
    }
       /**
     * 
     * @param Request $request
     * @return type
     */
    public function renewalFilter(Request $request) {

        $filtername = $request->get('filter_customer_name', '');
        $filtertype = $request->get('filter_customer_type', '');
        $filtergroup = $request->get('filter_customergroup_oid', '');
        $filterDays = $request->get('filter_remaining_days', '');

        $query = DB::table('operation_renewal_notification as re')
                ->join('policies as p', 'p.id', '=', 're.policy_id')
                ->join('customers as c', 'c.id', '=', 're.customer_id')
                ->leftJoin('customer_contact_person_info as cp', 'c.id', '=', 'cp.customer_id')
                ->select('re.*', 'p.policy_number', 'p.start_date', 'p.end_date', 'c.name', 'p.policy_number', DB::raw("(case p.policy_type when 2 then 'Medical' when 3 then 'Motor' else 'General'  end) AS lobdata"), DB::raw('DATEDIFF(p.end_date,now()) as datediff'), 'c.type', 'c.id_code', 'c.customer_group', 'c.phone as customerPhone', 'cp.email as contactEmail', 'c.email as customerEmail', 'cp.phone as contactPhone', 'c.name')
                ->where('p.end_date', '>=', date('Y-m-d'));
              
        $whereArray = [];
        $whereBetweenArray = [];
        $formData = [];
        if ($filtername != '') {
            $whereArray[] = ['c.name', 'LIKE', '%' . $filtername . '%'];

            $formData['filtername'] = $filtername;
        }
        if ($filtertype != '') {
            $whereArray[] = ['c.type', '=', $filtertype];
            $formData['filtertype'] = $filtertype;
        }
        if ($filtergroup != '') {
            $whereArray[] = ['c.customer_group', '=', $filtergroup];
            $formData['filtergroup'] = $filtergroup;
        }
        
        if (count($whereArray) > 0) {
            $query->where($whereArray);
            Session::put('renewaldaysfilter_' . Auth::user()->id, json_encode($whereArray));
            Session::put('renewaldaysfilterform_' . Auth::user()->id, json_encode($formData));
        }
        if ($filterDays != '') {
             if($filterDays ==20){
              $query->whereBetween(DB::raw('DATEDIFF(p.end_date,now())'), [1, 20]);   
             } else if($filterDays ==40) {
              $query->whereBetween(DB::raw('DATEDIFF(p.end_date,now())'), [20, 40]);   
             } else {
              $query->whereBetween(DB::raw('DATEDIFF(p.end_date,now())'), [40, 60]);   
             }
             
            $formData['filterdays'] = $filterDays;
        }
        $renewallist = $query->orderBy('datediff', 'asc')                        
                       
                        ->paginate(12)->setPath(route('renewalnotificationlist'));

       
        $userGroup = array('' => '--- not set ---', 'corporate' => 'Corporate', 'retail' => 'Retail', 'sme' => 'SME');
        $lineofbusiness = DB::table('line_of_business')->distinct()->where('status', '1')->orderBy('title')->pluck('title', 'id')->toArray();
        $data = array('renewalDatas' => $renewallist, 'usergroup' => $userGroup,'formData' => $formData ,'lineofbusiness' => $lineofbusiness);
        return view('Renewal/notificationlist', $data);
    }
    
    
    public function saveRenewalrequest(Request $request) {
    
    
        $requestId = substr("CRM-" . uniqid(date("Ymdhi")), 0, -12);
        $crmMainObj = new crmMain();
        $crmMainObj->customer_id = $request->get('customer_id');
        $crmMainObj->crm_request_id = $requestId;
        //ASSIGN REQUEST TO SALES LEAD
        //FIND A SALES LEAD
        $assignUser = DB::table('users')->select('id')->where('status', '1')->where('roles', 'like', "%ROLE_SALES_LEAD%")->orderBy('id')->first();
        if( $assignUser && count(get_object_vars($assignUser)) > 0){
          $crmMainObj->assigned_to = $assignUser->id ;  
        }
            
    

        $crmMainObj->user_id = Auth::user()->id;
        $crmMainObj->status = 2;
        $crmMainObj->crm_line_of_business = $request->get('request_lineof_business');
        $crmMainObj->type = 3;
        $crmMainObj->priority = 3;
        $crmMainObj->created_date = date('Y-m-d h:i');
        $crmMainObj->updated_date = date('Y-m-d h:i');
        $crmMainObj->policy_id = $request ->get('policy_id');
        $crmMainObj->save();
        $crmMainId = $crmMainObj->getKey();
        //Request detail saving area
        $crmRequestObj = new crmRequest();
        $crmRequestObj->crm_id = $crmMainId;
        $crmRequestObj->description = $request->get('request_description');
        
        $crmRequestObj->save();
        
        //File upload area
        
        if($request->hasFile('document_file')) {
            
          $this->requestFileUpload($request,$crmMainId ); 
          
        }
       
        //MAIL SENDING AREA
        // $users = DB::table('users')->select('email', 'name')->where('status', '1')->where('roles', 'like', "%ROLE_SALES_LEAD%")->orderBy('id')->first();

        $url = route('customeroverview', ['customerId' => $request->get('customer_select')]);
        
        return back()->with(['success' => 'Successfully added renewal request!']);
        
        
        
//        $userObj = DB::table('users')->find($request->get('user_select'));
//        $data = array('name' => $users->name, 'link' => $url, 'Request_no' => $requestId, 'username' => $userObj->name);
//        $templatename = 'emails.notification';
//        $maidetails['to'] = $users->email;
//        $maidetails['name'] = $users->name;
//        $maidetails['subject'] = "New quote request is raised CRM no: " . $requestId;
//        $this->send_email($maidetails, $data, $templatename);
        
    }
    /**
     * Function to upload renewal request files
     * @param type $request
     * @param type $requestId
     */
    private function requestFileUpload($request, $requestId) {
        
        $files = $request->file('document_file');
        $insertArray = [];
        $type = 6;
        $customerId = $request->get('customer_id');
        $filename = [];
        $datetime = date('Y-m-d h:i');
        File::isDirectory('uploads/' . $customerId . "/document/") or File::makeDirectory('uploads/' . $customerId . "/document/", 0777, true, true);
        
        foreach ($files as $uploadedfile) {
            $destinationPath = 'uploads/' . $customerId . "/document/";
            $path_parts = pathinfo($uploadedfile->getClientOriginalName());
            $newfilename = $path_parts['filename'] . "_" . date('Ymdhis') . '.' . $path_parts['extension'];
            $filename[] = $newfilename;
            $uploadedfile->move($destinationPath, $newfilename);
            $insertArray[] = array("customer_id" => $customerId,
                "filename" => $newfilename,
                "document_type" => $type,
                "comment" => 'new files',
                "uploaded_by" => Auth::user()->id,
                "uploaded_at" => $datetime,
                "crm_id" => $requestId
            );
        }

        $customerObj = new customer();
        if (count($insertArray) > 0) {
            DB::table('customer_crm_documents')->insert($insertArray); // Query Builder approach
            //insert log entry
            $logarray = array("crm_id" => $requestId,
                "title" => "Following documents are uploaded: " . implode(',', $filename),
                "old_value" => '',
                "edited_by" => Auth::user()->id,
                "edited_at" => $datetime);
            $customerObj->logInsert('crm_log', $logarray);
           
        }  
        
    }

}
