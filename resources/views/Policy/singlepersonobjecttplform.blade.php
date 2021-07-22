<div class="panel-heading"><h3 class="panel-title">{{$formtitle}}</h3></div>
       @if (isset($objdata))

                        {{ Form::open(array('route' => array('updateobjectdata',$objdata->policy_id, $objdata->id),'name' => 'objectForm','id'=>'objectForm') ) }}
         @elseif (isset($objdata) && count($objdata) ==0)                  
                    {{ Form::open(array('route' => array('createnewobject',$policyId),'name' => 'objectForm','id'=>'objectForm') ) }}
        @endif                  
                       
                 
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
                        <input type="text" id="object_last_name" name="object_last_name[1]" value="{{ isset($objdata->last_name) ? $objdata->last_name : '' }}" autocomplete="off" maxlength="100" class='form-control'>
                    </div>
                </td>
            </tr>
            <tr id="field_object_middle_name" class="field ">
                <td class="">
                    <div class="label ">
                        <input type="hidden" id="object_type" name="object_type" value="person">
                        <span class="text-danger icon-asterix"></span>
                        <span class="title">First/middle names</span>
                    </div>
                </td>
                <td>
                    <div class="element">
                        <input type="text" id="object_middle_name" name="object_middle_name[1]" value="{{ isset($objdata->first_name) ? $objdata->first_name : '' }}" autocomplete="off" maxlength="100" class='form-control'>
                    </div>
                </td>
            </tr>
            <tr id="field_object_dob" class="field">
                <td class=""><div class="label"><span class="text-danger icon-asterix"></span><span class="title">Date of birth</span></div></td>
                <td><div class="element"><input type="dob" id="object_dob" name="object_dob[1]" value="{{ isset($objdata->dob) ?   date('Y-m-d', strtotime($objdata->dob))   : '' }}" maxlength="10" autocomplete="off" class="datefield form-control" style="margin-right: 0px !important"><div style="float: right; width: 40%; padding-left: 10px; padding-top: 9px; overflow: hidden"><div id="object_dob_comment"></div></div></div>
                </td>
            </tr> 
            <tr id="field_object_gender" class="field ">
                <td class="">
                    <div class="label ">
                        <span class="text-danger "></span>
                        <span class="title">Gender</span>
                    </div>
                </td>
                <td>
                    <div class="element">
                        <select id="object_gender" name="object_gender[1]" class='form-control'>
                            <option value="">--- select from here ---</option>
                            <option value="male" {{ isset($objdata->gender) ? (($objdata->gender=="male")? 'selected':''): '' }}>male</option>

                            <option value="female" {{ isset($objdata->gender) ? (($objdata->gender=="female")? 'selected':''): '' }}>female</option>

                        </select>


                    </div>
                </td>
            </tr>
            <tr id="field_object_coverage" class="field" style="display:none"><td colspan="2" class="subtitle">Coverage validity</td></tr>
                    <tr id="field_object_coverage_begin" class="field" style="display:none">
                        <td class=""><div class="label"><span class="title">Coverage begin</span></div></td>
                        <td><div class="element"><input type="date" id="object_coverage_begin" name="object_coverage_begin[1]" value="{{ isset($objdata->coverage_start_date) ?  date('Y-m-d', strtotime($objdata->coverage_start_date))  : '' }}" maxlength="10" autocomplete="off" class="datefield form-control" style="margin-right: 0px !important"><div style="float: right; width: 40%; padding-left: 10px; padding-top: 9px; overflow: hidden"><div id="field_object_coverage_comment"></div></div></div>
                        </td>
                    </tr>
                    <tr id="field_object_coverage_end" class="field" style="display:none">
                        <td class=""><div class="label"><span class="title">Coverage end</span></div></td>
                        <td><div class="element"><input type="date" id="object_object_coverage_end" name="object_coverage_end[1]" value="{{ isset($objdata->coverage_end_date) ?    date('Y-m-d', strtotime($objdata->coverage_end_date)) : '' }}" maxlength="10" autocomplete="off" class="datefield form-control" style="margin-right: 0px !important"><div style="float: right; width: 40%; padding-left: 10px; padding-top: 9px; overflow: hidden"><div id="field_object_coverage_end_comment"></div></div></div></td>
                    </tr>
        </tbody></table></div>
@if (isset($objdata))

                        {{ Form::close() }}
                        
           @endif 