<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use App\Taskreminder;
use App\Http\Controllers\Controller;
use Session;

class DashboardController extends Controller {

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function dashboardInfo() {
        $type = 0;
        $reminderDetails = array();
        if (in_array('ROLE_TECHNICAL_MANAGER', Auth::user()->roles) || in_array('ROLE_TECHNICAL', Auth::user()->roles)) {
            $type = 1;

            $reminderDetails = DB::table('dpib_notification_details')->select('*')->whereRaw('created_date >= DATE_ADD(CURDATE(), INTERVAL -3 DAY)')->orderBy('id', 'asc')->get();
        } else if (in_array('ROLE_OPERATION_MANAGER', Auth::user()->roles) || in_array('ROLE_OPERATION', Auth::user()->roles)) {
            $type = 2;
            //return redirect()->action('Dashboard\DashboardController@customerList');
        } else if (in_array('ROLE_FINANCE_MANAGER', Auth::user()->roles) || in_array('ROLE_FINANCE', Auth::user()->roles)) {
            $type = 3;
            //return redirect()->action('Dashboard\DashboardController@customerList');
        } else {
            $type = 0;
        }


        $generalValues = $this->generalValues();
        $lineofbusiness = DB::table('line_of_business')->distinct()->where('status', '1')->orderBy('title')->pluck('title', 'id')->toArray();

        $quoteRequest = DB::table('crm_main_table as r')
                ->leftJoin('crm_task_table as t', 'r.id', '=', 't.crm_main_id')
                ->leftJoin('crm_request_table as rt', 'r.id', '=', 'rt.crm_id')
                ->leftJoin('customers as c', 'c.id', '=', 'r.customer_id')
                ->orderBy('c.updated_at', 'desc')
                ->whereIn('r.status', $generalValues['statusKey'])
                ->select('r.*', 'r.id as mainId', 'c.name as customerName', 't.*', 'rt.*', DB::raw("(case r.status when '0' then 'open' when '1' then 'under process' when '2' then 'Technical review' when '3' then 'Document approved' when '4' then 'Quote upload'  when '5' then 'Revise quotation'  when '6' then 'Request policy'  when '7' then 'Policy upload'  when '8' then 'Reject'  when '9' then 'Completed' when '10' then 'Lost' when '11' then 'Pending with sales' else 'Pending with client' end) AS statusString"))
                ->get();

        $whereArray[] = ['quotes_notification_flag', '1'];
        $whereArray[] = ['reminder_flag', '1'];

        $crmDetails = DB::table('crm_main_table as r')
                ->join('broking_slip_info as br', 'r.id', '=', 'br.crm_id')
                ->join('customers as c', 'c.id', '=', 'r.customer_id')
                ->join('insurer_details as ins', 'ins.id', '=', 'br.company_id')
                ->where($whereArray)
                ->whereNull('quotes_id')
                ->select('r.*', 'br.*', 'br.id as brokenId', 'c.name as client', 'ins.insurer_name as insurer', 'r.id as crmId', 'br.updated_date as updatedDate')
                ->get();


        $taskDetails = DB::table('reminders_details as r')
                ->leftJoin('users', 'users.id', '=', 'r.created_by')
                ->where('r.reminder_type', $type)
                ->where('r.reminder_date', '=', date('Y-m-d'))
                ->select('r.*', 'users.name as createdUser', DB::raw("(case r.priority when 0 then 'High' when 1 then 'Medium' when 2 then 'Low'  else 'low' end) AS priorityString"), DB::raw('DATE_FORMAT(r.reminder_date, "%d.%m.%Y") as formattedate '))
                ->get();



        $priorityArray = [0 => 'High', 1 => 'Medium', 2 => 'Low'];
        $generalValues['notificationCount'] = count($taskDetails);

        $customerDetails = DB::table('customers')->distinct()->orderBy('id')->pluck('name', 'id')->toArray();
        $users = DB::table('users')->distinct()->where('status', '1')->orderBy('name')->pluck('name', 'id')->toArray();

        $dashboardDetails = array();
        if (in_array('ROLE_TECHNICAL_MANAGER', Auth::user()->roles) || in_array('ROLE_TECHNICAL', Auth::user()->roles)) {
            $dashboardDetails = $this->currentMonthDetailsTechnical();
        } else if (in_array('ROLE_SALES_MANAGER', Auth::user()->roles) || in_array('ROLE_SALES', Auth::user()->roles)) {
            $dashboardDetails = $this->currentMonthDetailsSales();
        } else if (in_array('ROLE_OPERATION_MANAGER', Auth::user()->roles) || in_array('ROLE_OPERATION', Auth::user()->roles)) {
            $dashboardDetails = $this->currentYearDetailsOperation();
        } else {
            $dashboardDetails = $this->currentYearDetailsFinance();
        }


        $data = array("title" => 'Customers',
            "requestData" => $quoteRequest,
            'quotecount' => $generalValues['quotecount'],
            'policycount' => $generalValues['policycount'],
            'customercount' => $generalValues['customercount'],
            'premium' => $generalValues['premium'][0],
            'monthlyPremium' => $generalValues['monthlypolicyamount'],
            'customercountreport' => $generalValues['customercountreport'],
            'policycountreport' => $generalValues['policycountreport'],
            'quotecountreport' => $generalValues['quotecountreport'],
            'notificationDetails' => $crmDetails,
            'taskDetails' => $taskDetails,
            'priorityArray' => $priorityArray,
            'reminderType' => $type,
            'notificationCount' => $generalValues['notificationCount'],
            'customerDetails' => $customerDetails,
            'userDetails' => $users,
            'taskFlag' => true,
            'dashboardDetails' => $dashboardDetails,
            'lineofbusiness' => $lineofbusiness,
            'reminderDetails' => $reminderDetails
        );




        return view('Dashboard/index', $data);
    }

    /**
     * To display crm request of a customer according to the status
     * @param string $status
     * @return type
     */
    public function listCustomerRequest($status) {
        $generalValues = $this->generalValues();
        $priorityArray = [0 => 'High', 1 => 'Medium', 2 => 'Low'];
        $type = 0;

        if (in_array('ROLE_TECHNICAL_MANAGER', Auth::user()->roles) || in_array('ROLE_TECHNICAL', Auth::user()->roles)) {
            $type = 1;
        } else if (in_array('ROLE_OPERATION_MANAGER', Auth::user()->roles) || in_array('ROLE_OPERATION', Auth::user()->roles)) {
            $type = 2;
        } else if (in_array('ROLE_FINANCE_MANAGER', Auth::user()->roles) || in_array('ROLE_FINANCE', Auth::user()->roles)) {
            $type = 3;
        } else {
            $type = 0;
        }


        $quoteRequest = DB::table('crm_main_table as r')
                ->leftJoin('crm_task_table as t', 'r.id', '=', 't.crm_main_id')
                ->leftJoin('policies as p', 'p.id', '=', 'r.policy_id')
                ->leftJoin('crm_request_table as rt', 'r.id', '=', 'rt.crm_id')
                ->leftJoin('customers as c', 'c.id', '=', 'r.customer_id')
                ->select('r.*', 'r.id as mainId', 'c.name as customerName', 't.*', 'rt.*', DB::raw("(case r.status when '0' then 'open' when '1' then 'under process' when '2' then 'Technical review' when '3' then 'Document approved' when '4' then 'Quote uploaded'  when '5' then 'Revise quotation'  when '6' then 'Request policy'  when '7' then 'Policy uploaded'  when '8' then 'Rejected'  when '9' then 'Completed' when '10' then 'Lost' when '11' then 'Pending with sales' else 'Pending with client' end) AS statusString"),"p.id as policyId","p.policy_number");

        if ($status == 0) {
            $quoteRequest->whereIn('r.status', ['0', '1']);
        } else {
            $quoteRequest->where('r.status', '=', $status);
        }


        if (in_array('ROLE_SALES_MANAGER', Auth::user()->roles) || in_array('ROLE_TECHNICAL_MANAGER', Auth::user()->roles)) {
            
        } else if (in_array('ROLE_SALES', Auth::user()->roles) || in_array('ROLE_TECHNICAL', Auth::user()->roles)) {
            $quoteRequest->where('r.user_id', Auth::user()->id)->orWhere('r.assigned_to', Auth::user()->id);
        } else {
            
        }

        $quoteRequests = $quoteRequest->orderBy('c.updated_at', 'desc')->get();

        $taskDetails = DB::table('reminders_details as r')
                ->join('users', 'users.id', '=', 'r.created_by')
                ->where('r.reminder_type', $type)
                ->where('r.reminder_date', '=', date('Y-m-d'))
                ->select('r.*', 'users.name as createdUser', DB::raw("(case r.priority when 0 then 'High' when 1 then 'Medium' when 2 then 'Low'  else 'low' end) AS priorityString"), DB::raw('DATE_FORMAT(r.reminder_date, "%d.%m.%Y") as formattedate '))
                ->get();

        $generalValues['notificationCount'] = count($taskDetails);

        $data = array("title" => 'Customers',
            "requestData" => $quoteRequests,
            'notificationDetails' => array(),
            'priorityArray' => $priorityArray,
            'notificationCount' => $generalValues['notificationCount'],
        );

        return view('Dashboard/requestFilter', $data);
    }

    /**
     * To collect the general values
     * @return type
     */
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

    /**
     * To add task reminder
     * @param Request $request
     * @return type
     */
    public function addTaskReminder(Request $request) {
        $reminderId = $request->get('reminder_Id', 0);
        if ($reminderId > 0) {
            $taskReminderObj = Taskreminder::find($reminderId);
        } else {
            $taskReminderObj = new Taskreminder();
        }
        $taskReminderObj->reminder_date = date('Y-m-d', strtotime($request->get('reminder_date')));
        $taskReminderObj->created_date = date('Y-m-d h:i');
        $taskReminderObj->priority = $request->get('reminder_priority');
        $taskReminderObj->description = $request->get('reminder_description');
        $taskReminderObj->reminder_type = $request->get('reminder_type');
        $taskReminderObj->created_by = Auth::user()->id;
        $taskReminderObj->save();
        $message = ($reminderId > 0) ? "Successfully update reminder details!" : "Successfully add reminder!";
        return back()->with(['success' => $message]);
    }

