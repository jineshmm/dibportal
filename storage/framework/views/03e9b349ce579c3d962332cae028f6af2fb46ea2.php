<?php $__env->startSection('headtitle'); ?>
  Policy <?php if(!empty($policyDetails->policy_number)): ?>:: <?php echo e($policyDetails->policy_number); ?> <?php else: ?> :: -- Not Issued---<?php endif; ?>
  
 
<?php $__env->stopSection(); ?>
<?php $__env->startSection('overviewmenu'); ?>
 <ul id="page-content-menu" class="list-inline pull-right">
                   
 
    
<?php if(($policyDetails->policy_status ==0 || $policyDetails->policy_status ==6) ): ?>  
<li  class="dpib_policy_flag_change btn waves-effect waves-light btn-rounded btn-outline-primary" changeflag="1" data-url="<?php echo e(route('changepolicystatus',[$policyDetails->mainId])); ?>">Post policy</li>     
      <?php elseif($policyDetails->policy_status ==1): ?>     
     <li  class="dpib_policy_flag_change btn waves-effect waves-light btn-rounded btn-outline-primary" changeflag="2" data-url="<?php echo e(route('changepolicystatus',[$policyDetails->mainId])); ?>">Issue/Activate policy</li> 
     <?php elseif($policyDetails->policy_status ==1 || $policyDetails->policy_status ==2): ?>     
     <li  class="dpib_policy_flag_change btn waves-effect waves-light btn-rounded btn-outline-primary" changeflag="3" data-url="<?php echo e(route('changepolicystatus',[$policyDetails->mainId])); ?>">Lock policy</li>     
     <?php endif; ?>     
     <li class="dpib_policy_flag_change btn waves-effect waves-light btn-rounded btn-outline-primary" changeflag="4" data-url="<?php echo e(route('changepolicystatus',[$policyDetails->mainId])); ?>">Delete policy</li>
 </ul>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<?php $__env->startSection('warningmessage'); ?>

 <?php if($policyscheduleCount ==0 && $policyDetails->policy_status ==2): ?>
  <div class="alert alert-warning alert-block">
    <button type="button" class="close" data-dismiss="alert">×</button> 
        <strong>Policy schedule is didn't set yet!!!!!!</strong>
        
  </div>
 
<!--  <div class="alert alert-warning alert-block">
	<button type="button" class="close" data-dismiss="alert">×</button>	
        <strong>In oder to post policy we must set policy schedule!!!!!</strong>
        
  </div>-->
  <?php endif; ?>
  <?php $__env->stopSection(); ?>
<!--                  content here  -->
<!--TAB AREA-->
<div class="row">

    
        <div class="col-md-12 card">
        
        
        <ul class="nav nav-tabs policytab card-body" role="tablist" id="policytab">
                                <li id="tab_overview" class="nav-item" onclick="TAB.select('overview', null, 1)"> <a class="nav-link <?php echo e(empty($overviewTab) || $overviewTab == 'overview' ? 'active' : ''); ?>" data-toggle="tab" href="#content_overview" role="tab" aria-selected="true"><span class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down">Overview</span></a> </li>
                                
                                <li id="tab_coverage" class="nav-item" onclick="TAB.select('coverage', null, 0)"> <a class="nav-link <?php echo e(empty($overviewTab) || $overviewTab == 'coverage' ? 'active' : ''); ?>" data-toggle="tab" href="#content_coverage" role="tab" aria-selected="false"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">coverage</span></a> </li>
                                
                                <li id="tab_endorsement" class="nav-item" onclick="TAB.select('endorsement', '<?php echo e(route('listendorsement',[$policyDetails->mainId])); ?>', 0)"> <a class="nav-link <?php echo e(empty($overviewTab) || $overviewTab == 'endorsement' ? 'active' : ''); ?>" data-toggle="tab" href="#content_endorsement" role="tab" aria-selected="false"><span class="hidden-sm-up"><i class="ti-email"></i></span> <span class="hidden-xs-down">Endorsement (<?php echo e($endorsementCount); ?>)</span></a> </li>
                                
                                <li id="tab_installment" class="nav-item" onclick="TAB.select('installment', null, 0)"> <a class="nav-link <?php echo e(empty($overviewTab) || $overviewTab == 'installment' ? 'active' : ''); ?>" data-toggle="tab" href="#content_installment" role="tab" aria-selected="false"><span class="hidden-sm-up"><i class="ti-email"></i></span> <span class="hidden-xs-down">Installment schedule</span></a> </li>
                                <li id="tab_document" class="nav-item" onclick="TAB.select('document', '<?php echo e(route('policydocuments',[$policyDetails->customer_id, $policyDetails->mainId])); ?>', 0)"> <a class="nav-link <?php echo e(empty($overviewTab) || $overviewTab == 'document' ? 'active' : ''); ?>" data-toggle="tab" href="#content_document" role="tab" aria-selected="false"><span class="hidden-sm-up"><i class="ti-email"></i></span> <span class="hidden-xs-down">Documents (<?php echo e($documentCount); ?>)</span></a> </li>
                                <li id="tab_claims" class="nav-item" onclick="TAB.select('claims', '<?php echo e(route('getclaimdetails',[$policyDetails->mainId])); ?>', 0)"> <a class="nav-link <?php echo e(empty($overviewTab) || $overviewTab == 'claims' ? 'active' : ''); ?>" data-toggle="tab" href="#content_claims" role="tab" aria-selected="false"><span class="hidden-sm-up"><i class="ti-email"></i></span> <span class="hidden-xs-down">Claims (<?php echo e($claimCount); ?>)</span></a> </li>
                                <li id="tab_crm" class="nav-item" onclick="TAB.select('crm', '<?php echo e(route('endorsementrequest',[$policyDetails->mainId])); ?>', 0)"> <a class="nav-link <?php echo e(empty($overviewTab) || $overviewTab == 'crm' ? 'active' : ''); ?>" data-toggle="tab" href="#content_crm" role="tab" aria-selected="false"><span class="hidden-sm-up"><i class="ti-email"></i></span> <span class="hidden-xs-down">Requests (<?php echo e($policyrequestCount); ?>)</span></a> </li>
                                <li id="tab_log" class="nav-item" onclick="TAB.select('log', '<?php echo e(route('policylogdata',[$policyDetails->mainId])); ?>', 0)"> <a class="nav-link <?php echo e(empty($overviewTab) || $overviewTab == 'log' ? 'active' : ''); ?>" data-toggle="tab" href="#content_log" role="tab" aria-selected="false"><span class="hidden-sm-up"><i class="ti-email"></i></span> <span class="hidden-xs-down">Log</span></a> </li>
        </ul>
        

    </div>
    
    
    
    
    
