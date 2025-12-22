<?php
namespace App\Services;

use App\Interfaces\ContractDocumentRepositoryInterface;

class ContractDocumentService
{

    protected $contractDocumentRepository;

    public function __construct(ContractDocumentRepositoryInterface $contractDocumentRepository)
    {
        $this->contractDocumentRepository = $contractDocumentRepository;
    }

    public function createContractDocument($request)
    {
        return $this->contractDocumentRepository->createContractDocument($request);
    }

    public function getContractDocumentByUuid($request)
    {
        return $this->contractDocumentRepository->getContractDocumentByUuid($request);
    }

    public function getContractDocumentPath($request)
    {
        return $this->contractDocumentRepository->getContractDocumentPath($request);
    }
    public function deleteDocumentTracing($request)
    {
        return $this->contractDocumentRepository->deleteDocumentTracing($request);
    }

    public function updateContractDocument($request)
    {
        return $this->contractDocumentRepository->updateContractDocument($request);
    }
}