    /**
     * To display appointment display
     * @return type
     */
    public function appointmentList() {

        $appointmentDetails = DB::table('dpib_appointments as ap')
                        ->join('dpib_events as e', 'e.id', '=', 'ap.event_id')
                        ->leftJoin('users as u', 'u.id', '=', 'ap.created_by')
                        ->where('ap.created_by', Auth::user()->id)
                        ->select('ap.*', 'u.name as appointmentHandler', 'e.color_selection')
                        ->orderBy('ap.appointment_date', 'asc')->get();

        $events = DB::table('dpib_events')->where('created_by', Auth::user()->id)->select('*')->orderBy('id', 'desc')->get();

        $sidebarValues = $this->sidebarMenurelatedData();
        $generalValues = $sidebarValues['generalValues'];

        $taskDetails = $sidebarValues['taskDetails'];


        $data = array("title" => 'Claims',
            "sidemenustatus" => $generalValues['statusDetails'],
            "countDetails" => $generalValues['statusCount'],
            'appointmentDetail' => $appointmentDetails,
            'notificationCount' => $taskDetails,
            'eventsDetails' => $events
        );

        return view('Dashboard/appointment', $data);
    }

    /**
     * To save appointment details
     * @param Request $request
     */
    public function appointmentAdd(Request $request) {

        $formData = $request->get('formData');
        if ($formData[5]['value'] === null) {
            $appointmentArray['event_id'] = $formData[2]['value'];
            $appointmentArray['appointment_end_date'] = null;
            $appointmentArray['description'] = $formData[1]['value'];
            $appointmentArray['appointment_date'] = date('Y-m-d h:i', strtotime($formData[3]['value']));
            $appointmentArray['created_by'] = Auth::user()->id;

            DB::table('dpib_appointments')->insert($appointmentArray);
        } else {
            $appointmentArray['description'] = $formData[1]['value'];
            DB::table('dpib_appointments')->where('id', $formData[5]['value'])->update($appointmentArray);
        }



        return response()->json(array('status' => true));
    }

    /**
     * To add events
     * @param Request $request
     * @return type
     */
    public function createEvents(Request $request) {
        $insertArray['created_date'] = date('Y-m-d h:i');
        $insertArray['title'] = $request->get('category-name');
        $insertArray['color_selection'] = $request->get('category-color', 0);
        $insertArray['created_by'] = Auth::user()->id;
        DB::table('dpib_events')->insert($insertArray); // Query Builder approach 
        return back()->with(['success' => 'Successfully add events!']);
    }

    /**
     * To delete notification
     * @param Request $request
     * @return type
     */
    public function deleteNotification(Request $request) {

        DB::table('reminders_details')->where('id', $request->get('notificationId'))->delete(); // Query Builder approach 
        return back()->with(['success' => 'Successfully delete reminder!']);
    }

    /**
     * To display the sales crm list
     * @return type
     */
    public function salesCrmList() {

        Session::forget('requestfilterform_' . Auth::user()->id);
        $quoteSqlObj = DB::table('crm_main_table as r')
                ->leftJoin('policies as p', 'p.id', '=', 'r.policy_id')
                ->leftJoin('crm_task_table as t', 'r.id', '=', 't.crm_main_id')
                ->leftJoin('crm_request_table as rt', 'r.id', '=', 'rt.crm_id')
                ->leftJoin('customers as c', 'c.id', '=', 'r.customer_id')
                ->leftJoin('users as u', 'u.id', '=', 'r.assigned_to')
                ->where('r.status', '>=', 0)
                ->select('r.*', 'r.id as mainId', 'c.id as customerId', 'c.name as customerName', 'u.name as assigned', 't.*', 'rt.*', DB::raw("(case r.status when '0' then 'New' when '1' then 'New' when '2' then 'Technical review' when '3' then 'Document approved' when '4' then 'Quote uploaded'  when '5' then 'Revise quotation'  when '6' then 'Request policy'  when '7' then 'Policy uploaded'  when '8' then 'Rejected'  when '9' then 'Completed' when '10' then 'Lost' when '11' then 'Pending with sales' else 'Pending with client' end) AS statusString"),"p.id as policyId","p.policy_number");

        if (in_array('ROLE_SALES_MANAGER', Auth::user()->roles) || in_array('ROLE_TECHNICAL_MANAGER', Auth::user()->roles) || in_array('ROLE_TECHNICAL_LEAD', Auth::user()->roles) ) {
           
        } else if (in_array('ROLE_SALES', Auth::user()->roles) || in_array('ROLE_TECHNICAL', Auth::user()->roles)) {
            $quoteSqlObj->where('r.user_id', Auth::user()->id)->orWhere('r.assigned_to', Auth::user()->id);
        } else {
            
        }

        $quoteRequest = $quoteSqlObj->orderBy('r.updated_date', 'desc')->get();

        $data = $this->getRequestlistdata();
        $data['requestData'] = $quoteRequest;

        return view('Dashboard/salescrmlist', $data);
    }

    /**
     * To show the customer data
     * @return type
     */
    public function customerList() {
        $sidebarValues = $this->sidebarMenurelatedData();
        $generalValues = $sidebarValues['generalValues'];

        $taskDetails = $sidebarValues['taskDetails'];

        $users = DB::table('users')->distinct()->where('status', '1')->orderBy('name')->pluck('name', 'id')->toArray();
        $userGroup = array('' => '--- not set ---', 'corporate' => 'Corporate', 'retail' => 'Retail', 'sales team' => 'Sales Team', 'wsale' => 'Wsale');

        $data = array("title" => 'Customers',
            'quotecount' => $generalValues['quotecount'],
            'policycount' => $generalValues['policycount'],
            'customercount' => $generalValues['customercount'],
            'premium' => $generalValues['premium'][0],
            'monthlyPremium' => $generalValues['monthlypolicyamount'],
            'customercountreport' => $generalValues['customercountreport'],
            'policycountreport' => $generalValues['policycountreport'],
            'quotecountreport' => $generalValues['quotecountreport'],
            'notificationCount' => $taskDetails,
            'users' => $users,
            'usergroup' => $userGroup
        );
        return view('Dashboard/customerlist', $data);
    }

    /**
     * to show the customer in the dashboard
     * @return type
     */
    public function dashboardCustomerlist() {
        $sidebarValues = $this->sidebarMenurelatedData();
        $generalValues = $sidebarValues['generalValues'];

        $taskDetails = $sidebarValues['taskDetails'];

        $users = DB::table('users')->distinct()->where('status', '1')->orderBy('name')->pluck('name', 'id')->toArray();
        $userGroup = array('' => '--- not set ---', 'corporate' => 'Corporate', 'retail' => 'Retail', 'sales team' => 'Sales Team', 'wsale' => 'Wsale');


        $data = array("title" => 'Dashboard',
            'quotecount' => $generalValues['quotecount'],
            'policycount' => $generalValues['policycount'],
            'customercount' => $generalValues['customercount'],
            'premium' => $generalValues['premium'][0],
            'monthlyPremium' => $generalValues['monthlypolicyamount'],
            'customercountreport' => $generalValues['customercountreport'],
            'policycountreport' => $generalValues['policycountreport'],
            'quotecountreport' => $generalValues['quotecountreport'],
            'notificationCount' => $taskDetails,
            "title" => 'Customers',
            'users' => $users,
            'usergroup' => $userGroup
        );


        return view('Dashboard/listcustomer', $data);
    }

    /**
     * To display dashboard complaints
     * @return type
     */
    public function dashboardComplaintList() {
        $sidebarValues = $this->sidebarMenurelatedData();
        $generalValues = $sidebarValues['generalValues'];

        $taskDetails = $sidebarValues['taskDetails'];


        $allComplaintsDetails = DB::table('policy_complaints as pc')
                        ->join('policies as p', 'pc.policy_id', '=', 'p.id')
                        ->leftJoin('users as u', 'u.id', '=', 'pc.handle_user')
                        ->leftJoin('customers as c', 'c.id', '=', 'pc.client_id')
                        ->leftJoin('users as us', 'us.id', '=', 'pc.created_by')
                        ->select('pc.*', 'u.name as handleUser', 'us.name as userName', DB::raw("(case pc.request_status when 1 then 'Open'  else 'Closed'   end) AS statusString"), DB::raw("(case pc.request_validity when 1 then 'Valid'  else 'Invalid'   end) AS complaintValidity"), 'p.policy_number', DB::raw("(case pc.compliant_type when 1 then 'Deletion delay' when 2 then 'Treatment rejection by provider' else 'Others'   end) AS complaintType"), "c.name as clientName")
                        ->orderBy('p.updated_at')->get();

        $data = array("title" => 'Dashboard',
            'quotecount' => $generalValues['quotecount'],
            'policycount' => $generalValues['policycount'],
            'customercount' => $generalValues['customercount'],
            'premium' => $generalValues['premium'][0],
            'monthlyPremium' => $generalValues['monthlypolicyamount'],
            'customercountreport' => $generalValues['customercountreport'],
            'policycountreport' => $generalValues['policycountreport'],
            'quotecountreport' => $generalValues['quotecountreport'],
            'notificationCount' => $taskDetails,
            "title" => 'Complaints',
            'complaintDetails' => $allComplaintsDetails
        );

        return view('Dashboard/complaintlist', $data);
    }

