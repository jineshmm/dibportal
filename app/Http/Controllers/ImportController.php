<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Excel;
use App\Policy_product_details;
use Auth;

class ImportController extends Controller {

    /**
     *
     */
    public function customerImport() {


        return view('Import/customerimport');
    }

    /**
     *
     */
    public function customerDataImport(Request $request) {

        $path = $request->file('document_file')->getRealPath();

        $data = Excel::load($path)->get();
        $insert_data = [];
        if ($data->count() > 0) {

            foreach ($data->toArray() as $key => $row) {
               
                if (trim($row['customer']) == null || trim($row['customer']) == '') {
                    continue;
                }                
                
                $insert_data[] = array(
                    'name' => $row['customer'],
                    'email' => $row['e_mail_address'],
                    'mobile' => $row['mobile_phone'],
                    'phone' => $row['phone'],
                    'fax' => $row['fax'],
                    'type' => $this->getCustomertype($row['customer_type']),
                    'customer_code' => $row['customer_code'],
                    'customer_group' => $this->getCustomerGroup($row['customer_group']),
                    'website' => isset($row['website'])?$row['website']:'',
                    'company_name' => $row['company_name'],
                    'channel' => isset($row['sales_channel'])?$this->getSalesChannel($row['sales_channel']):'',
                    'customer_management_user' => 1,
                    'technical_handler' => 14,
                    'id_code' => $row['id_code'],
                    'created_at' => date('Y-m-d h:i'),
                    'status' => 2,
                    'policy_flag' => 1
                );
            }
        }

        if (!empty($insert_data)) {

            DB::table('customers')->insert($insert_data);
        }

        return back()->with('success', 'Excel Data Imported successfully.');
    }

    /**
     *
     */
    private function getCustomertype($typeString) {
        $value = '0';
        if (strtolower(trim($typeString)) == 'company') {
            $value = '1';
        }

        return $value;
    }

    /**
     *
     */
    private function getSalesChannel($stringvalue) {
        $value = 'sales';
        if (strtolower(trim($stringvalue)) == 'direct') {
            $value = 'direct';
        }
        return $value;
    }

    /**
     *
     */
    private function getCustomerGroup($stringvalue) {
        $value = 'sme';
         if (strtolower(trim($stringvalue)) == 'corporate') {
            $value = 'corporate';
        } else if (strtolower(trim($stringvalue)) == 'retail') {
            $value = 'retail';
        } else {
            $value = 'sme';
        }
        return $value;
    }

    public function policyimport() {

        $allCustomers = DB::table('customers')->distinct()->where('status', '1')->orderBy('id')->pluck('name', 'id')->toArray();
        return view('Import/policyimport', array('allCustomers' => $allCustomers));
    }

    /**
     * 
     * @param Request $request
     * @return type
     */
    public function policyDataImport(Request $request) {
        
        $path = $request->file('document_file')->getRealPath();
        $cusId = $request->get('customer_id');
        $data = Excel::load($path)->get();
        ini_set('max_execution_time', 0);
        $insert_data = [];
        if ($data->count() > 0) {

             foreach ($data->toArray() as $key => $row) {
              $customerId =  $this->getPolicyCheckDetails($row,$row['customer']); 
               if($customerId ==0 ) {
                file_put_contents(base_path().'\nocustomerpolicyid.txt',PHP_EOL.'######START'.$row['policy'].'****'.$key."######",FILE_APPEND);
                    continue;
                 
               } 
                
                
                if (trim($row['policy']) == null || trim($row['policy']) == '') {
                    continue;
                }
                $produtId = $this->getProductId($row['product']);
                if( $produtId ==null) {
                  $produtId = $this->assignProductId($row['product']);  
                }
                $insurerId = $this->getInsurer($row['insurer']);

                $insert_data = array(
                    'policy_number' => $row['policy'],
                    'start_date' => date("Y-m-d", strtotime($row['start_date'])),
                    'end_date' => date("Y-m-d", strtotime($row['end_date'])),
                    'insurer_id' => $insurerId,
                    'product_id' => $produtId,
                    'customer_id' => $customerId,
                    'installment_number' => $row['installments'],
                    'gross_amount' => floatval(str_replace(',', '', $row['gross_premium'])),
                    'total_premium' => floatval(str_replace(',', '', $row['gross_premium'])),
                    'commision' => $row['comm.'],
                    'issue_date' => date("Y-m-d", strtotime($row['issue_date'])),
                    'vat_amount' => floatval(str_replace(',', '', $row['taxes_added_above_the_premium'])),
                    'collection_type' => $row['collects'],
                    'created_at' => date('Y-m-d h:i'),
                    'policy_status' => 2,
                    'tax' => '5'
                );
                if ($this->compareDate(date("Y-m-d", strtotime($row['end_date'])), date('Y-m-d'))) {
                    $insert_data['policy_status'] = 2;
                } else {
                    $insert_data['policy_status'] = 4;
                    $insert_data['renewal_status'] = 1;
                }
                if ($produtId == 11 && $row['object'] != '') {
                    $insert_data['no_of_members'] = $row['object'];
                }
                if ($produtId == 35 && $row['object'] != '') {
                    $inputArray = explode(',', $row['object']);
                    $insert_data['fire_lightening'] = $inputArray[0];
                }

                

                $policyId = DB::table('policies')->insertGetId($insert_data);
                if ($policyId > 0) {
                    //insert product details
                    $this->saveProductDetails($row, $policyId, $produtId);
                    $this->savePolicyCommission($row, $policyId);
                }
            }
        }

        return back()->with('success', 'Policy data Imported successfully.');
    }

