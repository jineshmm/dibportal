@extends('layouts.elite',['notificationCount'=>$notificationCount ] )

@section('content')



<div class="row">
    <div class="col-md-12">
        
          <div class="card">
                            <div class="">
                                <div class="row">
                                                                        
                                    <div class="col-lg-3">
                                        <div class="card-body">
                                            <h4 class="card-title m-t-10">Drag & Drop Event</h4>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div id="calendar-events" class="">
                                                        
                                                        @foreach($eventsDetails as $events)
                                                       
                                                        @if ($events->color_selection =='bg-success') 
                                                                @php
                                                               $color='text-success';
                                                               @endphp
                                                        @elseif($events->color_selection =='bg-danger') 
                                                                @php
                                                               $color='text-danger';
                                                               @endphp
                                                        @elseif($events->color_selection =='bg-purple') 
                                                                @php
                                                               $color='text-purple';
                                                               @endphp
                                                        @elseif($events->color_selection =='bg-info') 
                                                                @php
                                                               $color='text-info';
                                                               @endphp
                                                        @elseif($events->color_selection =='bg-primary')
                                                                @php
                                                                    $color='text-primary';
                                                               @endphp
                                                        @elseif($events->color_selection =='bg-warning') 
                                                                @php
                                                                 $color='text-warning';
                                                               @endphp
                                                         @else
                                                                @php
                                                                $color='text-inverse';
                                                               @endphp                                                              
                                                         @endif
                                                        <div class="calendar-events" data-class="{{$events->color_selection}}" data-event-id='{{$events->id}}'>
                                                            <i class="fa fa-circle {{$color}}"></i> 
                                                            
                                                            
                                                            {{$events->title}}
                                                        
                                                        </div>
                                               
                                                    @endforeach
                                                    </div>
                                                    <!-- checkbox -->
                                                    <div class="custom-control custom-checkbox m-l-10 m-t-10">
                                                        <input type="checkbox" class="custom-control-input" id="drop-remove">
                                                        <label class="custom-control-label" for="drop-remove">Remove after drop</label>
                                                    </div>
                                                    <a href="javascript:void(0)"  class="btn m-t-10 btn-info btn-block waves-effect waves-light" id='db_appointment_add'>
                                                        <i class="ti-plus"></i> Add New Event
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-9">
                                        <div class="card-body b-l calender-sidebar">
                                            <div id="calendar"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
    </div>
</div>

<div class="modal none-border" id="my-event">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title"><strong>Add Event</strong></h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            </div>
                            <div class="modal-body"></div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-success save-event waves-effect waves-light">Create event</button>
                                <button type="button" class="btn btn-danger delete-event waves-effect waves-light" data-dismiss="modal">Delete</button>
                            </div>
                        </div>
                    </div>
                </div>

 <!-- Modal Add Category -->

     

