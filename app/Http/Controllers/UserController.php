<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Auth;
use \View as View;
use App\customer;
use Illuminate\Support\Facades\DB;

class UserController extends Controller {
    /*
      |--------------------------------------------------------------------------
      | Register Controller
      |--------------------------------------------------------------------------
      |
      | This controller handles the registration of new users as well as their
      | validation and creation. By default this controller uses a trait to
      | provide this functionality without requiring any additional code.
      |
     */

    protected $layout = 'layouts.new';

    public function __construct() {
        
    }

    public function index() {
        // check the user is loggined or not 
        if( isset(Auth::user()->name)) {
           //go to the dashboard of the portal
            return redirect('/dashboard');  
        } else {
          return view('login');  
        }
        
    }

    public function show() {

        //return view('newhome')->with('name', 'Victoria');
        return View::make('newhome', ['name' => 'Victoria']);
    }

    public function checklogin(Request $request) {

        
       //server side validation
        $validator = $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|alphaNum|min:6'
        ]);

        // set the user tabel setting
        $user_data = array(
            'email' => $request->get('email'),
            'password' => $request->get('password'));

        if (Auth::attempt($user_data)) {
            
            //set user roles
            //return redirect()->route('listcustomerdata'); 

          //User's login log entry
            DB::table('user_log')->insert(array('login_user_id'=>Auth::user()->id,'login_date' =>date('Y-m-d H:i')));
            
             if (in_array('ROLE_MANAGEMENT_ADMIN', Auth::user()->roles)) {
               return redirect()->route('managementpolicy');   
             } else {
                return redirect()->route('dashboard');  
             }
                     
            
           
        } else {
            return back()->with('error', 'wrong login details');
        }
    }

    public function successlogin() {

/* ############# INSERT DATABASE ENTRY ################################ */  
//        $customer = new customer();
//        $customer ->firstname ='jinesh';
//        $customer ->lastname ='mani';
//        $customer->email = 'jineshmmani1985@gmail.com';
//        $customer->phone = '05528455';
//        $customer->save();
//       echo  $customer->getKey();
  
 /* ############# UPDATE DATABASE ENTRY ################################ */       
//        $customer = customer::find(1);
//        $customer->firstname = 'jineshmm';
//        $customer->save();
  
  /* ############# DELETE DATABASE ENTRY ################################ */       
   //     $deletedRows = customer::where('id', 5)->delete();
        
 /* #################### GET FOREIGN CONSTRAINTS VALUES ######################*/
//      $policies = customer::find(1)->policies->where('policy_name', 'policy2');
//   
//    
//        foreach ($policies as $comment) {
//               echo  "$$$$$$$$".$comment->policy_name;
//               echo  "########".$comment->id;
//               
//            }
//        
//  exit;   
        
     return redirect()->route('dashboard');  
        //return view('success');
    }

    public function register() {
        
    }
    public function logout() {
       Auth::logout();
      return redirect('/');  
    }


}
