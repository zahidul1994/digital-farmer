@php
use NumberToWords\NumberToWords;
@endphp
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html" charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Agent  Slip</title>
    <style media="all">
        
        * {
            margin: 0;
            padding: 0;
            line-height: 1.3;
            color: #333542;
        }

        body {
            font-size: .875rem;
            font-family: "Camber";
            color: #000000 !important;
            margin: 16px 50px !important;
            /* width: 718.11023622px; */
        }

        table {
            width: 100%;
        }

        table th {
            font-weight: normal;
        }

        table.padding th {
            padding: .5rem .7rem;
        }

        /* CUSTOM DESIGN */

        table,
        th,
        td {
            border-collapse: collapse;
        }
        .product_details td,
        .product_details th {
            padding: 8px;
            border-left: 1px solid black;
            border-right: 1px solid black;
        }
        .product_details th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #0A77BA;
            color: white;
        }
        .number-to-word {
            font-size: .625rem;
            padding: 30 1.5rem;
        }

        

    </style>
</head>

<body>
    <div class="invoice" style="text-align: center;">
        <div class="heading">
            <img src="{{ @$supplierDue->admin->profile->company_logo }}" style="height: 100px; width:100px;">
            <div>
                <strong style="font-size: 1.17em;">{{ @$supplierDue->admin->profile->company_name }}</strong>
            </div>
            <div style="margin: 10px 0px; font-size:1.5em;" >
                <div  style="border: 2px solid #0A77BA;border-radius: 10px;padding: 7px 15px; width:30%; margin:auto;"> 
                    <div style="background-color: #0A77BA;color: #eceff4; padding: 3px 25px; width:70%; margin:auto;">Agent Slip</div>
                 </div> 
            </div>
            <div>
                <strong style="font-size: .9rem;">Invoice NO: {{@$supplierDue->invoice_no }}</strong>
            </div>
        </div>
    </div>
    <div style="margin: 20px 0px;">
        <div style="width:100%;">
            <div style="width:40%; float:left;">
                <strong style="font-weight:100;">Date & Time: {{@$supplierDue->created_at}}</strong><br>
                <strong style="font-weight:100;">Company Name: {{ @$supplierDue->admin->profile->company_name }} </strong><br>
                <strong style="font-weight:100;">Address: {{ @$supplierDue->admin->profile->company_address }}  </strong><br>
                <strong style="font-weight:100;">Phone: {{ @$supplierDue->admin->phone }}  </strong><br>
                <strong style="font-weight:100;">Email: {{ @$supplierDue->admin->email }}  </strong><br>

            </div>

            <div style="width:40%;float:right;">
                <strong style="font-weight:100;">Supplier Name : {{ @$supplierDue->supplier->supplier_name }}</strong>
                <br>
                <strong style="font-weight:100;">Supplier NO : {{@$supplierDue->supplier->agent_id }}</strong>
                <br>
                <strong style="font-weight:100;">Phone : {{ @$supplierDue->supplier->phone }}</strong>
                <br>
                <strong style="font-weight:100;">Email : {{ @$supplierDue->supplier->email }}</strong>
                <br>
                <strong style="font-weight:100;">Address : {{ @$supplierDue->supplier->address }}</strong>
                <br>
            </div>
        </div>
    </div>

    <div class="product_details">
        <table>
            <thead>
                <tr>
                <th width="45%">Note </th>
                <th width="15%"> Payment Method </th>
               <th width="40%"> Paid </th>
            
                  
                </tr>
            </thead>
            <tbody>
               
                <tr style="border:1px solid #000;">
                    <td>
                        {{$supplierDue->note}}
                    </td>
                    <td>
                        {{Helper::getPaymentMethodName($supplierDue->payment_method)->payment_name}}
                    </td>
                    <td>
                        {{$supplierDue->paid}}
                    </td>
                    
                   
                   
                </tr>
                 <tr style="border:1px solid #000;">
                    <td colspan="4"> Total Amount In Word :
                      
                        {{ NumberToWords::transformNumber('en', @($supplierDue->paid)) }}  {{ @Helper::setting()->currency_name }}
                    </td>
                   
                </tr>
            </tbody>
        </table>
    </div>
    
    <div class="footer_details" style="padding: 1.5rem 0px; text-align: center;
   margin-top: 10px;">
        <div>
            <strong style="font-weight:100;">Thank You For Receive</strong>
           
        </div>
    </div>
    <div style="width:100%; margin-top:150px;">
        <div style="width:50%; float:left;">
          &nbsp;  &nbsp;  &nbsp;   &nbsp; &nbsp;  &nbsp;   &nbsp; &nbsp;  &nbsp;   &nbsp; &nbsp;  &nbsp;   &nbsp; <strong style=" border-bottom: dotted"> Company Signature:</strong>
        </div>
        <div style="width:50%;float:right">
              &nbsp;  &nbsp;  &nbsp;   &nbsp; &nbsp;  &nbsp;   &nbsp; &nbsp;  &nbsp;   &nbsp; &nbsp;  &nbsp;   &nbsp; <strong style=" border-bottom: dotted"> Supplier Signature:</strong>   
        </div>
    </div>
</body>

</html>