</div>
<!--TAB CONTENT AREA-->
<div class="row card dpib-custom-form">
  
        <div id="content_overview" class="tabcontent col-md-12 card-body">
            
            
            
            
            <div class="row">
                
                  <div id="main-content" class="col-md-8">
                    <div id="panel-customer_overview" class="panel panel-default open">
                        <div class="panel-heading">
                                <?php if(in_array('ROLE_TECHNICAL_MANAGER', Auth::user()->roles) || in_array('ROLE_SALES_MANAGER', Auth::user()->roles) || in_array('ROLE_FINANCE_MANAGER', Auth::user()->roles) || Auth::user()->id == $policyDetails->created_by || in_array('ROLE_TECHNICAL_LEAD', Auth::user()->roles)): ?>
                            <ul class="panel-actions list-inline pull-right">
                                <?php if($policyDetails->policy_status ==2): ?>
                                 
                                  <li ><a href="<?php echo e(route('checklistform', [ $policyDetails->mainId])); ?>"><span class="far fa-list-alt text-blue" data-toggle="tooltip" title="" data-original-title="Policy checklist"></span></a></li>
                                 <?php endif; ?>
                                 <li ><a href="<?php echo e(route('createschedule', [$policyDetails->customer_id, $policyDetails->mainId])); ?>"><span class="far fa-calendar-plus text-blue" data-toggle="tooltip" title="" data-original-title="Edit policy schedule"></span></a></li>
                                <li ><a href="<?php echo e(route('editpolicy',$policyDetails->mainId)); ?>"><span class="fas fa-edit text-blue" data-toggle="tooltip" title="" data-original-title="Edit policy info"></span></a></li>


                            </ul>
                             <?php endif; ?>
                            <h3 class="panel-title">Policy info</h3></div>
                        <div id="customer_overview" class="panel-collapse panel-body">
                            <table class="info-table" width='100%'>
                                <tbody>
                                    <tr><td>Customer:</td><td><a href='<?php echo e(route('customeroverview',$policyDetails->customer_id)); ?>'><?php echo e($policyDetails->customerName); ?></a> </td></tr>
                                    <tr><td>Coverage:</td><td><?php echo e($policyDetails->coverage); ?></td></tr>
                                    <tr><td>Object:</td><td><?php echo e(($policyDetails->coverage_info==null) ? $policyDetails->product_name :  $policyDetails->coverage_info); ?>   </td></tr>

                                    <tr class="subtitle"><th colspan="2">Policy info</th></tr>
                                    <tr><td>Policy type:</td><td>
                                            <?php if($policyDetails->policy_type ==3): ?>
                                            Motor
                                            <?php elseif($policyDetails->policy_type ==2): ?>
                                            Medical                                           
                                            <?php else: ?>
                                            General
                                            <?php endif; ?>
                                        </td></tr>
                                    <tr><td>Insurer:</td><td class="phoneNumber"><?php echo e($policyDetails->insurer_name); ?></td></tr>
                                    <tr><td>Policy number:</td><td class="phoneNumber"><?php if($policyDetails->policy_number !=''): ?>  <span class='text-success' style='font-weight: bold'><?php echo e($policyDetails->policy_number); ?><span> <?php else: ?> <span class='text-danger' style='font-weight: bold'>not issued</span><?php endif; ?></td></tr>
                                    <tr><td>Inception date:</td><td> <?php echo e(date('d.m.Y', strtotime($policyDetails->start_date))); ?>  </td></tr>
                                    <tr><td>End date:</td><td><?php echo e(date('d.m.Y', strtotime($policyDetails->end_date))); ?> </td></tr>
                                    <tr><td>Issue date:</td><td><?php echo e(date('d.m.Y', strtotime($policyDetails->issue_date))); ?> </td></tr>
                                    
                                    <tr><td>Status:</td><td>
                                            <?php if($policyDetails->policy_status ==0): ?>
                                            <span class='text-danger' style='font-weight: bold'>Saved</span>
                                            <?php elseif($policyDetails->policy_status ==2): ?>
                                            <span class='text-success' style='font-weight: bold'> Active/Issued</span>
                                            <?php elseif($policyDetails->policy_status ==4): ?>
                                            <span class='text-success' style='font-weight: bold'> Renewed</span>
                                             <?php elseif($policyDetails->policy_status ==6): ?>
                                            <span class='text-danger' style='font-weight: bold'> Rejected</span>
                                            <?php else: ?>
                                             Post
                                            <?php endif; ?>
                                        
                                        </td></tr>
                                        <?php if($policyDetails->policy_status ==6): ?>
                                       <tr><td>Rejected reason:</td><td class="phoneNumber"><i><b><?php echo e($policyDetails->reject_reason); ?></b></i></td></tr>
                                      <?php endif; ?>  
                                    <tr class="subtitle"><th colspan="2">Sales</th></tr>
                                    <tr><td>Sales type:</td><td><?php echo e(($policyDetails->sales_type ==1) ? "new sales": "manual renewal"); ?></td></tr>
                                    <tr class="subtitle"><th colspan="2">Renewal</th></tr>
                                    <tr><td>Renewable status:</td><td><?php echo e($policyDetails->policystatusString); ?></td></tr>
                                    <?php if( $policyDetails->renewal_status ==1): ?>
                                    <tr><td>Previous policy number:</td><td><a href="<?php echo e(route('policyoverview',[$policyDetails->previous_policy_id])); ?>"><?php echo e($policyDetails->previusPolicy); ?></a></td></tr>
                                     <?php endif; ?>    
                                    

                                </tbody>
                            </table>
                        </div></div>
                    <div id="panel-customer_address" class="panel panel-default open">
                        <div class="panel-heading">
                                        <h3 class="panel-title">Persons and commission split</h3>
                        </div>
                        <div id="customer_address" class="panel-collapse panel-body">


                            <?php if(count($commissionDetails) >0): ?>
                            <table class="table table-bordered table-striped table-hovered color-table">
                                <thead>
                                    <tr> <th>#</th><th>PERSON</th><th class="nowrap">ROLE </th><th class="nowrap">COMMISSION(%) </th><th class="nowrap">COMMISSION SAR </th></tr> 

                                </thead>
                                <tbody>


                                    <?php $__currentLoopData = $commissionDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key =>$commission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
                                    <tr>

                                        <td><?php echo e($key+1); ?> </td>
                                        <td><?php echo e(($commission->distributor_type =='sales person') ? $commission->salesperson: 'Diamond insurance broker'); ?> </td>
                                        <td><?php echo e(($commission->distributor_type =='sales person') ? $commission->distributor_type: 'rounding correction'); ?> </td>
                                        <td><?php echo e(($commission->commission_type ==0) ? $commission->percentage: 'Amount'); ?> </td>
                                        <td><?php echo e(number_format($commission->totalAmount, 2, '.', ',')); ?> </td>

                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                                </tbody></table>
                            <?php endif; ?>

                        </div>
                    </div>

                    <div id="panel-customer_contact" class="panel panel-default open">
                        <div class="panel-heading">
                            
                            <h3 class="panel-title">Revenue</h3></div>
                        <div id="customer_contact" class="panel-collapse panel-body">
                            <?php if(count($commissionDetails) >0): ?>
                            <?php 
                            $amount=0;
                            $total =0;
                            ?>                           
                            <table class="info-table">
                                <tbody><tr>
                                        <td class="normal" style="width: 31%; font-weight: bold; text-align: left;"></td>
                                        <td style="width: 23%; font-weight: bold; text-align: right; white-space: nowrap;">Commission</td>

                                        <td style="width: 23%; font-weight: bold; text-align: right; white-space: nowrap;">Total</td>
                                    </tr>
                                    <?php $__currentLoopData = $commissionDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key =>$commission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
                                    <?php if($commission->distributor_type =='sales person'): ?>
                                    <?php 
                                    $amount= $commission->totalAmount  ;

                                    ?> 
                                    <tr>
                                        <td class="normal" style="text-align: left">Internal persons:</td>

                                        <td style="text-align: right; white-space: nowrap">
                                            <span style="color: #919191"><?php echo e(number_format($commission->totalAmount, 2, '.', ',')); ?> SAR</span>
                                        </td>
                                        <td style="font-weight: bold; text-align: right; white-space: nowrap"><?php echo e(number_format($commission->totalAmount, 2, '.', ',')); ?> SAR</td>
                                    </tr>
                                    <?php elseif($commission->distributor_type =='diamond'): ?>   
                                    <?php 
                                    $total= $commission->totalAmount;

                                    ?> 
                                    <tr>
                                        <td class="normal" style="text-align: left">Diamond insurance broker:</td>

                                        <td style="text-align: right; white-space: nowrap">
                                            <span style="color: #919191"><?php echo e(number_format( $commission->totalAmount - $amount, 2, '.', ',')); ?>   SAR</span>
                                        </td>
                                        <td style="font-weight: bold; text-align: right; white-space: nowrap"><?php echo e(number_format($commission->totalAmount - $amount, 2, '.', ',')); ?> SAR</td>
                                    </tr>
                                    <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                    <tr>
                                        <td style="font-weight: bold; text-align: left">TOTAL:</td>
                                        <td style="font-weight: bold; text-align: right; white-space: nowrap"><?php echo e(number_format($total, 2, '.', ',')); ?> SAR</td>

                                        <td style="font-weight: bold; text-align: right; white-space: nowrap"><?php echo e(number_format($total, 2, '.', ',')); ?> SAR</td>
                                    </tr>
                                </tbody></table>



                            <?php endif; ?>
                        </div>
                    </div>


                </div> 
                
                
                
                
                
                <aside class="col-md-3">
                    <?php 

                    $total =0;
                    $payment=0;
                    ?>   
                    <div id="panel-customer_balance" class="panel panel-default open">
                        <div class="panel-heading"><h3 class="panel-title">Premium</h3></div>
                        <div id="customer_premium" class="panel-collapse panel-body">

                            <table class="info-table">

                                <tbody>
                                    <tr id="signedPremium">
                                        <td>Gross premium:</td>
                                        <td>
                                            <span>
                                                <?php echo e(number_format($policyDetails->total_premium, 2, '.', ',')); ?>  SAR</span>
                                        </td>
                                    </tr>
                                    <?php if($endorsementCount > 0): ?>
                                   <tr id="signedPremium">
                                        <td>Endorsements:</td>
                                        <td>
                                            <span>
                                                <?php if($endorsementamount !=''): ?>
                                                <?php echo e(number_format($endorsementamount, 2, '.', ',')); ?>  SAR
                                                <?php else: ?>
                                                 0.00 SAR
                                                <?php endif; ?>
                                                
                                                </span>
                                        </td>
                                    </tr>
                                    <?php endif; ?>

                                    <tr id="gross_written_premium">
                                        <td>Gross written premium:</td>
                                        <td>
                                            <span>
                                                <?php echo e(number_format(($policyDetails->total_premium + $endorsementamount), 2, '.', ',')); ?> SAR</span>
                                        </td>
                                    </tr>
                                     <tr id="installments">
                                        <td>Installments:</td>
                                        <td>
                                            <span>
                                                <?php echo e(($policyDetails->installment_number !=0) ? $policyDetails->installment_number:''); ?></span>
                                        </td>
                                    </tr>
                                   	

                                    <tr id="collection">
                                        <td>Collection:</td>
                                        <td>
                                            <span>
                                                <?php echo e($policyDetails->collection_type); ?>  </span>
                                        </td>
                                    </tr>

                                    <tr id="netPremium">
                                        <td>Net premium:</td>
                                        <td>
                                            <span>
                                           
                                                <?php echo e(number_format(($policyDetails->total_premium +  $endorsementamount +$vatAmount->installmentVat)- $companyRevanue, 2, '.', ',')); ?> SAR</span>
                                        </td>
                                    </tr>
                                    
                                      <tr  class="text-success">
                                        <td >Total premium:</td>
                                        <td>
                                            <span style="font-weight: bold">
                                           
                                                <?php echo e(number_format(($policyDetails->total_premium +  $endorsementamount +$vatAmount->installmentVat), 2, '.', ',')); ?> SAR</span>
                                        </td>
                                    </tr>
                                </tbody></table>



                        </div>

                    </div>
                    <div id="panel-customer_profile" class="panel panel-default open">
                        <div class="panel-heading"><h3 class="panel-title">Commission</h3></div>
                        <div id="company_commission" class="panel-collapse panel-body">
                            <table class="info-table">

                                <tbody><tr>
                                        <td>Commission:</td>
                                        <td>

                                            <?php echo e($policyDetails->commision); ?>% 
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Commission:</td>
                                        <td style="width: 60%;  ">
                                     
                                            <?php echo e(number_format($companyRevanue, 2, '.', ',')); ?>

                      
                                            SAR
                                        </td>
                                    </tr>


                                </tbody></table>
                        </div>
                    </div>
                    <div id="panel-customer_salesopportunities" class="panel panel-default open">
                        <div class="panel-heading"><h3 class="panel-title">Taxes</h3></div>
                        <div id="customer_tax" class="panel-collapse panel-body">
                            <table class="info-table">
                                <tbody><tr>
                                        <td>Total:</td>
                                        <td id="total_tax_value"> <?php echo e(number_format(($vatAmount->installmentVat), 2, '.', ',')); ?> SAR</td>
                                    </tr>
                                </tbody></table>
                        </div>
                    </div>


                </aside>
             
            </div>
        </div>
    
        <div id="content_coverage" class="tabcontent col-12" rel="" style="display: none;">


            <div id="panel-policy_product_10020236" class="panel panel-default open">
                <div class="panel-heading">
                    <?php if(!empty($policyDetails->product_id)): ?>
                    <ul class="panel-actions list-inline pull-right">
                        <li>
                            
                            <a href="<?php echo e(route('updatecoveragedata',[$policyDetails->mainId, $policyDetails->product_id])); ?>">
                                <span class="fas fa-edit text-blue" data-toggle="tooltip" title="" data-original-title="Edit product info"></span></a>
                        </li>
                        
                    </ul>
                    <?php endif; ?>
                    <h3 class="panel-title"><?php echo e($policyDetails->product_name); ?></h3></div>
                <div id="policy_product_10020236" class="panel-collapse panel-body">
                    <table class="insly-form">
                        <tbody>

                            <?php $__currentLoopData = $coverageDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key =>$coverageDetail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
                            <tr id="field_prop_field_<?php echo e($key); ?>" class="field">
                                <td class=""><div class="label full-height" style="height: 32px;"><span class="title"><?php echo e($coverageLabel[$key]); ?></span></div></td>
                                <td><div class="element element-text">
                                        <?php echo e($policyDetails->$coverageDetail); ?>


                                    </div></td>
                            </tr>


                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>




                            <tr class="field">
                                <td colspan="2" class="subtitle">Commission</td>
                            </tr>
                            <tr class="field">
                                <td><div class="label">Commission:</div></td>
                                <td><div class="element element-text"><?php echo e($policyDetails->commision); ?>%</div></td>
                            </tr>
                            <tr class="field">
                                <td colspan="2" class="subtitle">Tax</td>
                            </tr>
                            <tr class="field">
                                <td><div class="label">Tax:</div></td>
                                <td><div class="element element-text"><?php echo e($policyDetails->tax); ?>%</div></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php if(count($objectDetails) >0): ?>
            <div>
                <div id="panel-policy_object" class="panel panel-default open">
                    <div class="panel-heading">
                        <?php if(!empty($policyDetails->product_id)): ?>  
                        <ul class="panel-actions list-inline pull-right">
                            <?php if( $policyDetails->product_id ==27 || $policyDetails->product_id ==38): ?>
                            <li class="dp_policy_object_add" data-url="<?php echo e(route('editobjectform',[$policyDetails->mainId,$policyDetails->product_id, 0])); ?>"><span class="fas fa-plus" data-toggle="tooltip" title="" data-original-title="Add another object" ></span>
                              <?php else: ?>  
                                <li class="dp_policy_object_edit" product-id="<?php echo e($policyDetails->product_id); ?>" object-id="<?php echo e($objectDetails[0]->objectId); ?>" data-url="<?php echo e(route('editobjectform',[$policyDetails->mainId,$policyDetails->product_id, $objectDetails[0]->objectId])); ?>"><span class="fas fa-edit text-blue" data-toggle="tooltip" title="" data-original-title="Edit object"></span>                                
                             <?php endif; ?>   
                            </li>
                        </ul>
                        <?php endif; ?>
                          
                        <h3 class="panel-title">Objects</h3></div>
                    <div id="policy_object" class="panel-collapse panel-body">
                        
                       
                            

                                <?php $__currentLoopData = $objectDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key =>$objectDetail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
                                    <?php if(($objectDetail->object_type =='person' || $objectDetail->object_type =='vehicle_multiple') && $key==0): ?>
                            <table class="table table-striped table-hovered table-bordered insly-form">
                                    <thead>
                                <tr class="headerrow">
                                    <th style="width: 80%">Object</th>
                                    <th style="width: 1%">Status</th>
                                    <th style="width: 1%; white-space: nowrap;">Last Change</th>
                                    <th style="width: 1%"></th>
                                </tr>
                            </thead>
                            
                                <?php elseif($key==0): ?>
                                <table class="insly-form">
                                <tbody>
                                
                            <?php endif; ?>
                                <?php if($objectDetail->object_type =='person'): ?>  
                                <tr class="datarow table-striped-row-light objectrow">
                                    <td>

                                        <?php echo e($objectDetail->last_name); ?>,<?php echo e($objectDetail->first_name); ?>


                                    </td>
                                    <td style="width: 1%;">active</td>
                                    <td style="width: 1%;"><?php if(!is_null($objectDetail->updated_date)): ?>  <?php echo e(date('d.m.Y h:i',strtotime($objectDetail->updated_date))); ?> <?php endif; ?></td>
                                    <td class="iconactions"> 
                                        <?php if(!empty($policyDetails->product_id)): ?>  
                                        <a class="dp_policy_object_edit" data-url="<?php echo e(route('editobjectform',[$policyDetails->mainId,$policyDetails->product_id, $objectDetail->objectId])); ?>">
                                            <span data-original-title="Edit object" class="fas fa-edit text-blue" data-toggle="tooltip" title=""></span></a>
                                            <a class="dp_policy_object_remove" data-url="<?php echo e(route('editobjectform',[$policyDetails->mainId,$policyDetails->product_id, $objectDetail->objectId])); ?>">
                                                <span data-original-title="Remove object" class="fas fa-archive text-blue" data-toggle="tooltip" title=""></span></a>
                                                <?php endif; ?>
                                    </td>
                                </tr>
                                
                                <?php elseif($objectDetail->object_type =='vehicle_multiple'): ?>  
                                <tr class="datarow table-striped-row-light objectrow">
                                    <td>

                                        <?php echo e($objectDetail->make); ?>,<?php echo e($objectDetail->model); ?>,<?php echo e($objectDetail->year); ?>,<?php echo e($objectDetail->license_plate); ?>,<?php echo e($objectDetail->no_of_passengers); ?>


                                    </td>
                                    <td style="width: 1%;">active</td>
                                    <td style="width: 1%;"><?php if(!is_null($objectDetail->updated_date)): ?>   <?php echo e(date('d.m.Y h:i',strtotime($objectDetail->updated_date))); ?> <?php endif; ?> </td>
                                    <td class="iconactions">
                                        <?php if(!empty($policyDetails->product_id)): ?>  
                                        <a class="dp_policy_object_edit" data-url="<?php echo e(route('editobjectform',[$policyDetails->mainId,$policyDetails->product_id, $objectDetail->objectId])); ?>">
                                            <span data-original-title="Edit object" class="fas fa-edit" data-toggle="tooltip" title=""></span></a>
                                            <a class="dp_policy_object_remove" data-url="<?php echo e(route('removeobject')); ?>" objId="<?php echo e($objectDetail->objectId); ?>" product-id='<?php echo e($policyDetails->mainId); ?>'><span data-original-title="Remove object" class="fas fa-archive" data-toggle="tooltip" title=""></span></a>
                                    <?php endif; ?>
                                    </td>
                                </tr>
                                
                                

                                <?php elseif($objectDetail->object_type =='vehicle'): ?>
                                <tr id="field_prop_field_10002072" class="field ">
                                    <td class="">
                                        <div class="label ">
                                            <span class="text-danger icon-asterix"></span>
                                            <span class="title">Make</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="element">

                                            <div class="element element-text element-disabled-display-type">

                                                <span><?php echo e($objectDetail->make); ?></span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr id="field_prop_field_10002073" class="field ">
                                    <td class="">
                                        <div class="label ">
                                            <span class="text-danger icon-asterix"></span>
                                            <span class="title">Model</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="element">

                                            <div class="element element-text element-disabled-display-type">

                                                <span><?php echo e($objectDetail->model); ?></span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr id="field_prop_field_10002075" class="field ">
                                    <td class="">
                                        <div class="label ">
                                            <span class="text-danger icon-asterix"></span>
                                            <span class="title">Year</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="element">

                                            <div class="element element-text element-disabled-display-type">

                                                <span><?php echo e($objectDetail->year); ?></span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr id="field_prop_field_10001061" class="field ">
                                    <td class="">
                                        <div class="label ">
                                            <span class="text-danger icon-asterix"></span>
                                            <span class="title">License plate</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="element">

                                            <div class="element element-text element-disabled-display-type">

                                                <span><?php echo e($objectDetail->license_plate); ?></span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr id="field_prop_field_10002085" class="field ">
                                    <td class="">
                                        <div class="label ">
                                            <span class="text-danger icon-asterix"></span>
                                            <span class="title">Body Type</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="element">

                                            <div class="element element-text element-disabled-display-type">

                                                <span><?php echo e($objectDetail->body_type); ?></span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr id="field_prop_field_10001062" class="field ">
                                    <td class="">
                                        <div class="label ">
                                            <span class="text-danger icon-asterix"></span>
                                            <span class="title">VIN code</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="element">

                                            <div class="element element-text element-disabled-display-type">

                                                <span><?php echo e($objectDetail->vin_code); ?></span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr id="field_prop_field_10002076" class="field ">
                                    <td class="">
                                        <div class="label ">
                                            <span class="text-danger icon-asterix"></span>
                                            <span class="title">Usage</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="element">

                                            <div class="element element-text element-disabled-display-type">

                                                <span><?php echo e($objectDetail->vehicle_usage); ?></span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr id="field_prop_field_10008080" class="field ">
                                    <td class="">
                                        <div class="label ">
                                            <span class="text-danger icon-asterix"></span>
                                            <span class="title">CAR NUMBER OF PASSENGERS</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="element">

                                            <div class="element element-text element-disabled-display-type">

                                                <span><?php echo e($objectDetail->no_of_passengers); ?></span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr id="field_prop_field_10001063" class="field ">
                                    <td class="">
                                        <div class="label ">
                                            <span class="text-danger "></span>
                                            <span class="title">Power (kw)</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="element">

                                            <div class="element element-text element-disabled-display-type">

                                                <span><?php echo e($objectDetail->power); ?></span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr id="field_prop_field_10001064" class="field ">
                                    <td class="">
                                        <div class="label ">
                                            <span class="text-danger icon-asterix"></span>
                                            <span class="title">Gross weight (kg)</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="element">

                                            <div class="element element-text element-disabled-display-type">

                                                <span><?php echo e($objectDetail->gross_weight); ?></span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>



                                 <?php elseif($objectDetail->object_type =='property'): ?> 

                                <tr id="field_prop_field_10001067" class="field ">
                                    <td class="">
                                        <div class="label ">
                                            <span class="text-danger icon-asterix"></span>
                                            <span class="title">Address</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="element">

                                            <div class="element element-text element-disabled-display-type">
                                                
                                                <span><?php echo e($objectDetail->address); ?></span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr id="field_prop_field_10001068" class="field ">
                                    <td class="">
                                        <div class="label ">
                                            <span class="text-danger icon-asterix"></span>
                                            <span class="title">Property type</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="element">

                                            <div class="element element-text element-disabled-display-type">
                                              
                                                <span><?php echo e($objectDetail->property_type); ?></span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr id="field_prop_field_10001069" class="field ">
                                    <td class="">
                                        <div class="label ">
                                            <span class="text-danger "></span>
                                            <span class="title">Year built</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="element">

                                            <div class="element element-text element-disabled-display-type">
                                                
                                                <span><?php echo e($objectDetail->year_built); ?></span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr id="field_prop_field_10001070" class="field ">
                                    <td class="">
                                        <div class="label ">
                                            <span class="text-danger "></span>
                                            <span class="title">Area</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="element">

                                            <div class="element element-text element-disabled-display-type">
                                               
                                                <span><?php echo e($objectDetail->area); ?></span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr id="field_prop_field_10001071" class="field ">
                                    <td class="">
                                        <div class="label ">
                                            <span class="text-danger "></span>
                                            <span class="title">Construction material</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="element">

                                            <div class="element element-text element-disabled-display-type">
                                                
                                                <span><?php echo e($objectDetail->construction_material); ?></span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                

                                <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php endif; ?>

        </div>
        <div id="content_endorsement" class="tabcontent col-12" rel="<?php echo e(route('listendorsement',[$policyDetails->mainId])); ?>" style="display:none">
            endorsement  display area

        </div>

        <div id="content_installment" class="tabcontent col-12" rel="" style="display: none;">
            <div id="panel-policy_installment_payment" class="panel panel-default open">
                
                <div class="panel-heading">
                  <?php if(in_array('ROLE_TECHNICAL_MANAGER', Auth::user()->roles) || in_array('ROLE_SALES_MANAGER', Auth::user()->roles) || in_array('ROLE_FINANCE_MANAGER', Auth::user()->roles) || Auth::user()->id == $policyDetails->created_by): ?>  
                    <ul class="panel-actions list-inline pull-right">
                        <li id="dpib_edit_premium_info" data-url="<?php echo e(route('getpremiuminfodata',[$policyDetails->mainId])); ?>"><span class="fas fa-edit text-blue" data-toggle="tooltip" title="" data-original-title="Edit premium info"></span></li>
                 
                    </ul>
                    <?php endif; ?>
                    <h3 class="panel-title">Payment</h3>
                </div>
                <div id="policy_installment_payment" class="panel-collapse panel-body">
                    <table class="info-table">
                        <tbody>
                            <tr>
                                <td style="width: 12%; font-weight: bold">Gross Premium:</td>
                                <td style="width: 21%">
                                    <span class="text-danger" style="font-weight: bold">
                                        
                                        <?php echo e(number_format($policyDetails->gross_amount, 2, '.', ',')); ?> SAR
                                    </span>
                                </td>
                                <td style="width: 12%; font-weight: bold">Installments:</td>
                                <td style="width: 21%">
                                    <?php echo e($policyDetails->installment_number); ?>

                                </td>
                                <td style="width: 12%; font-weight: bold"></td>
                                <td style="width: 21%"></td>
                            </tr>
                            <tr>
                                <td style="width: 12%; font-weight: bold">Addition amount:</td>
                                <td style="width: 21%"><?php echo e(number_format($policyDetails->additional_amount, 2, '.', ',')); ?> SAR</td>

                                <td style="width: 12%; font-weight: bold">Collection:</td>
                                <td style="width: 21%">
                                    <?php echo e($policyDetails->collection_type); ?>

                                </td>
                            </tr>
                            <tr>
                                <td style="width: 12%; font-weight: bold">Commission:</td>
                                <td style="width: 21%"><?php echo e($policyDetails->commision); ?>  %</td>

                                <td style="width: 12%; font-weight: bold"></td>
                                <td style="width: 21%">

                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
             <?php if(count($installmentDetails) >0): ?>
            <div id="panel-policy_installment_schedule" class="panel panel-default open">
                <div class="panel-heading">
                    <ul class="panel-actions list-inline pull-right">
                     
                            <div class="btn-group">
                                <span class="icon-settings text-blue"></span>
                         
                                <ul id="add-menu" class="dropdown-menu" role="menu">
                                   <?php if(in_array('ROLE_TECHNICAL_MANAGER', Auth::user()->roles)  || in_array('ROLE_FINANCE_MANAGER', Auth::user()->roles) || Auth::user()->id == $policyDetails->created_by): ?>   
                                    <li id="dpib_regenerate_installment" data-url="<?php echo e(route('regenerateinstallment',[$policyDetails->mainId])); ?>" policyId="<?php echo e($policyDetails->mainId); ?>"><a>Generate installment schedule</a></li>
                                      <?php endif; ?>
                                    <?php if(in_array('ROLE_FINANCE_MANAGER', Auth::user()->roles) || in_array('ROLE_FINANCE', Auth::user()->roles)): ?>    
                                    <li id="dpib_generate_invoice" data-url="<?php echo e(route('invoicegenerate',[$policyDetails->mainId])); ?>" policyId="<?php echo e($policyDetails->mainId); ?>"><a>Generate Invoice</a></li>
                                      <?php endif; ?>
                                </ul>
                            </div>
                        </li>
                    </ul>
                    <h3 class="panel-title">Installment schedule</h3>
                </div>
                <div id="policy_installment_schedule" class="panel-collapse panel-body">
                    
                    <table class="table table-striped table-hovered table-bordered policy_installment_table">
                        <thead>
                            <tr>
                               <?php if($policyDetails->policy_status ==2 && (in_array('ROLE_FINANCE_MANAGER', Auth::user()->roles) || in_array('ROLE_FINANCE', Auth::user()->roles))): ?>
                                <th style="width: 1%;"></th>
                                 <?php endif; ?>
                                <th style="width: 1%;">Installment</th>
                                <th style="width: 5%;">Endorsement</th>
                                
                                <th style="width: 10px;">Gross Premium</th>
                                <th style="width: 20px;">Period</th>
                                <th style="width: 20px;">Due date</th>
                                <th style="width: 40px;">Collects</th>
                                <th style="width: 30px;">Comm.</th>

                                <th style="width: 5px;">Tax</th>
                                <th style="width: 10px;">Customer payable</th>
                                <th style="width: 10px;">Paid status</th>
                                <th style="width: 1%;" class="nowrap"></th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            $total = $tax = $customerPayable = 0;
                           
                            $i=0;
                            ?>
                           

                            <?php $__currentLoopData = $installmentDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key =>$installmentDetail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                            $total = $total+ floatval($installmentDetail->amount);
                            $tax = $tax + floatval($installmentDetail->vat_amount);
                            $customerPayable = $customerPayable + floatval(($installmentDetail->amount + $installmentDetail->vat_amount));
                            ?>
                            <tr style="font-weight: bold" class="table-striped-row-light">                                
                               <?php if($policyDetails->policy_status ==2 && (in_array('ROLE_FINANCE_MANAGER', Auth::user()->roles) || in_array('ROLE_FINANCE', Auth::user()->roles)) ): ?>
                                <td> 
                                    <?php if($installmentDetail->paid_status ==0): ?>
                                    
                                    <div class="custom-control custom-checkbox mr-sm-2">
                                                <input type="checkbox" id="payments<?php echo e($key+1); ?>" name="payments[]" class="paymentIds custom-control-input" value="<?php echo e($installmentDetail->id); ?>">
                                                <label class="custom-control-label" for="payments<?php echo e($key+1); ?>"></label>
                                    </div>

                                    <?php endif; ?>
                                
                                </td> 
                                 <?php endif; ?>
                                
                                <?php if( empty($installmentDetail->endorsement_id)): ?>
                                 <?php
                                 $i++;
                                 ?>
                                <td style="text-align: left;"><?php echo e($i); ?></td>
                                <?php else: ?> 
                                <td style="text-align: left;"><img src="<?php echo e(asset('Images/icon-policy-payment-2.png')); ?>" data-toggle="tooltip" data-original-title="Comment/description: Endorsement <?php echo e($installmentDetail ->endorsement_number); ?>"></td>
                                <?php endif; ?>
                                <td class="nowrap"><?php echo e($installmentDetail ->endorsement_number); ?></td>
                                 <?php if( empty($installmentDetail->endorsement_id)): ?>
                                  <td class="nowrap"><?php echo e(number_format($installmentDetail->amount, 2, '.', ',')); ?> SAR</td>
                                 <?php else: ?>
                                  <td class="nowrap"><?php echo e(number_format($installmentDetail->amount, 2, '.', ',')); ?> SAR</td>
                                 <?php endif; ?>
                                
                                
                                <td><?php echo e(date('d.m.Y', strtotime($installmentDetail->start_date))); ?> - <?php echo e(date('d.m.Y', strtotime($installmentDetail->end_date))); ?></td>
                                <td><?php echo e(date('d.m.Y', strtotime($installmentDetail->due_date))); ?></td>
                                <td><i style="color: #555555"><?php echo e($installmentDetail->collectionString); ?></i></td>
                                <td><i style="color: #555555" data-toggle="tooltip" data-original-title=" <?php if($installmentDetail->amount >0): ?> <?php echo e(number_format( (($installmentDetail->amount * $policyDetails->commision)/100), 2, '.', ',')); ?> SAR <?php else: ?> <?php echo e(number_format( 0, 2, '.', ',')); ?> <?php endif; ?>"> <?php if($installmentDetail->amount >0): ?>  <?php echo e($policyDetails->commision); ?>%   <?php else: ?> <?php echo e(number_format( 0, 2, '.', ',')); ?> <?php endif; ?></i></td>

                                <td> <?php echo e(number_format($installmentDetail->vat_amount, 2, '.', ',')); ?> </td>
                                <td class="nowrap"><a onclick=""><?php echo e(number_format( ($installmentDetail->amount + $installmentDetail->vat_amount), 2, '.', ',')); ?> SAR</a></td>
                                <td class="nowrap"> <?php if($installmentDetail->paid_status==1): ?> <span class="text-success">Paid</span> <?php else: ?> <span class="text-danger">Unpaid</span>  <?php endif; ?></td>


                                <td class="iconactions nowrap">
                                    
                                     <?php if($installmentDetail->default_flag ==0 && empty($installmentDetail->endorsement_id) && $installmentDetail->paid_status==0): ?>
                                    <div class="btn-group">
                                        
                                            <span class="fas fa-edit"></span>                                            
                                            <span class="icon-active"></span>
                                  
                                      
                                        <ul class="dropdown-menu edit" role="menu">
                                            
                                            <li><a class="dpib_edit_installment" data-url="<?php echo e(route('editinstallment',[$installmentDetail->id])); ?>">Edit installment</a>
                                            </li> 
                                        </ul>
                                       
                                      
                                    </div>
                                    
                                       <?php endif; ?>
                                   
