{{--<html>--}}
{{--<h1>Invoice</h1><p>This is your invoice.</p>--}}
{{--</html>--}}


<html>
<head>
    <title>Financial Summary</title>
    <style>
        @page {
            margin-left: 30px;
            margin-right: 30px;
            margin-top: 30px;
        }

        .footer {
            position: absolute;
        }

        .footer {
            bottom: 0;
            height: 100px;
        }

        .footer {
            width: 100%;
            text-align: center;
            position: fixed;
            font-size: 10px;
            padding-top: -20px;
        }

        body {
            font-size: 12px;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif,
            "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
        }

        h3 {
            font-size: 24.5px;
        }

        h6 {
            font-size: 14px;
        }

        h6, h3 {
            margin-top: 0px;
            margin-bottom: 0px;
            font-family: inherit;
            font-weight: bold;
            line-height: 1.2;
            color: inherit;
        }

        table > tbody > tr > td {
            font-size: 11.5px;
        }

        .theme-tr-head {
            background-color: rgb(215, 215, 215) !important;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .font-weight-bold {
            font-weight: 700 !important;
        }

        .table thead th {
            border-bottom: none !important;
        }

        .white-space-pre-line {
            white-space: pre-line;
            white-space: pre;
            word-wrap: normal;
        }

        .text-muted {
            color: #dedede !important;
        }

        .font-weight-bold {
            font-weight: 700 !important;
        }

        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #c2cfd6;
        }

        table.table-bordered {
            border: 1px solid #000;
        }

        .table th, .table td {
            padding: 6.4px !important;
        }

        table.table-bordered {
            border-collapse: collapse;
        }

        table.table-bordered, .table-bordered th, .table-bordered td {
            border: 1px solid #e2e3e5;
        }

        table > thead > tr > th {
            font-size: 11.5px;
        }

        hr {
            margin-top: 16px;
            margin-bottom: 16px;
            border: 0;
            border-top: 1px solid
        }

        hr {
            -webkit-box-sizing: content-box;
            box-sizing: content-box;
            height: 0;
            overflow: visible;
        }

        .header {
            top: 0px;
        }

        .pagenum:before {
            content: counter(page);
        }

        #watermark {
            position: fixed;
            bottom: 0px;
            right: 0px;
            width: 200px;
            height: 200px;
            opacity: .1;
        }

        .content {
            margin-bottom: 45px;
        }

        .border-top-remov {
            border-top: 1px solid #ffffff00 !important;
            border-left: 1px solid #ffffff00 !important;
            background-color: #ffffff !important;
            border-right: 0;
        }

        .border-bottom-remov {
            border-bottom: 1px solid #ffffffff !important;
            background-color: #ffffff !important;
            border-right: 1px solid #ffffffff !important;
        }
        .container
        {
            display: block;
            max-width:230px;
            max-height:95px;
            width: auto;
            height: auto;
        }

        .table_height
        {
            max-height: 60px !important;
        }
    </style>
</head>
<body>
<div class="footer">
    <table style="width:100%;">
        <tr>
            <td colspan="3" style="width:100%">
                <hr style="background-color: black">
            </td>
        </tr>
        <tr>
            <td style="width:33%;font-size: 10px;vertical-align: top;">
                <span class="white-space-pre-line font-weight-bold"></span>
            </td>
            <td style="width:33%; text-align: center;font-size: 10px;vertical-align: top;">
                <span style="text-align: center">Page <span class="pagenum"></span></span><br>
                @if ($company)
                    {{$company->CompanyName}}
                @endif
            </td>
            <td style="width:33%;font-size: 10px;vertical-align: top;">
                <span style="margin-left: 50%;">Printed Date : {{date("d-M-y", strtotime(now()))}}</span>
            </td>
        </tr>
    </table>
