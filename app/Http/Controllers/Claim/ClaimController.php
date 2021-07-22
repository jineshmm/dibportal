<?php

namespace App\Http\Controllers\Claim;

use Illuminate\Http\Request;
use Response;
use Illuminate\Support\Facades\DB;
use Auth;
use Mail;
use App;
use App\customer;
use App\policy;
use App\Http\Controllers\Controller;
use Session;
use File;
use Hamcrest\Core\IsNullTest;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Policyclaims;
use App\ClaimPayment;
use App\ClaimPaymentreserve;

/**
 * Status info
 * 1-[Open] Awaiting info/documents, 2- [Open]Claim reopened, 3-[Open] Awaiting repair approval from insurer,4-[Open] Awaiting repair check from from insurer, 5-[Open] Under process with insurer,6-[Open] Repair approval sent to the client/Awaiting repair invoices from the client
 * 7- [Closed]Partial approval, 8-[Closed]Claim settled,9-[Closed] Claim with in excess,10-[Closed] Claim no longer pursued,11-[Closed] Late submission,12-[Closed] Not covered under the policy,13-[Closed] Policy terms & conditions
 */
class ClaimController extends Controller {

    /**
     * To list the claim details 
     * @return type
     */
    public function listClaim() {

        $dashboardObj = new DashboardController();
        $generalValues = $dashboardObj->generalValues();
        $claimDetails = DB::table('policy_claims as pc')
                        ->join('policies as p', 'p.id', '=', 'pc.policy_id')
                        ->join('customers as c', 'pc.customer_id', '=', 'c.id')
                        ->leftJoin('users as u', 'u.id', '=', 'pc.claim_handler')
                        ->select('pc.*', 'c.name as customerName', 'p.policy_number', 'c.id_code', 'c.customer_code', DB::raw("(SELECT CONCAT(
  '[',GROUP_CONCAT(JSON_OBJECT('name',pci.name,'originalType', pci.claimant_type,'Idnumber',pci.id_number,'membershipNumber',pci.membership_number,'plateNumber',pci.plate_number,'chase Number',pci.chase_number,'certificateNumber',pci.certificate_number, 'type',(case pci.claimant_type when 1 then 'Policy holder' when 2 then 'Death claim' when 3 then 'Medical claim' when 4 then 'Motor claim' else 'Travel claim' end))),
  ']'
) AS list FROM policy_claimant_info pci
                                WHERE pci.claim_id = pc.id
                                GROUP BY pci.claim_id) as claimant"), 'u.name as claimHandler', DB::raw("(case pc.status when 1 then '[Open] Awaiting info/documents' when 2 then '[Open]Claim reopened' when 3 then '[Open] Awaiting repair approval from insurer' when 4 then '[Open] Awaiting repair check from from insurer' when 5 then '[Open] Under Process With Insurer' when 6 then '[Open] Repair Approval Sent to the client / Awaiting Repair Invoices from the client' when 7 then '[Closed] Partial Approval' when 8 then '[Closed] Claim settled' when 9 then '[Closed] Claim within excess' when 10 then '[Closed] Claim no longer pursued' when 11 then '[Closed] Late Submission' when 12 then '[Closed] Not Covered Under the Policy' when 13 then '[Closed] Not Covered - Policy Terms &amp; Conditions' end) AS statusString"))
                        ->orderBy('pc.updated_date', 'desc')->get();
        
        
            
        
        $type =0;
         if (in_array('ROLE_TECHNICAL_MANAGER', Auth::user()->roles) || in_array('ROLE_TECHNICAL', Auth::user()->roles)) {
           $type =1;  
         } else if(in_array('ROLE_OPERATION_MANAGER', Auth::user()->roles) || in_array('ROLE_OPERATION', Auth::user()->roles)) {
            $type =2; 
         } else if(in_array('ROLE_FINANCE_MANAGER', Auth::user()->roles) || in_array('ROLE_FINANCE', Auth::user()->roles)) {
            $type =3; 
         } else {
           $type =0;   
         }

$taskDetails =  DB::table('reminders_details as r')
                ->join('users', 'users.id', '=', 'r.created_by')                
                ->where('r.reminder_type',$type)
                ->where('r.reminder_date','=',date('Y-m-d'))
                ->select('r.id')
                ->count();


        $data = array("title" => 'Claims',
            "sidemenustatus" => $generalValues['statusDetails'],
            "countDetails" => $generalValues['statusCount'],
            'claimDetails' => $claimDetails,'notificationCount' => $taskDetails);
        

        return view('Claims/listClaims', $data);
    }

    /**
     * To create new claim
     * @return type
     */
    public function createClaim(Request $request) {
        $customerId = $request->get('customer_id', 0);
        $policyId = $request->get('policy_id', 0);
        $allCustomers = DB::table('customers')->distinct()->where('status', '1')->orderBy('id')->pluck('name', 'id')->toArray();
        $breadcrumbDetails = array('url' =>action('Claim\ClaimController@listClaim'),'title' =>'Claims');
        return view('Claims/addClaimForm', array('allCustomers' => $allCustomers, 'customerId' => $customerId, 'policyId' => $policyId, 'breadcrumb'=>$breadcrumbDetails));
    }