    /**
     * To save the product details of the policy
     * @param type $request
     */
    private function saveProductDetails($row, $policyId, $productId) {
        $policyproductObj = '';
        //        11 - group medical insurance
//                15 - house insurance
//                19 - Marine cargo individual shipment
//                24 - motor comprehansive individual
//                25 - motor fleet comprehensive
//                27 - motor tpl individual
//                30 - personal accident
//                35 - property
//                38 - travel insurance


        if (in_array($productId, array(11, 19, 20, 25, 35))) {

            $policyproductObj = new Policy_product_details();
            $policyproductObj::where('policy_id', $policyId)->delete();
            if ($row['object'] != '') {
                $policyproductObj->policy_id = $policyId;
                switch ($productId) {
                    case 19:case 20:
                        $policyproductObj->kind_of_goods = $row['object'];
                    case 35:
                        $inputArray = explode(',', $row['object']);
                        if (count($inputArray) > 1) {
                            $policyproductObj->property_all_risks = $inputArray[1];
                        }
                        break;
                    case 24: case 27:
                        if($row['object'] !='') {
                          $objectDetails = explode(',', $row['object']);
                          $policyproductObj->make               = $objectDetails[0];
                          $policyproductObj->model              = $objectDetails[1];
                          $policyproductObj->year               = $objectDetails[2];
                          $policyproductObj->license_plate      = $objectDetails[3];
                          $policyproductObj->no_of_passengers   = $objectDetails[4];
                        } else {
                          $policyproductObj->no_of_passengers = $row['object'];
                        }
                        
                        break;
                }
                $policyproductObj->save();
            }
        }
    }

    /**
     *
     * @param type $searchString
     * @return type
     */
    private function getInsurer($searchString) {
        $value = null;
        if (trim($searchString) != '') {
            $insuranceInfo = DB::table('insurer_details')->select('id')
                            ->where('insurer_name', 'like', "%" . $searchString . "%")->first();
            if ($insuranceInfo &&  count(get_object_vars($insuranceInfo))  > 0) {
                $value = $insuranceInfo->id;
            } else {
                $value = DB::table('insurer_details')->insertGetId(array('insurer_name' => $searchString, 'status' => '1', 'created_at' => date('Y-m-d h:i')));
            }
        }

        return $value;
    }

    /**
     * 
     * @param type $searchString
     * @return type
     */
    private function getProductId($searchString) {
        $value = null;
        if (trim($searchString) != '') {
            $productInfo = DB::table('insurance_product')->select('id')
                            ->where('product_name', 'like', "%" . $searchString . "%")->where('status',1)->first();
            
            if ($productInfo && count(get_object_vars($productInfo)) > 0) {
                $value = $productInfo->id;
            } else {
                $value = DB::table('insurance_product')->insertGetId(array('product_name' => $searchString, 'status' => '1'));
            }
        }

        return $value;
    }

