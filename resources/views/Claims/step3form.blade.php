<div class="insly-form">



    <div class="panel panel-default panel-dark">
        <div class="panel-heading" id="fieldgroup_title_claimant">
            <!-- <div class="blocktitle"> -->
            <h3 class="panel-title">Claimant info</h3>
            <!-- </div> -->
        </div>
        <div class="panel-body" id="fieldgroup_claimant">
            <table class="insly-form">
                <tbody>
                    <tr id="field_prop_policyholder_is_claimant" class="field ">
                        <td class="">
                            <div class="label ">
                                <span class="text-danger "></span>
                                <span class="title">Policyholder is the claimant</span>
                            </div>
                        </td>
                        <td>
                            <div class="element custom-control custom-checkbox">
                   
                                
                                    <input type="checkbox" id="policyholder_is_claimant" name="policyholder_is_claimant" value="0" class='custom-control-input'>
                                                        
                                <label class="custom-control-label" style="cursor: pointer; color: rgb(87, 87, 87);" for='policyholder_is_claimant'>yes</label>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>


    <div class="panel panel-default panel-dark">
        <div class="panel-heading" id="fieldgroup_title_claimants">
            <!-- <div class="blocktitle"> -->
            <h3 class="panel-title">Claimant(s)</h3>
            <!-- </div> -->
        </div>
        <div class="panel-body" id="fieldgroup_claimants">
            <table class="insly-form">
                <tbody id="claimant_object_append_area">
                    <tr id="field_add_claimants" class="field">
                        <td colspan="2"  style="padding: 8px; text-align: center">                           
                            
                            {{ Form::select('claimanttype',  $claimType, '',array('name'=>'claimanttype','id' =>'claimanttype','required'=>'required','class'=>'required form-control','error-message' =>"Insurance company field is mandatory"))}}

                            <button type="button" class="dpib_add_claimant btn waves-effect waves-light btn-outline-primary">Add claimant</button>
                        </td>
                    </tr>							</tbody>
            </table>
        </div>
    </div>





</div>

<script id='claimant_object_template' type='text/html'>

   <div id="panel_form_<%- randomNum %>" class="panel panel-default open">
       <div class="panel-heading"><ul class="panel-actions list-inline pull-right">
               <li class="dpib_remove_claimant_info" remove_id="panel_form_<%- randomNum %>">
                   <span class="icon-delete" data-toggle="tooltip" title="Remove Claimant"></span></li></ul>
                   <h3 class="panel-title"><%- title %></h3></div><div id="form_<%- randomNum %>" class="panel-collapse panel-body">
                       <input type="hidden" name="claimant_claimanttype[<%- randomNum %>]" id="claimanttype_<%- randomNum %>" value="">
                       <table class="insly-form">   
                           <tbody>
                               <tr id="field_claimant_<%- randomNum %>_claim_claimant_name" class="field ">
                    <td class="">
    <div class="label ">
                <span class="text-danger icon-asterix"></span>
        <span class="title">Claimant name</span>
    </div>
</td>
                <td>
            <div class="element">
                                                                <input type="text" id="claimant_<%- randomNum %>" name="claimant_claim_claimant_name[<%- randomNum %>]" value="" autocomplete="off" class='form-control'>
                                                                                                                            </div>
        </td>
    </tr>
</tbody></table></div></div>
</script>

<script id='claimant_object_template_medical' type='text/html'>

   <div id="panel_form_<%- randomNum %>" class="panel panel-default open">
       <div class="panel-heading"><ul class="panel-actions list-inline pull-right">
               <li class="dpib_remove_claimant_info" remove_id="panel_form_<%- randomNum %>">
                   <span class="icon-delete" data-toggle="tooltip" title="Remove Claimant"></span></li></ul>
                   <h3 class="panel-title"><%- title %></h3></div><div id="form_<%- randomNum %>" class="panel-collapse panel-body">
                       <input type="hidden" name="claimant_claimanttype[<%- randomNum %>]" id="claimanttype_<%- randomNum %>" value="">
                       <table class="insly-form">   
                           <tbody>
                               <tr id="field_claimant_<%- randomNum %>_claim_claimant_name" class="field ">
                    <td class="">
    <div class="label ">
                <span class="text-danger icon-asterix"></span>
        <span class="title">Claimant name</span>
    </div>
