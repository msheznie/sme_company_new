<?php
namespace App\Services;

use App\Interfaces\AdditionalDocumentRepositoryInterface;
use App\Interfaces\ContractDocumentRepositoryInterface;

class ContractAdditionalDocumentService
{

    protected $additionalDocumentRepository;

    public function __construct(AdditionalDocumentRepositoryInterface $additionalDocumentRepository)
    {
        $this->additionalDocumentRepository = $additionalDocumentRepository;
    }

    public function createAdditionalDocument($request)
    {
        return $this->additionalDocumentRepository->createAdditionalDocument($request);
    }

    public function getAdditionalDocumentByUuid($request)
    {
        return $this->additionalDocumentRepository->getAdditionalDocumentByUuid($request);
    }

    public function deleteContractDocument($request)
    {
        return $this->additionalDocumentRepository->deleteContractDocument($request);
    }

    public function updateAdditionalDoc($request)
    {
        return $this->additionalDocumentRepository->updateAdditionalDoc($request);
    }

}
