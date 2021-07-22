

    
        <div class="panel panel-default panel-dark">
            <div class="panel-heading" id="fieldgroup_title_policy">
                <!-- <div class="blocktitle"> -->
                <h3 class="panel-title">Select product</h3>							
                <!-- </div> -->
            </div>
            <div class="panel-body" id="fieldgroup_policy">
                <table class="insly-form">
                    <tbody>
             
                        <tr id="field_policy_insurer" class="field ">
                            <td class="">
                                <div class="label ">
                                    <span class="text-danger icon-asterix"></span>
                                    <span class="title">Products</span>
                                </div>
                            </td>
                            <td>
                                <div class="element">
                                    {{ Form::select('policy_product',  array(''=>'--not selected--')+$productDetails, null,array('name'=>'policy_product','id' =>'policy_product','required'=>'required','class'=>'required form-control','error-message' =>"Product field is mandatory",'openUrl'=>route("productfields") ))}}  


                                </div>
                            </td>
                        </tr>


                    </tbody>
                </table>
            </div>
        </div>
        <div class="panel panel-default panel-dark productformblock" id="form_product_field_area" style="display:none">
            Form display area
        </div>



        <div class="panel panel-default panel-dark productformblock" id="form_commission"><div class="panel-heading"><h3 class="panel-title">Commission</h3></div><div class="panel-body" id="fieldgroup_commission"><table class="insly-form">    <tbody><tr id="field_policy_commission" class="field ">
                            <td class="">
                                <div class="label ">
                                    <span class="text-danger "></span>
                                    <span class="title">Commission</span>
                                </div>
                            </td>
                            <td>
                                <div class="element">
                                    
                                    <input type="text" id="policy_commission" name="policy_commission" value="" autocomplete="off" class="with-comment with-comment form-control required" data-m-dec="4" error-message="Commission field is mandatory">
                                    <small>% &nbsp;&nbsp; <span class="warning">Commission not entered for this insurer/product.</span></small>
                                    
                                  
                                </div>
                            </td>
                        </tr>
                    </tbody></table></div>
        </div>
        <div class="panel panel-default panel-dark productformblock" id="form_tax"><div class="panel-heading"><h3 class="panel-title">Tax</h3></div><div class="panel-body" id="fieldgroup_tax"><table class="insly-form">    <tbody><tr id="field_policy_tax" class="field ">
                            <td class="">
                                <div class="label ">
                                    <span class="text-danger icon-asterix"></span>
                                    <span class="title">VAT</span>
                                </div>
                            </td>
                            <td>
                                <div class="element">
                                    <select id="policy_tax" name="policy_tax" data-default-value="5" class='form-control required' error-message="Tax field is mandatory">
                                        <option value="">--- select from here ---</option>
                                         <option value="0">Nil (0%)</option>
                                        <option value="5" >VAT (5%)</option>
                                        <option value="15" selected="selected">VAT (15%)</option>
                                    </select>


                                </div>
                            </td>
                        </tr>
                    </tbody></table></div>
        </div>

        <!--        OBJECT ADDING AREA-->

        <div class="panel panel-default panel-dark productformblock" id="form_object" style="display:none">
            <!--    Object display area-->

        </div>
        <!--        EXTRA OBJECT ADDING AREA-->
        <div id="extra_object_append_area">



        </div>

        <div class="panel panel-default panel-dark productformblock" id="form_addobjects" style="display:none">
            <div class="panel-heading"><h3 class="panel-title"></h3></div>
            <div class="panel-body" id="fieldgroup_addobjects">
                <table class="insly-form">
                    <tbody>
                        <tr id="field_addobjects" class="field">
                            <td colspan="2"  style="padding: 8px; text-align: center"><input type="hidden" name="extra_object_count" id="extra_object_count" value="1"/><button type="button" class="dipib_add_extra_policy_obj" object-type=''>Add another object</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>



 
    


