<?php

namespace App\Http\Controllers\Report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Auth;
use App\Http\Classes\RequestFiltter;
use Session;
use Excel;

class SaleReportController extends Controller {

    /**
     * 
     * @return type
     */
    public function salesRequest() {
        $quoteSqlObj = DB::table('crm_main_table as r')
                ->leftJoin('crm_task_table as t', 'r.id', '=', 't.crm_main_id')
                ->leftJoin('crm_request_table as rt', 'r.id', '=', 'rt.crm_id')
                ->leftJoin('customers as c', 'c.id', '=', 'r.customer_id')
                ->orderBy('c.updated_at', 'desc')
                ->select('r.*', 'r.id as mainId', 'c.name as customerName', 't.*', 'rt.*', DB::raw("(case r.status when '0' then 'New' when '1' then 'New' when '2' then 'Technical review' when '3' then 'Document approved' when '4' then 'Quote upload'  when '5' then 'Revise quotation'  when '6' then 'Request policy'  when '7' then 'Policy upload'  when '8' then 'Reject'  when '9' then 'Completed' when '10' then 'Lost' when '11' then 'Pending with sales' else 'Pending with client' end) AS statusString"), DB::raw('DATEDIFF(r.created_date,r.updated_date) as daydiff'));
                

        if (in_array('ROLE_SALES_MANAGER', Auth::user()->roles) || in_array('ROLE_TECHNICAL_MANAGER', Auth::user()->roles)) {
            
        } else if (in_array('ROLE_SALES', Auth::user()->roles) || in_array('ROLE_TECHNICAL', Auth::user()->roles)) {
            $quoteSqlObj->where('r.user_id', Auth::user()->id)->orWhere('r.assigned_to', Auth::user()->id);
        } else {
            
        }
        $quoteRequest = $quoteSqlObj->get();
        $statusArray = ['New' => json_encode([0, 1]), 'Pending with technical department' => json_encode([2, 3]), 'quote uploaded' => '4', 'revise quotation' => '5', 'request policy' => '6', 'policy uploaded' => '7', 'reject' => '8', 'completed' => '9', 'lost' => '10','Pending with sales'=>'11','Pending with client'=>'12'];
        Session::forget('salesrequestFilterCondition_' . Auth::user()->id);
        $data = array('statusArray' => $statusArray, "requestData" => $quoteRequest);

        return view('Reports/salesrequest', $data);
    }

    /**
     * 
     * @param Request $request
     * @return type
     */
    public function salesRequestFilter(Request $request) {

        $filterObj = new RequestFiltter();
        $filteredResult = $filterObj->getSalesRequest($request);
        $statusArray = ['New' => json_encode([0, 1]), 'Pending with technical department' => json_encode([2, 3]), 'quote uploaded' => '4', 'revise quotation' => '5', 'request policy' => '6', 'policy uploaded' => '7', 'reject' => '8', 'completed' => '9', 'lost' => '10','Pending with sales'=>'11','Pending with client'=>'12'];
        $data = array('statusArray' => $statusArray, "requestData" => $filteredResult);

        return view('Reports/salesrequest', $data);
    }

    /**
     * 
     */
    public function salesRequestExport() {

        $filterObj = new RequestFiltter();
        $request = array();
        $filteredResult = $filterObj->getSalesRequest($request, true);
        $requestArray[] = array('Request No', 'Customer name', 'Description', 'Status', 'Created date', 'Updated date', 'Time taken');
        foreach ($filteredResult as $result) {
            $requestArray [] = array('Request No' => $result->crm_request_id,
                'Customer name' => $result->customerName,
                'Description' => ($result->type == 0) ? $result->subject : $result->description,
                'Status' => $result->statusString,
                'Created date' => date('d-m-Y', strtotime($result->created_date)),
                'Updated date' => date('d-m-Y', strtotime($result->updated_date)),
                'Time taken' => $result->daydiff
            );
        }

        Excel::create('Sales request data_' . date('Ymdhis'), function($excel) use ($requestArray) {
            $excel->setTitle('Sales request data');
            $excel->sheet('Sales request', function($sheet) use ($requestArray) {
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
    public function salesLeads() {

        Session::forget('salesleadFilterCondition_' . Auth::user()->id);

        return view('Reports/saleslead');
    }

    /**
     * 
     * @param Request $request
     */
    public function leadFilter(Request $request) {
        $filterObj = new RequestFiltter();
        $filteredResult = $filterObj->getLeadsData($request, 'lead');
        $data = array("customerDetails" => $filteredResult);

        return view('Reports/saleslead', $data);
    }

    /**
     * 
     */
    public function leadExport() {

        $filterObj = new RequestFiltter();
        $request = array();
        $filteredResult = $filterObj->getLeadsData($request, 'lead', true);
        $requestArray[] = array('Name', 'Type', 'Lead group', 'Account manager', 'Email', 'Mobile', 'Created at');
        foreach ($filteredResult as $result) {
            $requestArray [] = array(
                'Name' => $result->customerName,
                'Type' => ($result->type == 0) ? 'Individual' : 'Customer',
                'Lead group' => $result->customer_group,
                'Account manager' => $result->userName,
                'Email' => $result->customerEmail,
                'Mobile' => $result->mobile,
                'Created at' => $result->createdAt
            );
        }

        Excel::create('Sales leads data_' . date('Ymdhis'), function($excel) use ($requestArray) {
            $excel->setTitle('Sales leads data');
            $excel->sheet('Leads', function($sheet) use ($requestArray) {
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
    public function salesCustomer() {

        Session::forget('salesleadFilterCondition_' . Auth::user()->id);

        return view('Reports/salescustomer');
    }

    /**
     * 
     * @param Request $request
     */
    public function customerfilter(Request $request) {
        $filterObj = new RequestFiltter();
        $filteredResult = $filterObj->getLeadsData($request, 'customer');
        $data = array("customerDetails" => $filteredResult);

        return view('Reports/salescustomer', $data);
    }

    /**
     * 
     */
    public function customerexport() {

        $filterObj = new RequestFiltter();
        $request = array();
        $filteredResult = $filterObj->getLeadsData($request, 'customer', true);
        $requestArray[] = array('Name', 'Type', 'Lead group', 'Account manager', 'Email', 'Mobile', 'Policy', 'Issue date', 'End date', 'Created at');
        foreach ($filteredResult as $result) {
            $requestArray [] = array(
                'Name' => $result->customerName,
                'Type' => ($result->type == 0) ? 'Individual' : 'Customer',
                'Lead group' => $result->customer_group,
                'Account manager' => $result->userName,
                'Email' => $result->customerEmail,
                'Mobile' => $result->mobile,
                'Policy' => $result->policy_number,
                'Issue date' => date('d-m-Y', strtotime($result->issue_date)),
                'End date' => date('d-m-Y', strtotime($result->end_date)),
                'Created at' => $result->createdAt
            );
        }

        Excel::create('Customer data_' . date('Ymdhis'), function($excel) use ($requestArray) {
            $excel->setTitle('Customers data');
            $excel->sheet('Customer', function($sheet) use ($requestArray) {
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
