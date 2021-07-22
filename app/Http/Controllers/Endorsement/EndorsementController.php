<?php

namespace App\Http\Controllers\Endorsement;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use App;
use App\Mail\dpportalmail;
use Mail;
use App\customer;
use App\policy;
use App\PolicyInstallment;
use App\Http\Controllers\Controller;
use Session;
use File;
use Illuminate\Support\Facades\Date;

/**
 * To handle the policy related data
 * CRM Request Types are
 * Addition ,   CCHI Activation, Claim approval/Settlement ,Deletion ,Downgrade ,Updated member list ,Plate No Amendment ,Card Replacment ,CCHI Upload Status List ,MC Certificate ,Name Amendment ,
  Card Printer Request ,Invoices Request ,Upgrade, REQUEST ,INQUIRY ,announcement ,REQUEST SIGN ,Others

 * 
 * 
 */
class EndorsementController extends Controller {

    /**
     * To get new policy add page 
     * @param integer $customerId
     * @param integer $crmId
     * @param integer $quoteId
     * @return type
     */
    public function addEndorsement($crmId) {
        $crmrequestObj = App\EndorsementCrm::find($crmId);
        $type =1;
        if($crmrequestObj->type ==3) {
          $type =3;  
        } else if($crmrequestObj->type ==4) {
           $type =4; 
        } else if($crmrequestObj->type ==9) {
           $type =9; 
        } else {
          $type =1;  
        }
        $policyObj = policy::find($crmrequestObj->policy_id);

        $headTitle = 'Add an endorsement';
        $data = array('endorsementtype' => $type, 'policy_id' => $crmrequestObj->policy_id, 'headTitle' => $headTitle, 'endorsementVat' => $policyObj->tax, 'crmId' => $crmId);
        return view('Endorsement/addEndorsementForm', $data);
    }

    /**
     * To save endorsement details
     * @param Request $request
     * @param type $policyId
     */
    public function saveEndorsementDetails(Request $request, $policyId) {

        $amount = 0;
        $vatAmount = 0;
        $crmId = $request->get('endorsement_crm_id', null);

        switch ($request->get('endorsement_type')) {
            case 1:case 9:
                $amount = $request->get('policy_endorsement_premium');
                $vatAmount = ($request->get('policy_endorsement_premium') > 0) ? (1 * floatval(($request->get('policy_endorsement_premium') * $request->get('endorsement_tax')) / 100)) : 0;
                break;
            case 3:case 4:
                $amount = -1 * $request->get('policy_endorsement_premium');
                $vatAmount = ($request->get('policy_endorsement_premium') > 0) ? (-1 * floatval(($request->get('policy_endorsement_premium') * $request->get('endorsement_tax')) / 100)) : 0;
                break;
        }
        $vatDeatil['vat'] = ($request->get('policy_endorsement_premium') > 0) ? $request->get('endorsement_tax') : 0;
        $vatDeatil['vat_amount'] = $vatAmount;
        $insertArray = array("amount" => floatval($amount),
            "endorsement_number" => $request->get('policy_endorsement_no'),
            "endorsement_type" => $request->get('endorsement_type'),
            "start_date" => date('Y-m-d', strtotime($request->get('policy_endorsement_date_start'))),
            "issue_date" => date('Y-m-d', strtotime($request->get('policy_endorsement_date_issue'))),
            "due_date" => date('Y-m-d', strtotime($request->get('policy_endorsement_due_date'))),
            "added_by" => Auth::user()->id,
            "created_at" => date('Y-m-d h:i:s'),
            "policy_id" => $policyId,
            "vat_percentage" => $vatDeatil['vat'],
            "vat_amount" => $vatDeatil['vat_amount'],
            "endorsement_count" => $request->get('policy_endorsement_count'),
            "endorsement_status" => 1,
            "endorsement_crm_id" => $crmId
        );
        $insertId = DB::table('policy_endorsement')->insertGetId($insertArray);
        $dueDate = $request->get('policy_endorsement_due_date');

        //installment insert area
        //find installment interval

        $whereArray[] = ['start_date', '<=', date('Y-m-d', strtotime($request->get('policy_endorsement_date_start')))];
        $whereArray[] = ['due_date', '>=', date('Y-m-d', strtotime($request->get('policy_endorsement_date_start')))];
        $whereArray[] = ['installment_type', '=', 1];
        $whereArray[] = ['policy_id', '=', $policyId];
        $intallmentData = DB::table('policy_intallment')->select('id', 'end_date', 'due_date', 'amount', 'collect_by')->where($whereArray)->first();
        $installmentId = $this->insertInstallmentEntry($intallmentData, $request->get('policy_endorsement_date_start'), $amount, $policyId, $insertId, $vatDeatil, $dueDate);
        //Calculate commission
        if ($amount > 0) {
            $this->calculateEndorsementCommission($policyId, $installmentId, $amount);
        }

        //Notification adding area
        $policydetails = DB::table('policies')->select('policy_number', 'customer_id')->where('id', $policyId)->first();
        $notificationArray['message'] = 'New endorsement was added against Policy:' . $policydetails->policy_number;
        $notificationArray['created_date'] = date('Y-m-d');
        $notificationArray['department'] = 'OPERATION';
        $notificationArray['message_type'] = 'endorsement';
        DB::table('notification_details')->insert($notificationArray);

        //UPLOAD INVOICE DATA UNDER POLICY
        if ($request->hasFile('invoice_file')) {

            $this->endorsementInvoiceUpload($request, $policyId, $policydetails->customer_id, $insertId);
        }
        
        //SEND MAIL TO OPERATION 
        $overviewData = DB::table('crm_endorsement as ecr')                       
                        ->select('ecr.request_id','ecr.id','ecr.policy_id')
                        ->where('ecr.id', '=', $crmId)->first();
             $users = DB::table('users')->select('email')->where('status', '1')->where('roles','like',"%ROLE_OPERATION_SUPERVISER%")->orderBy('name')->first();
             
             $this->sendMail($users, $overviewData ,'Verify insly entered flag of endorsement request:'.$overviewData->request_id);
             
             
             $financeuser = DB::table('users')->select('email', 'name')->where('status', '1')->where('roles', 'like', "%ROLE_FINANCE_ADMIN%")->orderBy('id')->first();
                $url = route('overviewendorsement', ['endId' => $insertId]);
               $data = array('name' =>'Finance', 'link' =>$url, 'endorsement_no' =>$request->get('policy_endorsement_no'),'username' =>$financeuser->name,'status'=>'Posted');
               $templatename = 'emails.endorsementpostnotification';
               $maidetails['to'] = $financeuser->email;
               $maidetails['name'] = $financeuser->name;
               $maidetails['subject'] = "Endorsement ".$request->get('policy_endorsement_no') ." detail was posted to finance department";
               $this->send_email($maidetails, $data, $templatename); 



        Session::flash('success', 'Successfully added endorsement!!!!');
        return redirect()->route('policyoverview', ['policyId' => $policyId]);
    }