    /**
     * To save claim details
     * @param Request $request
     * @return type
     */
    public function saveClaimDetails(Request $request) {
        $step = $request->get('claim_step', 0);
        $type = $request->get('step_type', 'next');
        $dateObj = date('Y-m-d H:i:s');
        $insuranceCompany = DB::table('insurer_details')->distinct()->where('status', '1')->orderBy('id')->pluck('insurer_name', 'id')->toArray();
        $userData = DB::table('users')->distinct()->where('status', '1')->orderBy('id')->pluck('name', 'id')->toArray();
        $statusArray = array(1 => '[Open] Awaiting info/Documents', 2 => '[Open] Claim reopened', 3 => '[Open] Awaiting Repair Approval from Insurer', 4 => '[Open] Awaiting Repair Cheque from Insurer', 5 => '[Open] Under Process With Insurer', 6 => '[Open] Repair Approval Sent to the client / Awaiting Repair Invoices from the client', 7 => '[Closed] Partial Approval', 8 => '[Closed] Claim settled', 9 => '[Closed] Claim within excess', 10 => '[Closed] Claim no longer pursued', 11 => '[Closed] Late Submission', 12 => '[Closed] Not Covered Under the Policy', 13 => '[Closed] Not Covered - Policy Terms &amp; Conditions');
        $reserveTypeArray = array(1 => 'Own damage', 2 => 'Third party');
        $claimType = array(2 => 'Death claim', 3 => 'Medical claim', 4 => 'Motor claim', 5 => 'Travel claim');
        switch ($step) {

            case 1:
                //step 0 save and step1 show
                if ($type == 'next') {
                    if ($request->get('claim_id') > 0) {
                        $policyclaimObj = Policyclaims::find($request->get('claim_id'));
                    } else {
                        $policyclaimObj = new Policyclaims();
                    }
                    $policyclaimObj->customer_id = $request->get('customer_id');
                    $policyclaimObj->policy_id = $request->get('complaint_policy');
                    $policyclaimObj->created_date = $dateObj;
                    $policyclaimObj->created_by = Auth::user()->id;
                    $policyclaimObj->save();
                    $claimId = $policyclaimObj->getKey();
                    $whereArray[] = ['customer_id', "=", $request->get('customer_id')];
                    $whereArray[] = ['policy_number', "!=", null];
                    $customerOldPolicies = DB::table('policies')->where($whereArray)->orderBy('id')->pluck('policy_number', 'id')->toArray();
                    $data = array('claimId' => $claimId, 'insurancecompany' => $insuranceCompany, 'claimStatus' => $statusArray, 'reserveType' => $reserveTypeArray, 'userData' => $userData);
                    $returnHTML = view('Claims/step2Form', $data)->render();
                    return response()->json(array('status' => true, 'content' => $returnHTML, 'step' => 2, 'backButton' => true, 'claimId' => $claimId));
                }

                break;
            case 2:

                if ($type == 'next') {
                    $policyclaimObj = Policyclaims::find($request->get('claim_id'));
                    $incidentDate = $request->get('claim_date_incident') ;
                    $policyclaimObj->incident_date = date('Y-m-d H:i', strtotime($incidentDate));                    
                    $policyclaimObj->submitted_broker_date = date('Y-m-d', strtotime($request->get('claim_date_submit')));
                    $policyclaimObj->submitted_insurer_date = date('Y-m-d', strtotime($request->get('claim_date_submitinsurer')));
                    $policyclaimObj->settlement_date = empty($request->get('claim_date_settlement')) ? null : date('Y-m-d', strtotime($request->get('claim_date_settlement')));
                    $policyclaimObj->status = $request->get('claim_status');
                    $policyclaimObj->claim_handler = $request->get('claim_handle_person_id');
                    $policyclaimObj->location = $request->get('claim_location');
                    $policyclaimObj->deductible_excess_amount = $request->get('claim_deductible_amt');
                    $policyclaimObj->insurer_contact_name = $request->get('claim_insurer_name');
                    $policyclaimObj->insurer_contact_email = $request->get('claim_insurer_email');
                    $policyclaimObj->insurer_contact_phone = $request->get('claim_insurer_phone');
                    $policyclaimObj->insurer_refernce_id = $request->get('claim_id_insurer');
                    $policyclaimObj->reserve_sum_balance = $request->get('claim_reserve_sum');
                    	
                    $policyclaimObj->save();
                    $this->addReserveAmountDetails($request, $request->get('claim_id'), $request->get('claim_reserve_sum'));

                    $data = array('claimId' => $request->get('claim_id'), 'claimType' => $claimType);
                    $returnHTML = view('Claims/step3Form', $data)->render();
                    return response()->json(array('status' => true, 'content' => $returnHTML, 'step' => 3, 'backButton' => true));
                }
                break;
            case 3:

                if ($type == 'next') {
                    //save product details
                    $this->saveClaimantDetails($request);
                    Session::flash('success', 'Successfully added policy details!!!!');

                    $returnHTML = '';
                    return response()->json(array('status' => true, 'content' => $returnHTML, 'step' => 4, 'backButton' => true, 'returnUrl' => action('Claim\ClaimController@listClaim')));
                }
                break;
        }
    }

