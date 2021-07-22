
 
 <div class="row">
                    <!-- Column -->
                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">New Leads</h5>
                                <div class="d-flex no-block align-items-center m-t-20 m-b-20">
                                    <div id="sparklinedash"><canvas width="67" height="30" style="display: inline-block; width: 67px; height: 30px; vertical-align: top;"></canvas></div>
                                    <div class="ml-auto">
                                        <h2 class="text-success"><i class="ti-arrow-up"></i> <span class="counter">{{$dashboardDetails['leadsCount']}}</span></h2>
                                    </div>
                                </div>
                            </div>
                            <div id="sparkline8" class="sparkchart"></div>
                        </div>
                    </div>
                    <!-- Column -->
                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">New customer</h5>
                                <div class="d-flex no-block align-items-center m-t-20 m-b-20">
                                    <div id="sparklinedash2"><canvas width="67" height="30" style="display: inline-block; width: 67px; height: 30px; vertical-align: top;"></canvas></div>
                                    <div class="ml-auto">
                                        <h2 class="text-purple"><i class="ti-arrow-up"></i> <span class="counter">{{$dashboardDetails['customerCount']}}</span></h2>
                                    </div>
                                </div>
                            </div>
                            <div id="sparkline8" class="sparkchart"></div>
                        </div>
                    </div>
                    <!-- Column -->
                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Renewals</h5>
                                <div class="d-flex no-block align-items-center m-t-20 m-b-20">
                                    <div id="sparklinedash3"><canvas width="67" height="30" style="display: inline-block; width: 67px; height: 30px; vertical-align: top;"></canvas></div>
                                    <div class="ml-auto">
                                        <h2 class="text-info"><i class="ti-arrow-up"></i> <span class="counter">{{$dashboardDetails['renewalCount']}}</span></h2>
                                    </div>
                                </div>
                            </div>
                            <div id="sparkline8" class="sparkchart"></div>
                        </div>
                    </div>
                    <!-- Column -->
                    <!-- Column -->
                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Production</h5>
                                <div class="d-flex no-block align-items-center m-t-20 m-b-20">
                                    <div id="sparklinedash4"><canvas width="67" height="30" style="display: inline-block; width: 67px; height: 30px; vertical-align: top;"></canvas></div>
                                    <div class="ml-auto">
                                        <h2 class="text-danger"><i class="ti-arrow-up"></i> <span class="counter">{{ number_format($dashboardDetails['policySum'], 0, '.', ',')   }}</span></h2>
                                    </div>
                                </div>
                            </div>
                            <div id="sparkline8" class="sparkchart"></div>
                        </div>
                    </div>
                    <!-- Column -->
                </div>


<div class="row">
                    <!-- Column -->
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <h5 class="card-title m-b-40">SALES IN {{date('Y')}}</h5>
                                        <p>Sales lead is a potential sales contact, individual or organization that expresses an interest in our insurance services who may may eventually become our customer.</p>
                                        <p>Customer is a contact, individual or organization that have a policy issued through us.</p>
                                    </div>
                                    <div class="col-md-8 col-sm-6 col-xs-12">
                                       <ul class="list-inline text-right">
                                    <li>
                                        <h5><i class="fa fa-circle m-r-5 text-cyan"></i>Customers</h5>
                                    </li>
                                    <li>
                                        <h5><i class="fa fa-circle m-r-5 text-primary"></i>Leads</h5>
                                    </li>
                                </ul>
                                        
                                        <div id="leadsgraph" style="height: 250px;">
                                            
                                            
</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                </div>



