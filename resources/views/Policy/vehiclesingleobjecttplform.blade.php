<div class="panel-heading"><h3 class="panel-title">{{$formtitle}}</h3></div>

<div class="panel-body" id="fieldgroup_vehicle">
    <table class="insly-form">
        <tbody>


            <tr id="field_object_make" class="field ">
                <td class="">
                    <div class="label ">
                        <span class="text-danger icon-asterix"></span>
                        <span class="title">Make</span>
                        <input type="hidden" id="object_type" name="object_type" value="vehicle">
                    </div>
                </td>
                <td>
                    <div class="element">
                        <select id="object_make" name="object_make">
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
            <tr id="field_model" class="field ">
                <td class="">
                    <div class="label ">
                        <span class="text-danger icon-asterix"></span>
                        <span class="title">Model</span>
                    </div>
                </td>
                <td>
                    <div class="element">
                        <input type="text" id="object_model" name="object_model" value="" autocomplete="off" maxlength="255">
                    </div>
                </td>
            </tr>
            <tr id="field_object_year" class="field ">
                <td class="">
                    <div class="label ">
                        <span class="text-danger icon-asterix"></span>
                        <span class="title">Year</span>
                    </div>
                </td>
                <td>
                    <div class="element">
                        <input type="text" id="object_year" name="object_year" value="" autocomplete="off" data-m-dec="0">
                    </div>
                </td>
            </tr>
            <tr id="field_object_license_plate" class="field ">
                <td class="">
                    <div class="label ">
                        <span class="text-danger icon-asterix"></span>
                        <span class="title">License plate</span>
                    </div>
                </td>
                <td>
                    <div class="element">
                        <input type="text" id="object_license_plate" name="object_license_plate" value="" autocomplete="off" maxlength="32">
                    </div>
                </td>
            </tr>
            <tr id="field_object_body_type" class="field ">
                <td class="">
                    <div class="label ">
                        <span class="text-danger icon-asterix"></span>
                        <span class="title">Body Type</span>
                    </div>
                </td>
                <td>
                    <div class="element">
                        <select id="object_body_type" name="object_body_type">
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
            <tr id="field_object_vincode" class="field ">
                <td class="">
                    <div class="label ">
                        <span class="text-danger icon-asterix"></span>
                        <span class="title">VIN code</span>
                    </div>
                </td>
                <td>
                    <div class="element">
                        <input type="text" id="object_vincode" name="object_vincode" value="" autocomplete="off" maxlength="32" minlength="6">
                    </div>
                </td>
            </tr>
            <tr id="field_object_usage" class="field ">
                <td class="">
                    <div class="label ">
                        <span class="text-danger icon-asterix"></span>
                        <span class="title">Usage</span>
                    </div>
                </td>
                <td>
                    <div class="element">
                        <select id="object_usage" name="object_usage">
                            <option value="">--- select from here ---</option>
                            <option value="Private">Private</option>

                            <option value="Commercial">Commercial</option>

                            <option value="Other">Other</option>

                        </select>


                    </div>
                </td>
            </tr>
            <tr id="field_object_number_of_passengers" class="field ">
                <td class="">
                    <div class="label ">
                        <span class="text-danger icon-asterix"></span>
                        <span class="title">CAR NUMBER OF PASSENGERS</span>
                    </div>
                </td>
                <td>
                    <div class="element">
                        <input type="text" id="object_number_of_passengers" name="object_number_of_passengers" value="" autocomplete="off" data-m-dec="0">
                    </div>
                </td>
            </tr>
            <tr id="field_object_power" class="field ">
                <td class="">
                    <div class="label ">
                        <span class="text-danger "></span>
                        <span class="title">Power (kw)</span>
                    </div>
                </td>
                <td>
                    <div class="element">
                        <input type="text" id="object_power" name="object_power" value="" autocomplete="off" data-m-dec="0">
                    </div>
                </td>
            </tr>
            <tr id="field_object_gross_weight" class="field ">
                <td class="">
                    <div class="label ">
                        <span class="text-danger icon-asterix"></span>
                        <span class="title">Gross weight (kg)</span>
                    </div>
                </td>
                <td>
                    <div class="element">
                        <input type="text" id="object_gross_weight" name="object_gross_weight" value="" autocomplete="off" data-m-dec="0">
                    </div>
                </td>
            </tr>
        </tbody></table></div>
