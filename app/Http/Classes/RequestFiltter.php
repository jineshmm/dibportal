<?php

namespace App\Http\Classes;

use Illuminate\Support\Facades\DB;
use Session;
use Auth;
use Illuminate\Support\Carbon;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RequestFiltter
 *
 * @author j.mani
 */
class RequestFiltter {

    /**
     * 
     * @param type $request
     * @param type $filterflag
     * @return type
     */
    public function getSalesRequest($request, $filterflag = false) {

        $quoteRequest = DB::table('crm_main_table as r')
                ->leftJoin('crm_task_table as t', 'r.id', '=', 't.crm_main_id')
                ->leftJoin('crm_request_table as rt', 'r.id', '=', 'rt.crm_id')
                ->leftJoin('customers as c', 'c.id', '=', 'r.customer_id')
                ->orderBy('c.updated_at', 'desc')
                ->select('r.*', 'r.id as mainId', 'c.name as customerName', 't.*', 'rt.*', DB::raw("(case r.status when '0' then 'New' when '1' then 'New' when '2' then 'Technical review' when '3' then 'Document approved' when '4' then 'Quote upload'  when '5' then 'Revise quotation'  when '6' then 'Request policy'  when '7' then 'Policy upload'  when '8' then 'Reject'  when '9' then 'Completed'  when '10' then 'Lost' when 11 then 'Pending with sales'   else 'Pending with client'  end) AS statusString"), DB::raw('DATEDIFF(r.updated_date,r.created_date) as daydiff'));


        if ($filterflag) {
            $conditionFilter = json_decode(Session::get('salesrequestFilterCondition_' . Auth::user()->id), true);
            $resultArray = $this->generateQuery($conditionFilter, $quoteRequest);
        } else {
            $whereArray = [];
            $orWhereArray =[];
            $sessionWhereCondition = [];
            if ($request->get('startDate') != '' && $request->get('endDate') != '') {
                $quoteRequest->whereBetween('r.created_date', [$request->get('startDate'), $request->get('endDate')]);
                $sessionWhereCondition['betweenCondition']['r.created_date'][] = $request->get('startDate');
                $sessionWhereCondition['betweenCondition']['r.created_date'][] = $request->get('endDate');
            } elseif ($request->get('startDate') != '') {
                $whereArray[] = ['r.created_date', '=', $request->get('startDate')];
            } elseif ($request->get('endDate') != '') {
                $whereArray[] = ['r.created_date', '=', $request->get('endDate')];
            } else {
                
            }

            if ($request->get('customerId') > 0) {
                $whereArray[] = ['r.customer_id', '=', $request->get('customerId')];
            }
            if ($request->get('request_status') != '') {
                $status = json_decode($request->get('request_status'));
                if (is_array($status)) {
                    $quoteRequest->whereIn('r.status', $status);
                    $sessionWhereCondition['inCondition']['r.status'] = json_encode($status);
                } else {
                    $whereArray[] = ['r.status', $status];
                }
            }

            if (in_array('ROLE_SALES_MANAGER', Auth::user()->roles) || in_array('ROLE_TECHNICAL_MANAGER', Auth::user()->roles)) {
                
            } else if (in_array('ROLE_SALES', Auth::user()->roles) || in_array('ROLE_TECHNICAL', Auth::user()->roles)) {
                $quoteRequest->where('r.user_id', Auth::user()->id)->orWhere('r.assigned_to', Auth::user()->id);
                $whereArray[] = ['r.user_id', Auth::user()->id];
                $orWhereArray[] = ['r.assigned_to', Auth::user()->id];
                $sessionWhereCondition['orwhere'] = json_encode($orWhereArray);
            } else {
                
            }



            if (count($whereArray) > 0) {
                $quoteRequest->where($whereArray);
                $sessionWhereCondition['whereCondition'] = json_encode($whereArray);
            }
            Session::put('salesrequestFilterCondition_' . Auth::user()->id, json_encode($sessionWhereCondition));

            $resultArray = $quoteRequest->get();
        }


        return $resultArray;
    }

    /**
     * 
     * @param type $conditionFilter
     * @param type $quoteRequest
     * @return type
     */
    private function generateQuery($conditionFilter, $quoteRequest) {
        if ($conditionFilter && count($conditionFilter) > 0) {
            foreach ($conditionFilter as $key => $conditionval) {
                switch ($key) {
                    case 'betweenCondition':
                        foreach ($conditionval as $betKey => $betVal) {
                            $quoteRequest->whereBetween($betKey, [$betVal[0], $betVal[1]]);
                        }

                        break;
                    case 'inCondition':
                        foreach ($conditionval as $inKey => $inVal) {
                            $quoteRequest->whereIn($inKey, json_decode($inVal));
                        }

                        break;
                    case 'wheredate':
                        $quoteRequest->whereDate(json_decode($conditionval));
                        break;
                    case 'orWhere':
                        $quoteRequest->orWhere(json_decode($conditionval));
                        break;
                    default:
                        $quoteRequest->where(json_decode($conditionval));
                }
            }
        }
 
        return $quoteRequest->get();
    }

    /**
     * 
     * @param type $request
     * @param type $filterflag
     * @return type
     */
    public function getLeadsData($request, $type = 'lead', $filterflag = false) {
        $customerDetails = DB::table('customers as c')
                ->leftJoin('policies as p', 'p.customer_id', '=', 'c.id')
                ->leftJoin('customer_address as ca', 'c.id', '=', 'ca.customer_id')
                ->leftJoin('customer_contact_person_info as cp', 'c.id', '=', 'cp.customer_id')
                ->leftJoin('users as u', 'c.customer_management_user', '=', 'u.id');
        if ($type == 'lead') {
            $customerDetails->where('c.policy_flag', '=', 0);
            $customerDetails->groupby('c.id');
        } else {
            $customerDetails->where('c.policy_flag', '=', 1);
            $customerDetails->whereNotNull('p.policy_number');
            $customerDetails->groupby('p.id');
        }
        $customerDetails->orderBy('c.updated_at', 'desc');

        $customerDetails->select('c.*', 'ca.*', 'cp.*', 'u.*', 'p.*', 'c.id as customId', 'c.email as customerEmail', 'c.phone as customerPhone', 'cp.email as contactEmail', 'cp.phone as contactPhone', 'u.name as userName', 'c.name as customerName', 'ca.id as addressId', 'c.created_at as createdAt', 'u.id as userId');

        if ($filterflag) {
            $conditionFilter = json_decode(Session::get('salesleadFilterCondition_' . Auth::user()->id), true);
            $resultArray = $this->generateLeadQuery($conditionFilter, $customerDetails);
        } else {
            $whereArray = [];
            $sessionWhereCondition = [];

            if ($request->get('startDate') != '' && $request->get('endDate') != '') {
                $customerDetails->whereBetween('c.created_at', [$request->get('startDate'), $request->get('endDate')]);
                $sessionWhereCondition['betweenCondition']['c.created_at'][] = $request->get('startDate');
                $sessionWhereCondition['betweenCondition']['c.created_at'][] = $request->get('endDate');
            } elseif ($request->get('startDate') != '') {
                $whereArray[] = ['c.created_at', '<=', Carbon::parse($request->get('startDate'))->format('Y-m-d')];
            } elseif ($request->get('endDate') != '') {
                $whereArray[] = ['c.created_at', '<=', $request->get('endDate')];
            } else {
                
            }

            if ($request->get('customerId') > 0) {
                $whereArray[] = ['c.id', '=', $request->get('customerId')];
            }

            if ($request->get('managerId') > 0) {
                $whereArray[] = ['u.id', '=', $request->get('managerId')];
            }



            if ($request->get('request_type') != '') {
                $status = $request->get('request_type');
                if (is_array($status)) {
                    $customerDetails->whereIn('c.type', $status);
                    $sessionWhereCondition['inCondition']['c.type'] = json_encode($status);
                } else {
                    $whereArray[] = ['c.type', $status];
                    $customerDetails->where('c.type', $status);
                }
            }
            if ($request->get('customergroup') != '') {
                $whereArray[] = ['c.customer_group', '=', $request->get('customergroup')];
            }
            if (in_array('ROLE_SALES_MANAGER', Auth::user()->roles) || in_array('ROLE_TECHNICAL_MANAGER', Auth::user()->roles)) {
                
            } else if (in_array('ROLE_SALES', Auth::user()->roles) || in_array('ROLE_TECHNICAL', Auth::user()->roles)) {
                $customerDetails->where('c.created_user', Auth::user()->id);
                $whereArray[] = ['c.created_user', Auth::user()->id];
            } else {
                
            }


            if (count($whereArray) > 0) {
                $customerDetails->where($whereArray);
                $sessionWhereCondition['whereCondition'] = json_encode($whereArray);
            }
            Session::put('salesleadFilterCondition_' . Auth::user()->id, json_encode($sessionWhereCondition));

            $resultArray = $customerDetails->get();
        }


        return $resultArray;
    }