    /**
     * Transform a date value into a Carbon object.
     *
     * @return \Carbon\Carbon|null
     */
    private function compareDate($oldvalue, $newvalue) {

        $dateTimestamp1 = strtotime($oldvalue);
        $dateTimestamp2 = strtotime($newvalue);


        if ($dateTimestamp1 > $dateTimestamp2)
            return true;
        else
            return false;
    }

    /**
     * 
     * @param type $row
     * @param type $policyId
     */
    private function savePolicyCommission($row, $policyId) {

        $commissionId = DB::table('policy_commission')->insertGetId(array('sales_person_id' => 3, 'policy_id' => $policyId, 'distributor_type' => 'diamond', 'commission_type' => '0', 'percentage' => $row['comm.'], 'amount' => floatval(floatval(str_replace(',', '', $row['gross_premium'])) * ($row['comm.'] / 100))));
    }

    /**
     * 
     */
    public function installmentImport() {


        $allCustomers = DB::table('customers')->distinct()->where('status', '1')->orderBy('id')->pluck('name', 'id')->toArray();
        return view('Import/installmentimport', array('allCustomers' => $allCustomers));
    }

    /**
     * 
     * @param Request $request
     */
    public function installmentDataImport(Request $request) {
        $path = $request->file('document_file')->getRealPath();
        $cId = $request->get('customer_id');
        //$policyId = $request->get('customer_id');
        $data = Excel::load($path)->get();
 ini_set('max_execution_time', 0);
        $insert_data = [];
        if ($data->count() > 0) {
            foreach ($data->toArray() as $key => $row) {
                
                


                if (trim($row['policy']) == null || trim($row['policy']) == '') {
                    continue;
                }
                   $customerDetails = DB::table('customers')->select('id')
                            ->where('name', 'like', "%" . trim($row['customer']) . "%")->first();
                            $customerId =0;
           
               if ($customerDetails &&  count(get_object_vars($customerDetails))  > 0) {
                    $customerId = $customerDetails->id;

               } 

               if($customerId==0) {
                 file_put_contents(base_path().'\nocustomerid.txt',PHP_EOL.'######START'.$row['customer'].'****'.$key."######",FILE_APPEND);
                    continue;
               }

                $row['policyId'] = $this->getPolicyId($row,$customerId);

                if($row['policyId']==null) {
                 
                 file_put_contents(base_path().'\nopolicyid.txt',PHP_EOL.'######START'.$row['policy'].'****'.$key."######",FILE_APPEND);
                    continue;
                }
                $sum = floatval(str_replace(',', '', $row['sum']));
                if (stripos($row['installment'], 'Endorsement') !== false) {
                    $endorsementSql = DB::table('policy_endorsement')->select('id')
                        ->where('policy_id',$row['policyId'])
                        ->whereDate('start_date',date('Y-m-d', strtotime($row['period_begin'])))
                        ->whereDate('issue_date',date('Y-m-d', strtotime($row['due_date'])))
                        -> where('endorsement_number', 'like', trim($row['installment']))                     
                        
                        ->orderBy('id','ASC');
                  $endorsedata= $endorsementSql->first();


                if ($endorsedata && count(get_object_vars($endorsedata)) > 0   ) {
                    file_put_contents(base_path().'\existinstallment.txt',PHP_EOL.'######START'.$row['policy'].'****'.$customerId."######".$key,FILE_APPEND);
                    continue;
                }      


                } 

                  $installSql = DB::table('policy_intallment')->select('id')
                        ->where('policy_id',$row['policyId'])
                        ->whereDate('start_date',date('Y-m-d', strtotime($row['period_begin'])))
                        ->whereDate('end_date',date('Y-m-d', strtotime($row['due_date'])))  
                        ->where('amount','=',$sum)
                        
                        ->orderBy('id','ASC');

                  
                  //dd($installSql->toSql(), $installSql->getBindings());
              $installdata = $installSql->first();

                if ($installdata && count(get_object_vars($installdata)) > 0   ) {

                    file_put_contents(base_path().'\existinstallment.txt',PHP_EOL.'######START'.$row['policy'].'****'.$customerId."######installment exist",FILE_APPEND);
                    //continue;
                }
                

               
                if ($row['policyId'] !== null) {
                    //policy_endorsement
                    if (stripos($row['installment'], 'Endorsement') !== false) {
                        $row['endorsementId'] = $this->insertEndorsementData($row);
                        //update endorsement amount in main table
                    }

                    $insert_data = array(
                        'policy_id' => $row['policyId'],
                        'start_date' => date("Y-m-d", strtotime($row['period_begin'])),
                        'end_date' => date("Y-m-d", strtotime($row['due_date'])),
                        'due_date' => date("Y-m-d", strtotime('-1 day', strtotime($row['due_date']))),
                        'amount' => floatval(str_replace(',', '', $row['sum'])),
                        'parent_id' => $this->getParentId($row),
                        'installment_type' => isset($row['endorsementId']) ? 2 : 1,
                        'endorsement_id' => isset($row['endorsementId']) ? $row['endorsementId'] : null,
                        'vat_amount' => floatval(str_replace(',', '', $row['tax_amount'])),
                        'vat_percentage' => ($row['tax_amount'] > 0) ? '5' : 0
                    );
file_put_contents(base_path().'\installimportrows.txt',PHP_EOL.'*****START'.$key.'*****'.$row['policyId'],FILE_APPEND); 
                    $installmentId = DB::table('policy_intallment')->insertGetId($insert_data);
                   
                    $row['installmentId'] = $installmentId;
                    if (isset($row['endorsementId']) && $row['endorsementId'] != '') {
                        $this->calculateEndorsementCommission($row);
                    }
                } else {
                   file_put_contents(base_path().'\installimport.txt',PHP_EOL.'*****START'.$key.'*****',FILE_APPEND);  
                }
            }
        }

        return back()->with('success', 'Installment data Imported successfully.');
    }

