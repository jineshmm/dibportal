<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<link type="text/css" href="{{ public_path('/css/pdf.css') }}" type="text/css" rel="stylesheet" media="all"/>
<style>
/*  table {
  border-collapse: collapse;
}

table, th, td {
  border: 1px solid black;
}  */
    
</style>
</head>
<body style='border:3px solid #007DA0;width:100%;border-style: groove;margin:0;top:0'>
<header class="clearfix" style='width:85%;margin-left:25px;height:85px'>
<div id="logo" >
<img src="{{ public_path('/Images/big_logo.jpg') }}" width='100' height='100'>
</div>
<div id="company" style='width:80%;margin-right:0px;text-align:right'>
<h2 class="name" >Diamond Insurance Broker</h2>
<div >7356 Abdul Aziz Al Uraifi- Ar Rabi,Unit No 1316, Ar Riyadh 13315</div>
<div >920004778</div>
<div><a href="mailto:info@dbroker.com.sa">info@dbroker.com.sa</a></div>
</div>

</header>
<main style='border-top-color: 1px solid #AAAAAA;margin-top:100px;width:100%;' class='page-break'>
<div id="details" class="clearfix" style='width:95%;height:275px;'>

<div  style="float:left;text-align:center;width:100%;margin-bottom:20px;margin-left:60px;">

<div class='name' style='font-weight:bold;font-size:16'>MOTOR TPL TERMS</div>
<div  style="margin-top:20px;height:50px;"><img src="{{$formdata['imagepath'] }}" height='50'></div>
<div style='font-weight:bold;font-size:15;margin-top:10px'>REQUESTED MOTOR TPL TERMS </div>
<div style="margin-top:30px;font-weight:bold;font-size:15">FOR </div>
<div style="margin-top:30px;font-weight:bold;font-size:15">{{$formdata['insurer'] }} </div>
<div  style="font-weight:bold">{{ date('j F Y') }}</div>
</div>
</div>  
    <div style="margin-top:0px;margin-left:10px;width:100%;">
<div style="font-size:14">Please provide us with your competitive quotation based on the following:</div>
<div style="text-align:left;margin-top:20px;font-size:14">Insured: {{$formdata['insurer'] }} <br/>
Period of Insurance:  {{$formdata['period_of_insurance'] }}</div>
</div>  
<table border="0" cellspacing="0" cellpadding="0" style='width:100%;'  class='dptable'>

<tbody>
<tr>
<td class="desc"><h3>Cover</h3></td>            
<td class="qty">{!! $formdata['coverage'] !!}</td>
</tr>
<tr>
<td class="desc"><h3>Interest</h3></td>            
<td class="qty">{!! $formdata['interest'] !!} </td>
</tr>
<tr>
<td class="desc"><h3>Territory</h3></td>            
<td class="qty">{!! $formdata['territorial_limit'] !!}</td>
</tr>
<tr>
<td class="desc"><h3>Jurisdiction</h3></td>            
<td class="qty">{!! $formdata['applicable_law'] !!}</td>
</tr>
<tr>
<td class="desc"><h3>RATES</h3></td>            
<td class="qty">{!! $formdata['rate'] !!}</td>
</tr>
<tr class='last-tr-style'>
<td class="desc"><h3>Brokerage Commission</h3></td>            
<td class="qty">{!! $formdata['brokerage_commission'] !!}</td>
</tr>

</tbody>
</table>

</main>
<footer style='border-top: none;bottom: 21px;left:20px'>
    <span style='text-align:right'>Diamond Insurance Broker</span>
</footer>
</body>
</html>