<script id='appointment_add_template' type='text/template'>
        
        {{ Form::open(array('route' => array('addappointment'), 'name' => 'form_appointment_add','id'=>'form_appointment_add','files'=>'true' )) }}
				     <div class="row dialogform">
                                     <div class="col-lg-12">
                                <div class="card">

                                    <div class="card-body card-block">
                                        <div class="form-group">
                                            <label for="company" class=" form-control-label">Appointment date</label>
                                           
                                            <div style="float:left;width:225px">  <input type="text"  name="appointment_date" value="{{date('d.m.Y')}}"  id="testDatepicker" class="newdatefield" style="float: left !important;"></div>
                                               <div> <select style="width: 60px;padding-top: 2px;margin-left: 75px;" id="appointment_hour" name="appointment_hour">
                                        @for ($i = 0; $i < 24; $i++)
                                                          <option value="{{str_pad($i, 2,"0", STR_PAD_LEFT)}}">{{str_pad($i, 2,"0", STR_PAD_LEFT)}}</option>
                                        @endfor
                                        
                                    </select>
                                    <span style="font-weight: bold; margin-left: 5px;">:</span>
                                    <select style="width: 60px;padding-top: 2px;margin-left: 5px;" id="appointment_minute" name="appointment_minute">
                                        @for ($i = 0; $i < 60; $i++)
                                                          <option value="{{str_pad($i, 2,"0", STR_PAD_LEFT)}}">{{str_pad($i, 2,"0", STR_PAD_LEFT)}}</option>
                                        @endfor
                                    </select>
                                    </div>
                                         
                                            
                                        </div>
                                   
                                        <div class="form-group">
                                            <label for="vat" class=" form-control-label">Description</label>
                                            <textarea  id="reminder_description" placeholder="Description" class="form-control" name="reminder_description"></textarea>
                                                   
                                        </div>
                                        <div class="row form-group">
                                                <div class="col col-md-3">
                                                    <label class=" form-control-label">All day</label>
                                                </div>
                                                <div class="col col-md-9">
                                                    <div class="form-check-inline form-check">
                                                        <label for="inline-checkbox1" class="form-check-label ">
                                                            <input type="checkbox" id="inline-checkbox1" name="all_day_flag" value="1" class="form-check-input">Yes
                                                        </label>
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        
                                        
                                        
                                        
                                        
                                    </div>
                                </div>
                            </div>
                            </div>
                            {{Form::close()}}
			
</script>

<script id='events_add_template' type='text/template'>

        {{ Form::open(array('route' => array('createevents'), 'name' => 'form_events_add','id'=>'form_events_add','files'=>'true' )) }}

                             
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="control-label">Title</label>
                                            <input class="form-control form-white" placeholder="Enter title" type="text" name="category-name" />
                                        </div>
                                        <div class="col-md-6">
                                            <label class="control-label">Choose Category Color</label>
                                            <select class="form-control form-white" data-placeholder="Choose a color..." name="category-color">
                                                <option value="bg-success">Success</option>
                                                <option value="bg-danger">Danger</option>
                                                <option value='bg-purple'>Purple</option>
                                                <option value="bg-info">Info</option>
                                                <option value="bg-primary">Primary</option>
                                                <option value="bg-warning">Warning</option>
                                                <option value="bg-inverse">Inverse</option>
                                            </select>
                                        </div>
                                    </div>
                            
                            {{Form::close()}}
			
</script>

<script id='multiple_events_add_template' type='text/template'>

        

                             
                                     <div class="modal fade none-border" id="add-multiple-new-event">
                    <div class="modal-dialog">
                    {{ Form::open(array('route' => array('createappointments'), 'name' => 'form_multipleday_events_add','id'=>'form_multipleday_events_add','files'=>'true' )) }}
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title"><strong>Add</strong> an event</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            </div>
                            <div class="modal-body">
                                
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="control-label">Title</label>
                                            <input class="form-control form-white" placeholder="Enter name" type="text" value='' name="category-name" />
                                        </div>
                                        <div class="col-md-6">
                                            <label class="control-label">Choose Category Color</label>
                                            <select class="form-control form-white" data-placeholder="Choose a color..." name="category-color">
                                                <option value="bg-success">Success</option>
                                                <option value="bg-danger">Danger</option>
                                                <option value="bg-info">Info</option>
                                                <option value="bg-primary">Primary</option>
                                                <option value="bg-warning">Warning</option>
                                                <option value="bg-inverse">Inverse</option>
                                            </select>
                                        </div>
                                    </div>
                              
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger waves-effect save-event" data-dismiss="modal">Save</button>
                                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                     {{Form::close()}}
                </div>
                            
                        
			
</script>


