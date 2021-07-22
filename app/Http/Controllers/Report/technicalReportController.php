<?php

namespace App\Http\Controllers\Report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Auth;
use App\Http\Classes\RequestFiltter;
use Session;
use Excel;

class technicalReportController extends Controller {

    /**
     *
     * @return type
     */
    public function corporatepipelineReport() {
        Session::forget('pendingtechfilter_' . Auth::user()->id);
        Session::forget('policyrenewalfilter_0_' . Auth::user()->id);
        Session::forget('policyrenewalfilter_60_' . Auth::user()->id);
        Session::forget('policyrenewalfilter_90_' . Auth::user()->id);
        Session::forget('policyrenewalfilter_120_' . Auth::user()->id);
        Session::forget('underissauncefilter_' . Auth::user()->id);
        return view('Reports/coporatepipeline');
    }

    /**
     *
     * @param Request $request
     * @return type
     */
    public function corporatepipelineFilter(Request $request) {

        //pending with tech
        $filterObj = new RequestFiltter();
        $filteredResult = $filterObj->getTechnicalRequest($request);

        //renewal
        $renewalDetails = $filterObj->getRenewalDetails($request);


        //under issuance
        $underissuanceResult = $filterObj->getUnderIssuanceRequest($request);

        //lost request details
        $lostissuanceResult = $filterObj->getLostIssuanceRequest($request);
        


        return view('Reports/coporatepipeline', array('pendingwithtechDetails' => $filteredResult, 'underissuanceDetails' => $underissuanceResult, 'renewalDetails' => $renewalDetails, 'lostrequestDetails'=> $lostissuanceResult));
    }

    /**
     *
     * @param type $premiumValue
     * @return string
     */
    private function findClientSegment($premiumValue) {
        $segment = '';
        if ($premiumValue <= 500000) {
            $segment = "Small";
        } elseif ($premiumValue <= 3000000) {
            $segment = "Medium";
        } elseif ($premiumValue <= 10000000) {
            $segment = "Large";
        } elseif ($premiumValue > 10000000) {
            $segment = "Key Account";
        }
        return $segment;
    }