    /**
     * To save the claimant details
     * @param Request $request
     */
    private function saveClaimantDetails($request) {
        $policyclaimObj = Policyclaims::find($request->get('claim_id'));
        $insertArray = [];
        $i = 0;
  
        if (!empty($request->get('policyholder_is_claimant'))) {
            $policyclaimObj->is_policy_holder_claimate = 1;
            $insertArray[$i]['claimant_type'] = 1;
            $insertArray[$i]['name'] = customer::find($policyclaimObj->customer_id)->name;
            $insertArray[$i]['claim_id'] = $request->get('claim_id');
            $i++;
        } else {
            $policyclaimObj->is_policy_holder_claimate = 0;
        }


        $policyclaimObj->save();

        $claimantNameArray = empty($request->get('claimant_claim_claimant_name')) ? array() : $request->get('claimant_claim_claimant_name');
        $claimantTypeArray = empty($request->get('claimant_claimanttype')) ? array() : $request->get('claimant_claimanttype');

        $claimantCertificateNumberArray = empty($request->get('claimant_certificatenumber')) ? array() : $request->get('claimant_certificatenumber');
        $claimantChasenumberArray = empty($request->get('claimant_chasenumber')) ? array() : $request->get('claimant_chasenumber');
        $claimantPlatenumberArray = empty($request->get('claimant_platenumber')) ? array() : $request->get('claimant_platenumber');
        $claimantMembershipArray = empty($request->get('claimant_membership_number')) ? array() : $request->get('claimant_membership_number');
        $claimantIdNumArray = empty($request->get('claimant_idnumber')) ? array() : $request->get('claimant_idnumber');




        foreach ($claimantTypeArray as $key => $claimantName) {
            $insertArray[$i]['claimant_type'] = $claimantTypeArray[$key];
            $insertArray[$i]['claim_id'] = $request->get('claim_id');
            $insertArray[$i]['id_number'] =null;
            $insertArray[$i]['membership_number']=null;
            $insertArray[$i]['plate_number'] = null;
            $insertArray[$i]['chase_number'] = null;
            $insertArray[$i]['certificate_number'] =null;
             $insertArray[$i]['name']=null;
            if($claimantTypeArray[$key] ==3) {
                $insertArray[$i]['id_number'] = $claimantIdNumArray[$key];
                $insertArray[$i]['membership_number'] = $claimantMembershipArray[$key];
                $insertArray[$i]['name'] = $claimantNameArray[$key];               
            } else if($claimantTypeArray[$key] ==4) {
                $insertArray[$i]['plate_number'] = $claimantPlatenumberArray[$key];
                $insertArray[$i]['chase_number'] = $claimantChasenumberArray[$key];
                $insertArray[$i]['certificate_number'] = $claimantCertificateNumberArray[$key];
            } else {
                 $insertArray[$i]['name'] = $claimantNameArray[$key];
            }
            $i++;
        }
        if (count($insertArray) > 0) {
            DB::table('policy_claimant_info')->insert($insertArray);
        }
    }

    /**
     * To display claim information
     * @param integer $claimId
     * @return type
     */
    public function claimOverviewDetails($claimId) {
        $claimDetails = DB::table('policy_claims as pc')
                        ->join('policies as p', 'p.id', '=', 'pc.policy_id')
                        ->join('customers as c', 'pc.customer_id', '=', 'c.id')
                        ->leftJoin('insurer_details as d', 'd.id', '=', 'p.insurer_id')
                        ->leftJoin('users as u', 'u.id', '=', 'pc.claim_handler')
                        ->select('pc.*', 'p.insurer_id', 'c.name as customerName', 'p.policy_number', 'p.start_date', 'p.end_date', 'd.insurer_name as insuranceName', 'pc.status as originalStatus', 'c.id_code', 'c.customer_code', DB::raw("(SELECT CONCAT(
  '[',GROUP_CONCAT(JSON_OBJECT('id',pci.id,'name',pci.name,'originalType', pci.claimant_type,'Idnumber',pci.id_number,'membershipNumber',pci.membership_number,'plateNumber',pci.plate_number,'chaseNumber',pci.chase_number,'certificateNumber',pci.certificate_number, 'type',(case pci.claimant_type when 1 then 'Policy holder' when 2 then 'Death claim' when 3 then 'Medical claim' when 4 then 'Motor claim' else 'Travel claim' end))),
  ']'
) AS list FROM policy_claimant_info pci
                                WHERE pci.claim_id = pc.id
                                GROUP BY pci.claim_id) as claimant"), 'u.name as claimHandler', DB::raw("(case pc.status when 1 then '[Open] Awaiting info/documents' when 2 then '[Open]Claim reopened' when 3 then '[Open] Awaiting repair approval from insurer' when 4 then '[Open] Awaiting repair check from from insurer' when 5 then '[Open] Under Process With Insurer' when 6 then '[Open] Repair Approval Sent to the client / Awaiting Repair Invoices from the client' when 7 then '[Closed] Partial Approval' when 8 then '[Closed] Claim settled' when 9 then '[Closed] Claim within excess' when 10 then '[Closed] Claim no longer pursued' when 11 then '[Closed] Late Submission' when 12 then '[Closed] Not Covered Under the Policy' when 13 then '[Closed] Not Covered - Policy Terms &amp; Conditions' end) AS statusString"))
                        ->where('pc.id', $claimId)
                        ->orderBy('pc.updated_date', 'desc')->first();
        $headTitle = "Claim:" . $claimDetails->id;
        //document details

        $documentDetails = DB::table('dp_customer_document as doc')
                        ->leftJoin('users as u', 'u.id', '=', 'doc.upload_by')
                        ->leftJoin('dp_document_type as dt', 'dt.id', '=', 'doc.type')
                        ->select('doc.*', 'u.name as uploadedBy', 'dt.title as docType', 'doc.id as docId')
                        ->where('doc.claim_id', $claimId)->orderBy('doc.id', 'desc')->get();

        $documentType = DB::table('dp_document_type')->distinct()->orderBy('id')->pluck('title', 'id')->toArray();

        $insurerDetails = DB::table('insurer_details')->where('id', $claimDetails->insurer_id)->first();
        
        
        $reserveAmount = Policyclaims::find($claimId)->reserve_sum_balance;
        $totalGrossPaid = DB::table('policy_claim_payment_recoveries_info')->where('claim_id', $claimId)->sum('payment_sum');

        $statusArray = array(1 => '[Open] Awaiting info/Documents', 2 => '[Open] Claim reopened', 3 => '[Open] Awaiting Repair Approval from Insurer', 4 => '[Open] Awaiting Repair Cheque from Insurer', 5 => '[Open] Under Process With Insurer', 6 => '[Open] Repair Approval Sent to the client / Awaiting Repair Invoices from the client', 7 => '[Closed] Partial Approval', 8 => '[Closed] Claim settled', 9 => '[Closed] Claim within excess', 10 => '[Closed] Claim no longer pursued', 11 => '[Closed] Late Submission', 12 => '[Closed] Not Covered Under the Policy', 13 => '[Closed] Not Covered - Policy Terms &amp; Conditions');
        $breadcrumbDetails = array('url' =>action('Claim\ClaimController@listClaim'),'title' =>'Claims');
        $data = array("title" => 'Claims', 'headTitle' => $headTitle, 'claimDetails' => $claimDetails, 'claimStatus' => $statusArray, 'documentDetails' => $documentDetails, 'documentType' => $documentType, 'policyId' => $claimDetails->policy_id, 'insuredDetals' => $insurerDetails,'reserveAmount'=>$reserveAmount,'grossPaid'=>$totalGrossPaid,'breadcrumb'=>$breadcrumbDetails);


        // echo "<pre>";
        // print_r($claimDetails);exit;

        return view('Claims/claimoverview', $data);
    }