<script id='dpib-new-appointments' type='text/template'>

        
                                     <div class="modal fade none-border" id="add-appointment-new-event">
                    <div class="modal-dialog">
                    {{ Form::open(array('route' => array('addappointment'), 'name' => 'form_add_appointments_add','id'=>'form_add_appointments_add','files'=>'true' )) }}
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title"><strong>Edit</strong> an event</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            </div>
                            <div class="modal-body">
                                
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="control-label">Title</label>
                                            <input class="form-control form-white" placeholder="Enter name" value='<%= title  %>'  type="text" name="category-name" />
                                                <input  value='<%= id  %>'  type="hidden" name="eventId" />
                                                     <input  value='<%= moment(start).format("DD-MM-YYYY HH:MM")  %>'  type="hidden" name="startDate" />
                                                          <input  value='<%= (end !==null) ? moment(end).format("DD-MM-YYYY HH:MM"):null  %>'  type="hidden" name="endDate" />
                                                                <input  value='<%= (appointmentId !==null) ? appointmentId:null  %>'  type="hidden" name="appointmentId" />
                                                              
                                        </div>
                                        
                                    </div>
                              
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger waves-effect waves-light save-appointment" data-dismiss="modal">Save</button>
                                <button type="button" class="btn btn-primary waves-effect waves-light delete-event" data-dismiss="modal">Delete</button>                                
                                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                     {{Form::close()}}
                </div>
			
</script>


@endsection
 @section('customcss')

 <!-- FullCalendar -->
    <link href='{{ asset('elitedesign/assets/node_modules/calendar/dist/fullcalendar.css') }}' rel="stylesheet"/>
   
<style type="text/css">
    /* force class color to override the bootstrap base rule
       NOTE: adding 'url: #' to calendar makes this unneeded
     */
    .fc-event, .fc-event:hover {
          color: #fff !important;
          text-decoration: none;
    }
    </style>

@endsection

  @section('customscript')

 <script src="{{ asset('elitedesign/assets/node_modules/moment/moment.js') }}"></script>
 <script src='{{ asset('elitedesign/assets/node_modules/calendar/dist/fullcalendar.min.js') }}'></script>
 <script src="{{ asset('js/dibcustom/dib_appointment.js') }}"></script>


@endsection
@section('pagescript')

<script type="text/javascript">
    var eventList = @json($appointmentDetail);
    var events = getCalenderEvents(eventList);
    console.log(events);
    var option = {"defaultEvents":events,'deleteUrl':"{!! route('deleteappointments') !!}"};
     var appointmentObj;
$(function () {
    appointmentObj = new Appointment(option);
    appointmentObj.initialSetting('calendar');


    
     $(document).off('click','#db_appointment_add');
    $(document).on('click','#db_appointment_add',function(){
        var template = _.template($("#events_add_template").html());
        var data = {'docType':0};
         var dialogElement =$("#db_appointment_add_popup");
        var result = template(data);
        

            $("#db_appointment_add_popup").remove();
                $('body').append('<div id="db_appointment_add_popup" title="Add Events"  >' + result + '</div>');
              
                $("#db_appointment_add_popup").dialog({
                                                            minWidth: 600,                                                            
                                                            modal:true,
                                                            buttons: {
                                                                    "Update": {
                                                                                class: "btn btn-primary waves-effect waves-light",
                                                                                text: "Save",                            
                                                                                click: function () {                               
                                                                                   $("form#form_events_add").submit();                             
                                                                                }
                                                                               },
                                                                        "cancel": {
                                                                            class: "btn btn-danger waves-effect waves-light",
                                                                                    text: "Cancel",
                                                                           click:function(){  $(this).dialog("close"); }

                                                                        }
                                                            },
                                                            open:function() {
                                                            $('.modal-backdrop').remove();                                                            

                                                          
                                                          
                                                            }
});
                 
       
    });
    
    
    
});


function getCalenderEvents(eventList) {
   var eventObj =[];
 _.each(eventList,function(values,index){
     eventObj.push({
      title: values.description,
      start: moment(values.appointment_date).format('DD-MM-YYYY'),
      className: values.color_selection,
      appointId:values.id,
      end:(values.appointment_end_date !==null) ? moment(values.appointment_end_date).format():null
      
    });  
       
       
   });
 
  return eventObj;
}
</script>


@endsection