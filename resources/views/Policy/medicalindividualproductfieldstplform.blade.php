<div class="panel-heading"><h3 class="panel-title">{{$formtitle}}</h3></div>
<div class="panel-body" id="fieldgroup_5dac3deae429d">
    <table class="insly-form"> 
        <tbody><tr id="field_product_name" class="field ">
                <td class="">
                    <div class="label ">
                        <span class="text-danger icon-asterix"></span>
                        <span class="title">Name</span>
                    </div>
                </td>
                <td>
                    <div class="element">
                        <input type="text" id="product_name" name="product_name" value="{{ isset($productData->client_name) ? $productData->client_name : '' }}" autocomplete="off" maxlength="255" class='form-control required' error-message="Name field is mandatory">
                    </div>
                </td>
            </tr>
            <tr id="field_product_id" class="field ">
                <td class="">
                    <div class="label ">
                        <span class="text-danger icon-asterix"></span>
                        <span class="title">ID Number</span>
                    </div>
                </td>
                <td>
                    <div class="element">
                        <input type="text" id="product_id_number" name="product_id_number" value="{{ isset($productData->id_number) ? $productData->id_number : '' }}" autocomplete="off" data-m-dec="0" class='form-control'>
                    </div>
                </td>
            </tr>
            <tr id="field_product_dob" class="field ">
                <td class="">
                    <div class="label ">
                        <span class="text-danger icon-asterix"></span>
                        <span class="title">DOB</span>
                    </div>
                </td>
               
                       <td><div class="element"><input type="text" id="product_dob" name="product_dob" value="{{ isset($productData->dob) ? $productData->dob : '' }}" maxlength="10" autocomplete="off" class="datefield form-control" style="margin-right: 0px !important"><div style="float: right; width: 40%; padding-left: 10px; padding-top: 9px; overflow: hidden"><div id="product_dob_comment"></div></div></div></td> 
                    
            </tr>
            <tr id="field_product_sponsor_number" class="field ">
                <td class="">
                    <div class="label ">
                        <span class="text-danger icon-asterix"></span>
                        <span class="title">Sponsor Number</span>
                    </div>
                </td>
                <td>
                    <div class="element">
                        <input type="text" id="product_sponsor_number" name="product_sponsor_number" value="{{ isset($productData->sponsor_number) ? $productData->sponsor_number : '' }}" autocomplete="off" data-m-dec="0" class='form-control'>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>