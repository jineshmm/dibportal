<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<link type="text/css" href="{{ public_path('/css/pdf.css') }}" type="text/css" rel="stylesheet" media="all"/>
<style>
  .product_details table tr td:first-child {
   background-color:#1f4e79;
   color:#fff;
   font-weight:bold;
}



.product_details table {
  width: 90%;
  border-collapse: collapse;
  border-spacing: 0;
  margin-bottom: 20px;
  color:#007da0;
}

.product_details table th,
.product_details table td {
/*  background: #FFF;*/
  text-align: center;
  border:1px solid #007DA0; 

}

.product_details table th {
  white-space: nowrap;        
  font-weight: normal;
}

.product_details table td {
  text-align: left;
}


.product_details table tbody tr:last-child td {
  border: 1px solid #007DA0; 
}


.product_details table tfoot tr:first-child td {
  border-top: 1px solid #007DA0; 
}

.product_details table tfoot tr:last-child td {
  color: #007DA0;
  font-size: 1.4em;
  border-top: 1px solid #007DA0; 

}

.product_details table tfoot tr td:first-child {
  border: 1px solid #007DA0; 
}



    
</style>
</head>
<body style='border:3px solid #007DA0;width:100%;border-style: groove;margin:0;top:0'>
<header class="clearfix" style='width:100%;margin-left:25px;height:85px'>
<div id="logo" >

<img src="{{ public_path('/Images/big_logo.jpg') }}" width='150' height='100'>
</div>
<div id="company" style='width:100%;margin-right:0px;text-align:right;'>
<h4 class="name" style='margin-bottom:0'>Diamond Insurance Broker</h4>
<div style='margin-top:0'>7356 Abdul Aziz Al Uraifi- Ar Rabi,Unit No 1316, Ar Riyadh 13315</div>
<div>920004778</div>
<div><a href="mailto:info@dbroker.com.sa" style='text-decoration: none;color:#000'>info@dbroker.com.sa</a></div>
</div>
</header>
    
<main style='border-top-color: 1px solid #AAAAAA;margin-top:100px;width:100%;' class='page-break'>
<div id="details" class="clearfix" style='width:95%;height:190px;'>
    <div style='width:95%;text-align:right;color:#007da0;margin-top:10px'>Date:{{date('Y-m-d')}}</div>
<div  style="float:left;text-align:center;width:90%;margin-bottom:20px;margin-left:60px;">

<div class='name' style='font-weight:bold;font-size:16'></div>
<div  style="margin-top:20px;height:50px;margin-bottom:10px;">@if( $formdata['imagepath'] !='' )<img src="{{ public_path($formdata['imagepath']) }}" height='75' width='75'> @endif</div>
<div style='font-weight:bold;font-size:16;margin-top:15px;color:#007da0;padding-top:15px' >Requested Terms for  </div>
<div style="margin-top:30px;font-weight:bold;font-size:18;color:#007da0">{{$formdata['insurer'] }} </div>
<div  style="font-weight:bold;margin-top:10px;font-size:16;color:#007da0">Medical Broking Slip</div>
</div>
</div>  
    <div style="margin-top:0px;margin-left:10px;width:95%; color:#007da0;margin-top:15px;padding-top: 10px">
<div style="font-size:12">We Kindly requesting you to submit your quotation in 2 working days for our client {{$formdata['insurer'] }} based on the following benefits in this slip.</div>
<div style="font-size:12;margin-top:30px">Thank you for your attention to this request.</div>
<div style="font-size:12;margin-top:5px;text-align:right;width:95%">Diamond Insurance Broker team.</div>
</div>  
    <div style='width:100%;margin-top:100px;page-break-before:always'>   
<div style="margin-left:10px;width:100%;" class='product_details'>
<div style='width:100%;'>
<div style='width:100%;text-align:center;font-size:11;color:#007da0;font-weight: bold;margin-bottom:10px'>Product Details</div>
{!! $formdata['product_details'] !!}


</div>
</div>
        @if($formdata['benefits'] !='')
<div style="margin-top:0px;margin-left:10px;width:100%;" class='product_details'>
<div style='width:100%'><div style='width:100%;text-align:center;font-size:11;color:#007da0;font-weight: bold;margin-bottom:10px'>Requested Benefits</div>{!! $formdata['benefits'] !!}


</div>
</div>
 @endif
  @if($formdata['brokerage_info'] !='')
        <div style="margin-top:0px;margin-left:10px;width:100%;" class='product_details'>
            <div style='width:100%'><div style='width:100%;text-align:center;font-size:11;color:#007da0;font-weight: bold;margin-bottom:10px'>Brokerage Info</div>{!! $formdata['brokerage_info'] !!}


</div>
</div>
        
@endif
    </div>
</main>
<footer style='border-top: none;bottom: 21px;left:20px'>
    <span style='text-align:right'>Diamond Insurance Broker</span>
</footer>
</body>
</html>