    /**
     * 
     * @param type $conditionFilter
     * @param type $customerDetails
     * @return type
     */
    private function generateLeadQuery($conditionFilter, $customerDetails) {


        if ( $conditionFilter && count($conditionFilter) > 0 ) {
            foreach ($conditionFilter as $key => $conditionval) {
                switch ($key) {
                    case 'betweenCondition':
                        foreach ($conditionval as $betKey => $betVal) {
                            $customerDetails->whereBetween($betKey, [$betVal[0], $betVal[1]]);
                        }

                        break;
                    case 'inCondition':
                        foreach ($conditionval as $inKey => $inVal) {
                            $customerDetails->whereIn($inKey, json_decode($inVal));
                        }

                        break;
                    default:
                        $customerDetails->where(json_decode($conditionval));
                }
            }
        }

        return $customerDetails->get();
    }

    /**
     * 
     * @param type $request
     */
    public function getOperationRequest($request, $filterflag = false) {
        $endorsementRequest = DB::table('crm_endorsement as ecr')
                ->join('policies as p', 'ecr.policy_id', '=', 'p.id')
                ->join('customers as c', 'c.id', '=', 'p.customer_id')
                ->leftJoin('users as u', 'u.id', '=', 'ecr.created_by')
                ->select('ecr.*', 'u.name as userName', 'c.*', DB::raw("(case ecr.status when 3 then 'Resolved' when 2 then 'Under process'  when 4 then 'Pending from insurer' when 5 then 'pending from client' when 6 then 'Completed with invoice' when 7 then 'Completed by client request' when 8 then 'Completed without invoice'   when 9 then 'Awaiting invoice' when 10 then 'Closed' else 'Open'   end) AS statusString"), 'p.policy_number', 'c.name as customerName', 'ecr.created_at as createdAt', 'ecr.updated_at as updatedAt', 'ecr.type as etype', DB::raw('DATEDIFF(ecr.updated_at,ecr.created_at) as daydiff'))
        ;



        if ($filterflag) {
            $conditionFilter = json_decode(Session::get('operationrequestFilterCondition_' . Auth::user()->id), true);

            $requestObj = $this->generateOperationRequestQuery($conditionFilter, $endorsementRequest);
            $resultArray = $requestObj->get();
        } else {
            $whereArray = [];
            $sessionWhereCondition = [];

            if ($request->get('startDate') != '' && $request->get('endDate') != '') {
                $endorsementRequest->whereBetween('ecr.created_at', [$request->get('startDate'), $request->get('endDate')]);
                $sessionWhereCondition['betweenCondition']['ecr.created_at'][] = $request->get('startDate');
                $sessionWhereCondition['betweenCondition']['ecr.created_at'][] = $request->get('endDate');
            } elseif ($request->get('startDate') != '') {
                $whereArray[] = ['ecr.created_at', '<=', Carbon::parse($request->get('startDate'))->format('Y-m-d')];
            } elseif ($request->get('endDate') != '') {
                $whereArray[] = ['ecr.created_at', '<=', $request->get('endDate')];
            } else {
                
            }



            if ($request->get('customerId') > 0) {
                $whereArray[] = ['c.id', '=', $request->get('customerId')];
            }





            if ($request->get('endorsement_type') != '') {
                $status = $request->get('endorsement_type');

                $whereArray[] = ['ecr.type', $status];
            }
            if ($request->get('endorsement_status') != '') {
                $status = $request->get('endorsement_status');

                $whereArray[] = ['ecr.status', $status];
            }
            if (in_array('ROLE_SALES_MANAGER', Auth::user()->roles) || in_array('ROLE_TECHNICAL_MANAGER', Auth::user()->roles)) {
                
            } else if (in_array('ROLE_SALES', Auth::user()->roles) || in_array('ROLE_TECHNICAL', Auth::user()->roles)) {
                $endorsementRequest->where('ecr.assign_to', Auth::user()->id);
                $whereArray[] = ['ecr.assign_to', Auth::user()->id];
            } else {
                
            }


            if (count($whereArray) > 0) {
                $endorsementRequest->where($whereArray);
                $sessionWhereCondition['whereCondition'] = json_encode($whereArray);
            }
    
            Session::put('operationrequestFilterCondition_' . Auth::user()->id, json_encode($sessionWhereCondition));

            $resultArray = $endorsementRequest->orderBy('ecr.updated_at')->get();
        }


        return $resultArray;
    }

    /**
     * 
     * @param type $conditionFilter
     * @param type $endorsementRequest
     * @return type
     */
    private function generateOperationRequestQuery($conditionFilter, $endorsementRequest) {


        if ($conditionFilter && count($conditionFilter) > 0) {
            foreach ($conditionFilter as $key => $conditionval) {
                switch ($key) {
                    case 'betweenCondition':
                        foreach ($conditionval as $betKey => $betVal) {
                            $endorsementRequest->whereBetween($betKey, [$betVal[0], $betVal[1]]);
                        }

                        break;
                    case 'inCondition':
                        foreach ($conditionval as $inKey => $inVal) {
                            $endorsementRequest->whereIn($inKey, json_decode($inVal));
                        }

                        break;
                    case 'extrawhereArray':
                        if ($conditionval == 60) {

                            $endorsementRequest->where(DB::raw('DATEDIFF(p.end_date,now())'), '<=', 60);
                        } else if ($conditionval == 90) {
                            $endorsementRequest->where(DB::raw('DATEDIFF(p.end_date,now())'), '>', 60);
                            $endorsementRequest->where(DB::raw('DATEDIFF(p.end_date,now())'), '<=', 90);
                        } else {

                            $endorsementRequest->where(DB::raw('DATEDIFF(p.end_date,now())'), '>', 90);
                        }

                        break;


                    default:
                        $endorsementRequest->where(json_decode($conditionval));
                }
            }
        }

        return $endorsementRequest;
    }

