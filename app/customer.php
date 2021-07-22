<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Auth;

class customer extends Model {

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'customers';
    public $timestamps = true;

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'type' => '0',
        'add_contact_person_flag' => '0',
        'status' => '1'
    ];

    public function policies() {
        return $this->hasMany('App\policy');
    }

    /**
     * Collect the detail of selected customer using their id
     * @param type $id
     * @return type
     */
    public function getCustomerDetails($id) {

        $customer = DB::table('customers as c')
                        ->leftJoin('users as u', 'u.id', '=', 'c.customer_management_user')
                        ->leftJoin('customer_address as ca', 'c.id', '=', 'ca.customer_id')
                        ->leftJoin('customer_contact_person_info as cp', 'c.id', '=', 'cp.customer_id')
                        ->select('c.*', 'ca.*', 'cp.*', 'c.id as customId', 'c.email as customerEmail', 'c.phone as customerPhone', 'cp.email as contactEmail', 'cp.phone as contactPhone', 'c.name as customerName', 'ca.id as addressId','u.name as accountManager')
                        ->where('c.id', $id)
                        ->get()->first();

        return $customer;
    }

    /**
     * Function to collect all the customer data
     * @return type
     */
    public function getAllCustomerDetails($individualFlag=false) {
        $customer = DB::table('customers as c')                
                ->leftJoin('customer_address as ca', 'c.id', '=', 'ca.customer_id')
                ->leftJoin('customer_contact_person_info as cp', 'c.id', '=', 'cp.customer_id')
                ->leftJoin('users as u', 'c.customer_management_user', '=', 'u.id')
                ->orderBy('c.updated_at', 'desc')
                ->groupby('c.id')
                ->select('c.*', 'ca.*', 'cp.*', 'c.id as customId', 'c.email as customerEmail', 'c.phone as customerPhone', 'cp.email as contactEmail', 'cp.phone as contactPhone', 'u.name as userName', 'c.name as customerName', 'ca.id as addressId');
                if($individualFlag) {
                   $customer ->where('c.created_user',Auth::user()->id);   
                } 
               $customerDetails = $customer ->get();
               echo Auth::user()->id;exit;
        
        return $customerDetails;
    }
/**
 * To get the filtered customer details
 * @param array $whereCondition filter condition
 * @return type
 */
    public function getFilteredCustomer($whereCondition) {

        $whereArray = [];
        
        $customer = DB::table('customers as c')
                ->join('policies as p','p.customer_id','=','c.id')
                ->leftJoin('customer_address as ca', 'c.id', '=', 'ca.customer_id')
                ->leftJoin('customer_contact_person_info as cp', 'c.id', '=', 'cp.customer_id')
                ->leftJoin('users as u', 'c.customer_management_user', '=', 'u.id');                

        if ($whereCondition['filter_customer_name'] != '') {

            $whereArray[] = ['c.name', 'LIKE', '%' . $whereCondition['filter_customer_name'] . '%' ];
        }
        if ($whereCondition['filter_customer_idcode'] != '') {
            $whereArray[] = ['c.customer_code', 'LIKE', $whereCondition['filter_customer_idcode']];
        }
        if ($whereCondition['filter_customer_contactperson'] != '') {
            $whereArray[] = ['cp.person_name', 'LIKE', $whereCondition['filter_customer_name']];
        }
        if ($whereCondition['filter_customer_type'] != '') {
            $whereArray[] = ['c.type', '=', $whereCondition['filter_customer_type']];
        }
        if ($whereCondition['filter_customergroup_oid'] != '') {
            $whereArray[] = ['c.customer_group', '=', $whereCondition['filter_customergroup_oid']];
        }
        
        if ($whereCondition['filter_broker_person_oid'] != '') {
            $whereArray[] = ['c.customer_management_user', '=', $whereCondition['filter_broker_person_oid']];
        }
        
        if (count($whereArray) > 0) {
            $customer->where($whereArray);
        }
        
        if(in_array('ROLE_SALES_MANAGER', Auth::user()->roles) || in_array('ROLE_TECHNICAL_MANAGER', Auth::user()->roles)) {
            
        } else if(in_array('ROLE_SALES', Auth::user()->roles)) {
            $customer ->where('c.created_user',Auth::user()->id);
           
        } else {
            
        }
     
       $customerDetails =  $customer
                ->select('c.*', 'ca.*', 'cp.*', 'c.id as customId', 'c.email as customerEmail', 'c.phone as customerPhone', 'cp.email as contactEmail', 'cp.phone as contactPhone', 'u.name as userName', 'c.name as customerName', 'ca.id as addressId')
                ->orderBy('c.updated_at', 'desc')->groupBy('c.id') -> get();
       
        return $customerDetails;
    }
/**
 * Insert log data to the table
 * @param String $tableName
 * @param array  $insertArray
 */
    public function logInsert($tableName, $insertArray) {
     DB::table($tableName)->insert($insertArray); // Query Builder approach
    }
    
    public function logData($customerId) {
      $customerLog = DB::table('customers as c')
                ->leftJoin('customer_log as cl', 'c.id', '=', 'cl.customer_id')
                ->leftJoin('users as u', 'cl.edited_by', '=', 'u.id') 
                ->orderBy('cl.updated_at', 'desc')                
                ->select('c.*', 'cl.*','u.name as loggedUser')
                ->where('c.id', $customerId)
                ->get();
      
      return $customerLog;
    }
    
}
