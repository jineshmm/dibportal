<?php

namespace App\Http\Controllers\Invoice;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use App;
use App\Mail\dpportalmail;
use Mail;
use App\customer;
use App\policy;
use App\PolicyInstallment;
use App\Http\Controllers\Controller;
use Session;
use PDF;
use File;
use Illuminate\Support\Facades\Date;
use App\Http\Controllers\Dashboard\DashboardController;

/**
 * To handle the invoice related data
 * CRM Request Types are
 * Addition ,   CCHI Activation, Claim approval/Settlement ,Deletion ,Downgrade ,Updated member list ,Plate No Amendment ,Card Replacment ,CCHI Upload Status List ,MC Certificate ,Name Amendment ,
  Card Printer Request ,Invoices Request ,Upgrade, REQUEST ,INQUIRY ,announcement ,REQUEST SIGN ,Others

 * 
 * 
 */
class InvoiceController extends Controller{

    /**
     * To generate invoice of selected invoice
     * @param Request $request
     * @param integer $policyId
     * @return type
     */
    public function generatePolicyInvoice(Request $request, $policyId) {

        $installmentIds = $request->get('installmentIds');



        $allInstallmentDetails = DB::table('policy_intallment as im')
                        ->join('policies', 'policies.id', '=', 'im.policy_id')
                        ->leftJoin('customers as c', 'c.id', '=', 'policies.customer_id')
                        ->leftJoin('insurance_product as ip', 'ip.id', '=', 'policies.product_id')
                        ->leftJoin('insurer_details as ic', 'ic.id', '=', 'policies.insurer_id')
                        ->leftJoin('policy_endorsement as en', 'en.id', '=', 'im.endorsement_id')
                        ->select('im.*', 'en.endorsement_number', 'policies.policy_number', 'ic.insurer_name as insurarName', 'c.*', 'ip.product_name as productName', DB::raw("(Select CONCAT_WS(',',street_name, district_name,city_name,zip_code) from customer_address where customer_id = policies.customer_id limit 1) AS address"), 'policies.customer_id as customerId', 'policies.start_date as p_start_date', 'policies.end_date as p_end_date', 'im.id as installmentId')
                        ->where("im.policy_id", "=", $policyId)
                        ->whereIn('im.id', $installmentIds)
                        ->orderBy('im.start_date', 'asc')->get();

        $invoiceDetails = array();


        $policyData = array();
        $totalSum = 0;
        $filepath = '';
        $individualInvoiceDetails = array();
        $i = 0;
        foreach ($allInstallmentDetails as $key => $installments) {
            $filepath = 'uploads/Invoice/' . $installments->customerId . '/' . $policyId;
            File::isDirectory($filepath) or File::makeDirectory($filepath, 0777, true, true);
            $filename = 'Invoice' . "_" . $policyId . '_' . $key . '_' . date('Ymdhis') . '.pdf';
            $data = array('installmentDetails' => $installments);
            //PDF::loadView('Invoice/invoicepdf', $data)->setPaper('a4', 'portrait')->save('uploads/Invoice/' . $installments->customerId . '/' . $policyId . '/' . $filename);
            $policyData = ['name' => $installments->name, 'phone' => $installments->phone, 'email' => $installments->email, 'invoice_date' => $installments->start_date, 'due_date' => $installments->due_date, 'policy_number' => $installments->policy_number, 'productName' => $installments->productName, 'address' => $installments->address];
            $individualInvoiceDetails[$i]['description'] = $this->generateInvoiceDescription($installments);
            $individualInvoiceDetails[$i]['amount'] = $installments->amount;
            $individualInvoiceDetails[$i]['vat_amount'] = $installments->vat_amount;
            $individualInvoiceDetails[$i]['installment_id'] = $installments->installmentId;

            $totalSum = $totalSum + floatval($installments->amount + $installments->vat_amount);
            $i++;
        }

        //Consolidate invoice

        $filename = 'Invoice' . "_" . $policyId . '_' . date('Ymdhis') . '.pdf';
        $invoiceDetails['policy_id'] = $policyId;
        $invoiceDetails['generated_date'] = date('Y-m-d h:i');
        $invoiceDetails['paid_status'] = 0;
        $invoiceDetails['file_name'] = $filename;
        $invoiceDetails['invoice_sum'] = floatval($totalSum);
        $invoiceDetails['invoice_due_date'] = date('Y-m-d');

        $data = array('installmentDetails' => $allInstallmentDetails, 'policyDetails' => $policyData, 'individualInvoiceDetails' => $individualInvoiceDetails);
        PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('Invoice/consolidateinvoicepdf', $data)->setPaper('a4', 'portrait')->save($filepath . '/' . $filename);


        if (count($invoiceDetails) > 0) {
            $insertId = DB::table('policy_invoice')->insertGetId($invoiceDetails);
            if (count($individualInvoiceDetails) > 0) {
                foreach ($individualInvoiceDetails as $key => $individualInvoiceDetail) {
                    $individualInvoiceDetails[$key]["invoice_id"] = $insertId;
                }
                DB::table('policy_invoice_details')->insert($individualInvoiceDetails);
            }
        }


        Session::flash('success', 'Successfully created invoice for selected payments!!!');
        return response()->json(array('success' => true));
    }

