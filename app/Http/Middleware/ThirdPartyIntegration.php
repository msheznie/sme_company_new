<?php

namespace App\Http\Middleware;

use App\Models\Company;
use App\Models\ThirdPartyIntegrationKeys;
use App\Models\ThirdPartySystems;
use Closure;

class ThirdPartyIntegration
{
    const invalidApiKey = 'Invalid API key';

    public function handle($request, Closure $next)
    {
        $authorizationHeader = $request->header('Authorization');

        if (!$authorizationHeader)
        {
            return self::errorMessage(self::invalidApiKey, 401);
        }

        $params = explode(' ', $authorizationHeader);

        if (count($params) != 2)
        {
            return self::errorMessage(self::invalidApiKey, 401);
        }


        [$key, $value] = explode(' ', $authorizationHeader);

        $thirdPartySystem = ThirdPartySystems::getData($key);


        if (!$thirdPartySystem)
        {
            return self::errorMessage(self::invalidApiKey, 401);
        }

        $thirdPartyKey = ThirdPartyIntegrationKeys::getData($thirdPartySystem->id, $value);

        if (!$thirdPartyKey)
        {
            return self::errorMessage(self::invalidApiKey, 401);
        }

        $company = Company::getData($thirdPartyKey->company_id);

        if (!$company)
        {
            return self::errorMessage('Company is not active', 401);
        }

        $request->merge(
            [
                'company_id' => $thirdPartyKey->company_id,
                'api_external_key' => $thirdPartyKey->api_external_key,
                'api_external_url' => $thirdPartyKey->api_external_url,
                'third_party_system_id' => $thirdPartyKey->third_party_system_id,
            ]);

        return $next($request);
    }

    function errorMessage($messsage, $code)
    {
        return response()->json([
            'success' => false,
            'message' => $messsage
        ], $code);
    }

}



