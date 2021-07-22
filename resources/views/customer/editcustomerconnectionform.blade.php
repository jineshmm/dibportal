

{{ Form::open(array('route' => array('updateconnectiondetails', $customerId, $connectionid), 'name' => 'form_customer_connection_add','id'=>'form_customer_connection_add','files'=>'true' )) }}



@csrf 

<div class="dialogform" id="fieldgroup_addaddress">

    <table class="insly_dialogform">
        <tbody><tr id="field_customer_2_oid" class="field"><td class="">
                    <div class="label"><span class="text-danger icon-asterix"></span><span class="title">Related customer</span></div></td>
                <td><div class="element">
                        <div class="element element-text">{{$customerdetails->name}}</div>
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
                    
                            <option value="daughter company" {{ isset($customerdetails->relation_type) ? (($customerdetails->relation_type=="daughter company")? 'selected':''): '' }}>daughter company</option>

                            <option value="group company" {{ isset($customerdetails->relation_type) ? (($customerdetails->relation_type=="group company")? 'selected':''): '' }}>group company</option>

                            <option value="manager/owner" {{ isset($customerdetails->relation_type) ? (($customerdetails->relation_type=="manager/owner")? 'selected':''): '' }}>manager/owner</option>

                            <option value="employee" {{ isset($customerdetails->relation_type) ? (($customerdetails->relation_type=="employee")? 'selected':''): '' }}>employee</option>

                            <option value="key employee" {{ isset($customerdetails->relation_type) ? (($customerdetails->relation_type=="key employee")? 'selected':''): '' }}>key employee</option>

                            <option value="child" {{ isset($customerdetails->relation_type) ? (($customerdetails->relation_type=="child")? 'selected':''): '' }}>child</option>

                            <option value="sibling" {{ isset($customerdetails->relation_type) ? (($customerdetails->relation_type=="sibling")? 'selected':''): '' }}>sibling</option>

                            <option value="spouse" {{ isset($customerdetails->relation_type) ? (($customerdetails->relation_type=="spouse")? 'selected':''): '' }}>spouse</option>

                            <option value="relative" {{ isset($customerdetails->relation_type) ? (($customerdetails->relation_type=="relative")? 'selected':''): '' }}>relative</option>

                            <option value="other" {{ isset($customerdetails->relation_type) ? (($customerdetails->relation_type=="other")? 'selected':''): '' }}>other</option>

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
                        <input type="text" id="customer_relation_description" name="customer_relation_description" value="{{$customerdetails->description}}" autocomplete="off">
                    </div>
                </td>
            </tr>
    

        </tbody></table></div>


{{ Form::close() }} 