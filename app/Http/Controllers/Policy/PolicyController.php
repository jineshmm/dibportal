<?php

namespace App\Http\Controllers\Policy;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use App;
use Mail;
use App\customer;
use App\policy;
use App\Policy_product_details;
use App\ProductObject;
use App\PolicyInstallment;
use App\Http\Controllers\Controller;
use Session;
use PDF;
use File;



/**
 * To handle the policy related data
 * CRM Request Types are
 * Addition ,   CCHI Activation, Claim approval/Settlement ,Deletion ,Downgrade ,Updated member list ,Plate No Amendment ,Card Replacment ,CCHI Upload Status List ,MC Certificate ,Name Amendment ,
  Card Printer Request ,Invoices Request ,Upgrade, REQUEST ,INQUIRY ,announcement ,REQUEST SIGN ,Others

 * Policy status:: 0 then 'Saved' when 1 then 'Policy posted' when 2 then 'Policy issued' when 4 then 'Expired' when 5 then 'Renewed' when 6 then 'Rejected'
 *
 */
class PolicyController extends Controller {

    public $selectedTab = 'overview';

    /** 
     * To get new policy add page
     * @param integer $customerId
     * @param integer $crmId
     * @param integer $quoteId
     * @return type
     */
    public function addPolicy($customerId, $crmId, $quoteId) {
        $customerName = '';
        if ($customerId > 0) {
            $customerName = DB::table('customers')->where('id', '=', $customerId)->pluck('name')->toArray();
        }
        if (in_array('ROLE_FINANCE_MANAGER', Auth::user()->roles) || in_array('ROLE_FINANCE', Auth::user()->roles)) {
            $breadcrumbDetails = array('url' => action('Dashboard\DashboardController@dashboardFinancePolicyList'), 'title' => 'Policy');
        } else {
            $breadcrumbDetails = array('url' => action('Dashboard\DashboardController@technicalPolicyDetails'), 'title' => 'Policy');
        }
        $data = array('customerData' => $customerName, 'customerId' => $customerId, 'crmId' => $crmId, 'quoteId' => $quoteId, 'breadcrumb' => $breadcrumbDetails);

        return view('Policy/addPolicyForm', $data);
    }

    /**
     * To save insurance policy details step wise
     * @param Request $request
     * @return type
     */
    public function savePolicyDetails(Request $request) {
        $step = $request->get('policy_step', 0);
        $type = $request->get('step_type', 'next');
        $dateObj = date('Y-m-d H:i:s');
        $insuranceCompany = DB::table('insurer_details')->distinct()->where('status', '1')->orderBy('id')->pluck('insurer_name', 'id')->toArray();
        $userData = DB::table('users')->distinct()->where('status', '1')->orderBy('id')->pluck('name', 'id')->toArray();
        switch ($step) {

            case 1:
                //step 0 save and step1 show
                if ($type == 'next') {
                    if ($request->get('policy_id') > 0) {
                        $policyObj = policy::find($request->get('policy_id'));
                    } else {
                        $policyObj = new policy();
                    }
                    $policyObj->customer_id = $request->get('customer_id');
                    $policyObj->crm_id = $request->get('crmId');
                    $policyObj->created_at = $dateObj;
                    $policyObj->created_by = Auth::user()->id;
                    $policyObj->save();
                    $policyId = $policyObj->getKey();
                    $whereArray[] = ['customer_id', "=", $request->get('customer_id')];
                    $whereArray[] = ['policy_number', "!=", null];
                    $customerOldPolicies = DB::table('policies')->where($whereArray)->orderBy('id')->pluck('policy_number', 'id')->toArray();
                    $data = array('policyId' => $policyId, 'crmId' => $request->get('crmId'), 'insurancecompany' => $insuranceCompany, 'oldPolicies' => $customerOldPolicies);
                    $returnHTML = view('Policy/step2Form', $data)->render();
                    return response()->json(array('status' => true, 'content' => $returnHTML, 'step' => 2, 'backButton' => true, 'policyId' => $policyId));
                }

                break;
            case 2:

                if ($type == 'next') {
                    $policyObj = policy::find($request->get('policy_id'));
                    $policyObj->issue_date = date('Y-m-d', strtotime($request->get('policy_date_issue')));
                    $policyObj->start_date = date('Y-m-d', strtotime($request->get('policy_date_start')));
                    $policyObj->end_date = date('Y-m-d', strtotime($request->get('policy_date_end')));
                    $policyObj->sales_type = $request->get('policy_salestype');
                    $policyObj->insurer_id = $request->get('policy_insurer');
                    $policyObj->policy_type = $request->get('policy_type');
                    $policyObj->renewal_status = $request->get('policy_renewalstatus');

                    if ($request->get('policy_salestype') == 2) {
                        $policyObj->previous_policy_id = $request->get('old_policy_number');
                    } else {
                        $policyObj->previous_policy_id = null;
                    }

                    $policyObj->save();
                    //product details
                    $whereArray[] = ["status", "=", '1'];
                    $whereArray[] = ["category", "=", $request->get('policy_type')];
                    $insuranceProduct = DB::table('insurance_product')->distinct()->where($whereArray)->orderBy('id')->pluck('product_name', 'id')->toArray();
                    $data = array('policyId' => $request->get('policy_id'), 'productDetails' => $insuranceProduct);
                    $returnHTML = view('Policy/step3Form', $data)->render();

                    //update previous policy as renewed
                    if ($request->get('old_policy_number') != '' && $request->get('old_policy_number') != null) {
                        DB::table('policies')->where('id', $request->get('old_policy_number'))->update(array('policy_status' => 5));
                    }

                    return response()->json(array('status' => true, 'content' => $returnHTML, 'step' => 3, 'backButton' => true));
                }
                break;
            case 3:

                if ($type == 'next') {
                    //save product details
                    $this->saveProductDetails($request);
                    //save product object
                    $this->saveProductObjects($request);

                    $data = array('policyId' => $request->get('policy_id'), 'userDetails' => $userData, 'broker_commission' => $request->get('policy_commission'));
                    $returnHTML = view('Policy/step4Form', $data)->render();
                    return response()->json(array('status' => true, 'content' => $returnHTML, 'step' => 4, 'backButton' => true));
                }
                break;
            case 4:

                if ($type == 'next') {
                    $policyObj = policy::find($request->get('policy_id'));
                    $vat = $policyObj->tax;
                    $policyObj->gross_amount = $request->get('policy_premium_sum');
                    $policyObj->installment_number = $request->get('policy_installments');
                    $policyObj->collection_type = $request->get('policy_collection');
                    $policyObj->additional_amount = $request->get('policy_additional_amount', 0);

                    $policyObj->total_premium = floatval($request->get('policy_premium_sum') + $request->get('policy_additional_amount', 0));
                    $policyObj->vat_amount = floatval(( $policyObj->total_premium * $vat) / 100);
                    $policyObj->save();
                    //save commission amount
                    $this->saveCommissionData($request);
                    //save installmentdata
                    $this->saveInstallmentData($request);

                    Session::flash('success', 'Successfully added policy details!!!!');
                    $data = array('policyId' => $request->get('policy_id'), 'userDetails' => $userData, 'broker_commission' => $request->get('broker_commission'));

                    $returnHTML = view('Policy/step4Form', $data)->render();
                    return response()->json(array('status' => true, 'content' => $returnHTML, 'step' => 5, 'backButton' => true, 'returnUrl' => action('Policy\PolicyController@policyDetails', ['policyId' => $request->get('policy_id')])));
                }
                break;
        }
    }

    /**
     * To get the product details
     * @param Request $request
     * @return type
     */
    public function getProductfields(Request $request) {
        $product = $request->get('product');
        $productTitle = ucfirst($request->get('product-title'));
        $objectFlag = false;
        $objectHtml = '';
        switch ($product) {
            case 1:case 2:case 3:case 4:case 5:case 6:case 7:case 8:case 9:case 10:case 12:case 13:case 14:case 16:case 17:case 18:case 21:case 23:case 69:
                $returnHTML = view('Policy/generalproductfieldstplform', array('formtitle' => $productTitle))->render();
                break;
            case 28:case 29:case 31:case 32:case 33:case 34:case 37:case 39:
                $returnHTML = view('Policy/generalproductfieldstplform', array('formtitle' => $productTitle))->render();
                break;
            case 11:case 36:
                $returnHTML = view('Policy/medicalinsuranceproductfieldstplform', array('formtitle' => $productTitle))->render();
                break;
            case 25:case 26:
                $returnHTML = view('Policy/motorfleetproductfieldstplform', array('formtitle' => $productTitle))->render();
                break;
            case 19:case 20:
                $returnHTML = view('Policy/marineproductfieldstplform', array('formtitle' => $productTitle))->render();
                break;
            case 35:
                $returnHTML = view('Policy/propertyproductfieldstplform', array('formtitle' => $productTitle))->render();
                break;
            case 22:
                $returnHTML = view('Policy/medicalindividualproductfieldstplform', array('formtitle' => $productTitle))->render();
                break;
            case 15:
                $returnHTML = view('Policy/houseinsuranceproductfieldstplform', array('formtitle' => $productTitle))->render();
                $objectFlag = true;
                $objectHtml = view('Policy/propertyobjecttplform', array('formtitle' => 'Private property'))->render();
                break;
            case 24:
                $returnHTML = view('Policy/motorcomprehensiveindividualproductfieldstplform', array('formtitle' => $productTitle))->render();
                $objectFlag = true;
                $objectHtml = view('Policy/vehicleobjecttplform', array('formtitle' => 'Vehicle', 'singleFlag' => true))->render();
                break;
            case 27:
                $returnHTML = view('Policy/motortplindividualproductfieldstplform', array('formtitle' => $productTitle))->render();
                $objectFlag = true;
                $objectHtml = view('Policy/vehicleobjecttplform', array('formtitle' => 'Vehicle', 'singleFlag' => false))->render();
                break;
            case 30:
                $returnHTML = view('Policy/personalaccidentproductfieldstplform', array('formtitle' => $productTitle))->render();
                $objectFlag = true;
                $objectHtml = view('Policy/singlepersonobjecttplform', array('formtitle' => 'Person'))->render();
                break;
            case 38:
                $returnHTML = view('Policy/travelproductfieldstplform', array('formtitle' => $productTitle))->render();
                $objectFlag = true;
                $objectHtml = view('Policy/singlepersonobjecttplform', array('formtitle' => 'Person'))->render();
                break;
            default:
                $returnHTML = view('Policy/generalproductfieldstplform', array('formtitle' => $productTitle))->render();
        }



        return response()->json(array('status' => true, 'content' => $returnHTML, 'objectFlag' => $objectFlag, 'objectHtml' => $objectHtml));
    }

    /**
     * To save the commission details of the policy
     * @param type $request
     */
    private function saveCommissionData($request) {
        //calcualte company commission
        $grossAmount = $request->get('policy_premium_sum');
        $percentage = floatval($request->get('broker_commission', 0));
        $datetime = date('Y-m-d h:i:s');
        $commissionAmount = floatval(($grossAmount * $percentage) / 100);
        $insertArray[] = array("policy_id" => $request->get('policy_id'),
            "distributor_type" => 'diamond',
            "commission_type" => '0',
            "percentage" => $percentage,
            "amount" => $commissionAmount,
            "added_date" => $datetime,
            "sales_person_id" => 3
        );
        //add sales person commission
        if ($request->get('person_sales_person') != '') {

            if ($request->get('type_sales_person') == 0) {
                $insertArray[] = array("policy_id" => $request->get('policy_id'),
                    "distributor_type" => 'sales person',
                    "commission_type" => '0',
                    "percentage" => floatval($request->get('perc_sales_person')),
                    "amount" => $request->get('commission_sales_person'),
                    "added_date" => $datetime,
                    "sales_person_id" => $request->get('person_sales_person'),
                );
            } else {
                $insertArray[] = array("policy_id" => $request->get('policy_id'),
                    "distributor_type" => 'sales person',
                    "commission_type" => '1',
                    "percentage" => 0,
                    "amount" => floatval($request->get('commission_sales_person')),
                    "added_date" => $datetime,
                    "sales_person_id" => $request->get('person_sales_person'),
                );
            }
        }


        if (count($insertArray) > 0) {
            DB::table('policy_commission')->insert($insertArray); // Query Builder approach
        }
    }