    /**
     * To generate invoice description
     * @param object $installments
     * @return string
     */
    private function generateInvoiceDescription($installments) {
        $descriptionString = ucwords($installments->productName) . "," . ucwords($installments->insurarName) . "\n";
        if (empty($installments->endorsement_id)) {
            $descriptionString .= ", Policy no:" . $installments->policy_number . ",Period:" . date('d.m.Y', strtotime($installments->p_start_date)) . "-" . date('d.m.Y', strtotime($installments->p_end_date)) . ",Installment:" . date('d.m.Y', strtotime($installments->start_date)) . "-" . date('d.m.Y', strtotime($installments->end_date));
        } else {
            $descriptionString .= ", Policy no:" . $installments->policy_number . "," . date('d.m.Y', strtotime($installments->start_date)) . " , Endorsement:" . $installments->endorsement_number;
        }

        return $descriptionString;
    }

    /**
     * To display invoice description
     * @param type $invoiceId
     */
    public function invoiceOverviewDetails($invoiceId) {

        $invoiceDetails = DB::table('policy_invoice as iv')
                ->join('policies', 'policies.id', '=', 'iv.policy_id')
                ->join('customers as c', 'c.id', '=', 'policies.customer_id')
                ->leftJoin('insurance_product as ip', 'ip.id', '=', 'policies.product_id')
                ->leftJoin('policy_invoice_details as pi', 'pi.invoice_id', '=', 'iv.id')
                ->select('iv.*', 'policies.policy_number', 'c.name as customerName', 'ip.product_name as productName', DB::raw("(case iv.paid_status when '0' then 'Invoice Created' when '1' then 'Paid'  end) AS invoiceStatusString"), 'policies.customer_id as customerId', 'iv.id as invoiceId', 'pi.*', 'c.id as customer_id')
                ->where('iv.id', $invoiceId)
                ->get();

        //payments details
        $paymentDetails = DB::table('policy_invoice as iv')
                        ->join('policy_invoice_payment as ip', 'ip.invoice_id', '=', 'iv.id')
                        ->where('iv.id', $invoiceId)->select('invoice_sum', DB::raw("(select sum(payment_sum) from policy_invoice_payment where invoice_id=iv.id group by invoice_id) as paidsum"), DB::raw("(select count(id) from policy_invoice_payment where invoice_id=iv.id) as count"), DB::raw("(select payment_date from policy_invoice_payment where invoice_id=iv.id order by id desc limit 1) as lastPayment"))->first();


        $paymentMode = [1 => 'bank transfer', 2 => 'cash payment', 3 => 'credit card payment', 4 => 'cheque', 5 => 'rounding payment', 6 => 'insurer payment', 7 => 'offsetting entry'];
        $headTitle = 'Invoice:' . $invoiceDetails[0]->invoiceId;

        $data = array("title" => 'Dashboard',
            "invoicedetails" => $invoiceDetails,
            "headTitle" => $headTitle,
            "paymentMode" => $paymentMode,
            "paymentDetails" => $paymentDetails
        );


        return view('Invoice/overviewDetails', $data);
    }

