

{{ Form::open(array('route' => array('saveconnectiondetails', $customerId), 'name' => 'form_customer_connection_add','id'=>'form_customer_connection_add','files'=>'true' )) }}



@csrf 

<div class="dialogform" id="fieldgroup_addaddress">

    <table class="insly_dialogform">
        <tbody><tr id="field_customer_2_oid" class="field"><td class="">
                    <div class="label"><span class="text-danger icon-asterix"></span><span class="title">Related customer</span></div></td>
                <td><div class="element">
                        {{ Form::select('customer_id',  $allCustomers, $customerId,array('name'=>'related_customer_id','id' =>'related_customer_id','required'=>'required','class'=>'required','error-message' =>"Insurance company field is mandatory"))}}
                    </div>
                </td>
            </tr>   
            <tr id="field_customer_relation_type" class="field ">
                <td class="">
                    <div class="label ">
                        <span class="text-danger icon-asterix"></span>
                        <span class="title">Relation</span>
                    </div>
                </td>
                <td>
                    <div class="element">
                        <select id="customer_relation_type" name="customer_relation_type">
                            <option value="">--- select from here ---</option>
                            <option value="daughter company">daughter company</option>

                            <option value="group company">group company</option>

                            <option value="manager/owner">manager/owner</option>

                            <option value="employee">employee</option>

                            <option value="key employee">key employee</option>

                            <option value="child">child</option>

                            <option value="sibling">sibling</option>

                            <option value="spouse">spouse</option>

                            <option value="relative">relative</option>

                            <option value="other">other</option>

                        </select>


                    </div>
                </td>
            </tr>
            <tr id="field_customer_relation_description" class="field relationtype" style="display: table-row; overflow: hidden;">
                <td class="">
                    <div class="label ">
                        <span class="text-danger "></span>
                        <span class="title">Description</span>
                    </div>
                </td>
                <td>
                    <div class="element">
                        <input type="text" id="customer_relation_description" name="customer_relation_description" value="" autocomplete="off">
                    </div>
                </td>
            </tr>
    

        </tbody></table></div>


{{ Form::close() }} 