    /**
     * To remove claimant info
     * @param Request $request
     * @param integer $claimantId
     * @return type
     */
    public function removeClaimant(Request $request, $claimantId) {
        $claimantInfo = DB::table('policy_claimant_info')->select('*')->where('id', $claimantId)->first();
        DB::table('policy_claimant_info')->where('id', $claimantId)->delete();
        Session::flash('success', 'Successfully delete claimant info!!!!');
        //log entry
        $logArray['claim_id'] = $request->get('claimId');
        $logArray['kind'] = 'Delete claimant info:' . $claimantInfo->name;
        $logArray['entered_date'] = date('Y-m-d h:i');
        $logArray['edited_by'] = Auth::user()->id;
        DB::table('claim_log')->insert($logArray);


        return response()->json(array('status' => true, 'redirect' => action('Claim\ClaimController@claimOverviewDetails', ['claimId' => $request->get('claimId')])));
    }

    /**
     * To save claimant data
     * @param Request $request
     * @param integer $claimId
     * @return type
     */
    public function saveClaimant(Request $request, $claimId) {
            $insertArray['claimant_type'] = $request->get('claimanttype');    
            $insertArray['id_number'] =null;
            $insertArray['membership_number']=null;
            $insertArray['plate_number'] = null;
            $insertArray['chase_number'] = null;
            $insertArray['certificate_number'] =null;
            $insertArray['name']=null;
            if($request->get('claimanttype') ==3) {
                $insertArray['id_number'] = $request->get('claim_id_number'); 
                $insertArray['membership_number'] = $request->get('claim_membership_number');
                $insertArray['name'] = $request->get('claim_claimant_name');                
            } else if($request->get('claimanttype')==4) {
                $insertArray['plate_number'] = $request->get('claim_plate_number'); 
                $insertArray['chase_number'] = $request->get('claim_chase_number'); 
                $insertArray['certificate_number'] = $request->get('claim_certificate_number');
            } else {
                 $insertArray['name'] = $request->get('claim_claimant_name');
            }

        $insertArray['claim_id'] = $claimId;
        DB::table('policy_claimant_info')->insert($insertArray);

        //log entry
        $logArray['claim_id'] = $claimId;
        $logArray['kind'] = 'New claimant added: ' ;
        $logArray['entered_date'] = date('Y-m-d h:i');
        $logArray['edited_by'] = Auth::user()->id;
        DB::table('claim_log')->insert($logArray);
        return back()->with(['success' => 'Successfully add claimant info!!!!']);
    }

    /**
     * To update claimant info
     * @param Request $request
     * @param type $claimId
     * @return type
     */
    public function updateClaimant(Request $request, $claimId) {
        $claimantInfo = DB::table('policy_claimant_info')->select('*')->where('id', $request->get('claimantId'))->first();

            if($claimantInfo->claimant_type  ==3) {
                $insertArray['id_number'] =  $request->get('claim_id_number');
                $insertArray['membership_number'] =  $request->get('claim_membership_number'); 
                $insertArray['name'] =  $request->get('claim_claimant_name');              
            } else if($claimantInfo->claimant_type ==4) {
                $insertArray['plate_number'] =  $request->get('claim_plate_number');
                $insertArray['chase_number'] =  $request->get('claim_chase_number');
                $insertArray['certificate_number'] =  $request->get('claim_certificate_name');
            } else {
                 $insertArray['name'] =  $request->get('claim_claimant_name');
            }



        DB::table('policy_claimant_info')->where('id', $request->get('claimantId'))->update($insertArray);
        //log entry
        $logArray['claim_id'] = $claimId;
        $logArray['kind'] = 'Update claimant info: ';
        $logArray['old_value'] = $claimantInfo->name;
        $logArray['entered_date'] = date('Y-m-d h:i');
        $logArray['edited_by'] = Auth::user()->id;
        DB::table('claim_log')->insert($logArray);

        return back()->with(['success' => 'Successfully update claimant info!!!!']);
    }

