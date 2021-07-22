<?php

namespace App\Http\Controllers\Management;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use App\Http\Controllers\Controller;
use Session;

class ManagementController extends Controller {

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function managementBoard($clearflag = 1) {
        $dashboardDetails = $this->currentMonthDetailsTechnical();
        $userGroup = array('' => '--- not set ---', 'corporate' => 'Corporate', 'retail' => 'Retail', 'sme' => 'SME');
        $formData = [];
        if ($clearflag == 1) {
            Session::forget('customerfilter_' . Auth::user()->id);
        }

        $whereArray = [];
        $sessionData = Session::get('customerfilter_' . Auth::user()->id);
        if ($sessionData != '') {
            $whereArray = json_decode($sessionData, true);
            $formData = json_decode(Session::get('customerfilterform_' . Auth::user()->id), true);
        }


        $query = DB::table('customers as c')
                ->join('policies as p', 'p.customer_id', '=', 'c.id')
                ->leftJoin('customer_address as ca', 'c.id', '=', 'ca.customer_id')
                ->leftJoin('customer_contact_person_info as cp', 'c.id', '=', 'cp.customer_id')
                ->leftJoin('users as u', 'c.customer_management_user', '=', 'u.id');

        if (count($whereArray) > 0) {
            $query->where($whereArray);
        }
        $customerData = $query->orderBy('c.updated_at', 'desc')
                ->groupby('c.id')
                ->select('c.*', 'ca.*', 'p.id as policyId', 'cp.*', 'c.id as customId', 'c.email as customerEmail', 'c.phone as customerPhone', 'cp.email as contactEmail', 'cp.phone as contactPhone', 'u.name as userName', 'c.name as customerName', DB::raw('(select count(dp.id) from customers as dc join policies as dp on dp.customer_id=dc.id where dp.policy_status=2 AND dp.customer_id=c.id) as policyCount '), DB::raw('(select min(dp.end_date) from customers as dc join policies as dp on dp.customer_id=dc.id where dp.policy_status=2 AND dp.customer_id=c.id) as policyEnddate '), DB::raw('(select dp.id from policies as dp  where dp.policy_status=2 AND dp.customer_id=c.id  HAVING min(dp.end_date)   ) as endpolicyId '))
                ->paginate(12);

        $data = array("title" => 'Customers',
            'dashboardDetails' => $dashboardDetails,
            'customerDatas' => $customerData,
            'usergroup' => $userGroup,
            'formData' => $formData
        );

        return view('Management/index', $data);
    }

    /**
     * Technical team dashboard data
     * @return type
     */
    private function currentMonthDetailsTechnical() {
        $customerCount = DB::table('customers as c')
                ->join('policies as p', 'p.customer_id', '=', 'c.id')
                ->whereYear('p.end_date', '=', date('Y'))
                ->whereIn('p.policy_status', [2,4])
                ->count();

        $policyCount = DB::table('policies as p')
                ->whereYear('p.start_date', '=', date('Y'))
                ->where('p.policy_status', [2])
                ->count();
        $claimCount = DB::table('policy_claims as pc')
                ->whereYear('pc.created_date', '=', date('Y'))
                
                ->count();


        $policySum = DB::table('customers as c')
                ->join('policies as p', 'p.customer_id', '=', 'c.id')
                ->select(DB::raw('sum(p.total_premium)'))
                ->whereYear('p.start_date', '=', date('Y'))
                ->where('p.policy_status', 2)
                ->sum('p.total_premium');
        
//        $policySum =DB::table('policy_intallment as im')
//                        ->leftJoin('policy_endorsement as e', 'e.id', '=', 'im.endorsement_id')
//                        ->whereYear('im.due_date', '=', date('Y'))
//                        ->where(function ($query) {
//                                $query  ->where('e.endorsement_status', '=', 2)
//                                        ->orWhere('e.endorsement_status', '=', null);
//            })
//                        
//                        
//                        ->sum(function ($row) {
//    return $row->amount + $row->vat_amount;
//});


        return array('policyCount' => $policyCount, 'policySum' => $policySum, 'claimCount' => $claimCount, 'customerCount' => $customerCount);
    }

    /**
     * 
     * @param Request $request
     * @return type
     */
    public function customerFilter(Request $request) {

        $filtername = $request->get('filter_customer_name', '');
        $filtertype = $request->get('filter_customer_type', '');
        $filtergroup = $request->get('filter_customergroup_oid', '');

        $query = DB::table('customers as c')
                ->join('policies as p', 'p.customer_id', '=', 'c.id')
                ->leftJoin('customer_address as ca', 'c.id', '=', 'ca.customer_id')
                ->leftJoin('customer_contact_person_info as cp', 'c.id', '=', 'cp.customer_id')
                ->leftJoin('users as u', 'c.customer_management_user', '=', 'u.id');
        $whereArray = [];
        $formData = [];
        if ($filtername != '') {
            $whereArray[] = ['c.name', 'LIKE', '%' . $filtername . '%'];

            $formData['filtername'] = $filtername;
        }
        if ($filtertype != '') {
            $whereArray[] = ['c.type', '=', $filtertype];
            $formData['filtertype'] = $filtertype;
        }
        if ($filtergroup != '') {
            $whereArray[] = ['c.customer_group', '=', $filtergroup];
            $formData['filtergroup'] = $filtergroup;
        }

        if (count($whereArray) > 0) {
            $query->where($whereArray);
            Session::put('customerfilter_' . Auth::user()->id, json_encode($whereArray));
            Session::put('customerfilterform_' . Auth::user()->id, json_encode($formData));
        }
        $customerData = $query->orderBy('c.updated_at', 'desc')
                        ->groupby('c.id')
                        ->select('c.*', 'ca.*', 'p.id as policyId', 'cp.*', 'c.id as customId', 'c.email as customerEmail', 'c.phone as customerPhone', 'cp.email as contactEmail', 'cp.phone as contactPhone', 'u.name as userName', 'c.name as customerName', DB::raw('(select count(dp.id) from customers as dc join policies as dp on dp.customer_id=dc.id where dp.policy_status=2 AND dp.customer_id=c.id) as policyCount '), DB::raw('(select min(dp.end_date) from customers as dc join policies as dp on dp.customer_id=dc.id where dp.policy_status=2 AND dp.customer_id=c.id) as policyEnddate '), DB::raw('(select dp.id from policies as dp  where dp.policy_status=2 AND dp.customer_id=c.id  HAVING min(dp.end_date)   ) as endpolicyId '))
                        ->paginate(12)->setPath(route('managementdashboard', [0]));

        $dashboardDetails = $this->currentMonthDetailsTechnical();
        $userGroup = array('' => '--- not set ---', 'corporate' => 'Corporate', 'retail' => 'Retail', 'sme' => 'SME');

        $data = array("title" => 'Customers',
            'dashboardDetails' => $dashboardDetails,
            'customerDatas' => $customerData,
            'usergroup' => $userGroup,
            'formData' => $formData
        );
        return view('Management/index', $data);
    }