    /**
     * To insert intallment entry
     * @param type $intallmentData
     * @param type $startDate
     * @param type $amount
     */
    private function insertInstallmentEntry($intallmentData, $startDate, $amount, $policyId, $insertId, $vatDeatil, $dueDate) {
        $policyObj = policy::find($policyId);
        if (!empty($intallmentData)) {
            $insertArray = array("policy_id" => $policyId,
                "start_date" => date('Y-m-d', strtotime($startDate)),
                "end_date" => date('Y-m-d', strtotime($intallmentData->end_date)),
                "due_date" => date('Y-m-d', strtotime($dueDate)),
                "amount" => floatval($amount),
                "parent_id" => $intallmentData->id,
                "collect_by" => $intallmentData->collect_by,
                "installment_type" => 2,
                "endorsement_id" => $insertId,
                "vat_percentage" => $vatDeatil['vat'],
                "vat_amount" => $vatDeatil['vat_amount']
            );
        } else {
            $insertArray = array("policy_id" => $policyId,
                "start_date" => date('Y-m-d', strtotime($startDate)),
                "end_date" => date('Y-m-d', strtotime($policyObj->end_date)),
                "due_date" => date("Y-m-d", strtotime($dueDate)),
                "amount" => floatval($amount),
                "collect_by" => $policyObj->collection_type,
                "installment_type" => 2,
                "endorsement_id" => $insertId,
                "vat_percentage" => $vatDeatil['vat'],
                "vat_amount" => $vatDeatil['vat_amount']
            );
        }
        $newInstallmentId = DB::table('policy_intallment')->insertGetId($insertArray);
        $dateTime = date('Y-m-d h:i:s');
        //Change in Endorsement amount value

        $currentValue = $policyObj->endorsement_amount;
        $policyObj->endorsement_amount = $currentValue + $amount;
        $policyObj->updated_at = $dateTime;
        $policyObj->save();
        //Endorsement Log
        $logArray = array("policy_id" => $policyId,
            "kind" => 'New Endorsement:Amount ' . $amount,
            "edited_by" => Auth::user()->id,
            "oldvalue" => '',
            "edited_at" => $dateTime);

        DB::table('policy_log')->insert($logArray);

        return $newInstallmentId;
    }