    /**
     * To save the product details of the policy
     * @param type $request
     */
    private function saveProductDetails($request) {
        $policyObj = policy::find($request->get('policy_id'));
        $policyObj->product_id = $request->get('policy_product');
        $policyObj->coverage_info = $request->get('product_coverage');
        $policyObj->information = $request->get('product_coverage_info');
        $policyObj->commision = is_numeric($request->get('policy_commission')) ? $request->get('policy_commission') : 0;
        $policyObj->tax = $request->get('policy_tax');
        $policyObj->commercial_registration = $request->get('product_commercial_registration');
        $policyObj->no_of_members = $request->get('product_no_of_members');
        $policyObj->transportation_type = $request->get('product_transportation_type');
        $policyObj->fire_lightening = (!empty($request->get('product_fire_and_lightening'))) ? implode(",", $request->get('product_fire_and_lightening')) : null;
        $policyObj->fire_allied_perils = (!empty($request->get('product_fire_and_allied_perils'))) ? implode(",", $request->get('product_fire_and_allied_perils')) : null;
        $policyObj->id_number = $request->get('product_id_number');
        $policyObj->client_name = $request->get('product_name');
        $policyObj->risk_covered = $request->get('product_risk_covered');
        $policyObj->insured_sum = $request->get('product_insured_sum');
        $policyObj->deductable = $request->get('product_deductible');
        $policyObj->car_value = $request->get('product_car_value');
        $policyObj->property_coverage = $request->get('product_own_damage');
        $policyObj->third_party_liability = $request->get('product_third_party_liability');
        $policyObj->coverage = $request->get('product_individual_coverage');
        $policyObj->accident_sum_covered = $request->get('product_accident_sum');
        $policyObj->country_coverage = $request->get('product_country_coverage');
        $policyObj->type_of_coverage = $request->get('product_type_of_coverage');

        $policyObj->save();
        $policyproductObj = '';
        if (in_array($request->get('policy_product'), array(19, 20, 35, 22, 15, 30, 24, 38))) {
            $editFlag = $request->get('editFlag', 0);

            if ($editFlag) {

                $policyproductObjDetails = Policy_product_details::where('policy_id', '=', $request->get('policy_id'))->first();


                $newproductArray = ($policyproductObjDetails !== null) ? json_decode(json_encode($policyproductObjDetails), true) : array();

                $policyproductObj = (count($newproductArray) > 0) ? $policyproductObjDetails : new Policy_product_details();
            } else {

                $policyproductObj = new Policy_product_details();
                $policyproductObj::where('policy_id', $request->get('policy_id'))->delete();
            }
            $policyproductObj->policy_id = $request->get('policy_id');
            switch ($request->get('policy_product')) {
                case 19:case 20:
                    $policyproductObj->date_of_shipment = (!empty($request->input('product_date_shipment'))) ? date('Y-m-d', strtotime($request->get('product_date_shipment'))) : null;
                    $policyproductObj->kind_of_goods = $request->get('product_kind_of_goods');
                    $policyproductObj->type_of_cover = $request->get('product_type_cover');
                    $policyproductObj->terms_of_sale = json_encode($request->get('product_term_of_sale'));
                    break;
                case 35:
                    $policyproductObj->property_all_risks = $request->get('product_property_all_risk');
                    break;
                case 22:
                    $policyproductObj->sponsor_number = $request->get('product_sponsor_number');
                    $policyproductObj->dob = (!empty($request->input('product_dob'))) ? date('Y-m-d', strtotime($request->get('product_dob'))) : null;
                    break;
                case 15:
                    $policyproductObj->personal_property_coverage = $request->get('product_personal_property_coverage');
                    $policyproductObj->temporary_rental_cost_coverage = $request->get('product_temporary_rental_costs_coverage');
                    break;
                case 24:
                    $policyproductObj->no_of_passengers = $request->get('product_number_of_passenger');
                    break;
                case 30:
                    $policyproductObj->death_sum_covered = $request->get('product_death_sum');

                    break;
                case 38:
                    $policyproductObj->duration_coverage = $request->get('product_duration_of_coverage');
                    break;
            }
            $policyproductObj->save();
        }
    }

    /**
     * TO generate the default installment details of the policy
     * @param Request $request
     * @return type
     */
    public function generateInstallment(Request $request) {

        $installmentNumber = $request->get('installment_number');
        $policyObj = policy::find($request->get('policyId'));
        $vat = $policyObj->tax;
        $policyStartDate = $policyObj->start_date;
        $policyEndDate = $policyObj->end_date;
        $totalPremium = floatval($request->get('premium_amount') + $request->get('additional_amount', 0));

        $startDate = $policyStartDate;

        $installmentAmount = floatval($totalPremium / $installmentNumber);
        $incrementMonth = 12 / $installmentNumber;
        $intallmentArray = [];

        for ($i = 1; $i <= $installmentNumber; $i++) {
            $time = strtotime($startDate);
            $intallmentArray[$i]['startDate'] = date("Y-m-d", $time);
            $tobeAdded = $i * $incrementMonth;
            $endDate = date("Y-m-d", strtotime("+$tobeAdded month", strtotime($policyObj->start_date)));
            if ($endDate > date('Y-m-d', strtotime($policyEndDate))) {
                $endDate = date('Y-m-d', strtotime($policyEndDate));
            }
            $intallmentArray[$i]['endDate'] = $endDate;
            $startDate = $endDate;
            $intallmentArray[$i]['dueDate'] = date("Y-m-d", strtotime("+1 days", strtotime($endDate)));
            $intallmentArray[$i]['amount'] = $installmentAmount;
            $intallmentArray[$i]['vat_percentage'] = floatval($vat);
            $intallmentArray[$i]['vat_amount'] = ( ($installmentAmount * $vat) / 100 );
        }

        $returnHTML = '';
        if (count($intallmentArray) > 0) {
            $returnHTML = view('Policy/installmenttplform', array('installments' => $intallmentArray, 'totalPremium' => $totalPremium, 'installmentnumber' => $installmentNumber))->render();
        }


        return response()->json(array('status' => true, 'content' => $returnHTML));
    }

    /**
     * TO save the installment details of the policy
     * @param Request $request
     */
    private function saveInstallmentData(Request $request) {

        if ($request->get('installmentschedule_date') !== null && count($request->get('installmentschedule_date')) > 0) {
            $policyinstallObj = new PolicyInstallment();
            $whereArray[] = ['policy_id', '=', $request->get('policy_id')];
            $whereArray[] = ['installment_type', '=', 1];
            $whereArray[] = ['default_flag', '=', 0];
            $policyinstallObj::where($whereArray)->delete();
            $insertArray = [];
            $paidArray = [];
            $startDateArray = $request->get('installmentschedule_date');
            $endDateArray = $request->get('installmentschedule_date_end');
            $dueDateArray = $request->get('installmentschedule_date_due');
            $amountArray = $request->get('installmentschedule_sum');
            $paidStatus = $request->get('installmentschedule_paidstatus');
            $vatAmount = $request->get('installmentschedule_vatamount');
            $vatPercentage = $request->get('installmentschedule_vatpercentage');
            $paidAmount = $request->get('inst_paid_amount');



            for ($i = 1; $i <= count($request->get('installmentschedule_date')); $i++) {
                $insertArray[$i]['policy_id'] = $request->get('policy_id');
                $insertArray[$i]['start_date'] = date('Y-m-d', strtotime($startDateArray[$i]));
                $insertArray[$i]['end_date'] = date('Y-m-d', strtotime($endDateArray[$i]));
                $insertArray[$i]['due_date'] = date('Y-m-d', strtotime($dueDateArray[$i]));
                $insertArray[$i]['amount'] = floatval($amountArray[$i]);
                $insertArray[$i]['paid_status'] = isset($paidStatus[$i]) ? 1 : 0;
                if (isset($paidStatus[$i])) {
                    $paidArray[$i]['policy_id'] = $request->get('policy_id');
                    $paidArray[$i]['paid_amount'] = floatval($paidAmount[$i]);
                    $paidArray[$i]['paid_date'] = date('Y-m-d h:i');
                }
                $insertArray[$i]['installment_type'] = 1;
                $insertArray[$i]['vat_amount'] = floatval($vatAmount[$i]);
                $insertArray[$i]['vat_percentage'] = floatval($vatPercentage[$i]);
            }

            if (count($insertArray) > 0) {
                DB::table('policy_intallment')->insert($insertArray);
            }
            if (count($paidArray) > 0) {
                DB::table('policy_paid_details')->insert($paidArray);
            }
        }

        if ($request->get('policy_installments') == 0) {
            //insert one entry to the installment table
            $this->defaultInstallmentEntry($request);
        }
    }

    /**
     * To save the product object of the policy
     * @param type $request
     */
    private function saveProductObjects($request) {

        if (!empty($request->input('object_type'))) {
            $insertArray = [];
            $productObj = new ProductObject();
            $productObj::where('policy_id', $request->get('policy_id'))->delete();
            switch ($request->get('policy_product')) {
                case 15:

                    $insertArray[] = array('policy_id' => $request->get('policy_id'),
                        'object_type' => $request->get('object_type'),
                        'address' => $request->get('object_address'),
                        'property_type' => $request->get('object_property_type'),
                        'year_built' => $request->get('object_yearbuilt'),
                        'area' => $request->get('object_area'),
                        'construction_material' => $request->get('object_construction_material'));

                    break;
                case 24:case 27:

                    $makefieldValueArray = $request->get('object_make');
                    $modelfieldValueArray = $request->get('object_model');

                    $yearfieldValueArray = $request->get('object_year');
                    $licenseplatefieldValueArray = $request->get('object_license_plate');
                    $bodytypefieldValueArray = $request->get('object_body_type');
                    $grossweightfieldValueArray = $request->get('object_gross_weight');
                    $vincodefieldValueArray = $request->get('object_vincode');
                    $usagefieldValueArray = $request->get('object_usage');
                    $passengernumberfieldValueArray = $request->get('object_number_of_passengers');
                    $powerfieldValueArray = $request->get('object_power');
                    $coverageStartDate = $request->get('object_coverage_begin');
                    $coverageEndDate = $request->get('object_coverage_end');

                    if ($request->get('object_make') !== null) {
                        for ($i = 1; $i <= count($request->get('object_make')); $i++) {
                            $insertArray[$i]['policy_id'] = $request->get('policy_id');
                            $insertArray[$i]['object_type'] = $request->get('object_type');
                            $insertArray[$i]['make'] = $makefieldValueArray[$i];
                            $insertArray[$i]['model'] = $modelfieldValueArray[$i];
                            $insertArray[$i]['year'] = $yearfieldValueArray[$i];
                            $insertArray[$i]['license_plate'] = $licenseplatefieldValueArray[$i];
                            $insertArray[$i]['body_type'] = $bodytypefieldValueArray[$i];
                            $insertArray[$i]['gross_weight'] = $grossweightfieldValueArray[$i];

                            $insertArray[$i]['vin_code'] = $vincodefieldValueArray[$i];
                            $insertArray[$i]['vehicle_usage'] = $usagefieldValueArray[$i];
                            $insertArray[$i]['no_of_passengers'] = $passengernumberfieldValueArray[$i];
                            $insertArray[$i]['power'] = $powerfieldValueArray[$i];

                            $insertArray[$i]['coverage_start_date'] = (!empty($coverageStartDate[$i])) ? date('Y-m-d', strtotime($coverageStartDate[$i])) : null;
                            $insertArray[$i]['coverage_end_date'] = (!empty($coverageEndDate[$i])) ? date('Y-m-d', strtotime($coverageEndDate[$i])) : null;
                        }
                    }






                    break;
                case 30:case 38:
                    $firstNameArray = $request->get('object_last_name');
                    $lastNameArray = $request->get('object_middle_name');
                    $dobDateArray = $request->get('object_dob');
                    $genderArray = $request->get('object_gender');
                    $coverageStartDate = $request->get('object_coverage_begin');
                    $coverageEndDate = $request->get('object_coverage_end');

                    if ($request->get('object_last_name') !== null) {
                        for ($i = 1; $i <= count($request->get('object_last_name')); $i++) {

                            $insertArray[$i]['policy_id'] = $request->get('policy_id');
                            $insertArray[$i]['last_name'] = $firstNameArray[$i];
                            $insertArray[$i]['first_name'] = $lastNameArray[$i];
                            $insertArray[$i]['dob'] = date('Y-m-d', strtotime($dobDateArray[$i]));
                            $insertArray[$i]['object_type'] = $request->get('object_type');
                            $insertArray[$i]['gender'] = $genderArray[$i];
                            $insertArray[$i]['coverage_start_date'] = (!empty($coverageStartDate[$i])) ? date('Y-m-d', strtotime($coverageStartDate[$i])) : null;
                            $insertArray[$i]['coverage_end_date'] = (!empty($coverageEndDate[$i])) ? date('Y-m-d', strtotime($coverageEndDate[$i])) : null;
                        }
                    }


                    break;
            }

            if (count($insertArray) > 0) {
                DB::table('policy_product_object')->insert($insertArray);
            }
        }
    }

