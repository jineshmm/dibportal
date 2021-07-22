<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class crmMain extends Model
{
    protected $table = 'crm_main_table';
    public $timestamps = false;
    
    
    public function crmRequestLogDetail($requestId) {
        $quoteRequestLog = DB::table('crm_main_table as cr')
                ->leftJoin('crm_log as lg', 'lg.crm_id', '=', 'cr.id')
                ->leftJoin('users as u', 'lg.edited_by', '=', 'u.id')
                ->orderBy('lg.id', 'desc')
                ->where('lg.crm_id', $requestId)
                ->select('lg.*',  'u.name as userName')
                ->get();
        return $quoteRequestLog;
    }
}
