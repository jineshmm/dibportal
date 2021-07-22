<?php

namespace App\Http\Controllers\Quote;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use App;
//use App\Mail\dpportalmail;
use Mail;
use App\customer;
use App\Dpibquoterequest;
use App\crmMain;
use App\crmTask;
use App\crmRequest;
use App\Customercrmdocuments;
use App\Http\Controllers\Controller;
use Session;
use PDF;
use File;
use Illuminate\Support\Facades\Input;
use Excel;
use Illuminate\Support\Facades\Storage;

class QuoteController extends Controller {

    /**
     * Create customer page
     * @return type
     */
    public function quoterequestList($customerId) {
        $quoteRequestObj = new Dpibquoterequest();
        $statusArray = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10,11,12];
        if (in_array('ROLE_TECHNICAL_MANAGER', Auth::user()->roles) || in_array('ROLE_TECHNICAL', Auth::user()->roles)) {
            $statusArray = [0,1,2, 3, 4, 5, 6,10,11,12];
        } else if (in_array('ROLE_SALES_MANAGER', Auth::user()->roles) || in_array('ROLE_SALES', Auth::user()->roles)) {
            $statusArray = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10,11,12];
        } else {
            $statusArray = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10,11,12];
        }

        $quoteRequestDetails = $quoteRequestObj->getQuoterequestList($customerId, $statusArray);

        $documentcount = count(DB::table('dp_customer_document')->where('customer_id', '=', $customerId)->get());
        $data = array('requestData' => $quoteRequestDetails, 'customerId' => $customerId, 'documentCount' => $documentcount);
        $returnHTML = view('quote/requestlist', $data)->render();
        return response()->json(array('status' => true, 'content' => $returnHTML));
    }

    /**
     * To get quote request form
     * @param integer $customerId
     * @return type
     */
    public function getquoterequestForm($customerId) {
        $customerDetails = DB::table('customers')->distinct()->orderBy('id')->pluck('name', 'id')->toArray();
        $users = DB::table('users')->distinct()->where('status', '1')->orderBy('name')->pluck('name', 'id')->toArray();
        $lineofbusiness = DB::table('line_of_business')->distinct()->where('status', '1')->orderBy('title')->pluck('title', 'id')->toArray();
        $data = array('customerId' => $customerId, 'customerDetails' => $customerDetails, 'userDetails' => $users, 'lineofbusiness' => $lineofbusiness);

        $returnHTML = view('quote/requestaddform', $data)->render();
        return response()->json(array('status' => true, 'content' => $returnHTML));
    }

    /**
     * To save crm request details
     * @param Request $request
     * @param integer $customerId
     * @return type
     */
    public function savecrmrequestForm(Request $request, $customerId) {

        $requestId = substr("CRM-" . uniqid(date("Ymdhi")), 0, -12);
        $crmMainObj = new crmMain();
        $crmMainObj->customer_id = $request->get('customer_select');
        $crmMainObj->crm_request_id = $requestId;
        if ($request->has('user_select') && $request->get('user_select') != '') {
            $crmMainObj->assigned_to = $request->get('user_select');
        } else {
            $crmMainObj->assigned_to = Auth::user()->id;
        }

        $crmMainObj->user_id = Auth::user()->id;
        $crmMainObj->status = 0;
        $crmMainObj->crm_line_of_business = $request->get('request_lineof_business');
        $crmMainObj->type = $request->get('crm_type');
        $crmMainObj->priority = $request->get('crm_priority');
        $crmMainObj->created_date = date('Y-m-d h:i');
        $crmMainObj->updated_date = date('Y-m-d h:i');
        $crmMainObj->save();
        $crmMainId = $crmMainObj->getKey();

        //Save other data
        if ($request->get('crm_type') == 0) {
            $this->saveCrmTaskDetails($request, $crmMainId);
            $message = "Successfully add crm task";
        } else {
            $this->saveCrmRequestDetails($request, $crmMainId);
            $message = "Successfully add crm request";
        }
//GET SALES MANAGER DETAILS

        $users = DB::table('users')->select('email', 'name')->where('status', '1')->where('roles', 'like', "%ROLE_SALES_LEAD%")->orderBy('id')->first();

        $url = route('customeroverview', ['customerId' => $request->get('customer_select')]);
        $userObj = DB::table('users')->find($request->get('user_select'));
        $data = array('name' => $users->name, 'link' => $url, 'Request_no' => $requestId, 'username' => $userObj->name);
        $templatename = 'emails.notification';
        $maidetails['to'] = $users->email;
        $maidetails['name'] = $users->name;
        $maidetails['subject'] = "New quote request is raised CRM no: " . $requestId;
        $this->send_email($maidetails, $data, $templatename);

        return redirect()->route('crmrequestOverview', $crmMainId)->with(['success' => $message, 'overviewtabselected' => 'crm']);
    }

    /**
     * To update crm request data
     * @param Request $request
     * @param integer $customerId
     * @param integer $requestId
     * @return type
     */
    public function updatecrmRequest(Request $request, $customerId, $requestId) {
        // 
        $requestObj = App\crmMain::find($requestId);
        $createdUser = $requestObj->user_id;
        $editTime = date('Y-m-d h:i');
        $status = ($request->get('hiddenType') == 0) ? $request->get('request_status_task') : $request->get('request_status_request');
        $url = route('crmrequestOverview', ['requestId' => $requestId]);
        $requestNo = $requestObj->crm_request_id;
        $userObj = App\User::where('id', $createdUser)
                ->first();

        $updatesArray = array(
            'assigned_to' => $request->get('user_select'),
            'customer_id' => $request->get('customer_select'),
            'updated_date' => $editTime,
            'priority' => $request->get('crm_priority'),
            'crm_line_of_business' => $request->get('request_lineof_business')
        );
        $updatesArray['status'] = $status;
        if ($requestObj->status != $status) {
            $statusArray = ['open', 'under process', 'technical review', 'approved submissions', 'quote uploaded', 'revise quotation', 'request policy', 'policy uploaded', 'reject', 'completed', 'lost','pending with sales','pending with client'];

            $logarray = array("crm_id" => $requestId,
                "title" => 'Request status was changed to ' . $statusArray[$status],
                "edited_by" => Auth::user()->id,
                "old_value" => $statusArray[$requestObj->status],
                "edited_at" => $editTime);
            $customerObj = new customer();

            $customerObj->logInsert('crm_log', $logarray);
        }



        if ($status == 8) {
            $updatesArray['reject_reason'] = $request->get('reject_reason');
            //SEND MAIL TO SALES LEAD

            $users = DB::table('users')->select('email', 'name')->where('status', '1')->where('roles', 'like', "%ROLE_SALES_LEAD%")->orderBy('id')->first();
            $data = array('name' => $userObj->name, 'link' => $url, 'Request_no' => $requestNo, 'username' => $userObj->name, 'status' => $statusArray[$status]);
            $templatename = 'emails.statuschangenotification';
            $maidetails['to'] = $users->email;
            $maidetails['name'] = $users->name;
            $maidetails['subject'] = "CRM Request no: " . $requestNo . " status was changed";
            $this->send_email($maidetails, $data, $templatename);
        } else if ($status == 9 || $status == 10) {
            $updatesArray['comments'] = $request->get('close_comment');
        } else if ($status == 5) {
            $updatesArray['revise_reason'] = $request->get('revise_comment');
        } else if ($status == 2) {
            $updatesArray['technical_reporting_date'] = date('Y-m-d', strtotime($editTime));
            //SEND MAIL TO TECHNICAL LEAD
            $users = DB::table('users')->select('email', 'name')->where('status', '1')->where('roles', 'like', "%ROLE_TECHNICAL_LEAD%")->orderBy('id')->first();

            $data = array('name' => $userObj->name, 'link' => $url, 'Request_no' => $requestNo, 'username' => $users->name);
            $templatename = 'emails.assignnotification';
            $maidetails['to'] = $users->email;
            $maidetails['name'] = $users->name;
            $maidetails['subject'] = "CRM Request no: " . $requestNo . " was assigned";
            $this->send_email($maidetails, $data, $templatename);
        }

        DB::table('crm_main_table')->where('id', $requestId)
                ->update($updatesArray);

        $logarray = array("crm_id" => $requestId,
            "title" => 'Request data was edited',
            "edited_by" => Auth::user()->id,
            "edited_at" => $editTime);
        $customerObj = new customer();
        $customerObj->logInsert('crm_log', $logarray);

        if ($request->get('hiddenType') == 0) {
            $this->updateTaskDetails($request, $customerId, $requestId);
            $title = "Successfully update task details!";
        } else {
            $this->updateRequestDetails($request, $customerId, $requestId);
            $title = "Successfully update request details!";
        }


        return back()->with(['success' => $title, 'overviewtabselected' => 'crm']);
    }

    /**
     * To get quote request edit form
     * @param integer $customerId
     * @param integer $requestId
     * @return type
     */
    public function getquoterequestEditForm($customerId, $requestId) {
        $quoteRequestObj = new Dpibquoterequest();
        $quoteRequestDetails = $quoteRequestObj->quoteRequestDetail($requestId);

        $users = DB::table('users')->distinct()->where('status', '1')->orderBy('name')->pluck('name', 'id')->toArray();
        $customerDetails = DB::table('customers')->distinct()->orderBy('id')->pluck('name', 'id')->toArray();
        $lineofbusiness = DB::table('line_of_business')->distinct()->where('status', '1')->orderBy('title')->pluck('title', 'id')->toArray();
        $data = array('customerId' => $customerId, 'customerDetails' => $customerDetails, 'userDetails' => $users, 'requestDetails' => $quoteRequestDetails, 'requestId' => $requestId, 'lineofbusiness' => $lineofbusiness);

        $returnHTML = view('quote/requestaddform', $data)->render();
        return response()->json(array('status' => true, 'content' => $returnHTML));
    }

    /**
     * To get crm request overview page 
     * @param integer $requestId
     * @return type
     */
    public function requestOverView($requestId) {
        $quoteRequestObj = new Dpibquoterequest();
        $quoteRequestDetail = $quoteRequestObj->quoteRequestDetail($requestId);
        $requestLogDetails = $quoteRequestObj->quoteRequestLogDetail($requestId);
        $data = array('requestDetail' => $quoteRequestDetail, 'logDetails' => $requestLogDetails);
        $returnHTML = view('quote/requestoverview', $data)->render();
        return response()->json(array('status' => true, 'content' => $returnHTML));
    }

    /**
     * To send email
     * @param string $to
     * @param string $data
     */
    private function send_email($maidetails, $data, $template = null) {
        //New quote request is raised

        Mail::send($template, $data, function($message) use($maidetails) {
            $message->to($maidetails['to'], $maidetails['name'])->subject
                    ($maidetails['subject']);

            $message->from('info@dbroker.com.sa', 'Diamond Broker');
        });
    }

    /**
     * To save crm details
     * @param object $request
     * @param integer $crmMainId
     */
    private function saveCrmTaskDetails($request, $crmMainId) {

        $crmTaskObj = new crmTask();
        $crmTaskObj->crm_main_id = $crmMainId;
        $crmTaskObj->reminder = $request->get('prop_reminder', 0);
        $crmTaskObj->subject = $request->get('request_subject');
        $crmTaskObj->attendees = $request->get('request_attendees');

        $crmTaskObj->location = $request->get('request_location');
        $crmTaskObj->requsted_date = date('Y-m-d h:i');
        $crmTaskObj->repeat_flag = $request->get('prop_repeat_flag');
        if ($request->get('prop_repeat_flag') == 1 && $request->get('prop_repeat_date') != null) {
            $crmTaskObj->repeat_date = date("Y-m-d", strtotime($request->get('prop_repeat_date')));
        }

        $crmTaskObj->save();
    }

    /**
     * To save crm request details
     * @param Object $request
     * @param integer $crmMainId
     */
    private function saveCrmRequestDetails($request, $crmMainId) {

        $crmRequestObj = new crmRequest();
        $crmRequestObj->crm_id = $crmMainId;
        $crmRequestObj->description = $request->get('request_description');
        $crmRequestObj->save();
    }

    /**
     * To update task detals
     * @param object $request
     * @param integer $customerId
     * @param integer $requestId
     */
    private function updateTaskDetails($request, $customerId, $requestId) {
        $updatesArray = array(
            'reminder' => $request->get('user_select'),
            'subject' => $request->get('request_subject'),
            'attendees' => $request->get('request_attendees'),
            'location' => $request->get('request_location'),
            'repeat_flag' => $request->get('prop_repeat_flag'),
            'reminder' => $request->get('prop_reminder'),
            'updated_date' => date('Y-m-d h:i')
        );
        if ($request->get('prop_repeat_flag') == 1) {
            $updatesArray['repeat_date'] = ($request->get('prop_repeat_date') != null) ? date("Y-m-d", strtotime($request->get('prop_repeat_date'))) : null;
        } else {
            $updatesArray['repeat_date'] = null;
        }

        DB::table('crm_task_table')->where('crm_main_id', $requestId)
                ->update($updatesArray);
    }

    /**
     * To update request details
     * @param object $request
     * @param integer $customerId
     * @param integer $requestId
     */
    private function updateRequestDetails($request, $customerId, $requestId) {
        $updatesArray = array(
            'description' => $request->get('request_description')
        );
        DB::table('crm_request_table')->where('crm_id', $requestId)
                ->update($updatesArray);
    }

    /**
     * To get crm overview details
     * @param integer $requestId
     * @return type
     */
    public function crmOverviewDetails($requestId) {
        $crmRequestObj = new Dpibquoterequest();
        $crmDetails = $crmRequestObj->quoteRequestDetail($requestId);
        $documentcount = count(DB::table('customer_crm_documents')->where('crm_id', '=', $requestId)->where('document_type', '<>', 8)->get());
        $insuranceCompany = DB::table('insurer_details')->distinct()->where('status', '1')->orderBy('id')->pluck('insurer_name', 'id')->toArray();
        $quotes = DB::table('quote')
                ->join('insurer_details', 'insurer_details.id', '=', 'quote.company_id')
                ->select('quote.*', 'insurer_details.insurer_name', 'quote.id as mainId')
                ->where('quote.crm_id', '=', $requestId);
        $brokingslipCount = DB::table('broking_slip_info')->where('crm_id', '=', $requestId)->count();

        $commentsDetails = DB::table('crm_request_comments as c')
                        ->join('users as u', 'u.id', '=', 'c.created_by')
                        ->select('c.*', 'u.name as createdBy')
                        ->where('c.request_id', '=', $requestId)
                        ->orderBy('c.created_at', 'desc')->get();




        if (in_array('ROLE_TECHNICAL_MANAGER', Auth::user()->roles) || in_array('ROLE_TECHNICAL', Auth::user()->roles)) {
            $quoteData = $quotes->orderBy('quote.created_at', 'desc')->get();
        } else {
            $quoteData = $quotes->where('quote.display_flag', 1)->orderBy('quote.created_at', 'desc')->get();
        }

        $users = DB::table('users')->distinct()->where('status', '1')->where('roles', 'like', "%ROLE_TECHNICAL%")->orderBy('name')->pluck('name', 'id')->toArray();
        // Comparison document details
        $comparisondocDetails = DB::table('customer_crm_documents as cd')
                        ->leftJoin('users as u', 'cd.uploaded_by', '=', 'u.id')
                        ->select('cd.*', 'u.name as uploadedBy', 'cd.id as mainId')
                        ->where('cd.crm_id', '=', $requestId)->where('cd.document_type', '=', 8)->orderBy('cd.uploaded_at', 'desc')->get();



        if (Session::get('requestoverviewtabselected') != '') {
            $overviewTab = Session::get('requestoverviewtabselected');
        } else {
            $overviewTab = 'overview';
            Session::put('requestoverviewtabselected', 'overview');
        }
        
        $breadcrumbDetails = array('url' => action('Dashboard\DashboardController@salesCrmList'), 'title' => 'Requests');
        $data = array('crmDetails' => $crmDetails, 'docCount' => $documentcount, 'quotes' => $quoteData, 'comparisonfiles' => $comparisondocDetails, 'brokingslipCount' => $brokingslipCount, 'overviewTab' => $overviewTab, 'breadcrumb' => $breadcrumbDetails, 'commentDetails' => $commentsDetails, 'insuranceCompany' => $insuranceCompany, 'assignUsers' => $users);
        return view('Request/requestoverview', $data);
    }

    /**
     * To get crm log details
     * @param integer $requestId
     * @return type
     */
    public function crmLogDetails($requestId) {
        $crmmainObj = new crmMain();
        $logData = $crmmainObj->crmRequestLogDetail($requestId);
        $data = array('logdata' => $logData);
        $returnHTML = view('Request/logData', $data)->render();
        Session::put('customercrmoverviewtabselected', 'log');

        return response()->json(array('status' => true, 'content' => $returnHTML));
    }

    /**
     * To get rendered document data html
     * @param  integer $customerId
     * @return json
     */
    public function customerCrmDocumentDetails($customerId, $crmId) {
        $customerDocObj = new Customercrmdocuments();
        $documentDetails = $customerDocObj->documentData($crmId);
        $data = array('documentData' => $documentDetails, 'crmId' => $crmId, 'customerId' => $customerId);
        $returnHTML = view('Request/crmDocumentData', $data)->render();
        return response()->json(array('status' => true, 'content' => $returnHTML));
    }

    /**
     * To get crm document form
     * @param integer $customerId
     * @param integer $crmId
     * @return type
     */
    public function customerCrmDocumentForm($customerId, $crmId) {
        $documentType = DB::table('dp_document_type')->distinct()->orderBy('id')->pluck('title', 'id')->toArray();
        $data = array('customerId' => $customerId, 'documentType' => $documentType, 'crmId' => $crmId);
        $returnHTML = view('Request/addDocument', $data)->render();
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    /**
     * To save the document detail and log data
     * @param Request $request
     * @param integer $customerId
     * @return type
     */
    public function customerCrmDocumentSave(Request $request, $customerId, $crmId) {
        $files = $request->file('document_file');
        $insertArray = [];
        $type = $request->get('documenttype_oid');
        $comment = $request->get('document_comment');
        $filename = [];
        $datetime = date('Y-m-d h:i');
        foreach ($files as $uploadedfile) {
            $destinationPath = 'uploads/' . $customerId . "/document/";
            $path_parts = pathinfo($uploadedfile->getClientOriginalName());
            $newfilename = $path_parts['filename'] . "_" . date('Ymdhis') . '.' . $path_parts['extension'];
            $filename[] = $newfilename;
            $uploadedfile->move($destinationPath, $newfilename);
            $insertArray[] = array("customer_id" => $customerId,
                "filename" => $newfilename,
                "document_type" => $type,
                "comment" => $comment,
                "uploaded_by" => Auth::user()->id,
                "uploaded_at" => $datetime,
                "crm_id" => $crmId
            );
        }
        $customerObj = new customer();
        if (count($insertArray) > 0) {
            DB::table('customer_crm_documents')->insert($insertArray); // Query Builder approach
            //insert log entry
            $logarray = array("crm_id" => $crmId,
                "title" => "Following documents are uploaded: " . implode(',', $filename),
                "old_value" => '',
                "edited_by" => Auth::user()->id,
                "edited_at" => $datetime);

            $customerObj->logInsert('crm_log', $logarray);

            if ($type == 7) {
                //update crm status

                $crmObj = App\crmMain::where('id', $crmId)
                                ->where('customer_id', $customerId)->first();
                $oldStatus = $crmObj->status;
                $crmObj->status = 4;
                $crmObj->updated_date = $datetime;
                $crmObj->save();

                //INSERT QUOTE DETAILS UNDER QUOTE TABLE
                // Log insertion
                $statusArray = ['open', 'under process', 'technical review', 'approved submissions', 'quote uploaded', 'revise quotation', 'request policy', 'policy uploaded', 'reject', 'completed', 'lost'];
                $logarray = array("crm_id" => $crmId,
                    "title" => "Status was changed to: " . $statusArray[4],
                    "old_value" => $statusArray[$oldStatus],
                    "edited_by" => Auth::user()->id,
                    "edited_at" => $datetime);

                $customerObj->logInsert('crm_log', $logarray);
            }
            Session::put('requestoverviewtabselected', 'document');
        }
        return back()->with(['success' => 'Successfully upload documents!', 'overviewtabselected' => 'document']);
    }

    /**
     * To delete document data 
     * @param Request $request
     * @param integer $customerId
     * @return json
     */
    public function customerCrmDocumentDelete(Request $request, $customerId, $crmId) {
        $whereArray[] = ['id', '=', $request->get('docId')];
        $whereArray[] = ['customer_id', '=', $customerId];
        $documentDetails = DB::table('customer_crm_documents')->where($whereArray)->pluck('filename')->toArray();
        DB::table('customer_crm_documents')->where($whereArray)->delete();
        $destinationPath = 'uploads/' . $customerId . "/document/" . $documentDetails[0];
        unlink($destinationPath);

        //insert log entry
        $logarray = array("crm_id" => $crmId,
            "title" => "Document '" . $documentDetails[0] . "' was deleted",
            "old_value" => '',
            "edited_by" => Auth::user()->id,
            "edited_at" => date('Y-m-d h:i'));
        $customerObj = new customer();
        $customerObj->logInsert('crm_log', $logarray);
        Session::put('requestoverviewtabselected', 'document');

        Session::flash('success', 'Successfully deleted document');
        return response()->json(array('success' => true));
    }

    /**
     * To get the document edit form
     * @param  integer $customerId
     * @param  integer $documentId
     * @return json
     */
    public function customercrmdocumentEdit(Request $request, $customerId, $crmId) {
        $whereArray[] = ['id', '=', $request->get('docId')];
        $whereArray[] = ['customer_id', '=', $customerId];
        $documentType = DB::table('dp_document_type')->distinct()->orderBy('id')->pluck('title', 'id')->toArray();
        $documentDetails = DB::table('customer_crm_documents')->where($whereArray)->get()->first();
        $data = array('documentdata' => $documentDetails, 'customerId' => $customerId, 'documentId' => $request->get('docId'), 'documentType' => $documentType, 'crmId' => $crmId);
        $returnHTML = view('Request/editDocument', $data)->render();
        return response()->json(array('status' => true, 'content' => $returnHTML));
    }

    /**
     * To update document data
     * @param  Request $request
     * @param  integer $customerId
     * @param  integer $documentId
     * @return json
     */
    public function customercrmdocumentDataEdit(Request $request, $customerId, $crmId) {
        $type = $request->get('documenttype_oid');
        $comment = $request->get('document_comment');
        $documentId = $request->get('documentId');
        $datetime = date('Y-m-d h:i');
        $documentObj = App\Customercrmdocuments::where('id', $documentId)
                        ->where('customer_id', $customerId)->first();

        $logarray = [];
        if ($documentObj->document_type != $type && $type != '') {
            //document type changed 
            $documentTypeObj = App\Documenttype::where('id', $type)->first();
            $documentOldTypeObj = ($documentObj->document_type != '') ? App\Documenttype::where('id', $documentObj->document_type)->first() : '';
            $dataArray = [$crmId, "'" . $documentObj->filename . "' document's type was changed to " . $documentTypeObj->title, (($documentOldTypeObj != '') ? $documentOldTypeObj->title : ''), $datetime];
            $logarray[] = $this->logarrayCreation($dataArray);
        }
        if ($documentObj->comment != $comment && $comment != '') {
            $dataArray = [$crmId, "'" . $documentObj->filename . "' document's comment was changed to " . $comment, $documentObj->comment, $datetime];
            $logarray[] = $this->logarrayCreation($dataArray);
        }

        $documentObj->document_type = $request->get('documenttype_oid');
        $documentObj->comment = $request->get('document_comment');
        $documentObj->save();

        //insert log entry
        if (count($logarray) > 0) {
            $customerObj = new customer();
            $customerObj->logInsert('crm_log', $logarray);
        }
        Session::put('requestoverviewtabselected', 'document');
        Session::flash('success', 'Successfully update document data');
        return response()->json(array('status' => true));
    }

    /**
     * To create log insert array
     * @param array $dataArray
     * @return type
     */
    private function logarrayCreation($dataArray) {

        $logarray = array("crm_id" => $dataArray[0],
            "title" => $dataArray[1],
            "old_value" => $dataArray[2],
            "edited_by" => Auth::user()->id,
            "edited_at" => $dataArray[3]);

        return $logarray;
    }

    /**
     * Create edit status form
     * @param Integer $customerId
     * @param Integer $crmId
     * @param Integer $type
     * @param String $status
     * @return type
     */
    public function customerCrmStatusChangeForm($customerId, $crmId, $type, $status) {
        if ($type == 0) {
            $statusArray = array('0' => 'New', '9' => 'Completed');
        } else {
            $statusArray = array('0' => 'New', '2' => 'Technical review', '3' => 'Approved submissions', '4' => 'Quote uploaded', '5' => 'Revise quotation', '6' => 'Request policy', '7' => 'Policy uploaded', '8' => 'Reject', '9' => 'Completed','10'=>'Lost','11'=>'Pending with sales','12'=>'Pending with client');
        }

        $data = array('customerId' => $customerId, 'crmId' => $crmId, 'status' => $status, 'statusArray' => $statusArray);
        $returnHTML = view('Request/editStatusForm', $data)->render();
        return response()->json(array('status' => true, 'content' => $returnHTML));
    }

    /**
     * 
     * @param Request $request
     * @param type $customerId
     * @param type $crmId
     * @return type
     */
    public function customerCrmStatusUpdate(Request $request, $customerId, $crmId) {
        $crmObj = App\crmMain::where('id', $crmId)
                        ->where('customer_id', $customerId)->first();
        $oldStatus = $crmObj->status;
        $requestNo = $crmObj->crm_request_id;
        $editTime = date('Y-m-d h:i');
        $createdUser = $crmObj->user_id;
        $crmObj->status = $request->get('crm_status');
        $crmObj->updated_date = $editTime;
        $userObj = App\User::where('id', $createdUser)
                ->first();
        $url = route('crmrequestOverview', ['requestId' => $crmId]);
        $status = $request->get('crm_status');
        if ($request->get('crm_status') == '7') {
            $statusArray = ['open', 'under process', 'technical review', 'approved submissions', 'quote uploaded', 'revise quotation', 'request policy', 'policy uploaded', 'reject', 'completed', 'lost','pending with sales','pending with client'];
            $data = array('customerId' => 1, 'crmId' => 1, 'status' => 1, 'statusArray' => $statusArray);
            $users = DB::table('users')->select('email', 'name')->where('status', '1')->where('roles', 'like', "%ROLE_SALES_LEAD%")->orderBy('id')->first();
            $data = array('name' => $userObj->name, 'link' => $url, 'Request_no' => $requestNo, 'username' => $userObj->name, 'status' => $statusArray[$status]);
            $templatename = 'emails.statuschangenotification';
            $maidetails['to'] = $users->email;
            $maidetails['name'] = $users->name;
            $maidetails['subject'] = "CRM Request no: " . $requestNo . " status was changed";
            $this->send_email($maidetails, $data, $templatename);
            //$returnHTML = view('Policy/addPolicyForm', $data)->render();
            //return response()->json(array('status' => true, 'content' => $returnHTML));
        } else if ($request->get('crm_status') == '7') {
            
        }

        if ($request->get('crm_status') == '5') {
            $crmObj->revise_reason = $request->get('crm_comment');
        } else if ($request->get('crm_status') == '8') {
            $crmObj->reject_reason = $request->get('crm_comment');
            //SEND MAIL TO SALES LEAD
            $statusArray = ['open', 'under process', 'technical review', 'approved submissions', 'quote uploaded', 'revise quotation', 'request policy', 'policy uploaded', 'reject', 'completed', 'lost','pending with sales','pending with client'];
            $users = DB::table('users')->select('email', 'name')->where('status', '1')->where('roles', 'like', "%ROLE_SALES_LEAD%")->orderBy('id')->first();
            $data = array('name' => $userObj->name, 'link' => $url, 'Request_no' => $requestNo, 'username' => $userObj->name, 'status' => $statusArray[$status]);
            $templatename = 'emails.statuschangenotification';
            $maidetails['to'] = $users->email;
            $maidetails['name'] = $users->name;
            $maidetails['subject'] = "CRM Request no: " . $requestNo . " status was changed";
            $this->send_email($maidetails, $data, $templatename);
            
            
        } else if ($request->get('crm_status') == '10') {
            $crmObj->comments = $request->get('crm_comment');
        } else if ($request->get('crm_status') == 2) {
            $crmObj->technical_reporting_date = date('Y-m-d', strtotime($editTime));
            $statusArray = ['open', 'under process', 'technical review', 'approved submissions', 'quote uploaded', 'revise quotation', 'request policy', 'policy uploaded', 'reject', 'completed', 'lost','pending with sales','pending with client'];
            //SEND MAIL TO TECHNICAL LEAD
            $users = DB::table('users')->select('email', 'name')->where('status', '1')->where('roles', 'like', "%ROLE_TECHNICAL_LEAD%")->orderBy('id')->first();

            $data = array('name' => $userObj->name, 'link' => $url, 'Request_no' => $requestNo, 'username' => $users->name);
            $templatename = 'emails.assignnotification';
            $maidetails['to'] = $users->email;
            $maidetails['name'] = $users->name;
            $maidetails['subject'] = "CRM Request no: " . $requestNo . " was assigned";
            $this->send_email($maidetails, $data, $templatename);
            
        }

        $crmObj->save();
       
        $statusArray = ['open', 'under process', 'technical review', 'approved submissions', 'quote uploaded', 'revise quotation', 'request policy', 'policy uploaded', 'reject', 'completed', 'lost','pending with sales','pending with client'];
        $logarray = array("crm_id" => $crmId,
            "title" => 'Request status was changed to ' . $statusArray[$status],
            "edited_by" => Auth::user()->id,
            "old_value" => $statusArray[$oldStatus],
            "edited_at" => $editTime);
        $customerObj = new customer();

        $customerObj->logInsert('crm_log', $logarray);

        //SEND NOTIFICATION MAIL TO CREATED USER
        $data = array('name' => $userObj->name, 'link' => $url, 'Request_no' => $requestNo, 'username' => $userObj->name, 'status' => $statusArray[$status]);
        $templatename = 'emails.statuschangenotification';
        $maidetails['to'] = $userObj->email;
        $maidetails['name'] = $userObj->name;
        $maidetails['subject'] = "CRM Request no: " . $requestNo . " status was changed";
        $this->send_email($maidetails, $data, $templatename);


        return back()->with(['success' => 'Successfully updated request status!!', 'overviewtabselected' => 'document']);
    }

    /**
     * Broking slip generation main form
     * @param integer $customerId
     * @param integer $crmId
     * @return type
     */
    public function brokenslipMainForm($customerId, $crmId) {
        //insurance company 
        $insuranceCompany = DB::table('insurer_details')->distinct()->where('status', '1')->orderBy('id')->pluck('insurer_name', 'id')->toArray();
        //type
        $insuranceProduct = DB::table('insurance_product')->distinct()->where('status', '1')->orderBy('id')->pluck('product_name', 'id')->toArray();

        $data = array('customerId' => $customerId, 'crmId' => $crmId, 'insuranceCompany' => $insuranceCompany, 'insuranceProduct' => $insuranceProduct);
        $returnHTML = view('Brokenslip/mainpage', $data)->render();
        return response()->json(array('status' => true, 'content' => $returnHTML));
    }

    /**
     * Generate broking slip category form according to the product
     * @param Request $request
     * @param integer $customerId
     * @param integer $crmId
     * @return type
     */
    public function brokenslipSubfields(Request $request, $customerId, $crmId) {

        $product = $request->get('product');

        if ($product == 2) {
            $returnHTML = view('Brokenslip/cglform')->render();
        } else if ($product == 8) {
            $returnHTML = view('Brokenslip/fgform')->render();
        } else if ($product == 11) {
            $returnHTML = view('Brokenslip/medicalform')->render();
        } else if ($product == 24) {
            $returnHTML = view('Brokenslip/mocform')->render();
        } else if ($product == 26) {
            $returnHTML = view('Brokenslip/motortplform')->render();
        } else if ($product == 9) {
            $returnHTML = view('Brokenslip/landintransitform')->render();
        } else if ($product == 25) {
            $returnHTML = view('Brokenslip/motorcompform')->render();
        } else if ($product == 6) {
            $returnHTML = view('Brokenslip/finalform')->render();
        } else if ($product == 35) {
            $returnHTML = view('Brokenslip/parform')->render();
        } else if ($product == 10) {
            $returnHTML = view('Brokenslip/grouplifeform')->render();
        } else if ($product == 22) {
            $returnHTML = view('Brokenslip/medicalform')->render();
        } else {
            $returnHTML = '';
        }


        return response()->json(array('status' => true, 'content' => $returnHTML));
    }

    /**
     * Generate broking slip pdf according to the pdf type
     * @param Request $request
     * @param integer $customerId
     * @param integer $crmId
     * @return type
     */
    public function generateBrokingSlip(Request $request, $customerId, $crmId) {
        $product = $request->get('insurance_product');
        $data = [];
        $startName = "MOC";
        $template = 'Pdfslip/moctpl';
        if ($product == 24) {
            $startName = 'MOC';
            //$formData = $this->generateMocData($request);
            $formData = $this->generateTplData($request);
            $data = ['formdata' => $formData];
            $template = 'Pdfslip/moctpl';
        } else if ($product == 2) {
            $startName = 'CGL';
            //$formData = $this->generateCglData($request);
            $formData = $this->generateTplData($request);
            $data = ['formdata' => $formData];
            $template = 'Pdfslip/cgltpl';
        } else if ($product == 9) {
            $startName = 'LANDINTRANSIT';
            //$formData = $this->generateMocData($request);
            $formData = $this->generateTplData($request);
            $data = ['formdata' => $formData];
            $template = 'Pdfslip/landintransittpl';
        } else if ($product == 8) {
            $startName = 'FG';
            //$formData = $this->generateFgData($request);
            $formData = $this->generateTplData($request);
            $data = ['formdata' => $formData];
            $template = 'Pdfslip/fgtpl';
        } else if ($product == 25) {
            $startName = 'MOTORFLEET';
            //$formData = $this->generateFgData($request);
            $formData = $this->generateTplData($request);
            $data = ['formdata' => $formData];
            $template = 'Pdfslip/motorcomptpl';
        } else if ($product == 6) {
            $startName = 'FINAL';
            //$formData = $this->generateFgData($request);
            $formData = $this->generateTplData($request);
            $data = ['formdata' => $formData];
            $template = 'Pdfslip/finaltpl';
        } else if ($product == 35) {
            $startName = 'PAR';
            //$formData = $this->generateFgData($request);
            $formData = $this->generateTplData($request);
            $data = ['formdata' => $formData];
            $template = 'Pdfslip/partpl';
        } else if ($product == 10) {
            $startName = 'GROUPLIFE';
            //$formData = $this->generateFgData($request);
            $formData = $this->generateTplData($request);
            $data = ['formdata' => $formData];
            $template = 'Pdfslip/grouplifetpl';
        } else if ($product == 26) {
            $startName = 'MOTORTPL';
            //$formData = $this->generateFgData($request);
            $formData = $this->generateTplData($request);
            $data = ['formdata' => $formData];
            $template = 'Pdfslip/motortpl';
        } else if ($product == 22 || $product == 11) {
            $startName = 'MEDICAL';
            //$formData = $this->generateFgData($request);
            $formData = $this->generateTplData($request);
            $data = ['formdata' => $formData];
            $template = 'Pdfslip/medicaltpl';
        }
        File::isDirectory('uploads/brokingslip/' . $customerId . '/' . $crmId) or File::makeDirectory('uploads/brokingslip/' . $customerId . '/' . $crmId, 0777, true, true);
        $uploadDate = date('Y-m-d H:i:s');
        $brokingslipData = [];
        $logArray = [];

        foreach ($request->get('insurance_company') as $company) {
            $filename = $startName . "_" . $company . '_' . date('Ymdhis') . '.pdf';


            PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView($template, $data)->setPaper('a4', 'portrait')->save('uploads/brokingslip/' . $customerId . '/' . $crmId . '/' . $filename);
            // Save broking slip file to document

            $brokingslipData[] = array('crm_id' => $crmId,
                'file_name' => $filename,
                'created_date' => $uploadDate,
                'updated_date' => $uploadDate,
                'customer_id' => $customerId,
                'company_id' => $company,
                'status' => 0,
                'product_id' => $product,
                'form_data' => json_encode($formData),
                'uploaded_by' => Auth::user()->id
            );

            $logArray[] = array("crm_id" => $crmId,
                "title" => 'Broken slip was generated . File: ' . $filename,
                "edited_by" => Auth::user()->id,
                "old_value" => '',
                "edited_at" => $uploadDate);
        }
        //insert log entry
        if (count($brokingslipData) > 0) {
            DB::table('broking_slip_info')->insert($brokingslipData);
            $customerObj = new customer();
            $customerObj->logInsert('crm_log', $logArray);
        }

        return back()->with(['success' => 'Successfully generated broking slip!', 'overviewtabselected' => 'document']);
    }

    /**
     * Generate array for save data
     * @param object $formData
     * @return type
     */
    private function generateTplData($formData) {
        $pdfArray = [];
        $pdfArray['insurer'] = $formData->get('insurer');
        $pdfArray['insured'] = $formData->get('insurer');
        $pdfArray['business_activities'] = $formData->get('business_activities');
        $pdfArray['period_of_insurance'] = $formData->get('period_date');
        $pdfArray['coverage'] = $formData->get('coverage');
        $pdfArray['interest'] = $formData->get('interest');
        $pdfArray['voyage'] = $formData->get('voyage');
        $pdfArray['conveyance'] = $formData->get('conveyance');
        $pdfArray['single_carrying_limit'] = $formData->get('single_carrying_limit');
        $pdfArray['estimated_annual_turnover'] = $formData->get('estimated_annual_turnover');
        $pdfArray['basis_of_valuation'] = $formData->get('basis_of_valuation');
        $pdfArray['target_rate'] = $formData->get('target_rate');
        $pdfArray['deductible'] = $formData->get('deductible');
        $pdfArray['brokerage_commission'] = $formData->get('commission');
        $pdfArray['declaration_basis'] = $formData->get('declaration_basis', '');
        $pdfArray['payment_basis'] = $formData->get('payment_basis', '');
        $pdfArray['conditions'] = $formData->get('condition');

        //Second part

        $pdfArray['type'] = $formData->get('MOC');

        $pdfArray['occupancy'] = $formData->get('occupancy');
        $pdfArray['territorial_limit'] = $formData->get('territorial_limit');
        $pdfArray['applicable_law'] = $formData->get('applicable_law');
        $pdfArray['liability_limit'] = $formData->get('liability_limit');

        // Third part

        $pdfArray['forms'] = $formData->get('forms');
        $pdfArray['max_limit'] = $formData->get('max_limit');
        $pdfArray['deductable'] = $formData->get('deductable');
        $pdfArray['rate'] = $formData->get('rate', '');
        $pdfArray['deprication'] = $formData->get('deprication');
        $pdfArray['form_tsi'] = $formData->get('form_tsi');
        $pdfArray['sum_insured'] = $formData->get('sum_insured');
        // fourth

        $pdfArray['benefits'] = $formData->get('benefits');
        $pdfArray['minimum_age'] = $formData->get('minimum_age');
        $pdfArray['maximum_age'] = $formData->get('maximum_age');
        $pdfArray['total_members'] = $formData->get('total_members');
        $pdfArray['free_cover_limit'] = $formData->get('free_cover_limit');
        $pdfArray['total_sum_insured'] = $formData->get('total_sum_insured');
        $pdfArray['target_anual_rate'] = $formData->get('target_anual_rate');
        $pdfArray['imagepath'] = '';
        if ($formData->hasFile('company_logo')) {
            $files = $formData->file('company_logo');
            $destinationPath = 'uploads/logos/';
            $path_parts = pathinfo($files->getClientOriginalName());
            $newfilename = $path_parts['filename'] . "_" . date('Ymdhis') . '.' . $path_parts['extension'];
            File::isDirectory('uploads/logos') or File::makeDirectory('uploads/logos', 0777, true, true);
            $files->move($destinationPath, $newfilename);
            $pdfArray['imagepath'] = "/" . $destinationPath . $newfilename;
        }

        $pdfArray['product_details'] = $formData->get('product_details');
        $pdfArray['brokerage_info'] = $formData->get('brokerage_info');




        return $pdfArray;
    }

    private function generateCglData($formData) {
        $pdfArray = [];
        $pdfArray['insurer'] = $formData->get('insurer');
        $pdfArray['type'] = $formData->get('MOC');
        $pdfArray['insured'] = $formData->get('insurer');
        $pdfArray['business_activities'] = $formData->get('business_activities');
        $pdfArray['period_of_insurance'] = $formData->get('period_date');
        $pdfArray['coverage'] = $formData->get('coverage');
        $pdfArray['occupancy'] = $formData->get('occupancy');
        $pdfArray['territorial_limit'] = $formData->get('territorial_limit');
        $pdfArray['applicable_law'] = $formData->get('applicable_law');
        $pdfArray['liability_limit'] = $formData->get('liability_limit');
        $pdfArray['deductible'] = $formData->get('estimated_annual_turnover');
        $pdfArray['brokerage_commission'] = $formData->get('commission');
        $pdfArray['conditions'] = $formData->get('condition');
        return $pdfArray;
    }

    private function generateFgData($formData) {
        $pdfArray = [];
        $pdfArray['insurer'] = $formData->get('insurer');
        $pdfArray['forms'] = $formData->get('forms');
        $pdfArray['business_activities'] = $formData->get('business_activities');
        $pdfArray['period_of_insurance'] = $formData->get('period_date');
        $pdfArray['coverage'] = $formData->get('coverage');
        $pdfArray['interest'] = $formData->get('interest');
        $pdfArray['territorial_limit'] = $formData->get('territorial_limit');
        $pdfArray['applicable_law'] = $formData->get('applicable_law');
        $pdfArray['max_limit'] = $formData->get('max_limit');
        $pdfArray['deductible'] = $formData->get('deductable');
        $pdfArray['brokerage_commission'] = $formData->get('commission');
        $pdfArray['rate'] = $formData->get('rate', '');
        $pdfArray['conditions'] = $formData->get('condition');
        $pdfArray['deprication'] = $formData->get('deprication');
        $pdfArray['form_tsi'] = $formData->get('form_tsi');
        return $pdfArray;
    }

    /**
     * To view broking slip pdf data
     * @param integer $customerId
     * @param integer $crmId
     * @param string $filename
     * @return type
     */
    public function viewFiles($customerId, $type = 'document', $crmId = 0, $filename) {

        $file = '';
        $headers = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"'
        ];

        switch ($type) {
            case 'brokingslip':
                $file = public_path() . "/uploads/brokingslip/" . $customerId . "/" . $crmId . "/" . $filename;
                break;
            case 'quote':
                $file = public_path() . "/uploads/" . $customerId . "/Quotes/" . $filename;
                break;
            case 'comparison':
                $file = public_path() . "/uploads/" . $customerId . "/document/" . $filename;
                $headers['Content-Type'] = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
                break;
            default:
                $file = public_path() . "/uploads/" . $customerId . "/document/" . $crmId . "/" . $filename;
                break;
        }

        return response()->file($file, $headers);
    }

    /**
     * To delete broking slip pdf
     * @param integer $customerId
     * @param integer $crmId
     * @param string $filename
     * @param integer $docId
     * @return type
     */
    public function deleteBrokingSlip($customerId, $crmId, $filename, $docId) {
        $file = "uploads/brokingslip/" . $customerId . "/" . $crmId . "/" . $filename;
        unlink($file);
        $whereArray[] = ['id', '=', $docId];
        $whereArray[] = ['customer_id', '=', $customerId];
        DB::table('broking_slip_info')->where($whereArray)->delete();
        $logArray = array("crm_id" => $crmId,
            "title" => 'Broken slip was deleted ',
            "edited_by" => Auth::user()->id,
            "old_value" => '',
            "edited_at" => date('Y-m-d h:i'));
        DB::table('crm_log')->insert($logArray);
        Session::flash('success', 'Successfully delete broking slip!');

        return response()->json(array('status' => true));
    }

    /**
     * To create send mail form 
     * @param integer $customerId
     * @param integer $crmId
     * @param integer $docId
     * @return type
     */
    public function senBrokingSlipForm($customerId, $crmId, $docId) {
        $data['customerId'] = $customerId;
        $data['crmId'] = $crmId;
        $data['docId'] = $docId;
        $returnHTML = view('Brokenslip/sendmailpopup', $data)->render();
        return response()->json(array('status' => true, 'content' => $returnHTML));
    }

    /**
     * To send mail to specified mail ids
     * @param Request $request
     * @param integer $customerId
     * @param integer $crmId
     * @param integer $docId
     * @return type
     */
    public function sendMailDocument(Request $request, $customerId, $type = 'document', $crmId = 0, $id) {

        $user_data = array();
        $title = '';
        switch ($type) {
            case 'brokingslip':
                $user_data = $this->getBrokingSlipDetailsArray($request, $customerId, $id, $crmId);
                $title = "Broking slip was send. File" . $user_data['file'];
                break;
            case 'quote':
                $user_data = $this->getQuoteDetailsArray($request, $customerId, $id);
                $title = "Quote was send. File" . $user_data['file'];
                break;
            case 'comparison':
                $user_data = $this->getComparisonDetailsArray($request, $customerId, $id);
                $title = "Comparison file was send. File" . $user_data['file'];
                break;
            default:

                break;
        }
        $user_data['mimetype'] = File::mimeType($user_data['filename']);

        if (file_exists($user_data['filename'])) {

            Mail::send('emails.brokesliptemplate', ['maildata' => $user_data], function ($m) use ($user_data) {
                $m->from('info@dbroker.com.sa', 'Diamond insurance broker');
                $m->to($user_data['to_data'], $user_data['to_data'])->subject($user_data['subject']);
                if ($user_data['cc_data'] != '') {
                    $m->cc($user_data['cc_data']);
                }

                $m->attach($user_data['filename'], [
                    'as' => $user_data['viewname'],
                    'mime' => $user_data['mimetype'],
                ]);
            });
            // check for failures
            if (Mail::failures() && $type == 'brokingslip') {
                $updatesArray = array(
                    'sendmail_flag' => 2
                );
                DB::table('broking_slip_info')->where('id', $id)
                        ->update($updatesArray);
            } else if ($type == 'brokingslip') {

                $updatesArray = array(
                    'sendmail_flag' => 1
                );
                DB::table('broking_slip_info')->where('id', $id)
                        ->update($updatesArray);

                //broking slip send date saved against the request
                DB::table('crm_main_table')->where('id', $id)
                        ->update(array('broking_slip_send_date' => date('Y-m-d')));
            }

            $logArray = array("crm_id" => $crmId,
                "title" => $title,
                "edited_by" => Auth::user()->id,
                "old_value" => '',
                "edited_at" => date('Y-m-d h:i'));
            DB::table('crm_log')->insert($logArray);



            return back()->with(['success' => 'successfully send mail', 'overviewtabselected' => 'brokingslip']);
        } else {
            return back()->with(['error' => 'Mail sending is failed', 'overviewtabselected' => 'brokingslip']);
        }
    }

    /**
     * To get rendered document data html 
     * @param  integer $customerId
     * @return json
     */
    public function brokingSlipDetails($customerId, $crmId) {
        $customerDocObj = new Customercrmdocuments();
        $brokingslipDetails = $customerDocObj->brokingslipData($crmId);
        //insurance company 
        $insuranceCompany = DB::table('insurer_details')->distinct()->where('status', '1')->orderBy('id')->pluck('insurer_name', 'id')->toArray();
        //type
        $insuranceProduct = DB::table('insurance_product')->distinct()->where('status', '1')->orderBy('id')->pluck('product_name', 'id')->toArray();
        $data = array('brokingslipData' => $brokingslipDetails, 'crmId' => $crmId, 'customerId' => $customerId, 'insuranceCompany' => $insuranceCompany, 'insuranceProduct' => $insuranceProduct);
        $returnHTML = view('Brokenslip/brokingslipList', $data)->render();
        return response()->json(array('status' => true, 'content' => $returnHTML));
    }

    /**
     * To create quotes
     * @param Request $request
     * @param integer $customerId
     * @param integer $crmId
     * @return type
     */
    public function createQuotes(Request $request, $customerId, $crmId) {
        $files = $request->file('quote_file');
        
        $insertArray = [];
        $comment = $request->get('quote_comment');
        $companyId = $request->get('companyId');
        $brokingId = $request->get('brkId', 0);
        $datetime = date('Y-m-d h:i');
        File::isDirectory('uploads/' . $customerId . "/Quotes/") or File::makeDirectory('uploads/' . $customerId . "/Quotes/", 0777, true, true);
        $filename = [];
        $customerObj = new customer();
        foreach ($files as $uploadedfile) {
            $destinationPath = 'uploads/' . $customerId . "/Quotes/";
            $path_parts = pathinfo($uploadedfile->getClientOriginalName());
            $newfilename = $path_parts['filename'] . "_" . date('Ymdhis') . '.' . $path_parts['extension'];
            $filename[] = $newfilename;
            $uploadedfile->move($destinationPath, $newfilename);
            $insertArray = array("customer_id" => $customerId,
                "file_name" => $newfilename,
                "crm_id" => $crmId,
                "additional_desc" => $comment,
                "upload_by" => Auth::user()->id,
                "updated_at" => $datetime,
                "created_at" => $datetime,
                "company_id" => $companyId,
                "customer_id" => $customerId
            );
       
            $quoteId = DB::table('quote')->insertGetId($insertArray); // Query Builder approach
            //insert log entry
            $logarray = array("crm_id" => $crmId,
                "title" => "Quote is uploaded : " . implode(',', $filename),
                "old_value" => '',
                "edited_by" => Auth::user()->id,
                "edited_at" => $datetime);
            
            $customerObj->logInsert('crm_log', $logarray);
            DB::table('broking_slip_info')->where('id', $brokingId)
                    ->update(array('status' => 1, 'quotes_id' => $quoteId));
            DB::table('crm_main_table')->where('id', $crmId)
                    ->update(array('status' => 4));
        }


        return back()->with(['success' => 'successfully uploaded quote', 'overviewtabselected' => 'brokingslip']);
    }

    /**
     * To get quote upload form
     * @param integer $customerId
     * @param integer $crmId
     * @param integer $brkId
     * @return type
     */
    public function quoteUploadForm($customerId, $crmId, $brkId) {

        $brokingObj = DB::table('broking_slip_info')->where('id', $brkId)->pluck('company_id')->toArray();
        $data = array('customerId' => $customerId, 'companyId' => $brokingObj[0], 'crmId' => $crmId, 'brokingId' => $brkId);
        $returnHTML = view('Quote/addQuote', $data)->render();
        return response()->json(array('success' => true, 'content' => $returnHTML));
    }

    /**
     * To get broking slip detail array
     * @param Object $request
     * @param integer $customerId
     * @param integer $brkId
     * @param integer $crmId
     * @return string
     */
    private function getBrokingSlipDetailsArray($request, $customerId, $brkId, $crmId = 0) {
        $whereArray[] = ['id', '=', $brkId];
        $whereArray[] = ['customer_id', '=', $customerId];
        $user_data = array();
        $documentDetails = DB::table('broking_slip_info')->where($whereArray)->pluck('file_name')->toArray();
        $filename = "uploads/brokingslip/" . $customerId . "/" . $crmId . "/" . $documentDetails[0];
        $user_data['to_data'] = $request->get('to_data');
        $user_data['cc_data'] = $request->get('cc_data');
        $user_data['subject'] = $request->get('subject');
        $user_data['message'] = $request->get('message');
        $user_data['filename'] = $filename;
        $user_data['viewname'] = 'Brokingslip.pdf';
        $user_data['file'] = $documentDetails[0];
        return $user_data;
    }

    /**
     * To get quote detail array
     * @param Object $request
     * @param integer $customerId
     * @param integer $Id
     * @return string
     */
    private function getQuoteDetailsArray($request, $customerId, $Id) {
        $whereArray[] = ['id', '=', $Id];
        $whereArray[] = ['customer_id', '=', $customerId];
        $user_data = array();
        $quoteDetails = DB::table('quote')->where($whereArray)->pluck('file_name')->toArray();
        $filename = "uploads/" . $customerId . "/Quotes/" . $quoteDetails[0];
        $user_data['to_data'] = $request->get('to_data');
        $user_data['cc_data'] = $request->get('cc_data');
        $user_data['subject'] = $request->get('subject');
        $user_data['message'] = $request->get('message');
        $user_data['filename'] = $filename;
        $user_data['file'] = $quoteDetails[0];
        $user_data['viewname'] = 'Quote.pdf';

        return $user_data;
    }

    /**
     * To get quote sending pop up form
     * @param type $customerId
     * @param type $crmId
     * @param type $docId
     * @return type
     */
    public function sendQuoteForm($customerId, $crmId, $docId, $type = 'quote') {
        $data['customerId'] = $customerId;
        $data['crmId'] = $crmId;
        $data['docId'] = $docId;
        $data['type'] = $type;
        $returnHTML = view('quote/sendmailpopup', $data)->render();
        return response()->json(array('status' => true, 'content' => $returnHTML));
    }

    /**
     * To create comparison details array
     * @param type $request
     * @param type $customerId
     * @param type $Id
     * @return type
     */
    private function getComparisonDetailsArray($request, $customerId, $Id) {
        $whereArray[] = ['id', '=', $Id];
        $whereArray[] = ['customer_id', '=', $customerId];
        $whereArray[] = ['document_type', '=', 8];
        $user_data = array();
        $comparisonfileDetails = DB::table('customer_crm_documents')->where($whereArray)->pluck('filename')->toArray();
        $filename = "uploads/" . $customerId . "/document/" . $comparisonfileDetails[0];
        $user_data['to_data'] = $request->get('to_data');
        $user_data['cc_data'] = $request->get('cc_data');
        $user_data['subject'] = $request->get('subject');
        $user_data['message'] = $request->get('message');
        $user_data['filename'] = $filename;
        $user_data['file'] = $comparisonfileDetails[0];
        $user_data['viewname'] = $comparisonfileDetails[0];

        return $user_data;
    }

    /**
     * To get insurance doc send form
     * @param integer $customerId
     * @param integer $crmId
     * @param integer $quoteId
     * @return type
     */
    public function getIssuanceDocSendForm($customerId, $crmId, $quoteId) {

        $data = array('customerId' => $customerId, 'crmId' => $crmId, 'quoteId' => $quoteId);
        $returnHTML = view('Quote/senddocumentpopup', $data)->render();
        return response()->json(array('success' => true, 'content' => $returnHTML));
    }

    public function displayQuote($customerId, $crmId, $docId) {

        $flag = Input::get("displayflag");
        DB::table('quote')->where('crm_id', $crmId)->update(array('display_flag' => $flag));

        if ($flag == 1) {
            $crmdetails = DB::table('crm_main_table')->select('crm_request_id')->where('id', $crmId)->first();
            $notificationArray['message'] = 'New quotes was upload against Request No:' . $crmdetails->crm_request_id;
            $notificationArray['created_date'] = date('Y-m-d');
            $notificationArray['department'] = 'SALES';
            $notificationArray['message_type'] = 'quote';
            DB::table('notification_details')->insert($notificationArray);
        }


        Session::flash('success', 'Successfully changed display flag of quote');
        return response()->json(array('success' => true, 'content' => ''));
    }

    /**
     * Comparison doc creation form
     * @return type
     */
    public function createComparisondoc($customerId, $crmId) {
        $insuranceCompany = DB::table('insurer_details')->distinct()->where('status', '1')->orderBy('id')->pluck('insurer_name', 'insurer_name')->toArray();
        $categoryClasses = ['VIP', 'Class A', 'Class B', 'Class C'];

        $data = array('insuranceCompany' => $insuranceCompany, 'categoryclasses' => $categoryClasses, 'customerId' => $customerId, 'crmId' => $crmId);


        return view('comparison/comparisonform', $data);
    }

    /**
     * Generate comparison list excel file
     * @param Request $request
     */
    public function createComparisonpdf(Request $request, $customerId, $crmId) {

        $countDetails = $request->get('countDetails');
        $premiumDetails = $request->get('premium');
        $companyDetails = $request->get('company');
        $tableData = array();
        $summaryData = array();


        if (count($companyDetails) !== null) {
            $categoryArray = array_keys($premiumDetails['employee'][0]);
            $memberTypes = array_keys($premiumDetails);
            $totalCategoryCountDetails = array();
            $totalCategoryPremiumDetails = array();
            $grandTotalMember = array();
            $grandTotalPremium = array();

            foreach ($companyDetails as $key => $company) {
                $iCount = 0;
                $requestArray = array();
                $categoryCount = array();
                $categoryTotalPremium = array();
                $requestArray[$iCount] = ['CLASS'];
                $grandTotalWithoutVat = 0;
                $grandTotalCount = 0;
// CATEGORY CREATION AREA
                foreach ($categoryArray as $newcat) {
                    array_push($requestArray[$iCount], $newcat);
                }


                $iCount++;
                $requestArray[$iCount] = array('RELATION', 'MEMBERS', 'A.PREMIUM', 'T.PREMIUM', 'MEMBERS', 'A.PREMIUM', 'T.PREMIUM', 'MEMBERS', 'A.PREMIUM', 'T.PREMIUM', 'MEMBERS', 'A.PREMIUM', 'T.PREMIUM');

                $iCount++;
                $jCount = $iCount;

                foreach ($memberTypes as $mkey => $membertype) {
                    $requestArray [$jCount]['CLASS'] = strtoupper($membertype);
                    $totalPremium = 0;
                    $totalMember = 0;


                    foreach ($categoryArray as $category) {

                        if (isset($categoryCount[$category])) {
                            $categoryCount[$category] = $categoryCount[$category] + $countDetails[$membertype][$category];

                            $categoryTotalPremium[$category] = $categoryTotalPremium[$category] + floatval($countDetails[$membertype][$category] * $premiumDetails[$membertype][$key][$category]);
                        } else {
                            $categoryCount[$category] = $countDetails[$membertype][$category];

                            $categoryTotalPremium[$category] = floatval($countDetails[$membertype][$category] * $premiumDetails[$membertype][$key][$category]);
                        }



                        $requestArray[$jCount]['MEMBERS_' . $category] = $countDetails[$membertype][$category];

                        $totalMember += floatval($countDetails[$membertype][$category]);
                        $totalPremium += floatval($countDetails[$membertype][$category] * $premiumDetails[$membertype][$key][$category]);

                        $requestArray[$jCount]['A.PREMIUM_' . $category] = "SAR " . floatval($premiumDetails[$membertype][$key][$category]);
                        $requestArray[$jCount]['T.PREMIUM_' . $category] = "SAR " . floatval($countDetails[$membertype][$category] * $premiumDetails[$membertype][$key][$category]);
                    }
                    $requestArray[$jCount]['TOTAL MEMBER'] = $totalMember;
                    $requestArray[$jCount]['TOTAL PREMIUM'] = "SAR " . $totalPremium;

                    $grandTotalWithoutVat += $totalPremium;
                    $grandTotalCount += $totalMember;

                    $jCount = $jCount + 1;
                }


                $totalCategoryCountDetails[$key] = $categoryCount;
                $totalCategoryPremiumDetails[$key] = $categoryTotalPremium;

                $grandTotalMember[$key] = $grandTotalCount;
                $grandTotalPremium[$key] = $grandTotalWithoutVat;

                $tableData[$key] = $requestArray;
            }
        }



        $data = array('requestDatas' => $tableData, 'companyDetails' => $companyDetails, 'totalCategoryCountDetails' => $totalCategoryCountDetails, 'totalCategoryPremiumDetails' => $totalCategoryPremiumDetails, 'grandTotalPremium' => $grandTotalPremium, 'grandTotalMember' => $grandTotalMember);

        $filename_part1 = 'comparisonlist_' . date('Ymdhis');
        $filename = $filename_part1 . ".xlsx";
        Excel::create($filename_part1, function($excel) use ($data) {

            $excel->sheet('Sheet1', function($sheet) use ($data) {
                $passingData = $data;
                $sheet->loadView('comparisonExcel/sheet_1', $passingData);
            });
            $excel->sheet('SUMMARY', function($sheet) use ($data) {
                $argumentData = $data;
                $sheet->loadView('comparisonExcel/sheet_2', $argumentData);
            });
        })->store('xlsx', "uploads/" . $customerId . "/document");

        //store('xlsx', storage_path("uploads/" . $customerId . "/document"));
#Store and export
        //->download('xlsx')
        $insertArray = array("customer_id" => $customerId,
            "filename" => $filename,
            "document_type" => 8,
            "comment" => null,
            "uploaded_by" => Auth::user()->id,
            "uploaded_at" => date('Y-m-d h:i'),
            "crm_id" => $crmId
        );
        DB::table('customer_crm_documents')->insert($insertArray);

        return redirect()->route('crmrequestOverview', $crmId)->with('success', 'Successfully created comparison file!');

#
//return view('comparisonExcel/sheet_1',  $requestArray);


        exit;
    }

    /**
     * 
     * @param Request $request
     * @param type $customerId
     * @param type $crmId
     * @return type
     */
    public function changepolicyscheduleFlag(Request $request, $customerId, $crmId) {
        $crmObj = App\crmMain::where('id', $crmId)
                        ->where('customer_id', $customerId)->first();

        $editTime = date('Y-m-d h:i');
        $crmObj->policy_schedule_flag = $request->get('scheduleflag');
        $crmObj->updated_date = $editTime;
        $crmObj->policy_schedule_reason = $request->get('document_comment', null);

        $crmObj->save();
        $changeString = ' No';
        if ($request->get('scheduleflag') == 0) {
            $changeString = ' Yes';
        }
        $logarray = array("crm_id" => $crmId,
            "title" => 'Policy schedule flag was changed to  ' . $changeString,
            "edited_by" => Auth::user()->id,
            "old_value" => '',
            "edited_at" => $editTime);
        $customerObj = new customer();

        $customerObj->logInsert('crm_log', $logarray);
        Session::flash('success', 'Successfully changed policy schedule flag!!');

        return response()->json(array('status' => true));
    }

    /**
     * 
     * @param Request $request
     * @param type $customerId
     * @param type $crmId
     */
    public function uploadbrokingSlip(Request $request, $customerId, $crmId) {
        //FILE UPLOAD AREA
        $file = $request->file('broking_slip_file');
        $product = $request->get('insurance_product');
        File::isDirectory('uploads/brokingslip/' . $customerId . '/' . $crmId) or File::makeDirectory('uploads/brokingslip/' . $customerId . '/' . $crmId, 0777, true, true);
        $destinationPath = 'uploads/brokingslip/' . $customerId . '/' . $crmId . '/';
        $path_parts = pathinfo($file->getClientOriginalName());
        $newfilename = $path_parts['filename'] . '.' . $path_parts['extension'];
        $filename = $newfilename;
        $file->move($destinationPath, $newfilename);


        $uploadDate = date('Y-m-d H:i:s');
        $brokingslipData = [];
        $logArray = [];

        foreach ($request->get('insurance_company') as $company) {

            // Save broking slip file to document

            $brokingslipData[] = array('crm_id' => $crmId,
                'file_name' => $filename,
                'created_date' => $uploadDate,
                'updated_date' => $uploadDate,
                'customer_id' => $customerId,
                'company_id' => $company,
                'status' => 0,
                'product_id' => $product,
                'form_data' => null,
                'uploaded_by' => Auth::user()->id
            );

            $logArray[] = array("crm_id" => $crmId,
                "title" => 'Broken slip was uploaded . File: ' . $filename,
                "edited_by" => Auth::user()->id,
                "old_value" => '',
                "edited_at" => $uploadDate);
        }
        //insert log entry
        if (count($brokingslipData) > 0) {
            DB::table('broking_slip_info')->insert($brokingslipData);
            $customerObj = new customer();
            $customerObj->logInsert('crm_log', $logArray);
        }
        return back()->with(['success' => 'Successfully upload broking slip!', 'overviewtabselected' => 'document']);
    }

}