<!--                                       <div class="btn-group">
                                        <button type="button">
                                            <span class="icon-invoice"></span>
                                            <span class="icon-arrow-down"></span>
                                            <span class="icon-active"></span>
                                        </button>
                                        <ul class="dropdown-menu invoice" role="menu">
                                            <li><a onclick="">Create invoice</a>
                                            </li>            </ul>
                                    </div>-->
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> 
                           

                            <tr class="table-striped-row-dark">
                                <?php if($policyDetails->policy_status ==2 && (in_array('ROLE_FINANCE_MANAGER', Auth::user()->roles) || in_array('ROLE_FINANCE', Auth::user()->roles))): ?>
                                <td></td>
                                <?php endif; ?>
                                <td><b>TOTAL SAR</b></td>
                                <td><b></b></td>
                                <td><?php echo e(number_format($total, 2, '.', ',')); ?> SAR</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>

                                <td><?php echo e(number_format($tax, 2, '.', ',')); ?> SAR</td>
                                <td><?php echo e(number_format($customerPayable, 2, '.', ',')); ?> SAR</td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                     
                </div>
            </div>
              <?php endif; ?>
        </div>
        <div id="content_document" class="tabcontent col-12" rel="<?php echo e(route('policydocuments',[$policyDetails->customer_id, $policyDetails->mainId])); ?>" style="display:none">

      
        </div>
        <div id="content_claims" class="tabcontent col-12" rel="<?php echo e(route('getclaimdetails',[$policyDetails->mainId])); ?>" style="display:none">
            claim display area

        </div>
        <div id="content_crm" class="tabcontent col-12"  style="display:none" rel="<?php echo e(route('endorsementrequest',[$policyDetails->mainId])); ?>" >
            crm display area
        </div>
           
        

        <div id="content_log" class="tabcontent col-12"  style="display:none;" rel="<?php echo e(route('policylogdata',[$policyDetails->mainId])); ?>">
            log display area 

        </div>

    

