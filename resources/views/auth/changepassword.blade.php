@extends('layouts.elite',['notificationCount'=>0 ] )

@section('headtitle')
Change password
@endsection




@section('content')
                   
         
            <div class="row" >
        
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                 <form class="mt-4" method="POST" action="{{ route('changePassword') }}">
                        {{ csrf_field() }}
                           
                                    <div class="form-group{{ $errors->has('current-password') ? ' has-error' : '' }}">
                                        <label for="current-password" style="background-color:transparent!important;">Current Password</label>                                        
                                        <input type="password" class="form-control" id="current-password" name="current-password" aria-describedby="emailHelp" placeholder="Current password" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="new-password" style="background-color:transparent!important">New Password</label>
                                        <input type="password" class="form-control" id="new-password" name="new-password" placeholder="New password" required>
                                        @if ($errors->has('new-password'))
                                    <span class="help-block alert-danger">
                                        <strong>{{ $errors->first('new-password') }}</strong>
                                    </span>
                                @endif
                                    </div>
                        
                                    <div class="form-group">
                                        <label for="new-password-confirm" style="background-color:transparent!important">Confirm New Password</label>
                                        <input type="password" class="form-control" id="new-password-confirm" name="new-password_confirmation" placeholder="Confirm password" required>                         
                                    </div>
                                    <button type="submit" class="btn btn-primary">Change Password</button>
                                </form>
                            </div>
                        </div>
                    </div>
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
            </div>                
                       
                  
           
    

 



          

@endsection


  @section('customscript')
      <!-- slimscrollbar scrollbar JavaScript -->
    <script src="{{ asset('elitedesign/dist/js/perfect-scrollbar.jquery.min.js') }}"></script>

    <!--stickey kit -->
    <script src="{{ asset('elitedesign/assets/node_modules/sticky-kit-master/dist/sticky-kit.min.js') }}"></script>
     <script src="{{ asset('elitedesign/assets/node_modules/sparkline/jquery.sparkline.min.js') }}"></script>
    <script src="{{ asset('elitedesign/assets/node_modules/raphael/raphael-min.js') }}"></script>
    <script src="{{ asset('elitedesign/assets/node_modules/morrisjs/morris.min.js') }}"></script>


        <script src="{{ asset('elitedesign/assets/node_modules/skycons/skycons.js') }}"></script>
        <script src="{{ asset('js/dibcustom/dib_dashboard.js') }}"></script>
   
  @endsection


 
