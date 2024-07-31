<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Repositories\CMCounterPartiesMasterRepository;
use App\Repositories\ContractMasterRepository;
use App\Repositories\contractStatusHistoryRepository;
use App\Repositories\MilestonePaymentSchedulesRepository;
use Illuminate\Http\Request;

class DashboardController extends AppBaseController
{

    private $contractMasterRepository;
    private $milestonePaymentSchedulesRepository;

    private $contractStatusHistoryRepo;

    public function __construct(ContractMasterRepository $contractMasterRepository,
                                MilestonePaymentSchedulesRepository $milestonePaymentSchedulesRepository,
                                contractStatusHistoryRepository $contractStatusHistoryRepo)
    {
        $this->contractMasterRepository = $contractMasterRepository;
        $this->milestonePaymentSchedulesRepository = $milestonePaymentSchedulesRepository;
        $this->contractStatusHistoryRepo = $contractStatusHistoryRepo;
    }

    public function getTotalContracts(Request $request)
    {
        return $this->contractMasterRepository->getContractMasterData($request);
    }

    public function getContractTypeWiseActiveContracts(Request $request)
    {
        return $this->contractMasterRepository->getContractTypeWiseActiveContracts($request);
    }

    public function getContractExpiry(Request $request)
    {
        return $this->contractMasterRepository->getContractExpiryListGraph($request);
    }

    public function getContractMilestone(Request $request)
    {
        return $this->milestonePaymentSchedulesRepository->getContractMilestoneListGraph($request);
    }

    public function getTotalContractStatus(Request $request)
    {
        $statusCounts = $this->contractStatusHistoryRepo->getContractHistoryStatusCount($request);
        return response()->json($statusCounts);
    }

}