    /**
     * To get create policy form
     * @return type
     */
    public function createPolicy() {
        $allCustomers = DB::table('customers')->distinct()->where('status', '1')->orderBy('id')->pluck('name', 'id')->toArray();
        return view('Policy/addPolicyForm', array('allCustomers' => $allCustomers, 'customerId' => 0));
    }

    /**
     * To list policy details
     * @return type
     */
    public function listPolicy() {

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


        return view('Policy/policyList', array('allpolicies' => $allPolicies, 'customerId' => 0));
    }

    /**
     * To get policy details
     * @param integer $policyId
     * @return type
     */
    public function policyDetails($policyId) {


        if (session()->has('policyoverviewtabselected')) {
            $this->selectedTab = session()->get('policyoverviewtabselected');
        }

        $policyDetails = DB::table('policies')
                        ->join('customers', 'customers.id', '=', 'policies.customer_id')
                        ->leftJoin('crm_main_table as mt', 'policies.crm_id', '=', 'mt.id')
                        ->leftJoin('insurer_details', 'insurer_details.id', '=', 'policies.insurer_id')
                        ->leftJoin('insurance_product', 'insurance_product.id', '=', 'policies.product_id')
                        ->leftJoin('policy_product_details', 'policy_product_details.policy_id', '=', 'policies.id')
                        ->leftJoin('policies as pp', 'policies.previous_policy_id', '=', 'pp.id')
                        ->select('policies.*', 'pp.policy_number as previusPolicy', 'policy_product_details.*', 'insurer_details.insurer_name', 'policies.id as mainId', 'customers.name as customerName', 'insurance_product.product_name', DB::raw("(case policies.renewal_status when 0 then 'No' when 1 then 'Yes'   end) AS policystatusString"), 'mt.policy_schedule_flag')
                        ->where("policies.id", "=", $policyId)
                        ->orderBy('policies.updated_at', 'desc')->first();


        $commissionDetails = DB::table('policy_commission')
                        ->join('policies', 'policies.id', '=', 'policy_commission.policy_id')
                        ->leftJoin('users', 'users.id', '=', 'policy_commission.sales_person_id')
                        ->select('policy_commission.*', 'users.name as salesperson', DB::raw("SUM(policy_commission.amount) AS totalAmount"))
                        ->where("policy_commission.policy_id", "=", $policyId)
                        ->groupBy('policy_commission.distributor_type')
                        ->orderBy('policy_commission.id', 'desc')->get();




        $whereArray[] = ["policy_commission.policy_id", "=", $policyId];
        $whereArray[] = ["policy_commission.distributor_type", "=", 'diamond'];

        $companyRevanue = DB::table('policy_commission')
                        ->join('policies', 'policies.id', '=', 'policy_commission.policy_id')
                        ->leftJoin('users', 'users.id', '=', 'policy_commission.sales_person_id')
                        ->select('policy_commission.*', 'users.name as salesperson', DB::raw("SUM(policy_commission.amount) AS totalAmount"))
                        ->where($whereArray)
                        ->groupBy('policy_commission.distributor_type')
                        ->orderBy('policy_commission.id', 'desc')->first();



        $newrevanueArray = ($companyRevanue !== NULL) ? json_decode(json_encode($companyRevanue), true) : array();

        $companyCommission = (count($newrevanueArray) > 0) ? $companyRevanue->totalAmount : 0.00;

        //DOCUMENTS DETAILS
        $documentCount = DB::table('dp_customer_document as cd')
                        ->join('policies', 'policies.id', '=', 'cd.policy_id')
                        ->select('cd.id')
                        ->where("cd.policy_id", "=", $policyId)->count();

        $vatAmount = DB::table('policies as p')
                ->leftJoin('policy_intallment as im', 'p.id', '=', 'im.policy_id')
                ->leftJoin('policy_endorsement as pe', 'pe.id', '=', 'im.endorsement_id')
                ->select(DB::raw("SUM(im.vat_amount) AS installmentVat"), "p.vat_amount as premiumVat")
                ->where("p.id", "=", $policyId)
                ->where(function ($query) {
                    $query->where('pe.endorsement_status', '=', 2)
                    ->orWhere('pe.endorsement_status', '=', null);
                })
                ->first();

        //INSTALLMENT DETAILS
        $installmentDetails = DB::table('policy_intallment as im')
                        ->join('policies', 'policies.id', '=', 'im.policy_id')
                        ->leftJoin('policy_endorsement as pe', 'pe.id', '=', 'im.endorsement_id')
                        ->select('im.*', DB::raw("(case collect_by when '1' then 'Broker' when '2' then 'Insurer' when '3' then 'Retailer' else 'Premium financier' end) AS collectionString"), 'pe.endorsement_number')
                        ->where("policies.id", "=", $policyId)
                        ->where(function ($query) {
                            $query->where('pe.endorsement_status', '=', 2)
                            ->orWhere('pe.endorsement_status', '=', null);
                        })
                        ->orderBy('im.start_date', 'asc')->get();


        $coverageFields = $this->createFieldArray($policyDetails->product_id);
        $labelDetails = $this->coverageLabelDetails($policyDetails->product_id);
        $productObjectDetails = DB::table('policies')
                        ->leftJoin('policy_product_object', 'policy_product_object.policy_id', '=', 'policies.id')
                        ->leftJoin('insurance_product', 'insurance_product.id', '=', 'policies.product_id')
                        ->select('policies.*', 'policy_product_object.*', 'policy_product_object.id as objectId')
                        ->where("policies.id", "=", $policyId)
                        ->orderBy('policies.updated_at', 'desc')->get();

        $endorsementCount = DB::table('policy_endorsement')->select('id')->where('policy_id', $policyId)->count();

        $endorsementAmount = DB::table('policy_endorsement')->where('policy_id', $policyId)->where('endorsement_status', 2)->sum('amount');


        $claimCount = DB::table('policy_claims')->where('policy_id', $policyId)->count();

        $headTitle = 'Policy overview: ' . (empty($policyDetails->policy_number) ? '--- not issued ---' : $policyDetails->policy_number);
        $policyScheduleCount = DB::table('policy_schedule')->where('policy_id', $policyId)->count();
        $policyRequestCount = DB::table('crm_endorsement')->where('policy_id', $policyId)->count();


        if (in_array('ROLE_FINANCE_MANAGER', Auth::user()->roles) || in_array('ROLE_FINANCE', Auth::user()->roles)) {
            $breadcrumbDetails = array('url' => action('Dashboard\DashboardController@dashboardFinancePolicyList'), 'title' => 'Policy');
        } else {
            $breadcrumbDetails = array('url' => action('Dashboard\DashboardController@technicalPolicyDetails'), 'title' => 'Policy');
        }


        $data = array('policyDetails' => $policyDetails, 'commissionDetails' => $commissionDetails, 'companyRevanue' => $companyCommission, 'documentCount' => $documentCount, 'customerId' => $policyDetails->customer_id, 'installmentDetails' => $installmentDetails, 'coverageDetails' => $coverageFields, 'coverageLabel' => $labelDetails, 'objectDetails' => $productObjectDetails, 'headTitle' => $headTitle, 'overviewTab' => $this->selectedTab, 'endorsementCount' => $endorsementCount, 'vatAmount' => $vatAmount, 'endorsementamount' => $endorsementAmount, 'breadcrumb' => $breadcrumbDetails, 'policyscheduleCount' => $policyScheduleCount, 'claimCount' => $claimCount, 'policyrequestCount' => $policyRequestCount);

        return view('Policy/policyoverview', $data);
    }

    /**
     * To get field array
     * @param integer $productId
     * @return array
     */
    private function createFieldArray($productId) {

        switch ($productId) {
            case 1:case 2:case 3:case 4:case 5:case 6:case 7:case 8:case 9:case 10:case 12:case 13:case 14:case 16:case 17:case 18:case 21:case 23:
                $ProductFieldDetails = ['coverage_info', 'information'];
                break;
            case 28:case 29:case 31:case 32:case 33:case 34:case 37:case 39:
                $ProductFieldDetails = ['information', 'coverage_info'];
                break;
            case 11:case 36:
                $ProductFieldDetails = ['commercial_registration', 'no_of_members'];
                break;
            case 25:case 26:
                $ProductFieldDetails = ['commercial_registration', 'no_of_members'];
                break;
            case 19:case 20:
                $ProductFieldDetails = ['commercial_registration', 'transportation_type', 'date_of_shipment', 'kind_of_goods', 'type_of_cover', 'terms_of_sale'];
                break;
            case 35:
                $ProductFieldDetails = ['fire_lightening', 'fire_allied_perils', 'property_all_risks'];
                break;
            case 22:
                $ProductFieldDetails = ['client_name', 'id_number', 'dob', 'sponsor_number'];
                break;
            case 15:
                $ProductFieldDetails = ['risk_covered', 'insured_sum', 'deductable', 'personal_property_coverage', 'temporary_rental_cost_coverage'];
                break;
            case 24:
                $ProductFieldDetails = ['car_value', 'deductable', 'no_of_passengers'];
                break;
            case 27:
                $ProductFieldDetails = ['third_party_liability', 'property_coverage'];
                break;
            case 30:
                $ProductFieldDetails = ['coverage', 'accident_sum_covered', 'death_sum_covered'];
                break;
            case 38:
                $ProductFieldDetails = ['country_coverage', 'type_of_coverage', 'duration_coverage', 'no_of_members'];
                break;
            default:
                $ProductFieldDetails = array();
        }

        return $ProductFieldDetails;
    }

    /**
     * To coverage label details
     * @param integer $productId
     * @return array
     */
    private function coverageLabelDetails($productId) {
        $labelDetails = array();
        switch ($productId) {
            case 1:case 2:case 3:case 4:case 5:case 6:case 7:case 8:case 9:case 10:case 12:case 13:case 14:case 16:case 17:case 18:case 21:case 23:
                $labelDetails = ['Coverage info', 'Info'];
                break;
            case 28:case 29:case 31:case 32:case 33:case 34:case 37:case 39:
                $labelDetails = ['Coverage info', 'Info'];
                break;
            case 11:case 36:
                $labelDetails = ['Commercial registration', 'Number of members'];
                break;
            case 25:case 26:
                $labelDetails = ['Commercial registration', 'Number of members'];
                break;
            case 19:case 20:
                $labelDetails = ['Commercial registration', 'Transportation type', 'Date of shipment', 'Kind of goods', 'Type of cover', 'Terms of sale'];
                break;
            case 35:
                $labelDetails = ['Fire and lightening', 'Fire and allied perils', 'Property all risks'];
                break;
            case 22:
                $labelDetails = ['Name', 'Id number', 'Date of birth', 'Sponsor Number'];
                break;
            case 15:
                $labelDetails = ['Risk covered', 'Insured sum', 'Deductible', 'Personal property coverage', 'Temporary rental costs coverage'];
                break;
            case 24:
                $labelDetails = ['Car value', 'Deductible', 'Number of passengers'];
                break;
            case 27:
                $labelDetails = ['Third party liability', 'Property coverage'];
                break;
            case 30:
                $labelDetails = ['Coverage', 'Accident sum covered', 'Death sum covered'];
                break;
            case 38:
                $labelDetails = ['Country coverage', 'Type of coverage', 'Duration coverage', 'Number of members'];
                break;
            default:
                $labelDetails = array();
        }

        return $labelDetails;
    }

    /**
     * To get document details
     * @param integer $customerId
     * @param integer $policyId
     * @return type
     */
    public function documentDetails($customerId, $policyId) {
        //DOCUMENTS DETAILS
        $documentDetails = DB::table('dp_customer_document as cd')
                        ->join('policies', 'policies.id', '=', 'cd.policy_id')
                        ->leftJoin('users', 'users.id', '=', 'cd.upload_by')
                        ->leftJoin('dp_document_type as dt', 'dt.id', '=', 'cd.type')
                        ->select('cd.*', 'users.name as uploadedBy', 'dt.title as docType', 'cd.id as docId')
                        ->where("policies.id", "=", $policyId)
                        ->orderBy('cd.id', 'desc')->get();

        $data = array('documentDetails' => $documentDetails, 'policyId' => $policyId, 'customerId' => $customerId);
        $returnHTML = view('Policy/documentData', $data)->render();
        return response()->json(array('status' => true, 'content' => $returnHTML));
    }