    /**
     * 
     * @param type $request
     * @param type $filterflag
     * @return type
     */
    public function getComplaintReport($request, $filterflag = false) {
        $allComplaintsDetails = DB::table('policy_complaints as pc')
                ->join('policies as p', 'pc.policy_id', '=', 'p.id')
                ->leftJoin('users as u', 'u.id', '=', 'pc.handle_user')
                ->leftJoin('customers as c', 'c.id', '=', 'pc.client_id')
                ->leftJoin('users as us', 'us.id', '=', 'pc.created_by')
                ->select('pc.*', 'u.name as handleUser', 'us.name as userName', DB::raw("(case pc.request_status when 1 then 'Open'  else 'Closed'   end) AS statusString"), DB::raw("(case pc.request_validity when 1 then 'Valid'  else 'Invalid'   end) AS complaintValidity"), 'p.policy_number', DB::raw("(case pc.compliant_type when 1 then 'Deletion delay' when 2 then 'Treatment rejection by provider' else 'Others'   end) AS complaintType"), "c.name as clientName", DB::raw('DATEDIFF(pc.updated_at,pc.created_at) as daydiff'));


        if ($filterflag) {
            $conditionFilter = json_decode(Session::get('policycompliantFilterCondition_' . Auth::user()->id), true);

            $requestObj = $this->generateOperationRequestQuery($conditionFilter, $allComplaintsDetails);
            dd($requestObj->toSql(),$requestObj->getBindings());
            $resultArray = $requestObj->get();
        } else {
            $whereArray = [];
            $sessionWhereCondition = [];

            if ($request->get('startDate') != '' && $request->get('endDate') != '') {
                $allComplaintsDetails->whereBetween('pc.requested_date', [$request->get('startDate'), $request->get('endDate')]);
                $sessionWhereCondition['betweenCondition']['pc.requested_date'][] = $request->get('startDate');
                $sessionWhereCondition['betweenCondition']['pc.requested_date'][] = $request->get('endDate');
            } elseif ($request->get('startDate') != '') {
                $whereArray[] = ['pc.requested_date', '<=', Carbon::parse($request->get('startDate'))->format('Y-m-d')];
            } elseif ($request->get('endDate') != '') {
                $whereArray[] = ['pc.requested_date', '<=', $request->get('endDate')];
            } else {
                
            }

            if ($request->get('customerId') > 0) {
                $whereArray[] = ['c.id', '=', $request->get('customerId')];
            }

            if ($request->get('complaint_status') != '') {
                $status = $request->get('complaint_status');

                $whereArray[] = ['pc.request_status', $status];
            }
            if ($request->get('complaint_validity') != '') {
                $vaidity = $request->get('complaint_validity');
                $whereArray[] = ['pc.request_validity', $vaidity];
            }


            if (count($whereArray) > 0) {
                $allComplaintsDetails->where($whereArray);
                $sessionWhereCondition['whereCondition'] = json_encode($whereArray);
            }
            Session::put('policycompliantFilterCondition_' . Auth::user()->id, json_encode($sessionWhereCondition));

            $resultArray = $allComplaintsDetails->orderBy('pc.updated_at')->get();
        }


        return $resultArray;
    }

    /**
     * 
     * @param type $request
     * @param type $filterflag
     * @return type
     */
    public function getClaimReport($request, $filterflag = false) {
        $claimDetails = DB::table('policy_claims as pc')
                ->join('policies as p', 'p.id', '=', 'pc.policy_id')
                ->join('customers as c', 'pc.customer_id', '=', 'c.id')
                ->leftJoin('users as u', 'u.id', '=', 'pc.claim_handler')
                ->select('pc.*', 'c.name as customerName', 'p.policy_number', 'c.id_code', 'c.customer_code', DB::raw("(SELECT CONCAT(
  '[',GROUP_CONCAT(JSON_OBJECT('name',pci.name,'type',(case pci.claimant_type when 1 then 'Policy holder' when 2 then 'Death claim' when 3 then 'Medical claim' when 4 then 'Motor claim' else 'Travel claim' end))),
  ']'
) AS list FROM policy_claimant_info pci
                                WHERE pci.claim_id = pc.id
                                GROUP BY pci.claim_id) as claimant"), 'u.name as claimHandler', DB::raw("(case pc.status when 1 then '[Open] Awaiting info/documents' when 2 then '[Open]Claim reopened' when 3 then '[Open] Awaiting repair approval from insurer' when 4 then '[Open] Awaiting repair check from from insurer' when 5 then '[Open] Under Process With Insurer' when 6 then '[Open] Repair Approval Sent to the client / Awaiting Repair Invoices from the client' when 7 then '[Closed] Partial Approval' when 8 then '[Closed] Claim settled' when 9 then '[Closed] Claim within excess' when 10 then '[Closed] Claim no longer pursued' when 11 then '[Closed] Late Submission' when 12 then '[Closed] Not Covered Under the Policy' when 13 then '[Closed] Not Covered - Policy Terms &amp; Conditions' end) AS statusString"), 'pc.updated_date as updatedDate', DB::raw('DATEDIFF(pc.updated_date,pc.created_date) as daydiff'));



        if ($filterflag) {
            $conditionFilter = json_decode(Session::get('claimFilterCondition_' . Auth::user()->id), true);
            $requestObj = $this->generateOperationRequestQuery($conditionFilter, $claimDetails);
            $resultArray = $requestObj->get();
        } else {
            $whereArray = [];
            $sessionWhereCondition = [];

            if ($request->get('startDate') != '' && $request->get('endDate') != '') {
                $claimDetails->whereBetween('pc.incident_date', [$request->get('startDate'), $request->get('endDate')]);
                $sessionWhereCondition['betweenCondition']['pc.incident_date'][] = $request->get('startDate');
                $sessionWhereCondition['betweenCondition']['pc.incident_date'][] = $request->get('endDate');
            } elseif ($request->get('startDate') != '') {
                $whereArray[] = ['pc.incident_date', '<=', Carbon::parse($request->get('startDate'))->format('Y-m-d')];
            } elseif ($request->get('endDate') != '') {
                $whereArray[] = ['pc.incident_date', '<=', $request->get('endDate')];
            } else {
                
            }

            if ($request->get('customerId') > 0) {
                $whereArray[] = ['c.id', '=', $request->get('customerId')];
            }

            if ($request->get('claim_status') != '') {
                $status = $request->get('claim_status');

                $whereArray[] = ['pc.status', $status];
            }



            if (count($whereArray) > 0) {
                $claimDetails->where($whereArray);
                $sessionWhereCondition['whereCondition'] = json_encode($whereArray);
            }
            Session::put('claimFilterCondition_' . Auth::user()->id, json_encode($sessionWhereCondition));

            $resultArray = $claimDetails->orderBy('pc.updated_date', 'desc')->get();
        }


        return $resultArray;
    }

