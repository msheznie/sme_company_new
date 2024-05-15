<?php

namespace App\Http\Middleware;

use App\Helpers\General;
use App\Models\Company;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use InfyOm\Generator\Utils\ResponseUtil;

class CheckCompanyAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $selectedCompanyId = $request->input('selectedCompanyID') ?? 0;
        if(!$selectedCompanyId){
            return $this->sendError('Please select the company',500);
        }
        $company = Company::find($selectedCompanyId);
        if(empty($company)){
            return $this->sendError('Company not found',404);
        }
        $request->merge(['companyId'=> $selectedCompanyId]);
        $request->merge(['pc' => General::getRequestPc()]);
        return $next($request);
    }

    private function sendError($message,$code = 401){
        return Response::json(ResponseUtil::makeError($message), $code);
    }
}
