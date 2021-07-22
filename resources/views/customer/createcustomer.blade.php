@extends('layouts.elite_fullwidth',['sidemenustatus' => array(),'countDetails'=>array(),'notificationCount'=>array() ] )
@section('headtitle')
 {{$headTitle}}
@endsection

@section('content')

    <div class="row col-12 dpib-custom-form">
        <div class="col-md-12">
            <div class="card">
                      <div class="card-body">
                    <div class="insly-form">

                        @if (isset($customers))

                        {{ Form::open(array('route' => array('updatecustomerdata', $customers->customId),'name' => 'customerForm','id'=>'customerForm','class'=>'dpib-custom-form') ) }}
                        @else
                        {{ Form::open(array('route' => 'savecustomer', 'name' => 'customerForm','id'=>'customerForm','class'=>'dpib-custom-form' ) ) }}
                        @endif

                        @csrf
                

                        <div class="panel panel-default panel-dark">
                            <div class="panel-heading" id="fieldgroup_title_customer">
                                <!-- <div class="blocktitle"> -->
                                <h3 class="panel-title">Customer</h3>							
                                <!-- </div> -->
                            </div>
                            <div class="panel-body" id="fieldgroup_customer">
                                <table class="insly-form">
                                    <tbody>
                                        <tr id="field_customer_type" class="field"><td class=""><div class="label"><span class="text-danger icon-asterix"></span><span class="title">Customer type</span></div></td>
                                            <td>
                                                 <div class="form-group" >
                                                   
                                                    <div class="custom-control custom-radio">
                                                        
                                                         @php
                                                        $checked = ''
                                                        @endphp

                                                        @if (isset($customers->type) && $customers->type ==1)
                                                        @php
                                                        $checked = 'checked=checked';
                                                        @endphp

                                                        @endif
                                                        <input type="radio" id="customer_type_11" name="customer_type" value="1" {{$checked}} class="get-outta-here dib_customer_type custom-control-input" data-default-value="0" >
                                                        <label class="custom-control-label" for="customer_type_11">Company</label>
                                                    </div>
                                                    <div class="custom-control custom-radio">
                                                         @php
                                                        $checked = ''
                                                        @endphp

                                                        @if (isset($customers->type) && $customers->type ==0)
                                                        @php
                                                        $checked = 'checked=checked';
                                                        @endphp 
                                                        @endif
                                                        <input type="radio" id="customer_type_21" name="customer_type" value="0" {{$checked}} class="get-outta-here dib_customer_type custom-control-input" data-default-value="1" >
                                                        <label class="custom-control-label" for="customer_type_21">Individual</label>
                                                    </div>
                                                </div>
                                             </td>
                                        </tr>
                                        <tr id="field_customer_name" class="field"><td class=""><div class="label"><span class="text-danger icon-asterix"></span>Name</div></td>
                                            <td><div class="element"><input type="text" id="customer_name" name="customer_name" value="{{ isset($customers->customerName) ? $customers->customerName : '' }}"   autocomplete="off" maxlength="255" class="required form-control" required="required" error-message="Name field is mandatory"></div></td>
                                        </tr>
                                        <tr id="field_customer_idcode" class="field">
                                            <td class=""><div class="label"><span class="text-danger icon-asterix"></span><span class="title">ID code / reg no</span></div></td>
                                            <td><div class="element"><input type="text" id="customer_idcode" name="customer_idcode" value="{{ isset($customers->customer_code) ? $customers->customer_code : '' }}" autocomplete="off" maxlength="64" class="required form-control" required="required" error-message="Id code/Reg No field is mandatory"></div></td>
                                        </tr> 
                                        <tr id="field_customer_gender" class="field customertype customertype2" style="display: none;">
                                            <td class="">
                                                <div class="label">
                                                    <span class="text-danger icon-asterix"></span>
                                                    <span class="title">Gender</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="element">
                                                    @php
                                                    $selected = '';
                                                    @endphp

                                                    @if (isset($customers->gender) )
                                                    @php
                                                    $selected = $customers->gender ;
                                                    @endphp                                                            
                                                    @endif

                                                    {{ Form::select('customer_gender', array('' => '--- not entered ---','male' => 'male','female' => 'female'), $selected,array('id' =>'customer_gender','required'=>'required','class'=>'required form-control custom-select','error-message' =>"Gender field is mandatory" ))}}




                                                </div>
                                            </td>
                                        </tr>
                                        <tr id="field_customer_customercode" class="field ">
                                            <td class="">
                                                <div class="label ">
                                                    <span class="text-danger "></span>
                                                    <span class="title">Customer code</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="element">                                                       
                                                    <input type="text" id="customer_customercode" name="customer_customercode" value="{{ isset($customers->id_code) ? $customers->id_code : '' }}" autocomplete="off" maxlength="255" class='form-control'>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>


                        <div class="panel panel-default panel-dark">
                            <div class="panel-heading" id="fieldgroup_title_contactinfo">
                                <!-- <div class="blocktitle"> -->
                                <h3 class="panel-title">Contact info</h3>							
                                <!-- </div> -->
                            </div>
                            <div class="panel-body" id="fieldgroup_contactinfo">
                                <table class="insly-form">
                                    <tbody>
                                        <tr id="field_customer_email" class="field"><td class=""><div class="label"><span class="text-danger icon-asterix"></span><span class="title">E-mail address</span></div></td><td><div class="element">
                                                    <input type="text" id="customer_email" name="customer_email" value="{{ isset($customers->customerEmail) ? $customers->customerEmail : '' }}" autocomplete="off" maxlength="255" class="required form-control @error('customer_email') error @enderror" required error-message="Customer mail is mandatory"></div></td></tr><tr id="field_customer_phone" class="field"><td class=""><div class="label"><span class="text-danger icon-asterix"></span><span class="title">Phone</span></div></td><td><div class="element">
                                                    <input type="tel" id="customer_phone" name="customer_phone" value="{{ isset($customers->customerPhone) ? $customers->customerPhone : '' }}" autocomplete="off" maxlength="255" required class="required form-control" error-message="Customer phone is mandatory"></div></td></tr><tr id="field_customer_mobile" class="field"><td class=""><div class="label"><span class="text-danger icon-asterix"></span><span class="title">Mobile phone</span></div></td><td><div class="element">
                                                    <input type="text" id="customer_mobile" name="customer_mobile" value="{{ isset($customers->mobile) ? $customers->mobile : '' }}" autocomplete="off" maxlength="255" required class="required form-control" error-message="Customer mobile is mandatory"></div></td></tr>    <tr id="field_customer_fax" class="field ">
                                            <td class="">
                                                <div class="label ">
                                                    <span class="text-danger "></span>
                                                    <span class="title">Fax</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="element">
                                                    <input type="text" id="customer_fax" name="customer_fax" value="{{ isset($customers->fax) ? $customers->fax : '' }}" autocomplete="off" maxlength="255" class='form-control'>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr id="field_customer_url" class="field ">
                                            <td class="">
                                                <div class="label ">
                                                    <span class="text-danger "></span>
                                                    <span class="title">Website</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="element">
                                                    <input type="text" id="customer_url" name="customer_url" value="{{ isset($customers->website) ? $customers->website : '' }}" autocomplete="off" maxlength="255" class='form-control'>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr id="field_customer_preferredcommunicationchannel" class="field ">
                                            <td class="">
                                                <div class="label ">
                                                    <span class="text-danger "></span>
                                                    <span class="title">Preferred communication channel</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="element">

                                                    @php
                                                    $selected = '';
                                                    @endphp

                                                    @if (isset($customers->prefered_communication_type) )
                                                    @php
                                                    $selected = $customers->prefered_communication_type ;
                                                    @endphp                                                            
                                                    @endif

                                                    {{ Form::select('customer_preferredcommunicationchannel', array('mobile' => 'phone','email' => 'e-mail'), $selected,array('id' =>'customer_preferredcommunicationchannel','class'=>'form-control custom-select'))}}

                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>


                                                    <div class="panel panel-default panel-dark">
                                                        <div class="panel-heading" id="fieldgroup_title_accounting">
                                                             <div class="blocktitle"> 
                                                            <h3 class="panel-title">Accounting info</h3>							
                                                             </div> 
                                                        </div>
                                                        <div class="panel-body" id="fieldgroup_accounting">
                                                            <table class="insly-form">
                                                                <tbody>
                                                                    <tr id="field_customer_email_invoice" class="field"><td class=""><div class="label"><span class="title">Invoice e-mail</span></div></td>
                                                                        <td><div class="element"><input type="text" id="customer_email_invoice" name="customer_email_invoice" value="{{ isset($customers->invoice_email) ? $customers->invoice_email : '' }}" autocomplete="off" maxlength="255" class='form-control'></div></td>
                                                                    </tr> 
                                                                    <tr id="field_customer_bankaccount" class="field ">
                                                                        <td class="">
                                                                            <div class="label full-height" style="height: 90px;">
                                                                                <span class="text-danger "></span>
                                                                                <span class="title">Bank account info</span>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="element">
                                                                                <textarea id="customer_bankaccount" name="customer_bankaccount" wrap="soft" rows="4" class='form-control'>{{ isset($customers->bank_acc_info) ? $customers->bank_acc_info : '' }}</textarea>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr id="field_prop_invoice_consolidatedinvoices" class="field">
                                                                        <td class="">
                                                                            <div class="label ">
                                                                                <span class="text-danger "></span>
                                                                                <span class="title">Consolidated invoices</span>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="element">
                                                                                
                                                                                @php
                                                    $selected = '';
                                                    @endphp

                                                    @if (isset($customers->consolidate_invoice) )
                                                    @php
                                                    $selected = $customers->consolidate_invoice ;
                                                    @endphp                                                            
                                                    @endif

                                                    {{ Form::select('prop_invoice_consolidatedinvoices', array('0' => 'no preference','1' => 'does not want consolidated invoices','2'=>'wants consolidated invoices','3'=>'wants consolidates invoices by product'), $selected,array('id' =>'prop_invoice_consolidatedinvoices','class'=>'form-control custom-select'))}}
                                                                            
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>


                        <div class="panel panel-default panel-dark">
                            <div class="panel-heading" id="fieldgroup_title_customermgmt">
                                <!-- <div class="blocktitle"> -->
                                <h3 class="panel-title">Customer management</h3>							
                                <!-- </div> -->
                            </div>
                            <div class="panel-body" id="fieldgroup_customermgmt">
                                <table class="insly-form">
                                    <tbody>
                                        <tr id="field_broker_person_oid" class="field"><td class=""><div class="label full-height" style="height: 32px;"><span class="text-danger icon-asterix"></span><span class="title">Account manager</span></div></td><td>
                                                <div class="element">
                                                    <div style="width: 60%; float: left">
                                                         {{ Form::select('broker_person_oid', $users, isset($customers->customer_management_user) ? $customers->customer_management_user:Auth::user()->id,array('id' =>'broker_person_oid','style'=>'width: 94%; ','class'=>"required form-control custom-select","required" =>"required" ,"error-message"=>'Account manager field is mandatory'))}}                                                    





                                                    </div>
                                                    
             
                                                </div>
                                            </td>
                                        </tr>    
                                        <tr id="field_customergroup_oid" class="field ">
                                            <td class="">
                                                <div class="label ">
                                                    <span class="text-danger "></span>
                                                    <span class="title">Customer group</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="element">
                                                    {{ Form::select('customergroup_oid', $usergroup, isset($customers->customer_group) ? $customers->customer_group:'',array('id' =>'customergroup_oid','style' =>'width:59%','class'=>'form-control custom-select required','error-message' =>"Customer group field is mandatory"))}}  

                                                </div>
                                            </td>
                                        </tr>
                                             <tr id="field_customer_channel" class="field ">
                                            <td class="">
                                                <div class="label ">
                                                    <span class="text-danger "></span>
                                                    <span class="title">Channel</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="element">
                                                    {{ Form::select('customer_channel', $channelDetails, '',array('id' =>'customer_channel','style' =>'width:59%','class'=>'form-control custom-select required','error-message' =>"Channel field is mandatory"))}} 

                                                </div>
                                            </td>
                                        </tr>
                                              <tr id="field_customer_channel" class="field">
                                            <td class="">
                                                <div class="label ">
                                                    <span class="text-danger "></span>
                                                    <span class="title">Technical person</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="element">
                                                    {{ Form::select('customer_technical_handler', ['' => '---Not set---']+ $technicalhanler, '',array('id' =>'customer_technical_handler','style' =>'width:59%','class'=>'form-control custom-select'))}} 
                                                 
                                                </div>
                                            </td>
                                        </tr>

                                    
                                    </tbody>
                                </table>
                            </div>
                        </div>


                        <div class="panel panel-default panel-dark">
                            <div class="panel-heading" id="fieldgroup_title_addaddress">
                                <!-- <div class="blocktitle"> -->
                                <h3 class="panel-title">Add National address</h3>							
                                <!-- </div> -->
                            </div>
                            <div class="panel-body" id="fieldgroup_addaddress">
                                <table class="insly-form">
                                    <tbody>
                                        <tr id="field_addaddress_address" class="field">
                                            <td class=""><div class="label full-height" style="height: 125px;"><span class="title">Address</span></div></td>
                                            <td><div class="element">
                                                    <div style="overflow: hidden">
                                                        <div style="float: left; width: 20%; padding-right: 10px"><label class="label-without-margins label-simple">Building No:</label><br>
                                                            <input type="text" id="addaddress_address_building_no" name="addaddress_address_building_no" value="{{ isset($customers->building_no) ? $customers->building_no : '' }}" autocomplete="off" style="width: 100%" maxlength="100" class='form-control'>
                                                        </div>
                                                        <div style="float: left; width: 43%; padding-right: 10px"><label class="label-without-margins label-simple">Street</label><br>
                                                            <input type="text" id="addaddress_address_street" name="addaddress_address_street" value="{{ isset($customers->street_name) ? $customers->street_name : '' }}" autocomplete="off" style="width: 100%" class='form-control'>
                                                        </div>
                                                        <div style="float: left; width: 35%; padding-right: 10px"><label class="label-without-margins label-simple">District</label><br>
                                                            <input type="text" id="addaddress_address_district" name="addaddress_address_district" value="{{ isset($customers->district_name) ? $customers->district_name : '' }}" autocomplete="off" style="width: 100%" maxlength="100" class='form-control'>
                                                        </div>
                                                    </div>


                                                    <div style="overflow: hidden; padding-bottom: 9px">
                                                        <div style="float: left; width: 20%; padding-right: 10px"><label class="label-without-margins label-simple">City</label><br>
                                                            <input type="text" id="addaddress_address_city" name="addaddress_address_city" value="{{ isset($customers->city_name) ? $customers->city_name : '' }}" autocomplete="off" style="width: 100%" maxlength="100" class='form-control'>
                                                        </div>
                                                        <div style="float: left; width: 43%; padding-right: 10px"><label class="label-without-margins label-simple">Zip code</label><br>
                                                            <input type="text" id="addaddress_address_zipcode" name="addaddress_address_zipcode" value="{{ isset($customers->zip_code) ? $customers->zip_code : '' }}" autocomplete="off" style="width: 100%" class='form-control'>
                                                        </div>
                                                        <div style="float: left; width: 35%; padding-right: 10px"><label class="label-without-margins label-simple">Additional No:</label><br>
                                                            <input type="text" id="addaddress_address_additional_no" name="addaddress_address_additional_no" value="{{ isset($customers->additional_no) ? $customers->additional_no : '' }}" autocomplete="off" style="width: 100%" maxlength="100" class='form-control'>
                                                        </div>
                                                    </div>
                                                    <div style="overflow: hidden; padding-bottom: 9px">
                                                        <div style="float: left; width: 20%; padding-right: 10px"><label class="label-without-margins label-simple">Unit No:</label><br>
                                                            <input type="text" id="addaddress_address_unit_no" name="addaddress_address_unit_no" value="{{ isset($customers->unit_no) ? $customers->unit_no : '' }}" autocomplete="off" style="width: 100%" maxlength="100" class='form-control'>
                                                        </div>

                                                    </div>


                                                </div></td></tr>
