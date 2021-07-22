  
                <table class="insly_dialogform"><tbody>
                        <tr id="field_product" class="field"><td><div class="label full-height" >List fields</div></td>
                            <td style="padding-right: 0"><div class="element">
                                    <ul class="checklist">
                                        <label style="width: 99%; color: #000000;pointer-events: none" for="customerName"><input type="checkbox" id="customerName" name="customerName" value="1"  setting-value='{"name":"customerName","rowName":"customerName","title":"Name","linkFlag":true}'><span class="icon-check-empty"></span> Name</label>
                                        <label style="width: 99%; cursor: pointer; color: rgb(87, 87, 87);" for="customer_idcode">
                                            <input type="checkbox" id="customer_idcode" name="customer_idcode" value="1"  setting-value='{"name":"customer_idcode","rowName":"id_code","title":"Id code"}'>
                                            <span class="icon-check-empty "></span> ID code</label>
                                        <label style="width: 99%; cursor: pointer; color: rgb(87, 87, 87);" for="customer_customercode"><input type="checkbox" id="customer_customercode" name="customer_customercode" value="1"  setting-value='{"name":"customer_customercode","rowName":"customer_code","title":"Code"}'><span class="icon-check-empty"></span> Code</label>
                                        <label style="width: 99%; cursor: pointer; color: rgb(87, 87, 87);" for="customer_type"><input type="checkbox" id="customer_type" name="customer_type" value="1"  setting-value='{"name":"customer_type","rowName":"type","manipulationFlag":true,"manipulation":{"0":"Individual","1":"Company"},"title":"Customer type"}'><span class="icon-check-empty"></span> Customer type</label>
                                        <label style="width: 99%; cursor: pointer; color: rgb(87, 87, 87);" for="customer_email"><input type="checkbox" id="customer_email" name="customer_email" value="1"  setting-value='{"name":"customer_email","rowName":"email","title":"E-mail address"}'><span class="icon-check-empty"></span> E-mail address</label>
                                        <label style="width: 99%; cursor: pointer; color: rgb(87, 87, 87);" for="customer_phone"><input type="checkbox" id="customer_phone" name="customer_phone" value="1"  setting-value='{"name":"customer_phone","rowName":"phone","title":"Phone"}'><span class="icon-check-empty"></span> Phone</label>
                                        <label style="width: 99%; cursor: pointer; color: rgb(87, 87, 87);" for="customer_url"><input type="checkbox" id="customer_url" name="customer_url" value="1"  setting-value='{"name":"customer_url","rowName":"website","title":"Website"}'><span class="icon-check-empty"></span> Website</label>
                                        <label style="width: 99%; cursor: pointer; color: rgb(87, 87, 87);" for="saleschannel_name"><input type="checkbox" id="saleschannel_name" name="saleschannel_name" value="1" setting-value='{"name":"saleschannel_name","rowName":"userName","title":"Account manager"}'><span class="icon-check-empty"></span> Account manager</label>
                                        <label style="width: 99%; cursor: pointer; color: rgb(87, 87, 87);" for="created_date"><input type="checkbox" id="created_date" name="created_date" value="1"  setting-value='{"name":"created_date","rowName":"created_at","title":"Date"}'><span class="icon-check-empty"></span>Date </label>
                                        <label style="width: 99%; cursor: pointer; color: rgb(87, 87, 87);" for="customergroup_name"><input type="checkbox" id="customergroup_name" name="customergroup_name" value="1" setting-value='{"name":"customergroup_name","rowName":"customer_group","title":"Customer group"}'><span class="icon-check-empty"></span> Customer group</label>
                                        
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

       
<script>
var selectedColumns = [];
   if (localStorage.getItem("customerColumnSetting_"+{{ Auth::user()->id }}) === null) {
 
      selectedColumns = [{"name":"customerName","rowName":"customerName","title":"Name","linkFlag":true},
         {"name":"customer_idcode","rowName":"id_code","title":"Id code"},
         {"name":"customer_customercode","rowName":"customer_code","title":"Code"},
         {"name":"customer_type","rowName":"type","manipulationFlag":true,"manipulation":{0:"Individual",1:"Company"},"title":"Customer type"},
         {"name":"customer_email","rowName":"email","title":"E-mail address"},
         {"name":"customer_phone","rowName":"phone","title":"Phone"},
         {"name":"customer_url","rowName":"website","title":"Website"},
         {"name":"saleschannel_name","rowName":"userName","title":"Account manager"},
         {"name":"customergroup_name","rowName":"customer_group","title":"Date"}];
     localStorage.setItem("customerColumnSetting_"+{{ Auth::user()->id }},JSON.stringify(selectedColumns));
} else {
   selectedColumns = $.parseJSON(localStorage.getItem("customerColumnSetting_"+{{ Auth::user()->id }}));
}

$.each(selectedColumns,function(key,value){   
   $("ul.checklist").find("[id='"+value.name+"']").attr('checked',true);
   $("ul.checklist").find("[for='"+value.name+"']").find('span').addClass('icon-check');
  //$("ul.checklist").find("[for='"+value.name+"']")
    
})

</script>
   