    /**
     * To get object form details
     * @param type $policyId
     * @param type $productId
     * @param type $id
     * @return type
     */
    public function editObjectForm($policyId, $productId, $id = 0) {

        if ($id > 0) {
            $productObjectDetails = DB::table('policy_product_object as obj')
                    ->select('obj.*')
                    ->where("id", "=", $id)
                    ->first();
        } else {
            $productObjectDetails = array();
        }



        switch ($productId) {

            case 15:

                $objectHtml = view('Policy/propertyobjecttplform', array('formtitle' => 'Private property', 'objdata' => $productObjectDetails, 'policyId' => $policyId))->render();
                break;
            case 24:

                $objectHtml = view('Policy/vehicleobjecttplform', array('formtitle' => 'Vehicle', 'singleFlag' => true, 'objdata' => $productObjectDetails, 'policyId' => $policyId))->render();
                break;
            case 27:

                $objectHtml = view('Policy/vehicleobjecttplform', array('formtitle' => 'Vehicle', 'singleFlag' => false, 'objdata' => $productObjectDetails, 'policyId' => $policyId))->render();
                break;
            case 30:

                $objectHtml = view('Policy/singlepersonobjecttplform', array('formtitle' => 'Person', 'objdata' => $productObjectDetails, 'policyId' => $policyId))->render();
                break;
            case 38:

                $objectHtml = view('Policy/singlepersonobjecttplform', array('formtitle' => 'Person', 'objdata' => $productObjectDetails, 'policyId' => $policyId))->render();
                break;
            default:
                $objectHtml = '';
        }
        return response()->json(array('status' => true, 'content' => $objectHtml));
    }

    /**
     * To update object details
     * @param Request $request
     * @param integer $policyId
     * @param integer $objectId
     * @return type
     */
    public function updateObjectDetails(Request $request, $policyId, $objectId) {

        $insertArray = $this->createObjectValueArray($request, $policyId);
        if (count($insertArray) > 0) {
            DB::table('policy_product_object')->where('id', '=', $objectId)->update($insertArray);
        }
        $logArray[] = array("policy_id" => $policyId,
            "kind" => 'Policy object detail was changed',
            "edited_by" => Auth::user()->id,
            "oldvalue" => '',
            "edited_at" => date('Y-m-d h:i:s'));

        DB::table('policy_log')->insert($logArray);

        Session::flash('success', 'Successfully updated object data!!!!');
        return redirect()->route('policyoverview', ['policyId' => $policyId]);
    }

    /**
     * To update coverage info
     * @param integer $policyId
     * @param integer $productId
     * @return type
     */
    public function updateCoverageInfo($policyId, $productId) {

        $policyProductDetails = DB::table('policies')
                ->leftJoin('policy_product_details', 'policy_product_details.policy_id', '=', 'policies.id')
                ->leftJoin('insurance_product', 'insurance_product.id', '=', 'policies.product_id')
                ->select('policies.*', 'policy_product_details.*', 'insurance_product.product_name as productTitle', 'policies.id as mainId')
                ->where("policies.id", "=", $policyId)
                ->first();

        $headTitle = 'Policy: ' . (empty($policyProductDetails->policy_number) ? '--- not issued ---' : $policyProductDetails->policy_number) . ": Edit product info";

        $productTitle = ucfirst($policyProductDetails->productTitle);

        switch ($productId) {
            case 1:case 2:case 3:case 4:case 5:case 6:case 7:case 8:case 9:case 10:case 12:case 13:case 14:case 16:case 17:case 18:case 21:case 23:
                $returnHTML = view('Policy/generalproductfieldstplform', array('formtitle' => $productTitle, 'productData' => $policyProductDetails, 'headTitle' => $headTitle))->render();
                break;
            case 28:case 29:case 31:case 32:case 33:case 34:case 37:case 39:
                $returnHTML = view('Policy/generalproductfieldstplform', array('formtitle' => $productTitle, 'productData' => $policyProductDetails, 'headTitle' => $headTitle))->render();
                break;
            case 11:case 36:
                $returnHTML = view('Policy/medicalinsuranceproductfieldstplform', array('formtitle' => $productTitle, 'productData' => $policyProductDetails, 'headTitle' => $headTitle))->render();
                break;
            case 25:case 26:
                $returnHTML = view('Policy/motorfleetproductfieldstplform', array('formtitle' => $productTitle, 'productData' => $policyProductDetails, 'headTitle' => $headTitle))->render();
                break;
            case 19:case 20:
                $returnHTML = view('Policy/marineproductfieldstplform', array('formtitle' => $productTitle, 'productData' => $policyProductDetails, 'headTitle' => $headTitle))->render();
                break;
            case 35:
                $returnHTML = view('Policy/propertyproductfieldstplform', array('formtitle' => $productTitle, 'productData' => $policyProductDetails, 'headTitle' => $headTitle))->render();
                break;
            case 22:
                $returnHTML = view('Policy/medicalindividualproductfieldstplform', array('formtitle' => $productTitle, 'productData' => $policyProductDetails, 'headTitle' => $headTitle))->render();
                break;
            case 15:
                $returnHTML = view('Policy/houseinsuranceproductfieldstplform', array('formtitle' => $productTitle, 'productData' => $policyProductDetails, 'headTitle' => $headTitle))->render();
                break;
            case 24:
                $returnHTML = view('Policy/motorcomprehensiveindividualproductfieldstplform', array('formtitle' => $productTitle, 'productData' => $policyProductDetails, 'headTitle' => $headTitle))->render();
                break;
            case 27:
                $returnHTML = view('Policy/motortplindividualproductfieldstplform', array('formtitle' => $productTitle, 'productData' => $policyProductDetails, 'headTitle' => $headTitle))->render();
                break;
            case 30:
                $returnHTML = view('Policy/personalaccidentproductfieldstplform', array('formtitle' => $productTitle, 'productData' => $policyProductDetails, 'headTitle' => $headTitle))->render();
                break;
            case 38:
                $returnHTML = view('Policy/travelproductfieldstplform', array('formtitle' => $productTitle, 'productData' => $policyProductDetails, 'headTitle' => $headTitle))->render();
                break;
            default:
                $returnHTML = '';
        }


        $data = array('innerHtml' => $returnHTML, 'headTitle' => $headTitle, 'productData' => $policyProductDetails);

        return view('Policy/editCoverageForm', $data);
    }

    /**
     * To edit product info
     * @param Request $request
     * @param integer $policyId
     * @return type
     */
    public function editProductInfo(Request $request, $policyId) {

        $policyObj = policy::find($policyId);

        if ($policyObj->commision != $request->get('policy_commission')) {

            $this->recalculateCommission($request);
        }
        $this->saveProductDetails($request);

        $logArray[] = array("policy_id" => $policyId,
            "kind" => 'Policy product detail was changed',
            "edited_by" => Auth::user()->id,
            "oldvalue" => '',
            "edited_at" => date('Y-m-d h:i:s'));

        DB::table('policy_log')->insert($logArray);
        Session::flash('success', 'Successfully updated product data!!!!');
        return redirect()->route('policyoverview', ['policyId' => $policyId]);
    }

    /**
     * To edit policy info
     * @param type $policyId
     * @return type
     */
    public function editPolicyInfo($policyId) {
        $allCustomers = DB::table('customers')->distinct()->where('status', '1')->orderBy('id')->pluck('name', 'id')->toArray();
        $insuranceCompany = DB::table('insurer_details')->distinct()->where('status', '1')->orderBy('id')->pluck('insurer_name', 'id')->toArray();

        $policyDetails = DB::table('policies')
                ->join('customers', 'customers.id', '=', 'policies.customer_id')
                ->select('policies.*', 'policies.id as mainId', 'customers.name as customerName')
                ->where("policies.id", "=", $policyId)
                ->first();

        $headTitle = 'Edit: policy info: ' . (empty($policyDetails->policy_number) ? '--- not issued ---' : $policyDetails->policy_number);

        if (in_array('ROLE_FINANCE_MANAGER', Auth::user()->roles) || in_array('ROLE_FINANCE', Auth::user()->roles)) {
            $breadcrumbDetails = array('url' => action('Dashboard\DashboardController@dashboardFinancePolicyList'), 'title' => 'Policy');
        } else {
            $breadcrumbDetails = array('url' => action('Dashboard\DashboardController@technicalPolicyDetails'), 'title' => 'Policy');
        }
        $data = array('policyData' => $policyDetails, 'insurancecompany' => $insuranceCompany, 'allCustomers' => $allCustomers, 'headTitle' => $headTitle, 'breadcrumb' => $breadcrumbDetails);
        return view('Policy/editPolicyForm', $data);
    }

    /**
     * To update policy status
     * @param Request $request
     * @return type
     */
    public function updatePolicyDetails(Request $request) {
        if ($request->get('policy_id') > 0) {
            $policyObj = policy::find($request->get('policy_id'));
        }

        $dateObj = date('Y-m-d H:i:s');
        $policyId = $request->get('policy_id');
        $policyObj->customer_id = $request->get('customer_id');
        $policyObj->issue_date = date('Y-m-d', strtotime($request->get('policy_date_issue')));
        $policyObj->start_date = date('Y-m-d', strtotime($request->get('policy_date_start')));
        $policyObj->end_date = date('Y-m-d', strtotime($request->get('policy_date_end')));
        $policyObj->sales_type = $request->get('policy_salestype');
        $policyObj->insurer_id = $request->get('policy_insurer');
        $policyObj->policy_type = $request->get('policy_type', 1);
        $policyObj->renewal_status = $request->get('policy_renewalstatus');
        $policyObj->policy_number = $request->get('policy_number');


        $policyObj->updated_at = $dateObj;
        $policyObj->save();

        $logArray[] = array("policy_id" => $request->get('policy_id'),
            "kind" => 'Policy details was changed',
            "edited_by" => Auth::user()->id,
            "oldvalue" => '',
            "edited_at" => date('Y-m-d h:i:s'));

        DB::table('policy_log')->insert($logArray);

        Session::flash('success', 'Successfully updated product data!!!!');
        return redirect()->route('policyoverview', ['policyId' => $policyId]);
    }

    /**
     * To recalculate commission amount
     * @param type $request
     */
    private function recalculateCommission($request) {

        $policyObj = policy::find($request->get('policy_id'));
        $grossAmount = $policyObj->gross_amount;
        $percentage = floatval($request->get('policy_commission', 0));
        $datetime = date('Y-m-d h:i:s');
        $commissionAmount = floatval(($grossAmount * $percentage) / 100);
        $whereArray[] = ["policies.id", "=", $request->get('policy_id')];
        $whereArray[] = ["cm.distributor_type", "=", 'sales person'];
        $oldCommissinDetails = DB::table('policy_commission as cm')
                ->join('policies', 'policies.id', '=', 'cm.policy_id')
                ->select('cm.distributor_type', 'cm.sales_person_id', 'cm.commission_type', 'cm.percentage')
                ->where($whereArray)
                ->first();



        $insertArray[] = array("policy_id" => $request->get('policy_id'),
            "distributor_type" => 'diamond',
            "commission_type" => '0',
            "percentage" => $percentage,
            "amount" => $commissionAmount,
            "added_date" => $datetime,
            "sales_person_id" => 3
        );


        //add sales person commission
        if ($oldCommissinDetails && count(get_object_vars($oldCommissinDetails)) > 0) {

            if ($oldCommissinDetails->commission_type == 0) {
                $newCommissionAmount = floatval(($commissionAmount * $oldCommissinDetails->percentage) / 100);
                $insertArray[] = array("policy_id" => $request->get('policy_id'),
                    "distributor_type" => 'sales person',
                    "commission_type" => '0',
                    "percentage" => $oldCommissinDetails->percentage,
                    "amount" => $newCommissionAmount,
                    "added_date" => $datetime,
                    "sales_person_id" => $oldCommissinDetails->sales_person_id,
                );
            } else {
                $insertArray[] = array("policy_id" => $request->get('policy_id'),
                    "distributor_type" => 'sales person',
                    "commission_type" => '1',
                    "percentage" => 0,
                    "amount" => floatval($oldCommissinDetails->amount),
                    "added_date" => $datetime,
                    "sales_person_id" => $oldCommissinDetails->sales_person_id,
                );
            }
        }


        if (count($insertArray) > 0) {
            DB::table('policy_commission')->where('policy_id', '=', $request->get('policy_id'))->delete();
            DB::table('policy_commission')->insert($insertArray); // Query Builder approach
            $logArray[] = array("policy_id" => $request->get('policy_id'),
                "kind" => 'Policy commission details was changed',
                "edited_by" => Auth::user()->id,
                "oldvalue" => '',
                "edited_at" => date('Y-m-d h:i:s'));

            DB::table('policy_log')->insert($logArray);
        }
    }