    /**
     * 
     * @param type $row
     * @return type
     */
    private function getPolicyId($row, $customerId) {
        $value = null;
        $policydata = DB::table('policies')->select('id', 'policy_number', 'start_date', 'end_date')
                        ->where('customer_id',$customerId)
                        ->where('policy_number', 'like', "%" . trim($row['policy']) . "%")->where('start_date', '<=', date('Y-m-d', strtotime($row['period_begin'])))->where('end_date', '>=', date('Y-m-d', strtotime($row['period_begin'])))->orderBy('id','ASC')->first();
        if ($policydata && count(get_object_vars($policydata)) > 0   ) {
            $value = $policydata->id;
        }  else {

        }
        return $value;
    }

    /**
     * 
     * @param type $row
     * @return type
     */
    private function insertEndorsementData($row) {
        $insert_data = array(
            'policy_id' => $row['policyId'],
            'endorsement_number' => $row['installment'],
            'endorsement_type' => (floatval(str_replace(',', '', $row['sum'])) > 0) ? 1 : 3,
            'start_date' => date("Y-m-d", strtotime($row['period_begin'])),
            'issue_date' => date("Y-m-d", strtotime($row['due_date'])),
            'amount' => floatval(str_replace(',', '', $row['sum'])),
            'added_by' => Auth::user()->id,
            'created_at' => date('Y-m-d h:i:s'),
            'vat_percentage' => ( floatval(str_replace(',', '', $row['tax_amount'])) > 0) ? '5' : 0,
            'vat_amount' => floatval(str_replace(',', '', $row['tax_amount'])),
            'endorsement_status' => 1,
            'endorsement_count' => 1
        );
        return DB::table('policy_endorsement')->insertGetId($insert_data);
    }

    /**
     * Installment parent id
     * @param type $row
     */
    private function getParentId($row) {
        $value = null;
        $installmentdata = DB::table('policy_intallment')->select('id', 'policy_id', 'start_date', 'end_date')
                        ->where('policy_id', $row['policyId'])->where('start_date', '<=', date('Y-m-d', strtotime($row['period_begin'])))->where('end_date', '>=', date('Y-m-d', strtotime($row['due_date'])))->first();

        if ( $installmentdata && count(get_object_vars($installmentdata)) > 0 ) {
            $value = $installmentdata->id;
        }

        return $value;
    }

