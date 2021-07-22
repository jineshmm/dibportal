<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class customerDocument extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'dp_customer_document'; 
    public $timestamps = false;
    
    
    public function documentData($customerId) {
       
        $customer = DB::table('customers as c')
                        ->leftJoin('dp_customer_document as cd', 'c.id', '=', 'cd.customer_id')
                        ->leftJoin('dp_document_type as dt', 'dt.id', '=', 'cd.type')
                        ->leftJoin('users as u', 'cd.upload_by', '=', 'u.id')
                        ->select( 'cd.*', 'dt.title as docType', 'u.name as uploadedBy','cd.id as docId')
                        ->where('cd.customer_id', $customerId)
                        ->get();

        return $customer;
        
    }
    

}
