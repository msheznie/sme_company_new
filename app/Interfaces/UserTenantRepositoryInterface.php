<?php

namespace App\Interfaces;

interface UserTenantRepositoryInterface
{
    public function save($request);

    public function getTenantList($userId);

    public function isTenantRegistered($userId, $apiKey);

    public function getKycStatus($userId, $tenantId);
}