    /**
     * 
     * @return type
     */
    public function managementPolicy() {

        $dashboardDetails = $this->currentMonthDetailsTechnical();
        
        $policyYearlySum = DB::table('policies')
                        ->where('policy_status', 2)
                        ->select(DB::raw('sum(gross_amount)+sum(additional_amount) as `tamount`'), DB::raw('YEAR(issue_date) year'))
                        ->groupby('year')->get();

        $endorsementYearlySum = DB::table('policy_endorsement')
                        ->where('endorsement_status', 2)
                        ->select(DB::raw('sum(amount) as `tamount`'), DB::raw('YEAR(issue_date) year'))
                        ->groupby('year')->get();

        $policyMonthlySum = DB::table('policies')
                        ->whereYear('issue_date', '=', date('Y'))
                        ->where('policy_status', 2)
                        ->select(DB::raw('sum(gross_amount)+sum(additional_amount) as `tamount`'), DB::raw('YEAR(issue_date) year, MONTH(issue_date) month'))
                        ->groupby('year', 'month')->get();

        $endorsementMonthlySum = DB::table('policy_endorsement')
                        ->whereYear('issue_date', '=', date('Y'))
                        ->where('endorsement_status', 1)
                        ->select(DB::raw('sum(amount) as `tamount`'), DB::raw('YEAR(issue_date) year, MONTH(issue_date) month'))
                        ->groupby('year', 'month')->get();

        
        
        $complaintCountMonthly = DB::table('policy_complaints')
                        ->whereYear('requested_date', '=', date('Y'))                   
                        ->select(DB::raw('count(id) as `tamount`'), DB::raw('YEAR(requested_date) year, MONTH(requested_date) month'))
                        ->groupby('year', 'month')->get();
        
        $claimCountMonthly = DB::table('policy_claims')
                        ->whereYear('submitted_broker_date', '=', date('Y'))                   
                        ->select(DB::raw('count(id) as `tamount`'), DB::raw('YEAR(submitted_broker_date) year, MONTH(submitted_broker_date) month'))
                        ->groupby('year', 'month')->get();
        
        
        $monthwisePolicyData = $this->getMonthArray($policyMonthlySum);
        $monthwiseEndorsementData = $this->getMonthArray($endorsementMonthlySum);

        $yearlywisePolicydata = $this->getYearArray($policyYearlySum);
        $yearlywiseEndorsementdata = $this->getYearArray($endorsementYearlySum);
        
        $complaintMonthlyCount = $this->getMonthArray($complaintCountMonthly,false);
        $claimMonthlycount = $this->getMonthArray($claimCountMonthly,false);

        $data = array('endorsementMonthlywise' => json_encode($monthwiseEndorsementData, true), 'policyMonthlywise' => json_encode($monthwisePolicyData, true), 'endorsementYearlywise' => json_encode($yearlywiseEndorsementdata, true), 'policyYearlywise' => json_encode($yearlywisePolicydata, true),'dashboardDetails' => $dashboardDetails,'complaintMonthly'=>json_encode($complaintMonthlyCount, true), 'claimMonthly'=>json_encode($claimMonthlycount, true), );


        return view('Management/policygraph', $data);
    }

    /**
     * 
     * @param type $dataArray
     * @return type
     */
    private function getMonthArray($dataArray,$formatFlag=true) {
        $monthArray = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
        $newArray = [];
        foreach ($monthArray as $key => $month) {
            $newArray[$month] = 0;
            foreach ($dataArray as $data) {
                if ($data->month == $key) {
                    $newArray[$data->month] = ($formatFlag)? number_format((float) $data->tamount, 2, '.', '') : $data->tamount;
                    continue;
                }
            }
        }
        return $newArray;
    }

    /**
     * 
     * @param type $dataArray
     * @return type
     */
    private function getYearArray($dataArray, $formatFlag=true) {
        $yearArray = [];
        $currentdate = date('Y-m-d');
        for ($i = 2015; $i <= date("Y-m-d", strtotime(date("Y-m-d", strtotime($currentdate)) . " + 1 year")); $i++) {
            $yearArray[] = $i;
        }
        $newArray = [];
        foreach ($yearArray as $key => $year) {
            $newArray[$year] = 0;
            foreach ($dataArray as $data) {
                if ($data->year == $year) {
                    $newArray[$data->year] = ($formatFlag)? number_format((float) $data->tamount, 2, '.', '') : $data->tamount;
                    continue;
                }
            }
        }
        return $newArray;
    }
    
}