<!--                                        <tr id="field_addaddress_address_type" class="field"><td class=" narrow"><div class="label full-height" style="height: 78px;"><span class="title">Type</span></div></td><td><div class="element"><div class="checklist">
                                                        <input type="hidden" name="addaddress_address_type" value="">
                                                        <label style="width: 30%; cursor: pointer; color: rgb(87, 87, 87);">
                                                            @php
                                                            $selctedCheckbox = array();
                                                            @endphp
                                                            @if (isset($customers -> address_type) && $customers -> address_type !='null')
                                                            @php
                                                           
                                                            $selctedCheckbox = json_decode($customers -> address_type,true);
                                                   
                                                            @endphp
                                                            @endif
                                                            @if (in_array('legal', $selctedCheckbox)) 
                                                            @php
                                                            $checked = true;
                                                            @endphp

                                                            @else
                                                            @php
                                                            $checked = false;
                                                            @endphp 
                                                            @endif

                                                            {{ Form::checkbox('addaddress_address_type[]', 'legal', $checked , array('id' =>'addaddress_address_type_legal')) }}
                                                            
                                                            <span class="icon-check-empty dib_checkbox_select"></span>legal address</label><label style="width: 30%; cursor: pointer; color: rgb(87, 87, 87);">

                                                            @if (in_array('office', $selctedCheckbox)) 
                                                            @php
                                                            $checked = true;
                                                            @endphp
                                                            @else
                                                            @php
                                                            $checked = false;
                                                            @endphp 
                                                            @endif
                                                            

                                                            {{ Form::checkbox('addaddress_address_type[]', 'office', $checked , array('id' =>'addaddress_address_type_office')) }}       

                                                            
                                                            <span class="icon-check-empty dib_checkbox_select"></span>office address</label><label style="width: 30%; cursor: pointer; color: rgb(87, 87, 87);">
                                                            @if (in_array('home', $selctedCheckbox)) 
                                                            @php
                                                            $checked = true;
                                                            @endphp
                                                            @else
                                                            @php
                                                            $checked = false;
                                                            @endphp 
                                                            @endif

                                                            {{ Form::checkbox('addaddress_address_type[]', 'home', $checked , array('id' =>'addaddress_address_type_home')) }}


                                                             <span class="icon-check-empty dib_checkbox_select"></span>home address</label><label style="width: 30%; cursor: pointer; color: rgb(87, 87, 87);">

                                                            @if (in_array('work', $selctedCheckbox)) 
                                                            @php
                                                            $checked = true;
                                                            @endphp
                                                            @else
                                                            @php
                                                            $checked = false;
                                                            @endphp 
                                                            @endif

                                                            {{ Form::checkbox('addaddress_address_type[]', 'work', $checked , array('id' =>'addaddress_address_type_work')) }}     

                                                             <span class="icon-check-empty dib_checkbox_select"></span>work address</label><label style="width: 30%; cursor: pointer; color: rgb(87, 87, 87);">

                                                            @if (in_array('postal', $selctedCheckbox)) 
                                                            @php
                                                            $checked = true;
                                                            @endphp


                                                            @else
                                                            @php
                                                            $checked = false;
                                                            @endphp 
                                                            @endif

                                                            {{ Form::checkbox('addaddress_address_type[]', 'postal', $checked , array('id' =>'addaddress_address_type_postal')) }}       

                                                             <span class="icon-check-empty dib_checkbox_select"></span>postal address</label></div></div></td></tr>							</tbody>-->
                                </table>
                            </div>
                        </div>


                        <div class="panel panel-default panel-dark" style="display:show">
                            <div class="panel-heading" id="fieldgroup_title_customerprofile">
                                <!-- <div class="blocktitle"> -->
                                <h3 class="panel-title">Customer profile</h3>						
                                <!-- </div> -->
                            </div>
                            <div class="panel-body" id="fieldgroup_customerprofile" >
                                <table class="insly-form">
                                    <tbody>
                                        <tr id="field_prop_salesopportunities_fieldofactivity" class="field ">
                                            <td class="">
                                                <div class="label ">
                                                    <span class="text-danger "></span>
                                                    <span class="title">Field of activity</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="element">
                                                    <select id="prop_salesopportunities_fieldofactivity" name="prop_salesopportunities_fieldofactivity" class='form-control custom-select'>
                                                        <option value="">--- not set ---</option>
                                                        <option value="10076727">DIB</option>

                                                    </select>


                                                </div>
                                            </td>
                                        </tr>
                                        <tr id="field_prop_customerprofile_10003016" class="field">
                                            <td class="">
                                                <div class="label ">
                                                    <span class="text-danger "></span>
                                                    <span class="title">CLIENT REQUEST</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="element">
                                                    <input type="text" id="prop_customerprofile_10003016" name="prop_customerprofile_10003016" value="" autocomplete="off" maxlength="255" class='form-control'>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr id="field_prop_customerprofile_10016579" class="field ">
                                            <td class="">
                                                <div class="label ">
                                                    <span class="text-danger "></span>
                                                    <span class="title">OTHER INFO</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="element">
                                                    <input type="text" id="prop_customerprofile_10016579" name="prop_customerprofile_10016579" value="" autocomplete="off" maxlength="255" class='form-control'>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr id="field_prop_customerprofile_10016665" class="field ">
                                            <td class="">
                                                <div class="label ">
                                                    <span class="text-danger "></span>
                                                    <span class="title">COMPANY NAME</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="element">
                                                    <input type="text" id="prop_customerprofile_10016665" name="prop_customerprofile_10016665" value="" autocomplete="off" maxlength="255" class='form-control'>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr id="field_prop_customerprofile_10028459" class="field">
                                            <td class="">
                                                <div class="label"><span class="text-danger icon-asterix"></span><span class="title">Date</span></div></td>
                                            <td><div class="element">
                                                    <input type="date" id="prop_customerprofile_10028459" name="prop_customerprofile_10028459" value=""  id="testDatepicker" class="datefield form-control" style="margin-right: 0px !important"></div>
                                            </td>
                                        </tr>							</tbody>
                                </table>
                            </div>
                        </div>


                        <div class="panel panel-default panel-dark" >
                            <div class="panel-heading custom-control custom-checkbox mr-sm-2 mb-3" id="fieldgroup_title_addperson">
                                <!-- <div class="blocktitle"> -->
                              {{ Form::checkbox('addperson', 1, $checked , array('id' =>'addperson','class'=>"pull-left custom-control-input")) }}
                               <label for="addperson" style="cursor: pointer; color: rgb(87, 87, 87);left:34px" class='custom-control-label'>
                                    @php
                                          $checked = false;
                                        @endphp    
                                    @if (isset($customers -> add_contact_person_flag) && $customers -> add_contact_person_flag ==1)
                                        @php
                                          $checked = true;
                                        @endphp    
                                    @endif  
                                    
                                    