    /**
     * To remove object
     * @param Request $request
     * @return type
     */
    public function removeObject(Request $request) {
        $whereArray[] = ['id', '=', $request->get('objId')];
        $policyId = $request->get('policyId');
        DB::table('policy_product_object')->where($whereArray)->delete();
        $logArray[] = array("policy_id" => $policyId,
            "kind" => 'Product object was removed',
            "edited_by" => Auth::user()->id,
            "oldvalue" => '',
            "edited_at" => date('Y-m-d h:i:s'));

        DB::table('policy_log')->insert($logArray);
        Session::flash('success', 'Successfully deleted object data!!!!');
        return response()->json(array('success' => true));
    }

    /**
     * To create new object
     * @param Request $request
     * @param integer $policyId
     * @return type
     */
    public function creatNewobject(Request $request, $policyId) {
        $insertArray = $this->createObjectValueArray($request, $policyId);
        if (count($insertArray) > 0) {
            DB::table('policy_product_object')->insert($insertArray);
        }
        Session::flash('success', 'Successfully inserted object data!!!!');
        $logArray[] = array("policy_id" => $policyId,
            "kind" => 'New product object was added',
            "edited_by" => Auth::user()->id,
            "oldvalue" => '',
            "edited_at" => date('Y-m-d h:i:s'));

        DB::table('policy_log')->insert($logArray);
        return redirect()->route('policyoverview', ['policyId' => $policyId]);
    }

    /**
     * To creat product object value array
     * @param type $request
     * @param type $policyId
     * @return type
     */
    private function createObjectValueArray($request, $policyId) {
        $insertArray = [];
        $policyObj = DB::table('policies')->find($policyId);
        $currentTime = date('Y-m-d h:i');
        switch ($policyObj->product_id) {
            case 15:

                $insertArray[] = array(
                    'policy_id' => $policyId,
                    'address' => $request->get('object_address'),
                    'property_type' => $request->get('object_property_type'),
                    'year_built' => $request->get('object_yearbuilt'),
                    'area' => $request->get('object_area'),
                    'object_type' => $request->get('object_type'),
                    'construction_material' => $request->get('object_construction_material'),
                    'updated_date' => $currentTime
                );

                break;
            case 24:case 27:

                $makefieldValueArray = $request->get('object_make');
                $modelfieldValueArray = $request->get('object_model');

                $yearfieldValueArray = $request->get('object_year');
                $licenseplatefieldValueArray = $request->get('object_license_plate');
                $bodytypefieldValueArray = $request->get('object_body_type');
                $grossweightfieldValueArray = $request->get('object_gross_weight');
                $vincodefieldValueArray = $request->get('object_vincode');
                $usagefieldValueArray = $request->get('object_usage');
                $passengernumberfieldValueArray = $request->get('object_number_of_passengers');
                $powerfieldValueArray = $request->get('object_power');
                $coverageStartDate = $request->get('object_coverage_begin');
                $coverageEndDate = $request->get('object_coverage_end');

                for ($i = 1; $i <= count($request->get('object_make')); $i++) {
                    $insertArray['make'] = $makefieldValueArray[$i];
                    $insertArray['policy_id'] = $policyId;
                    $insertArray['model'] = $modelfieldValueArray[$i];
                    $insertArray['year'] = $yearfieldValueArray[$i];
                    $insertArray['license_plate'] = $licenseplatefieldValueArray[$i];
                    $insertArray['body_type'] = $bodytypefieldValueArray[$i];
                    $insertArray['gross_weight'] = $grossweightfieldValueArray[$i];
                    $insertArray['object_type'] = $request->get('object_type');
                    $insertArray['vin_code'] = $vincodefieldValueArray[$i];
                    $insertArray['vehicle_usage'] = $usagefieldValueArray[$i];
                    $insertArray['no_of_passengers'] = $passengernumberfieldValueArray[$i];
                    $insertArray['power'] = $powerfieldValueArray[$i];
                    $insertArray['updated_date'] = $currentTime;

                    $insertArray['coverage_start_date'] = (!empty($coverageStartDate[$i])) ? date('Y-m-d', strtotime($coverageStartDate[$i])) : null;
                    $insertArray['coverage_end_date'] = (!empty($coverageEndDate[$i])) ? date('Y-m-d', strtotime($coverageEndDate[$i])) : null;
                }





                break;
            case 30:case 38:
                $firstNameArray = $request->get('object_last_name');
                $lastNameArray = $request->get('object_middle_name');
                $dobDateArray = $request->get('object_dob');
                $genderArray = $request->get('object_gender');
                $coverageStartDate = $request->get('object_coverage_begin');
                $coverageEndDate = $request->get('object_coverage_end');
                for ($i = 1; $i <= count($request->get('object_last_name')); $i++) {
                    $insertArray['last_name'] = $firstNameArray[$i];
                    $insertArray['policy_id'] = $policyId;
                    $insertArray['first_name'] = $lastNameArray[$i];
                    $insertArray['dob'] = date('Y-m-d', strtotime($dobDateArray[$i]));
                    $insertArray['object_type'] = $request->get('object_type');
                    $insertArray['gender'] = $genderArray[$i];
                    $insertArray['coverage_start_date'] = (!empty($coverageStartDate[$i])) ? date('Y-m-d', strtotime($coverageStartDate[$i])) : null;
                    $insertArray['coverage_end_date'] = (!empty($coverageEndDate[$i])) ? date('Y-m-d', strtotime($coverageEndDate[$i])) : null;
                    $insertArray['updated_date'] = $currentTime;
                }

                break;
        }

        return $insertArray;
    }

    /**
     * To regenerate installment details
     * @param Request $request
     * @param integer $policyId
     * @return type
     */
    public function regenerateInstallment(Request $request, $policyId) {
        $policyObj = DB::table('policies')->find($policyId);
        $installmentNumber = $policyObj->installment_number;
        $policyStartDate = $policyObj->start_date;
        $policyEndDate = $policyObj->end_date;
        $totalPremium = $policyObj->total_premium;
        $policyVat = $policyObj->tax;

        $startDate = $policyStartDate;
        if ($installmentNumber > 0) {
            $installmentAmount = floatval($totalPremium / $installmentNumber);
            $incrementMonth = 12 / $installmentNumber;
            $intallmentArray = [];

            for ($i = 1; $i <= $installmentNumber; $i++) {
                $time = strtotime($startDate);
                $intallmentArray[$i]['policy_id'] = $policyId;
                $intallmentArray[$i]['start_date'] = date("Y-m-d", $time);
                $tobeAdded = $i * $incrementMonth;
                $endDate = date("Y-m-d", strtotime("+$tobeAdded month", strtotime($policyObj->start_date)));
                if ($endDate > date('Y-m-d', strtotime($policyEndDate))) {
                    $endDate = date('Y-m-d', strtotime($policyEndDate));
                }
                $intallmentArray[$i]['end_date'] = $endDate;
                $startDate = $endDate;
                $intallmentArray[$i]['due_date'] = date("Y-m-d", strtotime("+1 days", strtotime($endDate)));
                $intallmentArray[$i]['amount'] = $installmentAmount;

                $intallmentArray[$i]['vat_amount'] = floatval(($installmentAmount * $policyVat) / 100);
                $intallmentArray[$i]['vat_percentage'] = $policyVat;
            }
            $whereArray[] = ['policy_id', '=', $policyId];
            $whereArray[] = ['installment_type', '=', 1];


            DB::table('policy_intallment')->where($whereArray)->delete();
            if (count($intallmentArray) > 0) {
                DB::table('policy_intallment')->insert($intallmentArray);
            }
            $logArray[] = array("policy_id" => $policyId,
                "kind" => 'Installment detail was changed',
                "edited_by" => Auth::user()->id,
                "oldvalue" => '',
                "edited_at" => date('Y-m-d h:i:s'));
            DB::table('policy_log')->insert($logArray);
        }


        Session::flash('success', 'Successfully re-generated installment details!!!!');
        return response()->json(array('success' => true, 'returnUrl' => action('Policy\PolicyController@policyDetails', ['policyId' => $policyId])));
    }

    /**
     * To get premium info
     * @param integer $policyId
     * @return type
     */
    public function getPremiumInfo($policyId) {
        $policyObj = DB::table('policies')->find($policyId);
        $objectHtml = view('Policy/editPolicyPremiumForm', array('premiumdata' => $policyObj, 'policyId' => $policyId))->render();
        return response()->json(array('status' => true, 'content' => $objectHtml));
    }

    /**
     * To save premium info
     * @param Request $request
     * @param integer $policyId
     * @return type
     */
    public function savePremiumInfo(Request $request, $policyId) {
        $policyObj = DB::table('policies')->find($policyId);
        $vat = $policyObj->tax;
        $totalPremium = floatval($request->get('policy_premium_sum') + $request->get('policy_premium_addition', 0));
        $oldVatamount = $policyObj->vat_amount;
        $newVatAmount = $policyObj->vat_amount - (($policyObj->total_premium * $vat ) / 100 ) + (($totalPremium * $vat) / 100);

        DB::table('policies')->where('id', '=', $policyId)->update(array('collection_type' => $request->get('policy_collection'), 'gross_amount' => $request->get('policy_premium_sum'), 'additional_amount' => $request->get('policy_premium_addition'), 'installment_number' => $request->get('policy_installments'), 'updated_at' => date('Y-m-d H:i:s'), 'total_premium' => $totalPremium, 'vat_amount' => $newVatAmount));
        $logArray[] = array("policy_id" => $policyId,
            "kind" => 'Premium info was changed',
            "edited_by" => Auth::user()->id,
            "oldvalue" => '',
            "edited_at" => date('Y-m-d h:i:s'));

        DB::table('policy_log')->insert($logArray);
        Session::flash('success', 'Successfully updated premium data!!!!');
        return redirect()->route('policyoverview', ['policyId' => $policyId]);
    }

    /**
     * To get policy log info
     * @param integer $policyId
     * @return type
     */
    public function getPolicyLogInfo($policyId) {
        $logData = DB::table('policy_log as log')
                        ->join('policies', 'policies.id', '=', 'log.policy_id')
                        ->leftJoin('users', 'users.id', '=', 'log.edited_by')
                        ->select('log.*', 'users.name as editedBy')
                        ->where("log.policy_id", "=", $policyId)
                        ->orderBy('log.id', 'desc')->get();
        $data = array('logdata' => $logData);
        $returnHTML = view('Policy/logData', $data)->render();
        return response()->json(array('status' => true, 'content' => $returnHTML));
    }

    /**
     * To set up installment information editing form
     * @param integer $installmentId
     * @return type
     */
    public function editInsatllmentInfo($installmentId) {

        $installmentDetails = DB::table('policy_intallment as im')->where('id', $installmentId)->first();
        $data = array('installmentData' => $installmentDetails, 'installmentId' => $installmentId);
        $returnHTML = view('Policy/editInstallementForm', $data)->render();
        return response()->json(array('status' => true, 'content' => $returnHTML));
    }

    /**
     * To update installment details
     * @param Request $request
     * @param integer $installmentId
     * @return type
     */
    public function updateInstallment(Request $request, $installmentId) {
        $installmentObj = PolicyInstallment::find($installmentId);
        $policyId = $installmentObj->policy_id;
        $installmentObj->end_date = date('Y-m-d', strtotime($request->get('policy_installment_date_end')));
        $installmentObj->due_date = date('Y-m-d', strtotime($request->get('policy_installment_date_due')));
        $installmentObj->start_date = date('Y-m-d', strtotime($request->get('policy_installment_date')));
        $installmentObj->amount = $request->get('policy_installment_sum');
        $installmentObj->paid_status = $request->get('policy_installment_paid_status');
        $installmentObj->collect_by = $request->get('policy_installment_collection');
        $installmentObj->vat_percentage = $request->get('policy_installment_tax');
        $installmentObj->vat_amount = floatval(($request->get('policy_installment_sum') * $request->get('policy_installment_tax')) / 100);
        $installmentObj->save();
        $logArray[] = array("policy_id" => $policyId,
            "kind" => 'Installment info was changed',
            "edited_by" => Auth::user()->id,
            "oldvalue" => '',
            "edited_at" => date('Y-m-d h:i:s'));

        DB::table('policy_log')->insert($logArray);
        Session::flash('success', 'Successfully updated installment data!!!!');

        return redirect()->route('policyoverview', ['policyId' => $policyId]);
    }

