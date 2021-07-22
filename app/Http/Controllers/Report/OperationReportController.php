<?php

namespace App\Http\Controllers\Report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Auth;
use App\Http\Classes\RequestFiltter;
use Session;
use Excel;

class OperationReportController extends Controller {

    /**
     * 
     * @return type
     */
    public function operationRequest() {
        $typeArray = [1 => 'Addition', 'CCHI Activation', 'Claim approval/Settlement', 'Deletion', 'Downgrade', 'Updated member list', 'Plate No Amendment', 'Card Replacment', 'CCHI Upload Status List', 'MC Certificate', 'Name Amendment',
            'Card Printer Request', 'Invoices Request', 'Upgrade', 'Request', 'Inquiry', 'announcement', 'Request sign', 'Others'];
        
          $statusArray = [1 => 'Open', 'Under process', 'Resolve', 'Pending from insurer', 'Pending from client'];        
          $statusArray[]= 'Completed with invoice';
          $statusArray[]= 'Completed by client request';
          $statusArray[]= 'Completed without invoice';
          $statusArray[]= 'Awaiting Invoice';
          $statusArray[10] = 'Close';
        Session::forget('operationrequestFilterCondition_' . Auth::user()->id);
        $data = array('typeArray' => $typeArray,'statusArray' =>$statusArray);

        return view('Reports/operationrequest', $data);
    }

    /**
     * 
     * @param Request $request
     * @return type
     */
    public function operationRequestFilter(Request $request) {

        $filterObj = new RequestFiltter();
        $filteredResult = $filterObj->getOperationRequest($request);
        $statusArray = [1 => 'Open', 'Under process', 'Resolve', 'Pending from insurer', 'Pending from client'];        
          $statusArray[]= 'Completed with invoice';
          $statusArray[]= 'Completed by client request';
          $statusArray[]= 'Completed without invoice';
          $statusArray[]= 'Awaiting Invoice';
          $statusArray[10] = 'Close';
//     echo "<pre>";
//     print_r($filteredResult);exit;
        $typeArray = [1 => 'Addition', 'CCHI Activation', 'Claim approval/Settlement', 'Deletion', 'Downgrade', 'Updated member list', 'Plate No Amendment', 'Card Replacment', 'CCHI Upload Status List', 'MC Certificate', 'Name Amendment',
            'Card Printer Request', 'Invoices Request', 'Upgrade', 'Request', 'Inquiry', 'announcement', 'Request sign', 'Others'];
        $data = array('typeArray' => $typeArray, "requestData" => $filteredResult, 'statusArray' =>$statusArray);

        return view('Reports/operationrequest', $data);
    }

    /**
     * 
     */
    public function requestExport() {

        $filterObj = new RequestFiltter();
        $request = array();
        $filteredResult = $filterObj->getOperationRequest($request, true);
        $requestArray[] = array('No:', 'Customer', 'Type', 'Policy', 'Status', 'Created date', 'Last updated date', 'Created by', 'Time taken');
        $requestTypeArray = ['', 'Addition', 'CCHI Activation', 'Claim approval/Settlement', 'Deletion', 'Downgrade', 'Updated member list', 'Plate No Amendment', 'Card Replacment', 'CCHI Upload Status List', 'MC Certificate', 'Name Amendment',
            'Card Printer Request', 'Invoices Request', 'Upgrade', 'Request', 'Inquiry', 'announcement', 'Request sign', 'Others'];
        foreach ($filteredResult as $result) {
            $requestArray [] = array(
                'No:' => $result->request_id,
                'Customer' => $result->customerName,
                'Type' => $requestTypeArray[$result->etype],
                'Policy' => $result->policy_number,
                'Status' => $result->statusString,
                'Created date' => date('d-m-Y', strtotime($result->createdAt)),
                'Last updated date' => date('d-m-Y', strtotime($result->updatedAt)),
                'Created by' => $result->userName,
                'Time taken' => $result->daydiff,
            );
        }

        Excel::create('Operationrequestdata_' . date('Ymdhis'), function($excel) use ($requestArray) {
            $excel->setTitle('Operation request details');
            $excel->sheet('Requests', function($sheet) use ($requestArray) {
                $sheet->fromArray($requestArray, null, 'A1', false, false);
                $sheet->setPageMargin(array(
                    0.25, 0.30, 0.25, 0.30
                ));
                $sheet->row(1, function($row) {

                    // call cell manipulation methods
                    $row->setBackground('#4F5467');
                    $row->setFontColor('#ffffff');
                    $row->setFontSize(16);
                    $row->setFontWeight('bold');
                });
            });
        })->download('xlsx');
    }