    /**
     * To list endorsement details
     * @param integer $policyId
     * @return type
     */
    public function listEndorsementDetails($policyId) {

        $endorsementDetails = DB::table('policy_endorsement as pe')
                        ->join('policies as p', 'pe.policy_id', '=', 'p.id')
                        ->select('pe.*', DB::raw("(case pe.endorsement_type when 1 then 'Addition' when 3 then 'Deletion'  when 4 then 'Downgrade' when 9 then 'Upgrade'   end) AS typeString"), DB::raw('DATE_FORMAT(pe.start_date, "%d.%m.%Y") as formatted_startDate'), DB::raw('DATE_FORMAT(pe.issue_date, "%d.%m.%Y") as formatted_issueDate'), DB::raw("(case pe.endorsement_status when 3 then 'Rejected' when 2 then 'Approved' when 1 then 'Waiting for approval' end) AS statusString"), 'p.end_date as expiryDate')
                        ->where('pe.policy_id', '=', $policyId)
                        ->orderBy('pe.created_at')->get();

        $data = array('endorsementDetails' => $endorsementDetails, "title" => 'Endorsements', 'policyId' => $policyId);


        $returnHTML = view('Endorsement/endorsementList', $data)->render();

        return response()->json(array('status' => true, 'content' => $returnHTML));
    }

    /**
     * To create endorsement details
     * @param integer $policyId
     * @return type
     */
    public function createEndorsementDetails($policyId) {
        $typeArray = [1 => 'Addition', 3 => 'Deletion', 4 => 'Downgrade', 9 => 'Upgrade'];
        $data = array('typeArray' => $typeArray, 'policy_id' => $policyId);
        $returnHTML = view('Endorsement/createendorsement', $data)->render();
        return response()->json(array('status' => true, 'content' => $returnHTML));
    }

    /**
     * 
     * @param integer $policyId
     * @param integer $endorsementId
     * @return type
     */
    public function editEndorsementDetails($policyId, $endorsementId) {
        $endorsementDetails = DB::table('policy_endorsement as pe')->where('id', $endorsementId)->select('pe.*')->first();
        $typeArray = [1 => 'Addition', 3 => 'Deletion', 4 => 'Downgrade', 9 => 'Upgrade'];
        $data = array('typeArray' => $typeArray, 'policy_id' => $policyId, 'endorsementData' => $endorsementDetails, 'endorsementId' => $endorsementId);
        $returnHTML = view('Endorsement/createendorsement', $data)->render();
        return response()->json(array('status' => true, 'content' => $returnHTML));
    }