<div class="row">
    <div class="col-lg-6">
      <div class="card">
                <div class="card-body">
                    <ul class="panel-actions list-inline pull-right dib_head" >

                                <li><a href="{{ route('customeradd') }}" ><span class="icon-add fas fa-plus large-size" data-toggle="tooltip" title="Add leads" ></span></a></li>


                            </ul> <h4 style="font-weight: 500;font-size: 1.125rem;">Leads</h4>
                                
                                
                                <div class="table-responsive m-t-40">
                     <table class="  table table-bordered table-striped dpib_leads_table color-table info-table" >
                    <thead>
                        <tr>
                            <th style="width: 15%" class="nowrap">customerName</th>
                            <th  class="nowrap">customer_idcode</th>                           
                            <th  class="nowrap">customer_customercode</th>
                            <th  class="nowrap">customer_type</th>
                            <th  class="nowrap">customer_email</th>
                            <th  class="nowrap">customer_phone</th>
                            <th  class="nowrap">saleschannel_name</th>
                            <th  class="nowrap">customergroup_name</th>
                            
                        </tr>
                    </thead>
                    <tbody>
           
                   </tbody>
                </table>
                                </div>
                            </div>
                        </div>
        
    </div>
        
        

    
   <div class="col-lg-6">
<div class="card">
                            <div class="card-body">
                                
                                 <ul class="panel-actions list-inline pull-right dib_head" >

                                <li><span class="icon-add fas fa-plus large-size dpib_client_request_add" data-toggle="tooltip" title="Add quote request" ></span></li>


                            </ul> <h4 style="font-weight: 500;font-size: 1.125rem;">Requests</h4>
                              
                                
                                <div class="table-responsive m-t-40">
                                    <table id="myTable" class="table table-bordered table-striped dpib_quote_request_list color-table info-table">
                                        <thead>
                                       <tr>
                            <th style="width: 15%" class="nowrap">Request id</th>
                            <th style="width: 15%" class="nowrap">Customer name</th>                           
                            <th  class="nowrap" style="width: 10%">Type</th>
                            <th  class="nowrap" style="width: 15%">Description</th>
                            <th  class="nowrap" style="width: 10%">Status</th>
                            <th  class="nowrap" style="width: 10%">Created at</th>
                            <th  class="nowrap" style="width: 15%">Last modified at</th>
                           
                        </tr>
                                        </thead>
                                        
                                    </table>
                                </div>
                            </div>
                        </div>
</div>
</div>
   
                      @if(count($notificationDetails) > 0)  
            <div class="row">
   <div class="col-lg-12">          
                      <div class="card">
                          <div class="card-body">
                            <h4 class="card-title">Notifications</h4>  
                                <div class="">
                                        <div class="">
                                            <select class="dpib_notification_action" name="type" tabindex="-1" >
                                                <option selected="selected" value="">--not select--</option>
                                                <option value="1">Disable notification</option>
                                               
                                            </select>
                                            <div class="dropDownSelect2"></div>
                                        </div>
                                    </div>
                             <div class="table-responsive m-t-40">
                            <table class="table table-bordered table-striped dpib_notification_table color-table info-table">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Request id</th>
                                                <th>Client</th>
                                                <th>Insurer</th>
                                                <th>Notification type</th>
                                                <th>Send date</th>                                               
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($notificationDetails as $notification)
                                            <tr class="tr-shadow">
                                                <td>
                                                    <label class="au-checkbox">
                                                        <input type="checkbox" name="brokinfslipid_{{$notification->crmId}}" value="{{$notification->brokenId}}" class="notofocation_change_id">
                                                        <span class="au-checkmark"></span>
                                                    </label>
                                                </td>                                              
                                                <td>
                                                    <span class="block-email"><a href='{!! route("crmrequestOverview",[$notification->crm_id]) !!}'>{{$notification->crm_request_id}}</a></span>
                                                </td>
                                                <td class="desc">{{$notification->client}}</td>
                                                <td class="desc">{{$notification->insurer}}</td>
                                                <td>Quote notification</td>
                                                <td>{{date('d-m-Y',strtotime($notification->updatedDate))}}</td>
                                               
                                                
                                            </tr>
<!--                                            <tr class="spacer"></tr>-->
                                            @endforeach
                                            
                                   
                                        </tbody>
                                    </table>
                             </div>
                          </div>
                          </div>
                    </div>
</div>    
                      @endif     