    /**
     * To update claimant info
     * @param Request $request
     * @param type $claimId
     * @return type
     */
    public function updateStatus(Request $request, $claimId) {
        $claimObj = Policyclaims::find($claimId);
        $oldStatus = $claimObj->status;
        $claimObj->status = $request->get('claim_status');
        $claimObj->save();
        $statusArray = array(1 => '[Open] Awaiting info/Documents', 2 => '[Open] Claim reopened', 3 => '[Open] Awaiting Repair Approval from Insurer', 4 => '[Open] Awaiting Repair Cheque from Insurer', 5 => '[Open] Under Process With Insurer', 6 => '[Open] Repair Approval Sent to the client / Awaiting Repair Invoices from the client', 7 => '[Closed] Partial Approval', 8 => '[Closed] Claim settled', 9 => '[Closed] Claim within excess', 10 => '[Closed] Claim no longer pursued', 11 => '[Closed] Late Submission', 12 => '[Closed] Not Covered Under the Policy', 13 => '[Closed] Not Covered - Policy Terms &amp; Conditions');
        //log entry
        $logArray['claim_id'] = $claimId;
        $logArray['kind'] = 'Update claimant status: ' . $statusArray[$request->get('claim_status')];
        $logArray['old_value'] = $statusArray[$oldStatus];
        $logArray['entered_date'] = date('Y-m-d h:i');
        $logArray['edited_by'] = Auth::user()->id;
        $logArray['information'] = $request->get('claim_progress_comment');
        DB::table('claim_log')->insert($logArray);


        return back()->with(['success' => 'Successfully update claimant status!!!!']);
    }

    public function logData($claimId) {
        $logData = DB::table('claim_log as lo')
                        ->join('policy_claims as pc', 'pc.id', '=', 'lo.claim_id')
                        ->join('users as u', 'u.id', '=', 'lo.edited_by')
                        ->select('lo.*', 'u.name as editedBy')
                        ->where('lo.claim_id', $claimId)
                        ->orderBy('lo.id', 'desc')->get();


        $data = array('logData' => $logData, "title" => 'Log', 'claimId' => $claimId);

        $returnHTML = view('Claims/logData', $data)->render();

        return response()->json(array('status' => true, 'content' => $returnHTML));
    }

    public function documentData($claimId) {
        $logData = DB::table('dp_customer_document as lo')
                        ->join('policy_claims as pc', 'pc.id', '=', 'lo.claim_id')
                        ->join('users as u', 'u.id', '=', 'lo.edited_by')
                        ->select('lo.*', 'u.name as editedBy')
                        ->where('lo.claim_id', $claimId)
                        ->orderBy('lo.id', 'desc')->get();


        $data = array('logData' => $logData, "title" => 'Log', 'claimId' => $claimId, 'policyId' => '');

        $returnHTML = view('Claims/documentData', $data)->render();

        return response()->json(array('status' => true, 'content' => $returnHTML));
    }

    public function documentSave(Request $request, $claimId) {
        $files = $request->file('document_file');
        $insertArray = [];
        $type = $request->get('documenttype_oid');
        $comment = $request->get('document_comment');
        $customerId = $request->get('customer_id');
        $policyId = $request->get('policy_id');
        $filename = [];
        $datetime = date('Y-m-d h:i');
        foreach ($files as $uploadedfile) {
            $destinationPath = 'uploads/' . $customerId . "/document/";
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
                "claim_id" => $claimId,
                "policy_id" => $policyId
            );
        }