    /**
     * 
     * @param Request $request
     * @param integer $policyId
     * @param integer $endorsementId
     * @return type
     */
    public function updateEndorsementDetails(Request $request, $policyId, $endorsementId) {
        $endorsementDetails = DB::table('policy_endorsement as pe')->where('pe.id', $endorsementId)->select('pe.*')->first();
        $oldEndorsementAmount = $endorsementDetails->amount;


        $amount = 0;
        $vatAmount = 0;
        switch ($request->get('endorsement_type')) {
            case 1:case 9:
                $amount = $request->get('policy_endorsement_premium');
                $vatAmount = ($request->get('policy_endorsement_premium') > 0) ? ($request->get('policy_endorsement_premium') * $request->get('endorsement_tax') / 100) : 0;
                break;
            case 3:case 4:
                $amount = -1 * $request->get('policy_endorsement_premium');
                $vatAmount = ($request->get('policy_endorsement_premium') > 0) ? -1 * (($request->get('policy_endorsement_premium') * $request->get('endorsement_tax')) / 100) : 0;
                break;
        }
        $vatDeatil['vat'] = ($request->get('policy_endorsement_premium') > 0) ? $request->get('endorsement_tax') : 0;
        $vatDeatil['vat_amount'] = $vatAmount;
        $dueDate = $request->get('policy_endorsement_due_date');
        $updateArray = array("amount" => floatval($amount),
            "endorsement_number" => $request->get('policy_endorsement_no'),
            "endorsement_type" => $request->get('endorsement_type'),
            "start_date" => date('Y-m-d', strtotime($request->get('policy_endorsement_date_start'))),
            "issue_date" => date('Y-m-d', strtotime($request->get('policy_endorsement_date_issue'))),
            "due_date" => date('Y-m-d', strtotime($request->get('policy_endorsement_due_date'))),
            "vat_percentage" => $vatDeatil['vat'],
            "vat_amount" => floatval($vatDeatil['vat_amount']),
            "endorsement_count" => $request->get('policy_endorsement_count')
        );

        DB::table('policy_endorsement')->where('id', $endorsementId)->update($updateArray);
        $dateTime = date('Y-m-d h:i:s');
        //Endorsement Log
        $logArray = array("policy_id" => $policyId,
            "kind" => 'Endorsementdata was updated: ' . $request->get('policy_endorsement_no'),
            "edited_by" => Auth::user()->id,
            "oldvalue" => '',
            "edited_at" => $dateTime);
        DB::table('policy_log')->insert($logArray);

        //Policy endorsement amount update
        $policyObj = policy::find($policyId);
        $currentValue = $policyObj->endorsement_amount;
        $policyObj->endorsement_amount = floatval($currentValue) + floatval((-1 * $oldEndorsementAmount) + $amount);
        $policyObj->updated_at = $dateTime;
        $policyObj->save();

        //update installment entry
        DB::table('policy_intallment')->where('endorsement_id', $endorsementId)->delete();

        $whereArray[] = ['start_date', '<=', date('Y-m-d', strtotime($request->get('policy_endorsement_date_start')))];
        $whereArray[] = ['due_date', '>=', date('Y-m-d', strtotime($request->get('policy_endorsement_date_start')))];
        $whereArray[] = ['installment_type', '=', 1];
        $whereArray[] = ['policy_id', '=', $policyId];

        $intallmentData = DB::table('policy_intallment')->select('id', 'end_date', 'due_date', 'amount', 'collect_by')->where($whereArray)->first();
        $installmentId = $this->insertInstallmentEntry($intallmentData, $request->get('policy_endorsement_date_start'), $amount, $policyId, $endorsementId, $vatDeatil, $dueDate);
        //Calculate commission
        if ($amount > 0) {
            $this->calculateEndorsementCommission($policyId, $installmentId, $amount);
        }

        return back()->with(['success' => 'Successfully update endorsement details!!!!', 'overviewtabselected' => 'crm']);
    }

    /**
     * To delete endorsement amount
     * @param integer $policyId
     * @param integer $endorsementId
     * @return type
     */
    public function deleteEndorsement($policyId, $endorsementId) {
        $endorsementObj = DB::table('policy_endorsement')->select('*')->where('id', $endorsementId)->first();
        $amount = floatval($endorsementObj->amount);
        $endorsementNumber = $endorsementObj->endorsement_number;
        $dateTime = date('Y-m-d h:i:s');
        DB::table('policy_endorsement')->where('id', $endorsementId)->delete();

        //Endorsement Log
        $logArray = array("policy_id" => $policyId,
            "kind" => 'Endorsementdata was deleted: ' . $endorsementNumber,
            "edited_by" => Auth::user()->id,
            "oldvalue" => '',
            "edited_at" => $dateTime);
        DB::table('policy_log')->insert($logArray);

        //Policy endorsement amount update
        $policyObj = policy::find($policyId);
        $currentValue = $policyObj->endorsement_amount;
        $policyObj->endorsement_amount = floatval($currentValue) + floatval(-1 * $amount);
        $policyObj->updated_at = $dateTime;
        $policyObj->save();



        //Remove installment data        

        Session::flash('success', 'Successfully deleted endorsement!!!!');
        return response()->json(array('success' => true));
    }

    /**
     * To calculate endorsement commission
     * @param type $policyId
     * @param type $endorsementId
     */
    private function calculateEndorsementCommission($policyId, $installmentId, $amount) {

        $policyCommissionDetails = DB::table('policy_commission')->select('*')->where('policy_id', '=', $policyId)->groupBy('distributor_type')->orderBy('distributor_type', 'ASC')->get();
        $insertArray = [];
        $datetime = date('Y-m-d h:i:s');
        $companyCommisison = 0;
        if (count($policyCommissionDetails) > 0) {
            foreach ($policyCommissionDetails as $commissionDetails) {
                if ($commissionDetails->commission_type == 0) {
                    $percentage = floatval($commissionDetails->percentage);
                    $commissionAmount = ($commissionDetails->distributor_type == 'diamond') ? floatval(($amount * $percentage) / 100) : floatval(($companyCommisison * $percentage) / 100);
                    $companyCommisison = ($commissionDetails->distributor_type == 'diamond') ? $commissionAmount : $companyCommisison;
                    $insertArray[] = array("policy_id" => $policyId,
                        "distributor_type" => $commissionDetails->distributor_type,
                        "commission_type" => $commissionDetails->commission_type,
                        "percentage" => $percentage,
                        "amount" => $commissionAmount,
                        "added_date" => $datetime,
                        "sales_person_id" => $commissionDetails->sales_person_id,
                        "installment_id" => $installmentId
                    );
                }
            }

            if (count($insertArray) > 0) {
                DB::table('policy_commission')->insert($insertArray);
                $logArray[] = array("policy_id" => $policyId,
                    "kind" => 'Policy commission details was changed',
                    "edited_by" => Auth::user()->id,
                    "oldvalue" => '',
                    "edited_at" => $datetime);

                DB::table('policy_log')->insert($logArray);
            }
        }
    }

