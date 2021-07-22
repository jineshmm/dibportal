@php
    $companyIndex = 0;
@endphp

@foreach($companyDetails as $key=>$companyData)

@php
    $companyIndex = $key;
@endphp
 <table>
  
    <tbody>
    <tr>
        <td  colspan=15 style='text-align:center;background-color: #03a9f3;font-size:28;color:#fff'>{{ strtoupper($companyData)}}</td>        
    </tr>
@foreach($requestDatas[$companyIndex] as $rkey=>$requestData)
    @if($rkey==0)
    <tr>
        @foreach($requestData as $mIndex=>$insuranceClass)
            @if($mIndex==0)
                <td style="background-color:#002db3;color:#fff">{{ ucfirst($insuranceClass) }}</td>
            @else
             <td colspan="3" style="background-color:#002db3;color:#fff">{{ucfirst($insuranceClass)}}</td>
            @endif

        @endforeach
        <td rowspan="2" style="text-align:center;background-color:#03a9f3;color:#fff;" valign="middle">TOTAL MEMBERS</td>
        <td rowspan="2"  style="text-align:center;background-color:#03a9f3;color:#fff;" valign="middle">TOTAL PREMIUM</td>
    </tr>
    @elseif($rkey==1)
    <tr>

        @foreach($requestData as $nIndex =>$categoryData)
            @if($nIndex==0)
                <td style="background-color:#002db3;color:#fff;">{{ ucfirst($categoryData)}}</td>
            @else
             <td style="background-color:#03a9f3;color:#fff;"> {{ucfirst($categoryData)}}</td>
            @endif
        
        @endforeach

        
    </tr>
    @else
    <tr>
@php
    
$valueIndex =0 ;
@endphp
         @foreach($requestData as $valueData)
            @if($valueIndex==0)
                <td style="background-color:#002db3;color:#fff;">{{$valueData }}</td>
            @else
             <td>{{$valueData}}</td>
            @endif
        @php
    
$valueIndex++ ;
@endphp
        @endforeach
        
    </tr>
    @endif
@endforeach

{{-- Total count and premium row categorywise --}}

<tr>
    <td style='background-color:#03a9f3'>TOTAL</td>
    @foreach($totalCategoryCountDetails[$companyIndex] as $cIndex =>$details)
    <td style='background-color:#70db70'>{{$details}}</td>
    <td colspan='2' style='background-color:#70db70'>SAR {{ number_format($totalCategoryPremiumDetails[$companyIndex][$cIndex],2)}}</td>
    @endforeach
   <td style='background-color:#70db70'>{{$grandTotalMember[$companyIndex]}}</td> 
   <td style='background-color:#70db70'>SAR {{ number_format($grandTotalPremium[$companyIndex],2) }}</td>
</tr>
{{-- Vat calculation row categorywise --}}
<tr >
    @php
     $colspan = (count($totalCategoryCountDetails[$companyIndex])*3)+1;  
    @endphp
    <td colspan="{{$colspan}}" style='background-color:#A0A0A0;color:#fff;text-align:right'><b>VAT</b></td>    
   <td style='background-color:#A0A0A0;color:#fff;'><b>5%</b></td> 
   <td style='background-color:#A0A0A0;color:#fff;'><b>SAR {{  number_format((($grandTotalPremium[$companyIndex] * 5)/100), 2)  }}</b></td>
</tr>

{{-- Final row calculation --}}
<tr >
    @php
     $colspan = (count($totalCategoryCountDetails[$companyIndex])*3)+2;  
    @endphp
      
   <td colspan="{{$colspan}}" style='background-color:#ffff00;text-align:right'><b>TOTAL</b> </td> 
   <td style='background-color:#ffff00;text-align:right'><b>

    SAR {{  number_format( ((($grandTotalPremium[$companyIndex] * 5)/100)+$grandTotalPremium[$companyIndex]) , 2)   }}</b>
   </td>
</tr>

    </tbody>
</table>
@endforeach