    /**
     * 
     * @param type $request
     * @param type $filterflag
     */
    public function getEndorsementReport($request, $filterflag = false) {
        $approvedEndorsementDetails = DB::table('policy_endorsement as pe')
                ->join('policies as p', 'p.id', '=', 'pe.policy_id')
                ->select('pe.*', DB::raw("(case pe.endorsement_type when 1 then 'Addition' when 4 then 'Deletion'  when 5 then 'Downgrade' when 14 then 'Upgrade'   end) AS typeString"), DB::raw('DATE_FORMAT(pe.start_date, "%d.%m.%Y") as formatted_startDate'), DB::raw('DATE_FORMAT(pe.issue_date, "%d.%m.%Y") as formatted_issueDate'), 'p.policy_number', 'p.end_date as expiry_date', DB::raw("(case pe.endorsement_status when 2 then 'Approved' when 3 then 'Rejected' when 1 then 'Waiting for approval' end) AS statusString"));



        if ($filterflag) {
            $conditionFilter = json_decode(Session::get('endorsementFilterCondition_' . Auth::user()->id), true);

            $requestObj = $this->generateOperationRequestQuery($conditionFilter, $approvedEndorsementDetails);
            $resultArray = $requestObj->get();
        } else {
            $whereArray = [];
            $sessionWhereCondition = [];
            //INCEPTION DATE
            if ($request->get('startDate') != '' && $request->get('endDate') != '') {
                $approvedEndorsementDetails->whereBetween('pe.start_date', [$request->get('startDate'), $request->get('endDate')]);
                $sessionWhereCondition['betweenCondition']['pe.start_date'][] = $request->get('startDate');
                $sessionWhereCondition['betweenCondition']['pe.start_date'][] = $request->get('endDate');
            } elseif ($request->get('startDate') != '') {
                $whereArray[] = ['pe.start_date', '<=', Carbon::parse($request->get('startDate'))->format('Y-m-d')];
            } elseif ($request->get('endDate') != '') {
                $whereArray[] = ['pe.start_date', '<=', $request->get('endDate')];
            } else {
                
            }

            //END DATE

            if ($request->get('end_startDate') != '' && $request->get('end_endDate') != '') {
                $approvedEndorsementDetails->whereBetween('p.end_date', [$request->get('end_startDate'), $request->get('end_endDate')]);
                $sessionWhereCondition['betweenCondition']['p.end_date'][] = $request->get('end_startDate');
                $sessionWhereCondition['betweenCondition']['p.end_date'][] = $request->get('end_endDate');
            } elseif ($request->get('end_startDate') != '') {
                $whereArray[] = ['p.end_date', '<=', Carbon::parse($request->get('end_startDate'))->format('Y-m-d')];
            } elseif ($request->get('end_endDate') != '') {
                $whereArray[] = ['p.end_date', '<=', $request->get('end_endDate')];
            } else {
                
            }



            //ISSUE DATE
            if ($request->get('issue_startDate') != '' && $request->get('issue_endDate') != '') {
                $approvedEndorsementDetails->whereBetween('pe.issue_date', [$request->get('issue_startDate'), $request->get('issue_endDate')]);
                $sessionWhereCondition['betweenCondition']['pe.issue_date'][] = $request->get('issue_startDate');
                $sessionWhereCondition['betweenCondition']['pe.issue_date'][] = $request->get('issue_endDate');
            } elseif ($request->get('issue_startDate') != '') {
                $whereArray[] = ['pe.issue_date', '<=', Carbon::parse($request->get('issue_startDate'))->format('Y-m-d')];
            } elseif ($request->get('issue_endDate') != '') {
                $whereArray[] = ['pe.issue_date', '<=', $request->get('issue_endDate')];
            } else {
                
            }

            //DUE DATE
            if ($request->get('due_startDate') != '' && $request->get('due_endDate') != '') {
                $approvedEndorsementDetails->whereBetween('pe.due_date', [$request->get('due_startDate'), $request->get('due_endDate')]);
                $sessionWhereCondition['betweenCondition']['pe.due_date'][] = $request->get('due_startDate');
                $sessionWhereCondition['betweenCondition']['pe.due_date'][] = $request->get('due_endDate');
            } elseif ($request->get('due_startDate') != '') {
                $whereArray[] = ['pe.due_date', '<=', Carbon::parse($request->get('due_startDate'))->format('Y-m-d')];
            } elseif ($request->get('due_endDate') != '') {
                $whereArray[] = ['pe.due_date', '<=', $request->get('due_endDate')];
            } else {
                
            }

            if ($request->get('customerId') > 0) {
                $whereArray[] = ['p.customer_id', '=', $request->get('customerId')];
            }

            if ($request->get('endorsement_type') != '') {
                $type = $request->get('endorsement_type');
                $whereArray[] = ['pe.endorsement_type', $type];
            }

            if ($request->get('endorsement_status') != '') {
                $status = $request->get('endorsement_status');
                $whereArray[] = ['pe.endorsement_status', $status];
            }

            if (count($whereArray) > 0) {
                $approvedEndorsementDetails->where($whereArray);
                $sessionWhereCondition['whereCondition'] = json_encode($whereArray);
            }
            Session::put('endorsementFilterCondition_' . Auth::user()->id, json_encode($sessionWhereCondition));

            $resultArray = $approvedEndorsementDetails->orderBy('pe.created_at')->get();
        }


        return $resultArray;
    }

