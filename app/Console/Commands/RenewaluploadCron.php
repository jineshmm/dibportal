<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class RenewaluploadCron extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'makeRenewallist:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make policy renewal count down list';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {


        $policyDetails = DB::table('policies as p')
                        ->join('customers as c', 'c.id', '=', 'p.customer_id')
                        ->leftJoin('insurance_product as pr', 'pr.id', '=', 'p.product_id')
                        ->select('p.id as policyId','p.customer_id', DB::raw("(case p.policy_type when 2 then 'Medical' when 3 then 'Motor' else 'General'  end) AS lobdata"),DB::raw(" DATEDIFF(p.end_date,now()) as daydifference"), 'c.channel', DB::raw('DATEDIFF(p.end_date,now()) as datediff'),  'p.end_date as expirydate', 'p.start_date as inceptiondate')->where('p.policy_status', 2)->whereRaw('DATEDIFF(p.end_date,now())<=60')->get();
       $datetime = date('Y-m-d H:i');

       //dd($policyDetails);
       $insert_data = array();
        if ($policyDetails && count($policyDetails) > 0) {
            foreach ($policyDetails as $policyDetail) {
             
                $existpolicyDetails = DB::table('operation_renewal_notification')->where('policy_id', $policyDetail->policyId)->select('id')->first();
           
                if ($existpolicyDetails && count(get_object_vars($existpolicyDetails)) > 0) {
                    continue;
                }


                $insert_data[] = array(
                    'customer_id' =>$policyDetail->customer_id,
                    'policy_id' =>  $policyDetail->policyId,
                    'created_date' => $datetime,
                    'policy_type' => $policyDetail->lobdata
                    
                );
            }
        }
        if (!empty($insert_data)) {

            DB::table('operation_renewal_notification')->insert($insert_data);
        }
    
    }

}
