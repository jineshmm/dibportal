<div class="dialogform" >
    <div  class="offset-3">   
        <div id="customer_overview" class="panel-collapse panel-body">
            <table class="info-table">
                <tbody>
                    <tr><td>Request Id:</td><td>{{ $requestDetail->request_id  }}</td></tr>
                    <tr><td>Customer name:</td><td>{{$requestDetail->name}}</td></tr>
                    <tr><td>Request by:</td><td>{{$requestDetail->userName}}</td></tr>
                                     
                    <tr><td>Status:</td><td>{{$requestDetail->statusString}}</td></tr>
                    <tr><td>Description:</td><td>{{$requestDetail->description}}</td></tr>
                    @if($requestDetail->request_status ==2)
                    <tr><td>Reject Reason:</td><td>{{$requestDetail->reject_reason}}</td></tr>
                    @endif
                    <tr><td>Created at:</td><td>{{ date("d.m.Y h:i",strtotime($requestDetail->created_at))}}</td></tr>
                    <tr><td>Updated at:</td><td>{{ date("d.m.Y h:i",strtotime($requestDetail->updated_at))}}</td></tr>                        
                </tbody>
            </table>
        </div>
        @if(count($logDetails) >0)
        <div id="panel-customer_contact" class="panel panel-default open">
            <div class="panel-heading">

                <h3 class="panel-title">Log details</h3></div>
            <div id="customer_contact" class="panel-collapse panel-body">

                <table class="table table-bordered table-striped table-hovered">
                    <thead>
                        <tr> <th>Edited by</th><th>Updated at</th><th> title</th></tr> 

                    </thead>
                    <tbody>


                        @foreach($logDetails as $logDetail) 
                        <tr>
                            <td><small>{{ $logDetail->userName  }}</small></td>
                            <td><small>{{ $logDetail->edited_at  }}</small></td>
                            <td> {{ $logDetail->title  }}  </td>

                        </tr>
                        @endforeach


                    </tbody></table>

            </div>
        </div>
        @endif      

    </div>       




</div>


