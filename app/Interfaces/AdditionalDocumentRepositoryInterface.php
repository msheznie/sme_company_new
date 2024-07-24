<?php
namespace App\Interfaces;

interface AdditionalDocumentRepositoryInterface
{
    public function createAdditionalDocument($request);
    public function getAdditionalDocumentByUuid($request);

    public function deleteContractDocument($request);
    public function updateAdditionalDoc($request);
}