</div>
<div id="watermark"></div>
<div class="card-body content" id="print-section">
    <table style="width: 100%" class="table_height">
        <tr style="width: 100%">
            <td valign="top" style="width: 20%">
                @if($company)
                    <img src="{{$company->logo_url}}" style="width: 40%" class="container">
                @endif
            </td>
            <td valign="top" style="width: 80%">
                @if($company)
                    <span style="font-size: 24px;font-weight: 400"> {{$company->CompanyName}}</span>
                @endif
                    <br>
                    <table>
                        <tr>
                            <td width="150px">
                                <span class="font-weight-bold">Contract Title:</span>
                            </td>
                            <td width="10px">
                                <span class="font-weight-bold">:</span>
                            </td>
                            <td>
                                <span>@if($contract)
                                        {{$contract->title}}
                                    @endif
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td width="150px">
                                <span class="font-weight-bold">Contract Type:</span>
                            </td>
                            <td width="10px">
                                <span class="font-weight-bold">:</span>
                            </td>
                            <td>
                            <span>
                                @if($contract)
                                    {{$contract->contractTypes->cm_type_name}}
                                @endif
                            </span>
                            </td>
                        </tr>
                        <tr>
                            <td width="150px">
                                <span class="font-weight-bold">Counter Party:</span>
                            </td>
                            <td width="10px">
                                <span class="font-weight-bold">:</span>
                            </td>
                            <td>
                            <span>
                              @if($contract)
                                    {{$contract->counterParties->cmCounterParty_name}}
                                @endif
                            </span>
                            </td>
                        </tr>
                        <tr>
                            <td width="150px">
                                <span class="font-weight-bold">Contract Amount:</span>
                            </td>
                            <td width="10px">
                                <span class="font-weight-bold">:</span>
                            </td>
                            <td>
                            <span>
                                @if($contract)
                                    {{number_format($contract->contractAmount, $decimalPlaces)}}
                                @endif
                            </span>
                            </td>
                        </tr>
                        <tr>
                            <td width="150px">
                                <span class="font-weight-bold">Contract Code:</span>
                            </td>
                            <td width="10px">
                                <span class="font-weight-bold">:</span>
                            </td>
                            <td>
                            <span>
                                @if($contract)
                                    {{$contract->contractCode}}
                                @endif
                            </span>
                            </td>
                        </tr>
                        <tr>
                            <td width="150px">
                                <span class="font-weight-bold">Contract Owner:</span>
                            </td>
                            <td width="10px">
                                <span class="font-weight-bold">:</span>
                            </td>
                            <td>
                            <span>
                                @if($contract)
                                    {{$contract->contractOwners->contractUserName}}
                                @endif
                            </span>
                            </td>
                        </tr>
                        <tr>
                            <td width="150px">
                                <span class="font-weight-bold">Counter Party Name:</span>
                            </td>
                            <td width="10px">
                                <span class="font-weight-bold">:</span>
                            </td>
                            <td>
                            <span>
                                @if($contract)
                                    {{$contract->contractUsers->contractUserName}}
                                @endif
                            </span>
                            </td>
                        </tr>
                    </table>
            </td>
        </tr>
    </table>


    <hr style="color: #d3d9df">

    <table style="width: 100%" class="table_height">
        <tr style="width: 100%">
            <td>
                <div>
                    <span style="font-size: 18px">
                            Contract Management Financial Summary
                    </span>
                </div>
            </td>
        </tr>
    </table>

    <br>

    <div style="margin-top: 10px">
        <p><strong>Purchase Order</strong></p>
        <table class="table table-bordered" style="width: 100%;">
            <thead>
            <tr class="theme-tr-head">
                <th style="width: 1%;">#</th>
                <th class="text-center" style="width: 25%;">Document Code</th>
                <th class="text-center" style="width: 24%;">Amount</th>
                <th class="text-center" style="width: 25%;">Document Status</th>
                <th class="text-center" style="width: 25%;">Created Date</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($purchaseOrder as $item)
                <tr style="border-top: 1px solid #ffffff !important;border-bottom: 1px solid #ffffff !important;">
                    <td class="text-center">{{$loop->iteration}}</td>
                    <td class="text-center">{{$item->purchaseOrderCode}}</td>
                    <td class="text-center">{{$item->currency->CurrencyCode}}
                        {{number_format($item->poTotalSupplierTransactionCurrency,
 $item->currency->DecimalPlaces)}}</td>
                    <td class="text-center">
                        @if($item->poConfirmedYN == 0 && $item->approved == 0)
                            <span>Not Confirmed</span>
                        @endif
                        @if($item->poConfirmedYN == 1 && $item->approved == 0 && $item->refferedBackYN == 0)
                            <span>Pending Approval</span>
                        @endif
                        @if($item->poConfirmedYN == 1 && $item->approved == 0 && $item->refferedBackYN == 1)
                            <span>Rejected</span>
                        @endif
                        @if($item->poConfirmedYN == 1 && ($item->approved == -1 || $item->approved == 1))
                            <span>Fully Approved</span>
                        @endif
                        @if($item->poConfirmedYN == 1 && $item->approved == 0 && $item->refferedBackYN == -1)
                            <span>Referred Back</span>
                        @endif
                    </td>
                    <td class="text-center">{{ \App\helpers\General::dateFormat($item->createdDateTime)}}</td>
                </tr>
            @endforeach
            @if(count($purchaseOrder) == 0)
                <tr>
                    <td colspan="5" class="text-center">No records found !</td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
    <div style="margin-top: 10px">
        <p><strong>Retention</strong></p>
        <table class="table table-bordered" style="width: 100%;">
            <thead>
            <tr class="theme-tr-head">
                <th class="text-left" colspan="5">Invoice</th>
            </tr>
            <tr class="theme-tr-head">
                <th style="width: 1%;">#</th>
                <th class="text-center" style="width: 25%;">Document Code</th>
                <th class="text-center" style="width: 24%;">Amount</th>
                <th class="text-center" style="width: 25%;">Document Status</th>
                <th class="text-center" style="width: 25%;">Created Date</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($retentionSI as $item)
                <tr style="border-top: 1px solid #ffffff !important;border-bottom: 1px solid #ffffff !important;">
                    <td class="text-center">{{$loop->iteration}}</td>
                    <td class="text-center">{{$item->invoiceMaster->bookingInvCode}}</td>
                    <td class="text-center">{{$item->invoiceMaster->currency->CurrencyCode}}
                        {{number_format($item->invoiceMaster->bookingAmountTrans,
                        $item->invoiceMaster->currency->DecimalPlaces)}}
                    </td>
                    <td class="text-center">
                        @if($item->invoiceMaster->confirmedYN == 0 && $item->invoiceMaster->approved == 0)
                            <span>Not Confirmed</span>
                        @endif
                        @if($item->invoiceMaster->confirmedYN == 1 && $item->invoiceMaster->approved == 0 &&
 $item->invoiceMaster->refferedBackYN == 0)
                            <span>Pending Approval</span>
                        @endif
                        @if($item->invoiceMaster->confirmedYN == 1 && $item->invoiceMaster->approved == 0 &&
 $item->invoiceMaster->refferedBackYN == 1)
                            <span>Rejected</span>
                        @endif
                        @if($item->invoiceMaster->confirmedYN == 1 &&
                            ($item->invoiceMaster->approved == -1 || $item->invoiceMaster->approved == 1))
                            <span>Fully Approved</span>
                        @endif
                        @if($item->invoiceMaster->confirmedYN == 1 && $item->invoiceMaster->approved == 0 &&
 $item->invoiceMaster->refferedBackYN == -1)
                            <span>Referred Back</span>
                        @endif
                    </td>
                    <td class="text-center">
                        {{ \App\helpers\General::dateFormat($item->invoiceMaster->createdDateAndTime)}}
                    </td>
                </tr>
            @endforeach
            @if(count($retentionSI) == 0)
                <tr>
                    <td colspan="5" class="text-center">No records found !</td>
                </tr>
            @endif
            </tbody>
        </table>
        <br>
        <table class="table table-bordered" style="width: 100%;">
            <thead>
            <tr class="theme-tr-head">
                <th class="text-left" colspan="5">Payment Voucher</th>
            </tr>
            <tr class="theme-tr-head">
                <th style="width: 1%;">#</th>
                <th class="text-center" style="width: 25%;">Document Code</th>
                <th class="text-center" style="width: 24%;">Amount</th>
                <th class="text-center" style="width: 25%;">Document Status</th>
                <th class="text-center" style="width: 25%;">Created Date</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($retentionPV as $item)
                <tr style="border-top: 1px solid #ffffff !important;border-bottom: 1px solid #ffffff !important;">
                    <td class="text-center">{{$loop->iteration}}</td>
                    <td class="text-center">{{$item->paymentVoucherMaster->BPVcode}}</td>
                    <td class="text-center">
                        <span>{{$item->paymentVoucherMaster->supplierCurrency->CurrencyCode}}</span>
                        @if(
                     $item->paymentVoucherMaster->invoiceType == 3 && $item->paymentVoucherMaster->confirmedYN == 1)
                            <span>
                                {{number_format
                     ($item->paymentVoucherMaster->payAmountSuppTrans * 1 + $item->paymentVoucherMaster->VATAmount * 1,
                     $item->paymentVoucherMaster->supplierCurrency->DecimalPlaces)
                     }}
                            </span>
                        @endif


                        @if(
                     $item->paymentVoucherMaster->invoiceType == 3 && $item->paymentVoucherMaster->confirmedYN == 0
                        && $item->paymentVoucherMaster->payAmountSuppTrans == 0)
                            <span>
                                {{number_format
                     ($item->paymentVoucherMaster->payAmountSuppTrans,
                     $item->paymentVoucherMaster->supplierCurrency->DecimalPlaces)
                     }}
                            </span>
                        @endif


                        @if(
                     $item->paymentVoucherMaster->invoiceType == 3 && $item->paymentVoucherMaster->confirmedYN == 0
                        && $item->paymentVoucherMaster->payAmountSuppTrans != 0)
                            <span>
                                {{number_format
                     ($item->paymentVoucherMaster->payAmountSuppTrans * 1 + $item->paymentVoucherMaster->VATAmount * 1,
                     $item->paymentVoucherMaster->supplierCurrency->DecimalPlaces)
                     }}
                            </span>
                        @endif


                        @if($item->paymentVoucherMaster->invoiceType != 3)
                            <span>
                                {{number_format
            ($item->paymentVoucherMaster->payAmountSuppTrans * 1 + $item->paymentVoucherMaster->retentionVatAmount * 1,
                     $item->paymentVoucherMaster->supplierCurrency->DecimalPlaces)
                     }}
                            </span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($item->paymentVoucherMaster->confirmedYN == 0 && $item->paymentVoucherMaster->approved == 0)
                            <span>Not Confirmed</span>
                        @endif
                        @if($item->paymentVoucherMaster->confirmedYN == 1 && $item->paymentVoucherMaster->approved == 0
 && $item->paymentVoucherMaster->refferedBackYN == 0)
                            <span>Pending Approval</span>
                        @endif
                        @if(
                    $item->paymentVoucherMaster->confirmedYN == 1 && $item->paymentVoucherMaster->approved == 0 &&
                     $item->paymentVoucherMaster->refferedBackYN == 1)
                            <span>Rejected</span>
                        @endif
                        @if($item->paymentVoucherMaster->confirmedYN == 1 &&
                        ($item->paymentVoucherMaster->approved == -1 || $item->paymentVoucherMaster->approved == 1))
                            <span>Fully Approved</span>
                        @endif
                        @if($item->paymentVoucherMaster->confirmedYN == 1 && $item->paymentVoucherMaster->approved == 0
 && $item->paymentVoucherMaster->refferedBackYN == -1)
                            <span>Referred Back</span>
                        @endif
                    </td>
                    <td class="text-center">
                        {{ \App\helpers\General::dateFormat($item->paymentVoucherMaster->createdDateTime)}}
                    </td>
                </tr>
            @endforeach
            @if(count($retentionPV) == 0)
                <tr>
                    <td colspan="5" class="text-center">No records found !</td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
    <div style="margin-top: 10px">
        <p><strong>Milestone and Payment Schedules</strong></p>
        <table class="table table-bordered" style="width: 100%;">
            <thead>
            <tr class="theme-tr-head">
                <th class="text-left" colspan="5">Invoice</th>
            </tr>
            <tr class="theme-tr-head">
                <th style="width: 1%;">#</th>
                <th class="text-center" style="width: 25%;">Document Code</th>
                <th class="text-center" style="width: 24%;">Amount</th>
                <th class="text-center" style="width: 25%;">Document Status</th>
                <th class="text-center" style="width: 25%;">Created Date</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($milestoneSI as $item)
                <tr style="border-top: 1px solid #ffffff !important;border-bottom: 1px solid #ffffff !important;">
                    <td class="text-center">{{$loop->iteration}}</td>
                    <td class="text-center">{{$item->invoiceMaster->bookingInvCode}}</td>
                    <td class="text-center">{{$item->invoiceMaster->currency->CurrencyCode}}
                        {{number_format($item->invoiceMaster->bookingAmountTrans,
                        $item->invoiceMaster->currency->DecimalPlaces)}}
                    </td>
                    <td class="text-center">
                        @if($item->invoiceMaster->confirmedYN == 0 && $item->invoiceMaster->approved == 0)
                            <span>Not Confirmed</span>
                        @endif
                        @if($item->invoiceMaster->confirmedYN == 1 && $item->invoiceMaster->approved == 0 &&
 $item->invoiceMaster->refferedBackYN == 0)
                            <span>Pending Approval</span>
                        @endif
                        @if($item->invoiceMaster->confirmedYN == 1 && $item->invoiceMaster->approved == 0 &&
 $item->invoiceMaster->refferedBackYN == 1)
                            <span>Rejected</span>
                        @endif
                        @if($item->invoiceMaster->confirmedYN == 1 &&
                            ($item->invoiceMaster->approved == -1 || $item->invoiceMaster->approved == 1))
                            <span>Fully Approved</span>
                        @endif
                        @if($item->invoiceMaster->confirmedYN == 1 && $item->invoiceMaster->approved == 0 &&
 $item->invoiceMaster->refferedBackYN == -1)
                            <span>Referred Back</span>
                        @endif
                    </td>
                    <td class="text-center">
                        {{ \App\helpers\General::dateFormat($item->invoiceMaster->createdDateAndTime)}}
                    </td>
                </tr>
            @endforeach
            @if(count($milestoneSI) == 0)
                <tr>
                    <td colspan="5" class="text-center">No records found !</td>
                </tr>
            @endif
            </tbody>
        </table>
        <br>
        <table class="table table-bordered" style="width: 100%;">
            <thead>
            <tr class="theme-tr-head">
                <th class="text-left" colspan="5">Payment Voucher</th>
            </tr>
            <tr class="theme-tr-head">
                <th style="width: 1%;">#</th>
                <th class="text-center" style="width: 25%;">Document Code</th>
                <th class="text-center" style="width: 24%;">Amount</th>
                <th class="text-center" style="width: 25%;">Document Status</th>
                <th class="text-center" style="width: 25%;">Created Date</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($milestonePV as $item)
                <tr style="border-top: 1px solid #ffffff !important;border-bottom: 1px solid #ffffff !important;">
                    <td class="text-center">{{$loop->iteration}}</td>
                    <td class="text-center">{{$item->paymentVoucherMaster->BPVcode}}</td>
                    <td class="text-center">
                        <span>{{$item->paymentVoucherMaster->supplierCurrency->CurrencyCode}}</span>
                        @if(
                     $item->paymentVoucherMaster->invoiceType == 3 && $item->paymentVoucherMaster->confirmedYN == 1)
                            <span>
                                {{number_format
                     ($item->paymentVoucherMaster->payAmountSuppTrans * 1 + $item->paymentVoucherMaster->VATAmount * 1,
                     $item->paymentVoucherMaster->supplierCurrency->DecimalPlaces)
                     }}
                            </span>
                        @endif


                        @if(
                     $item->paymentVoucherMaster->invoiceType == 3 && $item->paymentVoucherMaster->confirmedYN == 0
                        && $item->paymentVoucherMaster->payAmountSuppTrans == 0)
                            <span>
                                {{number_format
                     ($item->paymentVoucherMaster->payAmountSuppTrans,
                     $item->paymentVoucherMaster->supplierCurrency->DecimalPlaces)
                     }}
                            </span>
                        @endif


                        @if(
                     $item->paymentVoucherMaster->invoiceType == 3 && $item->paymentVoucherMaster->confirmedYN == 0
                        && $item->paymentVoucherMaster->payAmountSuppTrans != 0)
                            <span>
                                {{number_format
                     ($item->paymentVoucherMaster->payAmountSuppTrans * 1 + $item->paymentVoucherMaster->VATAmount * 1,
                     $item->paymentVoucherMaster->supplierCurrency->DecimalPlaces)
                     }}
                            </span>
                        @endif


                        @if($item->paymentVoucherMaster->invoiceType != 3)
                            <span>
                                {{number_format
            ($item->paymentVoucherMaster->payAmountSuppTrans * 1 + $item->paymentVoucherMaster->retentionVatAmount * 1,
                     $item->paymentVoucherMaster->supplierCurrency->DecimalPlaces)
                     }}
                            </span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($item->paymentVoucherMaster->confirmedYN == 0 && $item->paymentVoucherMaster->approved == 0)
                            <span>Not Confirmed</span>
                        @endif
                        @if($item->paymentVoucherMaster->confirmedYN == 1 && $item->paymentVoucherMaster->approved == 0
 && $item->paymentVoucherMaster->refferedBackYN == 0)
                            <span>Pending Approval</span>
                        @endif
                        @if(
                    $item->paymentVoucherMaster->confirmedYN == 1 && $item->paymentVoucherMaster->approved == 0 &&
                     $item->paymentVoucherMaster->refferedBackYN == 1)
                            <span>Rejected</span>
                        @endif
                        @if($item->paymentVoucherMaster->confirmedYN == 1 &&
                        ($item->paymentVoucherMaster->approved == -1 || $item->paymentVoucherMaster->approved == 1))
                            <span>Fully Approved</span>
                        @endif
                        @if($item->paymentVoucherMaster->confirmedYN == 1 && $item->paymentVoucherMaster->approved == 0
 && $item->paymentVoucherMaster->refferedBackYN == -1)
                            <span>Referred Back</span>
                        @endif
                    </td>
                    <td class="text-center">
                        {{ \App\helpers\General::dateFormat($item->paymentVoucherMaster->createdDateTime)}}
                    </td>
                </tr>
            @endforeach
            @if(count($milestonePV) == 0)
                <tr>
                    <td colspan="5" class="text-center">No records found !</td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>

</div>