    /**
     * To display all endorsement
     * @return type
     */
    public function viewAllEndorsementRequest() {

        $endorsementRequest = DB::table('crm_endorsement as ecr')
                        ->join('policies as p', 'ecr.policy_id', '=', 'p.id')
                        ->leftJoin('users as u', 'u.id', '=', 'ecr.created_by')
                        ->select('ecr.*', 'u.name as userName', DB::raw("(case ecr.status when 3 then 'Closed' when 2 then 'Under process'  when 4 then 'Pending from insurer' when 5 then 'pending from client'  else 'Open'   end) AS statusString"), 'p.policy_number')
                        ->where('ecr.status', '!=', 3)
                        ->orderBy('ecr.updated_at')->get();



        $data = array('endorsementDetails' => $endorsementRequest, "title" => 'Endorsement request');
        return view('Endorsement/endorsementrequestList', $data);
    }

    /**
     * 
     * @param integer $policyId
     * @return type
     */
    public function newEndorsementRequest() {
        $typeArray = [1 => 'Addition', 'CCHI', 'Deletion', 'Downgrade', 'Card Replacment', 'Name Amendment', 'Card Printer Request', 'Invoices Request', 'Upgrade', 'Others'];
        $allCustomer = DB::table('customers as c')
                        ->join('policies as p', 'p.customer_id', '=', 'c.id')->where('p.policy_status', '2')->orderBy('c.name')->groupBy('c.id')->pluck('c.name', 'c.id')->toArray();
        $allPolicies = DB::table('policies')->where('policy_status', 2)->pluck('policy_number', 'id')->toArray();
        $data = array('typeArray' => $typeArray, 'allPolicies' => $allPolicies, 'customers' => $allCustomer);
        $returnHTML = view('Endorsement/createendorsementrequest', $data)->render();
        return response()->json(array('status' => true, 'content' => $returnHTML));
    }

    /**
     * To save endorsement request
     * @param Request $request
     * @return type
     */
    public function saveEndorsementRequest(Request $request) {
        $requestId = substr("ER-" . uniqid(date("Ymdhi")), 0, -12);
        $insertId = DB::table('crm_endorsement')->insertGetId(array('policy_id' => $request->get('request_policy'), 'description' => $request->get('request_comment'), 'type' => $request->get('request_type'), 'status' => 1, 'created_at' => date('Y-m-d h:i:s'), 'updated_at' => date('Y-m-d h:i:s'), 'created_by' => Auth::user()->id, 'request_id' => $requestId, 'assign_to' => Auth::user()->id));

        return redirect()->route('overviewendorsementcrmrequest', ['policyId' => $request->get('request_policy'), 'requestId' => $insertId])->with('success', 'Successfully created endorsement request!!!!');
    }

    /**
     * To delete endorsement request
     * @param integer $requestId
     * @return type
     */
    public function deleteEndorsementRequest($requestId) {
        DB::table('crm_endorsement')->where('id', $requestId)->delete();

        Session::flash('success', 'Successfully deleted endorsement request!!!!');
        return response()->json(array('status' => true));
    }

    /**
     * To save endorsement request
     * @param Request $request
     * @return type
     */
    public function endorsementIssue(Request $request) {
        $dateObj = date("Y-m-d h:i");
        $updateArray = array("endorsement_status" => 2, "updated_date" => $dateObj);
        $endorsementCrmDetails = DB::table('policy_endorsement as pe')
                        ->join('crm_endorsement as en', 'en.id', '=', 'pe.endorsement_crm_id')->select('pe.endorsement_crm_id')->where('pe.id', $request->get('endorsement_id'))->first();


        DB::table('policy_endorsement')->where('id', $request->get('endorsement_id'))->update($updateArray);

        if ($endorsementCrmDetails && count(get_object_vars($endorsementCrmDetails)) > 0) {
            DB::table('crm_endorsement')->where('id', $endorsementCrmDetails->endorsement_crm_id)->update(array('status' => 10, 'updated_at' => $dateObj));
            $crmlogArray = array('crm_id' => $endorsementCrmDetails->endorsement_crm_id, 'kind' => 'Crm request status was changed to Close', 'old_value' => 'Resolved', 'updated_by' => Auth::user()->id, 'updated_at' => $dateObj);
            DB::table('endorsement_crm_log')->insert($crmlogArray);         
          
        }

        //Endorsement Log
        $logArray = array("policy_id" => $request->get('endorsement_policy_id'),
            "kind" => 'Endorsement was issued: ' . $request->get('endorsement_number'),
            "edited_by" => Auth::user()->id,
            "oldvalue" => '',
            "edited_at" => $dateObj);
        DB::table('policy_log')->insert($logArray);
        return back()->with(['success' => 'Successfully issued endorsement!']);
    }

