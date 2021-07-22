<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of NavbarComposer
 *
 * @author j.mani
 */

namespace App\DibComposers;

use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Auth;

class NavbarComposer {

    public $movieList = [];

    /**
     * Create a movie composer.
     *
     * @return void
     */
    public function __construct() {
        $this->movieList = [
            'Shawshank redemption',
            'Forrest Gump',
            'The Matrix',
            'Pirates of the Carribean',
            'Back to the future',
        ];
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view) {

        $generalValues = $this->generalValues();
        $notificationDetails = $this->notificationDetails();
        $details = array('sidemenustatus' => $generalValues['statusDetails'], 'countDetails' => $generalValues['statusCount'],'notificationDetails'=>$notificationDetails);
        
        
        $view->with('navbarValues', $details);
    }

    public function generalValues() {
        $generalValues = array();

        if (in_array('ROLE_TECHNICAL_MANAGER', Auth::user()->roles) || in_array('ROLE_TECHNICAL', Auth::user()->roles)) {
            $statusArray = [2, 3, 4, 5, 6];
            $satatus = array("2" => "Technical Review", "3" => "Document Approved", "4" => "Quote uploaded", "5" => "Revise quotation", "6" => "Request policy");
        } else if (in_array('ROLE_SALES_MANAGER', Auth::user()->roles) || in_array('ROLE_SALES', Auth::user()->roles)) {
            $statusArray = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
            $satatus = array("0" => "New", "2" => "Technical Review", "3" => "Document Approved", "4" => "Quote uploaded", "5" => "Revise quotation", "6" => "Request policy", "7" => "Policy uploaded", "9" => "Completed");
        } else {
            $statusArray = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
            $satatus = array("0" => "New", "1" => "Under process", "2" => "Technical Review", "3" => "Document Approved", "4" => "Quote uploaded", "5" => "Revise quotation", "6" => "Request policy", "7" => "Policy uploaded", "8" => "Reject", "9" => "Completed");
        }

        $generalValues['quotecount'] = DB::table('quote')->count();
        $generalValues['policycount'] = DB::table('policies')->where('policy_number', "!=", null)->count();
        $generalValues['customercount'] = DB::table('customers')->count();
        $generalValues['premium'] = DB::table('policies')->select(DB::raw("sum(total_premium) as premium"))->where('policy_number', "!=", null)->get();
        $generalValues['statusDetails'] = $satatus;
        $generalValues['statusKey'] = $statusArray;
        $generalValues['statusCount'] = DB::table('crm_main_table as r')->select(DB::raw("count(id) as count"), 'status')->groupBy('status')->get();
        $monthlypolicyamount = DB::table('policies')->select(DB::raw("sum(gross_amount) as premium"), DB::raw("MONTHNAME(created_at) as monthname"), DB::raw('YEAR(created_at) year, MONTH(created_at) month'))->where('policy_number', "!=", null)->groupby('year', 'month')->get();
        $customerreport = DB::table('customers')->select(DB::raw("count(id) as customercount"), DB::raw("MONTHNAME(created_at) as monthname"), DB::raw('YEAR(created_at) year, MONTH(created_at) month'))->groupby('year', 'month')->get();
        $policycountReport = DB::table('policies')->select(DB::raw("count(id) as policycount"), DB::raw("MONTHNAME(created_at) as monthname"), DB::raw('YEAR(created_at) year, MONTH(created_at) month'))->where('policy_number', "!=", null)->groupby('year', 'month')->get();
        $quotecountReport = DB::table('quote')->select(DB::raw("count(id) as quotecount"), DB::raw("MONTHNAME(created_at) as monthname"), DB::raw('YEAR(created_at) year, MONTH(created_at) month'))->groupby('year', 'month')->get();
        $generalValues['monthlypolicyamount'] = $monthlypolicyamount->pluck('premium', 'monthname')->toArray();
        $generalValues['customercountreport'] = $customerreport->pluck('monthname', 'customercount')->toArray();
        $generalValues['policycountreport'] = $policycountReport->pluck('monthname', 'policycount')->toArray();
        $generalValues['quotecountreport'] = $quotecountReport->pluck('monthname', 'quotecount')->toArray();


        return $generalValues;
    }

    public function notificationDetails() {
        $notificationArray = []; 
        if (Auth::user()) {
            $userRoles = Auth::user()->roles;
            $mainRole = $userRoles[0];
           
            if (strpos($mainRole, 'TECHNICAL') !== false) {
                $notificationArray = DB::table('notification_details')->select('*')->where('department', 'LIKE', '%TECHNICAL%')->where('created_date','>',DB::raw('(CURDATE() - INTERVAL 3 DAY)')  )->get();
            } else if (strpos($mainRole, 'SALES') !== false) {
                $notificationArray = DB::table('notification_details')->select('*')->where('department', 'LIKE', '%SALES%')->where('created_date','>',DB::raw('(CURDATE() - INTERVAL 3 DAY)') )->get();
            } else if (strpos($mainRole, 'OPERATION') !== false) {
                $notificationArray = DB::table('notification_details')->select('*')->where('department', 'LIKE', '%OPERATION%')->where('created_date','>',DB::raw('(CURDATE() - INTERVAL 3 DAY)') )->get();
            } else if (strpos($mainRole, 'FINANCE') !== false) {
                $notificationArray = DB::table('notification_details')->select('*')->where('department', 'LIKE', '%FINANCE%')->where('created_date','>',DB::raw('(CURDATE() - INTERVAL 3 DAY)') )->get();
            }
            
        }
        
        return $notificationArray;
    }

}
