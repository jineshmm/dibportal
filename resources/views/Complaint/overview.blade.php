@extends('layouts.elite_fullwidth',['notificationCount'=>array() ] )
@section('content')


<!--                  content here  -->
<!--TAB AREA-->
<div class="row">

    <div class="col-md-12 card">


        <ul class="nav nav-tabs customtab card-body" role="tablist">
            <li id="tab_overview" class="{{ empty($overviewTab) || $overviewTab == 'overview' ? 'active' : '' }}" onclick="TAB.select('overview', null, 1)"><a href="#content_overview">Overview</a></li>

        </ul>


    </div>

</div>
<!--TAB CONTENT AREA-->
<div class="row card">

    <div id="content_overview" class="tabcontent col-md-12 card-body">
        <div class="row">

            <div id="main-content" class="col-md-8">
                <div id="panel-customer_overview" class="panel panel-default open">
                    <div class="panel-heading">
                        <ul class="panel-actions list-inline pull-right">

                            <li class="dpib_complaint_edit" edit_url="{{ route('editcomplaint',$complaintDetails->id)}}"><span class="fas fa-edit text-blue" data-toggle="tooltip" title="" data-original-title="Edit complaint"></span></li>


                        </ul>
                        <h3 class="panel-title">Complaint Info</h3>
                    </div>
                    <div id="customer_overview" class="panel-collapse panel-body">
                        <table class="info-table">
                            <tbody>
                                <tr><td>Customer:</td><td><a href='{{route('customeroverview',$complaintDetails->client_id)}}'>{{ $complaintDetails->clientName}}</a> </td></tr>
                                <tr><td>Policy:</td><td>@if($complaintDetails->policy_number !='')  <span class='text-success' style='font-weight: bold'>{{$complaintDetails->policy_number}}<span> @else <span class='text-danger' style='font-weight: bold'>not issued</span>@endif</td></tr>
                                                <tr><td>Complaint type:</td><td>{{$complaintDetails->complaintType}}</td></tr>

                                                <tr><td>Requested date:</td><td class="phoneNumber">{{date('d.m.Y', strtotime($complaintDetails->requested_date)) }}</td></tr>
                                                <tr><td>Description:</td><td class="phoneNumber">{{ $complaintDetails->remarks}}</td></tr>
                                                <tr><td>Request status:</td><td> {{ $complaintDetails->statusString}}  </td></tr>

                                                <tr><td>Validity:</td><td>{{ $complaintDetails->complaintValidity}}</td></tr>

                                                <tr><td>Bill amount:</td><td>{{ number_format($complaintDetails->bill_amount, 2, '.', ',')}}</td></tr>

                                                <tr><td>Approve amount:</td><td>{{ number_format($complaintDetails->approve_amount, 2, '.', ',')}}</td></tr>                                   

                                                @if( isset($complaintDetails->closed_date) && !empty($complaintDetails->closed_date))
                                                <tr><td>Closed date:</td><td>{{date('d.m.Y', strtotime($complaintDetails->closed_date)) }}</td></tr>
                                                @endif

                                                <tr><td>Created date:</td><td>{{date('d.m.Y', strtotime($complaintDetails->created_at)) }}</td></tr>
                                                <tr><td>Updated date:</td><td>{{date('d.m.Y', strtotime($complaintDetails->updated_at)) }}</td></tr>
                                                <tr><td>Created by:</td><td>{{ $complaintDetails->userName }}</td></tr>



                                                </tbody>
                                                </table>
                                                </div></div>





                                                </div>
                                                </div>
                                                </div>

                                                </div>
@endsection
@section('pagescript')

                                                <script>
                                                    $(function () {

                                                        $(document).on('click', '.dpib_complaint_edit', function () {

                                                            $.ajax({
                                                                url: $(this).attr('edit_url'),
                                                                type: "GET"

                                                            }).done(function (data) {
                                                                $("#db_complaint_edit_popup").remove();
                                                                $('body').append('<div id="db_complaint_edit_popup" title="Edit Complaint" style="display:none" >' + data.content + '</div>');
                                                                var dialogElement = $("#db_complaint_edit_popup");
                                                                dialogElement.dialog({
                                                                    width: 900,                                                                   
                                                                    modal: true,
                                                                    buttons: {
                                                                        "Update": {
                                                                            class: "btn waves-effect waves-light btn-rounded btn-success",
                                                                            text:'Update',
                                                                            click: function () {
                                                                                DIB.progressDialog(LOCALE.get('dib.common.progress.loading'));
                                                                                $("form#form_complaint_create").submit();
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
                                                                    open: function (event, ui) {

                                                                        FORM.setDatePicker('.datefield');
                                                                        $(document).off('change', '#complaint_client');
                                                                        $(document).on('change', '#complaint_client', function () {
                                                                            DIB.progressDialog(LOCALE.get('dib.common.progress.loading'));
                                                                            $.ajax({
                                                                                url: "{!! route('clientpolicies') !!}",
                                                                                headers: {
                                                                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                                                },
                                                                                type: "post",
                                                                                data: {'customer_id': $(this).val(), 'selectedoption': $("#complaint_policy").attr('selected_id')}

                                                                            }).done(function (data) {
                                                                                if (data.status) {
                                                                                    $("#complaint_policy").empty().html(data.optionstring);
                                                                                }

                                                                            })

                                                                        });

                                                                        $("#complaint_client").trigger('change');

                                                                    }
                                                                });
                                                                DIB.centerDialog();
                                                            });

                                                        });



                                                    });
                                                </script>

                                                @endsection