    /**
     * To display invoice overview details
     * @param integer $invoiceId
     */
    public function invoiceOverview($invoiceId) {
        $invoiceDetails = DB::table('policy_invoice as iv')
                ->join('policies', 'policies.id', '=', 'iv.policy_id')
                ->join('customers as c', 'c.id', '=', 'policies.customer_id')
                ->leftJoin('insurance_product as ip', 'ip.id', '=', 'policies.product_id')
                ->leftJoin('policy_invoice_details as pi', 'pi.invoice_id', '=', 'iv.id')
                ->select('iv.*', 'policies.policy_number', 'c.name as customerName', 'ip.product_name as productName', DB::raw("(case iv.paid_status when '0' then 'Invoice Created' when '1' then 'Paid'  end) AS invoiceStatusString"), 'policies.customer_id as customerId', 'iv.id as invoiceId', 'pi.*', 'c.id as customer_id')
                ->where('iv.id', $invoiceId)
                ->get();

        //payments details
        $paymentDetails = DB::table('policy_invoice as iv')
                        ->join('policy_invoice_payment as ip', 'ip.invoice_id', '=', 'iv.id')
                        ->where('iv.id', $invoiceId)->select('invoice_sum', DB::raw("(select sum(payment_sum) from policy_invoice_payment where invoice_id=iv.id group by invoice_id) as paidsum"), DB::raw("(select count(id) from policy_invoice_payment where invoice_id=iv.id) as count"), DB::raw("(select payment_date from policy_invoice_payment where invoice_id=iv.id order by id desc limit 1) as lastPayment"))->first();


        $paymentMode = [1 => 'bank transfer', 2 => 'cash payment', 3 => 'credit card payment', 4 => 'cheque', 5 => 'rounding payment', 6 => 'insurer payment', 7 => 'offsetting entry'];
        $headTitle = 'Invoice:' . $invoiceDetails[0]->invoiceId;
        $breadcrumbDetails = array('url' => action('Dashboard\DashboardController@invoiceList'), 'title' => 'Invoices');
        $data = array("title" => 'Dashboard',
            "invoicedetails" => $invoiceDetails,
            "headTitle" => $headTitle,
            "paymentMode" => $paymentMode,
            "paymentDetails" => $paymentDetails, 'breadcrumb' => $breadcrumbDetails
        );


        return view('Invoice/overview', $data);
    }

    /**
     * To update invoice details 
     * @param Request $request
     * @param integer $invoiceId
     * @return type
     */
    public function updateInvoiceDetails(Request $request, $invoiceId) {

        $invoiceDetails['generated_date'] = date('Y-m-d', strtotime($request->get('invoice_date')));
        $invoiceDetails['invoice_due_date'] = date('Y-m-d', strtotime($request->get('invoice_due_date')));
        DB::table('policy_invoice')->where('id', $invoiceId)->update($invoiceDetails);
        // regenarate invoice and update the values
        $savedInvoiceDetails = DB::table('policy_invoice as iv')
                ->join('policies', 'policies.id', '=', 'iv.policy_id')
                ->join('customers as c', 'c.id', '=', 'policies.customer_id')
                ->leftJoin('insurance_product as ip', 'ip.id', '=', 'policies.product_id')
                ->leftJoin('policy_invoice_details as pi', 'pi.invoice_id', '=', 'iv.id')
                ->select('iv.*', 'policies.policy_number', 'c.name as customerName', 'ip.product_name as productName', DB::raw("(Select CONCAT_WS(',',street_name, district_name,city_name,zip_code) from customer_address where customer_id = policies.customer_id limit 1) AS address"), DB::raw("(case iv.paid_status when '0' then 'Invoice Created' when '2' then 'Paid'  end) AS invoiceStatusString"), 'policies.customer_id as customerId', 'iv.id as invoiceId', 'pi.*', 'c.id as customer_id', 'c.email as email')
                ->where('iv.id', $invoiceId)
                ->get();

        $policyData = ['name' => $savedInvoiceDetails[0]->customerName, 'email' => $savedInvoiceDetails[0]->email, 'invoice_date' => $savedInvoiceDetails[0]->generated_date, 'due_date' => $savedInvoiceDetails[0]->invoice_due_date, 'policy_number' => $savedInvoiceDetails[0]->policy_number, 'productName' => $savedInvoiceDetails[0]->productName, 'address' => $savedInvoiceDetails[0]->address];
        $individualInvoiceDetails = [];
        $installmentArray = [];
        foreach ($savedInvoiceDetails as $key => $details) {
            $individualInvoiceDetails[$key]['description'] = $details->description;
            $installmentArray[$key]['amount'] = $details->amount;
            $installmentArray[$key]['vat_amount'] = $details->vat_amount;
        }
        $installmentobj = json_decode(json_encode($installmentArray));

        $policyId = $savedInvoiceDetails[0]->policy_id;
        $filepath = 'uploads/Invoice/' . $savedInvoiceDetails[0]->customer_id . '/' . $policyId;
        $filename = 'Invoice' . "_" . $policyId . '_' . date('Ymdhis') . '.pdf';
        $data = array('installmentDetails' => $installmentobj, 'policyDetails' => $policyData, 'individualInvoiceDetails' => $individualInvoiceDetails);
        PDF::loadView('Invoice/consolidateinvoicepdf', $data)->setPaper('a4', 'portrait')->save($filepath . '/' . $filename);
        $newinvoiceDetails['file_name'] = $filename;
        DB::table('policy_invoice')->where('id', $invoiceId)->update($newinvoiceDetails);

        //log entry

        $logArray['changed_date'] = date('Y-m-d h:i');
        $logArray['invoice_id'] = $invoiceId;
        $logArray['kind'] = 'Invoice detail was changed ';
        $logArray['changed_by'] = Auth::user()->id;
        DB::table('policy_invoice_log')->insert($logArray);


        return back()->with(['success' => 'Successfully updated invoice details!']);
    }

