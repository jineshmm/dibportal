<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Example 2</title>

<link type="text/css" href="{{ public_path('/css/pdf.css') }}" type="text/css" rel="stylesheet" media="all"/>
</head>
<body>
<header class="clearfix" style='width:86%;'>
<div id="logo" >
<img src="{{ public_path('/Images/big_logo.jpg') }}" width='100' height='100'>
</div>
<div id="company" >
<h2 class="name" >Diamond Insurance Broker</h2>
<div >7356 Abdul Aziz Al Uraifi- Ar Rabi,Unit No 1316, Ar Riyadh 13315</div>
<div >920004778</div>
<div><a href="mailto:info@dbroker.com.sa">info@dbroker.com.sa</a></div>
</div>

</header>
<main style='border-top-color: 1px solid #AAAAAA;width:95%' class='page-break'>
<div id="details" class="clearfix" style='width:95%;height:20px;'>

<div id="invoice" >

<div class="date">Date: <?php echo e(date('Y-m-d H:i')); ?></div>

</div>
</div>     
<table border="0" cellspacing="0" cellpadding="0" style='width:90%;' class='dptable'>

<tbody>
<tr>
<td class="desc"><h3>PROSPECT NAME</h3></td>            
<td class="qty">{!! $formdata['insurer'] !!}</td>
</tr>
<tr>
<td class="desc"><h3>Type</h3></td>            
<td class="qty">Land in transit</td>
</tr>
<tr>
<td class="desc"><h3>Insured</h3></td>            
<td class="qty">{!! $formdata['insurer'] !!}</td>
</tr>
<tr>
<td class="desc"><h3>Business activities</h3></td>            
<td class="qty">{!! $formdata['business_activities'] !!}</td>
</tr>
<tr>
<td class="desc"><h3>Period of insurance</h3></td>            
<td class="qty">{!! $formdata['period_of_insurance'] !!}</td>
</tr>

<tr>
<td class="desc"><h3>Interest</h3></td>            
<td class="qty">{!! $formdata['interest'] !!}</td>
</tr>

<tr>
<td class="desc"><h3>Coverage</h3></td>            
<td class="qty">{!! $formdata['coverage'] !!}</td>
</tr>
<tr>
<td class="desc"><h3>Voyage</h3></td>            
<td class="qty">{!! $formdata['voyage'] !!}</td>
</tr>
<tr>
<td class="desc"><h3>Conveyance(s)</h3></td>            
<td class="qty">{!! $formdata['conveyance'] !!}</td>
</tr>
<tr>
<td class="desc"><h3>Single Carrying Limit</h3></td>            
<td class="qty">{!! $formdata['single_carrying_limit'] !!}</td>
</tr>
<tr>
<td class="desc"><h3>Estimated annual turnover</h3></td>            
<td class="qty">{!! $formdata['estimated_annual_turnover'] !!}</td>
</tr>
<tr>
<td class="desc"><h3>Basis of valuation</h3></td>            
<td class="qty">{!! $formdata['basis_of_valuation'] !!}</td>
</tr>
<tr>
<td class="desc"><h3>Target Rate</h3></td>            
<td class="qty">{!! $formdata['target_rate'] !!}</td>
</tr>
<tr>
<td class="desc"><h3>Deductible(s)</h3></td>            
<td class="qty">{!! $formdata['deductible'] !!}</td>
</tr>
<tr class='last-tr-style'>
<td class="desc"><h3>Brokerage commission</h3></td>            
<td class="qty">{!! $formdata['brokerage_commission'] !!}</td>
</tr>

</tbody>
</table>
<div id="notices" >
<div id="thanks" >Conditions:</div>

<div class="notice" style="margin-top:5px;width:90%">
{!!  $formdata['conditions'] !!}

</div>
</div>
</main>
<footer>
Diamond Insurance Broker
</footer>
</body>
</html>
