<?php

namespace App\Http\Controllers\Dbroker;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use File;
use App;
use Storage;


class DatasaveController extends Controller {

    /**
     *
     */

protected $requetType;

    public function __construct()
    {
         $this->requetType = array(0=>1,1=>3,2=>10,3=>10,4=>6,5=>1,6=>3,7=>9,8=>4,9=>10,10=>5,11=>8,12=>6);
    }

    public function saveRequestForm(Request $request) {

       
         $requestId = substr("ER-" . uniqid(date("Ymdhi")), 0, -12);
         $requestType = $this->requetType[$request->get('RequestType')];
         $policy_details   = $this->getPolicyId($request->get('PolicyNumber'));       
         if($policy_details['id'] !==null) {
            $insertId = DB::table('crm_endorsement')->insertGetId(array('policy_id' => $policy_details['id'], 
            'description' => $request->get('Details'), 
            'type' => $requestType, 
            'status' => 1, 
            'dbroker_id'=>$request->get('insertId'),
            'created_at' => date('Y-m-d h:i:s'),
            'updated_at' => date('Y-m-d h:i:s'), 'created_by' => 8, 'request_id' => $requestId));

         //SAVE UPLOAD FILE DETAILS
        // $files = explode("|", $request->file('Files'));       
        // $insertArray = [];
        // $type = 1;       
        // $customerId = $policy_details['company_id'];
        // $policyId = $policy_details['id'];
        // $filename = [];
        // $datetime = date('Y-m-d h:i');
        // foreach ($files as $uploadedfile) {
        //     $destinationPath = 'uploads/' . $customerId . "/document/";
        //     $path_parts = pathinfo($uploadedfile->getClientOriginalName());
        //     $newfilename = $path_parts['filename'] . "_" . date('Ymdhis') . '.' . $path_parts['extension'];
        //     $filename[] = $newfilename;
        //     $uploadedfile->move($destinationPath, $newfilename);
        //     $insertArray[] = array("customer_id" => $customerId,
        //         "file_name" => $newfilename,
        //         "type" => $type,
        //         "comment" => $comment,
        //         "upload_by" => Auth::user()->id,
        //         "upload_at" => $datetime,
        //         "endorsement_crm_id" => $insertId,
        //         "policy_id" => $policyId
        //     );
            
        // }

        // if (count($insertArray) > 0) {
        //     DB::table('dp_customer_document')->insert($insertArray); // Query Builder approach
        //     //insert log entry
        //     $logarray = array("crm_id" => $insertId,
        //         "kind" => "Following documents are uploaded: " . implode(',', $filename),
        //         "old_value" => '',
        //         "updated_by" => Auth::user()->id,
        //         "updated_at" => $datetime);

        //     DB::table('endorsement_crm_log')->insert($logarray);
           
        // }

         }
         

         return true;

    }
    /**
     * 
     * @param type $row
     * @return type
     */
    private function getPolicyId($policyNumber) {
        $value = array('id'=>null,'company_id'=>null);
        $policydata = DB::table('policies')->select('id', 'policy_number', 'start_date', 'end_date','customer_id')                     
                        ->where('policy_number', 'like', "%" . trim($policyNumber) . "%")->where('start_date', '<=',  DB::raw('curdate()'))->where('end_date', '>=', DB::raw('curdate()'))->orderBy('id','ASC')->first();
        if ($policydata && count(get_object_vars($policydata)) > 0   ) {
            $value['id'] = $policydata->id;
            $value['company_id'] = $policydata->customer_id;
        }  
        return $value;
    }


