
    <div id="panel-claim_payment_summary" class="panel panel-default open">
        <div class="panel-heading">
            <h3 class="panel-title">Summary</h3>
        </div>
        <div id="claim_payment_summary" class="panel-collapse panel-body">
            <div class="row">
                <div class="" style="float: left; width: 30%; margin-left: 50px;">
                    <table class="info-table" style="">
                        <tbody>
                            <tr><td>Total Gross Paid:</td><td> {{ number_format($grossPaid, 2, '.', ',') }} SAR</td></tr>
                            <tr><td>Outstanding reserve:</td><td>{{ number_format($reserveAmount, 2, '.', ',') }} SAR</td></tr>
                            <tr><td>Recoveries:</td><td>0.00 SAR</td></tr>
                            <tr><td>Deductible/Contribution:</td><td>{{ number_format($deductionContribution, 2, '.', ',') }} SAR</td></tr>
                            <tr><td>Total Gross Incurred:</td><td>{{ number_format($grossPaid+$reserveAmount, 2, '.', ',') }} SAR</td></tr>
                        </tbody>
                    </table>
                </div>
                <div class="" style="float: left; width: 35%;">
                    <table class="info-table dpib_change_width">

                        <tbody>
                            <tr>
                                <td style="width: 20%; font-weight: bold;">Paid Split</td>
                                <td style="width: 20% !important; font-weight: bold;"> Gross Paid </td>
                                <td style="width: 40%; font-weight: bold;">Insurer Net Paid</td>
                            </tr>
                            @if(count($paidSplit)>0)
                    @foreach($paidSplit as $key =>$paysplit)
                    <tr>
                        <td>{{$paysplit->paymentType}}</td>
                        <td>{{ number_format($paysplit->groupSum, 2, '.', ',') }}</td>
                        <td>{{ number_format($insuredPaidSplit[$key]->groupSum, 2, '.', ',') }} </td>
                        
                    </tr>
                    @endforeach
                    @endif
                            
                            
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div id="panel-claim_payment_payments" class="panel panel-default open">
        <div class="panel-heading">
            <ul class="panel-actions list-inline pull-right">
                <li class='dpib_claim_payment_add' type='0'><span class="fas fa-plus text-blue" data-toggle="tooltip" title="" data-original-title="Add Payments and recoveries"></span></li>
            </ul>
            <h3 class="panel-title">Payments and recoveries</h3>
        </div>
        <div id="claim_payment_payments" class="panel-collapse panel-body">
            <table class="display nowrap table table-hover table-striped table-bordered">
                <thead>
                    <tr>
                        <th style="width: 1%">No</th>
                        <th style="width: 9%">Payment sum</th>
                        <th style="width: 10%">Recovery</th>
                        <th style="width: 10%;">Added</th>
                        <th style="width: 10%;">Type</th>
                        <th style="width: 10%;">Payer</th>
                        <th style="width: 10%;">Payee</th>
                        <th style="width: 33%;">Description</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($paymentDetails)>0)
                    @foreach($paymentDetails as $key =>$payments)
                    <tr>
                        <td>{{$key+1}}</td>
                        <td>{{ number_format($payments->payment_sum, 2, '.', ',') }}</td>
                        <td> </td>
                        <td>{{date('d.m.Y',strtotime($payments->payment_date))}}</td>
                        <td>{{$payments->paymentType}}</td>
                        <td> {{ ($payments->payer_type==0) ?  $payments->payerInsurer : $payments->payer_name}} <br>{{$payments->payerType}}</td>
                        <td>{{($payments->payee_type==0) ?  $payments->payeeInsurer : $payments->payee_name}}<br> {{$payments->payeeType}}</td>
                        <td>{{$payments->payment_description}}</td>
                        <td class="iconactions">
                            <span  style="margin-right: 8px" class="dpib_claim_payments_edit" current-data="{{@json_encode($payments)}}">
                                <span class="fas fa-edit text-blue" data-toggle="tooltip" title="" data-original-title="Edit reserve history"></span>
                                    
                            </span>
                          
                            <span class="dpib_claim_payments_delete" data-url="{{ route('claimpaymentdelete',[$payments->claim_id,$payments->id])}}" payment-id="{{$payments->id}}">
                                <span class="fas fa-archive text-blue" data-toggle="tooltip" title="" data-original-title="Delete reserve history"></span>
                                    
                            </span>
                           
                        </td>
                    </tr>
                    @endforeach
                    @else
                      <tr class="table-striped-row-light"><td colspan="9">No Payments and recoveries.</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    <div id="panel-claim_payment_payments" class="panel panel-default open">
        <div class="panel-heading"><ul class="panel-actions list-inline pull-right"><li class='dpib_claim_payment_add' type='1'><span class="fas fa-plus text-blue" data-toggle="tooltip" title="" data-original-title="Add Deductibles and contributions"></span></li></ul><h3 class="panel-title">Deductibles and contributions</h3></div>
        <div id="claim_payment_payments" class="panel-collapse panel-body">
            <table class="display nowrap table table-hover table-striped table-bordered">
                <thead>
                    <tr><th style="width: 1%">No</th><th style="width: 9%">Payment sum</th><th style="width: 10%">Recovery</th><th style="width: 10%;">Added</th><th style="width: 10%;">Type</th><th style="width: 10%;">Payer</th><th style="width: 10%;">Payee</th><th style="width: 33%;">Description</th><th></th></tr>
                </thead>
                <tbody>
                    
                   @if(count($deductionDetails)>0)
                    @foreach($deductionDetails as $key =>$deduction)
                    <tr>
                        <td>{{$key+1}}</td>
                        <td>{{ number_format($deduction->payment_sum, 2, '.', ',') }}</td>
                        <td> </td>
                        <td>{{date('d.m.Y',strtotime($deduction->payment_date))}}</td>
                        <td>{{$deduction->paymentType}}</td>
                        <td> {{ ($deduction->payer_type==0) ?  $deduction->payerInsurer : $deduction->payer_name}} <br>{{$deduction->payerType}}</td>
                        <td>{{($deduction->payee_type==0) ?  $deduction->payeeInsurer : $deduction->payee_name}}<br> {{$deduction->payeeType}}</td>
                        <td>{{$deduction->payment_description}}</td>
                        <td class="iconactions">
                            <span  style="margin-right: 8px" class="dpib_claim_payments_edit" current-data="{{@json_encode($deduction)}}">
                                <span class="fas fa-edit text-blue" data-toggle="tooltip" title="" data-original-title="Edit reserve history"></span>
                                    
                            </span>
                          
                            <span class="dpib_claim_payments_delete" data-url="{{ route('claimpaymentdelete',[$deduction->claim_id,$deduction->id])}}" payment-id="{{$deduction->id}}">
                                <span class="fas fa-archive" data-toggle="tooltip" title="" data-original-title="Delete reserve history"></span>
                                    
                            </span>
                           
                        </td>
      
                    </tr>
                    @endforeach
                    @else
                      <tr class="table-striped-row-light"><td colspan="9">No Deductibles and contributions.</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    <div id="panel-claim_payment_reserve" class="panel panel-default open">
        <div class="panel-heading">
            <ul class="panel-actions list-inline pull-right">
                <li class='dpib_claim_reserve_history_add'><span class="fas fa-plus text-blue" data-toggle="tooltip" title="" data-original-title="Add reserve history"></span></li>
            </ul>
            <h3 class="panel-title">Reserve history</h3>
        </div>
        <div id="claim_payment_reserve" class="panel-collapse panel-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th style="width: 1%">No</th>
                        <th style="width: 9%">Reserve sum</th>
                        <th style="width: 10%">Transaction</th>
                        <th style="width: 10%;">Added</th>
                        <th style="width: 60%;">Description</th>
                        <th> </th>                        
                        
                    </tr>
                </thead>
                <tbody>
                    
                    @if(count($reserveHistories)>0)
                    @foreach($reserveHistories as $key =>$history)
                    <tr>
                        <td>{{$key+1}}</td>
                        <td>{{ number_format($history->balance_reserve_sum, 2, '.', ',') }}</td>
                       <td>{{ number_format($history->amount, 2, '.', ',') }}</td>
                        <td>{{date('d.m.Y',strtotime($history->reserve_date))}}</td>
                        
                        <td> {{ $history->description}}</td>                       
                        
                        <td class="iconactions">
                            <span  style="margin-right: 8px" class="dpib_reserve_hisory_edit" current-data="{{@json_encode($history)}}">
                                <span class="fas fa-edit text-blue" data-toggle="tooltip" title="" data-original-title="Edit reserve history" ></span>
                                    
                            </span>
                            @if($loop->last)
                            <span class="dpib_reserve_hisory_delete" data-url="{{ route('claimreservedelete',[$history->claim_id,$history->id])}}" history-id="{{$history->id}}">
                                <span class="fas fa-archive text-blue" data-toggle="tooltip" title="" data-original-title="Delete reserve history"></span>
                                    
                            </span>
                              @endif
                        </td>
                    </tr>
                    @endforeach
                    @else
                      <tr class="table-striped-row-light"><td colspan="6">No reserve history.</td></tr>
                    @endif
                    
                    
               
                </tbody>
            </table>
        </div>
    </div>
  
