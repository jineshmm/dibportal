<?php

namespace App\Http\Controllers\Complaint;

use Illuminate\Http\Request;
use Response;
use Illuminate\Support\Facades\DB;
use Auth;
use Mail;
use App;
use App\customer;
use App\policy;
use App\policyComplaint;
use App\Http\Controllers\Controller;
use Session;
use File;
use Hamcrest\Core\IsNullTest;

class ComplaintController extends Controller {
/**
 * To list complaints
 * @return type
 */
    public function listComplaints() {
        $allComplaintsDetails = DB::table('policy_complaints as pc')
                        ->join('policies as p', 'pc.policy_id', '=', 'p.id')
                        ->leftJoin('users as u', 'u.id', '=', 'pc.handle_user')
                        ->leftJoin('customers as c', 'c.id', '=', 'pc.client_id')
                        ->leftJoin('users as us', 'us.id', '=', 'pc.created_by')
                        ->select('pc.*', 'u.name as handleUser', 'us.name as userName', DB::raw("(case pc.request_status when 1 then 'Open'  else 'Closed'   end) AS statusString"), DB::raw("(case pc.request_validity when 1 then 'Valid'  else 'Invalid'   end) AS complaintValidity"), 'p.policy_number', DB::raw("(case pc.compliant_type when 1 then 'Deletion delay' when 2 then 'Treatment rejection by provider' else 'Others'   end) AS complaintType"), "c.name as clientName")
                        ->orderBy('p.updated_at')->get();

        $data = array('complaintDetails' => $allComplaintsDetails, "title" => 'Complaints');
        return view('Complaint/complaintList', $data);
    }
/**
 * To add complaint
 * @return type
 */
    public function addComplaints() {

        $allCustomer =  DB::table('customers as c') 
                ->join('policies as p','p.customer_id','=','c.id')->where('p.policy_status', '2')->orderBy('c.created_at')->orderBy('c.name')->groupBy('c.id')->pluck('c.name', 'c.id')->toArray();
    

        $allUsers = DB::table('users')->distinct()->where('status', '1')->orderBy('name')->pluck('name', 'id')->toArray();

        $allPolicies = DB::table('policies')->where('policy_status', 1)->get();


        $requestValidity = array(1 => 'Valid', 2 => 'Invalid');
        $complaintType = array(1 => 'Deletion delay', 2 => 'Treatment rejection by provider', 3 => 'Others');
        $data = array('policies' => $allPolicies, 'customers' => $allCustomer, 'users' => $allUsers, 'complaintType' => $complaintType, 'requestValidity' => $requestValidity);

        $returnHTML = view('Complaint/createcomplaint', $data)->render();
        return response()->json(array('status' => true, 'content' => $returnHTML));
    }
/**
 * To save complaint details
 * @param Request $request
 * @return type
 */
    public function saveComplaintDetails(Request $request) {
        $policyComplaintObj = new policyComplaint();
        $policyComplaintObj->client_id = $request->get('complaint_client');
        $policyComplaintObj->policy_id = $request->get('complaint_policy');
        $policyComplaintObj->compliant_type = $request->get('complaint_type');
        $policyComplaintObj->requested_date = date('Y-m-d', strtotime($request->get('complaint_request_date')));
        $policyComplaintObj->bill_amount = $request->get('complaint_bill_amount');
        $policyComplaintObj->approve_amount = $request->get('complaint_approve_amount');
        $policyComplaintObj->request_validity = $request->get('complaint_validity');
        $policyComplaintObj->request_status = $request->get('complaint_status');
        $policyComplaintObj->remarks = $request->get('complaint_remarks');
        $policyComplaintObj->closed_date = empty($request->get('complaint_closed_date')) ? null : date('Y-m-d', strtotime($request->get('complaint_closed_date')));
        $policyComplaintObj->handle_user = $request->get('complaint_handle_user');
        $policyComplaintObj->created_by = Auth::user()->id;
        $policyComplaintObj->created_at = date('Y-m-d h:i:s');
        $policyComplaintObj->updated_at = date('Y-m-d h:i:s');
        $policyComplaintObj->save();

        return back()->with(['success' => 'Successfully create complaints details!!!!']);
    }

    /**
     * To get the complaint edit form
     * @param integer $complaintId
     * @return type
     */
    public function editComplaintForms($complaintId) {
        $allCustomer = DB::table('customers')->where('status', '1')->orderBy('created_at')->pluck('name', 'id')->toArray();
        $allUsers = DB::table('users')->distinct()->where('status', '1')->orderBy('name')->pluck('name', 'id')->toArray();

        $allPolicies = DB::table('policies')->where('policy_status', 1)->pluck('policy_number', 'id')->toArray();
        $requestValidity = array(1 => 'Valid', 2 => 'Invalid');
        $complaintType = array(1 => 'Deletion delay', 2 => 'Treatment rejection by provider', 3 => 'Others');


        $complaintDetails = DB::table('policy_complaints')->where('id', $complaintId)->first();
        $data = array('policies' => $allPolicies, 'customers' => $allCustomer, 'users' => $allUsers, 'complaintType' => $complaintType, 'requestValidity' => $requestValidity, 'complaintDetails' => $complaintDetails);

        $returnHTML = view('Complaint/createcomplaint', $data)->render();
        return response()->json(array('status' => true, 'content' => $returnHTML));
    }

