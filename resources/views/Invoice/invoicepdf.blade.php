<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Invoice</title>
    <link rel="stylesheet" href="style.css" media="all" />
    <link type="text/css" href="{{ asset('css/invoicestyle.css') }}" type="text/css" rel="stylesheet" media="all"/>
  </head>
  <body>
    <header class="clearfix">
      <div id="logo">
        <img src="{{ asset('Images/big_logo.jpg') }} ">
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
          <h2 class="name">{{$installmentDetails->name}}</h2>
          <div class="address">{{$installmentDetails->phone}}</div>
          <div class="email"><a href="mailto:john@example.com">{{$installmentDetails->email}}</a></div>
        </div>
        <div id="invoice" >
          <h1>INVOICE </h1>
          <div class="date">Date of Invoice: {{ date('d.m.Y', strtotime($installmentDetails->start_date ))  }}</div>
          <div class="date">Due Date: {{ date('d.m.Y', strtotime($installmentDetails->due_date))  }}</div>
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
        <tbody>
          <tr>          
            <td class="desc">#1</td>
            <td class="unit">{{number_format($installmentDetails->amount, 2, '.', ',')}} SAR </td>
            <td class="unit">{{number_format($installmentDetails->amount, 2, '.', ',')}} SAR </td>
          </tr>          
       
        </tbody>
        <tfoot>
          <tr>
            <td colspan="1"></td>
            <td colspan="1">SUBTOTAL</td>
            <td>{{number_format($installmentDetails->amount, 2, '.', ',')}} SAR </td>
          </tr>
          <tr>
            <td colspan="1"></td>
            <td colspan="1">VAT 5%</td>
            <td>{{number_format($installmentDetails->vat_amount, 2, '.', ',')}} SAR  </td>
          </tr>
          <tr>
            <td colspan="1"></td>
            <td colspan="1">GRAND TOTAL</td>
            <td>{{ number_format(($installmentDetails->vat_amount + $installmentDetails->amount), 2, '.', ',') }} SAR </td>
          </tr>
        </tfoot>
      </table>
<!--      <div id="thanks">Thank you!</div> -->
    </main>

  </body>
</html>