    /**
     * To save invoice payment details
     * @param Request $request
     * @param integer $invoiceId
     */
    public function saveInvoicePayment(Request $request, $invoiceId) {
        if ($invoiceId == 0) {
            $invoiceId = $request->get('invoiceId');
        }

        $invoiceDetails = DB::table('policy_invoice as iv')
                ->join('policies', 'policies.id', '=', 'iv.policy_id')
                ->select('policies.customer_id', 'iv.policy_id')
                ->where('iv.id', $invoiceId)
                ->first();
         //Policy paid details
         
        $paidDetails['policy_id'] =  $invoiceDetails->policy_id;
        $paidDetails['paid_amount'] = floatval($request->get('payment_sum'));
        $paidDetails['paid_date'] = date('Y-m-d h:i');


        $paymentDetails['invoice_id'] = $invoiceId;
        $paymentDetails['payment_date'] = date('Y-m-d', strtotime($request->get('payment_date')));
        $paymentDetails['payment_transfer_type'] = $request->get('paymentmode');
        $paymentDetails['payment_sum'] = floatval($request->get('payment_sum'));
        $paymentDetails['payer_name'] = $request->get('payer_name');
        $paymentDetails['reference_number'] = $request->get('payment_reference_number');

        $paymentDetails['created_date'] = date('Y-m-d h:i');
        $paymentDetails['created_by'] = Auth::user()->id;
        if ($request->hasFile('payment_document')) {
            $uploadfile = $request->file('payment_document');
            $destinationPath = 'uploads/' . $invoiceDetails->customer_id . "/payments/";
            File::isDirectory($destinationPath) or File::makeDirectory($destinationPath, 0777, true, true);
            $path_parts = pathinfo($uploadfile->getClientOriginalName());
            $newfilename = $path_parts['filename'] . "_" . date('Ymdhis') . '.' . $path_parts['extension'];
            $paymentDetails['upload_file'] = $newfilename;
            $uploadfile->move($destinationPath, $newfilename);
        }

        DB::table('policy_invoice_payment')->insert($paymentDetails);
        DB::table('policy_paid_details')->insert($paidDetails);
        //log entry

        $logArray['changed_date'] = date('Y-m-d h:i');
        $logArray['invoice_id'] = $invoiceId;
        $logArray['kind'] = 'Payment detail was added : Amount:' . floatval($request->get('payment_sum')) . " SAR";
        $logArray['changed_by'] = Auth::user()->id;
        DB::table('policy_invoice_log')->insert($logArray);
        //Update related fields

        $this->updateInstallmentStatus($invoiceId);

        return back()->with(['success' => 'Successfully add invoice payment details!']);
    }