    /**
     * To set up endorsement creation form
     * @param integer $policyId
     * @return type
     */
    public function endorsementRequest($policyId) {
        $endorsementRequest = DB::table('crm_endorsement as ecr')
                        ->leftJoin('users as u', 'u.id', '=', 'ecr.created_by')
                        ->select('ecr.*', 'u.name as userName', DB::raw("(case ecr.status when 3 then 'Resolved' when 2 then 'Under process'  when 4 then 'Pending from insurer' when 5 then 'pending from client' when 6 then 'Completed with invoice' when 7 then 'Completed by client request' when 8 then 'Completed without invoice'   when 9 then 'Awaiting invoice'  when 10 then 'closed' else 'Open'   end) AS statusString"))
                        ->where('ecr.policy_id', '=', $policyId)->orderBy('ecr.updated_at')->get();

        $data = array('endorsementDetails' => $endorsementRequest, "title" => 'Endorsement request', 'policyId' => $policyId);

        $returnHTML = view('policy/endorsementrequestList', $data)->render();

        return response()->json(array('status' => true, 'content' => $returnHTML));
    }

    /**
     * To create endorsement request
     * @param integer $policyId
     * @return type
     */
    public function createendorsementRequest($policyId) {
        $typeArray = [1 => 'Addition', 'CCHI', 'Deletion', 'Downgrade', 'Corrections', 'Certificate', 'Najam upload', 'Invoices Request', 'Upgrade', 'Others', 'Approvals', 'Request quatations', 'Active list'];
        $typeArray[17] = 'Announcement';
        $data = array('typeArray' => $typeArray, 'policyId' => $policyId);
        $returnHTML = view('policy/createendorsementrequest', $data)->render();
        return response()->json(array('status' => true, 'content' => $returnHTML));
    }

    /**
     * To save the endorsement crm request
     * @param Request $request
     * @param integer $policyId
     * @return type
     */
    public function saveCrmRequest(Request $request, $policyId) {
        $requestId = substr("ER-" . uniqid(date("Ymdhi")), 0, -12);
        DB::table('crm_endorsement')->insert(array('policy_id' => $policyId, 'description' => $request->get('request_comment'), 'type' => $request->get('request_type'), 'status' => 1, 'created_at' => date('Y-m-d h:i:s'), 'updated_at' => date('Y-m-d h:i:s'), 'created_by' => Auth::user()->id, 'request_id' => $requestId,'assign_to'=>Auth::user()->id));
        return back()->with(['success' => 'Successfully created endorsement request!!!!', 'overviewtabselected' => 'crm']);
    }

    /**
     * To display the endorsement crm request details
     * @param integer $policyId
     * @param integer $requestId
     * @return type
     */
    public function endorsementCrmRequestOverview($policyId, $requestId) {
       
        
        $overviewData = DB::table('crm_endorsement as ecr')
                        ->join('policies as po', 'po.id', '=', 'ecr.policy_id')
                        ->join('customers as c', 'po.customer_id', '=', 'c.id')
                        ->leftJoin('users as u', 'u.id', '=', 'ecr.created_by')
                        ->leftJoin('users as su', 'su.id', '=', 'ecr.assign_to')
                        ->select('ecr.*', 'c.name as customerName', 'c.id as customerId','su.name as AssignName', 'u.name as userName', DB::raw("(case ecr.status when 3 then 'Resolved' when 2 then 'Under process'  when 4 then 'Pending from insurer' when 5 then 'pending from client' when 6 then 'Completed with invoice' when 7 then 'Completed by client request' when 8 then 'Completed without invoice'  when 9 then 'Awaiting invoice' when 10 then 'Closed'  else 'Open'   end) AS statusString"), 'po.policy_number', 'po.customer_id')
                        ->where('ecr.id', '=', $requestId)->first();

  

        $typeArray = [1 => 'Addition', 'CCHI', 'Deletion', 'Downgrade', 'Corrections', 'Certificate', 'Najam upload', 'Invoices Request', 'Upgrade', 'Others', 'Approvals', 'Request quatations', 'Active list'];
        $typeArray[17] = 'Announcement';
        $statusArray = [1 => 'Open', 'Under process', 'Resolve', 'Pending from insurer', 'Pending from client'];

        if (in_array($overviewData->type, [1, 3, 4, 9])) {
            $statusArray[] = 'Completed with invoice';
            $statusArray[] = 'Completed by client request';
            $statusArray[] = 'Completed without invoice';
            $statusArray[] = 'Awaiting Invoice';
          }
        $statusArray[10] = 'Close';
        $logDetails = DB::table('endorsement_crm_log as log')
                        ->leftJoin('users as u', 'u.id', '=', 'log.updated_by')
                        ->select('log.*', 'u.name as userName')
                        ->where('log.crm_id', '=', $requestId)->orderBy('log.id', 'desc')->get();

        $whereArray[] = ['doc.endorsement_crm_id', '=', $requestId];
        $whereArray[] = ['doc.policy_id', '=', $policyId];
        $documentDetails = DB::table('dp_customer_document as doc')
                        ->leftJoin('users as u', 'u.id', '=', 'doc.upload_by')
                        ->leftJoin('dp_document_type as dt', 'dt.id', '=', 'doc.type')
                        ->select('doc.*', 'u.name as uploadedBy', 'dt.title as docType', 'doc.id as docId')
                        ->where($whereArray)->orderBy('doc.id', 'desc')->get();
        $documentType = DB::table('dp_document_type')->distinct()->orderBy('id')->pluck('title', 'id')->toArray();

        $commentsDetails = DB::table('crm_endorsement_comments as c')
                        ->join('users as u', 'u.id', '=', 'c.created_by')
                        ->select('c.*', 'u.name as createdBy')
                        ->where('c.request_id', '=', $requestId)
                        ->orderBy('c.created_at', 'desc')->get();
        $relatedWhere[] = ['policy_id', '=', $policyId];
        $relatedWhere[] = ['status', '!=', 3];
        $relatedWhere[] = ['id', '!=', $requestId];

        $relatedRequests = DB::table('crm_endorsement')->where($relatedWhere)->pluck('request_id', 'id')->toArray();
        $users = DB::table('users')->distinct()->where('status', '1')->where('roles','like',"%ROLE_OPERATION%")->orderBy('name')->pluck('name', 'id')->toArray(); 

        $breadcrumbDetails = array('url' => action('Dashboard\DashboardController@dashboardEndorsementList'), 'title' => 'Operation request');

        $data = array('overviewData' => $overviewData, 'policyId' => $policyId, 'requestId' => $requestId, 'typeArray' => $typeArray, 'overviewTab' => 'overview', 'statusArray' => $statusArray, 'logData' => $logDetails, 'documentDetails' => $documentDetails, 'documentType' => $documentType, 'breadcrumb' => $breadcrumbDetails, 'commentDetails' => $commentsDetails, 'relatedrequests' => $relatedRequests,'assignUsers'   =>$users);


        return view('Policy/overviewendorsementrequest', $data);
    }

    /**
     * To edit the crm request data
     * @param integer $policyId
     * @param integer $requestId
     * @return type
     */
    public function editCrmRequestData($policyId, $requestId) {
        $typeArray = [1 => 'Addition', 'CCHI', 'Deletion', 'Downgrade', 'Corrections', 'Certificate', 'Najam upload', 'Invoices Request', 'Upgrade', 'Others', 'Approvals', 'Request quatations', 'Active list'];
        $typeArray[17] = 'Announcement';
        $overviewData = DB::table('crm_endorsement as ecr')
                        ->join('policies as po', 'po.id', '=', 'ecr.policy_id')
                        ->leftJoin('users as u', 'u.id', '=', 'ecr.created_by')
                        ->select('ecr.*', 'u.name as userName', DB::raw("(case ecr.status when 3 then 'Closed' when 2 then 'Under process'  when 4 then 'Pending from insurer' when 5 then 'pending from client' when 6 then 'Completed with invoice' when 7 then 'Completed by client request' when 8 then 'Completed without invoice'   when 9 then 'Awaiting invoice' else 'Open'   end) AS statusString"), 'po.policy_number')
                        ->where('ecr.id', '=', $requestId)->first();
        $data = array('typeArray' => $typeArray, 'policyId' => $policyId, 'requestDetails' => $overviewData, 'requestId' => $requestId);
        $returnHTML = view('policy/editendorsementrequest', $data)->render();
        return response()->json(array('status' => true, 'content' => $returnHTML));
    }

    /**
     * To update crm request data
     * @param Request $request
     * @param integer $policyId
     * @return type
     */
    public function updateCrmRequestData(Request $request, $policyId) {
        $requestId = $request->get('crm_request_id');

        $crmrequestObj = App\EndorsementCrm::find($requestId);


        $oldType = $crmrequestObj->type;
        $oldDescription = $crmrequestObj->description;
        $crmrequestObj->type = $request->get('request_type');
        $crmrequestObj->description = $request->get('request_comment');
        $crmrequestObj->updated_at = date('Y-m-d h:i:s');
        $crmrequestObj->save();
        $typeArray = [1 => 'Addition', 'CCHI', 'Deletion', 'Downgrade', 'Corrections', 'Certificate', 'Najam upload', 'Invoices Request', 'Upgrade', 'Others', 'Approvals', 'Request quatations', 'Active list'];
        $typeArray[17] = 'Announcement';

        $logArray = [];

        if ($oldType != $request->get('request_type')) {
            $logArray[] = array('crm_id' => $requestId, 'kind' => 'Crm request type was changed to ' . $typeArray[$request->get('request_type')], 'old_value' => $typeArray[$oldType], 'updated_by' => Auth::user()->id, 'updated_at' => date('Y-m-d h:i:s'));
        } else if ($oldDescription != $request->get('request_comment')) {
            $logArray[] = array('crm_id' => $requestId, 'kind' => 'Crm request description was changed ', 'old_value' => $oldDescription, 'updated_by' => Auth::user()->id, 'updated_at' => date('Y-m-d h:i:s'));
        } else {
            
        }

        if (count($logArray) > 0) {
            DB::table('endorsement_crm_log')->insert($logArray);
        }

        return back()->with(['success' => 'Successfully update endorsement request!!!!', 'overviewtabselected' => 'crm']);
    }

    /**
     * To update crm request status
     * @param Request $request
     * @param integer $policyId
     * @return type
     */
    public function updateCrmRequestStatus(Request $request, $policyId) {
        $requestId = $request->get('crm_request_id');

        $crmrequestObj = App\EndorsementCrm::find($requestId);
        $createdBy = $crmrequestObj->assign_to;
        $requestNo = $crmrequestObj->request_id;
        $requestType = $crmrequestObj->type;
        $oldStatus = $crmrequestObj->status;
        $crmrequestObj->status = $request->get('request_status');
        $crmrequestObj->updated_at = date('Y-m-d h:i:s');
        $crmrequestObj->save();

        $statusArray = [1 => 'Open', 'Under process', 'Resolve', 'Pending from insurer', 'Pending from client'];
        if (in_array($requestType, [1, 3, 4, 9])) {
            $statusArray[] = 'Completed with invoice';
            $statusArray[] = 'Completed by client request';
            $statusArray[] = 'Completed without invoice';
            $statusArray[] = 'Awaiting invoice';
        }
       $statusArray[10] = 'Closed';
        if ($oldStatus != $request->get('status')) {
            $logArray = array('crm_id' => $requestId, 'kind' => 'Crm request status was changed to ' . $statusArray[$request->get('request_status')], 'old_value' => $statusArray[$oldStatus], 'updated_by' => Auth::user()->id, 'updated_at' => date('Y-m-d h:i:s'));
            DB::table('endorsement_crm_log')->insert($logArray);
        }
        //send mail to request created person
         $userObj = DB::table('users')->find($createdBy);
         $url = route('overviewendorsementcrmrequest', ['policyId' => $policyId,'requestId'=>$requestId]);
         $data = array('name' =>$userObj->name, 'link' =>$url, 'Request_no' =>$requestNo,'username' =>$userObj->name,'status'=>$statusArray[$request->get('request_status')]);
         $templatename = 'emails.operationrequeststatuschangenotification';
         $maidetails['to'] = $userObj->email;
         $maidetails['name'] = $userObj->name;
         $maidetails['subject'] = $requestNo." :Endorsement request status was changed ";
         $this->send_email($maidetails, $data, $templatename);      
        
        
        if (in_array($request->get('request_status'), [3, 6, 7, 8]) && in_array($requestType, [1, 3, 4, 9])) {
            return redirect()->route('addendorsement', ['crmId' => $requestId]);
        } else {
            return back()->with(['success' => 'Successfully update endorsement request status!!!!', 'overviewtabselected' => 'crm']);
        }
    }

