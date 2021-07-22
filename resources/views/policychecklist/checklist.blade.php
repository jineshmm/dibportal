
 <table >
  
    <tbody>


    <tr>
        <td  colspan="2" style='text-align:center;background-color: #276ca8;font-size:28;color:#fff'>TECHNICAL DEPARTMENT </td>        
    </tr>
    <tr>
        <td colspan="2"  style='text-align:center;background-color: #276ca8;font-size:28;color:#fff'>POLICY FILE CHECKLIST & SUMMARY</td>
    </tr>
    <tr>
        <td  colspan="2"  style='text-align:center;background-color: #fff;font-size:28;color:#fff'></td>        
    </tr>
    <tr>
        <td  colspan="2"  style='text-align:center;background-color: #276ca8;font-size:28;'>FILE DATA</td>        
    </tr>
    <tr>
        <td   style='text-align:center;background-color: #276ca8;font-size:28;'>POLICY FILE NUMBER</td>
        <td   style='text-align:center;font-size:28;'>{{$file_number}}</td>        
    </tr>
    <tr>
        <td   style='text-align:center;background-color: #276ca8;font-size:28;'>POLICY NUMBER</td>
        <td   style='text-align:center;font-size:28;'>{{$policy_number}}</td>         
    </tr>
    <tr>
        <td   style='text-align:center;background-color: #276ca8;font-size:28;'>INSURER</td>
        <td   style='text-align:center;font-size:28;'>{{$insurer}}</td>          
    </tr>
    <tr>
        <td   style='text-align:center;background-color: #276ca8;font-size:28;'>INSURED</td>
        <td   style='text-align:center;font-size:28;'>{{$insured}}</td>          
    </tr>
    <tr>
        <td   style='text-align:center;background-color: #276ca8;font-size:28;'>POLICY TYPE</td>
        <td   style='text-align:center;font-size:28;'>{{$policy_type}}</td>          
    </tr>
    <tr>
       <td   style='text-align:center;background-color: #276ca8;font-size:28;'>LOB</td>
        <td   style='text-align:center;font-size:28;'>{{$lob}}</td>          
    </tr>
    <tr>
        <td   style='text-align:center;background-color: #276ca8;font-size:28;'>PRODUCT</td>
        <td   style='text-align:center;font-size:28;'>{{$product_name}}</td>      
    </tr>
    <tr>
        <td   style='text-align:center;background-color: #276ca8;font-size:28;'>POLICY ISSUE DATE</td>
        <td   style='text-align:center;font-size:28;'>{{date('d/m/Y',strtotime($issue_date))}}</td>
    </tr>
    <tr>
        <td   style='text-align:center;background-color: #276ca8;font-size:28;'>POLICY INCEPTION DATE</td>
        <td   style='text-align:center;font-size:28;'>{{date('d/m/Y',strtotime($inception_date))}}</td>          
    </tr>
       <tr>
        <td   style='text-align:center;background-color: #276ca8;font-size:28;'>POLICY EXPIRY DATE</td>
        <td   style='text-align:center;font-size:28;color:#fff'>{{date('d/m/Y',strtotime($expiry_date))}}</td>          
    </tr>
    <tr>
        <td  colspan="2"  style='text-align:center;background-color: #276ca8;font-size:28;'>T&C SUMMARY</td>        
    </tr>
    @if($lob_type ==1)
    <tr>
       <td   style='text-align:center;background-color: #276ca8;font-size:28;'>ANNUAL TSI/ LIMIT</td>
        <td   style='text-align:center;font-size:28;'>{{$annual_limit}}</td>
    </tr>
    <tr>
        <td   style='text-align:center;background-color: #276ca8;font-size:28;'>RATES</td>
        <td   style='text-align:center;font-size:28;'>{{$rates}}</td>
    </tr>
    <tr>
        <td   style='text-align:center;background-color: #276ca8;font-size:28;'>DEDUCTIBLE</td>
        <td   style='text-align:center;font-size:28;'>{{$deductible}}</td>
    </tr>
    <tr>
        <td   style='text-align:center;background-color: #276ca8;font-size:28;'>SPECIAL NOTES</td>
        <td   style='text-align:center;font-size:28;'>{{$special_note}}</td>          
    </tr>
 
    
    @elseif($lob_type ==2)
    <tr>
        <td   style='text-align:center;background-color: #276ca8;font-size:28;'>RATES</td>
        <td   style='text-align:center;font-size:28;'>{{$rates}}</td>      
    </tr>
    <tr>
        <td   style='text-align:center;background-color: #276ca8;font-size:28;'>DEDUCTIBLE</td>
        <td   style='text-align:center;font-size:28;'>{{$deductible}}</td>
    </tr>
    <tr>
        <td   style='text-align:center;background-color: #276ca8;font-size:28;word-wrap: break-word;'>PARTIAL LOSS DEPRECIATION</td>
        <td   style='text-align:center;font-size:28;'>{{$partial_depreciation}}</td>
    </tr>
    <tr>
        <td   style='text-align:center;background-color: #276ca8;font-size:28;word-wrap: break-word;'>TOTAL LOSS DEPRECIATION</td>
        <td   style='text-align:center;font-size:28;'>{{$total_loss_depreciation}}</td>          
    </tr>
       <tr>
        <td   style='text-align:center;background-color: #276ca8;font-size:28;'>SPECIAL NOTES</td>
        <td   style='text-align:center;font-size:28;color:#fff'>{{$special_note}}</td>          
    </tr>
    
    @else
    <tr>
       <td   style='text-align:center;background-color: #276ca8;font-size:28;'>CLASS</td>
        <td   style='text-align:center;font-size:28;'>{{$dip_class}}</td>          
    </tr>
    <tr>
        <td   style='text-align:center;background-color: #276ca8;font-size:28;'>RATES</td>
        <td   style='text-align:center;font-size:28;'>{{$rates}}</td>      
    </tr>
    <tr>
        <td   style='text-align:center;background-color: #276ca8;font-size:28;'>DEDUCTIBLE</td>
        <td   style='text-align:center;font-size:28;'>{{$deductible}}</td>
    </tr>
    <tr>
        <td   style='text-align:center;background-color: #276ca8;font-size:28;'>DENTAL LIMIT</td>
        <td   style='text-align:center;font-size:28;'>{{$dental}}</td>          
    </tr>
       <tr>
        <td   style='text-align:center;background-color: #276ca8;font-size:28;'>OPTICAL LIMIT</td>
        <td   style='text-align:center;font-size:28;color:#fff'>{{$opticals}}</td>          
    </tr>
    <tr>
       <td   style='text-align:center;background-color: #276ca8;font-size:28;'>MATERNITY LIMIT</td>
        <td   style='text-align:center;font-size:28;'>{{$maternity}}</td>          
    </tr>
    <tr>
        <td   style='text-align:center;background-color: #276ca8;font-size:28;'>PARENTS</td>
        <td   style='text-align:center;font-size:28;'>{{$parents}}</td>      
    </tr>
    <tr>
        <td   style='text-align:center;background-color: #276ca8;font-size:28;'>SPECIAL NOTES</td>
        <td   style='text-align:center;font-size:28;'>{{$special_note}}</td>
    </tr>
    
    
    @endif
    
    <tr>
        <td  colspan="2"  style='text-align:center;background-color: #276ca8;font-size:28;'>FILE DOCUMENTS</td>        
    </tr>
    
    
    <tr>
       <td   style='text-align:center;background-color: #276ca8;font-size:28;'>UW DOCS</td>
        <td   style='text-align:center;font-size:28;'>{{$uw_docs ? 'Yes':'No'}}</td>          
    </tr>
    <tr>
        <td   style='text-align:center;background-color: #276ca8;font-size:28;'>QUOTES</td>
        <td   style='text-align:center;font-size:28;'>{{$quotes ? 'Yes':'No'}}</td>      
    </tr>
    <tr>
        <td   style='text-align:center;background-color: #276ca8;font-size:28;'>ISSUANCE DOCS</td>
        <td   style='text-align:center;font-size:28;'>{{$issuance ? 'Yes':'No'}}</td>
    </tr>
    <tr>
        <td   style='text-align:center;background-color: #276ca8;font-size:28;'>POLICY DOCS</td>
        <td   style='text-align:center;font-size:28;'>{{$policy ? 'Yes':'No'}}</td>          
    </tr>
       <tr>
        <td   style='text-align:center;background-color: #276ca8;font-size:28;'>SPECIAL NOTES</td>
        <td   style='text-align:center;font-size:28;color:#fff'>{{$special ? 'Yes':'No'}}</td>          
    </tr>
    <tr>
       <td   style='text-align:center;background-color: #276ca8;font-size:28;'>PAYMENT</td>
        <td   style='text-align:center;font-size:28;'>{{$payment ? 'Yes':'No'}}</td>          
    </tr>
    <tr>
        <td   style='text-align:center;background-color: #276ca8;font-size:28;'>ANNOUNCEMENT EMAIL</td>
        <td   style='text-align:center;font-size:28;'>{{$announce ? 'Yes':'No'}}</td>      
    </tr>
    <tr>
        <td   style='text-align:center;background-color: #276ca8;font-size:28;'>INSLY</td>
        <td   style='text-align:center;font-size:28;'>{{$insly? 'Yes':'No'}}</td>
    </tr>
    <tr>
       <td   style='text-align:center;background-color: #276ca8;font-size:28;'>FILE CREATED ON</td>
        <td   style='text-align:center;font-size:28;'>{{date('d/m/Y')}}</td>          
    </tr>
    <tr>
        <td   style='text-align:center;background-color: #276ca8;font-size:28;'>FILE CREATED BY</td>
        <td   style='text-align:center;font-size:28;'></td>      
    </tr>
    <tr>
        <td   style='text-align:center;background-color: #276ca8;font-size:28;'>FILE APPROVED BY</td>
        <td   style='text-align:center;font-size:28;'></td>
    </tr>
    
    
    

    </tbody>
</table>
