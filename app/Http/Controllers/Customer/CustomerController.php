<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use Response;
use Illuminate\Support\Facades\DB;
use Auth;
use Mail;
use App;
use App\customer;
use App\customer_address;
use App\contact_person;
use App\customerComment;
use App\customerDocument;
use App\crmMain;
use App\Http\Controllers\Controller;
use Session;
use File;
use Illuminate\Contracts\Filesystem\FileNotFoundException;



class CustomerController extends Controller {

    /**
     * Create customer page
     * @return type
     */
    public function addcustomer() {
        //collect user details
//        $users = DB::table('users')->distinct()->where('status', '1')->get();
        $users = DB::table('users')->distinct()->where('status', '1')->orderBy('name')->pluck('name', 'id')->toArray();
        $userGroup = array('' => '--- not set ---', 'corporate' => 'Corporate', 'retail' => 'Retail', 'sme' => 'SME');
        $technicalPersons = DB::table('users')->distinct()->where('status', '1')->where('department', 'technical')->orderBy('name')->pluck('name', 'id')->toArray();

        $channelDetails = array('' => '--- not set ---', 'direct' => 'Direct', 'sales' => 'Sales');
        $breadcrumbDetails = array('url' => action('Dashboard\DashboardController@leadsDetails'), 'title' => 'Leads');
        $headTitle = 'Create Lead';
        $data = array('users' => $users, 'usergroup' => $userGroup, "title" => 'Çreate customer', 'headTitle' => $headTitle, 'breadcrumb' => $breadcrumbDetails, 'channelDetails' => $channelDetails, 'technicalhanler' => $technicalPersons);



        return view('customer/createcustomer', $data);
    }

    /**
     * 
     * @param Request $request
     */
    public function savecustomer(Request $request) {

        //server side validation
        $validator = $this->validate($request, [
            'customer_email' => 'required|email',
        ]);
        $customerId = $this->saveBasicData($request);

        return redirect()->route('customeroverview', $customerId)->with('success', 'Successfully created lead!');
        //return redirect()->route('dashboard')->with('success', 'Successfully created lead!');
        //return back()->with('success', 'Successfully create customer data!');
    }

    /**
     * 
     * @param type $request
     * @param type $customerId
     */
    private function saveBasicData($request, $customerId = 0) {

        if ($customerId > 0) {
            $customerObj = customer::find($customerId);
            $this->findBasicChangedValues($customerObj, $request, $customerId);
        } else {
            $customerObj = new customer();
        }

        $customerObj->name = $request->get('customer_name');
        if ($customerId == 0) {
            $requestId = substr("CL-" . uniqid(date("Ymdhi")), 0, -12);
            $customerObj->accountNo = $requestId;
        }

        $customerObj->email = $request->get('customer_email');
        $customerObj->phone = $request->get('customer_phone');
        $customerObj->mobile = $request->get('customer_mobile');
        $customerObj->type = $request->get('customer_type');
        $customerObj->gender = $request->get('customer_gender');
        $customerObj->customer_code = $request->get('customer_idcode');
        $customerObj->fax = $request->get('customer_fax');
        $customerObj->id_code = $request->get('customer_customercode');

        $customerObj->add_contact_person_flag = ($request->get('addperson') != null) ? $request->get('addperson') : '0';

        $customerObj->website = $request->get('customer_url');
        $customerObj->prefered_communication_type = $request->get('customer_preferredcommunicationchannel');
        $customerObj->customer_management_user = $request->get('broker_person_oid');
        $customerObj->customer_group = $request->get('customergroup_oid');
        //Accounting info area
        $customerObj->consolidate_invoice = $request->get('prop_invoice_consolidatedinvoices');
        $customerObj->bank_acc_info = $request->get('customer_bankaccount');
        $customerObj->invoice_email = $request->get('customer_email_invoice');

        $customerObj->channel = $request->get('customer_channel');
        $customerObj->technical_handler = $request->get('customer_technical_handler');
        $customerObj->created_user = Auth::user()->id;


        $customerObj->save();
        $customerId = $customerObj->getKey();
        //address saving area
        $this->addAddressData($customerObj, $request, $customerId);
        //Contact person detail of the client
        $this->addContactPersonInfo($customerObj, $request, $customerId);

        return $customerId;
    }

    /**
     * To save address data of a customer
     * @param type $customerObj
     * @param type $request
     */
    private function addAddressData($customerObj, $request, $customerId = 0, $addressId = 0) {
        if ($addressId > 0) {
            $customerAddressDetails = DB::table('customer_address')->where('id', $addressId)->first();
        } else {
            $customerAddressDetails = DB::table('customer_address')->where('customer_id', $customerId)->first();
        }


        $newaddressArray = ($customerAddressDetails !== null) ? json_decode(json_encode($customerAddressDetails), true) : array();
        if (count($newaddressArray) > 0) {

//            Address detail Log entries
            $this->findAddressChangedValues($customerAddressDetails, $request, $customerId);
            $addressId = $customerAddressDetails->id;
            DB::table('customer_address')->where('id', $addressId)
                    ->update(array('customer_id' => $customerObj->getKey(),
                        'address_type' => json_encode($request->get('addaddress_address_type')),
                        'building_no' => $request->get('addaddress_address_building_no'),
                        'street_name' => $request->get('addaddress_address_street'),
                        'district_name' => $request->get('addaddress_address_district'),
                        'city_name' => $request->get('addaddress_address_city'),
                        'zip_code' => $request->get('addaddress_address_zipcode'),
                        'additional_no' => $request->get('addaddress_address_additional_no'),
                        'unit_no' => $request->get('addaddress_address_unit_no'),
                        'modifies_at' => date('Y-m-d h:i')
                            )
            );
        } else {


            $customerAddress = new customer_address();
            $customerAddress->customer_id = $customerObj->getKey();
            $customerAddress->address_type = json_encode($request->get('addaddress_address_type'));
            $customerAddress->building_no = $request->get('addaddress_address_building_no');
            $customerAddress->street_name = $request->get('addaddress_address_street');
            $customerAddress->district_name = $request->get('addaddress_address_district');
            $customerAddress->city_name = $request->get('addaddress_address_city');
            $customerAddress->zip_code = $request->get('addaddress_address_zipcode');
            $customerAddress->additional_no = $request->get('addaddress_address_additional_no');
            $customerAddress->unit_no = $request->get('addaddress_address_unit_no');
            $customerAddress->save();
        }
    }

    /**
     * Add contact person details against customer id
     * @param type $customerObj
     * @param type $request
     */
    private function addContactPersonInfo($customerObj, $request, $customerId = 0, $contactId = 0) {

        if ($contactId > 0) {
            $contactAddressDetails = DB::table('customer_contact_person_info')->where('id', $contactId)->first();
        } else {
            $contactAddressDetails = DB::table('customer_contact_person_info')->where('customer_id', $customerId)->first();
        }



        $newaddressArray = ($contactAddressDetails !== null) ? json_decode(json_encode($contactAddressDetails), true) : array();


        if (count($newaddressArray) > 0) {
//            Contact person info log entries
            $this->findContactpersonChangedValues($contactAddressDetails, $request, $customerId);
            $contactId = $contactAddressDetails->id;
            DB::table('customer_contact_person_info')->where('id', $contactId)
                    ->update(array(
                        'person_name' => $request->get('addperson_name'),
                        'person_title' => $request->get('addperson_title'),
                        'email' => $request->get('addperson_email'),
                        'mobile_phone' => $request->get('addperson_mobile'),
                        'phone' => $request->get('addperson_phone'),
                        'person_gender' => $request->get('contactperson_gender'),
                        'contact_person_id_code' => $request->get('addperson_idcode'),
                        'updated_date' => date('Y-m-d h:i')
                            )
            );
        } else {
            $contactPersonDetail = new contact_person();
            $contactPersonDetail->customer_id = $customerObj->getKey();
            $contactPersonDetail->person_name = $request->get('addperson_name');
            $contactPersonDetail->person_title = $request->get('addperson_title');
            $contactPersonDetail->email = $request->get('addperson_email');
            $contactPersonDetail->mobile_phone = $request->get('addperson_mobile');
            $contactPersonDetail->phone = $request->get('addperson_phone');
            $contactPersonDetail->contact_person_id_code = $request->get('addperson_idcode');
            $contactPersonDetail->person_gender = ($request->get('contactperson_gender1') != '') ? $request->get('contactperson_gender1') : 'male';
            $contactPersonDetail->added_date = date('Y-m-d h:i:s');
            $contactPersonDetail->save();
        }
    }