    /**
     * To list payment details
     * @param integer $invoiceId
     * @return type
     */
    public function listPayments($invoiceId) {
        $paymentDetails = DB::table('policy_invoice_payment as pm')
                        ->join('policy_invoice as iv', 'iv.id', '=', 'pm.invoice_id')
                        ->join('policies as p', 'p.id', '=', 'iv.policy_id')
                        ->select('pm.*', 'p.customer_id', DB::raw("(case pm.payment_transfer_type when 1 then 'bank transfer' when 2 then 'cash payment'  when 3 then 'credit card payment' when 4 then 'cheque' when 5 then 'rounding payment' when 6 then 'insurer payment'   else 'offsetting entry'   end) AS paymentmethodString"))
                        ->where("pm.invoice_id", "=", $invoiceId)
                        ->orderBy('pm.created_date', 'desc')->get();
        $data = array('paymentData' => $paymentDetails);
        $returnHTML = view('Invoice/paymentList', $data)->render();
        return response()->json(array('status' => true, 'content' => $returnHTML));
    }

    /**
     * To update installment status
     * @param integer $invoiceId
     */
    public function updateInstallmentStatus($invoiceId) {

        $getInstallmentDetails = DB::table('policy_invoice as iv')
                        ->where('iv.id', $invoiceId)->select('iv.id', 'invoice_sum', DB::raw("(select sum(payment_sum) from policy_invoice_payment where invoice_id=iv.id group by invoice_id) as paidsum"), DB::raw("(select group_concat(installment_id) from policy_invoice_details where invoice_id=iv.id group by invoice_id) as installmentIds"))->first();



$newIntallmentArray = ($getInstallmentDetails !==null) ?json_decode(json_encode($getInstallmentDetails), true):array();

        if (count($newIntallmentArray) > 0) {
            $updateId = [];
            $updateFlag = false;
            $invoiceSum = $getInstallmentDetails->invoice_sum;
            $totalPayment = (is_null($getInstallmentDetails->paidsum)) ? 0 : $getInstallmentDetails->paidsum;
            $updateId = (is_null($getInstallmentDetails->installmentIds)) ? 0 : explode(",", $getInstallmentDetails->installmentIds);
            if ($invoiceSum > 0 && $totalPayment >= $invoiceSum) {
                $updateFlag = true;
            }

            if ($updateFlag) {

                DB::table('policy_intallment')->whereIn('id', $updateId)->update(['paid_status' => 1]);
                DB::table('policy_invoice')->where('id', $getInstallmentDetails->id)->update(['paid_status' => 1]);
                //log entry

                $logArray['changed_date'] = date('Y-m-d h:i');
                $logArray['invoice_id'] = $getInstallmentDetails->id;
                $logArray['kind'] = 'Invoice status was changed to paid';
                $logArray['changed_by'] = Auth::user()->id;
                DB::table('policy_invoice_log')->insert($logArray);
            } else if (count($updateId) > 0) {
                DB::table('policy_intallment')->whereIn('id', $updateId)->update(['paid_status' => 0]);
            }
        }
    }

    /**
     * To delete payments
     * @param integer $invoiceId
     * @param integer $paymentId
     * @return type
     */
    public function deletePayment($invoiceId, $paymentId) {
        $paymentAmount = DB::table('policy_invoice_payment')->where('id', $paymentId)->select('payment_sum')->first();
        DB::table('policy_invoice_payment')->where('id', $paymentId)->delete();
        $this->updateInstallmentStatus($invoiceId);

        //log entry

        $logArray['changed_date'] = date('Y-m-d h:i');
        $logArray['invoice_id'] = $invoiceId;
        $logArray['kind'] = 'Payment detail was deleted: Amount ' . $paymentAmount->payment_sum . "SAR";
        $logArray['changed_by'] = Auth::user()->id;
        DB::table('policy_invoice_log')->insert($logArray);

        Session::flash('success', 'Successfully deleted payment!!!');
        return response()->json(array('success' => true));
    }

    /**
     * To list payment details
     * @param integer $invoiceId
     * @return type
     */
    public function listLog($invoiceId) {
        $logDetails = DB::table('policy_invoice_log as log')
                        ->join('policy_invoice as iv', 'iv.id', '=', 'log.invoice_id')
                        ->leftJoin('users as u', 'u.id', '=', 'log.changed_by')
                        ->select('log.*', 'u.name as userName')
                        ->where("log.invoice_id", "=", $invoiceId)
                        ->orderBy('log.changed_date', 'desc')->get();
        $data = array('logdata' => $logDetails);
        $returnHTML = view('Invoice/logData', $data)->render();
        return response()->json(array('status' => true, 'content' => $returnHTML));
    }