    /**
     * 
     * @param type $request
     * @param type $filterflag
     * @return type
     */
    public function getTechnicalRequest($request, $filterflag = false) {
        $quoteRequest = DB::table('crm_main_table as r')
                ->leftJoin('crm_task_table as t', 'r.id', '=', 't.crm_main_id')
                ->leftJoin('crm_request_table as rt', 'r.id', '=', 'rt.crm_id')
                ->leftJoin('customers as c', 'c.id', '=', 'r.customer_id')
                ->leftJoin('line_of_business as lb', 'lb.id', '=', 'r.crm_line_of_business')
                ->leftJoin('users as u', 'u.id', '=', 'r.user_id')
                ->leftJoin('users as us', 'us.id', '=', 'c.technical_handler')
                ->leftJoin('policies as p', function($join) {
                    $join->on('p.id', '=', 'r.policy_id');
                    $join->where('p.policy_status', '=', 2);
                })
                ->leftJoin('insurer_details as ins', 'ins.id', '=', 'p.insurer_id')
                ->select('r.*', 'ins.insurer_name', 'lb.title as lineofbusinesstitle', DB::raw('(p.total_premium+p.endorsement_amount) as premiumAmount'), 'p.renewal_status', 'p.policy_number', 'us.name as handler', 'u.name as agent', 'r.id as mainId', 'c.name as customerName', 't.*', 'rt.*', DB::raw("(case r.status when '0' then 'New' when '1' then 'New' when '2' then 'Technical review' when '3' then 'Document approved' when '4' then 'Quote upload'  when '5' then 'Revise quotation'  when '6' then 'Request policy'  when '7' then 'Policy upload'  when '8' then 'Reject'  when '9' then 'Completed' when '10' then 'Lost' when 11 then 'Pending with sales'   else 'Pending with client'  end) AS statusString"), 'r.updated_date as lastUpdated', 'c.channel', 'p.end_date as expiryDate', 'p.start_date as inceptiondate');
        if ($filterflag) {
            $conditionFilter = json_decode(Session::get('pendingtechfilter_' . Auth::user()->id), true);
            $requestObj = $this->generateOperationRequestQuery($conditionFilter, $quoteRequest);
            $resultArray = $requestObj->orderBy('r.updated_date')->get();
        } else {
            $whereArray = [];
            $sessionWhereCondition = [];

            if ($request->get('startDate') != '' && $request->get('endDate') != '') {
                $quoteRequest->whereBetween('r.updated_date', [$request->get('startDate'), $request->get('endDate')]);
                $sessionWhereCondition['betweenCondition']['r.updated_date'][] = $request->get('startDate');
                $sessionWhereCondition['betweenCondition']['r.updated_date'][] = $request->get('endDate');
            } elseif ($request->get('startDate') != '') {
                $whereArray[] = ['r.updated_date', '<=', Carbon::parse($request->get('startDate'))->format('Y-m-d')];
            } elseif ($request->get('endDate') != '') {
                $whereArray[] = ['r.updated_date', '<=', $request->get('endDate')];
            } else {
                
            }

            if ($request->get('customerId') > 0) {
                $whereArray[] = ['c.id', '=', $request->get('customerId')];
            }
            $sessionWhereCondition['inCondition']['r.status'] = json_encode([2, 3, 5]);
            if (count($whereArray) > 0) {
                $quoteRequest->where($whereArray);
                $sessionWhereCondition['whereCondition'] = json_encode($whereArray);
            }
            Session::put('pendingtechfilter_' . Auth::user()->id, json_encode($sessionWhereCondition));
            $quoteRequest->whereIn('r.status', [2, 3, 5]);
            $resultArray = $quoteRequest->orderBy('r.updated_date')->get();
        }


        return $resultArray;
    }

    /**
     * 
     * @param type $request
     * @param type $filterflag
     */
    public function getUnderIssuanceRequest($request, $filterflag = false) {
        $quoteRequest = DB::table('crm_main_table as r')
                ->leftJoin('crm_task_table as t', 'r.id', '=', 't.crm_main_id')
                ->leftJoin('crm_request_table as rt', 'r.id', '=', 'rt.crm_id')
                ->leftJoin('customers as c', 'c.id', '=', 'r.customer_id')
                ->leftJoin('line_of_business as lb', 'lb.id', '=', 'r.crm_line_of_business')
                ->leftJoin('users as u', 'u.id', '=', 'r.user_id')
                ->leftJoin('policies as p', function($join) {
                    $join->on('p.id', '=', 'r.policy_id');
                    $join->where('p.policy_status', '=', 2);
                })
                ->leftJoin('users as us', 'us.id', '=', 'c.technical_handler')
                ->select('r.*', 'lb.title as lineofbusinesstitle', DB::raw('(p.total_premium+p.endorsement_amount) as premiumAmount'), 'p.renewal_status', 'p.policy_number', 'us.name as handler', 'u.name as agent', 'r.id as mainId', 'c.name as customerName', 't.*', 'rt.*', DB::raw("(case r.status when '0' then 'New' when '1' then 'New' when '2' then 'Technical review' when '3' then 'Document approved' when '4' then 'Quote upload'  when '5' then 'Revise quotation'  when '6' then 'Request policy'  when '7' then 'Policy upload'  when '8' then 'Reject'  when '9' then 'Completed' when '10' then 'Lost' when 11 then 'Pending with sales'   else 'Pending with client'  end) AS statusString"), 'r.updated_date as lastUpdated', 'c.channel', 'p.end_date as expiryDate', 'p.start_date as inceptiondate');
        if ($filterflag) {
            $conditionFilter = json_decode(Session::get('underissauncefilter_' . Auth::user()->id), true);
            $requestObj = $this->generateOperationRequestQuery($conditionFilter, $quoteRequest);
            $resultArray = $requestObj->orderBy('r.updated_date')->get();
        } else {
            $whereArray = [];
            $sessionWhereCondition = [];

            if ($request->get('startDate') != '' && $request->get('endDate') != '') {
                $quoteRequest->whereBetween('r.updated_date', [$request->get('startDate'), $request->get('endDate')]);
                $sessionWhereCondition['betweenCondition']['r.updated_date'][] = $request->get('startDate');
                $sessionWhereCondition['betweenCondition']['r.updated_date'][] = $request->get('endDate');
            } elseif ($request->get('startDate') != '') {
                $whereArray[] = ['r.updated_date', '<=', Carbon::parse($request->get('startDate'))->format('Y-m-d')];
            } elseif ($request->get('endDate') != '') {
                $whereArray[] = ['r.updated_date', '<=', $request->get('endDate')];
            } else {
                
            }

            if ($request->get('customerId') > 0) {
                $whereArray[] = ['c.id', '=', $request->get('customerId')];
            }

            $whereArray[] = ['r.status', '=', 6];
            if (count($whereArray) > 0) {
                $quoteRequest->where($whereArray);
                $sessionWhereCondition['whereCondition'] = json_encode($whereArray);
            }
            Session::put('underissauncefilter_' . Auth::user()->id, json_encode($sessionWhereCondition));

            $resultArray = $quoteRequest->orderBy('r.updated_date')->get();
        }


        return $resultArray;
    }

