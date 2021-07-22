<?php

namespace App\Http\Controllers\Report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Auth;
use App\Http\Classes\FinanceFiltter;
use Session;
use Excel;

class FinanceReportController extends Controller {

    /**
     * 
     * @return type
     */
    public function invoiceDetails() {
        $statusArray = array('0' => 'Invoice created', '1' => 'Paid');
        Session::forget('invoicefilter_' . Auth::user()->id);

        return view('Reports/financeinvoice', array('statusArray' => $statusArray));
    }

    /**
     * 
     * @param Request $request
     * @return type
     */
    public function invoiceFilter(Request $request) {

        $filterObj = new FinanceFiltter();
        $filteredResult = $filterObj->getFinanceRequest($request);
        $statusArray = array('0' => 'Invoice created', '1' => 'Paid');
        $data = array('statusArray' => $statusArray, "invoiceDetails" => $filteredResult);

        return view('Reports/financeinvoice', $data);
    }

    /**
     * 
     */
    public function invoiceExport() {

        $filterObj = new FinanceFiltter();
        $request = array();
        $filteredResult = $filterObj->getFinanceRequest($request, true);
        $requestArray[] = array('Invoice No:', 'Customer', 'Policy', 'Invoice date', 'Invoice amount', 'Status', 'Last action', 'Last action date', 'Remarks', 'Payment date');

        foreach ($filteredResult as $result) {
            $requestArray [] = array(
                'Invoice No:' => $result->invoiceId,
                'Customer' => $result->name,
                'Policy' => $result->policy_number,
                'Invoice date' => date('d-m-Y', strtotime($result->invoice_due_date)),
                'Invoice amount' => floatval($result->invoice_sum),
                'Status' => $result->invoiceStatusString,
                'Last action' => $result->lastAction,
                'Last action date' => ($result->lastactionDate != '') ? date('d-m-Y', strtotime($result->lastactionDate)) : '',
                'Remarks' => $result->remarks,
                'Payment date' => ($result->paymentDate != '') ? date('d-m-Y', strtotime($result->paymentDate)) : ''
            );
        }

        Excel::create('invoicerequestdata_' . date('Ymdhis'), function($excel) use ($requestArray) {
            $excel->setTitle('Invoice request details');
            $excel->sheet('Invoice', function($sheet) use ($requestArray) {
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

    public function productionDetails() {
        $statusArray = array('0' => 'Policy', '1' => 'Endorsement');
        Session::forget('financeproduction_' . Auth::user()->id);
        Session::forget('financeendorsement_' . Auth::user()->id);

        return view('Reports/financeproduction', array('statusArray' => $statusArray));
    }

    /**
     * 
     * @param Request $request
     * @return type
     */
    public function productionFilter(Request $request) {

        $filterObj = new FinanceFiltter();
        $filteredResult = $filterObj->getProductionRequest($request, 'financeproduction_');
        $endorsementDetails = $filterObj->getEndorsementRequest($request, 'financeendorsement_');

        $statusArray = array('0' => 'Policy', '1' => 'Endorsement');
        $data = array('statusArray' => $statusArray, "productionDetails" => $filteredResult, 'endorsementDetails' => $endorsementDetails);

        return view('Reports/financeproduction', $data);
    }

    /**
     * 
     */
    public function productionExport() {

        $filterObj = new FinanceFiltter();
        $request = array();
        $filteredResult = $filterObj->getProductionRequest($request, 'financeproduction_', true);
        $endorsementDetails = $filterObj->getEndorsementRequest($request, 'financeendorsement_', true);

        //dd($filteredResult);

        $requestArray[] = array('Policy no:', 'Validity', 'Customer', 'Issue date', 'Amount', 'Vat');
        $endorsementArray[] = array('Policy no:','Type', 'Endorsement number', 'Validity', 'Customer', 'Issue date', 'Amount', 'Vat');



        foreach ($filteredResult as $result) {
            $requestArray [] = array(
                'Policy no:' => $result->policy_number,
                'Validity' => (date('d-m-Y', strtotime($result->inceptiondate))) . '-' . (date('d-m-Y', strtotime($result->expirydate))),
                'Customer' => $result->customerName,
                'Issue date' => date('d-m-Y', strtotime($result->policyissuedate)),
                'Amount' => $result->premiumAmount,
                'Vat' => $result->policyvatAmount
            );
        }

        foreach ($endorsementDetails as $result) {
            $endorsementArray [] = array(
                'Policy no:' => $result->policy_number,
                'Type' => $result->product_name,
                'Endorsement number' => ($result->endorsement_number != '') ? $result->endorsement_number : '',
                'Validity' => (date('d-m-Y', strtotime($result->endorsementStart))) . '-' . (date('d-m-Y', strtotime($result->expirydate))),
                'Customer' => $result->customerName,
                'Issue date' => date('d-m-Y', strtotime($result->endorsementIssuedate)),
                'Amount' => $result->premiumAmount,
                'Vat' => $result->endorsementvatAmount
            );
        }



        Excel::create('financeproductiondata_' . date('Ymdhis'), function($excel) use ($requestArray, $endorsementArray) {
            $excel->setTitle('Production details');
            $excel->sheet('Policies', function($sheet) use ($requestArray) {
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

            //Renewal sheet creation area
            $excel->sheet('Endorsement', function($sheet) use ($endorsementArray) {
                $sheet->fromArray($endorsementArray, null, 'A1', false, false);
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

    public function installmentDetails() {
        $statusArray = array('0' => 'Unpaid', '1' => 'Paid');
        Session::forget('financeinstallment_' . Auth::user()->id);

        return view('Reports/financeinstallment', array('statusArray' => $statusArray));
    }

    /**
     * 
     * @param Request $request
     * @return type
     */
    public function installmentFilter(Request $request) {

        $filterObj = new FinanceFiltter();
        $filteredResult = $filterObj->getInstallmentRequest($request);
        $statusArray = array(0 => 'Unpaid', 1 => 'Paid');
        $data = array('statusArray' => $statusArray, "installmentDetails" => $filteredResult);

        return view('Reports/financeinstallment', $data);
    }

    /**
     * 
     */
    public function installmentExport() {

        $filterObj = new FinanceFiltter();
        $request = array();
        $filteredResult = $filterObj->getInstallmentRequest($request, true);

        $requestArray[] = array('Policy no:', 'Customer', 'End date', 'Due date', 'Amount', 'Vat', 'Vat amount', 'Total amount', 'Status');



        foreach ($filteredResult as $result) {
            $requestArray [] = array(
                'Policy no:' => $result->policy_number,
                'Customer' => $result->customerName,
                'End date' => ($result->instEnddate !== null) ? date('d-m-Y', strtotime($result->instEnddate)) : date('d-m-Y', strtotime($result->instEnddate)),
                'Due date' => ($result->instDuedate !== null) ? date('d-m-Y', strtotime($result->instDuedate)) : date('d-m-Y', strtotime($result->instDuedate)),
                'Amount' => $result->instAmount,
                'Vat (Perventage)' => $result->instvatpercentage,
                'Vat  Amount' => ($result->instVatamount !== null) ? $result->instVatamount : $result->instVatamount,
                'Total amount' => $result->instAmount + $result->instVatamount,
                'Status' => ($result->instPaidstatus == 0) ? 'Unpaid' : 'Paid'
            );
        }

        Excel::create('financeinstallmentdata_' . date('Ymdhis'), function($excel) use ($requestArray) {
            $excel->setTitle('Installment details');
            $excel->sheet('Installment', function($sheet) use ($requestArray) {
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

    public function postrequestDetails() {
        $statusArray = array('0' => 'Policy', '1' => 'Endorsement');
        $poststatusArray = array(1 => 'Waiting for approval', 2 => 'Approved');
        Session::forget('postpolicyrequest_' . Auth::user()->id);
        Session::forget('postendorsementrequest_' . Auth::user()->id);

        return view('Reports/postrequest', array('statusArray' => $statusArray, 'poststatusArray' => $poststatusArray));
    }

    /**
     * 
     * @param Request $request
     * @return type
     */
    public function postrequestFilter(Request $request) {

        $filterObj = new FinanceFiltter();
        $filteredResult = $filterObj->getProductionRequest($request, 'postpolicyrequest_');
        $endorsementDetails = $filterObj->getEndorsementRequest($request, 'postendorsementrequest_');

        $statusArray = array('0' => 'Policy', '1' => 'Endorsement');

        $poststatusArray = array(1 => 'Waiting for approval', 2 => 'Approved');
        $data = array('statusArray' => $statusArray, "productionDetails" => $filteredResult, 'endorsementDetails' => $endorsementDetails, 'poststatusArray' => $poststatusArray);

        return view('Reports/postrequest', $data);
    }

    /**
     * 
     */
    public function postrequestExport() {

        $filterObj = new FinanceFiltter();
        $request = array();
        $filteredResult = $filterObj->getProductionRequest($request, 'postpolicyrequest_', true);
        $endorsementDetails = $filterObj->getEndorsementRequest($request, 'postendorsementrequest_', true);

        $requestArray[] = array('Policy no:', 'Validity', 'Customer', 'Issue date', 'Amount', 'Vat');
        $endorsementArray[] = array('Policy no:', 'Endorsement number', 'Validity', 'Customer', 'Issue date', 'Amount', 'Vat');



        foreach ($filteredResult as $result) {
            $requestArray [] = array(
                'Policy no:' => $result->policy_number,
                'Validity' => (date('d-m-Y', strtotime($result->inceptiondate))) . '-' . (date('d-m-Y', strtotime($result->expirydate))),
                'Customer' => $result->customerName,
                'Issue date' => date('d-m-Y', strtotime($result->policyissuedate)),
                'Amount' => $result->premiumAmount,
                'Vat' => $result->policyvatAmount
            );
        }

        foreach ($endorsementDetails as $result) {
            $endorsementArray [] = array(
                'Policy no:' => $result->policy_number,
                'Endorsement number' => ($result->endorsement_number != '') ? $result->endorsement_number : '',
                'Validity' => (date('d-m-Y', strtotime($result->endorsementStart))) . '-' . (date('d-m-Y', strtotime($result->expirydate))),
                'Customer' => $result->customerName,
                'Issue date' => date('d-m-Y', strtotime($result->endorsementIssuedate)),
                'Amount' => $result->premiumAmount,
                'Vat' => $result->endorsementvatAmount
            );
        }



        Excel::create('postrequestdata_' . date('Ymdhis'), function($excel) use ($requestArray, $endorsementArray) {
            $excel->setTitle('Post request details');
            $excel->sheet('Policies', function($sheet) use ($requestArray) {
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

            //Renewal sheet creation area
            $excel->sheet('Endorsement', function($sheet) use ($endorsementArray) {
                $sheet->fromArray($endorsementArray, null, 'A1', false, false);
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