    /**
     * To calculate endorsement commission
     * @param type $policyId
     * @param type $endorsementId
     */
    private function calculateEndorsementCommission($row) {

        $policyCommissionDetails = DB::table('policy_commission')->select('*')->where('policy_id', '=', $row['policyId'])->groupBy('distributor_type')->orderBy('distributor_type', 'ASC')->get();
        $insertArray = [];
        $datetime = date('Y-m-d h:i:s');
        $companyCommisison = 0;
        if (count($policyCommissionDetails) > 0) {
            foreach ($policyCommissionDetails as $commissionDetails) {
                if ($commissionDetails->commission_type == 0) {
                    $percentage = floatval($commissionDetails->percentage);
                    $commissionAmount = ($commissionDetails->distributor_type == 'diamond') ? floatval(str_replace(',', '', $row['commission'])) : floatval(str_replace(',', '', $row['commission']));
                    $companyCommisison = ($commissionDetails->distributor_type == 'diamond') ? $commissionAmount : $commissionAmount;
                    $insertArray[] = array("policy_id" => $row['policyId'],
                        "distributor_type" => $commissionDetails->distributor_type,
                        "commission_type" => $commissionDetails->commission_type,
                        "percentage" => $percentage,
                        "amount" => $commissionAmount,
                        "added_date" => $datetime,
                        "sales_person_id" => $commissionDetails->sales_person_id,
                        "installment_id" => $row['installmentId']
                    );
                }
            }

            if (count($insertArray) > 0) {
                DB::table('policy_commission')->insert($insertArray);
            }
        }
    }

    /**
     * 
     * @return type
     */
    public function claimImport() {
        $allCustomers = DB::table('customers')->distinct()->where('status', '1')->orderBy('id')->pluck('name', 'id')->toArray();
        return view('Import/claimimport', array('allCustomers' => $allCustomers));
    }

    /**
     * 
     * @param Request $request
     */
    public function claimDataImport(Request $request) {
        $path = $request->file('document_file')->getRealPath();
        $customerId = 0;

        $data = Excel::load($path)->get();

        $insert_data = [];
        if ($data->count() > 0) {

            foreach ($data->toArray() as $key => $row) {

     
                if (trim($row['policy']) == null || trim($row['policy']) == '') {
                
                    continue;
                } 

                $row['policyId'] = $this->getClaimPolicy($row);
                if($row['policyId'] === null) {
                  $row['policyId'] = $this->getClaimPolicy($row, false);   
                }
                $customerDetails = DB::table('customers')->select('id')
                            ->where('name', 'like', "%" . trim($row['customer']) . "%")->first();
                            
           
               if ($customerDetails &&  count(get_object_vars($customerDetails))  > 0) {
                    $customerId = $customerDetails->id;

               } 

                if ($row['policyId'] !== null) {
                    //policy_endorsement
                   

                    $insert_data = array(
                        'policy_id' => $row['policyId'],
                        'customer_id' => $customerId,
                        'incident_date' => date("Y-m-d h:i", strtotime($row['loss_date'])),
                        'submitted_broker_date' => date("Y-m-d", strtotime($row['date_submitted_to_broker'])),
                        'claim_handler' => Auth::user()->id,
                        'is_policy_holder_claimate' => 0,
                        'created_date' => date("Y-m-d h:i"),
                        'created_by' => Auth::user()->id,
                        'status' =>$this->findStatus($row['status']),
                        'insly_claim_id'=>$row['claim_id']
                        
                    );

                    $claimId = DB::table('policy_claims')->insertGetId($insert_data);
                    $claimId=0;
                    $row['claimId'] = $claimId;
                    if ($claimId !='' && $claimId >0) {
                        $this->insertClaimant($row);
                    }
                }
            }
        }

        return back()->with('success', 'Claim data Imported successfully.');
    }
/**
 * 
 * @param type $row
 * @return type
 */
    private function getClaimPolicy($row, $flag =true) {
        $value = null;
        if($flag) {
           $policydata = DB::table('policies')->select('id', 'policy_number', 'start_date', 'end_date')
                        ->where('policy_number', 'like', "%" . $row['policy'] . "%")->where('start_date', '<=', date('Y-m-d', strtotime($row['loss_date'])))->where('end_date', '>=', date('Y-m-d', strtotime($row['loss_date'])))->first();   
        } else {
           $policydata = DB::table('policies')->select('id', 'policy_number', 'start_date', 'end_date')
                        ->where('policy_number', 'like', "%" . $row['policy'] . "%")->first(); 
        }


        if ( $policydata && count(get_object_vars($policydata)) > 0) {
            $value = $policydata->id;
        }
        return $value;
    }
    /**
     * 
     * @param type $statusString
     * @return type
     */
    private function findStatus($statusString) {
        $key =1;
        $statusArray = array(1 => 'Awaiting info/Documents', 2 => 'Claim reopened', 3 => '[Open] Awaiting Repair Approval from Insurer', 4 => 'Awaiting Repair Cheque from Insurer', 5 => 'Under Process With Insurer', 6 => 'Repair Approval Sent to the client / Awaiting Repair Invoices from the client', 7 => 'Partial Approval', 8 => 'Claim settled', 9 => 'Claim within excess', 10 => 'Claim no longer pursued', 11 => 'Late Submission', 12 => 'Not Covered Under the Policy', 13 => 'Not Covered - Policy Terms &amp; Conditions');
        
        if ( array_search(trim($statusString), $statusArray) !== NULL  ) {
           
           $key = array_search(trim($statusString), $statusArray);  
       } 

       return $key;
        
    }
    /**
     * 
     * @param type $row
     */
    private function insertClaimant($row) {
              $insert_data = array(
                        'claim_id' => $row['claim_id'],
                        'name' => $row['claimant'],
                        'claimant_type' => 1
                        
                    );

                    DB::table('policy_claimant_info')->insertGetId($insert_data);
    }
    
