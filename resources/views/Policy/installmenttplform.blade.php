<table class="insly-form panel-body" id='dpib_installment_table'>
    <tbody>
								<tr id="field_policy_installments" class="field">
                                                                    <td><div class="label">Installments</div></td>
                                                                    <td colspan="7"><div class="element">
                                                                            <select id="policy_installments" name="policy_installments" style="width: 500px" class="form-control">
                                                                                <option value="0" {{($installmentnumber =='')}}>0</option>
                                                                                @for ($i = 0; $i < 13; $i++)
                                                                                  @php
                                                                                      $selected = '';
                                                                                  @endphp
                                                                                @if($installmentnumber == $i)
                                                                                    @php
                                                                                      $selected = 'selected=selected';
                                                                                  @endphp
                                                                                
                                                                                @endif
                                                                                <option value="{{$i}}" {{$selected }}>{{$i}}</option>
                                                                                @endfor
                                                                            </select>
                                                                            <button type="button" id="dip_installment_generate" class="btn waves-effect waves-light btn-outline-primary" openUrl="{{route("generateinstallment")}}">Generate installment schedule</button>
                                                                        </div></td>
                                                                </tr>
                                                                <tr class="installmentschedulerow" style="height: 30px">
                                                                    <th style="font-weight: bold"></th>
                                                                    <th style="width: 10%; text-align: left; font-weight: bold">Date</th>
                                                                    <th style=" text-align: left; font-weight: bold">End date</th>
                                                                    <th style=" text-align: left; font-weight: bold">Due date</th>
                                                                    <th style="width: 35%; text-align: left; font-weight: bold">Sum</th>
                                                                    <th style="width: 7%; text-align: left; font-weight: bold">Paid</th>
                                                                    <th style="width: 30%; text-align: left; font-weight: bold">Paid amount</th>
                                                                   
                                                                </tr>
                                                                @foreach ( $installments as $key =>$installment)
                                                                <tr class="field installmentschedulerow">
                                                                    <td><input type="hidden" id="installmentschedule_num_{{$key}}" name="installmentschedule_num[{{$key}}]" value="{{$key}}">
                                                                        <input type="hidden" name="installmentschedule_vatpercentage[{{$key}}]" value="{{$installment['vat_percentage']}}" />
                                                                        <input type="hidden" name="installmentschedule_vatamount[{{$key}}]" value="{{$installment['vat_amount']}}" />
                                                                        <div class="label">Installment #{{$key}}:</div>
                                                                    </td>
                                                                    <td class="element" style="text-align: center"><input type="date" autocomplete="off" class="datefield form-control" maxlength="10" id="installmentschedule_date_{{$key}}" name="installmentschedule_date[{{$key}}]" value="{{ date('Y-m-d', strtotime($installment['startDate'])) }}"></td>
                                                                    <td class="element" style="text-align: left"><input type="date" autocomplete="off" class="datefield form-control" maxlength="10" id="installmentschedule_date_end_{{$key}}" name="installmentschedule_date_end[{{$key}}]" value="{{ date('Y-m-d', strtotime($installment['endDate'])) }}">
                                                                    </td>
                                                                    <td class="element" style="text-align: left"><input type="date" autocomplete="off" class="datefield form-control" maxlength="10" id="installmentschedule_date_due_{{$key}}" name="installmentschedule_date_due[{{$key}}]" value="{{ date('Y-m-d', strtotime($installment['dueDate'])) }}">
                                                                    </td>
                                                                    <td class="element" style="text-align: left"><input type="text" autocomplete="off" class="numberfield installment_sum form-control" onkeyup="POLICY.ADD.checkInstallmentSum();" style="width: 50% !important" maxlength="10" id="installmentschedule_sum_{{$key}}" name="installmentschedule_sum[{{$key}}]" value="{{  floatval($installment['amount'])}}"><input type="hidden" id="installmentschedule_currency_{{$key}}" name="installmentschedule_currency[{{$key}}]" value="SAR">
                                                                     </td>
                                                                   <!--  <td class="element" style="text-align: left;"> <input type="hidden" name="installmentschedule_paidstatus[{{$key}}]" value="0" class="installment_paid_status"><input type="checkbox" id="installmentschedule_paidstatus_{{$key}}"  value="1" class='dib_select_box custom-control-input'><label class="custom-control-label" for="installmentschedule_paidstatus_{{$key}}"></label>
                                                                    </td> -->
                                                                     <td class="element" style="text-align: left"><input type="checkbox" name="installmentschedule_paidstatus[{{$key}}]" value="0" class="installment_paid_status custom-control-input" id="installmentschedule_paidstatus_{{$key}}"/><label class="custom-control-label"  style="margin-left:27px" for="installmentschedule_paidstatus_{{$key}}"></label>
                                                                     </td>
                                                                     <td class="element" style="text-align: left">
                                                                        <input type="text" autocomplete="off" class="numberfield form-control" style="width: 100% !important" maxlength="10" id="inst_paid_amount_{{$key}}" name="inst_paid_amount[{{$key}}]" value="{{  number_format(0.00, 2, '.',',')}}">
                                                                     </td>

                                                                </tr>
                                                                @endforeach
                                                                <tr class="field installmentschedulerow"><td style="text-align: right"><div class="label">Total: </div></td><td class="element" style="text-align:center;color:#aa0000;line-height:32px;" colspan="3"><div class="element"><b class="hidden" id="installment_error">Sum of installments does not match the signed premium</b></div></td><td style="text-align:center"><div class="element"><b id="installmentTotalSum">{{ number_format($totalPremium, 2, '.',',')  }}</b> <b>SAR</b></div></td><td class="field"></td></tr>
    </tbody>
						</table>