    /**
     * To update complaint details
     * @param Request $request
     * @param integer $complaintId
     * @return type
     */
    public function updateComplaintDetails(Request $request, $complaintId) {
        $policyComplaintObj = policyComplaint::find($complaintId);
        $policyComplaintObj->client_id = $request->get('complaint_client');
        $policyComplaintObj->policy_id = $request->get('complaint_policy');
        $policyComplaintObj->compliant_type = $request->get('complaint_type');
        $policyComplaintObj->requested_date = date('Y-m-d', strtotime($request->get('complaint_request_date')));
        $policyComplaintObj->bill_amount = $request->get('complaint_bill_amount');
        $policyComplaintObj->approve_amount = $request->get('complaint_approve_amount');
        $policyComplaintObj->request_validity = $request->get('complaint_validity');
        $policyComplaintObj->request_status = $request->get('complaint_status');
        $policyComplaintObj->remarks = $request->get('complaint_remarks');
        $policyComplaintObj->closed_date = empty($request->get('complaint_closed_date')) ? null : date('Y-m-d', strtotime($request->get('complaint_closed_date')));
        $policyComplaintObj->handle_user = $request->get('complaint_handle_user');
        $policyComplaintObj->updated_at = date('Y-m-d h:i:s');
        $policyComplaintObj->save();

        return back()->with(['success' => 'Successfully update complaints details!!!!']);
    }

    /**
     * To delete complaints
     * @param integer $complaintId
     * @return type
     */
    public function deleteComplaint($complaintId) {
        DB::table('policy_complaints')->where('id', $complaintId)->delete();
        Session::flash('success', 'Successfully deleted complaint!!!!');

        return response()->json(array('status' => true));
    }

    /**
     * To display the complaints detail
     * @param integer $complaintId
     * @return type
     */
    public function complaintOverview($complaintId) {
        $allComplaintsDetails = DB::table('policy_complaints as pc')
                        ->join('policies as p', 'pc.policy_id', '=', 'p.id')
                        ->leftJoin('users as u', 'u.id', '=', 'pc.handle_user')
                        ->leftJoin('customers as c', 'c.id', '=', 'pc.client_id')
                        ->leftJoin('users as us', 'us.id', '=', 'pc.created_by')
                        ->where('pc.id', '=', $complaintId)
                        ->select('pc.*', 'u.name as handleUser', 'us.name as userName', DB::raw("(case pc.request_status when 1 then 'Open'  else 'Closed'   end) AS statusString"), DB::raw("(case pc.request_validity when 1 then 'Valid'  else 'Invalid'   end) AS complaintValidity"), 'p.policy_number', DB::raw("(case pc.compliant_type when 1 then 'Deletion delay' when 2 then 'Treatment rejection by provider' else 'Others'   end) AS complaintType"), "c.name as clientName")
                        ->orderBy('p.updated_at')->first();
        $headTitle = 'Complaint overview';
        $breadcrumbDetails = array('url' =>action('Dashboard\DashboardController@dashboardComplaintList'),'title' =>'Complaints');
        $data = array('complaintDetails' => $allComplaintsDetails, "headTitle" => $headTitle,'breadcrumb'=>$breadcrumbDetails);
        return view('Complaint/overview', $data);
    }

    /**
     * To get the policies of a client
     * @param Request $request
     * @return type
     */
    public function getClientPolicies(Request $request) {
        
        $whereArray[] = ['customer_id', '=', $request->get('customer_id')];
        $allFlag = $request->get('allFlag',false);
        
        $selectedOption = $request->get('selectedoption');
        if($allFlag){
         $allPolicies = DB::table('policies')->where($whereArray)->whereIn('policy_status',[2,4])->get();  
        } else {
          $allPolicies = DB::table('policies')->where($whereArray)->where('policy_status',2)->get(); 
        }
        
     
        $optionstring = '<option value="">-- Select policy--</option>';
        if (count($allPolicies) > 0) {
            foreach ($allPolicies as $policies) {
                if ($selectedOption == $policies->id) {
                    $optionstring .= "<option value='" . $policies->id . "' selected='selected'>" . $policies->policy_number . "</option>";
                } else {
                    $optionstring .= "<option value='" . $policies->id . "'>" . $policies->policy_number . "</option>";
                }
            }
        }

        return response()->json(array('status' => true, 'optionstring' => $optionstring));
    }

}