    private function assignProductId($findstring) {
        $productId =null;
         //        11 - group medical insurance
//                15 - house insurance
//                19 - Marine cargo individual shipment
//                24 - motor comprehansive individual
//                25 - motor fleet comprehensive
//                27 - motor tpl individual
//                30 - personal accident
//                35 - property
//                38 - trave
        if(trim(strtoupper($findstring)) =='SME MEDICAL INSURANCE') {
          $productId = 11;  
        }
        return $productId;
        
    }
    
    private function getPolicyCheckDetails($row, $customerName) {
         $customerId = 0; 
       
        if (trim($row['policy'])!= null && trim($row['policy']) != '' && trim($customerName)!= null && trim($customerName) != '') {
         //get customer id from name   
           $customerDetails = DB::table('customers')->select('id')
                            ->where('name', 'like', "%" . $customerName . "%")->first();
           
           if ($customerDetails &&  count(get_object_vars($customerDetails))  > 0) {
                $cId = $customerDetails->id;
                   //check policy details
               $policySql = DB::table('policies')->select('id', 'policy_number', 'start_date', 'end_date')
                        ->where('customer_id',$cId)
                        ->where('policy_number', 'like', "%" . trim($row['policy']) . "%")->whereDate('end_date',date('Y-m-d', strtotime($row['end_date'])))->orderBy('id','ASC');

 
                  $policydata= $policySql->first();   
                if ($policydata && count(get_object_vars($policydata)) > 0   ) {
                  $customerId = 0; 
                   $dateTime = date('Y-m-d h:i');   
                  //update policy start_date
                  DB::table('policies')->where('id', $policydata->id)
                    ->update(array('start_date' => date('Y-m-d', strtotime($row['start_date'])),'updated_at' =>$dateTime
                )                            
                 );
                 
                  file_put_contents(base_path().'\updatepolicy.txt',PHP_EOL.'######START'.$row['policy'].'****'.$customerName."######",FILE_APPEND);
                } else {
                    $customerId = $cId;
                     file_put_contents(base_path().'\newpolicy.txt',PHP_EOL.'######START'.$row['policy'].'****'.$customerName."######",FILE_APPEND);
                }
                
                
                
            }  
                  
        } 
        return $customerId;
    }
   
/**
     * Update agent details on customer table
     * @param Request $request
     * @return type
     */
     public function customerAgentConnection(Request $request) {

        $path = $request->file('document_file')->getRealPath();

        $data = Excel::load($path)->get();
        $insert_data = [];
        if ($data->count() > 0) {

            foreach ($data->toArray() as $key => $row) {
               
                if (trim($row['customer']) == null || trim($row['customer']) == '') {
                    continue;
                }                
                if (trim($row['account_manager']) == null || trim($row['account_manager']) == '') {
                    continue;
                }
                //check customer is exist or not
                
                $customerDetails = DB::table('customers')->select('id')
                            ->where('name', 'like', "%" . trim($row['customer']) . "%")->first();
                            $customerId =0;
           
               if ($customerDetails &&  count(get_object_vars($customerDetails))  > 0) {
                    $customerId = $customerDetails->id;

               } else {
                   file_put_contents(base_path().'\nocustomer.txt',PHP_EOL.'######START****'.$row['customer']."######".$row['account_manager'],FILE_APPEND);
                   continue;
               }
                
                //check account manager(agent) exist or not
               $agentDetails = DB::table('users')->select('id')
                            ->where('name', 'like', "%" . trim($row['account_manager']) . "%")->first();
                            $agentId =0;
           
               if ($agentDetails &&  count(get_object_vars($agentDetails))  > 0) {
                    $agentId = $agentDetails->id;

               } else {
                   $agentId = 19; 
                   file_put_contents(base_path().'\noagent.txt',PHP_EOL.'######START****'.$row['customer']."######".$row['account_manager'],FILE_APPEND);
                  // continue;
               }
               
               
                
                //update customer manager value
               
               DB::table('customers')->where('id', $customerId)
                    ->update(array('customer_management_user' =>$agentId ));
               
                file_put_contents(base_path().'\updatedcustomer.txt',PHP_EOL.'######START****'.$row['customer']."######".$agentId,FILE_APPEND);
            
            }
        }

        

        return back()->with('success', 'Excel Data Imported successfully.');
    }
    