</div>


<script id='policy_issued_template' type='text/template'>
    
 <?php echo e(Form::open(array('route' => array('changepolicystatus',$policyDetails->mainId), 'name' => 'form_policy_status_change','id'=>'form_policy_status_change','files'=>'true' ))); ?>


    <div class="dialogform"><table class="insly_dialogform">
                <tbody>                    
                    <tr id="field_request_type" class="field">
                        <td class="">
                     Do you really want to activate policy?.
                                <input type='hidden' name='flag' value=2>
                             
                           
                        </td>
                    </tr>
                    
                    
                    

                </tbody></table></div>
    <?php echo e(Form::close()); ?>   
    
    
</script>

<script id='policy_post_template' type='text/template'>
    
 <?php echo e(Form::open(array('route' => array('changepolicystatus',$policyDetails->mainId), 'name' => 'form_policy_status_change','id'=>'form_policy_status_change','files'=>'true' ))); ?>


    <div class="dialogform"><table class="insly_dialogform insly-form">
                <tbody>                    
                    <tr id="field_request_type" class="field">
                        <td style='width:25%'>
              Policy number                              
                        </td>
                        <td>
                             <input type='hidden' name='flag' value=1>
                             <input type='text' name='policy_number' id="policy_number"  value=''>
                            </td>
                    </tr>
   <tr id="field_5dac554a1ac30_prop_field_10068074" class="field">
                <td class="">
                    <div class="label full-height" style="height: 39px;"><span class="title">Send following documents:</span></div>
                </td>
                <td>
                    <div class="form-group row pt-3" style="margin-left:10px;">
                        
                            <div class="col-sm-4 custom-control custom-checkbox"><input type="checkbox" class="custom-control-input" id="send_document_policy" name="send_document[]" value="Policy"><label style="width: 30%; cursor: pointer; color: rgb(87, 87, 87);background-color:#fff!important" class="custom-control-label" for="send_document_policy"> Policy</label></div>
                            <div class="col-sm-4 custom-control custom-checkbox"> <input type="checkbox" class="custom-control-input" id="send_document_card" name="send_document[]" value="Insurance card"><label style="width: 92%; cursor: pointer; color: rgb(87, 87, 87);background-color:#fff!important" class="custom-control-label" for="send_document_card">Insurance card</label></div>
                        </div>
                        </td>
                        </tr>

                </tbody>
                </table>
    </div>
    <?php echo e(Form::close()); ?>   
    
    