<script id='person_object_template' type='text/html'>

    <div class="panel panel-default panel-dark productformblock form_extra_object" >   
        <div class="panel-heading">
            <ul class="panel-actions list-inline pull-right">
                <li class="dpib_delete_object">
                    <span class="icon-delete" data-toggle="tooltip" title="Remove object"></span>
                </li></ul><h3 class="panel-title"><%= title %></h3></div>



        <div class="panel-body" id="fieldgroup_person">
            <table class="insly-form">
                <tbody>

                    <tr id="field_object_last_name" class="field ">
                        <td class="">
                            <div class="label ">
                                <span class="text-danger icon-asterix"></span>
                                <span class="title">Last name</span>
                            </div>
                        </td>
                        <td>
                            <div class="element">
                                <input type="text" id="object_last_name" class="form-control" name="object_last_name[]" value="" autocomplete="off" maxlength="100">
                            </div>
                        </td>
                    </tr>
                    <tr id="field_object_middle_name_<%= count %>" class="field ">
                        <td class="">
                            <div class="label ">
                                
                                <span class="text-danger icon-asterix"></span>
                                <span class="title">First/middle names</span>
                            </div>
                        </td>
                        <td>
                            <div class="element">
                                <input type="text" id="object_middle_name_<%= count %>" class="form-control" name="object_middle_name[]" value="" autocomplete="off" maxlength="100">
                            </div>
                        </td>
                    </tr>
                    <tr id="field_object_dob_<%= count %>" class="field">
                        <td class=""><div class="label"><span class="text-danger icon-asterix"></span><span class="title">Date of birth</span></div></td>
                        <td><div class="element"><input type="text" id="object_dob_<%= count %>" name="object_dob[]" value="" maxlength="10" autocomplete="off" class="datefield form-control" style="margin-right: 0px !important"><div style="float: right; width: 40%; padding-left: 10px; padding-top: 9px; overflow: hidden"><div id="object_dob_comment_<%= count %>"></div></div></div>
                        </td>
                    </tr> 
                    <tr id="field_object_gender_<%= count %>" class="field ">
                        <td class="">
                            <div class="label ">
                                <span class="text-danger "></span>
                                <span class="title">Gender</span>
                            </div>
                        </td>
                        <td>
                            <div class="element">
                                <select id="object_gender_<%= count %>" name="object_gender[]" class="form-control">
                                    <option value="">--- select from here ---</option>
                                    <option value="male">male</option>

                                    <option value="female">female</option>

                                </select>


                            </div>
                        </td>
                    </tr>
                    <tr id="field_object_coverage_<%= count %>" class="field"><td colspan="2" class="subtitle">Coverage validity</td></tr>
                    <tr id="field_object_coverage_begin_<%= count %>" class="field">
                        <td class=""><div class="label"><span class="title">Coverage begin</span></div></td>
                        <td><div class="element"><input type="text" id="object_coverage_begin_<%= count %>" name="object_coverage_begin[]" value="" maxlength="10" autocomplete="off" class="datefield form-control" style="margin-right: 0px !important"><div style="float: right; width: 40%; padding-left: 10px; padding-top: 9px; overflow: hidden"><div id="field_object_coverage_comment_<%= count %>"></div></div></div>
                        </td>
                    </tr>
                    <tr id="field_object_coverage_end_<%= count %>" class="field">
                        <td class=""><div class="label"><span class="title">Coverage end</span></div></td>
                        <td><div class="element"><input type="text" id="object_object_coverage_end_<%= count %>" name="object_coverage_end[]" value="" maxlength="10" autocomplete="off" class="datefield form-control" style="margin-right: 0px !important"><div style="float: right; width: 40%; padding-left: 10px; padding-top: 9px; overflow: hidden"><div id="field_object_coverage_end_comment_<%= count %>"></div></div></div></td>
                    </tr>
                </tbody></table></div>  
    </div>
</script>
<script id='vehicle_object_template' type='text/html'>
    
  <div class="panel panel-default panel-dark productformblock form_extra_object" >    
<div class="panel-heading">
            <ul class="panel-actions list-inline pull-right">
                <li class="dpib_delete_object">
                    <span class="icon-delete" data-toggle="tooltip" title="Remove object"></span>
                </li></ul><h3 class="panel-title"><%= title %></h3></div>