<style>
    
    table.dpib_change_width tr td {width:20% !important}
        
</style>

<script>
$(function(){
    
    $(document).off('click','.dpib_claim_payment_add');
    $(document).on('click','.dpib_claim_payment_add',function(){
        var template = _.template($("#dpib_claim_payment_add_form").html());
        var data = {'selectedType':$(this).attr('type')};
        var result = template(data);

            $("#db_claim_payment_addpopup").remove();
                $('body').append('<div id="db_claim_payment_addpopup" title="Add payment" style="display:none" >' + result + '</div>');
                var dialogElement = $("#db_claim_payment_addpopup");

                dialogElement.dialog({
                    width: 900,
                    
                    modal: true,
                    buttons: {
                        "Add": {
                            class: "btn waves-effect waves-light btn-rounded btn-success",
                            text:'Add',
                            click: function () {
                                DIB.progressDialog(LOCALE.get('dib.common.progress.loading'));
                                
                                 var isValid = true;
         var errorMessage = "";
                var i=0;
                $(".required:visible").each(function(){                
                 if($(this).val()=='' || $(this).val() == null) {
                    isValid = false; 
                    $(this).addClass('form-control-danger');
                    $(this).parent('.element').addClass('has-danger')
                    if( i==0 ) {
                     errorMessage+="<b>The following errors occurred while validating data:"+"</b><br/>";
                     i++;
                    }
                    errorMessage+="<b>"+ $(this).attr('error-message')+"</b><br/>"
                  
                 } else {
                    $(this).removeClass('error'); 
                    $(this).removeClass('form-control-danger');
                    $(this).parent('.element').removeClass('has-danger')
                 }
                });
                

            if(isValid) {

               $("form#form_claim_payment_add").submit();
            } else {
              DIB.alert(errorMessage,'Error!!!!');    
            }
                                
                               
                               

                            }
                        },
                        "cancel": {
                            class: "btn waves-effect waves-light btn-rounded btn-danger",
                            text:'Cancel',
                            click: function () {
                                dialogElement.dialog('close');
                                dialogElement.remove();
                            }
                        }
                    },
                    open:function(){
                        FORM.setDatePicker("#form_claim_payment_add input:text.datefield");
                    }
                });  
       
    });
    
    $(document).off('click', '.dpib_reserve_hisory_delete');
      $(document).on('click', '.dpib_reserve_hisory_delete', function() {
        DIB.progressDialog(LOCALE.get('dib.common.progress.loading'));
        let deleteUrl = $(this).attr('data-url');
        let historyId = $(this).attr('history-id');
        $("#db_claim_resrvehistory_delete_popup").empty();
        $('body').append('<div id="db_claim_resrvehistory_delete_popup" title="Delete reserve history" style="display:none" >Do you want to delete reserve history?</div>');
        var dialogElement = $("#db_claim_resrvehistory_delete_popup");
        dialogElement.dialog({
          width: 900,
          resizable: false,
          bgiframe: true,
          modal: true,
          buttons: {
            "Delete": {
              class: "btn waves-effect waves-light btn-rounded btn-success",
               text:'Delete',
              click: function() {
                DIB.progressDialog(LOCALE.get('dib.common.progress.loading'));

                $.ajax({
                  url: deleteUrl,
                  type: "post",
                  data: {
                    'historyId': historyId
                  },
                  headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  }
                }).done(function(data) {
                  if (data.status) {
                    window.location.href = data.redirect;
                  }


                });

              }
            },
            "cancel": {
              class: "btn waves-effect waves-light btn-rounded btn-danger",
               text:'Cancel',
              click: function() {
                dialogElement.dialog('close');
                dialogElement.remove();
              }
            }

          }

        });
        DIB.centerDialog();



      });
      
      $(document).off('click', '.dpib_claim_payments_delete');
      $(document).on('click', '.dpib_claim_payments_delete', function() {
        DIB.progressDialog(LOCALE.get('dib.common.progress.loading'));
        let deleteUrl = $(this).attr('data-url');
        let historyId = $(this).attr('history-id');
        $("#db_claim_resrvehistory_delete_popup").remove();
        $('body').append('<div id="db_claim_resrvehistory_delete_popup" title="Delete claim payment" style="display:none" >Do you want to delete payment history?</div>');
        var dialogElement = $("#db_claim_resrvehistory_delete_popup");
        dialogElement.dialog({
          width: 900,          
          modal: true,
          buttons: {
            "Delete": {
              class: "btn waves-effect waves-light btn-rounded btn-success",
               text:'Delete',
              click: function() {
                DIB.progressDialog(LOCALE.get('dib.common.progress.loading'));

                $.ajax({
                  url: deleteUrl,
                  type: "post",
                  data: {
                    'historyId': historyId
                  },
                  headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  }
                }).done(function(data) {
                  if (data.status) {
                    window.location.href = data.redirect;
                  }


                });

              }
            },
            "cancel": {
              class: "btn waves-effect waves-light btn-rounded btn-danger",
               text:'cancel',
              click: function() {
                dialogElement.dialog('close');
                dialogElement.remove();
              }
            }

          }

        });
        DIB.centerDialog();



      });
       $(document).off('click', '.dpib_claim_payments_edit');
      $(document).on('click', '.dpib_claim_payments_edit', function() {
      var currentData = $(this).attr('current-data');
        DIB.progressDialog(LOCALE.get('dib.common.progress.loading'));
        var template = _.template($("#request_payment_edit_template").html());
        var data = $.parseJSON(currentData);
         var result = template(data);
        $("#db_claim_resrvehistory_delete_popup").empty();

        $('body').append('<div id="db_claim_resrvehistory_delete_popup" title="Edit payment details" style="display:none" >'+result+'</div>');
        var dialogElement = $("#db_claim_resrvehistory_delete_popup");
        dialogElement.dialog({
          width: 900,         
          modal: true,
          buttons: {
            "Update": {
              class: "btn waves-effect waves-light btn-rounded btn-success",
              text:'Update',
              click: function() {
                DIB.progressDialog(LOCALE.get('dib.common.progress.loading'));

                $("#form_claim_payment_add").submit();

              }
            },
            "cancel": {
              class: "btn waves-effect waves-light btn-rounded btn-danger",
               text:'cancel',
              click: function() {
                dialogElement.dialog('close');
                dialogElement.remove();
              }
            }

          },
          open:function() {
            FORM.setDatePicker("#form_claim_payment_add input:text.datefield");
            $("#claim_payment_type").val(data.payment_type);
            $("#claim_payment_payer_type").val(data.payer_type);
            $("#claim_payment_payee_type").val(data.payee_type);
             
            $("#claim_payment_payer_type").trigger('change');
            $("#claim_payment_payee_type").trigger('change');
             
            $("#claim_payment_payer_insurer").val(data.payer_insurer_id);
            $("#claim_payment_payee_insurer").val(data.payee_insurer_id);
            
          }

        });
        DIB.centerDialog();



      });
      
      
      $(document).off('click','.dpib_claim_reserve_history_add');
    $(document).on('click','.dpib_claim_reserve_history_add',function(){
        var template = _.template($("#dpib_claim_reserve_add_form").html());
        var data = {'selectedType':$(this).attr('type')};
        var result = template(data);

            $("#db_claim_payment_addpopup").remove();
                $('body').append('<div id="db_claim_payment_addpopup" title="Add reserve history" style="display:none" >' + result + '</div>');
                var dialogElement = $("#db_claim_payment_addpopup");

                dialogElement.dialog({
                    width: 900,                    
                    modal: true,
                    buttons: {
                        "Add": {
                            class: "btn waves-effect waves-light btn-rounded btn-success",
                            text:'Add',
                            click: function () {
                                DIB.progressDialog(LOCALE.get('dib.common.progress.loading'));

                               $("form#form_claim_payment_add").submit();
                               

                            }
                        },
                        "cancel": {
                            class: "btn waves-effect waves-light btn-rounded btn-danger",
               text:'Cancel',
                            click: function () {
                                dialogElement.dialog('close');
                                dialogElement.remove();
                            }
                        }
                    },
                    open:function(){
                         FORM.setDatePicker("#form_claim_reserve_add input:text.datefield");
                    }
                });  
       
    });
    
    // update reserve sum  history
    
     $(document).off('click', '.dpib_reserve_hisory_edit');
      $(document).on('click', '.dpib_reserve_hisory_edit', function() {
      var currentData = $(this).attr('current-data');
        DIB.progressDialog(LOCALE.get('dib.common.progress.loading'));
        var template = _.template($("#dpib_claim_reservesum_update_form").html());
       
        var data = $.parseJSON(currentData);
         var result = template(data);  
          $("#db_claim_resrvehistory_edit_popup").remove();

        $('body').append('<div id="db_claim_resrvehistory_edit_popup" title="Edit reserve history" style="display:none" >'+result+'</div>');
        var dialogElement = $("#db_claim_resrvehistory_edit_popup");
        dialogElement.dialog({
          width: 900,          
          modal: true,
          
          buttons: {
            "Update": {
              class: "btn waves-effect waves-light btn-rounded btn-success",
               text:'Update',
              click: function() {
                DIB.progressDialog(LOCALE.get('dib.common.progress.loading'));

                $("#form_claim_payment_add").submit();

              }
            },
            "cancel": {
              class: "btn waves-effect waves-light btn-rounded btn-danger",
               text:'Cancel',
              click: function() {
                dialogElement.dialog('close');
                dialogElement.remove();
              }
            }

          },
          open:function() {
            FORM.setDatePicker("#form_claim_payment_add input:text.datefield");
            $("#claim_reserve_type").val(1);            
            
          }

        });
        DIB.centerDialog();



      });

      
      
       
    });
    

</script>
