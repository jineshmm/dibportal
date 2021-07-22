<div class="panel-heading"><h3 class="panel-title">{{$formtitle}}</h3></div>
   @if (isset($objdata))

                        {{ Form::open(array('route' => array('updateobjectdata',$objdata->policy_id, $objdata->id),'name' => 'objectForm','id'=>'objectForm') ) }}
     @elseif (isset($objdata) && count($objdata) ==0)                  
                    {{ Form::open(array('route' => array('createnewobject',$policyId),'name' => 'objectForm','id'=>'objectForm') ) }}
        @endif                      
                       
                        @endif
<div class="panel-body" id="fieldgroup_property">
    <table class="insly-form">
        <tbody>

            <tr id="field_object_address" class="field ">
                <td class="">
                    <div class="label ">
                        <span class="text-danger icon-asterix"></span>
                        <span class="title">Address</span>
                        <input type="hidden" id="object_type" name="object_type" value="property">
                    </div>
                </td>
                <td>
                    <div class="element">
                        <input type="text" id="object_address" name="object_address" value="{{ isset($objdata->address) ? $objdata->address : '' }}" autocomplete="off" maxlength="100" class='form-control'>
                    </div>
                </td>
            </tr>
            <tr id="field_object_property_type" class="field ">
                <td class="">
                    <div class="label ">
                        <span class="text-danger icon-asterix"></span>
                        <span class="title">Property type</span>
                    </div>
                </td>
                <td>
                    <div class="element">
                        <select id="object_property_type" name="object_property_type" class='form-control'>
                            <option value="">--- select from here ---</option>
                            <option value="house" {{ isset($objdata->property_type) ? (($objdata->property_type=="house")? 'selected':''): '' }}>house</option>

                            <option value="duplex" {{ isset($objdata->property_type) ? (($objdata->property_type=="duplex")? 'selected':''): '' }}>duplex</option>

                            <option value="apartment" {{ isset($objdata->property_type) ? (($objdata->property_type=="apartment")? 'selected':''): '' }}>apartment</option>

                            <option value="condominium" {{ isset($objdata->property_type) ? (($objdata->property_type=="condominium")? 'selected':''): '' }}>condominium</option>

                        </select>


                    </div>
                </td>
            </tr>
            <tr id="field_object_yearbuilt" class="field ">
                <td class="">
                    <div class="label ">
                        <span class="text-danger "></span>
                        <span class="title">Year built</span>
                    </div>
                </td>
                <td>
                    <div class="element">
                        <input type="text" id="object_yearbuilt" name="object_yearbuilt" value="{{ isset($objdata->year_built) ? $objdata->year_built : '' }}" autocomplete="off" data-m-dec="0" class='form-control'>
                    </div>
                </td>
            </tr>
            <tr id="field_object_area" class="field ">
                <td class="">
                    <div class="label ">
                        <span class="text-danger "></span>
                        <span class="title">Area</span>
                    </div>
                </td>
                <td>
                    <div class="element">
                        <input type="text" id="object_area" name="object_area" value="{{ isset($objdata->area) ? $objdata->area : '' }}" autocomplete="off" data-m-dec="0" class='form-control'>
                    </div>
                </td>
            </tr>
            <tr id="field_object_construction_material" class="field ">
                <td class="">
                    <div class="label ">
                        <span class="text-danger "></span>
                        <span class="title">Construction material</span>
                    </div>
                </td>
                <td>
                    <div class="element">
                        <select id="object_construction_material" name="object_construction_material" class='form-control'>
                            <option value="">--- select from here ---</option>
                            <option value="brick/concrete" {{ isset($objdata->construction_material) ? (($objdata->construction_material=="brick/concrete")? 'selected':''): '' }}>brick/concrete</option>

                            <option value="wood" {{ isset($objdata->construction_material) ? (($objdata->construction_material=="wood")? 'selected':''): '' }}>wood</option>

                            <option value="mixed" {{ isset($objdata->construction_material) ? (($objdata->construction_material=="mixed")? 'selected':''): '' }}>mixed</option>

                        </select>


                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>
@if (isset($objdata))

                        {{ Form::close() }}
                        
           @endif 