@php
use NumberToWords\NumberToWords;
@endphp
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html" charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice</title>
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
            margin-top: 20px !important;;
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
            border: 1px solid black;
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
            <div style="margin: 20px 0px;">
                <div style="width:100%;">
                    <div style="width:50%; float:left;">
                        <img src="{{ @Helper::setting()->logo }}" style="height: 100px; width:100px;">
                    </div>
                    <div style="width:50%;float:right;">
                    <img style="height: 100px; width:100px"  src="data:image/png;base64,{{DNS2D::getBarcodePNG(Request::url(), 'QRCODE')}}">
                    </div>
                </div>
            </div>
          
            <div>
                <strong style="font-size: 1.17em;">{{ @Helper::setting()->print_heading }} </strong>
            </div>
            <div style="margin: 10px 0px; font-size:1.5em;" >
                <div  style="border: 2px solid #0A77BA;border-radius: 10px;padding: 7px 15px; width:30%; margin:auto;"> 
                    <div style="background-color: #0A77BA;color: #eceff4; padding: 3px 25px; width:70%; margin:auto;">Invoice</div>
                 </div> 
            </div>
            <div>
                <strong style="font-size: .9rem;">VAT NO:{{ @Helper::setting()->vat_number }}</strong>
            </div>
        </div>
    </div>
    <div style="margin: 20px 0px;">
        <div style="width:100%;">
            <div style="width:40%; float:left;">
                <strong style="font-weight:100;">Invoice No: # {!! date('Y').@$payments->id  !!}</strong><br>
                <strong style="font-weight:100;">Date & Time: {{ date('d-M-Y h:iA', strtotime(@@$payments->created_at)) }} </strong><br>
                <strong style="font-weight:100;">Name: Sohi Software  LTD
                </strong><br>
                <strong style="font-weight:100;">Receiver: Sohisoft</strong><br>
                <strong style="font-weight:100;">Phone: 017000000</strong><br>
                <strong style="font-weight:100;">Email: support@sohibd.com</strong>
            </div>
           
            <div style="width:40%;float:right;">
                <strong style="font-weight:100;"> Name : {{ @$payments->admin->name }}</strong>
                <br>
                <strong style="font-weight:100;"> Phone : {{ @$payments->admin->phone }}</strong>
                <br>
                <strong style="font-weight:100;"> Email : {{ @$payments->admin->email }}</strong>
                <br>
                <strong style="font-weight:100;"> Amount : {{$payments->credit }}  {{ @Helper::setting()->currency_name }}</strong> <br>
                <strong style="font-weight:100;"> Payment : {{$payments->payment->payment_name }} </strong><br>
                <strong><img style="height: 33px; width:170px;" src="data:image/png;base64,{!! DNS1D::getBarcodePNG(date('Y').@$payments->id, 'C39') !!}" /></strong>
            </div>
        </div>
    </div>

    <div class="product_details">
        <table>
            <thead>
                <tr>
                <th width="10%">SL: </th>
                <th width="15%">Type </th>
                <th width="15%">Status </th>
                <th width="45%">Payment Note </th>
                <th width="20%" class="text-right"> Amount </th>
                </tr>
            </thead>
            <tbody>
              
                    <tr class="">
                        <td> {{ 1 }}</td>
                        <td>{{(@$payments->type) }}</td>
                        <td> @if (@$payments->status==0)
                           <span style="color:#FFC0CB"> Pending</span>  
                            @elseif(@$payments->status==1)
                            <span style="color:#198754"> Approve</span>     
                            @else
                            <span style="color:#bc0808"> Reject</span>       
                        @endif</td>
                        <td>{{(@$payments->note?:'no note or message') }}</td>
                        <td>{{ number_format(@$payments->credit, 2) }}</td>
                    </tr>
                
                <tr>
                    <td colspan="4">Total In Word :
                        {{ NumberToWords::transformNumber('en', @($payments->credit)) }}  {{ @Helper::setting()->currency_name }}
                        <br>
                    </td>
                    <td>
                        
                        {{ number_format(@$payments->credit, 2) }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="barcode_details">
        <div style="width: 100%; margin:20px;">
            <div style="width: 50%; float:left; text-align:center;">
                <div style="width: 50%; margin: 0 auto;" >
                    
                </div>
                @php
                 $details=json_decode(@$payments->details) 
                @endphp
                <div style="width: 100%; margin-top:5px;">{{ $details}}</div>
                
            </div>
            <div style="width: 40%; float:right; text-align:left;">
               
                <strong style="font-weight:100;"> Grand Total: {{ number_format(@@$payments->credit, 2) }}</strong>
            </div>
            <br style="clear: left;" />
        </div>
    </div>

    <div class="footer_details" style="padding: 1.5rem 0px; text-align: center;
    border: 2px solid #0A77BA;">
        <div>
            <strong style="font-weight:100;">Terms & Conditions</strong>
            <br>
            <strong style="font-weight:100;">You cant request widrow your transisation 
                <br>
                Pay First Befor your Expired date</strong>
            <strong style="font-weight:100;">
                Thank You</strong>
        </div>
    </div>
</body>

</html>
