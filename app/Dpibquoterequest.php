<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Dpibquoterequest extends Model {

    protected $table = 'dpib_quote_request';
    public $timestamps = false;

    public function getQuoterequestList($customerId, $statusArray) {
        $quoteRequest = DB::table('crm_main_table as r')
                ->leftJoin('crm_task_table as t', 'r.id', '=', 't.crm_main_id')
                ->leftJoin('crm_request_table as rt', 'r.id', '=', 'rt.crm_id')
                ->leftJoin('customers as c', 'c.id', '=', 'r.customer_id') 
                ->leftJoin('users as u', 'u.id', '=', 'r.assigned_to')              
                ->orderBy('c.updated_at', 'desc')
                ->where('r.customer_id', $customerId) 
                ->whereIn('r.status', $statusArray)
                ->select('r.*','r.id as mainId', 'c.name as customerName','u.name as assigned','t.*','rt.*',  DB::raw("(case r.status when '0' then 'open' when '1' then 'under process' when '2' then 'Technical review' when '3' then 'Document approved' when '4' then 'Quote upload'  when '5' then 'Revise quotation'  when '6' then 'Request policy'  when '7' then 'Policy upload'  when '8' then 'Reject'  when '9' then 'Completed'  when '10' then 'Lost' when '11' then 'Pending with sales' else 'Pending with client' end) AS statusString"))
                ->groupBy('r.id')
                ->get();
        return $quoteRequest;
    }
/**
 * 
 * @param type $requestId
 * @return type
 */
    public function quoteRequestDetail($requestId) {
        $quoteRequest = DB::table('crm_main_table as r')
                ->leftJoin('crm_task_table as t', 'r.id', '=', 't.crm_main_id')
                ->leftJoin('policies as p', 'p.id', '=', 'r.policy_id')
                ->leftJoin('crm_request_table as rt', 'r.id', '=', 'rt.crm_id')
                ->leftJoin('customers as c', 'c.id', '=', 'r.customer_id')  
                ->leftJoin('users as u', 'r.user_id', '=', 'u.id')
                ->leftJoin('users as du', 'r.assigned_to', '=', 'du.id')
                ->leftJoin('line_of_business as lb','lb.id','=','r.crm_line_of_business')
                ->orderBy('c.updated_at', 'desc')
                ->where('r.id', $requestId) 
                ->select('r.*','lb.title as lineofbusinesstitle', 'du.name as assignedName','c.name as customerName','t.*','rt.*',  DB::raw("(case r.status when '0' then 'New' when '1' then 'New' when '2' then 'Technical review' when '3' then 'Document approved' when '4' then 'Quote upload'  when '5' then 'Revise quotation'  when '6' then 'Request policy'  when '7' then 'Policy upload'  when '8' then 'Reject'  when '9' then 'Completed' when '10' then 'Lost' when '11' then 'Pending with sales' else 'Pending with client' end) AS statusString"), "u.name as userName",DB::raw("(case r.priority when '1' then 'Low' when '2' then 'Medium'  else 'High' end) AS priorityString"),"r.id as mainId","r.customer_id as customerId","r.crm_line_of_business as lineofbusiness","p.id as policyId","p.policy_number")
                ->get()->first();
        return $quoteRequest;
    }
    /**
     * 
     * @param type $requestId
     * @return type
     */
    public function quoteRequestLogDetail($requestId) {
        $quoteRequestLog = DB::table('dpib_quote_request as r')
                ->leftJoin('dp_quote_request_log as lg', 'lg.quote_request_id', '=', 'r.id')
                ->leftJoin('users as u', 'lg.edited_by', '=', 'u.id')
                ->orderBy('lg.edited_at', 'desc')
                ->where('lg.quote_request_id', $requestId)
                ->select('lg.*',  'u.name as userName')
                ->get();
        return $quoteRequestLog;
    }
    
    

}