    /**
     * To display endorsement list
     * @return type
     */
    public function dashboardEndorsementList() {
        $sidebarValues = $this->sidebarMenurelatedData();
        $generalValues = $sidebarValues['generalValues'];

        $taskDetails = $sidebarValues['taskDetails'];
        $endorsementQuery = DB::table('crm_endorsement as ecr')
                ->join('policies as p', 'ecr.policy_id', '=', 'p.id')
                ->leftJoin('users as u', 'u.id', '=', 'ecr.created_by')
                ->leftJoin('users as su', 'su.id', '=', 'ecr.assign_to')
                ->select('ecr.*', 'su.name as AssignName', 'u.name as userName', DB::raw("(case ecr.status when 3 then 'Resolved' when 2 then 'Under process'  when 4 then 'Pending from insurer' when 5 then 'pending from client' when 6 then 'Completed with invoice' when 7 then 'Completed by client request' when 8 then 'Completed without invoice'  when 9 then 'Awaiting invoice' when 10 then 'Closed' else 'Open'   end) AS statusString"), 'p.policy_number');


        if (in_array('ROLE_OPERATION', Auth::user()->roles) && !in_array('ROLE_OPERATION_MANAGER', Auth::user()->roles) && !in_array('ROLE_OPERATION_LEAD', Auth::user()->roles)) {


            $endorsementQuery->where('ecr.assign_to', Auth::user()->id);
            //return redirect()->action('Dashboard\DashboardController@customerList');
        }
        $endorsementRequest = $endorsementQuery->orderBy('ecr.updated_at')->get();

        $users = DB::table('users')->distinct()->where('status', '1')->where('roles', 'like', "%ROLE_OPERATION%")->orderBy('name')->pluck('name', 'id')->toArray();


        $data = array("title" => 'Dashboard',
            'quotecount' => $generalValues['quotecount'],
            'policycount' => $generalValues['policycount'],
            'customercount' => $generalValues['customercount'],
            'premium' => $generalValues['premium'][0],
            'monthlyPremium' => $generalValues['monthlypolicyamount'],
            'customercountreport' => $generalValues['customercountreport'],
            'policycountreport' => $generalValues['policycountreport'],
            'quotecountreport' => $generalValues['quotecountreport'],
            'notificationCount' => $taskDetails,
            "title" => 'Endorsement',
            'endorsementDetails' => $endorsementRequest,
            'assignUsers' => $users
        );
        return view('Dashboard/endorsementlist', $data);
    }

    /**
     * To display finance policy list
     * @return type
     */
    public function dashboardFinancePolicyList() {
        $sidebarValues = $this->sidebarMenurelatedData();
        $generalValues = $sidebarValues['generalValues'];

        $taskDetails = $sidebarValues['taskDetails'];

        $postPolicies = DB::table('policies')
                        ->join('customers', 'customers.id', '=', 'policies.customer_id')
                        ->join('insurer_details', 'insurer_details.id', '=', 'policies.insurer_id')
                        ->leftJoin('policy_product_details', 'policy_product_details.policy_id', '=', 'policies.id')
                        ->leftJoin('insurance_product', 'insurance_product.id', '=', 'policies.product_id')
                        ->where('policies.policy_status', 1)
                        ->select('policies.*', DB::raw('DATE_FORMAT(policies.start_date, "%d.%m.%Y") as startDate'), DB::raw('DATE_FORMAT(policies.end_date, "%d.%m.%Y") as endDate'), 'insurer_details.insurer_name', 'policies.id as mainId', 'customers.name as customerName', 'insurance_product.product_name', DB::raw("(SELECT CONCAT(
  '[',GROUP_CONCAT(JSON_OBJECT('object_type',object_type,'make',make,'model',model,'year',year,'license_plate',license_plate,'last_name',last_name,'gender',gender,'address',address,'dob',dob,'property_type',property_type,'year_built',year_built,'area',area,'construction_material',construction_material)),
  ']'
) AS list FROM policy_product_object pobj
                                WHERE pobj.policy_id = policies.id
                                GROUP BY pobj.policy_id) as objectdetails"), DB::raw("(case policies.policy_status when 0 then 'Saved' when 1 then 'Policy posted' when 2 then 'Policy issued' when 4 then 'Expired' when 5 then 'Renewed' when 6 then 'Rejected' 
                                 end) AS statusString"))
                        ->orderBy('policies.updated_at', 'desc')->get();

        $approvedPolicies = DB::table('policies')
                        ->join('customers', 'customers.id', '=', 'policies.customer_id')
                        ->join('insurer_details', 'insurer_details.id', '=', 'policies.insurer_id')
                        ->leftJoin('policy_product_details', 'policy_product_details.policy_id', '=', 'policies.id')
                        ->leftJoin('insurance_product', 'insurance_product.id', '=', 'policies.product_id')
                        ->where('policies.policy_status', 2)
                        ->select('policies.*', DB::raw('DATE_FORMAT(policies.start_date, "%d.%m.%Y") as startDate'), DB::raw('DATE_FORMAT(policies.end_date, "%d.%m.%Y") as endDate'), 'insurer_details.insurer_name', 'policies.id as mainId', 'customers.name as customerName', 'insurance_product.product_name', DB::raw("(SELECT CONCAT(
  '[',GROUP_CONCAT(JSON_OBJECT('object_type',object_type,'make',make,'model',model,'year',year,'license_plate',license_plate,'last_name',last_name,'gender',gender,'address',address,'dob',dob,'property_type',property_type,'year_built',year_built,'area',area,'construction_material',construction_material)),
  ']'
) AS list FROM policy_product_object pobj
                                WHERE pobj.policy_id = policies.id
                                GROUP BY pobj.policy_id) as objectdetails"), DB::raw("(case policies.policy_status when 0 then 'Saved' when 1 then 'Policy posted' when 2 then 'Policy issued' when 4 then 'Expired' when 5 then 'Renewed' when 6 then 'Rejected' 
                                 end) AS statusString"))
                        ->orderBy('policies.updated_at', 'desc')->get();





        $data = array("title" => 'Dashboard',
            'quotecount' => $generalValues['quotecount'],
            'policycount' => $generalValues['policycount'],
            'customercount' => $generalValues['customercount'],
            'premium' => $generalValues['premium'][0],
            'monthlyPremium' => $generalValues['monthlypolicyamount'],
            'customercountreport' => $generalValues['customercountreport'],
            'policycountreport' => $generalValues['policycountreport'],
            'quotecountreport' => $generalValues['quotecountreport'],
            'notificationCount' => $taskDetails,
            "title" => 'Policy',
            'allpolicies' => $postPolicies,
            "approvedPolicies" => $approvedPolicies
        );
        return view('Dashboard/policyTable', $data);
    }

    /**
     * To display dashbaord finance policy details
     * @return type
     */
    public function dashboardFinancePolicyDetail() {

        $postPolicies = DB::table('policies')
                        ->join('customers', 'customers.id', '=', 'policies.customer_id')
                        ->join('insurer_details', 'insurer_details.id', '=', 'policies.insurer_id')
                        ->leftJoin('policy_product_details', 'policy_product_details.policy_id', '=', 'policies.id')
                        ->leftJoin('insurance_product', 'insurance_product.id', '=', 'policies.product_id')
                        ->where('policies.policy_status', 1)
                        ->select('policies.*', DB::raw('DATE_FORMAT(policies.start_date, "%d.%m.%Y") as startDate'), DB::raw('DATE_FORMAT(policies.end_date, "%d.%m.%Y") as endDate'), 'insurer_details.insurer_name', 'policies.id as mainId', 'customers.name as customerName', 'insurance_product.product_name', DB::raw("(SELECT CONCAT(
  '[',GROUP_CONCAT(JSON_OBJECT('object_type',object_type,'make',make,'model',model,'year',year,'license_plate',license_plate,'last_name',last_name,'gender',gender,'address',address,'dob',dob,'property_type',property_type,'year_built',year_built,'area',area,'construction_material',construction_material)),
  ']'
) AS list FROM policy_product_object pobj
                                WHERE pobj.policy_id = policies.id
                                GROUP BY pobj.policy_id) as objectdetails"), DB::raw("(case policies.policy_status when 0 then 'Saved' when 1 then 'Policy posted' when 2 then 'Policy issued' when 4 then 'Expired' when 5 then 'Renewed' when 6 then 'Rejected' 
                                 end) AS statusString"))
                        ->orderBy('policies.updated_at', 'desc')->get();

        $approvedPolicies = DB::table('policies')
                        ->join('customers', 'customers.id', '=', 'policies.customer_id')
                        ->join('insurer_details', 'insurer_details.id', '=', 'policies.insurer_id')
                        ->leftJoin('policy_product_details', 'policy_product_details.policy_id', '=', 'policies.id')
                        ->leftJoin('insurance_product', 'insurance_product.id', '=', 'policies.product_id')
                        ->where('policies.policy_status', 2)
                        ->select('policies.*', DB::raw('DATE_FORMAT(policies.start_date, "%d.%m.%Y") as startDate'), DB::raw('DATE_FORMAT(policies.end_date, "%d.%m.%Y") as endDate'), 'insurer_details.insurer_name', 'policies.id as mainId', 'customers.name as customerName', 'insurance_product.product_name', DB::raw("(SELECT CONCAT(
  '[',GROUP_CONCAT(JSON_OBJECT('object_type',object_type,'make',make,'model',model,'year',year,'license_plate',license_plate,'last_name',last_name,'gender',gender,'address',address,'dob',dob,'property_type',property_type,'year_built',year_built,'area',area,'construction_material',construction_material)),
  ']'
) AS list FROM policy_product_object pobj
                                WHERE pobj.policy_id = policies.id
                                GROUP BY pobj.policy_id) as objectdetails"), DB::raw("(case policies.policy_status when 0 then 'Saved' when 1 then 'Policy posted' when 2 then 'Policy issued' when 4 then 'Expired' when 5 then 'Renewed' when 6 then 'Rejected' 
                                 end) AS statusString"))
                        ->orderBy('policies.updated_at', 'desc')->get();



        $data = array(
            'allpolicies' => $postPolicies,
            'customerId' => 0,
            "title" => 'Policy',
            "approvedPolicies" => $approvedPolicies
        );
        return view('Dashboard/policyList', $data);
    }

