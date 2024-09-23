<?php

namespace App\Repositories;

use App\Models\FcmToken;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;

/**
 * Class FcmTokenRepository
 * @package App\Repositories
 * @version September 12, 2024, 11:20 am +04
*/

class FcmTokenRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'userID',
        'fcm_token',
        'deviceType'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return FcmToken::class;
    }
    public function getPortalRedirectUrl(Request $request)
    {
        $scheme = request()->secure() ? 'https' : 'http';
        $url = $request->fullUrl();
        $host = explode('/', $url);
        $subDomain = count($host) > 3 ? $host[3] : '';

        if ($subDomain === 'www')
        {
            $subDomain = explode('.', $host)[1];
        }

        if ($subDomain !== 'localhost')
        {
            $tenantDomain = explode('-', $subDomain);
            $domain = $tenantDomain[0] ?? null;

            return "{$scheme}://{$domain}." . env('APP_DOMAIN') . "/#/home";
        }

        return null;
    }
}