    /**
     *
     */
    public function pipelineExport() {
        $request = array();
        $filterObj = new RequestFiltter();
        $filteredResult = $filterObj->getTechnicalRequest($request, true);
        $renewalArray = $this->renewalReport();

        $underInsuranceArray = $this->underInsuanceReport();
        //lost request details
        $lostissuanceResult = $this->getLostIssuanceRequest($request,true);

        $requestArray[] = array('Client segmant', 'Type', 'Channel', 'Agent', 'LOB', 'Client', 'Requirement', 'Date of submission', 'Date of approach', 'Date of last action', 'Expiry date', 'Inception date', 'Current insurer', 'Do we have the renewal', 'Approx/premium', 'Tech owner');

        foreach ($filteredResult as $result) {

            $requestArray [] = array(
                'Client segmant' => ($result->premiumAmount > 0) ? $this->findClientSegment($result->premiumAmount) : 'Small',
                'Type' => ($result->renewal_status > 0) ? 'Renewal' : 'New',
                'Channel' => $result->channel,
                'Agent' => $result->agent,
                'LOB' => $result->lineofbusinesstitle,
                'Client' => $result->customerName,
                'Requirement' => '',
                'Date of submission' => ($result->technical_reporting_date != '') ? date('d-m-Y', strtotime($result->technical_reporting_date)) : '',
                'Date of approach' => ($result->broking_slip_send_date != '') ? date('d-m-Y', strtotime($result->broking_slip_send_date)) : '',
                'Date of last action' => ($result->lastUpdated != '') ? date('d-m-Y', strtotime($result->lastUpdated)) : '',
                'Expiry date' => ($result->expiryDate != '') ? date('d-m-Y', strtotime($result->expiryDate)) : '',
                'Inception date' => ($result->inceptiondate != '') ? date('d-m-Y', strtotime($result->inceptiondate)) : '',
                'Current insurer' => ($result->insurer_name != '') ? $result->insurer_name : '',
                'Do we have the renewal' => ($result->renewal_status > 0) ? 'Yes' : 'No',
                'Approx/premium' => ($result->premiumAmount != '') ? $result->premiumAmount : '',
                'Tech owner' => $result->handler,
            );
        }

        Excel::create('corporate_pipelinereport' . date('Ymdhis'), function($excel) use ($requestArray, $renewalArray, $underInsuranceArray, $lostissuanceResult) {
            //Pending with tech sheet creation area
            $excel->setTitle('Corporate pipeline report');
            $excel->sheet('Pending with tech', function($sheet) use ($requestArray) {
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
            //Under issuance sheet creation area
            $excel->sheet('Under Issuance', function($sheet) use ($underInsuranceArray) {
                $sheet->fromArray($underInsuranceArray, null, 'A1', false, false);
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
            $excel->sheet('Renewal', function($sheet) use ($renewalArray) {
                $sheet->fromArray($renewalArray, null, 'A1', false, false);
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
            
            //Lost request
             $excel->sheet('Lost', function($sheet) use ($lostissuanceResult) {
                $sheet->fromArray($lostissuanceResult, null, 'A1', false, false);
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
     * Renewal report creation array generation area
     * @return type
     */
    private function renewalReport($difference = 0) {
        $filterObj = new RequestFiltter();
        $request = array();
        $renewalDetails = $filterObj->getRenewalDetails($request, $difference, true);


        $requestArray[] = array('Client segmant', 'Channel', 'Agent', 'LOB', 'Client', 'Notes', 'BOR', 'LR', 'Renewal', 'Active list', 'OS Balance', 'Current Policy', 'Current Insurer', 'Current Premium', 'Expiry date', 'Renewal date', 'Remainig days', 'Tech owner');
        foreach ($renewalDetails as $result) {

            $requestArray [] = array(
                'Client segmant' => ($result->premiumAmount > 0) ? $this->findClientSegment($result->premiumAmount) : 'Small',
                'Channel' => $result->channel,
                'Agent' => $result->agent,
                'LOB' => $result->lineofbusinesstitle,
                'Client' => $result->customerName,
                'Notes' => $result->information,
                'BOR' => '',
                'LR' => '',
                'Renewal' => ($result->renewal_status > 0) ? 'YES' : 'NO',
                'Active list' => ($result->datediff > 0) ? 'YES' : 'NO',
                'OS Balance' => '',
                'Current Policy' => $result->policy_number,
                'Current insurer' => ($result->insurer_name != '') ? $result->insurer_name : '',
                'Current Premium' => $result->premiumAmount,
                'Expiry date' => ($result->expirydate != '') ? date('d-m-Y', strtotime($result->expirydate)) : '',
                'Renewal date' => ($result->expirydate != '') ? date('d-m-Y', strtotime('-1 day', strtotime($result->expirydate))) : '',
                'Remainig days' => $result->datediff,
                'Tech owner' => $result->handler
            );
        }

        return $requestArray;
    }

    /**
     * UnderIssuance report creation array generation area
     */
    private function underInsuanceReport() {
        //under issuance
        $filterObj = new RequestFiltter();
        $request = array();
        $underissuanceResult = $filterObj->getUnderIssuanceRequest($request, true);
        $requestArray[] = array('Client segmant', 'Type', 'Channel', 'Agent', 'LOB', 'Client', 'Inception date', 'Date of last action', 'Current Premium', 'Tech owner');
        foreach ($underissuanceResult as $result) {

            $requestArray [] = array(
                'Client segmant' => ($result->premiumAmount > 0) ? $this->findClientSegment($result->premiumAmount) : 'Small',
                'Type' => ($result->renewal_status > 0) ? 'Renewal' : 'New',
                'Channel' => $result->channel,
                'Agent' => $result->agent,
                'LOB' => $result->lineofbusinesstitle,
                'Client' => $result->customerName,
                'Inception date' => ($result->inceptiondate != '') ? date('d-m-Y', strtotime($result->inceptiondate)) : '',
                'Date of last action' => ($result->lastUpdated != '') ? date('d-m-Y', strtotime($result->lastUpdated)) : '',
                'Current Premium' => floatval($result->premiumAmount),
                'Tech owner' => $result->handler
            );
        }

        return $requestArray;
    }

    /**
     *
     * @return type
     */
    public function productionReport() {
        Session::forget('productionfilter_' . Auth::user()->id);
        Session::forget('clientsegmantfilter_' . Auth::user()->id);
        Session::forget('endorsementsegmantfilter_' . Auth::user()->id);

        return view('Reports/production');
    }

    /**
     *
     * @param Request $request
     * @return type
     */
    public function productionFilter(Request $request) {

        //pending with tech
        $filterObj = new RequestFiltter();
        $filteredResult = $filterObj->getProductionDetails($request);
        //renewal

        $segmantDetails = $filterObj->getClientSegmantDetails($request);

        $endorsementsegmantDetails = $filterObj->getEndorsementSegmantDetails($request);



        return view('Reports/production', array('productionDetails' => $filteredResult, 'clientsegmantDetails' => $segmantDetails, 'endorsementDetails' => $endorsementsegmantDetails));
    }

    /**
     * To export the production details
     */
    public function productionExport() {

        $productionDetails = $this->productionDetails();

        $segmantArray = $this->segmantDetails();
        $endorsementsegmant = $this->endorsementDetails();



        Excel::create('production_report' . date('Ymdhis'), function($excel) use ($productionDetails, $segmantArray, $endorsementsegmant) {
            //Pending with tech sheet creation area
            $excel->setTitle('Production report');
            $excel->sheet('Production', function($sheet) use ($productionDetails) {
                $sheet->fromArray($productionDetails, null, 'A1', false, false);
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
            //Endorsement segmant

            $excel->sheet('Endorsement segmant', function($sheet) use ($endorsementsegmant) {
                $sheet->fromArray($endorsementsegmant, null, 'A1', false, false);
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


            //Under issuance sheet creation area
            $excel->sheet('Client segmant', function($sheet) use ($segmantArray) {
                $sheet->fromArray($segmantArray, null, 'A1', false, false);
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
     */
    private function productionDetails() {
        $request = array();
        $filterObj = new RequestFiltter();
        $filteredResult = $filterObj->getProductionDetails($request, true);


        $requestArray[] = array('Policy Segment', 'Client Segmant', 'LOB', 'Product', 'Channel', 'Agent Name', 'Type', 'Sub Type', 'Year Of inception', 'Month of inception', 'Type of client', 'Client', 'Insurer', 'Policy #', 'Q', 'CANCELATION', 'Date of issuance', 'Date of inception', 'Date of Expiry', 'Renewals', 'Total SI /AGG', 'Number of fleet / Members', 'Premium', 'Fees', 'VAT 5%', 'Total Premium', 'Commission %', 'Commission');
        foreach ($filteredResult as $result) {

            $requestArray [] = array(
                'Policy Segment' => ($result->premiumAmount > 0) ? $this->findClientSegment($result->premiumAmount) : 'Small',
                'Client Segmant' => ($result->premiumAmount > 0) ? $this->findClientSegment($result->premiumAmount) : 'Small',
                'LOB' => $result->lineofbusinesstitle,
                'Product' => $result->product_name,
                'Channel' => $result->channel,
                'Agent' => $result->agent,
                'Type' => ($result->renewal_status > 0) ? 'YES' : 'NO',
                'Sub Type' => '',
                'Year Of inception' => ($result->inceptiondate != '') ? date('Y', strtotime($result->inceptiondate)) : '',
                'Month of inception' => ($result->inceptiondate != '') ? date('M', strtotime($result->inceptiondate)) : '',
                'Type of client' => $result->customer_group,
                'Client' => $result->customerName,
                'Insurer' => $result->insurer_name,
                'Policy #' => $result->policy_number,
                'Q' => 'Q1',
                'CANCELATION' => '',
                'Date of issuance' => ($result->issue_date != '') ? date('d-m-Y', strtotime($result->issue_date)) : '',
                'Date of inception' => ($result->inceptiondate != '') ? date('d-m-Y', strtotime($result->inceptiondate)) : '',
                'Date of Expiry' => ($result->expirydate != '') ? date('d-m-Y', strtotime($result->expirydate)) : '',
                'Renewals' => ($result->expirydate != '') ? date('d-m-Y', strtotime('-1 day', strtotime($result->expirydate))) : '',
                'Total SI /AGG' => floatval($result->insured_sum),
                'Number of fleet / Members' => $result->no_of_members,
                'Premium' => $result->total_premium,
                'Fees' => $result->additional_amount,
                'VAT 5%' => $result->vat_amount,
                'Total Premium' => $result->total_premium + $result->additional_amount + $result->vat_amount,
                'Commission %' => floatval($result->commision),
                'Commission' => floatval(($result->total_premium * $result->commision) / 100)
            );
        }

        return $requestArray;
    }

    /**
     *
     * @return type
     */
    private function segmantDetails() {
        $request = array();
        $filterObj = new RequestFiltter();
        $segmantDetails = $filterObj->getClientSegmantDetails($request, true);
        $requestArray[] = array('Client', 'Sum of premium', 'Segmant');
        foreach ($segmantDetails as $result) {

            $requestArray [] = array(
                'Client' => $result->customerName,
                'Sum of premium' => $result->premiumAmount,
                'Segmant' => ($result->premiumAmount > 0) ? $this->findClientSegment($result->premiumAmount) : 'Small',
            );
        }

        return $requestArray;
    }

    /**
     *
     * @return type
     */
    private function endorsementDetails() {
        $request = array();
        $filterObj = new RequestFiltter();
        $segmantDetails = $filterObj->getEndorsementSegmantDetails($request, true);

        $requestArray[] = array('Client', 'Insurer', 'Policy', 'Endorsement number', 'Endorsement type', 'Issue date', 'Date of inception', 'End date', 'Amount', 'Vat amount', 'Endorsement count');
        foreach ($segmantDetails as $result) {

            $requestArray [] = array(
                'Client' => $result->customerName,
                'Insurer' => $result->insurer_name,
                'Policy #' => $result->policy_number,
                'Endorsement number' => $result->endorsement_number,
                'Endorsement type' => $result->typeString,
                'Issue date' => ($result->issue_date != '') ? date('d-m-Y', strtotime($result->issue_date)) : '',
                'Date of inception' => ($result->endorsementStartDate != '') ? date('d-m-Y', strtotime($result->endorsementStartDate)) : '',
                'End date' => ($result->end_date != '') ? date('d-m-Y', strtotime($result->end_date)) : '',
                'Amount' => $result->amount,
                'Vat amount' => $result->vatAmount,
                'Endorsement count' => $result->endorsement_count
            );
        }

        return $requestArray;
    }

    /**
     * Display renewal details generating page
     * @return type
     */
    public function allrenewalReport() {

        Session::forget('policyrenewalfilter_0_' . Auth::user()->id);
        Session::forget('policyrenewalfilter_60_' . Auth::user()->id);
        Session::forget('policyrenewalfilter_90_' . Auth::user()->id);
        Session::forget('policyrenewalfilter_120_' . Auth::user()->id);

        return view('Reports/renewalreportall');
    }

    /**
     *
     * @param Request $request
     * @return type
     */
    public function renewalFilter(Request $request) {
        $filterObj = new RequestFiltter();
        $renewalDetails60 = $filterObj->getRenewalDetails($request, 60);

        $renewalDetails90 = $filterObj->getRenewalDetails($request, 90);

        $renewalDetails120 = $filterObj->getRenewalDetails($request, 120);


        return view('Reports/renewalreport', array('renewalDetails60' => $renewalDetails60, 'renewalDetails90' => $renewalDetails90, 'renewalDetails120' => $renewalDetails120));
    }

    /**
     * To export renewal details
     */
    public function renewalDaysExport() {
        $request = array();
        $renewalDetails60 = $this->renewalReport(60);
        $renewalDetails90 = $this->renewalReport(90);
        $renewalDetails120 = $this->renewalReport(120);


        Excel::create('renewalreport' . date('Ymdhis'), function($excel) use ($renewalDetails60, $renewalDetails90, $renewalDetails120) {
            //Pending with tech sheet creation area
            $excel->setTitle('Renewal report');
            //Renewal sheet creation area
            $excel->sheet('Under 60', function($sheet) use ($renewalDetails60) {
                $sheet->fromArray($renewalDetails60, null, 'A1', false, false);
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


            $excel->sheet('Under 90', function($sheet) use ($renewalDetails90) {
                $sheet->fromArray($renewalDetails90, null, 'A1', false, false);
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
            $excel->sheet('Under 120', function($sheet) use ($renewalDetails120) {
                $sheet->fromArray($renewalDetails120, null, 'A1', false, false);
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
     * To export renewal details
     */
    public function renewalExport() {
        $request = array();
        $filterObj = new RequestFiltter();
        $renewalArray = $this->renewalReport();
        Excel::create('renewalreport' . date('Ymdhis'), function($excel) use ($renewalArray) {
            //Pending with tech sheet creation area
            $excel->setTitle('Renewal report');
            //Renewal sheet creation area
            $excel->sheet('Renewal', function($sheet) use ($renewalArray) {
                $sheet->fromArray($renewalArray, null, 'A1', false, false);
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
     * To display quote report generating page
     * @return type
     */
    public function quoteReport() {

        Session::forget('quotefilter_' . Auth::user()->id);

        return view('Reports/quotereport');
    }

    /**
     * To filter quote details
     * @param Request $request
     * @return type
     */
    public function quoteFilter(Request $request) {
        $filterObj = new RequestFiltter();
        $quoteDetails = $filterObj->getQuoteDetails($request);
        return view('Reports/quotereport', array('quoteDetails' => $quoteDetails));
    }

    /**
     * To export quote details of quotes
     */
    public function quoteExport() {
        $request = array();
        $filterObj = new RequestFiltter();
        $request = array();
        $quotesDetails[] = array('Client', 'LOB', 'Channel', 'AGENT', 'DATE OF SUBMISSION', 'DATE OF APPROCHMENT', 'DATE OF RECEIVING QUOTATION-REMARKS', 'DATE OF SENT TO CLIENT-SALES', 'DURATION TAKEN BY MARKET (DAYS)', 'DURATION TAKEN BY TECHNICAL (DAYS)', 'Insurer', 'COMPARISON', 'Status');
        $quoteArray = $filterObj->getQuoteDetails($request, true);

        foreach ($quoteArray as $result) {
            $quotesDetails[] = array(
                'Client' => $result->customerName,
                'LOB' => $result->lineofbusinesstitle,
                'Channel' => $result->channel,
                'AGENT' => $result->agent,
                'DATE OF SUBMISSION' => ($result->technical_reporting_date != '') ? date('d-m-Y', strtotime($result->technical_reporting_date)) : '',
                'DATE OF APPROCHMENT' => ($result->submissionDate != '') ? date('d-m-Y', strtotime($result->submissionDate)) : '',
                'DATE OF RECEIVING QUOTATION-REMARKS' => $result->additional_desc,
                'DATE OF SENT TO CLIENT-SALES' => ($result->updated_at != '') ? date('d-m-Y', strtotime($result->updated_at)) : '',
                'DURATION TAKEN BY MARKET (DAYS)' => $result->salesdaydiff,
                'DURATION TAKEN BY TECHNICAL (DAYS)' => $result->technicaldaydiff,
                'Insurer' => $result->insurer_name,
                'COMPARISON' => ($result->comparisonDoc != '') ? 'Yes' : 'No',
                'Status' => $result->qstatus,
            );
        }



        Excel::create('quotereport' . date('Ymdhis'), function($excel) use ($quotesDetails) {
            //Pending with tech sheet creation area
            $excel->setTitle('Quote report');
            //Renewal sheet creation area
            $excel->sheet('Quotes', function($sheet) use ($quotesDetails) {
                $sheet->fromArray($quotesDetails, null, 'A1', false, false);
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
     * @param array $request
     * @return type
     */
    public function getLostIssuanceRequest($request) {
        $filterObj = new RequestFiltter();
        $request = array();
        $underissuanceResult = $filterObj->getLostIssuanceRequest($request, true);
        $requestArray[] = array('Client segmant', 'Type', 'Channel', 'Agent', 'LOB', 'Client', 'Inception date', 'Date of last action', 'Current Premium', 'Tech owner');
        foreach ($underissuanceResult as $result) {

            $requestArray [] = array(
                'Client segmant' => ($result->premiumAmount > 0) ? $this->findClientSegment($result->premiumAmount) : 'Small',
                'Type' => ($result->renewal_status > 0) ? 'Renewal' : 'New',
                'Channel' => $result->channel,
                'Agent' => $result->agent,
                'LOB' => $result->lineofbusinesstitle,
                'Client' => $result->customerName,
                'Inception date' => ($result->inceptiondate != '') ? date('d-m-Y', strtotime($result->inceptiondate)) : '',
                'Date of last action' => ($result->lastUpdated != '') ? date('d-m-Y', strtotime($result->lastUpdated)) : '',
                'Current Premium' => floatval($result->premiumAmount),
                'Tech owner' => $result->handler
            );
        }

        return $requestArray;
    }

}