    /**
     * 
     * @return type
     */
    public function policyComplaint() {
        $statuseArray = [1 => 'Open', 'Closed'];
        $validityArray = [1 => 'Valid', 'Invalid'];
        Session::forget('policycompliantFilterCondition_' . Auth::user()->id);
        $data = array('statusArray' => $statuseArray, 'validityArray' => $validityArray);

        return view('Reports/complaintlist', $data);
    }

    /**
     * 
     * @param type $request
     * @return type
     */
    public function complaintFilter(Request $request) {
        $filterObj = new RequestFiltter();
        $filteredResult = $filterObj->getComplaintReport($request);
        $statuseArray = [1 => 'Open', 'Closed'];
        $validityArray = [1 => 'Valid', 'Invalid'];
        $data = array('statusArray' => $statuseArray, 'validityArray' => $validityArray, "complaintDetails" => $filteredResult);

        return view('Reports/complaintlist', $data);
    }

    /**
     * 
     */
    public function complaintExport() {
        $filterObj = new RequestFiltter();
        $request = array();
        $filteredResult = $filterObj->getComplaintReport($request, true);
        $requestArray[] = array('Complaint no:', 'Type', 'Client name', 'Policy', 'Requested date', 'Validity', 'Complaint handler', 'Status', 'Created date', 'Last updated date', 'Time taken');
        foreach ($filteredResult as $result) {
            $requestArray [] = array(
                'Complaint no:' => $result->id,
                'Type' => $result->complaintType,
                'Client name' => $result->clientName,
                'Policy' => $result->policy_number,
                'Requested date' => date('d-m-Y', strtotime($result->requested_date)),
                'Validity' => $result->complaintValidity,
                'Complaint handler' => $result->handleUser,
                'Status' => $result->statusString,
                'Created date' => date('d-m-Y', strtotime($result->created_at)),
                'Last updated date' => date('d-m-Y', strtotime($result->updated_at)),
                'Time taken' => $result->daydiff,
            );
        }

        Excel::create('Complaintdata_' . date('Ymdhis'), function($excel) use ($requestArray) {
            $excel->setTitle('Complaint details');
            $excel->sheet('Complaints', function($sheet) use ($requestArray) {
                $sheet->fromArray($requestArray, null, 'A1', false, false);
                $sheet->setPageMargin(array(
                    0.25, 0.30, 0.25, 0.30
                ));
                $sheet->row(1, function($row) {

                    // call cell manipulation methods
                    $row->setBackground('#4F5467');
                    $row->setFontColor('#ffffff');
                    $row->setFontSize(16);
                    $row->setFontWeight('bold');
                });
            });
        })->download('xlsx');
    }

    /**
     * 
     * @return type
     */
    public function claimReport() {
        $statuseArray = [1 => '[Open] Awaiting info/documents', '[Open]Claim reopened', '[Open] Awaiting repair approval from insurer', '[Open] Awaiting repair check from from insurer'];

        Session::forget('claimFilterCondition_' . Auth::user()->id);
        $data = array('statusArray' => $statuseArray);

        return view('Reports/listClaims', $data);
    }

    /**
     * 
     * @param type $request
     * @return type
     */
    public function claimFilter(Request $request) {
        $filterObj = new RequestFiltter();
        $filteredResult = $filterObj->getClaimReport($request);
        $statuseArray = [1 => '[Open] Awaiting info/documents', '[Open]Claim reopened', '[Open] Awaiting repair approval from insurer', '[Open] Awaiting repair check from from insurer'];

        $data = array('statusArray' => $statuseArray, "claimDetails" => $filteredResult);

        return view('Reports/listClaims', $data);
    }

    /**
     * 
     */
    public function claimExport() {
        $filterObj = new RequestFiltter();
        $request = array();
        $filteredResult = $filterObj->getClaimReport($request, true);
        $requestArray[] = array('Id', 'Policy number', 'Customer', 'Id code/Reg no', 'Status', 'Claimant', 'Claim handler', 'Loss date', 'Submitted date', 'Last updated date', 'Time taken');
        foreach ($filteredResult as $result) {
            $requestArray [] = array(
                'Id' => $result->id,
                'Policy number' => $result->policy_number,
                'Customer' => $result->customerName,
                'Id code/Reg no' => ($result->id_code != null) ? $result->id_code : '',
                'Status' => $result->statusString,
                'Claimant' => ($result->claimant != '') ? $this->generateClaimantString($result->claimant) : '',
                'Claim handler' => $result->claimHandler,
                'Loss date' => ($result->incident_date != null) ? date('d-m-Y h:i', strtotime($result->incident_date)) : '',
                'Submitted date' => date('d-m-Y', strtotime($result->submitted_broker_date)),
                'Last updated date' => date('d-m-Y', strtotime($result->updatedDate)),
                'Time taken' => $result->daydiff,
            );
        }

        Excel::create('Claimdata_' . date('Ymdhis'), function($excel) use ($requestArray) {
            $excel->setTitle('Claim details');
            $excel->sheet('Claims', function($sheet) use ($requestArray) {
                $sheet->fromArray($requestArray, null, 'A1', false, false);
                $sheet->setPageMargin(array(
                    0.25, 0.30, 0.25, 0.30
                ));
                $sheet->row(1, function($row) {

                    // call cell manipulation methods
                    $row->setBackground('#4F5467');
                    $row->setFontColor('#ffffff');
                    $row->setFontSize(16);
                    $row->setFontWeight('bold');
                });
            });
        })->download('xlsx');
    }

