<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class customerComment extends Model
{
    protected $table = 'dp_customer_comment';
    public $timestamps = false;
    
    /**
     * Collect the note detail of particular customer
     * @param type $customerId
     * @return type
     */
    public function getCustomerNote($customerId) {
      $notesDetails = DB::table('customers as c')
                ->leftJoin('dp_customer_comment as cc', 'c.id', '=', 'cc.customer_id')
                ->orderBy('cc.created_at','desc')
                ->select( 'cc.*')
                ->where('c.id', $customerId)
                ->get();

        return $notesDetails;
    }
}