<!--                                    <span class="icon-check-empty dib_checkbox_addperson"></span>-->
                                    <h3 class="panel-title fieldgroup-title-checkable" style='padding-top:0px'>Add a contact person</h3></label>							
                                <!-- </div> -->
                            </div>
                            <div class="panel-body" id="fieldgroup_addperson" style="display: none;">
                                <table class="insly-form">
                                    <tbody>
                                        <tr id="field_addperson_name" class="field ">
                                            <td class="">
                                                <div class="label ">
                                                    <span class="text-danger icon-asterix"></span>
                                                    <span class="title">Contact person name</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="element">
                                                    <input type="text" id="addperson_name" name="addperson_name" value="{{ isset($customers->person_name) ? $customers->person_name : '' }}" autocomplete="off" maxlength="255" class="required form-control" required error-message="Contact person name is mandatory">
                                                </div>
                                            </td>
                                        </tr>
                                        <tr id="field_addperson_title" class="field ">
                                            <td class="">
                                                <div class="label ">
                                                    <span class="text-danger "></span>
                                                    <span class="title">Contact person title</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="element">
                                                    <input type="text" id="addperson_title" name="addperson_title" value="{{ isset($customers->person_title) ? $customers->person_title : '' }}" autocomplete="off" maxlength="32" class='form-control'>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr id="field_addperson_email" class="field"><td class=""><div class="label"><span class="title">E-mail address</span></div></td>
                                            <td><div class="element">
                                                    <input type="text" id="addperson_email" name="addperson_email" value="{{ isset($customers->contactEmail) ? $customers->contactEmail : '' }}" autocomplete="off" maxlength="255" class='form-control'> </div></td></tr>    <tr id="field_addperson_mobile" class="field ">
                                            <td class="">
                                                <div class="label ">
                                                    <span class="text-danger "></span>
                                                    <span class="title">Mobile phone</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="element">
                                                    <input type="text" id="addperson_mobile" name="addperson_mobile" value="{{ isset($customers->mobile_phone) ? $customers->mobile_phone : '' }}" autocomplete="off" maxlength="255" class='form-control'>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr id="field_addperson_phone" class="field ">
                                            <td class="">
                                                <div class="label ">
                                                    <span class="text-danger "></span>
                                                    <span class="title">Phone</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="element">
                                                    <input type="text" id="addperson_phone" name="addperson_phone" value="{{ isset($customers->contactPhone) ? $customers->contactPhone : '' }}" autocomplete="off" maxlength="255" class='form-control'>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr id="field_addperson_idcode" class="field ">
                                            <td class="">
                                                <div class="label ">
                                                    <span class="text-danger "></span>
                                                    <span class="title">Contact person ID code</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="element">
                                                    <input type="text" id="addperson_idcode" name="addperson_idcode" value="{{ isset($customers->contact_person_id_code) ? $customers->contact_person_id_code : '' }}" autocomplete="off" maxlength="255" class='form-control'>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="buttonbar pull-right">
                            <div class="submit"><button type="button" id="submit_save" name="submit_save" class="btn waves-effect waves-light btn-rounded btn-success" >{{ isset($customers->customerName) ? 'Edit' : 'Add' }}</button><button type="button" id="submit_cancel" class="btn waves-effect waves-light btn-rounded btn-danger" name="submit_cancel" onclick="FORM.cancel()">Cancel</button></div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('pagescript')