    /**
     * 
     * @param type $request
     * @param type $filterflag
     */
    public function getRenewalDetails($request, $differnece = 0, $filterflag = false) {
        $policyDetails = DB::table('policies as p')
                ->join('customers as c', 'c.id', '=', 'p.customer_id')
                ->leftJoin('insurer_details as ins', 'ins.id', '=', 'p.insurer_id')
                ->leftJoin('insurance_product as pr', 'pr.id', '=', 'p.product_id')
                ->leftJoin('policy_product_details as pp', 'pp.policy_id', '=', 'p.id')
                ->leftJoin('crm_main_table as r', 'r.id', '=', 'p.crm_id')
                ->leftJoin('users as u', 'u.id', '=', 'r.user_id')
                ->leftJoin('users as us', 'us.id', '=', 'c.technical_handler')
                ->leftJoin('line_of_business as lb', 'lb.id', '=', 'r.crm_line_of_business')
                ->select('p.*', DB::raw('(p.total_premium+p.endorsement_amount) as premiumAmount'), 'pp.*', 'u.name as agent', 'us.name as handler', 'ins.insurer_name', 'p.id as mainId', 'c.name as customerName', 'pr.product_name', DB::raw("(case p.renewal_status when 0 then 'No' when 1 then 'Yes'   end) AS policystatusString"), DB::raw("(case p.policy_type when 2 then 'Medical' when 3 then 'Motor' else 'General'  end) AS lobdata"), 'c.channel', DB::raw('DATEDIFF(p.end_date,now()) as datediff'), 'lb.title as lineofbusinesstitle', 'p.end_date as expirydate', 'p.start_date as inceptiondate');

        if ($filterflag) {
            $conditionFilter = json_decode(Session::get('policyrenewalfilter_' . $differnece . '_' . Auth::user()->id), true);

            $requestObj = $this->generateOperationRequestQuery($conditionFilter, $policyDetails);
            $resultArray = $requestObj->get();
        } else {
            $whereArray = [];
            $extrawhereArray = [];
            $dummywhereArray = [];
            $sessionWhereCondition = [];
            if ($request->get('startDate') == '' && $request->get('endDate') == '') {
                $startDate = date('Y-m-d', strtotime('first day of january this year'));
                $endDate = date('Y-m-d', strtotime($startDate . ' +365 days'));
                $policyDetails->whereBetween('p.end_date', [$startDate, $endDate]);
                $sessionWhereCondition['betweenCondition']['p.end_date'][] = $startDate;
                $sessionWhereCondition['betweenCondition']['p.end_date'][] = $endDate;
            } elseif ($request->get('startDate') != '' && $request->get('endDate') != '') {
                $policyDetails->whereBetween('p.end_date', [$request->get('startDate'), $request->get('endDate')]);
                $sessionWhereCondition['betweenCondition']['p.end_date'][] = $request->get('startDate');
                $sessionWhereCondition['betweenCondition']['p.end_date'][] = $request->get('endDate');
            } elseif ($request->get('startDate') != '') {
                $whereArray[] = ['p.end_date', '<=', Carbon::parse($request->get('startDate'))->format('Y-m-d')];
            } elseif ($request->get('endDate') != '') {
                $whereArray[] = ['p.end_date', '<=', $request->get('endDate')];
            } else {
                
            }

            $whereArray[] = ['p.policy_status', '=', 2];

            if ($request->get('customerId') > 0) {
                $whereArray[] = ['c.id', '=', $request->get('customerId')];
            }
            if ($differnece > 0 && $differnece == 60) {

                $extrawhereArray[] = [DB::raw('DATEDIFF(p.end_date,now())'), '<=', $differnece];
                $dummywhereArray = 60;
            }
            if ($differnece > 0 && $differnece == 90) {
                $extrawhereArray[] = [DB::raw('DATEDIFF(p.end_date,now())'), '>', 60];
                $extrawhereArray[] = [DB::raw('DATEDIFF(p.end_date,now())'), '<=', $differnece];
                $dummywhereArray = 90;
            }
            if ($differnece > 0 && $differnece == 120) {
                $extrawhereArray[] = [DB::raw('DATEDIFF(p.end_date,now())'), '>', 90];
                $dummywhereArray = 120;
            }

            if (count($whereArray) > 0) {

                $policyDetails->where($whereArray);
                $sessionWhereCondition['whereCondition'] = json_encode($whereArray);
            }
            if (count($extrawhereArray) > 0) {
                $policyDetails->where($extrawhereArray);
                $sessionWhereCondition['extrawhereArray'] = $dummywhereArray;
            }


            Session::put('policyrenewalfilter_' . $differnece . '_' . Auth::user()->id, json_encode($sessionWhereCondition));


            $policyDetails->groupby('mainId');
            $resultArray = $policyDetails->orderBy('p.end_date')->get();
        }


        return $resultArray;
    }

    /**
     * 
     * @param type $request
     * @param type $filterflag
     */
    public function getProductionDetails($request, $filterflag = false) {
        $policyDetails = DB::table('policies as p')
                ->join('customers as c', 'c.id', '=', 'p.customer_id')
                ->leftJoin('insurer_details as ins', 'ins.id', '=', 'p.insurer_id')
                ->leftJoin('insurance_product as pr', 'pr.id', '=', 'p.product_id')
                ->leftJoin('policy_product_details as pp', 'pp.policy_id', '=', 'p.id')
                ->leftJoin('crm_main_table as r', 'r.id', '=', 'p.crm_id')
                ->leftJoin('users as u', 'u.id', '=', 'r.user_id')
                ->leftJoin('users as us', 'us.id', '=', 'c.technical_handler')
                ->leftJoin('line_of_business as lb', 'lb.id', '=', 'r.crm_line_of_business')
                ->select('p.*', DB::raw('(p.total_premium+p.endorsement_amount) as premiumAmount'), 'pp.*', 'u.name as agent', 'us.name as handler', 'ins.insurer_name', 'p.id as mainId', 'c.name as customerName', 'pr.product_name', DB::raw("(case p.renewal_status when 0 then 'No' when 1 then 'Yes'   end) AS policystatusString"), DB::raw("(case p.policy_type when 2 then 'Medical' when 3 then 'Motor' else 'General'  end) AS lobdata"), 'c.channel', DB::raw('DATEDIFF(p.end_date,now()) as datediff'), 'lb.title as lineofbusinesstitle', 'p.end_date as expirydate', 'p.start_date as inceptiondate', 'c.customer_group');

        if ($filterflag) {
            $conditionFilter = json_decode(Session::get('productionfilter_' . Auth::user()->id), true);
            $requestObj = $this->generateOperationRequestQuery($conditionFilter, $policyDetails);
            $resultArray = $requestObj->get();
        } else {
            $whereArray = [];
            $sessionWhereCondition = [];
            if ($request->get('startDate') == '' && $request->get('endDate') == '') {
                $startDate = date('Y-m-d', strtotime('first day of january this year'));
                $endDate = date('Y-m-d', strtotime($startDate . ' +365 days'));
                $policyDetails->whereBetween('p.end_date', [$startDate, $endDate]);
                $sessionWhereCondition['betweenCondition']['p.end_date'][] = $startDate;
                $sessionWhereCondition['betweenCondition']['p.end_date'][] = $endDate;
            } elseif ($request->get('startDate') != '' && $request->get('endDate') != '') {
                $policyDetails->whereBetween('p.end_date', [$request->get('startDate'), $request->get('endDate')]);
                $sessionWhereCondition['betweenCondition']['p.end_date'][] = $request->get('startDate');
                $sessionWhereCondition['betweenCondition']['p.end_date'][] = $request->get('endDate');
            } elseif ($request->get('startDate') != '') {
                $whereArray[] = ['p.end_date', '<=', Carbon::parse($request->get('startDate'))->format('Y-m-d')];
            } elseif ($request->get('endDate') != '') {
                $whereArray[] = ['p.end_date', '<=', $request->get('endDate')];
            } else {
                
            }

            $whereArray[] = ['p.policy_status', '=', 2];

            if ($request->get('customerId') > 0) {
                $whereArray[] = ['c.id', '=', $request->get('customerId')];
            }
            if ($request->get('insurerId') > 0) {
                $whereArray[] = ['p.insurer_id', '=', $request->get('insurerId')];
            }

            if (count($whereArray) > 0) {
                $policyDetails->where($whereArray);
                $sessionWhereCondition['whereCondition'] = json_encode($whereArray);
            }
            Session::put('productionfilter_' . Auth::user()->id, json_encode($sessionWhereCondition));

            $policyDetails->groupby('mainId');


            $resultArray = $policyDetails->orderBy('p.end_date')->get();
        }
        return $resultArray;
    }