    /**
     * 
     * @param type $request
     * @param type $policyId
     * @param type $customerId
     * @param type $endorsementId
     */
    private function endorsementInvoiceUpload($request, $policyId, $customerId, $endorsementId) {

        $files = $request->file('invoice_file');
        $insertArray = [];
        $type = 6;
        $comment = "Endorsement invoice for " . $request->get('policy_endorsement_no');
        $filename = [];
        $datetime = date('Y-m-d h:i');
        $destinationPath = 'uploads/' . $customerId . "/document/" . $policyId . "/";

        foreach ($files as $uploadedfile) {

            $path_parts = pathinfo($uploadedfile->getClientOriginalName());
            $newfilename = $path_parts['filename'] . "_" . date('Ymdhis') . '.' . $path_parts['extension'];
            $filename[] = $newfilename;
            $uploadedfile->move($destinationPath, $newfilename);
            $insertArray[] = array("customer_id" => $customerId,
                "file_name" => $newfilename,
                "type" => $type,
                "comment" => $comment,
                "endorsement_id" => $endorsementId,
                "upload_by" => Auth::user()->id,
                "upload_at" => $datetime,
                "policy_id" => $policyId
            );
        }

        if (count($insertArray) > 0) {
            DB::table('dp_customer_document')->insert($insertArray); // Query Builder approach
            //insert log entry
            $logarray = array("crm_id" => $request->get('endorsement_crm_id'),
                "kind" => "Endorsement invoice for " . $request->get('policy_endorsement_no') . " are uploaded: " . implode(',', $filename),
                "old_value" => '',
                "updated_by" => Auth::user()->id,
                "updated_at" => $datetime);

            DB::table('endorsement_crm_log')->insert($logarray);
        }
    }

    /**
     * 
     * @param type $endorsementId
     * @return type
     */
    public function overviewEndorsement($endorsementId) {
        $endorsementDetails = DB::table('policy_endorsement as pe')
                ->join('policies as p', 'p.id', '=', 'pe.policy_id')
                ->join('customers as c', 'c.id', '=', 'p.customer_id')
                ->join('insurer_details as ind', 'ind.id', '=', 'p.insurer_id')
                ->select('pe.*', 'c.name as customerName', 'c.id as customerId', 'ind.insurer_name', 'p.policy_number', 'p.customer_id', DB::raw("(case pe.endorsement_type when 1 then 'Addition' when 3 then 'Deletion'  when 4 then 'Downgrade' when 9 then 'Upgrade'   end) AS typeString"), DB::raw('DATE_FORMAT(pe.start_date, "%d.%m.%Y") as formatted_startDate'), DB::raw('DATE_FORMAT(pe.issue_date, "%d.%m.%Y") as formatted_issueDate'), DB::raw("(case pe.endorsement_status when 2 then 'Approved' when 3 then 'Rejected' when 1 then 'Waiting for approval' end) AS statusString"), 'p.end_date as expiry_date')
                ->where('pe.id', '=', $endorsementId)
                ->first();

        // dd($endorsementDetails);     

        $documentDetails = DB::table('dp_customer_document as doc')
                        ->leftJoin('users as u', 'u.id', '=', 'doc.upload_by')
                        ->leftJoin('dp_document_type as dt', 'dt.id', '=', 'doc.type')
                        ->select('doc.*', 'u.name as uploadedBy', 'dt.title as docType', 'doc.id as docId')
                        ->where("doc.endorsement_id", $endorsementId)->orderBy('doc.id', 'desc')->get();
        $documentType = DB::table('dp_document_type')->distinct()->orderBy('id')->pluck('title', 'id')->toArray();
        $statusArray = [1 => 'Waiting for approve', 'Approved', 'Rejected'];
        $breadcrumbDetails = array('url' => action('Dashboard\DashboardController@dashboardEndorsementList'), 'title' => 'Endorsement');

        $data = array('overviewData' => $endorsementDetails, 'policyId' => $endorsementDetails->policy_id, 'overviewTab' => 'overview', 'documentType' => $documentType, 'statusArray' => $statusArray, 'documentDetails' => $documentDetails, 'breadcrumb' => $breadcrumbDetails);


        return view('Endorsement/overviewEndorsement', $data);
    }

