<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Invoice</title> 
    <link type="text/css" href="{{ public_path('/css/invoicestyle.css') }}"  rel="stylesheet" media="all"/>
  </head>
  <body>
    <header class="clearfix">
      <div id="logo">
        <img src="{{ public_path('/Images/big_logo.jpg') }}" width='150' height='100'>
      </div>
 <div id="company" >
<h2 class="name" >Diamond Insurance Broker</h2>
<div >7356 Abdul Aziz Al Uraifi- Ar Rabi,Unit No 1316, Ar Riyadh 13315</div>
<div >920004778</div>
<div><a href="mailto:info@dbroker.com.sa">info@dbroker.com.sa</a></div>
</div>
      </div>
    </header>
    <main style='width:95%; '>
      <div id="details" class="clearfix" style='width:95%'>
        <div id="client" >
          <div class="to">INVOICE TO:</div>
          <h2 class="name">{{$policyDetails['name']}}</h2>
          <div class="address">{{$policyDetails['address']}}</div>
          <div class="email"><a href="mailto:john@example.com">{{$policyDetails['email']}}</a></div>
        </div>
        <div id="invoice" >
          <h1>INVOICE </h1>
          <div class="date">Date of Invoice: {{ date('d.m.Y', strtotime($policyDetails['invoice_date']))  }}</div>
          <div class="date">Due Date: {{ date('d.m.Y', strtotime($policyDetails['due_date']))  }}</div>
          <div class="date">Policy No: {{ $policyDetails['policy_number']  }}</div>
          <div class="date">Policy: {{ ucfirst($policyDetails['productName'])  }}</div>
          
        </div>
      </div>
      <table border="0" cellspacing="0" cellpadding="0">
        <thead>
          <tr>
          
            <th class="desc">DESCRIPTION</th>
            <th class="unit">PRICE</th>
                <th class="unit">SUM</th>
          </tr>
        </thead>
        @php  
$total =0;
$vatTotal = 0;
$grandTotal = 0;
@endphp
        <tbody>
            @foreach($installmentDetails as $key => $installments)
          <tr>          
            <td class="desc">{{$individualInvoiceDetails[$key]['description']}}</td>
            <td class="unit">{{ number_format($installments->amount, 2, '.', ',') }} SAR </td>
            <td class="unit">{{ number_format($installments->amount, 2, '.', ',' )}} SAR </td>
          </tr>
@php  
$total =      floatval($total+$installments->amount);
$vatTotal =  floatval($vatTotal+$installments->vat_amount);
$grandTotal = floatval($grandTotal + $installments->amount +$installments->vat_amount);
@endphp
          @endforeach
        </tbody>
        <tfoot>
          <tr>
            <td colspan="1"></td>
            <td colspan="1">SUBTOTAL</td>
            <td>{{number_format($total, 2, '.', ',')}} SAR </td>
          </tr>
          <tr>
            <td colspan="1"></td>
            <td colspan="1">VAT 5%</td>
            <td>{{ number_format($vatTotal, 2, '.', ',') }} SAR  </td>
          </tr>
          <tr>
            <td colspan="1"></td>
            <td colspan="1">GRAND TOTAL</td>
            <td>{{ number_format($grandTotal, 2, '.', ',') }} SAR </td>
          </tr>
        </tfoot>
      </table>
<!--      <div id="thanks">Thank you!</div> -->
    </main>

  </body>
</html>