    /**
     * 
     * @param type $request
     * @param type $filterflag
     * @return type
     */
    public function getClientSegmantDetails($request, $filterflag = false) {
        $policyDetails = DB::table('policies as p')
                ->join('customers as c', 'c.id', '=', 'p.customer_id')
                ->leftJoin('insurer_details as ins', 'ins.id', '=', 'p.insurer_id')
                ->select('p.*', DB::raw('(SUM(p.total_premium)+ SUM(p.endorsement_amount)) as premiumAmount'), 'ins.insurer_name', 'p.id as mainId', 'c.name as customerName', DB::raw("(case p.renewal_status when 0 then 'No' when 1 then 'Yes'   end) AS policystatusString"));

        if ($filterflag) {
            $conditionFilter = json_decode(Session::get('clientsegmantfilter_' . Auth::user()->id), true);
            $requestObj = $this->generateOperationRequestQuery($conditionFilter, $policyDetails);
            $resultArray = $requestObj->groupby('p.customer_id')->orderBy('p.end_date')->get();
        } else {
            $whereArray = [];
            $sessionWhereCondition = [];
            if ($request->get('startDate') == '' && $request->get('endDate') == '') {
                $startDate = date('Y-m-d', strtotime('first day of january this year'));
                $endDate = date('Y-m-d', strtotime($startDate . ' +365 days'));
                $policyDetails->whereBetween('p.end_date', [$startDate, $endDate]);
                $sessionWhereCondition['betweenCondition']['p.end_date'][] = $startDate;
                $sessionWhereCondition['betweenCondition']['p.end_date'][] = $endDate;
            } elseif ($request->get('startDate') != '' && $request->get('endDate') != '') {
                $policyDetails->whereBetween('p.end_date', [$request->get('startDate'), $request->get('endDate')]);
                $sessionWhereCondition['betweenCondition']['p.end_date'][] = $request->get('startDate');
                $sessionWhereCondition['betweenCondition']['p.end_date'][] = $request->get('endDate');
            } elseif ($request->get('startDate') != '') {
                $whereArray[] = ['p.end_date', '<=', Carbon::parse($request->get('startDate'))->format('Y-m-d')];
            } elseif ($request->get('endDate') != '') {
                $whereArray[] = ['p.end_date', '<=', $request->get('endDate')];
            } else {
                
            }

            $whereArray[] = ['p.policy_status', '=', 2];

            if ($request->get('customerId') > 0) {
                $whereArray[] = ['c.id', '=', $request->get('customerId')];
            }

            if (count($whereArray) > 0) {
                $policyDetails->where($whereArray);
                $sessionWhereCondition['whereCondition'] = json_encode($whereArray);
            }
            Session::put('clientsegmantfilter_' . Auth::user()->id, json_encode($sessionWhereCondition));

            $policyDetails->groupby('p.customer_id');
            $resultArray = $policyDetails->orderBy('p.end_date')->get();
        }


        return $resultArray;
    }

    /**
     * 
     * @param type $request
     * @param type $filterflag
     * @return type
     */
    public function getEndorsementSegmantDetails($request, $filterflag = false) {
        $policyDetails = DB::table('policies as p')
                ->join('policy_endorsement as pn', function($join) {
                    $join->on('pn.policy_id', '=', 'p.id');
                    $join->where('pn.endorsement_status', '=', 1);
                    $join->whereIn('pn.endorsement_type', [1, 4, 5, 14]);
                })
                ->join('customers as c', 'c.id', '=', 'p.customer_id')
                ->leftJoin('insurer_details as ins', 'ins.id', '=', 'p.insurer_id')
                ->select('p.*', 'ins.insurer_name', 'p.id as mainId', 'c.name as customerName', DB::raw("(case pn.endorsement_type when 1 then 'Addition' when 4 then 'Deletion'  when 5 then 'Downgrade' when 14 then 'Upgrade'   end) AS typeString"), DB::raw("(case p.renewal_status when 0 then 'No' when 1 then 'Yes'   end) AS policystatusString"), 'pn.*', 'pn.start_date as endorsementStartDate', 'pn.vat_amount as vatAmount', 'pn.issue_date as issueDate');

        if ($filterflag) {
            $conditionFilter = json_decode(Session::get('endorsementsegmantfilter_' . Auth::user()->id), true);
            $requestObj = $this->generateOperationRequestQuery($conditionFilter, $policyDetails);
            $resultArray = $requestObj->orderBy('pn.start_date')->get();
        } else {
            $whereArray = [];
            $sessionWhereCondition = [];
            if ($request->get('startDate') == '' && $request->get('endDate') == '') {
                $startDate = date('Y-m-d', strtotime('first day of january this year'));
                $endDate = date('Y-m-d', strtotime($startDate . ' +365 days'));
                $policyDetails->whereBetween('pn.start_date', [$startDate, $endDate]);
                $sessionWhereCondition['betweenCondition']['pn.start_date'][] = $startDate;
                $sessionWhereCondition['betweenCondition']['pn.start_date'][] = $endDate;
            } elseif ($request->get('startDate') != '' && $request->get('endDate') != '') {
                $policyDetails->whereBetween('pn.start_date', [$request->get('startDate'), $request->get('endDate')]);
                $sessionWhereCondition['betweenCondition']['pn.start_date'][] = $request->get('startDate');
                $sessionWhereCondition['betweenCondition']['pn.start_date'][] = $request->get('endDate');
            } elseif ($request->get('startDate') != '') {
                $whereArray[] = ['pn.start_date', '<=', Carbon::parse($request->get('startDate'))->format('Y-m-d')];
            } elseif ($request->get('endDate') != '') {
                $whereArray[] = ['pn.start_date', '<=', $request->get('endDate')];
            } else {
                
            }

            $whereArray[] = ['p.policy_status', '=', 2];

            if ($request->get('customerId') > 0) {
                $whereArray[] = ['c.id', '=', $request->get('customerId')];
            }

            if (count($whereArray) > 0) {
                $policyDetails->where($whereArray);
                $sessionWhereCondition['whereCondition'] = json_encode($whereArray);
            }
            Session::put('endorsementsegmantfilter_' . Auth::user()->id, json_encode($sessionWhereCondition));

            $resultArray = $policyDetails->orderBy('pn.start_date')->get();
        }
        return $resultArray;
    }

