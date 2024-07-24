<?php
namespace App\Interfaces;

interface ContractDocumentRepositoryInterface
{
    public function createContractDocument($request);
    public function getContractDocumentList($request);
    public function getContractDocumentByUuid($request);

    public function getContractDocumentPath($request);
    public function deleteDocumentTracing($request);
    public function updateContractDocument($request);
}