<div class="panel-body" id="fieldgroup_vehicle">
    <table class="insly-form">
        <tbody>


            <tr id="field_object_make_<%= count %>" class="field ">
                <td class="">
                    <div class="label ">
                        <span class="text-danger icon-asterix"></span>
                        <span class="title">Make</span>
                       
                    </div>
                </td>
                <td>
                    <div class="element">
                        <select id="object_make_<%= count %>" name="object_make[]" class="form-control">
                            <option value="">--- select from here ---</option>
                            <option value="ASTON MARTIN">ASTON MARTIN</option>

                            <option value="AUDI">AUDI</option>

                            <option value="BENTLEY">BENTLEY</option>

                            <option value="BMW">BMW</option>

                            <option value="BUGATTI">BUGATTI</option>

                            <option value="BUICK">BUICK</option>

                            <option value="BYD">BYD</option>

                            <option value="CADILLAC">CADILLAC</option>

                            <option value="CHEVROLET">CHEVROLET</option>

                            <option value="CHRYSLER">CHRYSLER</option>

                            <option value="CITROEN">CITROEN</option>

                            <option value="DAEWOO">DAEWOO</option>

                            <option value="DODGE">DODGE</option>

                            <option value="FERRARI">FERRARI</option>

                            <option value="FIAT">FIAT</option>

                            <option value="FORD">FORD</option>

                            <option value="GEELY">GEELY</option>

                            <option value="GMC">GMC</option>

                            <option value="GRAND">GRAND</option>

                            <option value="HONDA">HONDA</option>

                            <option value="HUMMER">HUMMER</option>

                            <option value="HYUNDAI">HYUNDAI</option>

                            <option value="ISUZU">ISUZU</option>

                            <option value="JAGUAR">JAGUAR</option>

                            <option value="KIA">KIA</option>

                            <option value="LAMBORGHINI">LAMBORGHINI</option>

                            <option value="LAND ROVER">LAND ROVER</option>

                            <option value="LEXUS">LEXUS</option>

                            <option value="LINCOLN">LINCOLN</option>

                            <option value="LONDON TAXIS">LONDON TAXIS</option>

                            <option value="LOTUS">LOTUS</option>

                            <option value="MASERATI">MASERATI</option>

                            <option value="MAZDA">MAZDA</option>

                            <option value="MCLAREN">MCLAREN</option>

                            <option value="MERCEDES-BENZ">MERCEDES-BENZ</option>

                            <option value="MINI COOPER">MINI COOPER</option>

                            <option value="MITSUBISHI">MITSUBISHI</option>

                            <option value="NISSAN">NISSAN</option>

                            <option value="PEUGEOT">PEUGEOT</option>

                            <option value="PONTIAC">PONTIAC</option>

                            <option value="PORSCHE">PORSCHE</option>

                            <option value="RANGE ROVER">RANGE ROVER</option>

                            <option value="ROLLS-ROYCE">ROLLS-ROYCE</option>

                            <option value="SAAB">SAAB</option>

                            <option value="SEAT">SEAT</option>

                            <option value="SKODA">SKODA</option>

                            <option value="SUBARU">SUBARU</option>

                            <option value="SUZUKI">SUZUKI</option>

                            <option value="TESLA">TESLA</option>

                            <option value="TOYOTA">TOYOTA</option>

                            <option value="VOLKSWAGEN">VOLKSWAGEN</option>

                            <option value="VOLVO">VOLVO</option>

                            <option value="JEEP">JEEP</option>

                        </select>


                    </div>
                </td>
            </tr>
            <tr id="field_model_<%= count %>" class="field ">
                <td class="">
                    <div class="label ">
                        <span class="text-danger icon-asterix"></span>
                        <span class="title">Model</span>
                    </div>
                </td>
                <td>
                    <div class="element">
                        <input type="text" id="object_model_<%= count %>" name="object_model[]" value="" autocomplete="off" maxlength="255" class="form-control">
                    </div>
                </td>
            </tr>
            <tr id="field_object_year_<%= count %>" class="field ">
                <td class="">
                    <div class="label ">
                        <span class="text-danger icon-asterix"></span>
                        <span class="title">Year</span>
                    </div>
                </td>
                <td>
                    <div class="element">
                        <input type="text" id="object_year_<%= count %>" name="object_year[]" value="" autocomplete="off" data-m-dec="0" class="form-control">
                    </div>
                </td>
            </tr>
            <tr id="field_object_license_plate_<%= count %>" class="field ">
                <td class="">
                    <div class="label ">
                        <span class="text-danger icon-asterix"></span>
                        <span class="title">License plate</span>
                    </div>
                </td>
                <td>
                    <div class="element">
                        <input type="text" id="object_license_plate_<%= count %>"" name="object_license_plate[]" value="" autocomplete="off" maxlength="32" class="form-control">
                    </div>
                </td>
            </tr>
            <tr id="field_object_body_type_<%= count %>" class="field ">
                <td class="">
                    <div class="label ">
                        <span class="text-danger icon-asterix"></span>
                        <span class="title">Body Type</span>
                    </div>
                </td>
                <td>
                    <div class="element">
                        <select id="object_body_type_<%= count %>" name="object_body_type[]" class="form-control">
                            <option value="">--- select from here ---</option>
                            <option value="BUS">BUS</option>

                            <option value="COUPE/SPORT">COUPE/SPORT</option>

                            <option value="HEAD">HEAD</option>

                            <option value="PICKUP">PICKUP</option>

                            <option value="SEDAN">SEDAN</option>

                            <option value="SUV">SUV</option>

                            <option value="VAN">VAN</option>

                        </select>


                    </div>
                </td>
            </tr>
            <tr id="field_object_vincode_<%= count %>" class="field ">
                <td class="">
                    <div class="label ">
                        <span class="text-danger icon-asterix"></span>
                        <span class="title">VIN code</span>
                    </div>
                </td>
                <td>
                    <div class="element">
                        <input type="text" id="object_vincode_<%= count %>" name="object_vincode[]" value="" autocomplete="off" maxlength="32" minlength="6" class="form-control">
                    </div>
                </td>
            </tr>
            <tr id="field_object_usage_<%= count %>" class="field ">
                <td class="">
                    <div class="label ">
                        <span class="text-danger icon-asterix"></span>
                        <span class="title">Usage</span>
                    </div>
                </td>
                <td>
                    <div class="element">
                        <select id="object_usage_<%= count %>" name="object_usage[]" class="form-control">
                            <option value="">--- select from here ---</option>
                            <option value="Private">Private</option>

                            <option value="Commercial">Commercial</option>

                            <option value="Other">Other</option>

                        </select>


                    </div>
                </td>
            </tr>
            <tr id="field_object_number_of_passengers_<%= count %>" class="field " >
                <td class="">
                    <div class="label ">
                        <span class="text-danger icon-asterix"></span>
                        <span class="title">CAR NUMBER OF PASSENGERS</span>
                    </div>
                </td>
                <td>
                    <div class="element">
                        <input type="text" id="object_number_of_passengers_<%= count %>" name="object_number_of_passengers[]" value="" autocomplete="off" data-m-dec="0" class="form-control">
                    </div>
                </td>
            </tr>
            <tr id="field_object_power_<%= count %>" class="field ">
                <td class="">
                    <div class="label ">
                        <span class="text-danger "></span>
                        <span class="title">Power (kw)</span>
                    </div>
                </td>
                <td>
                    <div class="element">
                        <input type="text" id="object_power_<%= count %>" name="object_power[]" value="" autocomplete="off" data-m-dec="0" class="form-control">
                    </div>
                </td>
            </tr>
            <tr id="field_object_gross_weight_<%= count %>" class="field ">
                <td class="">
                    <div class="label ">
                        <span class="text-danger icon-asterix"></span>
                        <span class="title">Gross weight (kg)</span>
                    </div>
                </td>
                <td>
                    <div class="element">
                        <input type="text" id="object_gross_weight_<%= count %>" name="object_gross_weight[]" value="" autocomplete="off" data-m-dec="0" class="form-control">
                    </div>
                </td>
            </tr>
                <tr id="field_vehicle_object_coverage_<%= count %>" class="field"><td colspan="2" class="subtitle">Coverage validity</td></tr>
                    <tr id="field_vehicle_object_coverage_begin_<%= count %>" class="field">
                        <td class=""><div class="label"><span class="title">Coverage begin</span></div></td>
                        <td><div class="element"><input type="date" id="object_vehicle_coverage_begin_<%= count %>" name="object_coverage_begin[]" value="" maxlength="10" autocomplete="off" class="datefield form-control" style="margin-right: 0px !important"><div style="float: right; width: 40%; padding-left: 10px; padding-top: 9px; overflow: hidden"><div id="field_object_coverage_comment_<%= count %>"></div></div></div>
                        </td>
                    </tr>
                    <tr id="field_vehicle_object_coverage_end_<%= count %>" class="field">
                        <td class=""><div class="label"><span class="title">Coverage end</span></div></td>
                        <td><div class="element"><input type="date" id="object_object_vehicle_coverage_end_<%= count %>" name="object_coverage_end[]" value="" maxlength="10" autocomplete="off" class="datefield form-control" style="margin-right: 0px !important"><div style="float: right; width: 40%; padding-left: 10px; padding-top: 9px; overflow: hidden"><div id="field_object_coverage_end_comment_<%= count %>"></div></div></div></td>
                    </tr>
        </tbody></table></div></div>
</script>


<script>

$(function () {
    var dibpolicyAddObj = new DibPolicyAdd();
    dibpolicyAddObj.initialSetting();

});
</script>