    /**
     * To upload crm document
     * @param Request $request
     * @param integer $requestId
     * @return type
     */
    public function uploadInvoice(Request $request, $endorsementId) {
        $files = $request->file('document_file');
        $insertArray = [];
        $type = $request->get('documenttype_oid');
        $comment = $request->get('document_comment');
        $customerId = $request->get('customer_id');
        $policyId = $request->get('policy_id');
        $filename = [];
        $datetime = date('Y-m-d h:i');

        $endorsementNumber = DB::table('policy_endorsement')->where('id', $endorsementId)->select('endorsement_number')->first();

        foreach ($files as $uploadedfile) {
            $destinationPath = 'uploads/' . $customerId . "/document/" . $policyId . "/";
            $path_parts = pathinfo($uploadedfile->getClientOriginalName());
            $newfilename = $path_parts['filename'] . "_" . date('Ymdhis') . '.' . $path_parts['extension'];
            $filename[] = $newfilename;
            $uploadedfile->move($destinationPath, $newfilename);
            $insertArray[] = array("customer_id" => $customerId,
                "file_name" => $newfilename,
                "type" => $type,
                "comment" => $comment,
                "upload_by" => Auth::user()->id,
                "upload_at" => $datetime,
                "endorsement_id" => $endorsementId,
                "policy_id" => $policyId
            );
        }

        if (count($insertArray) > 0) {
            DB::table('dp_customer_document')->insert($insertArray); // Query Builder approach
            //insert log entry
            $logarray = array(
                "kind" => "Following invoice for endorsement(" . $endorsementNumber->endorsement_number . ") are uploaded: " . implode(',', $filename),
                "oldvalue" => '',
                "edited_by" => Auth::user()->id,
                "edited_at" => $datetime);

            DB::table('policy_log')->insert($logarray);


            //Session::put('requestoverviewtabselected', 'document');
        }
        return back()->with(['success' => 'Successfully upload documents!', 'overviewtabselected' => 'document']);
    }

    /**
     * 
     * @param Request $request
     * @param type $endorsementId
     * @return type
     */
    public function updateEndorsementStatus(Request $request, $endorsementId) {


        $statusArray[1] = "Waiting for approval";
        $statusArray[2] = "Approved";
        $statusArray[3] = "Reject";
        $endorsementData = DB::table('policy_endorsement')->where('id', $endorsementId)->select('endorsement_status', 'endorsement_number')->first();
        $oldStatus = $endorsementData->endorsement_status;
        $endorsementNumber = $endorsementData->endorsement_number;
        $updateArray = array("endorsement_status" => $request->get('request_status'),
            "updated_date" => date('Y-m-d h:i:s')
        );
        if ($request->get('request_status') == 2) {
            $updateArray['approved_date'] = date('Y-m-d');
        } else {
            $updateArray['approved_date'] = null;
        }


        DB::table('policy_endorsement')->where('id', $endorsementId)->update($updateArray);
        $dateTime = date('Y-m-d h:i:s');
        //Endorsement Log
        $logArray = array("policy_id" => $request->get("endorsementPolicyId"),
            "kind" => 'Endorsement(' . $endorsementNumber . ') status was changed to : ' . $statusArray[$request->get('request_status')],
            "edited_by" => Auth::user()->id,
            "oldvalue" => $statusArray[$oldStatus],
            "edited_at" => $dateTime);
        DB::table('policy_log')->insert($logArray);

        return back()->with(['success' => 'Successfully update endorsement status!!!!', 'overviewtabselected' => 'crm']);
    }

    /**
     * 
     * @param Request $request
     * @return type
     */
    public function rejectEndorsement(Request $request) {

        $endorsementId = $request->get("reject_endorsement_id");
        $policyId = $request->get("reject_endorsement_policy_id");
        $endorsementData = DB::table('policy_endorsement')->where('id', $endorsementId)->select('endorsement_status', 'endorsement_number')->first();

        $endorsementNumber = $endorsementData->endorsement_number;
        $dateTime = date('Y-m-d h:i:s');
        $updateArray = array("endorsement_status" => 3,
            "updated_date" => $dateTime,
            "reject_reason" => $request->get("reject_reason")
        );
        DB::table('policy_endorsement')->where('id', $endorsementId)->update($updateArray);
        //Endorsement Log
        $logArray = array("policy_id" => $policyId,
            "kind" => 'Endorsement(' . $endorsementNumber . ') status was changed to :Reject ',
            "edited_by" => Auth::user()->id,
            "oldvalue" => 'Waiting for approval',
            "edited_at" => $dateTime);
        DB::table('policy_log')->insert($logArray);

        return back()->with(['success' => 'Successfully rejected endorsement !!!!']);
    }