    /**
     * To display finance endorsement list
     * @return type
     */
    public function financeEndorsementList() {
        $sidebarValues = $this->sidebarMenurelatedData();
        $generalValues = $sidebarValues['generalValues'];

        $taskDetails = $sidebarValues['taskDetails'];

        $endorsementDetails = DB::table('policy_endorsement as pe')
                        ->join('policies as p', 'p.id', '=', 'pe.policy_id')
                        ->join('customers as c', 'c.id', '=', 'p.customer_id')
                        ->join('insurer_details as ind', 'ind.id', '=', 'p.insurer_id')
                        ->select('pe.*', 'c.name as customerName', 'ind.insurer_name', DB::raw("(case pe.endorsement_type when 1 then 'Addition' when 3 then 'Deletion'  when 4 then 'Downgrade' when 9 then 'Upgrade'   end) AS typeString"), DB::raw('DATE_FORMAT(pe.start_date, "%d.%m.%Y") as formatted_startDate'), DB::raw('DATE_FORMAT(pe.issue_date, "%d.%m.%Y") as formatted_issueDate'), 'p.policy_number', 'p.end_date as expiry_date')
                        ->where('pe.endorsement_status', 1)->orderBy('pe.created_at')->get();

        $approvedEndorsementDetails = DB::table('policy_endorsement as pe')
                        ->join('policies as p', 'p.id', '=', 'pe.policy_id')
                        ->join('customers as c', 'c.id', '=', 'p.customer_id')
                        ->select('pe.*', 'c.name as customerName', DB::raw("(case pe.endorsement_type when 1 then 'Addition' when 3 then 'Deletion'  when 4 then 'Downgrade' when 9 then 'Upgrade'   end) AS typeString"), DB::raw('DATE_FORMAT(pe.start_date, "%d.%m.%Y") as formatted_startDate'), DB::raw('DATE_FORMAT(pe.issue_date, "%d.%m.%Y") as formatted_issueDate'), 'p.policy_number', 'p.end_date as expiry_date')
                        ->where('pe.endorsement_status', 2)->orderBy('pe.created_at')->get();


        $data = array("title" => 'Dashboard',
            'quotecount' => $generalValues['quotecount'],
            'policycount' => $generalValues['policycount'],
            'customercount' => $generalValues['customercount'],
            'premium' => $generalValues['premium'][0],
            'monthlyPremium' => $generalValues['monthlypolicyamount'],
            'customercountreport' => $generalValues['customercountreport'],
            'policycountreport' => $generalValues['policycountreport'],
            'quotecountreport' => $generalValues['quotecountreport'],
            'notificationCount' => $taskDetails,
            "title" => 'Policy',
            'approvedEndorsement' => $approvedEndorsementDetails,
            'endorsementDetails' => $endorsementDetails
        );
        return view('Dashboard/financeendorsementTable', $data);
    }

    /**
     * To display finance endorsement details
     * @return type
     */
    public function financeEndorsementDetail() {


        $endorsementDetails = DB::table('policy_endorsement as pe')
                        ->join('policies as p', 'p.id', '=', 'pe.policy_id')
                        ->select('pe.*', DB::raw("(case pe.endorsement_type when 1 then 'Addition' when 3 then 'Deletion'  when 4 then 'Downgrade' when 9 then 'Upgrade'   end) AS typeString"), DB::raw('DATE_FORMAT(pe.start_date, "%d.%m.%Y") as formatted_startDate'), DB::raw('DATE_FORMAT(pe.issue_date, "%d.%m.%Y") as formatted_issueDate'), 'p.policy_number')
                        ->where('pe.endorsement_status', 1)->orderBy('pe.created_at')->get();

        $approvedEndorsementDetails = DB::table('policy_endorsement as pe')
                        ->join('policies as p', 'p.id', '=', 'pe.policy_id')
                        ->select('pe.*', DB::raw("(case pe.endorsement_type when 1 then 'Addition' when 3 then 'Deletion'  when 4 then 'Downgrade' when 9 then 'Upgrade'   end) AS typeString"), DB::raw('DATE_FORMAT(pe.start_date, "%d.%m.%Y") as formatted_startDate'), DB::raw('DATE_FORMAT(pe.issue_date, "%d.%m.%Y") as formatted_issueDate'), 'p.policy_number')
                        ->where('pe.endorsement_status', 2)->orderBy('pe.created_at')->get();

        $data = array('endorsementDetails' => $endorsementDetails, "title" => 'Endorsements', 'approvedEndorsement' => $approvedEndorsementDetails);


        return view('Dashboard/financeEndorsementList', $data);
    }

    /**
     * To display technical policy details
     * @return type
     */
    public function technicalPolicyDetails() {
        $sidebarValues = $this->sidebarMenurelatedData();
        $generalValues = $sidebarValues['generalValues'];
        $taskDetails = $sidebarValues['taskDetails'];
        $allPolicies = DB::table('policies')
                        ->join('customers', 'customers.id', '=', 'policies.customer_id')
                        ->join('insurer_details', 'insurer_details.id', '=', 'policies.insurer_id')
                        ->leftJoin('policy_product_details', 'policy_product_details.policy_id', '=', 'policies.id')
                        ->leftJoin('insurance_product', 'insurance_product.id', '=', 'policies.product_id')
                        ->select('policies.*', DB::raw('DATE_FORMAT(policies.start_date, "%d.%m.%Y") as startDate'), DB::raw('DATE_FORMAT(policies.end_date, "%d.%m.%Y") as endDate'), 'insurer_details.insurer_name', 'policies.id as mainId', 'customers.name as customerName', 'insurance_product.product_name', DB::raw("(SELECT CONCAT(
  '[',GROUP_CONCAT(JSON_OBJECT('object_type',object_type,'make',make,'model',model,'year',year,'license_plate',license_plate,'last_name',last_name,'gender',gender,'address',address,'dob',dob,'property_type',property_type,'year_built',year_built,'area',area,'construction_material',construction_material)),
  ']'
) AS list FROM policy_product_object pobj
                                WHERE pobj.policy_id = policies.id
                                GROUP BY pobj.policy_id) as objectdetails"), DB::raw("(case policies.policy_status when 0 then 'Saved' when 1 then 'Policy posted' when 2 then 'Policy issued' when 4 then 'Expired' when 5 then 'Renewed' when 6 then 'Rejected' 
                                 end) AS statusString"))
                        ->orderBy('policies.updated_at', 'desc')->get();




        $data = array("title" => 'Dashboard',
            'quotecount' => $generalValues['quotecount'],
            'policycount' => $generalValues['policycount'],
            'customercount' => $generalValues['customercount'],
            'premium' => $generalValues['premium'][0],
            'monthlyPremium' => $generalValues['monthlypolicyamount'],
            'customercountreport' => $generalValues['customercountreport'],
            'policycountreport' => $generalValues['policycountreport'],
            'quotecountreport' => $generalValues['quotecountreport'],
            'notificationCount' => $taskDetails,
            "title" => 'Policy', 'allpolicies' => $allPolicies, 'customerId' => 0
        );
        return view('Dashboard/technicalPolicyTable', $data);
    }

    /**
     * To list the invoice from the sidebar
     * 
     */
    public function invoiceList() {
        $sidebarValues = $this->sidebarMenurelatedData();
        $generalValues = $sidebarValues['generalValues'];

        $taskDetails = $sidebarValues['taskDetails'];


        $invoiceDetails = DB::table('policy_invoice as iv')
                        ->join('policies', 'policies.id', '=', 'iv.policy_id')
                        ->leftJoin('customers as c', 'c.id', '=', 'policies.customer_id')
                        ->leftJoin('insurance_product as ip', 'ip.id', '=', 'policies.product_id')
                        ->select('iv.*', 'policies.*', 'c.*', 'ip.product_name as productName', DB::raw("(case iv.paid_status when '0' then 'Invoice Created' when '1' then 'Paid'  end) AS invoiceStatusString"), 'policies.customer_id as customerId', 'iv.id as invoiceId')
                        ->orderBy('iv.generated_date', 'asc')->get();


        $data = array("title" => 'Dashboard',
            'quotecount' => $generalValues['quotecount'],
            'policycount' => $generalValues['policycount'],
            'customercount' => $generalValues['customercount'],
            'premium' => $generalValues['premium'][0],
            'monthlyPremium' => $generalValues['monthlypolicyamount'],
            'customercountreport' => $generalValues['customercountreport'],
            'policycountreport' => $generalValues['policycountreport'],
            'quotecountreport' => $generalValues['quotecountreport'],
            'notificationCount' => $taskDetails,
            "title" => 'Invoice',
            "invoicedetails" => $invoiceDetails
        );
        return view('Invoice/invoicelist', $data);
    }

    /**
     * 
     * @return type
     */
    public function sidebarMenurelatedData() {
        $generalValues = $this->generalValues();
        $type = 0;
        if (in_array('ROLE_TECHNICAL_MANAGER', Auth::user()->roles) || in_array('ROLE_TECHNICAL', Auth::user()->roles)) {
            $type = 1;
        } else if (in_array('ROLE_OPERATION_MANAGER', Auth::user()->roles) || in_array('ROLE_OPERATION', Auth::user()->roles)) {
            $type = 2;
        } else if (in_array('ROLE_FINANCE_MANAGER', Auth::user()->roles) || in_array('ROLE_FINANCE', Auth::user()->roles)) {
            $type = 3;
        } else {
            $type = 0;
        }
        $taskDetails = DB::table('reminders_details as r')
                ->join('users', 'users.id', '=', 'r.created_by')
                ->where('r.reminder_type', $type)
                ->where('r.reminder_date', '=', date('Y-m-d'))
                ->select('r.id')
                ->count();


        $data = array('generalValues' => $generalValues, 'taskDetails' => $taskDetails);
        return $data;
    }

