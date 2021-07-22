@extends('layouts.elite_fullwidth',['sidemenustatus' => array(),'countDetails'=>array(),'notificationCount'=>array() ] )


@section('content')

    <div class="row col-12 dpib-custom-form">
        <div class="col-md-12">
            <div class="card">
                      <div class="card-body">
                    <div class="insly-form">

                      
 {{ Form::open(array('route' => array('saveschedule', $policyObj->customer_id,$policyObj->id),'name' => 'scheduleform','id'=>'scheduleform','class'=>'dpib-scheduleform','method'=>'post') ) }}
     @csrf

                        <div class="panel panel-default panel-dark">
                            <div class="panel-heading" id="fieldgroup_title_addaddress">
                                <!-- <div class="blocktitle"> -->
                                <button type="button" class="btn waves-effect waves-light btn-rounded btn-warning d-none d-lg-block m-l-15" style="float:right" id="addschedule"><i class="fa fa-plus-circle"></i> Add more</button>
                                <h3 class="panel-title">Policy schedule setting</h3>
                                
                                <!-- </div> -->
                            </div>
                            <div class="panel-body" id="fieldgroup_addaddress">
                                <table class="insly-form" id="policy_schedule_table">
                                    <tbody>
                                        @foreach($plannedschedules as $key=>$schedule)
                                        <tr id="field_addaddress_address_{{$key}}" class="field">
                                            <td class=""><div class="label full-height" style="height: 125px;"><span class="title">Schedule </span></div></td>
                                            <td><div class="element">
                                                    <div style="overflow: hidden">
                                                        <div style="float: left; width: 20%; padding-right: 10px"><label class="label-without-margins label-simple">Start date:</label><br>
                                                            <input type="date" id="schedule_start_{{$key}}" name="schedule_start[]" value="{{ date('Y-m-d',strtotime($schedule['date'])) }}" autocomplete="off" style="width: 100%" maxlength="10" class='datefield form-control'>
                                                        </div>
                                                        <div style="float: left; width: 43%; padding-right: 10px"><label class="label-without-margins label-simple">End date</label><br>
                                                            <input type="date" id="schedule_end_{{$key}}" name="schedule_end[]" value="{{ isset($schedule['enddate']) ? date('Y-m-d',strtotime($schedule['enddate']))  : '' }}" autocomplete="off" style="width: 100%" class='form-control'>
                                                        </div>
                                                        <div style="float: left; width: 33%; padding-right: 10px"><label class="label-without-margins label-simple">Description</label><br>
                                                            <textarea id="schedule_note_{{$key}}" name="schedule_note[]" wrap="soft" rows="4" class='form-control'>{{ $schedule['note'] }}</textarea>
                                                            
                                                        </div>
                                                    </div>
                                                    <i class='fas fa-times-circle text-danger delete_schedule' style='float:right' data-toggle="tooltip" title="" data-original-title="Remove" remove-id="field_addaddress_address_{{$key}}"></i>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
    
                                </table>
                   
                                
                            </div>
                        </div>

                 <div class="buttonbar pull-right">
            <div class="submit"><button type="submit" id="submit_save" name="submit_save" class="submit_policy btn waves-effect waves-light btn-rounded btn-success" >Create</button><button type="button" id="submit_cancel" name="submit_cancel" onclick="FORM.cancel()" class='btn waves-effect waves-light btn-rounded btn-danger'>Cancel</button></div>
        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

<script id='policy_scedule_template' type='text/template'>
    


       <tr id="field_addaddress_address_<%- iCount %>" class="field extraSchedule">
                                            <td class=""><div class="label full-height" style="height: 125px;"><span class="title">Schedule </span></div></td>
                                            <td><div class="element">
                                                    <div style="overflow: hidden">
                                                        <div style="float: left; width: 20%; padding-right: 10px"><label class="label-without-margins label-simple">Start date:</label><br>
                                                            <input type="date" id="schedule_start_<%- iCount %>" name="schedule_start[]" value="" autocomplete="off" style="width: 100%" maxlength="10" class='datefield form-control'>
                                                        </div>
                                                        <div style="float: left; width: 43%; padding-right: 10px"><label class="label-without-margins label-simple">End date</label><br>
                                                            <input type="date" id="schedule_end_<%- iCount %>" name="schedule_end[]" value="" autocomplete="off" style="width: 100%" class='form-control'>
                                                        </div>
                                                        <div style="float: left; width: 35%; padding-right: 10px"><label class="label-without-margins label-simple">Description</label><br>
                                                            <textarea id="schedule_note_<%- iCount %>" name="schedule_note[]" wrap="soft" rows="4" class='form-control'></textarea>
                                                            
                                                        </div>
                                                    </div>
                                                    <i class='fas fa-times-circle text-danger delete_schedule' style='float:right' data-toggle="tooltip" title="" data-original-title="Remove" remove-id="field_addaddress_address_<%- iCount %>"></i>
                                                </div>
                                            </td>
                                        </tr>    
    
    
</script>


@endsection
@section('customscript')
<script src="{{ asset('elitedesign/assets/node_modules/bootstrap-select/bootstrap-select.min.js') }}"></script>

@endsection
@section('pagescript')


<script>
   
   
    $(function () {

  
$(document).off('click', '#addschedule');
        $(document).on('click', '#addschedule', function () {
          var template = _.template($("#policy_scedule_template").html());
          var rand = _.random(0, 100);
          var result = template({iCount:rand});

        $('#policy_schedule_table tr:last').after(result);
         $('[data-toggle="tooltip"]').tooltip();
        $('#field_addaddress_address_'+rand).focus();
    
        $(window).scrollTop($("#policy_schedule_table").height());
        
        });
        
        
        $(document).off('click', '.delete_schedule');
        $(document).on('click', '.delete_schedule', function () {
            removeId = $(this).attr('remove-id');
                $('#'+removeId).remove();
       
             
        });
        
        
        
        
    
      
    })
    

</script>
@endsection