    /**
     * To assign selected endorsement request to particular user
     * @param Request $request
     * @param type $requestId
     */
    public function assignEndorsementRequest(Request $request, $crmId) {
        $crmrequestObj = App\EndorsementCrm::find($crmId);
        $oldAssignValue = '';
        if ($crmrequestObj->assign_to != null) {
            $oldAssignObj = App\User::find($crmrequestObj->assign_to);
            $oldAssignValue = $oldAssignObj->name;
        }
        $changeddate = date('Y-m-d H:i');
        $crmrequestObj->assign_to = $request->get('operation_team');
        $crmrequestObj->updated_at = $changeddate;
        $crmrequestObj->save();
        //log entry
        $logArray = array();
        $newAssignObj = App\User::find($request->get('operation_team'));
        $logArray[] = array('crm_id' => $crmId, 'kind' => 'Crm request assigned to ' . $newAssignObj->name, 'old_value' => $oldAssignValue, 'updated_by' => Auth::user()->id, 'updated_at' => $changeddate);
        if (count($logArray) > 0) {
            DB::table('endorsement_crm_log')->insert($logArray);
        }
        return back()->with(['success' => 'Successfully assigned endorsement request !!!!']);
    }

    /**
     * To assign selected endorsement request to particular user
     * @param Request $request
     * @param type $requestId
     */
    public function assignSalesRequest(Request $request, $crmId) {
        $crmrequestObj = App\crmMain::find($crmId);
        $requestNo = $crmrequestObj->crm_request_id;
        $oldAssignValue = '';
        if ($crmrequestObj->assigned_to != null) {
            $oldAssignObj = App\User::find($crmrequestObj->assigned_to);
            $oldAssignValue = $oldAssignObj->name;
        }
        $changeddate = date('Y-m-d H:i');
        $crmrequestObj->assigned_to = $request->get('technical_team');
        $crmrequestObj->updated_date = $changeddate;
        $crmrequestObj->save();
        //log entry
        $logArray = array();
        $newAssignObj = App\User::find($request->get('technical_team'));
        $logArray[] = array('crm_id' => $crmId, 'title' => 'Request assigned to ' . $newAssignObj->name, 'old_value' => $oldAssignValue, 'edited_by' => Auth::user()->id, 'edited_at' => $changeddate);
        if (count($logArray) > 0) {
            DB::table('crm_log')->insert($logArray);
        }
        //MAIL send to assigned user
          $users = DB::table('users')->select('email', 'name')->where('status', '1')->where('id',$request->get('technical_team') )->first();

            $url = route('crmrequestOverview', ['requestId' => $crmId]);
            $data = array('name' => $users->name, 'link' => $url, 'Request_no' => $requestNo, 'username' => $users->name);
            $templatename = 'emails.assignnotification';
            $maidetails['to'] = $users->email;
            $maidetails['name'] = $users->name;
            $maidetails['subject'] = "CRM Request no: " . $requestNo . " was assigned";
            $this->send_email($maidetails, $data, $templatename);
        
        
        return back()->with(['success' => 'Successfully assigned endorsement request !!!!']);
    }
  /**
   * 
   * @param type $users
   * @param type $crmId
   * @param type $message
   */  
    private function sendMail($users,$overviewData, $message) {
        
             //CRM DETAILS  
        
        $link = action('Policy\PolicyController@endorsementCrmRequestOverview', ['policyId' => $overviewData->policy_id,'requestId'=>$overviewData->id]);
        Mail::send('emails.incompleteendorsementtemplate', ['overviewdata' => $overviewData,'link'=>$link], function ($m) use ($users,$message) {
                $m->from('info@dbroker.com.sa', 'Diamond insurance broker');
                $m->to($users->email)->subject($message); 
            });
    }
     /**
     * To send email
     * @param string $to
     * @param string $data
     */
    private function send_email($maidetails, $data, $template = null) {
        //New quote request is raised

        Mail::send($template, $data, function($message) use($maidetails) {
            $message->to($maidetails['to'], $maidetails['name'])->subject
                    ($maidetails['subject']);

            $message->from('info@dbroker.com.sa', 'Diamond Broker');
        });
    }

   

}
