<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class crmTask extends Model
{
    protected $table = 'crm_task_table';
    public $timestamps = false;
}