    /**
     * For edit customer data
     * @param type $customerId
     * @return type
     */
    public function editCustomerData($customerId) {
        $customerObj = new customer();
        $customerDetails = $customerObj->getCustomerDetails($customerId);

        //collect user details
        $users = DB::table('users')->distinct()->where('status', '1')->orderBy('name')->pluck('name', 'id')->toArray();
        $userGroup = array('' => '--- not set ---', 'corporate' => 'Corporate', 'retail' => 'Retail', 'sme' => 'SME');
        $technicalPersons = DB::table('users')->distinct()->where('status', '1')->where('department', 'technical')->orderBy('name')->pluck('name', 'id')->toArray();
        $channelDetails = array('' => '--- not set ---', 'direct' => 'Direct', 'sales' => 'Sales');

        $policyCount = DB::table('policies')
                ->select('id')
                ->where('customer_id', '=', $customerId)
                ->whereNotNull('policy_number')
                ->count();
        if ($policyCount > 0) {
            $breadcrumb = array('url' => action('Dashboard\DashboardController@customerList'), 'title' => 'Customers');
            $headTitle = 'Edit customer';
        } else {
            $breadcrumb = array('url' => action('Dashboard\DashboardController@leadsDetails'), 'title' => 'Leads');
            $headTitle = 'Edit lead';
        }


        $data = array('customers' => $customerDetails, 'users' => $users, 'usergroup' => $userGroup, "title" => 'Edit customer', "headTitle" => $headTitle, 'breadcrumb' => $breadcrumb, 'channelDetails' => $channelDetails, 'technicalhanler' => $technicalPersons);

        return view('customer/editcustomer', $data);
    }

    /**
     * To update customer data
     * @param type $customerId
     * @param Request $request
     * @return type
     */
    public function updateCustomerData($customerId, Request $request) {
        $this->saveBasicData($request, $customerId);

        return redirect()->route('customeroverview', $customerId)->with('success', 'Successfully update customer data!');
        //return back()->with('success', 'Successfully update customer data!');
    }

    /**
     * Custermer listing view
     * @return type
     */
    public function listCustomerDetails() {

        $users = DB::table('users')->distinct()->where('status', '1')->orderBy('name')->pluck('name', 'id')->toArray();
        $userGroup = array('' => '--- not set ---', 'corporate' => 'Corporate', 'retail' => 'Retail', 'sme' => 'SME');
        return view('customer/listcustomer', array("title" => 'Customers', 'users' => $users, 'usergroup' => $userGroup));
    }

    /**
     * To get the customer listing data
     * @return type
     */
    public function getCustomerListingData($type = 'all') {
        $customerObj = new customer();
        $individualFlag = false;
        if (in_array('ROLE_SALES_MANAGER', Auth::user()->roles) || in_array('ROLE_TECHNICAL_MANAGER', Auth::user()->roles)) {
            
        } else if (in_array('ROLE_SALES', Auth::user()->roles)) {
            $individualFlag = true;
        } else {
            
        }

        if ($type == 'leads') {
            $customerDetailObj = DB::table('customers as c')
                    ->leftJoin('policies as p', 'p.customer_id', '=', 'c.id')
                    ->leftJoin('customer_address as ca', 'c.id', '=', 'ca.customer_id')
                    ->leftJoin('customer_contact_person_info as cp', 'c.id', '=', 'cp.customer_id')
                    ->leftJoin('users as u', 'c.customer_management_user', '=', 'u.id')
                    ->whereNull('p.id')
                    ->select('c.*', 'ca.*', 'cp.*', 'c.id as customId', 'c.email as customerEmail', 'c.phone as customerPhone', 'cp.email as contactEmail', 'cp.phone as contactPhone', 'u.name as userName', 'c.name as customerName', 'ca.id as addressId');

            if ($individualFlag) {
                $customerDetailObj->where('c.created_user', Auth::user()->id);
            }
            $customerDetails = $customerDetailObj->orderBy('c.updated_at', 'desc')->groupby('c.id')->get();
        } else if ($type == 'customers') {
            $customerDetailObj = DB::table('customers as c')
                    ->join('policies as p', 'p.customer_id', '=', 'c.id')
                    ->leftJoin('customer_address as ca', 'c.id', '=', 'ca.customer_id')
                    ->leftJoin('customer_contact_person_info as cp', 'c.id', '=', 'cp.customer_id')
                    ->leftJoin('users as u', 'c.customer_management_user', '=', 'u.id')
                    ->select('c.*', 'ca.*', 'p.id as policyId', 'cp.*', 'c.id as customId', 'c.email as customerEmail', 'c.phone as customerPhone', 'cp.email as contactEmail', 'cp.phone as contactPhone', 'u.name as userName', 'c.name as customerName', 'ca.id as addressId');
            if ($individualFlag) {
                $customerDetailObj->where('c.created_user', Auth::user()->id);
            }

            $customerDetails = $customerDetailObj->orderBy('c.updated_at', 'desc')->groupby('c.id')->get();
        } else {
            $customerDetails = $customerObj->getAllCustomerDetails($individualFlag);
        }

        $Response = array('iTotalRecords' => count($customerDetails), 'iTotalDisplayRecords' => count($customerDetails), 'aaData' => $customerDetails);

        return response()->json($Response);
    }