<script>
   
   var selectedAddressType = []; 
    

   
    $(function () {

        
        
        $(document).on('click', 'input.dib_customer_type', function () {
            $("#field_customer_gender").hide();
            if ($(this).val() == 1) {
//                $("#dib_company_span").removeClass('icon-radio-empty').addClass('icon-radio');
//                $("#dib_individual_span").removeClass('icon-radio').addClass('icon-radio-empty');

            } else {
                $("#field_customer_gender").show();
//                $("#dib_individual_span").removeClass('icon-radio-empty').addClass('icon-radio');
//                $("#dib_company_span").removeClass('icon-radio').addClass('icon-radio-empty');
            }
        });

        $(document).on('click', '.dib_select_box', function () {
//            $("#broker_person_oid").show();
//            $("span.dib_select_box").hide();
//            if ($("#broker_person_oid_showall").is(':checked')) {
//                $("#broker_person_oid").html($("#broker_person_oid_fulllist").html());
//            } else
//            {
//                $("#broker_person_oid").html($("#broker_person_oid_shortlist").html());
//            }

        });


        $(document).on('click', '#addperson', function () {
            if ($(this).is(':checked')) {
                $('#fieldgroup_addperson').show();
            } else {
                $('#fieldgroup_addperson').hide();
            }

        });
        
        @if (isset($customers -> address_type) && $customers -> address_type !='')
           selectedAddressType = {!! json_decode(json_encode($customers -> address_type,true)) !!};
        @endif

       if (_.includes(selectedAddressType, 'legal')) {
            $('tr input[type=checkbox][value=legal]').trigger('click');
        }
        if (_.includes(selectedAddressType, 'office')) {
          
        $('tr input[type=checkbox][value=office]').trigger('click');
        }
        if (_.includes(selectedAddressType, 'home')) {
            $('tr input[type=checkbox][value=home]').trigger('click');
        }
        if (_.includes(selectedAddressType, 'work')) {
            $('tr input[type=checkbox][value=work]').trigger('click');
        }
        if (_.includes(selectedAddressType, 'postal')) {
            $('tr input[type=checkbox][value=postal]').trigger('click');
        }

         @if (isset($customers -> person_name) && $customers -> person_name !='')
          $("input#addperson").trigger('click');   
         @endif 
         
         @if (isset($customers -> type) )
           $("input.dib_customer_type:checked").trigger('click');  
          @else
          $("#customer_type_11").prop('checked','checked');    
         @endif  
         
         
         $(document).on('click','#submit_save',function(e){
             
            var isValid = true;
         var errorMessage = "";
                var i=0;
                $(".required:visible").each(function(){                
                 if($(this).val()=='') {
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
                if( !validateEmail($("#customer_email").val()) ) { 
                    isValid = false;
                    errorMessage += "<b> Email format is not correct</b><br/>";
                }

            if(isValid) {
              $("#customerForm").submit();
            } else {
              DIB.alert(errorMessage,'Error!!!!');    
            }
                    
         })
         
         
      
    })
    function validateEmail($email) {
        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        return emailReg.test( $email );
    }

</script>
@endsection