    public function saveEndorsementCommentDetails(Request $request) {
        

        $requestId =  $request->get('requestId');
        $comments = $request->get('UpdatesMessage');
        $actualRequestDetails = DB::table('crm_endorsement')->select('id')                     
                        ->where('dbroker_id', '=',$requestId)->orderBy('id','ASC')->first();

                        if ($actualRequestDetails && count(get_object_vars($actualRequestDetails)) > 0   ) {
                            $commentArray['request_id'] = $actualRequestDetails->id;
                            $commentArray['comments'] = $comments;
                            $commentArray['created_at'] = date('Y-m-d H:i');
                            $commentArray['created_by'] = 8;
                            $commentArray['created_user'] = $request->get('addedUser');
                            
                            DB::table('crm_endorsement_comments')->insert($commentArray);
                         }  

                         return true; 
        
    }

    public function endorsementstatusChange(Request $request){
        $requestId =  $request->get('requestId');
        $statusArray = [1 => 'Open', 'Under process', 'Close', 'Pending from insurer', 'Pending from client'];
        $status = $this->findActualStatus($request->get('NewStatus'));
        $actualRequestDetails = DB::table('crm_endorsement')->select('id')                     
                        ->where('dbroker_id', '=',$requestId)->orderBy('id','ASC')->first();
                        
        if ($actualRequestDetails && count(get_object_vars($actualRequestDetails)) > 0   ) {
        $crmrequestObj = App\EndorsementCrm::find($actualRequestDetails->id);
        $requestType = $crmrequestObj->type;
        $oldStatus = $crmrequestObj->status;
        $crmrequestObj->status = $status;
        $crmrequestObj->updated_at = date('Y-m-d h:i:s');
        $crmrequestObj->save();


        if ($oldStatus !== $status) {
     
            $logArray = array('crm_id' => $actualRequestDetails->id, 'kind' => 'Crm request status was changed to ' . $statusArray[$status], 'old_value' => $statusArray[$oldStatus], 'updated_by' => 8, 'updated_at' => date('Y-m-d h:i:s'));
            DB::table('endorsement_crm_log')->insert($logArray);
        }
                         }  

                         return true; 
    }

    private function findActualStatus($status) {
      $statusArray = [0 =>1,1=>2,2=>5,3=>3,5=>4,6=>4,7=>5]; 
      $statusKey = array_keys($statusArray);

      $newstatus = 1;
      if(in_array($status, $statusKey))
        {
         $newstatus = $statusArray[$status]; // $key = 2;
        } 

        return $newstatus;
    }

    public function saveClientdocument(Request $request){
         $requestId =  $request->get('requestId');
         //find request id
         $actualRequestDetails = DB::table('crm_endorsement as ed')->join('policies as po', 'po.id', '=', 'ed.policy_id')->select('ed.id','ed.policy_id','po.customer_id')                     
                        ->where('dbroker_id', '=',$requestId)->orderBy('id','ASC')->first();
         if ($actualRequestDetails && count(get_object_vars($actualRequestDetails)) > 0   ) {

         $files = $request->file;
         $destinationPath = 'uploads/' . $actualRequestDetails->customer_id . "/document/";
    
         $realname = str_slug(pathinfo($files->getClientOriginalName(), PATHINFO_FILENAME));
         $extension = $files->getClientOriginalExtension();
         $newfilename = str_slug($realname) .".". $extension;
         $files->move($destinationPath, $newfilename);
         $datetime = date('Y-m-d h:i');
         $insertArray = array("customer_id" => $actualRequestDetails->customer_id,
                "file_name" => $newfilename,
                "type" => 6,
                "comment" => null,
                "upload_by" => 8,
                "upload_at" => $datetime,
                "endorsement_crm_id" => $actualRequestDetails->id,
                "policy_id" => $actualRequestDetails->policy_id,
                "dbroker_user"=>$request->get('addedUser')
            );
         DB::table('dp_customer_document')->insert($insertArray);
         $logarray = array("crm_id" => $actualRequestDetails->id,
                "kind" => "Following documents are uploaded: " .$newfilename ,
                "old_value" => '',
                "updated_by" => 8,
                "updated_at" => $datetime
                );

            DB::table('endorsement_crm_log')->insert($logarray);
     }
        

    }

    public function testCurl() {
      echo "123444";
      exit;
    }


    
   
}
