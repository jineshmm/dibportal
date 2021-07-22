
@if (isset($addressId))
{{ Form::open(array('route' => array('updatecontactaddress', $customerId,$addressId), 'name' => 'form_contact_address_add','id'=>'form_contact_address_add','files'=>'true' )) }}
@else
{{ Form::open(array('route' => array('savecontactaddress', $customerId), 'name' => 'form_contact_address_add','id'=>'form_contact_address_add','files'=>'true' )) }}
@endif


@csrf 

<div class="dialogform" id="fieldgroup_addaddress">



    <table class="insly-form">
        <tbody>
            <tr id="field_addaddress_address" class="field" style="background-color:#EEE">
                <td class=""><div class="label full-height" style="height: 125px;"><span class="title">Address</span></div></td>
                <td><div class="element">
                        <div style="overflow: hidden">
                            <div style="float: left; width: 20%; padding-right: 10px"><label class="label-without-margins label-simple">Building No:</label><br>
                                <input type="text" id="addaddress_address_building_no" name="addaddress_address_building_no" value="{{ isset($customers->building_no) ? $customers->building_no : '' }}" autocomplete="off" style="width: 100%" maxlength="10">
                            </div>
                            <div style="float: left; width: 43%; padding-right: 10px"><label class="label-without-margins label-simple">Street</label><br>
                                <input type="text" id="addaddress_address_street" name="addaddress_address_street" value="{{ isset($customers->street_name) ? $customers->street_name : '' }}" autocomplete="off" style="width: 100%">
                            </div>
                            <div style="float: left; width: 35%; padding-right: 10px"><label class="label-without-margins label-simple">District</label><br>
                                <input type="text" id="addaddress_address_district" name="addaddress_address_district" value="{{ isset($customers->district_name) ? $customers->district_name : '' }}" autocomplete="off" style="width: 100%" maxlength="10">
                            </div>
                        </div>


                        <div style="overflow: hidden; padding-bottom: 9px">
                            <div style="float: left; width: 20%; padding-right: 10px"><label class="label-without-margins label-simple">City</label><br>
                                <input type="text" id="addaddress_address_city" name="addaddress_address_city" value="{{ isset($customers->city_name) ? $customers->city_name : '' }}" autocomplete="off" style="width: 100%" maxlength="10">
                            </div>
                            <div style="float: left; width: 43%; padding-right: 10px"><label class="label-without-margins label-simple">Zip code</label><br>
                                <input type="text" id="addaddress_address_zipcode" name="addaddress_address_zipcode" value="{{ isset($customers->zip_code) ? $customers->zip_code : '' }}" autocomplete="off" style="width: 100%">
                            </div>
                            <div style="float: left; width: 35%; padding-right: 10px"><label class="label-without-margins label-simple">Additional No:</label><br>
                                <input type="text" id="addaddress_address_additional_no" name="addaddress_address_additional_no" value="{{ isset($customers->additional_no) ? $customers->additional_no : '' }}" autocomplete="off" style="width: 100%" maxlength="10">
                            </div>
                        </div>
                        <div style="overflow: hidden; padding-bottom: 9px">
                            <div style="float: left; width: 20%; padding-right: 10px"><label class="label-without-margins label-simple">Unit No:</label><br>
                                <input type="text" id="addaddress_address_unit_no" name="addaddress_address_unit_no" value="{{ isset($customers->unit_no) ? $customers->unit_no : '' }}" autocomplete="off" style="width: 100%" maxlength="10">
                            </div>

                        </div>


                    </div></td></tr>
    </table>
</div>
{{ Form::close() }} 