    /**
     * 
     * @param type $claimantString
     * @return type
     */
    private function generateClaimantString($claimantString) {

        $objectJson = json_decode($claimantString, true);
        $objectString = (count($objectJson) > 0) ? '' : '-';
        if (count($objectJson) > 0) {
            foreach ($objectJson as $jsonvalue) {
                foreach ($jsonvalue as $objkey => $value) {
                    $objectString .= ($value !== null) ? $objkey.':' . $value . "," : '';
                }
            }
        }
        return $objectString;
    }

    /**
     * 
     * @return type
     */
    public function endorsementReport() {
        $typeArray = [1 => 'Addition', 'CCHI Activation', 'Claim approval/Settlement', 'Deletion', 'Downgrade', 'Updated member list', 'Plate No Amendment', 'Card Replacment', 'CCHI Upload Status List', 'MC Certificate', 'Name Amendment',
            'Card Printer Request', 'Invoices Request', 'Upgrade', 'Request', 'Inquiry', 'announcement', 'Request sign', 'Others'];
        $statusArray[1] = "Waiting for approval";
        $statusArray[2] = "Approved";
        $statusArray[3] = "Reject";
        Session::forget('endorsementFilterCondition_' . Auth::user()->id);
        $data = array('typeArray' => $typeArray,'statusArray'=>$statusArray);



        return view('Reports/endorsementlist', $data);
    }

    /**
     * 
     * @return type
     */
    public function endorsementFilter(Request $request) {
        $typeArray = [1 => 'Addition', 'CCHI Activation', 'Claim approval/Settlement', 'Deletion', 'Downgrade', 'Updated member list', 'Plate No Amendment', 'Card Replacment', 'CCHI Upload Status List', 'MC Certificate', 'Name Amendment',
            'Card Printer Request', 'Invoices Request', 'Upgrade', 'Request', 'Inquiry', 'announcement', 'Request sign', 'Others'];
        $statusArray[1] = "Waiting for approval";
        $statusArray[2] = "Approved";
        $statusArray[3] = "Reject";

        $filterObj = new RequestFiltter();
        $filteredResult = $filterObj->getEndorsementReport($request);
        $data = array('typeArray' => $typeArray, "endorsementDetails" => $filteredResult, 'statusArray'=>$statusArray);

        return view('Reports/endorsementlist', $data);
    }

    /**
     * 
     */
    public function endorsementExport() {
        $filterObj = new RequestFiltter();
        $request = array();
        $filteredResult = $filterObj->getEndorsementReport($request, true);
        $requestArray[] = array('Policy', 'Endorsement no', 'Type', 'Issue date', 'Inception date','Expiry date','Due date', 'Amount','Status');
        foreach ($filteredResult as $result) {
            $requestArray [] = array(
                'Policy' => $result->policy_number,
                'Endorsement no' => $result->endorsement_number,
                'Type' => $result->typeString,
                'Issue date' => $result->formatted_issueDate,
                'Inception date' =>date('d-m-Y',strtotime($result->start_date)),
                'Expiry date'=> date('d-m-Y',strtotime($result->expiry_date)),
                "Due date"=> ($result->due_date !=null) ? date('d-m-Y',strtotime($result->due_date)) : '-', 
                'Amount' => floatval($result->amount),
                "Status"=>$result->statusString
            );
        }

        Excel::create('Endorsementdata_' . date('Ymdhis'), function($excel) use ($requestArray) {
            $excel->setTitle('Endorsement details');
            $excel->sheet('Endorsement', function($sheet) use ($requestArray) {
                $sheet->fromArray($requestArray, null, 'A1', false, false);
                $sheet->setPageMargin(array(
                    0.25, 0.30, 0.25, 0.30
                ));
                $sheet->row(1, function($row) {

                    // call cell manipulation methods
                    $row->setBackground('#4F5467');
                    $row->setFontColor('#ffffff');
                    $row->setFontSize(16);
                    $row->setFontWeight('bold');
                });
            });
        })->download('xlsx');
    }

}