     /**
     *
     */
    public function customerAgentUpdate() {


        return view('Import/customeragentupdate');
    }
   
public function requestImport(){
      return view('Import/requestdataimport');  
    }
    
    public function requestDataImport(Request $request) {
     $path = $request->file('document_file')->getRealPath();
     $typeArray = [1 => 'Addition', 'CCHI upload', 'Deletion', 'Downgrade', 'Corrections', 'Certificate request', 'Najam upload', 'Invoices Request', 'Upgrade', 'Others', 'Approvals', 'Request quatations', 'Active list'];
     $typeArray[17] = 'Announcement';       
        
            $statusArray = [1 => 'New', 'Under process', 'Resolved', 'Pending with insurer', 'Pending with client'];
            $statusArray[] = 'Pending with policy admin';
            $statusArray[] = 'Completed by client request';
            $statusArray[] = 'Completed without invoice';
            $statusArray[] = 'Completed-Invoice not received';
            $statusArray[10] = 'Closed';  
        
        $data = Excel::load($path)->get();
        $insert_data = [];
        if ($data->count() > 0) {

            foreach ($data->toArray() as $key => $row) {

                if (trim($row['request_id']) == null || trim($row['request_id']) == '') {
                    continue;
                }                
                if (trim($row['policy_number']) == null || trim($row['policy_number']) == '') {
                    continue;
                }
                //check policy is exist or not
       
                $policyDetails = DB::table('policies')->select('id')
                            ->where('policy_number', 'like', "%" . trim($row['policy_number']) . "%")->whereDate('start_date','<=',date('Y-m-d', strtotime($row['date_created'])))->orderBy('id','ASC')->first();
                            $policyId =0;
         
               if ($policyDetails &&  count(get_object_vars($policyDetails))  > 0) {
                    $policyId = $policyDetails->id;

               } else {
                   continue;
               }
           
                //check assigned user exist or not
               $agentDetails = DB::table('users')->select('id')
                            ->where('name', 'like', "%" . trim($row['assigned_to']) . "%")->first();
                            $agentId =0;
           
               if ($agentDetails &&  count(get_object_vars($agentDetails))  > 0) {
                    $agentId = $agentDetails->id;

               } else {
                   continue;
               }
               
               //Type identification
               if (in_array(trim($row['request_type']), $typeArray)) {
                 $type =  array_search(trim($row['request_type']), $typeArray);
               } else {
                 $type = 10;  
               }
               
               //status identification
               if (in_array(trim($row['status']), $statusArray)) {
                 $status =  array_search(trim($row['status']), $statusArray);
               } else {
                 $status = 1;  
               }
               $inslyFlag = (trim($row['is_insly_entered'])=='YES') ? 1:0;
               $invoiceRecievedFlag = (trim($row['is_invoice_received'])=='YES') ? 1:0; 
               
               $recieved_date = ($row['invoice_recieived_date'] !=0000-00-00  ) ? date('Y-m-d',strtotime($row['invoice_recieived_date'])):null;  
               $inslyEnteredDate =  ($row['insly_entered_date'] !=0000-00-00) ? date('Y-m-d',strtotime($row['insly_entered_date'])):null;
               $insert_data[] = array('policy_id' => $policyId,
                   'request_id' =>trim($row['request_id']),
                   'description' => trim($row['details']), 
                   'type' => $type, 
                   'status' => $status, 
                   'created_at' => date('Y-m-d h:i:s', strtotime($row['date_created'])), 
                   'updated_at' => date('Y-m-d h:i:s', strtotime($row['date_updated'])),
                   'created_by' => $agentId,                    
                   'assign_to'=>$agentId,
                   'is_insly_entered'=> $inslyFlag,
                   'insly_entered_date' =>$inslyEnteredDate ,
                   'inv_flag' => $invoiceRecievedFlag ,
                   'inv_recieved_date' =>$recieved_date);
                
                //update customer manager value
               
              
               
                file_put_contents(base_path().'\insertrequest.txt',PHP_EOL.'######START****'.$key,FILE_APPEND);
            
            }   
        
        
    }
    
    if(count($insert_data)>0) {
      DB::table('crm_endorsement')->insert($insert_data);  
    }
    
    return back()->with('success', 'Excel Data Imported successfully.');
    
    }

/**
 * Update customer group
 * @param Request $request
 * @return type
 */
    public function updateCustomerInfo(Request $request) {
        $path = $request->file('document_file')->getRealPath();

        $data = Excel::load($path)->get();
        ini_set('max_execution_time', 0);

        if ($data->count() > 0) {

            foreach ($data->toArray() as $key => $row) {

                if (trim($row['customer']) == null || trim($row['customer']) == '') {
                    continue;
                }
                $customerDetails = DB::table('customers')->select('id')
                                ->where('name', 'like', "%" . trim($row['customer']) . "%")->first();
                if ($customerDetails && count(get_object_vars($customerDetails)) > 0) {
                    $customerId = $customerDetails->id;
                    $newgroup = $this->getCustomerGroup($row['customer_group']);
                    DB::table('customers')->where('id', $customerId)->update(array('customer_group' => $newgroup));
                }
            }
        }



        return back()->with('success', 'Customer Data updated successfully.');
    }