        if (count($insertArray) > 0) {
            DB::table('dp_customer_document')->insert($insertArray); // Query Builder approach
            //insert log entry
            $logArray['claim_id'] = $claimId;
            $logArray['kind'] = "Following documents are uploaded: " . implode(',', $filename);
            $logArray['old_value'] = '';
            $logArray['entered_date'] = $datetime;
            $logArray['edited_by'] = Auth::user()->id;
            DB::table('claim_log')->insert($logArray);


            //Session::put('requestoverviewtabselected', 'document');
        }
        return back()->with(['success' => 'Successfully upload documents!', 'overviewtabselected' => 'document']);
    }

    public function documentEdit(Request $request, $claimId) {
        $type = $request->get('documenttype_oid');
        $comment = $request->get('document_comment');
        $customerId = $request->get('customer_id');
        $policyId = $request->get('policy_id');
        $docId = $request->get('doc_id');
        $datetime = date('Y-m-d h:i');
        DB::table('dp_customer_document')->where('id', $docId)->update(['type' => $type, "comment" => $comment, "edited_at" => $datetime]);
        //log entry

        $logArray['claim_id'] = $claimId;
        $logArray['kind'] = "Document detail was changed";
        $logArray['old_value'] = '';
        $logArray['entered_date'] = $datetime;
        $logArray['edited_by'] = Auth::user()->id;
        DB::table('claim_log')->insert($logArray);

        return back()->with(['success' => 'Successfully updated documents details!', 'overviewtabselected' => 'document']);
    }

    /**
     * To delete crm document
     * @param Request $request
     * @param integer $requestId
     * @return type
     */
    public function deleteDocument(Request $request, $claimId) {

        $policyId = $request->get('policyId');
        $docId = $request->get('docId');
        $customerId = $request->get('customerId');
        $datetime = date('Y-m-d h:i');
        $whereArray[] = ['id', '=', $request->get('docId')];
        $whereArray[] = ['customer_id', '=', $customerId];
        $documentDetails = DB::table('dp_customer_document')->where($whereArray)->pluck('file_name')->toArray();

        DB::table('dp_customer_document')->where('id', $docId)->delete();
        $destinationPath = 'uploads/' . $customerId . "/document/" . $documentDetails[0];
        unlink($destinationPath);


        $logArray['claim_id'] = $claimId;
        $logArray['kind'] = "Document was removed:" . $documentDetails[0];
        $logArray['old_value'] = '';
        $logArray['entered_date'] = $datetime;
        $logArray['edited_by'] = Auth::user()->id;
        DB::table('claim_log')->insert($logArray);

        Session::flash('success', 'Successfully removed document!!!!');
        return response()->json(array('status' => true));
    }

    /**
     * To get the edit details of a claim
     * @param type $claimId
     * @return type
     */
    public function editClaim($claimId) {
        $allCustomers = DB::table('customers')->distinct()->where('status', '1')->orderBy('id')->pluck('name', 'id')->toArray();
        $insuranceCompany = DB::table('insurer_details')->distinct()->where('status', '1')->orderBy('id')->pluck('insurer_name', 'id')->toArray();
        $userData = DB::table('users')->distinct()->where('status', '1')->orderBy('id')->pluck('name', 'id')->toArray();
        //Claim details
        $claimDetails = DB::table('policy_claims as pc')
                ->select('pc.*')
                ->where('pc.id', $claimId)
                ->first();
        $breadcrumbDetails = array('url' =>action('Claim\ClaimController@listClaim'),'title' =>'Claims');
        $data = array('allCustomers' => $allCustomers, 'userData' => $userData, 'claimDetails' => $claimDetails,'breadcrumb'=>$breadcrumbDetails);
        return view('Claims/editClaimForm', $data);
    }

    /**
     * To update the details of the policy claim
     * @param Request $request
     * @param integer $claimId
     * @return type
     */
    public function updateClaimdetails(Request $request, $claimId) {
        $policyclaimObj = Policyclaims::find($claimId);

        $incidentDate = $request->get('claim_date_incident');
        $policyclaimObj->incident_date = date('Y-m-d H:i', strtotime($incidentDate));
        $policyclaimObj->submitted_broker_date = date('Y-m-d', strtotime($request->get('claim_date_submit')));
        $policyclaimObj->submitted_insurer_date = date('Y-m-d', strtotime($request->get('claim_date_submitinsurer')));
        $policyclaimObj->settlement_date = empty($request->get('claim_date_settlement')) ? null : date('Y-m-d', strtotime($request->get('claim_date_settlement')));
 
        $policyclaimObj->claim_handler = $request->get('claim_handle_person_id');
        $policyclaimObj->location = $request->get('claim_location');
        $policyclaimObj->insurer_contact_name = $request->get('claim_insurer_name');
        $policyclaimObj->insurer_contact_email = $request->get('claim_insurer_email');
        $policyclaimObj->insurer_contact_phone = $request->get('claim_insurer_phone');
        $policyclaimObj->insurer_refernce_id = $request->get('claim_id_insurer');
         $policyclaimObj->insurance_claim_number = $request->get('claim_insurace_claimnumber');

        $policyclaimObj->save();
        //log entry
        $datetime = date('Y-m-d h:i');
        $logArray['claim_id'] = $claimId;
        $logArray['kind'] = "Claim detail was changed";
        $logArray['old_value'] = '';
        $logArray['entered_date'] = $datetime;
        $logArray['edited_by'] = Auth::user()->id;
        DB::table('claim_log')->insert($logArray);
        return redirect()->action('Claim\ClaimController@claimOverviewDetails', ['claimId' => $claimId])->with('success', 'Successfully updated claim details!');
    }
