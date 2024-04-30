<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
//use App\Models\Tenant;
//use App\Models\TenantConfiguration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class ConfigurationAPIController extends AppBaseController
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */

    public function getConfigurationInfo(Request $request)
    {
        $version = $this->getVersion();
        $configuration = array('version' => $version);
        return $this->sendResponse($configuration, 'Configurations retrieved successfully');
    }

    public function getVersion()
    {
        $packageJsonPath = base_path('package.json');
        if (File::exists($packageJsonPath)) {
            $packageJsonContent = File::get($packageJsonPath);
            $packageJsonData = json_decode($packageJsonContent, true);
            return $packageJsonData['version'];
        } else {
            return null;
        }
    }
}