</script>



<?php $__env->stopSection(); ?>
<?php $__env->startSection('customcss'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('elitedesign/assets/node_modules/datatables.net-bs4/css/dataTables.bootstrap4.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('elitedesign/assets/node_modules/datatables.net-bs4/css/responsive.dataTables.min.css')); ?>">
<?php $__env->stopSection(); ?>


<?php $__env->startSection('pagescript'); ?>
<script src="<?php echo e(asset('elitedesign/assets/node_modules/datatables.net/js/jquery.dataTables.min.js')); ?>"></script>
<script src="<?php echo e(asset('elitedesign/assets/node_modules/datatables.net-bs4/js/dataTables.responsive.min.js')); ?>"></script>        
<script src="<?php echo e(asset('js/dibcustom/dib-quote-request.js')); ?>" type="text/javascript"></script>
<script src="<?php echo e(asset('js/dibcustom/dib-policy-add.js')); ?>" type="text/javascript"></script>

<script>
var   seletab = '<?php echo e($overviewTab); ?>';

$(function () {

var dibpolicyAddObj = new DibPolicyAdd();
    dibpolicyAddObj.initialSetting();
});


$('#policytab').find('.active').removeClass('active');
$('#tab_'+seletab).trigger('click');
$('#tab_'+seletab+' a[href="#content_'+seletab+'"]').addClass('active');
$('#tab_'+seletab+' a[href="#tab_'+seletab+'"]').attr('aria-selected',true);

$('a[data-toggle="tab"]').on("shown.bs.tab", function (e) {
$($.fn.dataTable.tables(true)).DataTable()
.columns.adjust()
.responsive.recalc();
});


</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.elite_fullwidth',['sidemenustatus' => array(),'countDetails'=>array(),'notificationCount'=>array() ] , \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Xampp_new\htdocs\inslyportal\resources\views/Policy/policyoverview.blade.php ENDPATH**/ ?>