    /**
     * 
     * @param type $request
     * @param type $filterflag
     * @return type
     */
    public function getQuoteDetails($request, $filterflag = false) {
        $quoteData = DB::table('quote as q')
                ->join('customers as c', 'c.id', '=', 'q.customer_id')
                ->join('broking_slip_info as inf', 'inf.quotes_id', '=', 'q.id')
                ->join('insurer_details as ind', 'ind.id', '=', 'q.company_id')
                ->leftJoin('crm_main_table as r', 'r.id', '=', 'q.crm_id')
                ->leftJoin('line_of_business as lb', 'lb.id', '=', 'r.crm_line_of_business')
                ->leftJoin('customer_crm_documents as cdoc', function($join) {
                    $join->on('cdoc.crm_id', '=', 'r.id');
                    $join->where('cdoc.document_type', '=', 8);
                    $join->groupBy('cdoc.crm_id');
                })
                ->leftJoin('customer_crm_documents as doc', 'lb.id', '=', 'r.crm_line_of_business')
                ->leftJoin('users as u', 'q.upload_by', '=', 'u.id')
                ->select('q.*', 'ind.insurer_name', 'u.name as agent', 'c.name as customerName', 'lb.title as lineofbusinesstitle', 'c.channel', 'r.broking_slip_send_date as submissionDate', DB::raw('DATEDIFF(r.technical_reporting_date,r.created_date) as salesdaydiff'), DB::raw('DATEDIFF(q.created_at,r.technical_reporting_date) as technicaldaydiff'), 'cdoc.id as comparisonDoc', DB::raw("(case r.status when 2 then 'Techincal' when 3 then 'Technical' when 4 then 'Technical' when 5 then 'Technical' when 6 then 'Technical' else 'Sales' end) as qstatus"), 'q.updated_at', 'r.technical_reporting_date');


        if ($filterflag) {

            $conditionFilter = json_decode(Session::get('quotefilter_' . Auth::user()->id), true);
            $requestObj = $this->generateOperationRequestQuery($conditionFilter, $quoteData);

            $resultArray = $requestObj->orderBy('q.created_at')->get();
        } else {
            $whereArray = [];
            $sessionWhereCondition = [];
            if ($request->get('startDate') == '' && $request->get('endDate') == '') {
                $startDate = date('Y-m-d', strtotime('first day of january this year'));
                $endDate = date('Y-m-d', strtotime($startDate . ' +365 days'));
                //$quoteData->whereBetween('q.created_at', [$startDate, $endDate]);
//                $sessionWhereCondition['betweenCondition']['q.created_at'][] = $startDate;
//                $sessionWhereCondition['betweenCondition']['q.created_at'][] = $endDate;
            } elseif ($request->get('startDate') != '' && $request->get('endDate') != '') {
                $quoteData->whereBetween('q.created_at', [$request->get('startDate'), $request->get('endDate')]);
                $sessionWhereCondition['betweenCondition']['q.created_at'][] = $request->get('startDate');
                $sessionWhereCondition['betweenCondition']['q.created_at'][] = $request->get('endDate');
            } elseif ($request->get('startDate') != '') {
                $whereArray[] = ['q.created_at', '<=', Carbon::parse($request->get('startDate'))->format('Y-m-d')];
            } elseif ($request->get('endDate') != '') {
                $whereArray[] = ['q.created_at', '<=', $request->get('endDate')];
            } else {
                
            }



            if ($request->get('customerId') > 0) {
                $whereArray[] = ['c.id', '=', $request->get('customerId')];
            }

            if (count($whereArray) > 0) {
                $quoteData->where($whereArray);
                $sessionWhereCondition['whereCondition'] = json_encode($whereArray);
            }
            Session::put('quotefilter_' . Auth::user()->id, json_encode($sessionWhereCondition));

            $resultArray = $quoteData->orderBy('q.created_at')->get();
        }
        return $resultArray;
    }
    
    /**
     * 
     * @param type $request
     * @param type $filterflag
     */
    public function getLostIssuanceRequest($request, $filterflag = false) {
        $quoteRequest = DB::table('crm_main_table as r')
                ->leftJoin('crm_task_table as t', 'r.id', '=', 't.crm_main_id')
                ->leftJoin('crm_request_table as rt', 'r.id', '=', 'rt.crm_id')
                ->leftJoin('customers as c', 'c.id', '=', 'r.customer_id')
                ->leftJoin('line_of_business as lb', 'lb.id', '=', 'r.crm_line_of_business')
                ->leftJoin('users as u', 'u.id', '=', 'r.user_id')
                ->leftJoin('users as us', 'us.id', '=', 'c.technical_handler')
                ->leftJoin('policies as p', function($join) {
                    $join->on('p.id', '=', 'r.policy_id');
                    $join->where('p.policy_status', '=', 2);
                })
                ->leftJoin('insurer_details as ins', 'ins.id', '=', 'p.insurer_id')
                ->select('r.*', 'ins.insurer_name', 'lb.title as lineofbusinesstitle', DB::raw('(p.total_premium+p.endorsement_amount) as premiumAmount'), 'p.renewal_status', 'p.policy_number', 'us.name as handler', 'u.name as agent', 'r.id as mainId', 'c.name as customerName', 't.*', 'rt.*', DB::raw("(case r.status when '0' then 'New' when '1' then 'New' when '2' then 'Technical review' when '3' then 'Document approved' when '4' then 'Quote upload'  when '5' then 'Revise quotation'  when '6' then 'Request policy'  when '7' then 'Policy upload'  when '8' then 'Reject'  when '9' then 'Completed' when '10' then 'Lost' when 11 then 'Pending with sales'   else 'Pending with client'  end) AS statusString"), 'r.updated_date as lastUpdated', 'c.channel', 'p.end_date as expiryDate', 'p.start_date as inceptiondate')
                ->where('r.status',10);

        if ($filterflag) {
            $conditionFilter = json_decode(Session::get('lostrequestFilterCondition_' . Auth::user()->id), true);
            $resultArray = $this->generateQuery($conditionFilter, $quoteRequest);
        } else {
            $whereArray = [];
            $orWhereArray =[];
            $sessionWhereCondition = [];
            if ($request->get('startDate') != '' && $request->get('endDate') != '') {
                $quoteRequest->whereBetween('r.created_date', [$request->get('startDate'), $request->get('endDate')]);
                $sessionWhereCondition['betweenCondition']['r.created_date'][] = $request->get('startDate');
                $sessionWhereCondition['betweenCondition']['r.created_date'][] = $request->get('endDate');
            } elseif ($request->get('startDate') != '') {
                $whereArray[] = ['r.created_date', '=', $request->get('startDate')];
            } elseif ($request->get('endDate') != '') {
                $whereArray[] = ['r.created_date', '=', $request->get('endDate')];
            } else {
                
            }

            if ($request->get('customerId') > 0) {
                $whereArray[] = ['r.customer_id', '=', $request->get('customerId')];
            }


            if (in_array('ROLE_SALES_MANAGER', Auth::user()->roles) || in_array('ROLE_TECHNICAL_MANAGER', Auth::user()->roles)) {
                
            } else if (in_array('ROLE_SALES', Auth::user()->roles) || in_array('ROLE_TECHNICAL', Auth::user()->roles)) {
                $quoteRequest->where('r.user_id', Auth::user()->id)->orWhere('r.assigned_to', Auth::user()->id);
                $whereArray[] = ['r.user_id', Auth::user()->id];
                $orWhereArray[] = ['r.assigned_to', Auth::user()->id];
                $sessionWhereCondition['orwhere'] = json_encode($orWhereArray);
            } else {
                
            }



            if (count($whereArray) > 0) {
                $quoteRequest->where($whereArray);
                $sessionWhereCondition['whereCondition'] = json_encode($whereArray);
            }
            Session::put('lostrequestFilterCondition_' . Auth::user()->id, json_encode($sessionWhereCondition));

            $resultArray = $quoteRequest->get();
        }


       

        return $resultArray;
    }
    
    

}