    /**
     * To upload crm document
     * @param Request $request
     * @param integer $requestId
     * @return type
     */
    public function uploadCrmDocument(Request $request, $requestId) {
        $files = $request->file('document_file');
        $insertArray = [];
        $type = $request->get('documenttype_oid');
        $comment = $request->get('document_comment');
        $customerId = $request->get('customer_id');
        $policyId = $request->get('policy_id');
        $filename = [];
        $datetime = date('Y-m-d h:i');
        foreach ($files as $uploadedfile) {
            $destinationPath = 'uploads/' . $customerId . "/document/";
            $path_parts = pathinfo($uploadedfile->getClientOriginalName());
            $newfilename = $path_parts['filename'] . "_" . date('Ymdhis') . '.' . $path_parts['extension'];
            $filename[] = $newfilename;
            $uploadedfile->move($destinationPath, $newfilename);
            $insertArray[] = array("customer_id" => $customerId,
                "file_name" => $newfilename,
                "type" => $type,
                "comment" => $comment,
                "upload_by" => Auth::user()->id,
                "upload_at" => $datetime,
                "endorsement_crm_id" => $requestId,
                "policy_id" => $policyId
            );
        }

        if (count($insertArray) > 0) {
            DB::table('dp_customer_document')->insert($insertArray); // Query Builder approach
            //insert log entry
            $logarray = array("crm_id" => $requestId,
                "kind" => "Following documents are uploaded: " . implode(',', $filename),
                "old_value" => '',
                "updated_by" => Auth::user()->id,
                "updated_at" => $datetime);

            DB::table('endorsement_crm_log')->insert($logarray);


            //Session::put('requestoverviewtabselected', 'document');
        }
        return back()->with(['success' => 'Successfully upload documents!', 'overviewtabselected' => 'document']);
    }

    /*     * To update crm document
     *
     * @param Request $request
     * @param integer $requestId
     * @return type
     */

    public function updateCrmDocument(Request $request, $requestId) {
        $type = $request->get('documenttype_oid');
        $comment = $request->get('document_comment');
        $customerId = $request->get('customer_id');
        $policyId = $request->get('policy_id');
        $docId = $request->get('doc_id');
        $datetime = date('Y-m-d h:i');
        DB::table('dp_customer_document')->where('id', $docId)->update(['type' => $type, "comment" => $comment, "edited_at" => $datetime]);
        $logarray = array("crm_id" => $requestId,
            "kind" => "Document detail was changed ",
            "old_value" => '',
            "updated_by" => Auth::user()->id,
            "updated_at" => $datetime);

        DB::table('endorsement_crm_log')->insert($logarray);
        return back()->with(['success' => 'Successfully updated documents details!', 'overviewtabselected' => 'document']);
    }

    /**
     * To delete crm document
     * @param Request $request
     * @param integer $requestId
     * @return type
     */
    public function deleteCrmDocument(Request $request, $requestId) {

        $policyId = $request->get('policyId');
        $docId = $request->get('docId');
        $customerId = $request->get('customerId');
        $crmId = $request->get('crmId');
        $datetime = date('Y-m-d h:i');
        $whereArray[] = ['id', '=', $request->get('docId')];
        $whereArray[] = ['customer_id', '=', $customerId];
        $documentDetails = DB::table('dp_customer_document')->where($whereArray)->pluck('file_name')->toArray();
        DB::table('dp_customer_document')->where('id', $docId)->delete();

        $destinationPath = 'uploads/' . $customerId . "/document/" . $documentDetails[0];
        unlink($destinationPath);

        $logarray = array("crm_id" => $requestId,
            "kind" => "Document was removed" . $documentDetails[0],
            "old_value" => '',
            "updated_by" => Auth::user()->id,
            "updated_at" => $datetime);

        DB::table('endorsement_crm_log')->insert($logarray);

        Session::flash('success', 'Successfully removed document!!!!');
        return response()->json(array('status' => true));
    }

    /**
     * To change status of the policy
     * @param Request $request
     * @param integer $policyId
     * @return type
     */
    public function changePolicyStatus(Request $request, $policyId) {
        $changedto = $request->get('flag');
        $logArray = [];
        $flashMessage = '';
        $redirect = '';
        if ($changedto == 4) {
            //delete
            $policyObj = policy::find($policyId);
            $customerId = $policyObj->customer_id;
            $policyObj->delete();
            //remove related file from the folders
            $path = public_path("/uploads/" . $customerId . "/document/" . $policyId . "/");
            if (File::exists($path)) {
                File::deleteDirectory($path);
            } else {
                
            }


            $flashMessage = "Successfully deleted policy details";
            $redirect = action('Policy\PolicyController@listPolicy');
        } else if ($changedto == 3) {
            //lock policy
            $policyObj = policy::find($policyId);
            $policyObj->policy_status = 3;
            $policyObj->save();
            $logArray['policy_id'] = $policyId;
            $logArray['kind'] = 'Policy status changed to lock';
            $logArray['edited_by'] = Auth::user()->id;
            $logArray['edited_at'] = date('Y-m-d h:i:s');
            $flashMessage = "Successfully locked policy!!!!!";
        } else if ($changedto == 1) {
            //lock policy
            $policyObj = policy::find($policyId);
            $policyObj->policy_status = 1;
            $policyObj->policy_number = $request->get('policy_number');
            $policyNo = $policyObj->policy_number ;

            $customerId = $policyObj->customer_id;
            $policyObj->save();
            $logArray['policy_id'] = $policyId;
            $logArray['kind'] = 'Policy status changed to post';
            $logArray['edited_by'] = Auth::user()->id;
            $logArray['edited_at'] = date('Y-m-d h:i:s');
            $flashMessage = "Successfully changed status of policy to post!!!!!";
            $customerdetails = DB::table('customers')->select('name')->where('id', $customerId)->first();
            $notificationArray['message'] = 'A new policy was posted for customer ' . $customerdetails->name;


            $notificationArray['created_date'] = date('Y-m-d');
            $notificationArray['department'] = 'FINANCE';
            $notificationArray['message_type'] = 'policy';
            DB::table('notification_details')->insert($notificationArray);

            $documentsDetails = ($request->get('send_document') !== NULL) ? $request->get('send_document') : array();

            // Create a mail notification to sales team 
            //$this->sendPolicyScheduleNotification($policyId, $documentsDetails);
            $financeuser = DB::table('users')->select('email', 'name')->where('status', '1')->where('roles', 'like', "%ROLE_FINANCE_ADMIN%")->orderBy('id')->first();
                $url = route('policyoverview', ['policyId' => $policyId]);
               $data = array('name' =>'Finance', 'link' =>$url, 'policy_no' =>$policyNo,'username' =>$financeuser->name,'status'=>'Posted');
               $templatename = 'emails.policypostnotification';
               $maidetails['to'] = $financeuser->email;
               $maidetails['name'] = $financeuser->name;
               $maidetails['subject'] = "Policy ".$policyNo ." detail was posted to finance department";
               $this->send_email($maidetails, $data, $templatename);      
            
            //Create a sales request
            $requestId = substr("ER-" . uniqid(date("Ymdhi")), 0, -12);
            DB::table('crm_endorsement')->insert(array('policy_id' => $policyId, 'description' => 'Please send policy issuance related document to customer', 'type' => 17, 'status' => 1, 'created_at' => date('Y-m-d h:i:s'), 'updated_at' => date('Y-m-d h:i:s'), 'created_by' => Auth::user()->id, 'request_id' => $requestId));

            return back()->with(['success' => 'Successfully posted policy!']);
            exit;
        } else if ($changedto == 2) {
            //activate policy
            $policyObj = policy::find($policyId);
            $customerId = $policyObj->customer_id;
            $policyObj->policy_status = 2;
            $crmId = $policyObj->crm_id;
            $policyObj->save();
            //Generate invoice
            //$this->generateInvoices($policyId);

            $logArray['policy_id'] = $policyId;
            $logArray['kind'] = 'Policy status changed to active';
            $logArray['edited_by'] = Auth::user()->id;
            $logArray['edited_at'] = date('Y-m-d h:i:s');
            $flashMessage = "Successfully activate policy!!!!!";
            DB::table('policy_log')->insert($logArray);
            $customerObj = customer::find($customerId);
            $customerObj->policy_flag = 1;
            $customerObj->save();

            //Policy issued notification
            if ($crmId != '' && $crmId != null) {
                $crmdetails = DB::table('crm_main_table')->select('crm_request_id')->where('id', $crmId)->first();
                $notificationArray['message'] = 'A new policy was issued against Request No:' . $crmdetails->crm_request_id;
            } else {
                $customerdetails = DB::table('customers')->select('name')->where('id', $customerId)->first();
                $notificationArray['message'] = 'A new policy was issued for customer ' . $customerdetails->name;
            }

            $notificationArray['created_date'] = date('Y-m-d');
            $notificationArray['department'] = 'SALES,OPERATION';
            $notificationArray['message_type'] = 'policy';
            DB::table('notification_details')->insert($notificationArray);
            return back()->with(['success' => 'Successfully activated policy!']);
        }
        if (count($logArray) > 0) {
            DB::table('policy_log')->insert($logArray);
        }

        Session::flash('success', $flashMessage);
        return response()->json(array('status' => true, 'redirect' => $redirect));
    }

    /**
     * To insert default entry for a policy
     * @param type $request
     */
    private function defaultInstallmentEntry($request) {

        $policyObj = policy::find($request->get('policy_id'));
        $vat = $policyObj->tax;
        $totalAmount = $policyObj->total_premium;
        $startDate = date('Y-m-d', strtotime($policyObj->start_date));
        $insertArray['policy_id'] = $request->get('policy_id');
        $insertArray['start_date'] = date('Y-m-d', strtotime($policyObj->start_date));
        $insertArray['end_date'] = date('Y-m-d', strtotime($policyObj->end_date));
        $insertArray['due_date'] = date("Y-m-d", strtotime("+3 days", strtotime($startDate)));
        $insertArray['amount'] = floatval($totalAmount);
        $insertArray['paid_status'] = 0;
        $insertArray['installment_type'] = 1;
        $insertArray['vat_percentage'] = floatval($vat);
        $insertArray['vat_amount'] = floatval(( $totalAmount * $vat) / 100);
        $insertArray['default_flag'] = 1;
        DB::table('policy_intallment')->insert($insertArray);
    }

    /**
     * To display claim detail of the specified policy
     * @param integer $policyId
     * @return type
     */
    public function getClaimDetails($policyId) {

        $policyObj = policy::find($policyId);

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
                        ->where('pc.policy_id', $policyId)
                        ->orderBy('pc.updated_date', 'desc')->get();



        $data = array("title" => 'Claims', 'claimDetails' => $claimDetails, 'policyId' => $policyId, 'customerId' => $policyObj->customer_id);
        $returnHTML = view('Policy/claimData', $data)->render();
        return response()->json(array('status' => true, 'content' => $returnHTML));
    }

    /**
     *
     * @param integer $policyId
     */
    private function generateInvoices($policyId) {
        $allInstallmentDetails = DB::table('policy_intallment as im')
                        ->join('policies', 'policies.id', '=', 'im.policy_id')
                        ->leftJoin('customers as c', 'c.id', '=', 'policies.customer_id')
                        ->leftJoin('insurance_product as ip', 'ip.id', '=', 'policies.product_id')
                        ->select('im.*', 'policies.policy_number', 'c.*', 'ip.product_name as productName', DB::raw("(case collect_by when '1' then 'Broker' when '2' then 'Insurer' when '3' then 'Retailer' else 'Premium financier' end) AS collectionString"), 'policies.customer_id as customerId')
                        ->where("im.policy_id", "=", $policyId)
                        ->where("im.paid_status", 0)
                        ->whereNull('im.endorsement_id')
                        ->orderBy('im.start_date', 'asc')->get();
        $invoiceDetails = array();
        $i = 0;

        $policyData = array();

        foreach ($allInstallmentDetails as $key => $installments) {
            File::isDirectory('uploads/Invoice/' . $installments->customerId . '/' . $policyId) or File::makeDirectory('uploads/Invoice/' . $installments->customerId . '/' . $policyId, 0777, true, true);
            $filename = 'Invoice' . "_" . $policyId . '_' . $key . '_' . date('Ymdhis') . '.pdf';
            $data = array('installmentDetails' => $installments);
            PDF::loadView('Invoice/invoicepdf', $data)->setPaper('a4', 'portrait')->save('uploads/Invoice/' . $installments->customerId . '/' . $policyId . '/' . $filename);
            $policyData = ['name' => $installments->name, 'phone' => $installments->phone, 'email' => $installments->email, 'invoice_date' => $installments->start_date, 'due_date' => $installments->due_date, 'policy_number' => $installments->policy_number, 'productName' => $installments->productName];
            $invoiceDetails[$i]['policy_id'] = $installments->policy_id;
            $invoiceDetails[$i]['installment_id'] = $installments->id;
            $invoiceDetails[$i]['generated_date'] = date('Y-m-d h:i');
            $invoiceDetails[$i]['paid_status'] = 0;
            $invoiceDetails[$i]['file_name'] = $filename;
            $i++;
        }

        if (count($invoiceDetails) > 0) {
            DB::table('policy_invoice')->insert($invoiceDetails);
        }

        //Consolidate invoice
        File::isDirectory('uploads/Invoice/' . $installments->customerId . '/' . $policyId) or File::makeDirectory('uploads/Invoice/' . $installments->customerId . '/' . $policyId, 0777, true, true);
        $filename = 'ConsolidateInvoice' . "_" . $policyId . '_' . $key . '_' . date('Ymdhis') . '.pdf';
        $data = array('installmentDetails' => $allInstallmentDetails, 'policyDetails' => $policyData);
        PDF::loadView('Invoice/consolidateinvoicepdf', $data)->setPaper('a4', 'portrait')->save('uploads/Invoice/' . $installments->customerId . '/' . $policyId . '/' . $filename);
    }