    /**
     * To get the customer column setting data
     * @return type
     */
    public function getColumnSettingData() {

        $returnHTML = view('customer/customerColumnSetting')->render();
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    /**
     * To collect the data of customer
     * @param type $customerId
     * @return type
     */
    public function overviewCustomerData(Request $request, $customerId) {
        $customerObj = new customer();
        $customerDetails = $customerObj->getCustomerDetails($customerId);

        // Customer notes data
        $customerNoteObj = new customerComment();
        if ($request->session()->has('overviewtabselected') && Session::get('overviewtabselected') != '') {
            $overviewTab = Session::get('overviewtabselected');
        } else {
            $overviewTab = 'overview';
            Session::put('overviewtabselected', 'overview');
        }
        $documentcount = count(DB::table('dp_customer_document')->where('customer_id', '=', $customerId)->get());

        $commentDetails = $customerNoteObj->getCustomerNote($customerId);
        $contactPersonDetails = App\contact_person::where('customer_id', $customerId)->get();
        $addressDetails = App\customer_address::where('customer_id', $customerId)->get();
        $quoteRequestDetails = App\Dpibquoterequest::where('customer_id', $customerId)->get();

        $customerRelationDetails = DB::table('customers_connection as cn')
                        ->join('customers as cs', 'cn.related_customer_id', '=', 'cs.id')
                        ->select('cn.*', 'cs.*', 'cn.id as mainId')
                        ->where('cn.customer_id', '=', $customerId)->get();
        $policyCount = DB::table('policies')
                ->select('id')
                ->where('customer_id', '=', $customerId)
                ->whereIn('policy_status', [2, 4, 5])
                ->whereNotNull('policy_number')
                ->count();
        $quoteCount = DB::table('quote as qt')
                        ->join('customers as cs', 'qt.customer_id', '=', 'cs.id')
                        ->select('qt.id')
                        ->where('qt.customer_id', '=', $customerId)->count();
        $policyAmountDetails['active'] = $this->activePolicyDetails($customerId);
        $policyAmountDetails['all'] = $this->calculatePolicyAmount($customerId);

        $customerVatDetails = $this->customerPolicyVatDetails($customerId);
        if ($policyCount > 0) {
            $breadcrumbDetails = array('url' => action('Dashboard\DashboardController@customerList'), 'title' => 'Customers');
        } else {
            $breadcrumbDetails = array('url' => action('Dashboard\DashboardController@leadsDetails'), 'title' => 'Leads');
        }


        $data = array('customers' => $customerDetails, 'notes' => $commentDetails, 'title' => 'Customer overview', 'overviewTab' => $overviewTab, 'documentcount' => $documentcount, 'contactpersonDetails' => $contactPersonDetails, 'addressDetails' => $addressDetails, 'requestData' => $quoteRequestDetails, 'customerRelationdetails' => $customerRelationDetails, 'policyCount' => $policyCount, 'quoteCount' => $quoteCount, 'policyAmountDetails' => $policyAmountDetails, 'vatDetails' => $customerVatDetails, 'breadcrumb' => $breadcrumbDetails);


        return view('customer/customeroverview', $data);
    }

    /**
     * To add a new note against customer
     * @param type $customerId
     * @param Request $request
     * @return type
     */
    public function noteAdd($customerId, Request $request) {
        $note = $request->get('note');
        $customerNote = new customerComment();
        $customerNote->customer_id = $customerId;
        $customerNote->comment = $note;
        $customerNote->save();
        $commentId = $customerNote->getKey();
        if ($commentId > 0) {
            $status = 'success';
        } else {
            $status = "false";
        }
        $Response = array('status' => $status);

        return response()->json($Response);
    }

    /**
     * To get custom filter data
     * @param Request $request
     * @return type
     */
    public function customerFilter(Request $request) {
        $formData = $request->get('formData');
        $createdFormArray = $this->createFormArray($formData);
        $customerObj = new customer();
        $customerDetails = $customerObj->getFilteredCustomer($createdFormArray);

        $Response = array('iTotalRecords' => count($customerDetails), 'iTotalDisplayRecords' => count($customerDetails), 'aaData' => $customerDetails);

        return response()->json($Response);
    }

    /**
     * To manipulate form array
     * @param array $serializedArray
     * @return type
     */
    private function createFormArray($serializedArray) {
        $newFormArray = [];
        if (count($serializedArray) > 0) {
            foreach ($serializedArray as $formdata) {
                $newFormArray[$formdata['name']] = $formdata['value'];
            }
        }

        return $newFormArray;
    }

    /**
     * To find the log change of contact person details
     * @param array    $resultArray
     * @param object   $request
     * @param integer  $customerId
     */
    private function findContactpersonChangedValues($resultArray, $request, $customerId) {

        $logarray = [];
        $loggedUser = Auth::user()->id;
        $date = date('Y-m-d h:i');
        $i = 0;

        if ($resultArray->person_name != $request->get('addperson_name')) {
            $logarray[$i] = $this->createLogInsertValue(array($customerId, "Contatct person name was changed to {$request->get('addperson_name')}", "{$resultArray->person_name}", $loggedUser, "{$date}"));
            $i++;
        }
        if ($resultArray->person_title != $request->get('addperson_title')) {
            $logarray[$i] = $this->createLogInsertValue(array($customerId, "Contatct person title was changed to {$request->get('addperson_title')}", "{$resultArray->person_title}", $loggedUser, "{$date}"));
            $i++;
        }
        if ($resultArray->email != $request->get('addperson_email')) {
            $logarray[$i] = $this->createLogInsertValue(array($customerId, "Contatct person email was changed to {$request->get('addperson_email')}", "{$resultArray->email }", $loggedUser, "{$date}"));
            $i++;
        }
        if ($resultArray->mobile_phone != $request->get('addperson_mobile')) {
            $logarray[$i] = $this->createLogInsertValue(array($customerId, "Contatct person mobile number was changed to {$request->get('addperson_mobile')}", "{$resultArray->mobile_phone}", $loggedUser, "{$date}"));
            $i++;
        }
        if ($resultArray->phone != $request->get('addperson_phone')) {
            $logarray[$i] = $this->createLogInsertValue(array($customerId, "Contatct person phone number was changed to {$request->get('addperson_phone')}", "{$resultArray->phone}", $loggedUser, "{$date}"));
            $i++;
        }
        if ($resultArray->contact_person_id_code != $request->get('addperson_idcode')) {
            $logarray[$i] = $this->createLogInsertValue(array($customerId, "Contatct person id code was changed to {$request->get('addperson_idcode')}", "{$resultArray->contact_person_id_code}", $loggedUser, "{$date}"));
            $i++;
        }
        if (count($logarray) > 0) {
            $customerObj = new customer();
            $customerObj->logInsert('customer_log', $logarray);
        }
    }

    /**
     * To create insert array
     * @param array $detailArray
     * @return type
     */
    private function createLogInsertValue($detailArray) {
        $logarray = array("customer_id" => $detailArray[0],
            "title" => $detailArray[1],
            "old_value" => $detailArray[2],
            "edited_by" => $detailArray[3],
            "updated_at" => $detailArray[4]);

        return $logarray;
    }

    /**
     * To find the log values of address field
     * @param array  $resultArray query result
     * @param Object $request 
     * @param int $customerId
     */
    private function findAddressChangedValues($resultArray, $request, $customerId) {

        $logarray = [];
        $loggedUser = Auth::user()->id;
        $date = date('Y-m-d h:i');
        $i = 0;

        if ($resultArray->address_type != $request->get('addaddress_address_type')) {
            $data = json_encode($request->get('addaddress_address_type'));
            $logarray[$i] = $this->createLogInsertValue(array($customerId, "Address type was changed to {$data}", "{$resultArray->address_type}", $loggedUser, "{$date}"));
            $i++;
        }
        if ($resultArray->building_no != $request->get('addaddress_address_building_no')) {
            $logarray[$i] = $this->createLogInsertValue(array($customerId, "Building number was changed to {$request->get('addaddress_address_building_no')}", "{$resultArray->building_no}", $loggedUser, "{$date}"));
            $i++;
        }
        if ($resultArray->street_name != $request->get('addaddress_address_street')) {
            $logarray[$i] = $this->createLogInsertValue(array($customerId, "Street detail was changed to {$request->get('addaddress_address_street')}", "{$resultArray->street_name }", $loggedUser, "{$date}"));
            $i++;
        }
        if ($resultArray->district_name != $request->get('addaddress_address_district')) {
            $logarray[$i] = $this->createLogInsertValue(array($customerId, "District detail was changed to {$request->get('addaddress_address_district')}", "{$resultArray->district_name}", $loggedUser, "{$date}"));
            $i++;
        }
        if ($resultArray->city_name != $request->get('addaddress_address_city')) {
            $logarray[$i] = $this->createLogInsertValue(array($customerId, "City detail was changed to {$request->get('addaddress_address_city')}", "{$resultArray->city_name}", $loggedUser, "{$date}"));
            $i++;
        }
        if ($resultArray->zip_code != $request->get('addaddress_address_zipcode')) {
            $logarray[$i] = $this->createLogInsertValue(array($customerId, "Zip code detail was changed to {$request->get('addaddress_address_zipcode')}", "{$resultArray->zip_code}", $loggedUser, "{$date}"));
            $i++;
        }
        if ($resultArray->additional_no != $request->get('addaddress_address_additional_no')) {
            $logarray[$i] = $this->createLogInsertValue(array($customerId, "Additional number detail was changed to {$request->get('addaddress_address_additional_no')}", "{$resultArray->additional_no}", $loggedUser, "{$date}"));
            $i++;
        }
        if ($resultArray->unit_no != $request->get('addaddress_address_unit_no')) {
            $logarray[$i] = $this->createLogInsertValue(array($customerId, "Unit number detail was changed to {$request->get('addaddress_address_unit_no')}", "{$resultArray->unit_no}", $loggedUser, "{$date}"));
            $i++;
        }
        if (count($logarray) > 0) {
            $customerObj = new customer();
            $customerObj->logInsert('customer_log', $logarray);
        }
    }

    /**
     * To find and insert customer basic values
     * @param array     $resultArray
     * @param Object    $request
     * @param Integer   $customerId
     */
    private function findBasicChangedValues($customerObj, $request, $customerId) {
        $logarray = [];
        $loggedUser = Auth::user()->id;
        $date = date('Y-m-d h:i');
        $i = 0;

        if ($customerObj->name != $request->get('customer_name')) {
            $data = $request->get('customer_name');
            $logarray[$i] = $this->createLogInsertValue(array($customerId, "Customer name was changed to {$data}", "{$customerObj->name}", $loggedUser, "{$date}"));
            $i++;
        }
        if ($customerObj->email != $request->get('customer_email')) {
            $logarray[$i] = $this->createLogInsertValue(array($customerId, "Customer email was changed to {$request->get('customer_email')}", "{$customerObj->email}", $loggedUser, "{$date}"));
            $i++;
        }
        if ($customerObj->phone != $request->get('customer_phone')) {
            $logarray[$i] = $this->createLogInsertValue(array($customerId, "Customer phone was changed to {$request->get('customer_phone')}", "{$customerObj->phone }", $loggedUser, "{$date}"));
            $i++;
        }
        if ($customerObj->mobile != $request->get('customer_mobile')) {
            $logarray[$i] = $this->createLogInsertValue(array($customerId, "Customer mobile number  was changed to {$request->get('customer_mobile')}", "{$customerObj->mobile}", $loggedUser, "{$date}"));
            $i++;
        }
        if ($customerObj->type != $request->get('customer_type')) {
            $logarray[$i] = $this->createLogInsertValue(array($customerId, "Customer type was changed to {$request->get('customer_type')}", "{$customerObj->type}", $loggedUser, "{$date}"));
            $i++;
        }
        if ($customerObj->gender != $request->get('customer_gender')) {
            $logarray[$i] = $this->createLogInsertValue(array($customerId, "Customer gender was changed to {$request->get('customer_gender')}", "{$customerObj->gender}", $loggedUser, "{$date}"));
            $i++;
        }
        if ($customerObj->customer_code != $request->get('customer_idcode')) {
            $logarray[$i] = $this->createLogInsertValue(array($customerId, "Customer id code was changed to {$request->get('customer_idcode')}", "{$customerObj->customer_code}", $loggedUser, "{$date}"));
            $i++;
        }
        if ($customerObj->fax != $request->get('customer_fax')) {
            $logarray[$i] = $this->createLogInsertValue(array($customerId, "Customer fax number was changed to {$request->get('customer_fax')}", "{$customerObj->fax}", $loggedUser, "{$date}"));
            $i++;
        }
        if ($customerObj->id_code != $request->get('customer_customercode')) {
            $logarray[$i] = $this->createLogInsertValue(array($customerId, "Customer code was changed to {$request->get('customer_customercode')}", "{$customerObj->id_code}", $loggedUser, "{$date}"));
            $i++;
        }

        if ($customerObj->website != $request->get('website')) {
            $logarray[$i] = $this->createLogInsertValue(array($customerId, "Customer website detail was changed to {$request->get('website')}", "{$customerObj->website}", $loggedUser, "{$date}"));
            $i++;
        }
        if ($customerObj->prefered_communication_type != $request->get('customer_preferredcommunicationchannel')) {
            $logarray[$i] = $this->createLogInsertValue(array($customerId, "Preferd communication was changed to {$request->get('customer_preferredcommunicationchannel')}", "{$customerObj->prefered_communication_type}", $loggedUser, "{$date}"));
            $i++;
        }
        if ($customerObj->customer_management_user != $request->get('broker_person_oid')) {
            $logarray[$i] = $this->createLogInsertValue(array($customerId, "Customer management was changed to {$request->get('broker_person_oid')}", "{$customerObj->customer_management_user}", $loggedUser, "{$date}"));
            $i++;
        }
        if ($customerObj->customer_group != $request->get('customergroup_oid')) {
            $logarray[$i] = $this->createLogInsertValue(array($customerId, "Customer group was changed to {$request->get('customergroup_oid')}", "{$customerObj->customer_group}", $loggedUser, "{$date}"));
            $i++;
        }
        if (count($logarray) > 0) {
            $customerObj = new customer();
            $customerObj->logInsert('customer_log', $logarray);
        }
    }

    /**
     * To get rendered  log data html
     * @param  integer $customerId
     * @return json
     */
    public function logData($customerId) {
        $customerObj = new customer();
        $logData = $customerObj->logData($customerId);
        $data = array('logdata' => $logData);
        $returnHTML = view('customer/logData', $data)->render();
        return response()->json(array('status' => true, 'content' => $returnHTML));
    }

    /**
     * To get rendered document data html 
     * @param  integer $customerId
     * @return json
     */
    public function documentData($customerId) {
        $customerDocObj = new customerDocument();
        $documentDetails = $customerDocObj->documentData($customerId);
        $data = array('documentData' => $documentDetails, 'customerId' => $customerId);
        $returnHTML = view('customer/documentData', $data)->render();
        return response()->json(array('status' => true, 'content' => $returnHTML));
    }

    /**
     * To get the rendered document html
     * @param integer $customerId
     * @return json
     */
    public function documentForm($customerId) {
        $documentType = DB::table('dp_document_type')->distinct()->orderBy('id')->pluck('title', 'id')->toArray();
        $data = array('customerId' => $customerId, 'documentType' => $documentType);
        $returnHTML = view('customer/addDocument', $data)->render();
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    /**
     * To save the document detail and log data
     * @param Request $request
     * @param integer $customerId
     * @return type
     */
    public function documentDetailSave(Request $request, $customerId) {
        $files = $request->file('document_file');
        $insertArray = [];
        $type = $request->get('documenttype_oid');
        $comment = $request->get('document_comment');
        $policyId = $request->get('policy_id', null);
        $filename = [];
        $datetime = date('Y-m-d h:i');
        if ($policyId > 0 && $policyId != null) {
            File::isDirectory('uploads/' . $customerId . "/document/" . $policyId . "/") or File::makeDirectory('uploads/' . $customerId . "/document/" . $policyId . "/", 0777, true, true);
        } else {
            File::isDirectory('uploads/' . $customerId . "/document/") or File::makeDirectory('uploads/' . $customerId . "/document/", 0777, true, true);
        }

        foreach ($files as $uploadedfile) {
            if ($policyId > 0 && $policyId != null) {
                $destinationPath = 'uploads/' . $customerId . "/document/" . $policyId . "/";
            } else {
                $destinationPath = 'uploads/' . $customerId . "/document/";
            }
            $path_parts = pathinfo($uploadedfile->getClientOriginalName());
            // $newfilename = $path_parts['filename'] . "_" . date('Ymdhis') . '.' . $path_parts['extension'];
            $newfilename = $path_parts['filename'] . '.' . $path_parts['extension'];
            $filename[] = $newfilename;
            $uploadedfile->move($destinationPath, $newfilename);
            $insertArray[] = array("customer_id" => $customerId,
                "file_name" => $newfilename,
                "type" => $type,
                "comment" => $comment,
                "upload_by" => Auth::user()->id,
                "upload_at" => $datetime,
                "policy_id" => $policyId
            );
        }

        if (count($insertArray) > 0) {
            DB::table('dp_customer_document')->insert($insertArray); // Query Builder approach
            //insert log entry
            $logarray = array("customer_id" => $customerId,
                "title" => "Following documents are uploaded: " . implode(',', $filename),
                "old_value" => '',
                "edited_by" => Auth::user()->id,
                "updated_at" => $datetime);
            $customerObj = new customer();
            $customerObj->logInsert('customer_log', $logarray);
        }
        $message = ($policyId != null || $policyId > 0) ? 'Successfully upload policy documents!' : 'Successfully upload customer documents!';
        Session::put('policyoverviewtabselected', 'document');

        return back()->with(['success' => 'Successfully upload customer documents!', 'policyoverviewtabselected' => 'document']);
    }

    /**
     * To delete document data 
     * @param Request $request
     * @param integer $customerId
     * @return json
     */
    public function documentDelete(Request $request, $customerId) {
        $whereArray[] = ['id', '=', $request->get('docId')];
        $whereArray[] = ['customer_id', '=', $customerId];
        $documentDetails = DB::table('dp_customer_document')->where($whereArray)->pluck('file_name')->toArray();

        DB::table('dp_customer_document')->where($whereArray)->delete();

        //insert log entry
        $logarray = array("customer_id" => $customerId,
            "title" => "Document '" . $documentDetails[0] . "' was deleted",
            "old_value" => '',
            "edited_by" => Auth::user()->id,
            "updated_at" => date('Y-m-d h:i'));
        $customerObj = new customer();
        $customerObj->logInsert('customer_log', $logarray);

        Session::flash('success', 'Successfully deleted document');
        return response()->json(array('success' => true));
    }

    /**
     * To get the document edit form
     * @param  integer $customerId
     * @param  integer $documentId
     * @return json
     */
    public function documentEdit($customerId, $documentId) {
        $whereArray[] = ['id', '=', $documentId];
        $whereArray[] = ['customer_id', '=', $customerId];
        $documentType = DB::table('dp_document_type')->distinct()->orderBy('id')->pluck('title', 'id')->toArray();
        $documentDetails = DB::table('dp_customer_document')->where($whereArray)->get()->first();
        $data = array('documentdata' => $documentDetails, 'customerId' => $customerId, 'documentId' => $documentId, 'documentType' => $documentType);
        $returnHTML = view('customer/editDocument', $data)->render();
        return response()->json(array('status' => true, 'content' => $returnHTML));
    }

    /**
     * To update document data
     * @param  Request $request
     * @param  integer $customerId
     * @param  integer $documentId
     * @return json
     */
    public function documentDataEdit(Request $request, $customerId, $documentId) {
        $insertArray = [];
        $type = $request->get('documenttype_oid');
        $comment = $request->get('document_comment');
        $filename = [];
        $datetime = date('Y-m-d h:i');
        $documentObj = customerDocument::where('id', $documentId)
                        ->where('customer_id', $customerId)->first();

        $logarray = [];
        if ($documentObj->type != $type && $type != '') {
            //document type changed 
            $documentTypeObj = App\Documenttype::where('id', $type)->first();
            $documentOldTypeObj = ($documentObj->type != '') ? App\Documenttype::where('id', $documentObj->type)->first() : '';
            $dataArray = [$customerId, "'" . $documentObj->file_name . "' document's type was changed to " . $documentTypeObj->title, (($documentOldTypeObj != '') ? $documentOldTypeObj->title : ''), $datetime];
            $logarray[] = $this->logarrayCreation($dataArray);
        }
        if ($documentObj->comment != $comment && $comment != '') {
            $dataArray = [$customerId, "'" . $documentObj->file_name . "' document's comment was changed to " . $comment, $documentObj->comment, $datetime];
            $logarray[] = $this->logarrayCreation($dataArray);
        }

        $documentObj->type = $request->get('documenttype_oid');
        $documentObj->comment = $request->get('document_comment');
        $documentObj->save();

        //insert log entry
        if (count($logarray) > 0) {
            $customerObj = new customer();
            $customerObj->logInsert('customer_log', $logarray);
        }

        Session::flash('success', 'Successfully updated document data');
        return response()->json(array('status' => true));
    }

    /**
     * To create log insert array
     * @param array $dataArray
     * @return type
     */
    private function logarrayCreation($dataArray) {

        $logarray = array("customer_id" => $dataArray[0],
            "title" => $dataArray[1],
            "old_value" => $dataArray[2],
            "edited_by" => Auth::user()->id,
            "updated_at" => $dataArray[3]);

        return $logarray;
    }

    /**
     * Add new contact person details
     * @param integer $customerId
     * @return type
     */
    public function addContactPerson($customerId) {
        $data = array('customerId' => $customerId);
        $returnHTML = view('customer/addcontactperson', $data)->render();
        return response()->json(array('status' => true, 'content' => $returnHTML));
    }

    /**
     * To save contact person info
     * @param Request $request
     * @param integer $customerId
     * @return type
     */
    public function saveContactPerson(Request $request, $customerId) {
        //Contact person detail of the client
        $customerObj = customer::find($customerId);
        $this->addContactPersonInfo($customerObj, $request, $customerId);

        return redirect()->route('customeroverview', $customerId)->with('success', 'Successfully add contact details!');
    }

    /**
     * To render edit contact person info html
     * @param integer $customerId
     * @param integer $contactId
     * @return type
     */
    public function editContactPerson($customerId, $contactId) {

        $contactPersonDetails = App\contact_person::where('id', $contactId)->get()->first();
        $data = array('customerId' => $customerId, 'contactId' => $contactId, 'contactperson' => $contactPersonDetails);
        $returnHTML = view('customer/addcontactperson', $data)->render();
        return response()->json(array('status' => true, 'content' => $returnHTML));
    }

    /**
     * To update contact person details
     * @param Request $request
     * @param integer $customerId
     * @param integer $contactId
     * @return type
     */
    public function updatecontactPerson(Request $request, $customerId, $contactId) {
        $customerObj = customer::find($customerId);
        $this->addContactPersonInfo($customerObj, $request, $customerId, $contactId);

        return redirect()->route('customeroverview', $customerId)->with('success', 'Successfully edit contact details!');
    }

    /**
     * To render delete html
     * @param integer $customerId
     * @param integer $contactId
     * @return type
     */
    public function deleteContatctPersonConfirm($customerId, $contactId) {
        $data = array('customerId' => $customerId, 'contactId' => $contactId);
        $returnHTML = view('customer/deletecontactperson', $data)->render();
        return response()->json(array('status' => true, 'content' => $returnHTML));
    }

    /**
     * To delete contact person info
     * @param type $customerId
     * @param type $contactId
     * @return type
     */
    public function deleteContatctPerson($customerId, $contactId) {
        DB::table('customer_contact_person_info')->where('id', '=', $contactId)->delete();
        $logarray = array("customer_id" => $customerId,
            "title" => 'Delete contact person',
            "old_value" => null,
            "edited_by" => Auth::user()->id,
            "updated_at" => date('Y-m-d h:i'));
        $customerObj = new customer();
        $customerObj->logInsert('customer_log', $logarray);
        return redirect()->route('customeroverview', $customerId)->with('success', 'Successfully delete contact details!');
    }

    public function addcustomerAddress($customerId) {
        $data = array('customerId' => $customerId);
        $returnHTML = view('customer/addcustomeraddress', $data)->render();
        return response()->json(array('status' => true, 'content' => $returnHTML));
    }

    public function saveContactAddress(Request $request, $customerId) {
        //Contact person detail of the client
        $customerObj = customer::find($customerId);
        $this->addAddressData($customerObj, $request, $customerId);
        return redirect()->route('customeroverview', $customerId)->with('success', 'Successfully add address details!');
    }

    /**
     * To render delete html
     * @param integer $customerId
     * @param integer $addressId
     * @return type
     */
    public function deleteContatctAddressConfirm($customerId, $addressId) {
        $data = array('customerId' => $customerId, 'addressId' => $addressId);
        $returnHTML = view('customer/deletecontactaddress', $data)->render();
        return response()->json(array('status' => true, 'content' => $returnHTML));
    }

    /**
     * To delete contact address info
     * @param type $customerId
     * @param type $addressId
     * @return type
     */
    public function deletecontactAddress($customerId, $addressId) {
        DB::table('customer_address')->where('id', '=', $addressId)->delete();
        $logarray = array("customer_id" => $customerId,
            "title" => 'Delete contact address',
            "old_value" => null,
            "edited_by" => Auth::user()->id,
            "updated_at" => date('Y-m-d h:i'));
        $customerObj = new customer();
        $customerObj->logInsert('customer_log', $logarray);
        return redirect()->route('customeroverview', $customerId)->with('success', 'Successfully delete contact address!');
    }

    /**
     * To render edit contact person info html
     * @param integer $customerId
     * @param integer $addressId
     * @return type
     */
    public function editContactAddress($customerId, $addressId) {

        $contactPersonDetails = App\customer_address::where('id', $addressId)->get()->first();
        $data = array('customerId' => $customerId, 'addressId' => $addressId, 'customers' => $contactPersonDetails);
        $returnHTML = view('customer/addcustomeraddress', $data)->render();
        return response()->json(array('status' => true, 'content' => $returnHTML));
    }

    /**
     * To update contact address details
     * @param Request $request
     * @param integer $customerId
     * @param integer $addressId
     * @return type
     */
    public function updatecontactAddress(Request $request, $customerId, $addressId) {
        $customerObj = customer::find($customerId);
        $this->addAddressData($customerObj, $request, $customerId, $addressId);


        return redirect()->route('customeroverview', $customerId)->with('success', 'Successfully edit contact address details!');
    }

    /**
     * To download customer uploaded files
     * @param integer $customerId
     * @param string $filename
     * @return type
     */
    public function getCustomerDownload($customerId, $policyId = 0, $filename) {
        //PDF file is stored under project/public/download/info.pdf
        try {
            if ($policyId > 0) {
                $file = public_path() . "/uploads/" . $customerId . "/document/" . $policyId . "/" . $filename;
                if (!is_file($file)) {
                    throw new FileNotFoundException($file);
                }
            } else {
                $file = public_path() . "/uploads/" . $customerId . "/document/" . $filename;
                if (!is_file($file)) {
                    throw new FileNotFoundException($file);
                }
            }
        } catch (Exception $e) {

            if ($e instanceof \Illuminate\Contracts\Filesystem\FileNotFoundException) {
                abort(404);
            }
            return parent::render($request, $exception);
        }

        $headers = array(
            'Content-Type: application/pdf',
        );
        return Response::download($file, $filename, [], 'attachment');
    }

    /**
     * To download files
     * @param integer $customerId
     * @param integer $type
     * @param integer $crmId
     * @param string  $filename
     * @return type
     */
    public function getFileDownload(Request $request, $customerId, $type = 'upload', $crmId = 0, $filename, $policyId) {
        $file = '';


        switch ($type) {
            case 'brokingslip':
                $file = public_path() . "/uploads/brokingslip/" . $customerId . "/" . $crmId . "/" . $filename;
                break;
            case 'quote':
                $file = public_path() . "/uploads/" . $customerId . "/Quotes/" . $filename;
                break;
            case 'comparison':
                $file = public_path() . "/uploads/" . $customerId . "/document/" . $filename;
                break;
            case 'invoice':

                $file = public_path() . "/uploads/Invoice/" . $customerId . "/" . $policyId . "/" . $filename;
                break;
            case 'payment':

                $file = public_path() . "/uploads/" . $customerId . "/payments/" . $filename;
                break;

            default:
                $file = public_path() . "/uploads/" . $customerId . "/document/" . $crmId . "/" . $filename;
                break;
        }

        return Response::download($file, $filename, [], 'attachment');
    }

    /**
     * To get customer quotes details
     * @param integer $customerId
     * @return type
     */
    public function customerquoteDetails($customerId) {
        $quoteData = DB::table('quote')
                        ->join('broking_slip_info', 'broking_slip_info.quotes_id', '=', 'quote.id')
                        ->join('insurer_details', 'insurer_details.id', '=', 'quote.company_id')
                        ->join('insurance_product', 'insurance_product.id', '=', 'broking_slip_info.product_id')
                        ->leftJoin('users as u', 'quote.upload_by', '=', 'u.id')
                        ->select('quote.*', 'insurer_details.insurer_name', 'quote.id as mainId', 'insurance_product.product_name', 'u.name as uploadedBy')
                        ->where('quote.customer_id', '=', $customerId)->orderBy('quote.created_at', 'desc')->get();


        $data = array('quoteData' => $quoteData);
        $returnHTML = view('customer/listquotes', $data)->render();
        return response()->json(array('status' => true, 'content' => $returnHTML));
    }

    /**
     * TO get comparison upload form
     * @param integer $customerId
     * @param integer $crmId
     * @return type
     */
    public function comparisonUploadForm($customerId, $crmId) {
        $data = array('customerId' => $customerId, 'crmId' => $crmId);
        $returnHTML = view('quote/addcomparisonDoc', $data)->render();
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    /**
     * To send issuance document
     * @param Request $request
     * @param integer $customerId
     * @param integer $crmId
     * @param integer $quoteId
     * @return type
     */
    public function sendIssuanceMailDocument(Request $request, $customerId, $crmId, $quoteId) {

        // save file details
        $files = $request->file('quote_related_file');
        $datetime = date("Y-m-d h:i");
        $filename = [];
        File::isDirectory('uploads/' . $customerId . "/document/") or File::makeDirectory('uploads/' . $customerId . "/document/", 0777, true, true);
        $attachments = [];
        $insertArray = [];
        foreach ($files as $uploadedfile) {
            $destinationPath = 'uploads/' . $customerId . "/document/";
            $path_parts = pathinfo($uploadedfile->getClientOriginalName());
            $newfilename = $path_parts['filename'] . "_" . date('Ymdhis') . '.' . $path_parts['extension'];
            $filename[] = $newfilename;
            $uploadedfile->move($destinationPath, $newfilename);

            $insertArray[] = array("customer_id" => $customerId,
                "filename" => $newfilename,
                "document_type" => 9,
                "comment" => '',
                "uploaded_by" => Auth::user()->id,
                "uploaded_at" => $datetime,
                "crm_id" => $crmId
            );

            $attachments[] = array('as' => $newfilename, 'mime' => File::mimeType($destinationPath . $newfilename), 'filename' => $destinationPath . $newfilename);
        }


        $user_data['to_data'] = $request->get('to_data');
        $user_data['cc_data'] = $request->get('cc_data');
        $user_data['subject'] = $request->get('subject');
        $user_data['message'] = $request->get('message');
        $user_data['attach'] = $attachments;

        // send mail to the address

        if (count($user_data['attach']) > 0) {

            Mail::send('emails.brokesliptemplate', ['maildata' => $user_data], function ($m) use ($user_data) {
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

            DB::table('customer_crm_documents')->insert($insertArray);


            $logArray = array("crm_id" => $crmId,
                "title" => 'Issuance document was send',
                "edited_by" => Auth::user()->id,
                "old_value" => '',
                "edited_at" => $datetime);
            DB::table('crm_log')->insert($logArray);

            $updatesArray = array(
                'send_file_flag' => 1
            );
            DB::table('quote')->where('id', $quoteId)
                    ->update($updatesArray);

            return back()->with(['success' => 'successfully send issuance documents', 'overviewtabselected' => 'quote']);
        } else {
            return back()->with(['error' => 'Mail sending is failed', 'overviewtabselected' => 'quote']);
        }
    }

    /**
     * To search customer
     * @param Request $request
     * @return type
     */
    public function searchCustomer(Request $request, $type = 'customer') {

        $searchValue = $request->get('search');
        $customerDetails = [];
        if (trim($searchValue) != '') {
            $customerInfo = DB::table('customers')
                    ->where('name', 'like', "%$searchValue%");
            if ($type == 'customer') {
                $customerInfo->where('policy_flag', '=', 1);
            } else if ("leads") {
                $customerInfo->where('policy_flag', '=', 0);
            }

            $customerDetails = $customerInfo->select('id', 'name')->get();
        }

        return response()->json($customerDetails);
    }

    /**
     * To get customer connection form
     * @param integer $customerId
     * @return type
     */
    public function getcustomerconnectionForm($customerId) {
        $wherearray[] = ['status', '=', '1'];
        $wherearray[] = ['id', '!=', $customerId];

        $allCustomers = DB::table('customers')->distinct()->where($wherearray)->orderBy('id')->pluck('name', 'id')->toArray();
        $data = array('customerId' => $customerId, 'allCustomers' => $allCustomers);
        $returnHTML = view('customer/customerconnectionform', $data)->render();
        return response()->json(array('status' => true, 'content' => $returnHTML));
    }

    /**
     * To save customer connection details
     * @param Request $request
     * @param integer $customerId
     * @return type
     */
    public function savecustomerconnectionDetails(Request $request, $customerId) {
        $insertArray['customer_id'] = $customerId;
        $insertArray['related_customer_id'] = $request->get('related_customer_id');
        $insertArray['relation_type'] = $request->get('customer_relation_type');
        $insertArray['description'] = $request->get('customer_relation_description');
        DB::table('customers_connection')->insert($insertArray);
        $logarray = array("customer_id" => $customerId,
            "title" => 'Customer relation detail was added ',
            "old_value" => '',
            "edited_by" => Auth::user()->id,
            "updated_at" => date('Y-m-d h:i'));
        DB::table('customer_log')->insert($logarray);
        return redirect()->route('customeroverview', $customerId)->with('success', 'Successfully add related customer details!');
    }

    /**
     * TO get customer connection details
     * @param integer $customerId
     * @param integer $connectionid
     * @return type
     */
    public function getcustomerconnectionDetails($customerId, $connectionid) {
        $whereArray[] = ['cn.customer_id', '=', $customerId];
        $whereArray[] = ['cn.id', '=', $connectionid];
        $customerRelationDetails = DB::table('customers_connection as cn')
                        ->join('customers as cs', 'cn.related_customer_id', '=', 'cs.id')
                        ->select('cn.*', 'cs.*', 'cn.id as mainId')
                        ->where($whereArray)->first();
        $data = array('customerId' => $customerId, 'connectionid' => $connectionid, 'customerdetails' => $customerRelationDetails);
        $returnHTML = view('customer/editcustomerconnectionform', $data)->render();
        return response()->json(array('status' => true, 'content' => $returnHTML));
    }

    /**
     * To update connection details
     * @param Request $request
     * @param integer $customerId
     * @param integer $connectionid
     * @return type
     */
    public function updateConnectionDetails(Request $request, $customerId, $connectionid) {
        $connectionObj = App\CustomersConnection::find($connectionid);
        $connectionObj->relation_type = $request->get('customer_relation_type');
        $connectionObj->description = $request->get('customer_relation_description');
        $connectionObj->save();
        $logarray = array("customer_id" => $customerId,
            "title" => 'Customer relation detail was updated ',
            "old_value" => '',
            "edited_by" => Auth::user()->id,
            "updated_at" => date('Y-m-d h:i'));
        DB::table('customer_log')->insert($logarray);
        return redirect()->route('customeroverview', $customerId)->with('success', 'Successfully update related customer details!');
    }

    /**
     * To delete connection 
     * @param integer $customerId
     * @param integer $connectionid
     * @return type
     */
    public function deleteConnection($customerId, $connectionid) {
        $connectionObj = App\CustomersConnection::find($connectionid)->delete();
        Session::flash('success', 'Successfully deleted connetion data');
        $loggedUser = Auth::user()->id;
        $date = date('Y-m-d h:i');
        $logarray = array("customer_id" => $customerId,
            "title" => 'Customer relation deleted ',
            "old_value" => '',
            "edited_by" => Auth::user()->id,
            "updated_at" => date('Y-m-d h:i'));
        DB::table('customer_log')->insert($logarray);
        return response()->json(array('success' => true));
    }

    /**
     * To update flag details
     * @param integer $type
     * @param integer $id
     * @param integer $flag
     * @return type
     */
    public function UpdateFlagDetails($type = 'brokingslip', $id, $flag) {
        $requestObj = crmMain::find($id);
        $newFlag = ($flag == 1) ? 0 : 1;
        if ($type == 'brokingslip') {
            $requestObj->broking_slip_notification_flag = $newFlag;
        } else {
            $requestObj->quotes_notification_flag = $newFlag;
        }
        $title = ($type == 'brokingslip') ? 'Broking slip notification flag was changed' : 'Quote notification flag was changed';
        $requestObj->save();
        if ($type != 'brokingslip') {
            DB::table('broking_slip_info')->where('crm_id', $id)->update(['reminder_flag' => $newFlag]);
        }

        $logarray = array("crm_id" => $id,
            "title" => $title,
            "edited_by" => Auth::user()->id,
            "old_value" => $flag,
            "edited_at" => date('Y-m-d h:i'));

        DB::table('crm_log')->insert($logarray);
        Session::flash('success', $title);

        return response()->json(array('success' => true));
    }

    /**
     * To change the notification flag of broken slip
     * @param Request $request
     * @return type
     */
    public function changeNotificationFlag(Request $request) {
        $formValues = $request->get('formValues');
        $logArray = [];
        $whereArray = [];
        $editedTime = date('Y-m-d h:i:s');
        foreach ($formValues as $values) {
            foreach ($values as $key => $iterateValue) {
                if ($key == 'name') {
                    $nameArray = explode("_", $iterateValue);
                    $logArray[] = array("crm_id" => $nameArray[1],
                        "title" => "Ouote notification flag was changed",
                        "old_value" => 0,
                        "edited_by" => Auth::user()->id,
                        "edited_at" => $editedTime);
                } else {
                    $whereArray[] = ['id', $iterateValue];
                }
            }
        }
        if (count($whereArray) > 0) {
            DB::table('broking_slip_info')->where($whereArray)->update(['reminder_flag' => 0]);
            DB::table('crm_log')->insert($logArray);
        }
        Session::flash('success', 'Successfully updated notification flag!!!!');

        return response()->json(array('success' => true));
    }

    /**
     * To list all issued policy detail of a customer
     * @param type $customerId
     * @return type
     */
    public function getPolicylistingData($customerId) {

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
                                GROUP BY pobj.policy_id) as objectdetails"), DB::raw("(case policies.policy_status when 0 then 'Saved' when 1 then 'Post policy' when 2 then 'Policy issued'  when  4 then 'Expired' when 5 then 'Renewed' end) AS statusString"))
                        ->where("policies.customer_id", $customerId)
                        ->whereIn('policies.policy_status', [2, 4, 5])
                        ->orderBy('policies.updated_at', 'desc')->get();


        $returnHTML = view('customer/policyList', array('allpolicies' => $allPolicies, 'customerId' => $customerId))->render();
        return response()->json(array('status' => true, 'content' => $returnHTML));
    }

    /**
     * To calculate policy amount of a customer
     * @param integer $customerId
     * @return type
     */
    private function calculatePolicyAmount($customerId) {
        $whereCondition[] = ["p.customer_id", '=', $customerId];
        $whereCondition[] = ["p.policy_status", '>=', 2];
        $allPolicies = DB::table('policies as p')
                        ->join('customers as c', 'c.id', '=', 'p.customer_id')
                        ->join('policy_intallment as ins', 'p.id', '=', 'ins.policy_id')
                        ->leftJoin('policy_endorsement as pe', 'pe.id', '=', 'ins.endorsement_id')
                        ->select(DB::raw('(select SUM(dins.amount) from policy_intallment as dins join policies as dp on dp.id =dins.policy_id where dp.customer_id=' . $customerId . ' and dins.installment_type=1 and dp.policy_status >=2) as grossAmount'), DB::raw('(select SUM(dins.amount) from policy_intallment as dins join policies as dp on dp.id =dins.policy_id where dp.customer_id=' . $customerId . ' and dins.installment_type=2 and dp.policy_status >=2) as endorsementAmount'), DB::raw("SUM(((ins.amount)/100 )* p.commision) as commision"), DB::raw("SUM(p.additional_amount) as additionAmount"))
                        ->where($whereCondition)
                        ->where(function ($query) {
                            $query->where('pe.endorsement_status', '=', 2)
                            ->orWhere('pe.endorsement_status', '=', null);
                        })
                        ->whereNotNull('p.policy_number')
                        ->orderBy('p.updated_at', 'desc')
                        ->groupBy('p.customer_id')->first();

        return $allPolicies;
    }

    /**
     * To calculate the active policy amount of the customer
     * @param integer $customerId
     * @return type
     */
    private function activePolicyDetails($customerId) {
        $whereCondition[] = ["p.customer_id", '=', $customerId];
        $whereCondition[] = ["p.policy_status", '=', 2];
        $activePolicies = DB::table('policies as p')
                        ->join('customers as c', 'c.id', '=', 'p.customer_id')
                        ->join('policy_intallment as ins', 'p.id', '=', 'ins.policy_id')
                        ->leftJoin('policy_endorsement as pe', 'pe.id', '=', 'ins.endorsement_id')
                        ->select(DB::raw('(select SUM(dins.amount) from policy_intallment as dins join policies as dp on dp.id =dins.policy_id where dp.customer_id=' . $customerId . ' and dins.installment_type=1 and dp.policy_status =2) as grossAmount'), DB::raw('(select SUM(dins.amount) from policy_intallment as dins join policies as dp on dp.id =dins.policy_id where dp.customer_id=' . $customerId . ' and dins.installment_type=2 and dp.policy_status =2) as endorsementAmount'), DB::raw("SUM(((ins.amount)/100 )* p.commision) as commision"), DB::raw("SUM(p.additional_amount) as additionAmount"))
                        ->where($whereCondition)
                        ->where(function ($query) {
                            $query->where('pe.endorsement_status', '=', 2)
                            ->orWhere('pe.endorsement_status', '=', null);
                        })
                        ->whereNotNull('p.policy_number')
                        ->orderBy('p.updated_at', 'desc')
                        ->groupBy('p.customer_id')->first();

//            echo "<pre>";
//            print_r($activePolicies);exit;

        return $activePolicies;
    }

    /**
     * To calculate customer policy vat amount
     * @param integer $customerId
     * @return type
     */
    private function customerPolicyVatDetails($customerId) {
        $vatAmountDetails = array("active" => 0,
            "all" => 0);
        $vatAmountDetails["all"] = DB::table('policies as p')
                        ->leftJoin('policy_intallment as im', 'p.id', '=', 'im.policy_id')
                        ->leftJoin('policy_endorsement as pe', 'pe.id', '=', 'im.endorsement_id')
                        ->select(DB::raw("SUM(im.vat_amount) AS installmentVat"), DB::raw(" (select SUM(vat_amount) from policies p1 where p1.customer_id=$customerId group by p1.customer_id)  AS premiumVat"))
                        ->where("p.customer_id", "=", $customerId)
                        ->where(function ($query) {
                            $query->where('pe.endorsement_status', '=', 2)
                            ->orWhere('pe.endorsement_status', '=', null);
                        })
                        ->groupBy("p.customer_id")->first();

        $whereCondition[] = ["policies.customer_id", '=', $customerId];
        $whereCondition[] = ["policies.policy_status", '=', 1];

        $vatAmountDetails["active"] = DB::table('policies as p')
                        ->leftJoin('policy_intallment as im', 'p.id', '=', 'im.policy_id')
                        ->leftJoin('policy_endorsement as pe', 'pe.id', '=', 'im.endorsement_id')
                        ->select(DB::raw("SUM(im.vat_amount) AS installmentVat"), DB::raw("(select SUM(vat_amount) from policies p1 where p1.customer_id=$customerId and p1.policy_status=1)  AS premiumVat"))
                        ->where("p.customer_id", "=", $customerId)
                        ->where(function ($query) {
                            $query->where('pe.endorsement_status', '=', 2)
                            ->orWhere('pe.endorsement_status', '=', null);
                        })
                        ->groupBy("p.customer_id")->first();

        return $vatAmountDetails;
    }

    /**
     * To calculate customer policy vat amount
     * @param integer $customerId
     * @return type
     */
    public function searchUser(Request $request) {

        $searchValue = $request->get('username');
        $customerDetails = [];
        if (trim($searchValue) != '') {
            $userInfo = DB::table('users')
                    ->where('name', 'like', "%$searchValue%")
                    ->where('user_type', '=', "user");
            $customerDetails = $userInfo->select('id', 'name')->get();
        }

        return response()->json($customerDetails);
    }

    /**
     * To remove the CRM request of the particular customer
     * @param type $customerId
     * @param type $crmId
     * @return type
     */
    public function removeCrmRequest($customerId, $crmId) {

        $requestDetails = DB::table('crm_main_table')->where('id', $crmId)->pluck('crm_request_id')->toArray();

        DB::table('crm_main_table')->where('id', $crmId)->delete();

        //insert log entry
        $logarray = array("customer_id" => $customerId,
            "title" => "Crm request no: '" . $requestDetails[0] . "' was deleted",
            "old_value" => '',
            "edited_by" => Auth::user()->id,
            "updated_at" => date('Y-m-d h:i'));
        $customerObj = new customer();
        $customerObj->logInsert('customer_log', $logarray);

        Session::flash('success', 'Successfully deleted CRM');
        return response()->json(array('status' => true));
    }

    

}