    /**
     * To list the payment in the dashboard
     * 
     */
    public function paymentList() {
        $sidebarValues = $this->sidebarMenurelatedData();
        $generalValues = $sidebarValues['generalValues'];

        $taskDetails = $sidebarValues['taskDetails'];


        $paymentDetails = DB::table('policy_invoice_payment as pm')
                        ->join('policy_invoice as iv', 'iv.id', '=', 'pm.invoice_id')
                        ->join('policies as p', 'p.id', '=', 'iv.policy_id')
                        ->select('pm.*', 'p.customer_id', DB::raw("(case pm.payment_transfer_type when 1 then 'bank transfer' when 2 then 'cash payment'  when 3 then 'credit card payment' when 4 then 'cheque' when 5 then 'rounding payment' when 6 then 'insurer payment'   else 'offsetting entry'   end) AS paymentmethodString"))
                        ->orderBy('pm.created_date', 'desc')->get();
        $paymentMode = [1 => 'bank transfer', 2 => 'cash payment', 3 => 'credit card payment', 4 => 'cheque', 5 => 'rounding payment', 6 => 'insurer payment', 7 => 'offsetting entry'];

        $unpaidInvoice = DB::table('policy_invoice as iv')->where('iv.paid_status', 0)->orderBy('id')->pluck('id', 'id')->toArray();

        $customers = DB::table('policies as p')
                        ->join('customers as c', 'c.id', '=', 'p.customer_id')
                        ->groupBy('p.customer_id')
                        ->pluck('c.name', 'c.id')->toArray();

        $data = array("title" => 'Dashboard',
            'quotecount' => $generalValues['quotecount'],
            'policycount' => $generalValues['policycount'],
            'customercount' => $generalValues['customercount'],
            'premium' => $generalValues['premium'][0],
            'monthlyPremium' => $generalValues['monthlypolicyamount'],
            'customercountreport' => $generalValues['customercountreport'],
            'policycountreport' => $generalValues['policycountreport'],
            'quotecountreport' => $generalValues['quotecountreport'],
            'notificationCount' => $taskDetails,
            "title" => 'Invoice',
            "paymentData" => $paymentDetails,
            "paymentMode" => $paymentMode,
            "unpaidInvoice" => $unpaidInvoice,
            "customers" => $customers
        );
        return view('Dashboard/payments', $data);
    }

    /**
     * 
     * @return type
     */
    public function leadsDetails() {
        $sidebarValues = $this->sidebarMenurelatedData();
        $generalValues = $sidebarValues['generalValues'];

        $taskDetails = $sidebarValues['taskDetails'];


        $paymentDetails = DB::table('policy_invoice_payment as pm')
                        ->join('policy_invoice as iv', 'iv.id', '=', 'pm.invoice_id')
                        ->join('policies as p', 'p.id', '=', 'iv.policy_id')
                        ->select('pm.*', 'p.customer_id', DB::raw("(case pm.payment_transfer_type when 1 then 'bank transfer' when 2 then 'cash payment'  when 3 then 'credit card payment' when 4 then 'cheque' when 5 then 'rounding payment' when 6 then 'insurer payment'   else 'offsetting entry'   end) AS paymentmethodString"))
                        ->orderBy('pm.created_date', 'desc')->get();
        $paymentMode = [1 => 'bank transfer', 2 => 'cash payment', 3 => 'credit card payment', 4 => 'cheque', 5 => 'rounding payment', 6 => 'insurer payment', 7 => 'offsetting entry'];

        $unpaidInvoice = DB::table('policy_invoice as iv')->where('iv.paid_status', 0)->orderBy('id')->pluck('id', 'id')->toArray();

        $customers = DB::table('policies as p')
                        ->join('customers as c', 'c.id', '=', 'p.customer_id')
                        ->groupBy('p.customer_id')
                        ->pluck('c.name', 'c.id')->toArray();

        $data = array("title" => 'Dashboard',
            'quotecount' => $generalValues['quotecount'],
            'policycount' => $generalValues['policycount'],
            'customercount' => $generalValues['customercount'],
            'premium' => $generalValues['premium'][0],
            'monthlyPremium' => $generalValues['monthlypolicyamount'],
            'customercountreport' => $generalValues['customercountreport'],
            'policycountreport' => $generalValues['policycountreport'],
            'quotecountreport' => $generalValues['quotecountreport'],
            'notificationCount' => $taskDetails,
            "title" => 'Invoice',
            "paymentData" => $paymentDetails,
            "paymentMode" => $paymentMode,
            "unpaidInvoice" => $unpaidInvoice,
            "customers" => $customers
        );
        return view('Dashboard/leads', $data);
    }

    /**
     * Sales team dashboard data
     * @return type
     */
    private function currentMonthDetailsSales() {
        $customerCount = DB::table('customers as c')
                ->join('policies as p', 'p.customer_id', '=', 'c.id')
                ->select('c.id')
                ->whereMonth('c.created_at', '=', date('m'))
                ->whereYear('c.created_at', '=', date('Y'))
                ->where('p.policy_status', 2)
                ->groupBy('c.id')
                ->get();


        $leadsCount = DB::table('customers as c')
                ->leftJoin('policies as p', 'p.customer_id', '=', 'c.id')
                ->whereMonth('c.created_at', '=', date('m'))
                ->whereYear('c.created_at', '=', date('Y'))
                ->whereNull('p.id')
                ->count();



        $policySum = DB::table('customers as c')
                ->join('policies as p', 'p.customer_id', '=', 'c.id')
                ->select(DB::raw('sum(p.total_premium)'))
                ->whereMonth('p.created_at', '=', date('m'))
                ->whereYear('p.created_at', '=', date('Y'))
                ->where('p.policy_status', 2)
                ->sum('p.total_premium');


        $renewalCount = DB::table('customers as c')
                ->join('policies as p', 'p.customer_id', '=', 'c.id')
                ->select(DB::raw('sum(p.total_premium)'))
                ->whereMonth('p.created_at', '=', date('m'))
                ->whereYear('p.created_at', '=', date('Y'))
                ->where('p.renewal_status', 1)
                ->whereNotNull('p.previous_policy_id')
                ->count();
        $leadsData = DB::table('customers as c')
                        ->leftJoin('policies as p', 'p.customer_id', '=', 'c.id')
                        ->whereYear('c.created_at', '=', date('Y'))
                        ->whereNull('p.id')
                        ->select(DB::raw('count(c.id) as count'), DB::raw(' MONTH(c.created_at) month'))
                        ->groupBy('month')
                        ->get()->pluck('count', 'month')->toArray();
        $customerData = DB::table('customers as c')
                        ->join('policies as p', 'p.customer_id', '=', 'c.id')
                        ->whereYear('c.created_at', '=', date('Y'))
                        ->where('p.policy_status', 2)
                        ->select(DB::raw('count(c.id) as count'), DB::raw(' MONTH(c.created_at) month'))
                        ->groupBy('month')
                        ->get()->pluck('count', 'month')->toArray();

        $monthArray = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
        $newArray = [];
        $existingMonth = (count($leadsData) > 0) ? array_keys($leadsData) : [];
        $customerExistingMonth = (count($customerData) > 0) ? array_keys($customerData) : [];
        foreach ($monthArray as $key => $month) {

            if (in_array($month, $existingMonth)) {
                $newArray[$key] = ['y' => $month, 'a' => $leadsData[$month]];
            } else {
                $newArray[$key] = ['y' => $month, 'a' => 0];
            }
            if (in_array($month, $customerExistingMonth)) {
                $newArray[$key]['b'] = $customerData[$month];
            } else {
                $newArray[$key]['b'] = 0;
            }
        }

        return array('renewalCount' => $renewalCount, 'policySum' => $policySum, 'leadsCount' => $leadsCount, 'customerCount' => count($customerCount), 'graphData' => $newArray, 'customerType' => 'leads');
    }