</td>
                <td>
            <div class="element">
                                                                <input type="text" id="claimant_<%- randomNum %>" name="claimant_claim_claimant_name[<%- randomNum %>]" value="" autocomplete="off" class='form-control'>
                                                                                                                            </div>
        </td>
    </tr>
                              <tr id="field_claimant_<%- randomNum %>_claim_claimant_name" class="field ">
                    <td class="">
    <div class="label ">
                <span class="text-danger icon-asterix"></span>
        <span class="title">Id number</span>
    </div>
</td>
                <td>
            <div class="element">
                                                                <input type="text" id="claimant_idnum_<%- randomNum %>" name="claimant_idnumber[<%- randomNum %>]" value="" autocomplete="off" class='form-control'>
                                                                                                                            </div>
        </td>
    </tr>
                              <tr id="field_claimant_<%- randomNum %>_claim_claimant_name" class="field ">
                    <td class="">
    <div class="label">
                <span class="text-danger icon-asterix"></span>
        <span class="title">Membership number</span>
    </div>
</td>
                <td>
            <div class="element">
                                                                <input type="text" id="claimant_membershipnum<%- randomNum %>" name="claimant_membership_number[<%- randomNum %>]" value="" autocomplete="off" class='form-control'>
                                                                                                                            </div>
        </td>
    </tr>

</tbody>
</table>
</div>
</div>
</script>


<script id='claimant_object_template_motor' type='text/html'>

   <div id="panel_form_<%- randomNum %>" class="panel panel-default open">
       <div class="panel-heading"><ul class="panel-actions list-inline pull-right">
               <li class="dpib_remove_claimant_info" remove_id="panel_form_<%- randomNum %>">
                   <span class="icon-delete" data-toggle="tooltip" title="Remove Claimant"></span></li></ul>
                   <h3 class="panel-title"><%- title %></h3></div><div id="form_<%- randomNum %>" class="panel-collapse panel-body">
                       <input type="hidden" name="claimant_claimanttype[<%- randomNum %>]" id="claimanttype_<%- randomNum %>" value="">
                       <table class="insly-form">   
                           <tbody>
                               <tr id="field_claimant_<%- randomNum %>_claim_claimant_name" class="field ">
                    <td class="">
    <div class="label ">
                <span class="text-danger icon-asterix"></span>
        <span class="title">Plate number</span>
    </div>
</td>
                <td>
            <div class="element">
                                                                <input type="text" id="claimant_platenumber<%- randomNum %>" name="claimant_platenumber[<%- randomNum %>]" value="" autocomplete="off" class='form-control'>
                                                                                                                            </div>
        </td>
    </tr>

                                   <tr id="field_claimant_<%- randomNum %>_claim_claimant_name" class="field ">
                    <td class="">
    <div class="label ">
                <span class="text-danger icon-asterix"></span>
        <span class="title">Chase number</span>
    </div>
</td>
                <td>
            <div class="element">
                                                                <input type="text" id="claimant_chasenumber<%- randomNum %>" name="claimant_chasenumber[<%- randomNum %>]" value="" autocomplete="off" class='form-control'>
                                                                                                                            </div>
        </td>
    </tr>

                                   <tr id="field_claimant_<%- randomNum %>_claim_claimant_name" class="field ">
                    <td class="">
    <div class="label ">
                <span class="text-danger icon-asterix"></span>
        <span class="title">Certificate number</span>
    </div>
</td>
                <td>
            <div class="element">
                                                                <input type="text" id="claimant_certificatenumber<%- randomNum %>" name="claimant_certificatenumber[<%- randomNum %>]" value="" autocomplete="off" class='form-control'>
                                                                                                                            </div>
        </td>
    </tr>
</tbody></table></div></div>
</script>