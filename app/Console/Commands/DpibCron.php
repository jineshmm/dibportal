<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Exception;

class DpibCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dpib:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automaticaly update the status of expired policy.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

       $policyDetails =  DB::table('policies')
                         ->whereDate('end_date', '<', date('Y-m-d'))
                         ->where('policy_status','>=',2)
                         ->orderBy('id')->pluck('id')->toArray();
 
        
      $updateArray =[];

      $dateTime = date('Y-m-d h:i');      
  
 
       if(count($policyDetails) >0) {
            foreach($policyDetails as $key =>$details) {
               $updateArray   =array('policy_status'=>4, 'updated_at' =>$dateTime);
         try {
              DB::table('policies')->where('id','=',$details)->update($updateArray);
         } catch(Exception $exception){
             \Log::debug('[Generate policy update] Failure - ', [$exception]);
         }
            }
          
       }
      
        $this->info('Policy status upload:Cron Cummand Run successfully!');
    }
}