    /**
     * Technical team dashboard data
     * @return type
     */
    private function currentMonthDetailsTechnical() {
        $customerCount = DB::table('customers as c')
                ->join('policies as p', 'p.customer_id', '=', 'c.id')
                ->select('c.id')
                ->whereMonth('c.created_at', '=', date('m'))
                ->whereYear('c.created_at', '=', date('Y'))
                ->where('p.policy_status', 2)
                ->groupBy('c.id')
                ->get();

        $policyCount = DB::table('policies as p')
                ->whereMonth('p.issue_date', '=', date('m'))
                ->whereYear('p.issue_date', '=', date('Y'))
                ->where('p.policy_status', 2)
                ->count();
        $quoteCount = DB::table('quote as q')
                ->whereMonth('q.created_at', '=', date('m'))
                ->whereYear('q.created_at', '=', date('Y'))
                ->where('q.status', '1')
                ->count();


        $policySum = DB::table('customers as c')
                ->join('policies as p', 'p.customer_id', '=', 'c.id')
                ->select(DB::raw('sum(p.total_premium)'))
                ->whereMonth('p.issue_date', '=', date('m'))
                ->whereYear('p.issue_date', '=', date('Y'))
                ->where('p.policy_status', 2)
                ->sum('p.total_premium');



        $endorsementData = DB::table('policy_endorsement as e')
                        ->leftJoin('policy_intallment as im', 'e.id', '=', 'im.endorsement_id')
                        ->whereYear('e.issue_date', '=', date('Y'))
                        ->where(function ($query) {
                            $query->where('e.endorsement_status', '=', 2)
                            ->orWhere('e.endorsement_status', '=', null);
                        })
                        ->select(DB::raw('(SUM(e.amount)+SUM(e.vat_amount)) as sum'), DB::raw(' MONTH(e.created_at) month'))
                        ->groupBy('month')
                        ->get()->pluck('sum', 'month')->toArray();


        $productionData = DB::table('policies as p')
                        ->whereYear('p.issue_date', '=', date('Y'))
                        ->where('p.policy_status', 2)
                        ->select(DB::raw('SUM(p.total_premium) as sum'), DB::raw(' MONTH(p.issue_date) month'))
                        ->groupBy('month')
                        ->orderBy('month')
                        ->get()->pluck('sum', 'month')->toArray();



        $monthArray = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
        $newArray = [];
        $existingMonth = (count($endorsementData) > 0) ? array_keys($endorsementData) : [];
        $productionExistingMonth = (count($productionData) > 0) ? array_keys($productionData) : [];
        foreach ($monthArray as $key => $month) {

            if (in_array($month, $existingMonth)) {
                $newArray[$key] = ['y' => $month, 'b' => $endorsementData[$month]];
            } else {
                $newArray[$key] = ['y' => $month, 'b' => 0];
            }
            if (in_array($month, $productionExistingMonth)) {
                $newArray[$key]['a'] = $productionData[$month];
            } else {
                $newArray[$key]['a'] = 0;
            }
        }

        //endorsemets

        $allPolicies = DB::table('policies')
                        ->join('customers', 'customers.id', '=', 'policies.customer_id')
                        ->join('insurer_details', 'insurer_details.id', '=', 'policies.insurer_id')
                        ->leftJoin('policy_product_details', 'policy_product_details.policy_id', '=', 'policies.id')
                        ->leftJoin('insurance_product', 'insurance_product.id', '=', 'policies.product_id')
                        ->select('policies.*', DB::raw('DATE_FORMAT(policies.start_date, "%d.%m.%Y") as startDate'), DB::raw('DATE_FORMAT(policies.end_date, "%d.%m.%Y") as endDate'), 'insurer_details.insurer_name', 'policies.id as mainId', 'customers.name as customerName', 'insurance_product.product_name', DB::raw("(SELECT CONCAT(
  '[',GROUP_CONCAT(JSON_OBJECT('object_type',object_type,'make',make,'model',model,'year',year,'license_plate',license_plate,'last_name',last_name,'gender',gender,'address',address,'dob',dob,'property_type',property_type,'year_built',year_built,'area',area,'construction_material',construction_material)),
  ']'
) AS list FROM policy_product_object pobj
                                WHERE pobj.policy_id = policies.id
                                GROUP BY pobj.policy_id) as objectdetails"), DB::raw("(case policies.policy_status when 0 then 'Saved' when 1 then 'Policy posted' when 2 then 'Policy issued' when 4 then 'Expired' when 5 then 'Renewed' when 6 then 'Rejected' 
                                 end) AS statusString"))
                        ->orderBy('policies.updated_at', 'desc')->get();

        $quoteData = DB::table('quote')
                        ->join('customers as c', 'c.id', '=', 'quote.customer_id')
                        ->join('broking_slip_info', 'broking_slip_info.quotes_id', '=', 'quote.id')
                        ->join('insurer_details', 'insurer_details.id', '=', 'quote.company_id')
                        ->join('insurance_product', 'insurance_product.id', '=', 'broking_slip_info.product_id')
                        ->leftJoin('users as u', 'quote.upload_by', '=', 'u.id')
                        ->select('quote.*', 'insurer_details.insurer_name', 'quote.id as mainId', 'insurance_product.product_name', 'u.name as uploadedBy', 'c.name as customerName')
                        ->orderBy('quote.created_at', 'desc')->get();

        return array('policyCount' => $policyCount, 'policySum' => $policySum, 'quoteCount' => $quoteCount, 'customerCount' => count($customerCount), 'graphData' => $newArray, 'customerType' => 'customers', 'quoteData' => $quoteData, 'allpolicies' => $allPolicies);
    }

    /**
     * 
     */
    private function currentYearDetailsOperation() {

        $endorsementCount = DB::table('policy_endorsement as pe')
                ->whereMonth('pe.issue_date', '=', date('m'))
                ->whereYear('pe.issue_date', '=', date('Y'))
                ->where('pe.endorsement_status', 0)
                ->count();
        $claimCount = DB::table('policy_claims as pc')
                ->whereMonth('pc.submitted_broker_date', '=', date('m'))
                ->whereYear('pc.submitted_broker_date', '=', date('Y'))
                ->count();

        $complaintCount = DB::table('policy_complaints as c')
                ->whereMonth('c.requested_date', '=', date('m'))
                ->whereYear('c.requested_date', '=', date('Y'))
                ->count();

        $endorsementSum = DB::table('policy_endorsement as e')
                ->join('policies as p', 'p.id', '=', 'e.policy_id')
                ->where('e.endorsement_status', 2)
                ->whereYear('e.issue_date', '=', date('Y'))
                ->sum('e.amount');

        $claimDetails = DB::table('policy_claims as pc')
                        ->join('policies as p', 'p.id', '=', 'pc.policy_id')
                        ->join('customers as c', 'pc.customer_id', '=', 'c.id')
                        ->leftJoin('users as u', 'u.id', '=', 'pc.claim_handler')
                        ->select('pc.*', 'c.name as customerName', 'p.policy_number', 'c.id_code', 'c.customer_code', DB::raw("(SELECT CONCAT(
  '[',GROUP_CONCAT(JSON_OBJECT('name',pci.name,'type',(case pci.claimant_type when 1 then 'Policy holder' when 2 then 'Death claim' when 3 then 'Medical claim' when 4 then 'Motor claim' else 'Travel claim' end))),
  ']'
) AS list FROM policy_claimant_info pci
                                WHERE pci.claim_id = pc.id
                                GROUP BY pci.claim_id) as claimant"), 'u.name as claimHandler', DB::raw("(case pc.status when 1 then '[Open] Awaiting info/documents' when 2 then '[Open]Claim reopened' when 3 then '[Open] Awaiting repair approval from insurer' when 4 then '[Open] Awaiting repair check from from insurer' when 5 then 'Issue policy' when 6 then 'Issue policy' when 7 then 'Issue policy' when 8 then 'Issue policy' when 9 then 'Issue policy' when 10 then 'Issue policy' when 11 then 'Issue policy' when 12 then 'Issue policy' when 13 then 'Issue policy' end) AS statusString"))
                        ->orderBy('pc.updated_date', 'desc')->get();
        $endorsementTypeArray = [1, 4, 5, 11];
        $endorsementGroupDetails = DB::table('policy_endorsement as pe')
                        ->whereYear('pe.issue_date', '=', date('Y'))
                        ->whereIn('pe.endorsement_type', $endorsementTypeArray)
                        ->select(DB::raw('count(pe.id) as count'), 'pe.endorsement_type as eType', DB::raw(' MONTH(pe.created_at) month'))
                        ->groupBy('pe.endorsement_type', 'month')
                        ->orderBy('month')
                        ->get()->toArray();


        $endorsementTypeArray = [1, 4, 5, 11];
        $monthArray = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];

        $newArray = [];
        $monthwiseArray = [];
        foreach ($monthArray as $key => $month) {
            $monthwiseArray[$month] = ['y' => $month, 1 => 0, 4 => 0, 5 => 0, 11 => 0];


            if (count($endorsementGroupDetails) > 0) {

                foreach ($endorsementGroupDetails as $details) {
                    if ($month == $details->month) {
                        $newArray[$details->month][$details->eType] = $details->count;
                        $newArray[$details->month]['y'] = $details->month;
                    }
                }
            } else {
                $newArray[$month] = ['y' => $month, 1 => 0, 4 => 0, 5 => 0, 11 => 0];
            }
        }


        $mergedArray = $this->createMergeArray($monthwiseArray, $newArray);

        $allComplaintsDetails = DB::table('policy_complaints as pc')
                        ->join('policies as p', 'pc.policy_id', '=', 'p.id')
                        ->leftJoin('users as u', 'u.id', '=', 'pc.handle_user')
                        ->leftJoin('customers as c', 'c.id', '=', 'pc.client_id')
                        ->leftJoin('users as us', 'us.id', '=', 'pc.created_by')
                        ->select('pc.*', 'u.name as handleUser', 'us.name as userName', DB::raw("(case pc.request_status when 1 then 'Open'  else 'Closed'   end) AS statusString"), DB::raw("(case pc.request_validity when 1 then 'Valid'  else 'Invalid'   end) AS complaintValidity"), 'p.policy_number', DB::raw("(case pc.compliant_type when 1 then 'Deletion delay' when 2 then 'Treatment rejection by provider' else 'Others'   end) AS complaintType"), "c.name as clientName")
                        ->orderBy('p.updated_at')->get();


