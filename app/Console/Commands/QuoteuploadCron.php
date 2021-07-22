<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class QuoteuploadCron extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quoteUpload:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Quote upload notification';

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
        $quoteRequests = DB::table('crm_main_table as r')
                ->leftJoin('crm_task_table as t', 'r.id', '=', 't.crm_main_id')
                ->leftJoin('crm_request_table as rt', 'r.id', '=', 'rt.crm_id')
                ->leftJoin('customers as c', 'c.id', '=', 'r.customer_id')
                ->where('r.status', 3)
                ->where('r.updated_date', '<=', DB::raw('DATE_ADD(now(), INTERVAL -3 DAY)'))
                ->orderBy('c.updated_at', 'desc')
                ->select('r.*', 'r.id as mainId', 'c.name as customerName', 't.*', 'rt.*', DB::raw("(case r.status when '0' then 'New' when '1' then 'New' when '2' then 'Technical review' when '3' then 'Document approved' when '4' then 'Quote upload'  when '5' then 'Revise quotation'  when '6' then 'Request policy'  when '7' then 'Policy upload'  when '8' then 'Reject'  when '9' then 'Completed' else 'Lost' end) AS statusString"))
                ->get();

        if (count($quoteRequests) > 0) {
            $req = '';
            foreach ($quoteRequests as $qrequest) {
                $req .= "," . $qrequest->crm_request_id;
            }


            $user_data['to_data'] = 'j.mani@dbroker.com.sa';
            $user_data['cc_data'] = '';
            $user_data['attach'] = array();
            $user_data['subject'] = 'Quote has not uploaded against request';
            //Mail sending area
            Mail::send('emails.quotereminder', ['content' => ltrim($req)], function ($m) use ($user_data) {
                $m->from('info@dbroker.com.sa', 'Diamond insurance broker');
                $m->to($user_data['to_data'], $user_data['to_data'])->subject($user_data['subject']);
                if ($user_data['cc_data'] != '') {
                    $m->cc($user_data['cc_data']);
                }
                if (count($user_data['attach']) > 0) {
                    foreach ($user_data['attach'] as $attachfile) {
                        $m->attach($attachfile['filename'], [
                            'as' => $attachfile['as'],
                            'mime' => $attachfile['mime'],
                        ]);
                    }
                }
            });
        }


        \Log::info("Cron is working fine!" . json_encode($quoteRequest));
        $this->info('Quote upload:Cron Command Run successfully!');

        // Policy upload request notification

        $policyRequests = DB::table('crm_main_table as r')
                ->leftJoin('crm_task_table as t', 'r.id', '=', 't.crm_main_id')
                ->leftJoin('crm_request_table as rt', 'r.id', '=', 'rt.crm_id')
                ->leftJoin('customers as c', 'c.id', '=', 'r.customer_id')
                ->where('r.status', 6)
                ->where('r.updated_date', '<=', DB::raw('DATE_ADD(now(), INTERVAL -3 DAY)'))
                ->orderBy('c.updated_at', 'desc')
                ->select('r.*', 'r.id as mainId', 'c.name as customerName', 't.*', 'rt.*', DB::raw("(case r.status when '0' then 'New' when '1' then 'New' when '2' then 'Technical review' when '3' then 'Document approved' when '4' then 'Quote upload'  when '5' then 'Revise quotation'  when '6' then 'Request policy'  when '7' then 'Policy upload'  when '8' then 'Reject'  when '9' then 'Completed' else 'Lost' end) AS statusString"))
                ->get();

        if (count($policyRequests) > 0) {
            $req = '';
            foreach ($policyRequests as $prequest) {
                $req .= "," . $prequest->crm_request_id;
            }

            // Send a notification mail to technical department
            // Mail sending area
            $user_data['to_data'] = 'j.mani@dbroker.com.sa';
            $user_data['cc_data'] = '';
            $user_data['attach'] = array();
            $user_data['subject'] = 'Policy has not uploaded against request';
            Mail::send('emails.policyreminder', ['content' => ltrim($req)], function ($m) use ($user_data) {
                $m->from('info@dbroker.com.sa', 'Diamond insurance broker');
                $m->to($user_data['to_data'], $user_data['to_data'])->subject($user_data['subject']);
                if ($user_data['cc_data'] != '') {
                    $m->cc($user_data['cc_data']);
                }
                if (count($user_data['attach']) > 0) {
                    foreach ($user_data['attach'] as $attachfile) {
                        $m->attach($attachfile['filename'], [
                            'as' => $attachfile['as'],
                            'mime' => $attachfile['mime'],
                        ]);
                    }
                }
            });
        }
    }

}