     /**
     * Import new leads
     * @param Request $request
     * @return type
     */
    
    public function importLeads(Request $request) {
      $path = $request->file('document_file')->getRealPath();

        $data = Excel::load($path)->get();
        ini_set('max_execution_time', 0);
        $insert_data = array();
        if ($data->count() > 0) {

            foreach ($data->toArray() as $key => $row) {

                if (trim($row['customer']) == null || trim($row['customer']) == '') {
                    continue;
                }
                $customerDetails = DB::table('customers')->select('id')
                                ->where('name', 'like', "%" . trim($row['customer']) . "%")->first();
                    if ($customerDetails && count(get_object_vars($customerDetails)) > 0) {
                        continue;
                    }
                   $insert_data[] = array(
                    'name' => $row['customer'],
                    'email' => $row['e_mail_address'],
                    'mobile' => $row['mobile_phone'],
                    'phone' => $row['phone'], 
                    'customer_group' => $this->getCustomerGroup($row['customer_group']),
                    'type' => 1,                    
                    'channel' =>  'sales',
                    'customer_management_user' => 19,
                    'technical_handler' => 62,                    
                    'created_at' => date('Y-m-d h:i'),
                    'status' => 2,
                    'policy_flag' => 1
                );
                
            }
        }


        if (!empty($insert_data)) {

            DB::table('customers')->insert($insert_data);
        }

        return back()->with('success', 'Leads Data Imported successfully.');  
    }



   
}


