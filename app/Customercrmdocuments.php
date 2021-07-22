<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Customercrmdocuments extends Model
{
    protected $table = 'customer_crm_documents';
    public $timestamps = false;
    
    public function documentData($crmId) {
       
        $documents = DB::table('crm_main_table as cr')
                        ->leftJoin('customer_crm_documents as cd', 'cr.id', '=', 'cd.crm_id')
                        ->leftJoin('dp_document_type as dt', 'dt.id', '=', 'cd.document_type')
                        ->leftJoin('users as u', 'cd.uploaded_by', '=', 'u.id')
                        ->select( 'cd.*', 'dt.title as docType', 'u.name as uploadedBy','cd.id as docId','cd.customer_id as customerId','cd.crm_id as crmId')
                        ->where('cd.crm_id', $crmId)
                        ->where('cd.document_type','<>',8)
                        ->get();
        return $documents;
        
    }
    
    public function brokingslipData($crmId) {
      $brokingslip = DB::table('crm_main_table as cr')
                        ->leftJoin('broking_slip_info as bs', 'cr.id', '=', 'bs.crm_id')
                        ->leftJoin('insurance_product as p', 'p.id', '=', 'bs.product_id')
                        ->leftJoin('insurer_details as d', 'd.id', '=', 'bs.company_id')
                        ->leftJoin('users as u', 'bs.uploaded_by', '=', 'u.id')
                        ->select( 'bs.*',  'u.name as uploadedBy','bs.id as brokId','bs.customer_id as customerId','bs.crm_id as crmId','d.insurer_name as insurerName','p.product_name as productName',DB::raw("(case bs.status when 0 then 'quotes waiting' else 'quote uploaded' end) AS statusString"))
                        ->where('bs.crm_id', $crmId)  
                        ->orderby('bs.id','desc')
                        ->get();
      

        return $brokingslip;  
    }
    
}
