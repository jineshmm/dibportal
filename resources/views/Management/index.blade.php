@extends('layouts.elite',['notificationCount'=>0 ] )


@section('headtitle')
Customers
@endsection




@section('content')


  <div class="row mt-1 mb-4 ml-1">

<div id="filter-insly_customer" class="panel-filter open extend-filter col-md-12">
 				 {{ Form::open(array('route' => array('managementcustomerfilter'), 'name' => 'form_management_customer_filter','id'=>'form_management_customer_filter') ) }}
                                    @csrf
                                    <table class="table-filter filtersperrow4">
                                        <tbody>
                                            <tr>
                                                <td id="filter_customer_name" class=" filterlevel1"><div><label for="filter_customer_name">Name:</label><div><input type="text" autocomplete="off" id="filter_customer_name" name="filter_customer_name" value="{{isset($formData['filtername']) ? $formData['filtername']:'' }}" class=""></div></div></td>
                                                <td id="filter_customer_type" class="extend-filter filterlevel2"><div><label for="filter_customer_type">Customer type:</label><div><select id="filter_customer_type" name="filter_customer_type"><option value="">--- all ---</option><option value="1">company</option><option value="0">individual</option></select></div></div></td>
                                                <td id="filter_customergroup_oid" class="extend-filter filterlevel2"><div><label for="filter_customergroup_oid">Customer group:</label><div>
                                                            {{ Form::select('filter_customergroup_oid',[''=>'---- not set ----'] +   $usergroup, isset($formData['filtergroup']) ? $formData['filtergroup']:'' ,array('id' =>'filter_customergroup_oid',"error-message"=>'Account manager field is mandatory'))}} 
                                                            </div></div></td>
                                            </tr>

                                            <tr>
                                                <td colspan="4" class="panel-buttons"><button type="submit" id="submit_save" name="submit_save" class="submit_policy btn waves-effect waves-light btn-rounded btn-primary">Apply filters</button> <button type="button" id="mg_clear_filter" name="clear_filter" class="submit_policy btn waves-effect waves-light btn-rounded btn-primary">Clear filters</button>  </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                {{ Form::close() }}
 			</div>


  </div>





<div class="row">          
          @foreach($customerDatas as $key =>$customer)
          
          @if($key % 3 ==0)
          </div>
           <div class="row"> 
          @endif
          

<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <div class="ribbon-wrapper-reverse card">
                                    @php
                                     $badgeColor = "ribbon-success";
                                     $customerStatus ='Active';
                                    @endphp
                                    
                                    @if($customer->policyCount > 0)
                                     @php
                                     $badgeColor = "ribbon-success";
                                     $customerStatus ='Active';
                                    @endphp
                                    
                                    
                                    @elseif($customer->policyCount == 0)
                                    
                                     @php
                                     $badgeColor="ribbon-danger";
                                     $customerStatus ='Inactive';
                                    @endphp
                                         
                                    @endif
                                    
                                    <div class="ribbon  ribbon-right {!!$badgeColor !!}">{{$customerStatus}}</div>
                                    <h5 ><a href="{!! route('customeroverview',$customer->customId) !!}" style='font-size:1.5em'><b>{{ ucfirst(trans($customer->name)) }}</b></a></h5>  
                                  <table style="width:100%">
                        <tbody>
                         <tr>
                            <td> Customer type</td><td>{{ ($customer->type==1) ? 'Company':'Individual'}}</td>                            
                          </tr>
                     
                          <tr>
                            <td> Customer code</td><td>{{($customer->id_code !='') ? $customer->id_code:'-' }}  </td>
                          </tr>
                    
                          <tr>
                            <td> Customer group</td><td>{{($customer->customer_group !='') ? $customer->customer_group:'-' }} </td>
                          </tr>
                    

                          <tr>
                           <td> Phone</td><td> {{$customer->customerPhone}} </td>                            
                          </tr> 
                          <tr>
                            <td> Email</td><td>{{$customer->customerEmail}}</td>                            
                          </tr> 
                          <tr>
                            <td> Active policy count</td><td>{{$customer->policyCount}}</td>                            
                          </tr> 
                          <tr class="text-danger">
                            <td>
                                @if($customer->policyCount > 0) Policy end date @endif  </td><td>@if($customer->endpolicyId !='' && ($customer->policyCount > 0))
                                <a href='{!! route("policyoverview",$customer->endpolicyId) !!}'>{{ date('d.m.Y',strtotime($customer->policyEnddate)) }}</a>
                                @endif
                            </td>
                          </tr> 
                         
                         
                        </tbody>
                                </table> 
                                </div>
                            </div>
          
          
          @endforeach
   </div>
  
   <div class="row">
       <div class="col-12"  style="float:right"></div>
       {!! $customerDatas->links() !!}
   </div> 


                     

@endsection

 @section('customcss')
  <link rel="stylesheet" type="text/css" href=" {{ asset('elitedesign/dist/css/pages/ribbon-page.css') }} ">
   
@endsection
          

  @section('customscript')
      <!-- slimscrollbar scrollbar JavaScript -->
    <script src="{{ asset('elitedesign/dist/js/perfect-scrollbar.jquery.min.js') }}"></script>

    <!--stickey kit -->
    <script src="{{ asset('elitedesign/assets/node_modules/sticky-kit-master/dist/sticky-kit.min.js') }}"></script>
     <script src="{{ asset('elitedesign/assets/node_modules/sparkline/jquery.sparkline.min.js') }}"></script>
    <script src="{{ asset('elitedesign/assets/node_modules/raphael/raphael-min.js') }}"></script>
    <script src="{{ asset('elitedesign/assets/node_modules/morrisjs/morris.min.js') }}"></script>

    <!-- This is data table -->
    <script src="{{ asset('elitedesign/assets/node_modules/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('elitedesign/assets/node_modules/datatables.net-bs4/js/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('elitedesign/assets/node_modules/skycons/skycons.js') }}"></script>
        <script src="{{ asset('js/dibcustom/dib_dashboard.js') }}"></script>
   
  @endsection






@section('pagescript')
<script>
    
 
   $(function(){

//Claims
$(document).on('click','#mg_clear_filter',function(){
   window.location.href = "{!! route('managementdashboard')  !!}";
    
})

   
    
    

   });


   


</script>




@endsection