        return array('endorsementcount' => $endorsementCount, 'endorsemetsum' => $endorsementSum, 'claimcount' => $claimCount, 'complaintcount' => $complaintCount, 'graphData' => $mergedArray, 'customerType' => 'customers', 'claimDetails' => $claimDetails, 'complaintDetails' => $allComplaintsDetails);
    }

    /**
     * 
     * @param type $array1
     * @param type $array2
     * @return type
     */
    private function createMergeArray($array1, $array2) {
        $resultArray = [];
        foreach ($array1 as $key => $val) { // Loop though one array
            if (array_key_exists($key, $array2)) {
                $val2 = $array2[$key]; //Get the values from the other array
                $differnce = array_diff_assoc($val, $val2);
                if (count($differnce) > 0) {
                    $array2[$key] = $val2 + $differnce;
                }
                $resultArray[] = $array2[$key];  // combine 'em   
            } else {
                $resultArray[] = $array1[$key];
            }
        }
        return $resultArray;
    }

    /**
     * 
     * @return type
     */
    private function currentYearDetailsFinance() {
        $customerCount = DB::table('customers as c')
                ->join('policies as p', 'p.customer_id', '=', 'c.id')
                ->whereMonth('p.issue_date', '=', date('m'))
                ->whereYear('p.issue_date', '=', date('Y'))
                ->where('p.policy_status', 2)
                ->count();

        $policyCount = DB::table('policies as p')
                ->whereMonth('p.issue_date', '=', date('m'))
                ->whereYear('p.issue_date', '=', date('Y'))
                ->where('p.policy_status', 2)
                ->count();



        $productionSum = DB::table('policy_intallment as im')
                        ->leftJoin('policy_endorsement as e', 'e.id', '=', 'im.endorsement_id')
                        ->whereYear('im.due_date', '=', date('Y'))
                        ->where(function ($query) {
                            $query->where('e.endorsement_status', '=', 2)
                            ->orWhere('e.endorsement_status', '=', null);
                        })->sum('im.amount');


        $vatSum = DB::table('policy_intallment as im')
                        ->leftJoin('policy_endorsement as e', 'e.id', '=', 'im.endorsement_id')
                        ->whereYear('im.due_date', '=', date('Y'))
                        ->where(function ($query) {
                            $query->where('e.endorsement_status', '=', 2)
                            ->orWhere('e.endorsement_status', '=', null);
                        })->sum('im.vat_amount');

        $productionDetails = DB::table('policy_intallment as im')
                        ->leftJoin('policy_endorsement as e', 'e.id', '=', 'im.endorsement_id')
                        ->whereYear('im.due_date', '=', date('Y'))
                        ->where(function ($query) {
                            $query->where('e.endorsement_status', '=', 2)
                            ->orWhere('e.endorsement_status', '=', null);
                        })
                        ->select(DB::raw('sum(im.amount) as production'), DB::raw('sum(im.vat_amount) as vatAmount'), DB::raw(' MONTH(im.due_date) month'))
                        ->groupBy('month')
                        ->orderBy('month')
                        ->get()->toArray();



        $monthArray = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];

        $newArray = [];
        $monthwiseArray = [];
        foreach ($monthArray as $key => $month) {
            $monthwiseArray[$month] = ['y' => $month, 'production' => 0, 'vat' => 0];


            if (count($productionDetails) > 0) {

                foreach ($productionDetails as $details) {
                    if ($month == $details->month) {
                        $newArray[$details->month]['production'] = $details->production;
                        $newArray[$details->month]['vat'] = $details->vatAmount;
                        $newArray[$details->month]['y'] = $details->month;
                    }
                }
            } else {
                $newArray[$month] = ['y' => $month, 'production' => 0, 'vat' => 0];
            }
        }

        $mergedArray = $this->createMergeArray($monthwiseArray, $newArray);

        $postPolicies = DB::table('policies')
                        ->join('customers', 'customers.id', '=', 'policies.customer_id')
                        ->join('insurer_details', 'insurer_details.id', '=', 'policies.insurer_id')
                        ->leftJoin('policy_product_details', 'policy_product_details.policy_id', '=', 'policies.id')
                        ->leftJoin('insurance_product', 'insurance_product.id', '=', 'policies.product_id')
                        ->where('policies.policy_status', 1)
                        ->select('policies.*', DB::raw('DATE_FORMAT(policies.start_date, "%d.%m.%Y") as startDate'), DB::raw('DATE_FORMAT(policies.end_date, "%d.%m.%Y") as endDate'), 'insurer_details.insurer_name', 'policies.id as mainId', 'customers.name as customerName', 'insurance_product.product_name', DB::raw("(SELECT CONCAT(
  '[',GROUP_CONCAT(JSON_OBJECT('object_type',object_type,'make',make,'model',model,'year',year,'license_plate',license_plate,'last_name',last_name,'gender',gender,'address',address,'dob',dob,'property_type',property_type,'year_built',year_built,'area',area,'construction_material',construction_material)),
  ']'
) AS list FROM policy_product_object pobj
                                WHERE pobj.policy_id = policies.id
                                GROUP BY pobj.policy_id) as objectdetails"), DB::raw("(case policies.policy_status when 0 then 'Saved' when 1 then 'Policy posted' when 2 then 'Policy issued' when 4 then 'Expired' when 5 then 'Renewed' when 6 then 'Rejected' 
                                 end) AS statusString"))
                        ->orderBy('policies.updated_at', 'desc')->get();

        $postendorsementDetails = DB::table('policy_endorsement as pe')
                        ->join('policies as p', 'p.id', '=', 'pe.policy_id')
                        ->select('pe.*', DB::raw("(case pe.endorsement_type when 1 then 'Addition' when 3 then 'Deletion'  when 4 then 'Downgrade' when 9 then 'Upgrade'   end) AS typeString"), DB::raw('DATE_FORMAT(pe.start_date, "%d.%m.%Y") as formatted_startDate'), DB::raw('DATE_FORMAT(pe.issue_date, "%d.%m.%Y") as formatted_issueDate'), 'p.policy_number')
                        ->where('pe.endorsement_status', 1)->orderBy('pe.created_at')->get();

        return array('customercount' => $customerCount, 'policycount' => $policyCount, 'productionsum' => $productionSum, 'vatsum' => $vatSum, 'graphData' => $mergedArray, 'customerType' => 'customers', 'postpolicy' => $postPolicies, 'postendorsementdetails' => $postendorsementDetails);
    }

    /**
     * 
     * @param Request $request
     * @return type
     */
    public function createMultiDayEvents(Request $request) {

        $formData = $request->get('formData');
        $insertArray['created_date'] = date('Y-m-d h:i');
        $insertArray['title'] = $formData[1]['value'];
        $insertArray['color_selection'] = $formData[2]['value'];
        $insertArray['created_by'] = Auth::user()->id;

        $insertId = DB::table('dpib_events')->insertGetId($insertArray); // Query Builder approach

        $appointmentArray['event_id'] = $insertId;
        $appointmentArray['appointment_end_date'] = date('Y-m-d h:i', strtotime($formData[4]['value']));
        $appointmentArray['description'] = $formData[1]['value'];
        $appointmentArray['appointment_date'] = date('Y-m-d h:i', strtotime($formData[3]['value']));
        $appointmentArray['created_by'] = Auth::user()->id;

        DB::table('dpib_appointments')->insert($appointmentArray);

        return response()->json(array('status' => true, 'redirect' => action('Dashboard\DashboardController@appointmentList')));
    }

    /**
     * 
     * @param Request $request
     * @return type
     */
    public function deleteAppointments(Request $request) {
        $appointmentId = $request->get('id', 0);
        if ($appointmentId > 0) {
            DB::table('dpib_appointments')->where('id', $appointmentId)->delete();
            return response()->json(array('status' => true));
        }
    }

    /**
     * 
     * @return type
     */
    public function endorsementDetailList() {

        $sidebarValues = $this->sidebarMenurelatedData();
        $generalValues = $sidebarValues['generalValues'];

        $taskDetails = $sidebarValues['taskDetails'];

        $approvedEndorsementDetails = DB::table('policy_endorsement as pe')
                        ->join('policies as p', 'p.id', '=', 'pe.policy_id')
                        ->select('pe.*', DB::raw("(case pe.endorsement_type when 1 then 'Addition' when 3 then 'Deletion'  when 4 then 'Downgrade' when 9 then 'Upgrade'   end) AS typeString"), DB::raw('DATE_FORMAT(pe.start_date, "%d.%m.%Y") as formatted_startDate'), DB::raw('DATE_FORMAT(pe.issue_date, "%d.%m.%Y") as formatted_issueDate'), 'p.policy_number', 'p.end_date as expiryDate')
                        ->where('pe.endorsement_status', 2)->orderBy('pe.created_at')->get();


        $data = array("title" => 'Dashboard',
            'quotecount' => $generalValues['quotecount'],
            'policycount' => $generalValues['policycount'],
            'customercount' => $generalValues['customercount'],
            'premium' => $generalValues['premium'][0],
            'monthlyPremium' => $generalValues['monthlypolicyamount'],
            'customercountreport' => $generalValues['customercountreport'],
            'policycountreport' => $generalValues['policycountreport'],
            'quotecountreport' => $generalValues['quotecountreport'],
            'notificationCount' => $taskDetails,
            "title" => 'Endorsements',
            'endorsementDetails' => $approvedEndorsementDetails
        );

        return view('Dashboard/endorsementDetailTable', $data);
    }

    public function quoteDetailList() {

        $sidebarValues = $this->sidebarMenurelatedData();
        $generalValues = $sidebarValues['generalValues'];

        $taskDetails = $sidebarValues['taskDetails'];

        $quoteData = DB::table('quote')
                        ->join('customers as c', 'c.id', '=', 'quote.customer_id')
                        ->join('broking_slip_info', 'broking_slip_info.quotes_id', '=', 'quote.id')
                        ->join('insurer_details', 'insurer_details.id', '=', 'quote.company_id')
                        ->join('insurance_product', 'insurance_product.id', '=', 'broking_slip_info.product_id')
                        ->leftJoin('users as u', 'quote.upload_by', '=', 'u.id')
                        ->select('quote.*', 'insurer_details.insurer_name', 'quote.id as mainId', 'insurance_product.product_name', 'u.name as uploadedBy', 'c.name as customerName')
                        ->orderBy('quote.created_at', 'desc')->get();

        $data = array("title" => 'Dashboard',
            'quotecount' => $generalValues['quotecount'],
            'policycount' => $generalValues['policycount'],
            'customercount' => $generalValues['customercount'],
            'premium' => $generalValues['premium'][0],
            'monthlyPremium' => $generalValues['monthlypolicyamount'],
            'customercountreport' => $generalValues['customercountreport'],
            'policycountreport' => $generalValues['policycountreport'],
            'quotecountreport' => $generalValues['quotecountreport'],
            'notificationCount' => $taskDetails,
            "title" => 'Quotes',
            'quoteData' => $quoteData
        );

        return view('Dashboard/quoteDetailTable', $data);
    }

    /**
     * To get quote sending pop up form
     * @param type $customerId
     * @param type $crmId
     * @param type $docId
     * @return type
     */
    public function sendRemindermailForm($reminderId) {
        $data['rId'] = $reminderId;

        $returnHTML = view('Dashboard/sendmailpopup', $data)->render();
        return response()->json(array('status' => true, 'content' => $returnHTML));
    }

    /**
     * To send mail to specified mail ids
     * @param Request $request
     * @param integer $rId
     *
     */
    public function sendReminderMail(Request $request, $rId) {

        $user_data = array();
        $user_data['to_data'] = $request->get('to_data');
        $user_data['cc_data'] = $request->get('cc_data');
        $user_data['subject'] = $request->get('subject');
        $user_data['message'] = $request->get('message');

        Mail::send('emails.brokesliptemplate', ['maildata' => $user_data], function ($m) use ($user_data) {
            $m->from('info@dbroker.com.sa', 'Diamond insurance broker');
            $m->to($user_data['to_data'], $user_data['to_data'])->subject($user_data['subject']);
            if ($user_data['cc_data'] != '') {
                $m->cc($user_data['cc_data']);
            }
        });
        // check for failures


        return back()->with(['success' => 'successfully send reminder mail']);
    }

    public function operationRequestlist() {
        $sidebarValues = $this->sidebarMenurelatedData();
        $generalValues = $sidebarValues['generalValues'];

        $taskDetails = $sidebarValues['taskDetails'];

        $endorsementRequest = DB::table('crm_endorsement as ecr')
                        ->join('policies as p', 'ecr.policy_id', '=', 'p.id')
                        ->join('customers as c', 'p.customer_id', '=', 'c.id')
                        ->leftJoin('users as u', 'u.id', '=', 'ecr.created_by')
                        ->select('ecr.*', 'c.name as customerName', 'c.id as customerId', 'u.name as userName', DB::raw("(case ecr.status when 3 then 'Resolved' when 2 then 'Under process'  when 4 then 'Pending from insurer' when 5 then 'pending from client' when 6 then 'Completed with invoice' when 7 then 'Completed by client request' when 8 then 'Completed without invoice'  when 9 then 'Awaiting invoice' when 10 then 'Closed' else 'Open'   end) AS statusString"), 'p.policy_number')
                        ->whereIn('ecr.type', [1, 3, 4, 9])
                        ->orderBy('ecr.updated_at', 'desc')->get();



        $data = array("title" => 'Dashboard',
            'quotecount' => $generalValues['quotecount'],
            'policycount' => $generalValues['policycount'],
            'customercount' => $generalValues['customercount'],
            'premium' => $generalValues['premium'][0],
            'monthlyPremium' => $generalValues['monthlypolicyamount'],
            'customercountreport' => $generalValues['customercountreport'],
            'policycountreport' => $generalValues['policycountreport'],
            'quotecountreport' => $generalValues['quotecountreport'],
            'notificationCount' => $taskDetails,
            "title" => 'Endorsement',
            'endorsementDetails' => $endorsementRequest
        );
        return view('Dashboard/financeoperationrequestlist', $data);
    }

    /**
     * 
     * @return type
     */
    public function incompleteRequest() {
        $sidebarValues = $this->sidebarMenurelatedData();
        $generalValues = $sidebarValues['generalValues'];

        $taskDetails = $sidebarValues['taskDetails'];

        $endorsementRequest = DB::table('crm_endorsement as ecr')
                        ->join('policies as p', 'ecr.policy_id', '=', 'p.id')
                        ->join('customers as c', 'p.customer_id', '=', 'c.id')
                        ->leftJoin('users as u', 'u.id', '=', 'ecr.created_by')
                        ->select('ecr.*', 'c.name as customerName', 'c.id as customerId', 'u.name as userName', DB::raw("(case ecr.status when 3 then 'Resolved' when 2 then 'Under process'  when 4 then 'Pending from insurer' when 5 then 'pending from client' when 6 then 'Completed with invoice' when 7 then 'Completed by client request' when 8 then 'Completed without invoice'  when 9 then 'Awaiting invoice' when 10 then 'Closed'  else 'Open'   end) AS statusString"), 'p.policy_number')
                        ->whereIn('ecr.type', [1, 3, 4, 9])
                        ->where('ecr.is_insly_entered', 0)
                        ->orderBy('ecr.updated_at', 'desc')->get();



        $data = array("title" => 'Dashboard',
            'quotecount' => $generalValues['quotecount'],
            'policycount' => $generalValues['policycount'],
            'customercount' => $generalValues['customercount'],
            'premium' => $generalValues['premium'][0],
            'monthlyPremium' => $generalValues['monthlypolicyamount'],
            'customercountreport' => $generalValues['customercountreport'],
            'policycountreport' => $generalValues['policycountreport'],
            'quotecountreport' => $generalValues['quotecountreport'],
            'notificationCount' => $taskDetails,
            "title" => 'Endorsement',
            'endorsementDetails' => $endorsementRequest
        );
        return view('Dashboard/incompleteoperationrequestlist', $data);
    }

    /**
     * 
     * @param Request $request
     * @return type
     */
    public function requestFilter(Request $request) {

        $filtername = $request->get('filter_request_number', '');
        $filterstatus = $request->get('filter_request_status', '');

        $quoteSqlObj = DB::table('crm_main_table as r')
                ->leftJoin('crm_task_table as t', 'r.id', '=', 't.crm_main_id')
                ->leftJoin('crm_request_table as rt', 'r.id', '=', 'rt.crm_id')
                ->leftJoin('policies as p', 'p.id', '=', 'r.policy_id')
                ->leftJoin('customers as c', 'c.id', '=', 'r.customer_id')
                ->leftJoin('users as u', 'u.id', '=', 'r.assigned_to')
                
                ->select('r.*', 'r.id as mainId', 'c.id as customerId', 'c.name as customerName', 'u.name as assigned', 't.*', 'rt.*', DB::raw("(case r.status when '0' then 'New' when '1' then 'New' when '2' then 'Technical review' when '3' then 'Document approved' when '4' then 'Quote uploaded'  when '5' then 'Revise quotation'  when '6' then 'Request policy'  when '7' then 'Policy uploaded'  when '8' then 'Rejected'  when '9' then 'Completed' when '10' then 'Lost' when '11' then 'Pending with sales' else 'Pending with client' end) AS statusString"),"p.id as policyId","p.policy_number");

       

        $whereArray = [];
        $formData = [];
        if ($filtername != '') {
            $whereArray[] = ['r.crm_request_id', $filtername];

            $formData['filterrequestnumber'] = $filtername;
        }
        if ($filterstatus != '') {
           
            $whereArray[] = ['r.status', '=', $filterstatus];
            $formData['filterstatus'] = $filterstatus;
        }


        if (count($whereArray) > 0) {
            $quoteSqlObj->where($whereArray);
            Session::put('requestfilter_' . Auth::user()->id, json_encode($whereArray));
           // Session::put('requestfilterform_' . Auth::user()->id, json_encode($formData));
        }
         if (in_array('ROLE_SALES_MANAGER', Auth::user()->roles) || in_array('ROLE_TECHNICAL_MANAGER', Auth::user()->roles)) {
            
        } else if (in_array('ROLE_SALES', Auth::user()->roles) || in_array('ROLE_TECHNICAL', Auth::user()->roles)) {
            $quoteSqlObj->whereRaw( "(r.user_id=".Auth::user()->id."   OR r.assigned_to =".Auth::user()->id .")");
        } else {
            
        }
        
   //dd($quoteSqlObj->orderBy('r.updated_date', 'desc')->toSql(), $quoteSqlObj->getBindings());
        $quoteRequest = $quoteSqlObj->orderBy('r.updated_date', 'desc')->get(); 

        $data = $this->getRequestlistdata();
        $data['requestData'] = $quoteRequest;
        $data['formData']    = $formData;
        return view('Dashboard/salescrmlist', $data);
        
    }
