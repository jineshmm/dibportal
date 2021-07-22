<?php

namespace App\Http\Controllers\Policy;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use App;
use Mail;
use App\Http\Controllers\Controller;
use Session;
use PDF;
use File;
use Illuminate\Support\Facades\Date;
use Excel;

/**
 * To handle the policy related data
 * CRM Request Types are
 * Addition ,   CCHI Activation, Claim approval/Settlement ,Deletion ,Downgrade ,Updated member list ,Plate No Amendment ,Card Replacment ,CCHI Upload Status List ,MC Certificate ,Name Amendment ,
  Card Printer Request ,Invoices Request ,Upgrade, REQUEST ,INQUIRY ,announcement ,REQUEST SIGN ,Others

 * Policy status:: 0 then 'Saved' when 1 then 'Policy posted' when 2 then 'Policy issued' when 4 then 'Expired' when 5 then 'Renewed'
 *
 */
class PolicychecklistController extends Controller {

    /**
     * Comparison doc creation form
     * @return type
     */
    public function generateChecklistForm($policyId) {
        $policyDetails = DB::table('policies')->select('*')->where('id', $policyId)->get();   
             $policyDetails = DB::table('policies as p')
                        ->join('customers as c', 'c.id', '=', 'p.customer_id')
                        ->leftJoin('insurer_details as ind', 'ind.id', '=', 'p.insurer_id')
                        ->leftJoin('insurance_product as inp', 'inp.id', '=', 'p.product_id')
                        ->leftJoin('product_category as pc', 'pc.id', '=', 'inp.category')                       
                        ->select('p.*', 'pc.id as lobId', 'pc.title as categoryTitle', 'ind.insurer_name as insurerName', 'p.id as mainId', 'c.name as customerName', 'inp.product_name', DB::raw("(case p.renewal_status when 0 then 'New' when 1 then 'Renewal'   end) AS policystatusString"))
                        ->where("p.id", "=", $policyId)
                        ->orderBy('p.updated_at', 'desc')->first();
        
          $data = array('policyDetails' => $policyDetails);
          return view('policychecklist/checklistform', $data);
    }

    
    public function saveChecklistForm(Request $request) {
        $tableDataArray = array();
      
       $tableDataArray['file_number'] = $request->get('file_number');
       $tableDataArray['insurer'] = $request->get('insurer');
       $tableDataArray['insured'] = $request->get('insured');
       $tableDataArray['policy_type'] = $request->get('policy_type');
       $tableDataArray['lob'] = $request->get('categoryTitle');
       $tableDataArray['issue_date'] = $request->get('issue_date');
       $tableDataArray['inception_date'] = $request->get('inception_date');
       $tableDataArray['expiry_date'] = $request->get('expiry_date');
       $tableDataArray['lob_type'] = $request->get('lob_type');
       
       $tableDataArray['uw_docs'] = $request->get('uw_docs');
       $tableDataArray['quotes'] = $request->get('quotes');
       $tableDataArray['issuance'] = $request->get('issuance');
       $tableDataArray['policy'] = $request->get('policy');
       $tableDataArray['special'] = $request->get('special');
       $tableDataArray['payment'] = $request->get('payment');
       $tableDataArray['announce'] = $request->get('announce');
       $tableDataArray['insly'] = $request->get('insly');
       
       $tableDataArray['rates'] = $request->get('rates');
       $tableDataArray['deductible'] = $request->get('deductible');       
       $tableDataArray['special_note'] = $request->get('special_note');
       
       $tableDataArray['annual_limit'] = $request->get('annual_limit','');
       
       $tableDataArray['partial_depreciation'] = $request->get('partial_depreciation','');
       $tableDataArray['total_loss_depreciation'] = $request->get('total_loss_depreciation','');
       $tableDataArray['dip_class'] = $request->get('dip_class','');
       $tableDataArray['dental'] = $request->get('dental','');
       $tableDataArray['opticals'] = $request->get('opticals','');
       $tableDataArray['maternity'] = $request->get('maternity','');
       $tableDataArray['parents'] = $request->get('parents','');       
       $tableDataArray['policy_number'] = $request->get('policy_number','');
       $tableDataArray['product_name'] = $request->get('product','');
       
       
       Excel::create('checklist', function($excel) use ($tableDataArray) {

            $excel->sheet($tableDataArray['lob'], function($sheet) use ($tableDataArray) {
               
                $sheet->loadView('policychecklist/checklist', $tableDataArray);
//                $sheet->setWidth(array('A'     =>  390, 'B'     =>  350));
            });
            
        })->download('xlsx');
      
       
       
       
        
        
    }
}
