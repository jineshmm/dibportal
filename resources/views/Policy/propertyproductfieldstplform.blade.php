<div class="panel-heading"><h3 class="panel-title">{{$formtitle}}</h3></div>
<div class="panel-body" id="fieldgroup_5dac3deae429d">
    <table class="insly-form">
        <tbody>
            <tr id="field_fire_and_lightening" class="field">
                <td class=""><div class="label full-height" style="height: 39px;"><span class="text-danger icon-asterix"></span><span class="title">Fire and Lightening</span></div></td>
                <td><div class="form-group row pt-3" style="margin-left:10px;">
                          @php
                           $valueArray = array();
                          @endphp
                            
                            @isset($productData->fire_lightening)
                               @php
                                 $valueArray = explode(',', $productData->fire_lightening);
     
                               @endphp
                            @endisset
                            <div class="col-sm-4 custom-control custom-checkbox"><input type="checkbox" class="custom-control-input" id="product_fire_and_lightening_Fire_1" name="product_fire_and_lightening[]" value="Fire" @if(in_array('Fire', $valueArray)) checked='checked'  @endif  >     <label style="width: 30%; cursor: pointer; color: rgb(87, 87, 87);background-color:#fff!important" class="custom-control-label" for="product_fire_and_lightening_Fire_1">Fire</label></div>
                            <div class="col-sm-4 custom-control custom-checkbox"><input type="checkbox" class="custom-control-input" id="product_fire_and_lightening_Lightening_1" name="product_fire_and_lightening[]" value="Lightening" @if(in_array('Lightening', $valueArray)) checked='checked'  @endif > <label style="width: 30%; cursor: pointer; color: rgb(87, 87, 87);background-color:#fff!important" class="custom-control-label" for="product_fire_and_lightening_Lightening_1">Lightening</label></div>
                        </div></td>
            </tr>
            <tr id="field_fire_and_allied_perils" class="field">
                <td class=""><div class="label full-height" style="height: 117px;"><span class="text-danger icon-asterix"></span><span class="title">Fire and Allied Perils</span></div></td>
                <td><div class="form-group row pt-3" style="margin-left:10px;">
                                  @php
                           $valuesArray = array();
                          @endphp
                            
                            @isset($productData->fire_allied_perils)
                               @php
                                 $valuesArray = explode(',', $productData->fire_allied_perils);
     
                               @endphp
                            @endisset 
                            
                            <div class="col-sm-4 custom-control custom-checkbox"><input type="checkbox" id="product_fire_and_allied_perils_Fire" class="custom-control-input" name="product_fire_and_allied_perils[]" value="Fire"  @if(in_array('Fire', $valuesArray)) checked='checked'  @endif > <label style="width: 30%; cursor: pointer; color: rgb(87, 87, 87);background-color:#fff!important" class="custom-control-label" for="product_fire_and_allied_perils_Fire"> Fire</label></div>
                            <div class="col-sm-4 custom-control custom-checkbox"><input type="checkbox" id="product_fire_and_allied_perils_Fire_Lightning" class="custom-control-input" name="product_fire_and_allied_perils[]" value="Fire + Lightning" @if(in_array('Fire + Lightning', $valuesArray)) checked='checked'  @endif > <label style="width: 30%; cursor: pointer; color: rgb(87, 87, 87);background-color:#fff!important" class="custom-control-label" for="product_fire_and_allied_perils_Fire_Lightning"> Fire + Lightning</label></div>
                            <div class="col-sm-4 custom-control custom-checkbox"><input type="checkbox" id="product_fire_and_allied_perils_Fire_Explosion" class="custom-control-input" name="product_fire_and_allied_perils[]" value="Fire + Explosion" @if(in_array('Fire + Explosion', $valuesArray)) checked='checked'  @endif > <label style="width: 30%; cursor: pointer; color: rgb(87, 87, 87);background-color:#fff!important" class="custom-control-label" for="product_fire_and_allied_perils_Fire_Explosion"> Fire + Explosion</label></div>
                            <div class="col-sm-4 custom-control custom-checkbox"><input type="checkbox" id="product_fire_and_allied_perils_Fire_Aircraft damage" class="custom-control-input" name="product_fire_and_allied_perils[]" value="Fire + Aircraft damage" @if(in_array('Fire + Aircraft damage', $valuesArray)) checked='checked'  @endif > <label style="width: 30%; cursor: pointer; color: rgb(87, 87, 87);background-color:#fff!important" class="custom-control-label" for="product_fire_and_allied_perils_Fire_Aircraft damage"> Fire + Aircraft damage</label></div>
                            <div class="col-sm-4 custom-control custom-checkbox"><input type="checkbox" id="product_fire_and_allied_perils_Fire_Earthquake or volcanic eruption" class="custom-control-input" name="product_fire_and_allied_perils[]" value="Fire + Earthquake or volcanic eruption" @if(in_array('Fire + Earthquake or volcanic eruption', $valuesArray)) checked='checked'  @endif > <label style="width: 30%; cursor: pointer; color: rgb(87, 87, 87);background-color:#fff!important" class="custom-control-label" for="product_fire_and_allied_perils_Fire_Earthquake or volcanic eruption"> Fire + Earthquake or volcanic eruption</label></div>
                            <div class="col-sm-4 custom-control custom-checkbox"><input type="checkbox" id="product_fire_and_allied_perils_Fire_Storm and tempest" class="custom-control-input" name="product_fire_and_allied_perils[]" value="Fire + Storm and tempest" @if(in_array('Fire + Storm and tempest', $valuesArray)) checked='checked'  @endif > <label style="width: 30%; cursor: pointer; color: rgb(87, 87, 87);background-color:#fff!important" class="custom-control-label" for="product_fire_and_allied_perils_Fire_Storm and tempest"> Fire + Storm and tempest</label></div>
                            <div class="col-sm-4 custom-control custom-checkbox"><input type="checkbox" id="product_Fire_Flood" name="product_fire_and_allied_perils[]" class="custom-control-input" value="Fire + Flood" @if(in_array('Fire + Flood', $valuesArray)) checked='checked'  @endif > <label style="width: 30%; cursor: pointer; color: rgb(87, 87, 87);background-color:#fff!important" class="custom-control-label" for="product_Fire_Flood"> Fire + Flood</label></div>
                            <div class="col-sm-4 custom-control custom-checkbox"><input type="checkbox" id="product_fire_and_allied_perils_Fire_Impact by any road vehicle or animal" class="custom-control-input" name="product_fire_and_allied_perils[]" value="Fire + Impact by any road vehicle or animal" @if(in_array('Fire + Impact by any road vehicle or animal', $valuesArray)) checked='checked'  @endif ><label style="width: 30%; cursor: pointer; color: rgb(87, 87, 87);background-color:#fff!important" class="custom-control-label" for="product_fire_and_allied_perils_Fire_Impact by any road vehicle or animal"> Fire + Impact by any road vehicle or animal</label></div>
                        </div></td>
            </tr>  
            <tr id="field_property_all_risk" class="field ">
                <td class="">
                    <div class="label ">
                        <span class="text-danger icon-asterix"></span>
                        <span class="title">Property All Risks</span>
                    </div>
                </td>
                <td>
                    <div class="element">
                        <input type="text" id="product_property_all_risk" name="product_property_all_risk" value="{{ isset($productData->property_all_risks) ? $productData->property_all_risks : '' }}" autocomplete="off" maxlength="255" class='form-control'>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>