/**
 * Customer crm request list datas
 * @return type
 */
    private function getRequestlistdata() {
        $sidebarValues = $this->sidebarMenurelatedData();
        $generalValues = $sidebarValues['generalValues'];
        $taskDetails = $sidebarValues['taskDetails'];
        $customerDetails = DB::table('customers')->distinct()->orderBy('id')->pluck('name', 'id')->toArray();
        $users = DB::table('users')->distinct()->where('status', '1')->orderBy('name')->pluck('name', 'id')->toArray();
        $lineofbusiness = DB::table('line_of_business')->distinct()->where('status', '1')->orderBy('title')->pluck('title', 'id')->toArray();
        $statusArray = ['Open', 'Under process', 'Technical review', 'Approved submissions', 'Quote uploaded', 'Revise quotation', 'Request policy', 'Policy uploaded', 'Reject', 'Completed', 'Lost','Pending with sales','Pending with client'];
        $data = array("title" => 'Dashboard',            
            'quotecount' => $generalValues['quotecount'],
            'policycount' => $generalValues['policycount'],
            'customercount' => $generalValues['customercount'],
            'premium' => $generalValues['premium'][0],
            'monthlyPremium' => $generalValues['monthlypolicyamount'],
            'customercountreport' => $generalValues['customercountreport'],
            'policycountreport' => $generalValues['policycountreport'],
            'quotecountreport' => $generalValues['quotecountreport'],
            'notificationCount' => $taskDetails,
            'customerDetails' => $customerDetails,
            'userDetails' => $users,
            'lineofbusiness' => $lineofbusiness,
            'statusArray' => $statusArray
        );
        return $data;
    }

}