    /**
     *
     * @param type $customerId
     * @param type $policyId
     */
    public function createSchedule($customerId, $policyId) {
        $policyObj = policy::find($policyId);
        $policyobjCount = DB::table('policy_schedule')->select('id')->where('policy_id', $policyId)->count();
        $dateArray = array();
        if ($policyobjCount > 0) {
            $policyscheduleArray = DB::table('policy_schedule')->select('*')->where('policy_id', $policyId)->get();
            for ($i = 0; $i < count($policyscheduleArray); $i++) {
                $dateArray[$i]['date'] = date("d-m-Y", strtotime($policyscheduleArray[$i]->start_date));
                if ($policyscheduleArray[$i]->end_date != null && $policyscheduleArray[$i]->end_date != '') {
                    $dateArray[$i]['enddate'] = date("d-m-Y", strtotime($policyscheduleArray[$i]->end_date));
                }

                $dateArray[$i]['note'] = $policyscheduleArray[$i]->description;
            }
        } else {
            $startDate = strtotime($policyObj->start_date);

            for ($i = 1; $i <= 11; $i++) {
                $dateArray[$i]['date'] = date("d-m-Y", strtotime("+$i month", $startDate));
                if ($i == 9) {
                    $dateArray[$i]['note'] = 'Only 90 days remains for renew policy: ' . $policyObj->policy_number;
                } else if ($i == 10) {
                    $dateArray[$i]['note'] = 'Only 60 days remains for renew policy: ' . $policyObj->policy_number;
                } else if ($i == 11) {
                    $dateArray[$i]['note'] = 'Only 30 days remains for renew policy ' . $policyObj->policy_number;
                } else {
                    $dateArray[$i]['note'] = '';
                }
            }
        }
        $data = array('policyObj' => $policyObj, 'plannedschedules' => $dateArray);
        return view('Policy/policyschedule', $data);
    }

    /**
     *
     * @param Request $request
     * @param type $customerId
     * @param type $policyId
     * @return type
     */
    public function saveSchedule(Request $request, $customerId, $policyId) {

        $startDateArray = $request->get('schedule_start');
        $endDateArray = $request->get('schedule_end');
        $noteArray = $request->get('schedule_note');
        $scheduleArray = [];
        foreach ($startDateArray as $key => $startDate) {
            $scheduleArray[$key]['policy_id'] = $policyId;
            $scheduleArray[$key]['start_date'] = date('Y-m-d', strtotime($startDate));
            if ($endDateArray[$key] != '' && $endDateArray[$key] != null) {
                $scheduleArray[$key]['end_date'] = date('Y-m-d', strtotime($endDateArray[$key]));
            } else {
                $scheduleArray[$key]['end_date'] = null;
            }

            $scheduleArray[$key]['description'] = $noteArray[$key];
            $appointmentArray[] = $this->eventSetting($scheduleArray[$key]);
        }

        if (count($scheduleArray) > 0) {
            DB::table('policy_schedule')->where('policy_id', $policyId)->delete();
            DB::table('policy_schedule')->insert($scheduleArray);
            DB::table('dpib_events')->where('policy_id', $policyId)->delete();
            $policyObj = policy::find($policyId);
            $insertArray['created_date'] = date('Y-m-d h:i');
            $insertArray['title'] = 'Policy schedule of :' . $policyObj->policy_number;
            $insertArray['color_selection'] = 0;
            $insertArray['created_by'] = Auth::user()->id;
            $insertArray['policy_id'] = $policyId;
            $insertId = DB::table('dpib_events')->insertGetId($insertArray);
            foreach ($appointmentArray as $key => $appointment) {
                $appointment['event_id'] = $insertId;
                $appointmentArray[$key] = $appointment;
            }
            DB::table('dpib_appointments')->insert($appointmentArray);
        }

        return redirect()->action('Policy\PolicyController@policyDetails', ['policyId' => $policyId])->with('success', 'Successfully added policy schedules!!!!!');
    }

    /**
     * 
     * @param type $eventData
     * @return type
     */
    private function eventSetting($eventData) {
        $appointmentArray['event_id'] = '';
        $appointmentArray['appointment_end_date'] = $eventData['end_date'];
        $appointmentArray['description'] = $eventData['description'];
        $appointmentArray['appointment_date'] = $eventData['start_date'];
        $appointmentArray['created_by'] = Auth::user()->id;

        return $appointmentArray;
    }

    /**
     * 
     * @param type $policyId
     */
    private function sendPolicyScheduleNotification($policyId, $documentsDetails) {

        $policyObj = policy::find($policyId);
        $customerobj = customer::find($policyObj->customer_id);
        $user_data['policyNumber'] = $policyObj->policy_number;
        $user_data['customerName'] = $customerobj->name;
        $user_data['documents'] = $documentsDetails;
        $user_data['to_data'] = 'j.mani@dbroker.com.sa';
        $user_data['cc_data'] = '';
        $user_data['attach'] = array();
        $user_data['subject'] = 'Action to be required';
        //Mail sending area

        Mail::send('emails.newpolicyschedulenotification', ['content' => $user_data], function ($m) use ($user_data) {
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

    /**
     * 
     * @param Request $request
     * @param type $policyId
     * @param type $requestId
     * @return type
     */
    public function updateInvoiceFlag(Request $request, $policyId, $requestId) {

        $crmrequestObj = App\EndorsementCrm::find($requestId);
        $crmrequestObj->inv_flag = $request->get('recievedFlag');
        $crmrequestObj->updated_at = date('Y-m-d h:i:s');

        $changeString = ' No';
        if ($request->get('recievedFlag') == 1) {
            $changeString = ' Yes';
            $crmrequestObj->inv_recieved_date = date('Y-m-d');
        }
        $crmrequestObj->save();

        $logArray = array('crm_id' => $requestId, 'kind' => 'Crm request invoice recieved flag detail  was changed to ' . $changeString, 'old_value' => '', 'updated_by' => Auth::user()->id, 'updated_at' => date('Y-m-d h:i:s'));
        DB::table('endorsement_crm_log')->insert($logArray);
        Session::flash('success', 'Successfully changed invoice recieve detail flag!!');

        return response()->json(array('status' => true));

        // return back()->with(['success' => 'Successfully changed invoice recieve detail flag!!', 'overviewtabselected' => 'crm']);
    }

    /**
     * 
     * @param Request $request
     * @param type $requestId
     * @return type
     */
    public function connectCrmRequestData(Request $request, $requestId) {
        $dateTime = date('Y-m-d h:i:s');
        $crmrequestObj = App\EndorsementCrm::find($requestId);
        $crmrequestObj->inv_flag = $request->get('recievedFlag');
        $crmrequestObj->updated_at = $dateTime;

        $crmrequestObj->inv_recieved_date = date('Y-m-d');
        $crmrequestObj->is_invoice_related = 1;

        $requestNum = $crmrequestObj->request_id;

        $crmrequestObj->save();

        $logArray = array('crm_id' => $requestId, 'kind' => 'Crm request invoice connection flag   was changed to TRUE', 'old_value' => '', 'updated_by' => Auth::user()->id, 'updated_at' => $dateTime);
        DB::table('endorsement_crm_log')->insert($logArray);

        if (!empty($request->get('connected_request'))) {


            DB::table('crm_endorsement')->whereIn('id', $request->get('connected_request'))
                    ->update(array('is_invoice_related' => 1, 'updated_at' => $dateTime, 'related_request' => $requestId . '###' . $requestNum, 'inv_flag' => 1, 'inv_recieved_date' => date('Y-m-d'), 'status' => 3
                            )
            );

            $relatedRequests = $request->get('connected_request');

            //update log of each request
            $logArray = array();
            foreach ($relatedRequests as $relatedRequest) {

                $logArray[] = array('crm_id' => $relatedRequest, 'kind' => 'Crm request status was changed to Close', 'old_value' => '', 'updated_by' => Auth::user()->id, 'updated_at' => $dateTime);
            }

            DB::table('endorsement_crm_log')->insert($logArray);
        }

        return back()->with(['success' => 'Successfully changed invoice connection flag!!', 'overviewtabselected' => 'crm']);
        // Session::flash('success', 'Successfully changed invoice recieve detail flag!!');
        // return response()->json(array('status' => true));
    }

    /**
     * 
     * @param Request $request
     * @return type
     */
    public function rejectPolicy(Request $request) {

        $policyId = $request->get("reject_policy_id");
        $policyData = DB::table('policies')->where('id', $policyId)->select('policy_status')->first();
        $policyNo = $policyData->policy_number;  
        $dateTime = date('Y-m-d h:i:s');
        $updateArray = array("policy_status" => 6,
            "updated_at" => $dateTime,
            "reject_reason" => $request->get("reject_reason")
        );
        DB::table('policies')->where('id', $policyId)->update($updateArray);
        //Endorsement Log
        $logArray = array("policy_id" => $policyId,
            "kind" => 'Policy status was changed to : Reject ',
            "edited_by" => Auth::user()->id,
            "oldvalue" => 'Policy posted',
            "edited_at" => $dateTime);
        DB::table('policy_log')->insert($logArray);
         $url = route('policyoverview', ['policyId' => $policyId]);
         $data = array('name' =>'Technical', 'link' =>$url, 'policy_no' =>$policyNo,'username' =>'Technical','status'=>'Reject');
         $templatename = 'emails.policystatusnotification';
         $maidetails['to'] = 'j.mani@dbroker.com.sa';
         $maidetails['name'] = 'Jinesh';
         $maidetails['subject'] = "Policy ".$policyNo ." status was changed to 'Reject'";
         //$this->send_email($maidetails, $data, $templatename);      
        

        return back()->with(['success' => 'Successfully rejected policy !!!!']);
    }

    /** Function to handle for send a message 
     * To send email
     * @param string $to
     * @param string $data
     */
    private function send_email($maidetails, $data, $template = null) {
        //New quote request is raised

        Mail::send($template, $data, function($message) use($maidetails) {
            $message->to($maidetails['to'], $maidetails['name'])->subject
                    ($maidetails['subject']);

            $message->from('info@dbroker.com.sa', 'diamondbroker');
        });
    }
    
    /**
     * 
     * @param Request $request
     * @param type $policyId
     * @param type $requestId
     * @return type
     */
    public function updateInslyFlag(Request $request, $policyId, $requestId) {

        $crmrequestObj = App\EndorsementCrm::find($requestId);
        $crmrequestObj->is_insly_entered = $request->get('recievedFlag');
        $crmrequestObj->updated_at = date('Y-m-d h:i:s');

        $changeString = ' No';
        if ($request->get('recievedFlag') == 1) {
            $changeString = ' Yes';
            $crmrequestObj->insly_entered_date = date('Y-m-d');
        }
        $crmrequestObj->save();

        $logArray = array('crm_id' => $requestId, 'kind' => 'Crm request insly entered flag detail  was changed to ' . $changeString, 'old_value' => '', 'updated_by' => Auth::user()->id, 'updated_at' => date('Y-m-d h:i:s'));
        DB::table('endorsement_crm_log')->insert($logArray);
        Session::flash('success', 'Successfully changed insly entered detail flag!!');

        return response()->json(array('status' => true));

       
    }

}
