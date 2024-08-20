<style>
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

    .font-weight-bold {
        font-weight: 700 !important;
    }

    .white-space-pre-line {
        white-space: pre-line;
        white-space: pre;
        word-wrap: normal;
    }

    .pagenum:before {
        content: counter(page);
    }
</style>
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
                {{--<span style="text-align: center">Page <span class="pagenum"></span><span></span></span><br>--}}
                @if ($masterdata->company)
                {{$masterdata->company->CompanyName}}
                @endif
            </td>
            <td style="width:33%;font-size: 10px;vertical-align: top;">
                <span style="margin-left: 50%;">Printed Date : {{date("d-M-y", strtotime(now()))}}</span>
            </td>
        </tr>
    </table>
</div>