/**
 * To get payment details
 * @param integer $claimId
 * @return type
 */
    public function paymentDetails($claimId) {
     $paymentData = DB::table('policy_claim_payment_recoveries_info as py')
                ->leftJoin('insurer_details as d', 'd.id', '=', 'py.payer_insurer_id')
                ->leftJoin('insurer_details as dd', 'dd.id', '=', 'py.payee_insurer_id')
                ->select('py.*','d.insurer_name as payerInsurer','dd.insurer_name as payeeInsurer',DB::raw("(case py.payment_type when 2 then 'By Cheque' when 3 then 'By Online transfer' else  'By cash'  end) AS paymentType"), DB::raw("(case py.payer_type when 0 then 'Insurer (system-defined)'  else  'Insured'  end) AS payerType"), DB::raw("(case py.payee_type when 0 then 'Insurer (system-defined)'  else  'Insured'  end) AS payeeType"))
                ->where('py.claim_id',$claimId)
                ->where('py.transaction_type',0)
             ->get();
     $deductionData = DB::table('policy_claim_payment_recoveries_info as py')
                ->leftJoin('insurer_details as d', 'd.id', '=', 'py.payer_insurer_id')
                ->leftJoin('insurer_details as dd', 'dd.id', '=', 'py.payee_insurer_id')
                ->select('py.*','d.insurer_name as payerInsurer','dd.insurer_name as payeeInsurer',DB::raw("(case py.payment_type when 2 then 'By Cheque' when 3 then 'By Online transfer' else  'By cash'  end) AS paymentType"), DB::raw("(case py.payer_type when 0 then 'Insurer (system-defined)'  else  'Insured'  end) AS payerType"), DB::raw("(case py.payee_type when 0 then 'Insurer (system-defined)'  else  'Insured'  end) AS payeeType"))
                ->where('py.claim_id',$claimId)
                ->where('py.transaction_type',1)
             ->get();
     
     $reserveHistory = DB::table('policy_claim_reserve_history as h')                
                ->select('h.*')
                ->where('h.claim_id',$claimId)              
             ->get();
     
     $reserveAmount = Policyclaims::find($claimId)->reserve_sum_balance;
     $totalGrossPaid = DB::table('policy_claim_payment_recoveries_info')->where('claim_id', $claimId)->sum('payment_sum');
     $deductionContribution = DB::table('policy_claim_payment_recoveries_info')->where('claim_id', $claimId)->where('transaction_type', 1)->sum('payment_sum');
     
     $paidSplit = DB::table('policy_claim_payment_recoveries_info as py')
                ->leftJoin('insurer_details as d', 'd.id', '=', 'py.payer_insurer_id')
                ->leftJoin('insurer_details as dd', 'dd.id', '=', 'py.payee_insurer_id')
                ->select('py.*',DB::raw("(case py.payment_type when 2 then 'By Cheque' when 3 then 'By Online transfer' else  'By cash'  end) AS paymentType"),DB::raw('SUM(py.payment_sum) as groupSum'))
                ->where('py.claim_id',$claimId)
                ->groupBy('py.payment_type')
             ->get(); 
    $insuredPaidSplit =DB::table('policy_claim_payment_recoveries_info as py')
                ->leftJoin('insurer_details as d', 'd.id', '=', 'py.payer_insurer_id')
                ->leftJoin('insurer_details as dd', 'dd.id', '=', 'py.payee_insurer_id')
                ->select(DB::raw("(case py.payment_type when 2 then 'By Cheque' when 3 then 'By Online transfer' else  'By cash'  end) AS paymentType"),DB::raw('SUM(py.payment_sum) as groupSum'))
                ->where('py.claim_id',$claimId)
                 // ->where('py.transaction_type',0)
                ->groupBy('py.payment_type')
             ->get(); 
   
     
     $data = array('paymentDetails'=>$paymentData,'deductionDetails'=>$deductionData,'reserveHistories' =>$reserveHistory,'reserveAmount'=>$reserveAmount,'grossPaid'=>$totalGrossPaid,'deductionContribution'=>$deductionContribution,'paidSplit'=>$paidSplit,'insuredPaidSplit'=>$insuredPaidSplit);



        $returnHTML = view('Claims/paymentData', $data)->render();        
        return response()->json(array('status' => true, 'content' => $returnHTML));
    }
/**
 * To save the new payment details
 * @param Request $request
 * @param integer $claimId
 * @return type
 */
    public function paymentAdd(Request $request, $claimId) {
        
        $paymentId = $request->get('payment_id',0);
        if($paymentId > 0) {
         $paymentObj =  ClaimPayment::find($paymentId);  
        } else {
          $paymentObj = new ClaimPayment();  
        }
        $paymentObj->claim_id = $claimId;
        $reserveFlag = empty($request->get('claim_payment_reserve_change')) ? 0 : $request->get('claim_payment_reserve_change');
        $paymentObj->transaction_type = $request->get('transaction_type');
        $paymentObj->payment_sum = $request->get('claim_payment_sum');
        $paymentObj->payment_date = date('Y-m-d', strtotime($request->get('claim_payment_date')));
        $paymentObj->payment_type = $request->get('claim_payment_type');
        $paymentObj->payment_description = $request->get('claim_payment_description');
        $paymentObj->is_reserve_change_flag =$reserveFlag;
        $paymentObj->payer_type = $request->get('claim_payment_payer_type');
        $paymentObj->payer_name = $request->get('claim_payment_payer_name');
        $paymentObj->payee_type = $request->get('claim_payment_payee_type');
        $paymentObj->payee_name = $request->get('claim_payment_payee_name');
        $paymentObj->payer_insurer_id = $request->get('claim_payment_payer_insurer');
        $paymentObj->payee_insurer_id = $request->get('claim_payment_payee_insurer');
        $paymentObj->save();
        $insertArray = [];
        if ($reserveFlag == 1 && $paymentId ==0 ) {
            $claimObj = Policyclaims::find($claimId);
            $currentBalance = $claimObj->reserve_sum_balance;
            $newBalance = floatval($currentBalance) - floatval($request->get('claim_payment_sum')); 
            $claimObj->reserve_sum_balance = $newBalance;
            $claimObj->save();
            $insertArray['claim_id'] = $claimId;
            $insertArray['amount'] = -1 * floatval($request->get('claim_payment_sum'));
            $insertArray['reserve_date'] = date('Y-m-d', strtotime($request->get('claim_payment_date')));
            $insertArray['balance_reserve_sum'] = floatval($newBalance);
        }
        

        if (count($insertArray) > 0) {
            DB::table('policy_claim_reserve_history')->insert($insertArray);
        }
        
        //log entry
        $logArray['claim_id'] = $claimId;
        $logArray['kind'] = ($paymentId >0) ? 'Payment entry updated' :'Payment entry insert';
        $logArray['entered_date'] = date('Y-m-d h:i');
        $logArray['edited_by'] = Auth::user()->id;     
        DB::table('claim_log')->insert($logArray);
        
        $message = ($paymentId >0) ? 'Successfully update payment details!' : 'Successfully enter payment details!';
        return back()->with(['success' => $message, 'overviewtabselected' => 'document']);
    }
