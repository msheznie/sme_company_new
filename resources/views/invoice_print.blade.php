<!DOCTYPE html>
<html>
    <head>
        <title>Direct Invoice Voucher</title>
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
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial,
                sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
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
                    <td width="40%"><span
                            class="font-weight-bold">Confirmed By :</span> {{ $masterdata->confirmedBy? $masterdata->confirmedBy->empFullName:'' }}
                    </td>
                    <td><span class="font-weight-bold">Review By :</span></td>
                </tr>
            </table>
            <table style="width:100%;">
                <tr>
                    <td><span class="font-weight-bold">Electronically Approved By :</span></td>
                </tr>
                <tr>
                    &nbsp;
                </tr>
            </table>
            <table style="width:100%;">
                <tr>
                    @if ($masterdata->approvedBy)
                        @foreach ($masterdata->approvedBy as $det)
                            <td style="padding-right: 25px;font-size: 9px;">
                                <div>
                                    @if($det->employee)
                                        {{$det->employee->empFullName }}
                                    @endif
                                </div>
                                <div>
                                    <span>
                                        @if(!empty($det->approvedDate))
                                            {{ \App\Helpers\General::convertDateWithTime($det->approvedDate)}}
                                        @endif
                                    </span>
                                </div>
                                <div style="width: 3px"></div>
                            </td>
                        @endforeach
                    @endif
                </tr>
            </table>
        </div>
        <div id="watermark"></div>
        <div class="card-body content" id="print-section">
            <table style="width: 100%" class="table_height">
                <tr style="width: 100%">
                    <td valign="top" style="width: 20%">
                        @if($masterdata->company)
                            <img src="{{$masterdata->company->logo_url}}" width="180px" height="60px"
                                 class="container">
                        @endif
                    </td>
                    <td valign="top" style="width: 80%">
                        @if($masterdata->company)
                            <span style="font-size: 24px;font-weight: 400"> {{$masterdata->company->CompanyName}}</span>
                        @endif
                        <br>
                        <table>
                            <tr>
                                <td width="100px">
                                    <span class="font-weight-bold">Doc Code</span>
                                </td>
                                <td width="10px">
                                    <span class="font-weight-bold">:</span>
                                </td>
                                <td>
                                    <span>{{$masterdata->bookingInvCode}}</span>
                                </td>
                            </tr>
                            <tr>
                                <td width="70px">
                                    <span class="font-weight-bold">Doc Date </span>
                                </td>
                                <td width="10px">
                                    <span class="font-weight-bold">:</span>
                                </td>
                                <td>
                            <span>
                                {{ \App\Helpers\General::dateFormat($masterdata->bookingDate)}}
                            </span>
                                </td>
                            </tr>
                            <tr>
                                <td width="70px">
                                    <span class="font-weight-bold">Invoice Number</span>
                                </td>
                                <td width="10px">
                                    <span class="font-weight-bold">:</span>
                                </td>
                                <td>
                            <span>
                               {{$masterdata->supplierInvoiceNo}}
                            </span>
                                </td>
                            </tr>
                            <tr>
                                <td width="70px">
                                    <span class="font-weight-bold">Invoice Date</span>
                                </td>
                                <td width="10px">
                                    <span class="font-weight-bold">:</span>
                                </td>
                                <td>
                            <span>
                                {{ \App\Helpers\General::dateFormat($masterdata->supplierInvoiceDate)}}
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
                                @if($masterdata->documentType == 0)
                                    Booking Invoice
                                @endif
                                @if($masterdata->documentType == 1)
                                    Direct Invoice Voucher
                                @endif
                            </span>
                        </div>
                    </td>
                </tr>
            </table>
            <br>
            <table style="width: 100%">
                <tr style="width:100%">
                    <td style="width: 60%">
                        <table>
                            <tr>
                                <td width="150px">
                                    <span class="font-weight-bold">Supplier Code</span>
                                </td>
                                <td width="10px">
                                    <span class="font-weight-bold">:</span>
                                </td>
                                <td>
                                    @if($masterdata->supplier)
                                        {{$masterdata->supplier->primarySupplierCode}}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td width="50px">
                                    <span class="font-weight-bold">Supplier Name</span>
                                </td>
                                <td width="10px">
                                    <span class="font-weight-bold">:</span>
                                </td>
                                <td>
                                    @if($masterdata->supplier)
                                        {{$masterdata->supplier->supplierName}}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td width="50px">
                                    <span class="font-weight-bold">Reference Number</span>
                                </td>
                                <td width="10px">
                                    <span class="font-weight-bold">:</span>
                                </td>
                                <td>
                                    {{$masterdata->secondaryRefNo}}
                                </td>
                            </tr>
                            <tr>
                                <td width="70px">
                                    <span class="font-weight-bold">Narration </span>
                                </td>
                                <td width="10px">
                                    <span class="font-weight-bold">:</span>
                                </td>
                                <td>
                                    <span>{{$masterdata->comments}}</span>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td style="width: 40%">
                        <table style="width: 100%">
                            <tr style="width: 100%">
                                <td valign="bottom" class="text-right">
                                    <h3 class="text-muted">
                                        @if($masterdata->confirmedYN == 0 && $masterdata->approved == 0)
                                            Not Confirmed
                                        @elseif($masterdata->confirmedYN == 1 && $masterdata->approved == 0)
                                            Pending Approval
                                        @elseif($masterdata->confirmedYN == 1 &&
                                            ($masterdata->approved == 1 ||  $masterdata->approved == -1))
                                            Fully Approved
                                        @endif
                                    </h3>
                                </td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td valign="bottom" class="text-right">
                                    <span class="font-weight-bold"> Currency:</span>
                                    @if($masterdata->currency)
                                        {{$masterdata->currency->CurrencyCode}}
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            @if($masterdata->documentType == 0)
                <div style="margin-top: 30px;">
                    <table class="table table-bordered" style="width: 100%;">
                        <thead>
                        <tr class="theme-tr-head">
                            <th></th>
                            <th class="text-center">GL Code</th>
                            <th class="text-center">GL Type</th>
                            <th class="text-center">GL Code Description</th>
                            <th class="text-center">Local Currency (
                                @if($masterdata->localcurrency)
                                    {{$masterdata->localCurrency->CurrencyCode}}
                                @endif
                                )
                            </th>
                            <th class="text-center">Supplier Currency (
                                @if($masterdata->currency)
                                    {{$masterdata->currency->CurrencyCode}}
                                @endif
                                )
                            </th>
                        </tr>
                        <tr class="theme-tr-head">
                            <th colspan="4"></th>
                            <th class="text-center">Amount</th>
                            <th class="text-center">Amount</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($masterdata->detail as $item)
                            <tr style="border-top: 1px solid #ffffff !important;border-bottom: 1px solid #ffffff !important;">
                                <td>{{$loop->iteration}}</td>
                                <td>
                                    @if($masterdata->supplierGrv)
                                        {{$masterdata->supplierGrv->AccountCode}}
                                    @endif
                                </td>
                                <td>
                                    @if($masterdata->supplierGrv)
                                        {{$masterdata->supplierGrv->catogaryBLorPL}}
                                    @endif
                                </td>
                                <td>
                                    @if($masterdata->supplierGrv)
                                        {{$masterdata->supplierGrv->AccountDescription}}
                                    @endif
                                </td>
                                <td class="text-right">{{number_format($item->totLocalAmount, $localDecimal)}}</td>
                                <td class="text-right">{{number_format($item->totTransactionAmount, $transDecimal)}}</td>
                            </tr>
                        @endforeach
                        <tr style="border-top: 1px solid #333 !important;border-bottom: 1px solid #333 !important;">
                            <td colspan="4" class="text-right border-bottom-remov"></td>
                            <td class="text-right ">{{number_format($grvTotLoc, $localDecimal)}}</td>
                            <td class="text-right ">{{number_format($grvTotTra, $transDecimal)}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div style="margin-top: 20px;">
                    <table class="table table-bordered" style="width: 100%;">
                        <thead>
                        <tr class="border-bottom-remov">
                            <th colspan="5" class="theme-tr-head text-left">GRV Details</th>
                        </tr>
                        <tr class="theme-tr-head">
                            <th class="text-center">GRV Code</th>
                            <th class="text-center">GRV Date</th>
                            <th class="text-center">Document Narration</th>
                            <th class="text-center">Local Currency (
                                @if($masterdata->localCurrency)
                                    {{$masterdata->localCurrency->CurrencyCode}}
                                @endif
                                )</th>
                            <th class="text-center">Rpt Currency (
                                @if($masterdata->rptCurrency)
                                    {{$masterdata->rptCurrency->CurrencyCode}}
                                @endif
                                )</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($masterdata->grvDetail as $item)
                            <tr style="border-top: 1px solid #ffffff !important;border-bottom: 1px solid #ffffff !important;">
                                <td>
                                    @if($item->grvMaster)
                                        {{$item->grvMaster->grvPrimaryCode}}
                                    @endif
                                </td>
                                <td>
                                    @if($item->grvMaster)
                                        {{ \App\Helpers\General::dateFormat($item->grvMaster->grvDate)}}
                                    @endif
                                </td>
                                <td>
                                    @if($item->grvMaster)
                                        {{$item->grvMaster->grvNarration}}
                                    @endif
                                </td>
                                <td class="text-right">{{number_format($item->totLocalAmount, $localDecimal)}}</td>
                                <td class="text-right">{{number_format($item->totRptAmount, $rptDecimal)}}</td>
                            </tr>
                        @endforeach
                        <tr style="border-top: 1px solid #333 !important;border-bottom: 1px solid #333 !important;">
                            <td colspan="3" class="text-right border-bottom-remov"></td>
                            <td class="text-right">{{number_format($grvTotLoc, $localDecimal )}}</td>
                            <td class="text-right">{{number_format($grvTotRpt, $rptDecimal)}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            @endif
            @if($masterdata->documentType == 1)
                <div style="margin-top: 30px">
                    <table class="table table-bordered" style="width: 100%;">
                        <thead>
                        <tr class="theme-tr-head">
                            <th></th>
                            <th class="text-center">GL Code</th>
                            <th class="text-center">GL Code Description</th>
                            <th colspan="3" class="text-center">Project</th>
                            <th class="text-center">Segment</th>
                            <th class="text-center">Amount</th>
                            <th class="text-center">VAT Amount</th>
                            <th class="text-center">Net Amount</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($masterdata->directdetail as $item)
                            <tr style="border-top: 1px solid #ffffff !important;border-bottom: 1px solid #ffffff !important;">
                                <td>{{$loop->iteration}}</td>
                                <td>{{$item->glCode}}</td>
                                <td>{{$item->glCodeDes}}</td>
                                <td colspan="3">
                                    @if($item->project)
                                        {{$item->project->projectCode}} - {{$item->project->description}}
                                    @endif
                                </td>
                                <td>
                                    @if($item->segment)
                                        {{$item->segment->ServiceLineDes}}
                                    @endif
                                </td>
                                <td class="text-right">{{number_format($item->DIAmount, $transDecimal)}}</td>
                                <td class="text-right">{{number_format($item->VATAmount, $transDecimal)}}</td>
                                <td class="text-right">{{number_format($item->netAmount, $transDecimal)}}</td>
                            </tr>
                        @endforeach
                        <tr style="border-top: 1px solid #333 !important;border-bottom: 1px solid #333 !important;">
                            <td colspan="5" class="text-right border-bottom-remov">&nbsp;</td>
                            <td colspan="3" class="text-right border-bottom-remov">&nbsp;</td>
                            <td class="text-right" style="background-color: rgb(215,215,215)">Total</td>
                            <td class="text-right" style="background-color: rgb(215,215,215)">
                                {{number_format($directTotTra, $transDecimal)}}
                            </td>
                        </tr>
                        <tr style="border-top: 1px solid #333 !important;border-bottom: 1px solid #333 !important;">
                            <td colspan="5" class="text-right border-bottom-remov">&nbsp;</td>
                            <td colspan="3" class="text-right border-bottom-remov">&nbsp;</td>
                            <td class="text-right" style="background-color: rgb(215,215,215)">VAT</td>
                            <td class="text-right" style="background-color: rgb(215,215,215)">
                                {{number_format($directTotVAT, $transDecimal)}}
                            </td>
                        </tr>
                        <tr style="border-top: 1px solid #333 !important;border-bottom: 1px solid #333 !important;">
                            <td colspan="5" class="text-right border-bottom-remov">&nbsp;</td>
                            <td colspan="3" class="text-right border-bottom-remov">&nbsp;</td>
                            <td class="text-right" style="background-color: rgb(215,215,215)">Net Total</td>
                            @if($masterdata->rcmActivated)
                                <td class="text-right" style="background-color: rgb(215,215,215)">
                                    {{number_format($directTotNet, $transDecimal)}}
                                </td>
                            @else
                                <td class="text-right" style="background-color: rgb(215,215,215)">
                                    {{number_format(($directTotNet + $directTotVAT), $transDecimal)}}
                                </td>
                            @endif
                        </tr>
                        <tr style="border-top: 1px solid #333 !important;border-bottom: 1px solid #333 !important;">
                            <td colspan="5" class="text-right border-bottom-remov">&nbsp;</td>
                            <td colspan="3" class="text-right border-bottom-remov">&nbsp;</td>
                            <td class="text-right" style="background-color: rgb(215,215,215)">Retention Amount</td>
                            @if($masterdata->rcmActivated)
                                <td class="text-right" style="background-color: rgb(215,215,215)">
                                    {{number_format($directTotNet * ($masterdata->retentionPercentage/100), $transDecimal)}}
                                </td>
                            @else
                                <td class="text-right" style="background-color: rgb(215,215,215)">
                                    {{number_format(($directTotNet + $directTotVAT) *
                                        ($masterdata->retentionPercentage/100), $transDecimal)}}
                                </td>
                            @endif
                        </tr>
                        <tr style="border-top: 1px solid #333 !important;border-bottom: 1px solid #333 !important;">
                            <td colspan="5" class="text-right border-bottom-remov">&nbsp;</td>
                            <td colspan="3" class="text-right border-bottom-remov">&nbsp;</td>
                            <td class="text-right" style="background-color: rgb(215,215,215)">Net Amount</td>
                            @if($masterdata->rcmActivated)
                                <td class="text-right" style="background-color: rgb(215,215,215)">
                                    {{number_format($directTotNet -
                                        ($directTotNet * ($masterdata->retentionPercentage/100)), $transDecimal)}}
                                </td>
                            @else
                                <td class="text-right" style="background-color: rgb(215,215,215)">
                                    {{number_format(($directTotNet + $directTotVAT) -
                                        (($directTotNet + $directTotVAT) * ($masterdata->retentionPercentage/100)),
                                         $transDecimal)}}
                                </td>
                            @endif
                        </tr>
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </body>
</html>