    /**
     * 
     * @param Request $request
     * @return type
     */
    public function getAllInvoices(Request $request) {
        $customerId = $request->get('customer_id', 0);
        $invoiceDetails = DB::table('policy_invoice as iv')
                ->join('policies as p', 'p.id', '=', 'iv.policy_id')
                ->join('customers as c', 'c.id', '=', 'p.customer_id')
                ->select('iv.id')
                ->where('p.customer_id', $customerId)
                ->where('iv.paid_status', '=', 0)
                ->get();

        $options = '<option value="">------- select invoice--------</option>';
        if (count($invoiceDetails) > 0) {
            foreach ($invoiceDetails as $details) {
                $options .= '<option value="' . $details->id . '">' . $details->id . '</option>';
            }
        }

        return response()->json(array('optionstring' => $options));
    }

    /**
     * 
     */
    public function debtManagementDetails($invoiceId) {
        $debtDetails = DB::table('invoice_bebt_management as dm')
                        ->join('policy_invoice as iv', 'iv.id', '=', 'dm.invoice_id')
                        ->leftJoin('users as u', 'u.id', '=', 'dm.created_by')
                        ->select('dm.*', 'u.name as userName')
                        ->where("dm.invoice_id", "=", $invoiceId)
                        ->orderBy('dm.created_date', 'desc')->get();
        $customerData = DB::table('policy_invoice as iv')
                ->join('policies as p', 'p.id', '=', 'iv.policy_id')
                ->join('customers as c', 'c.id', '=', 'p.customer_id')
                ->select('c.*')
                ->where('iv.id', $invoiceId)                
                ->first();
   
        $data = array('debtmanagementDetails' => $debtDetails, 'invoiceId' => $invoiceId,'customer'=>$customerData);
        $returnHTML = view('Invoice/debtmanagement', $data)->render();
        return response()->json(array('status' => true, 'content' => $returnHTML));
    }

    public function saveDebtCall(Request $request, $invoiceId) {
        $insertArray = array();
        $insertArray['invoice_id'] = $invoiceId;
        $insertArray['created_date'] = date('Y-m-d h:i');
        $message = '';
        $insertArray['created_by'] = Auth::user()->id;
        if ($request->get('debt_mgt_type') == 'phone') {
            $insertArray['management_type'] = 'call';
            $insertArray['info'] = $request->get('comment');
            $insertArray['recipient'] = $request->get('debt_mgt_name');
            $message = 'Successfully entered debt management call!!!';
            DB::table('invoice_bebt_management')->insert($insertArray);
        } else {
            $insertArray['management_type'] = 'email';
            $insertArray['info'] = $request->get('comment');
            $insertArray['recipient'] = $request->get('debt_mgt_name');
            $insertArray['subject'] = $request->get('debt_mgt_subject');
            $insertArray['attach_invoice_flag'] = ($request->get('invoice_attach_flag') == '') ? 0 : $request->get('invoice_attach_flag');
            $insertArray['extend_due_date'] = ($request->get('debt_mgt_extend_due_date') == '') ? 0 : $request->get('debt_mgt_extend_due_date');
            $insertId = DB::table('invoice_bebt_management')->insertGetId($insertArray);
            $message = 'Successfully send notice!!!';
            if ($insertId > 0) {
                $this->sendDebtEmail($request, $invoiceId);
            }
        }


        return back()->with(['success' => 'Successfully enter debt management call!!!']);
    }

    private function sendDebtEmail($request, $invoiceId) {
        $user['customerName'] = $request->get('customer_name');
        $user['email'] = $request->get('debt_mgt_name');
         $user['subject'] =  $request->get('debt_mgt_subject');
          $user['content'] =  $request->get('comment');
           $user['invoiceId'] =  $invoiceId;
        Mail::send('emails.reminder', $user, function ($m) use ($user) {
            $m->from('info@dbroker.com', 'dbroker');

            $m->to($user['email'], $user['customerName'])->subject($user['subject']);
        });
    }

}