/**
 * To remove reserve amount details
 * @param Request $request
 * @param integer $claimId
 * @return type
 */
    public function reserveAmountAdd(Request $request, $claimId) {
        $claimObj = Policyclaims::find($claimId);
           
          $historyId = $request->get('history_id',0);
           $newBalance=0;
            if($historyId > 0) {
             $historyObj =  ClaimPaymentreserve::find($historyId);                           
               $newBalance = $historyObj->balance_reserve_sum;
             
            } else {
               $currentBalance = $claimObj->reserve_sum_balance;
               $newBalance = floatval($currentBalance) + floatval($request->get('claim_reserve_sum')); 
            }
             
            $claimObj->reserve_sum_balance = $newBalance;
            $claimObj->save();
        $message = ($historyId >0) ? 'Successfully update reserve amount details!' : 'Successfully enter reserve amount details!';
        $this->addReserveAmountDetails($request, $claimId, $newBalance);
        
         //log entry
        $logArray['claim_id'] = $claimId;
        $logArray['kind'] = 'Reserve some value is updated';
        $logArray['entered_date'] = date('Y-m-d h:i');
        $logArray['edited_by'] = Auth::user()->id;     
        DB::table('claim_log')->insert($logArray);
        
           
        return back()->with(['success' => 'Successfully enter reserve amount details!', 'overviewtabselected' => 'document']);
    }
/**
 * To add reserve amount details
 * @param object $request
 * @param integer $claimId
 * @param integer $newBalance
 */
    private function addReserveAmountDetails($request, $claimId, $newBalance) {
         $historyId = $request->get('history_id',0);
         if( $historyId >0) {
          $reserveObj = ClaimPaymentreserve::find($historyId);   
         } else {
            $reserveObj = new ClaimPaymentreserve(); 
         }
         
        
        $reserveObj->claim_id = $claimId;
        $reserveObj->amount= floatVal($request->get('claim_reserve_sum'));
        $reserveObj->reserve_date = date('Y-m-d', strtotime($request->get('claim_reserve_date')));
        $reserveObj->description = $request->get('claim_reserve_description');
        $reserveObj->reserve_type = $request->get('claim_reserve_type');
        $reserveObj->balance_reserve_sum = floatval($newBalance);
        
        $reserveObj->save();
        
    }
   /** 
    * To delete reserve amount details
    * @param Request $request
    * @param integer $claimId
    * @param integer $historyId
    * @return type
    */
    public function reserveAmountDelete(Request $request, $claimId, $historyId) {
        $historyObj = ClaimPaymentreserve::find($historyId);        
        $amount = $historyObj->amount;
        Session::flash('success', 'Successfully delete reserve history info!!!!');
        //log entry
        $logArray['claim_id'] = $claimId;
        $logArray['kind'] = 'Delete claim reserve history amount:' . $historyObj->amount;
        $logArray['entered_date'] = date('Y-m-d h:i');
        $logArray['edited_by'] = Auth::user()->id;       
        DB::table('policy_claim_reserve_history')->where('id',$historyId)->delete();
        DB::table('claim_log')->insert($logArray);
        //Increase reserve amount
        $claimObj = Policyclaims::find($claimId);
        $oldAmount = $claimObj->reserve_sum_balance;
        $newAmount =   (-1*$amount) + $oldAmount;
        $claimObj->reserve_sum_balance =floatval($newAmount);
        $claimObj->save();
        return response()->json(array('status' => true, 'redirect' => action('Claim\ClaimController@claimOverviewDetails', ['claimId' => $claimId]))); 
    }
    /**
     * To delete deduction amount
     * @param Request $request
     * @param integer $claimId
     * @param integer $paymentId
     * @return type
     */
    public function deductionAmountDelete(Request $request, $claimId, $paymentId) {
        $paymentObj = ClaimPayment::find($paymentId);  
        $title = ($paymentObj->transaction_type ==0) ? 'Delete claim payment entry amount:' . $paymentObj->payment_sum : 'Delete claim deduction entry amount:' . $paymentObj->payment_sum;
        Session::flash('success', 'Successfully delete payment !!!!');
        //log entry
        $logArray['claim_id'] = $claimId;
        $logArray['kind'] = 'Delete claim payment entry delete amount:' . $paymentObj->payment_sum;
        $logArray['entered_date'] = date('Y-m-d h:i');
        $logArray['edited_by'] = Auth::user()->id;       
        DB::table('policy_claim_payment_recoveries_info')->where('id',$paymentId)->delete();
        DB::table('claim_log')->insert($logArray);
        
        return response()->json(array('status' => true, 'redirect' => action('Claim\ClaimController@claimOverviewDetails', ['claimId' => $claimId]))); 
    }

}
