<?php

namespace App\Http\Controllers\API;

use App\Exceptions\CommonException;
use App\Exceptions\ContractCreationException;
use App\Http\Requests\API\ApproveDocumentRequest;
use App\Http\Requests\API\CreateContractHistoryAPIRequest;
use App\Http\Requests\API\RejectDocumentAPIRequest;
use App\Http\Requests\API\UpdateContractHistoryAPIRequest;
use App\Http\Requests\CreateContractRequest;
use App\Http\Requests\CreateContractHistoryAttachmentRequest;
use App\Models\ContractHistory;
use App\Repositories\ContractHistoryRepository;
use App\Repositories\ErpDocumentAttachmentsRepository;
use App\Services\ContractAmendmentService;
use App\Services\ContractHistoryService;
use App\Utilities\ContractManagementUtils;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\ContractHistoryResource;
use Response;

/**
 * Class ContractHistoryController
 * @package App\Http\Controllers\API
 */

class ContractHistoryAPIController extends AppBaseController
{
    /** @var  ContractHistoryRepository */
    private $contractHistoryRepository;
    protected $contractHistoryService;
    protected $contractAmendmentService;
    protected $erpDocumentAttachmentsRepository ;

    const UNEXPECTED_ERROR_MESSAGE = 'An unexpected error occurred.';

    public function __construct(ContractHistoryRepository $contractHistoryRepo,
                                ContractHistoryService $contractHistoryService,
                                ErpDocumentAttachmentsRepository  $erpDocumentAttachmentsRepository,
                                ContractAmendmentService $contractAmendmentService)
    {
        $this->contractHistoryRepository = $contractHistoryRepo;
        $this->contractHistoryService = $contractHistoryService;
        $this->erpDocumentAttachmentsRepository = $erpDocumentAttachmentsRepository;
        $this->contractAmendmentService = $contractAmendmentService;
    }

    /**
     * Display a listing of the ContractHistory.
     * GET|HEAD /contractHistories
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $contractHistories = $this->contractHistoryRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse
        (
            ContractHistoryResource::collection($contractHistories), 'Contract Histories retrieved successfully'
        );
    }

    /**
     * Store a newly created ContractHistory in storage.
     * POST /contractHistories
     *
     * @param CreateContractHistoryAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateContractHistoryAPIRequest $request)
    {
        $input = $request->all();

        $contractHistory = $this->contractHistoryRepository->create($input);

        return $this->sendResponse
        (
            new ContractHistoryResource($contractHistory), 'Contract History saved successfully'
        );
    }

    /**
     * Display the specified ContractHistory.
     * GET|HEAD /contractHistories/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var ContractHistory $contractHistory */
        $contractHistory = $this->contractHistoryRepository->find($id);

        if (empty($contractHistory))
        {
            return $this->sendError('Contract History not found');
        }

        return $this->sendResponse
        (
            new ContractHistoryResource($contractHistory), 'Contract History retrieved successfully'
        );
    }

    /**
     * Update the specified ContractHistory in storage.
     * PUT/PATCH /contractHistories/{id}
     *
     * @param int $id
     * @param UpdateContractHistoryAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateContractHistoryAPIRequest $request)
    {
        $input = $request->all();

        /** @var ContractHistory $contractHistory */
        $contractHistory = $this->contractHistoryRepository->find($id);

        if (empty($contractHistory))
        {
            return $this->sendError('Contract History not found to update');
        }

        $contractHistory = $this->contractHistoryRepository->update($input, $id);

        return $this->sendResponse
        (
            new ContractHistoryResource($contractHistory), 'ContractHistory updated successfully'
        );
    }

    /**
     * Remove the specified ContractHistory from storage.
     * DELETE /contractHistories/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var ContractHistory $contractHistory */
        $contractHistory = $this->contractHistoryRepository->find($id);

        if (empty($contractHistory))
        {
            return $this->sendError('Contract History not found to delete');
        }

        $contractHistory->delete();

        return $this->sendSuccess('Contract History deleted successfully');
    }

    public function createContractHistory(CreateContractRequest $request)
    {
        $request->validated();
        try
        {
            $contractUuid =  $this->contractHistoryRepository->createContractHistory($request);
            return $this->sendResponse($contractUuid,'Successfully Created');
        } catch (ContractCreationException $e)
        {
            return $this->sendError($e->getMessage(), 500);
        } catch (\Exception $e)
        {
            return $this->sendError(self::UNEXPECTED_ERROR_MESSAGE . ' ' .$e->getMessage() , 500);
        }
    }

    public function getCategoryWiseContractData(Request $request)
    {
        $input = $request->all();
        try
        {
            $data = $this->contractHistoryService->getCategoryWiseContractData($input);
            $responseData = ['data' => $data];
            return response()->json($responseData);
        } catch (\Exception $e)
        {
            return ['error' => $e->getMessage()];
        }
    }

    public function deleteContractHistory(Request $request)
    {
        try
        {
            $this->contractHistoryService->deleteContractHistory($request->all());
            return $this->sendSuccess('Successfully deleted');
        } catch (ContractCreationException $e)
        {
            return $this->sendError($e->getMessage(), 500);
        } catch (\Exception $e)
        {
            return $this->sendError(self::UNEXPECTED_ERROR_MESSAGE . ' ' . $e->getMessage(), 500);
        }
    }

    public function getContractHistory(Request $request)
    {
        try
        {
            return $this->contractHistoryRepository->getContractHistory($request);
        } catch (\Exception $e)
        {
            return $this->sendError(self::UNEXPECTED_ERROR_MESSAGE . ' ' . $e->getMessage(), 500);
        }
    }

    public function updateContractStatus(Request $request)
    {
        $contractCategories = [
            1 => 'common.successfully_amendment_status_updated',
            2 => 'common.successfully_addendum_status_updated',
            3 => 'common.successfully_renewal_status_updated',
            4 => 'common.successfully_extension_status_updated',
            5 => 'common.successfully_revision_status_updated',
            6 => 'common.successfully_termination_status_updated',
        ];

        try
        {
            $input = $request->all();
            $categoryId = $input['category'];
            $this->contractHistoryService->updateContractStatus($request->all());
            return $this->sendSuccess(trans($contractCategories[$categoryId]));
        } catch (ContractCreationException $e)
        {
            return $this->sendError($e->getMessage(), 500);
        } catch (\Exception $e)
        {
            return $this->sendError(self::UNEXPECTED_ERROR_MESSAGE . ' ' . $e->getMessage(), 500);

        }
    }

    public function getContractApprovals(Request $request)
    {
        try
        {
            return $this->contractHistoryService->getContractApprovals($request);

        } catch (\Exception $e)
        {
            return ['error' => $e->getMessage()];
        }
    }

    public function approveContract(ApproveDocumentRequest $request)
    {
        try
        {
            $this->contractHistoryService->approveContract($request);
            return $this->sendResponse([], trans('common.document_approved_successfully'));
        } catch (CommonException $ex)
        {
            return $this->sendError($ex->getMessage(), 500);
        } catch (\Exception $e)
        {
            return $this->sendError($e->getMessage(), 500);
        }
    }

    public function rejectContract(RejectDocumentAPIRequest $request)
    {
        try
        {
            $this->contractHistoryService->rejectContract($request);
            return $this->sendResponse([], trans('common.document_successfully_rejected'));
        } catch (CommonException $ex)
        {
            return $this->sendError($ex->getMessage(), 500);
        } catch (\Exception $ex)
        {
            return $this->sendError($ex->getMessage(), 500);
        }
    }

    public function updateExtendStatus(Request $request)
    {
        try
        {
            $this->contractHistoryService->updateExtendStatus($request->all());
            return $this->sendSuccess(trans('common.successfully_status_updated'));
        } catch (ContractCreationException $e)
        {
            return $this->sendError($e->getMessage(), 500);
        } catch (\Exception $e)
        {
            return $this->sendError(self::UNEXPECTED_ERROR_MESSAGE . ' ' . $e->getMessage(), 500);
        }

    }

    public function contractHistoryAttachments(CreateContractHistoryAttachmentRequest $request)
    {
        try
        {
            $formData = $request->all();
            $companySystemID = $formData['selectedCompanyID'] ?? 0;
            $currentContractDetails = ContractManagementUtils::checkContractExist($formData['uuid'], $companySystemID);

            if ($request->has('attachmentID') && $request->input('attachmentID'))
            {
                $attachment = $this->erpDocumentAttachmentsRepository
                    ->updateDocumentAttachments($request, $request->input('attachmentID'));
            } else
            {
                $documentSystemCode = $formData['contractHistoryId'] ?? $currentContractDetails->id;
                $attachment = $this->erpDocumentAttachmentsRepository
                    ->saveDocumentAttachments($request, $documentSystemCode);
            }

            if (!$attachment['status'])
            {
                $errorCode = $attachment['code'] ?? 404;
                return $this->sendError($attachment['message'], $errorCode);
            }


            return $this->sendResponse([], 'Successfully Created');
        } catch (\Exception $e)
        {
            return $this->sendError(self::UNEXPECTED_ERROR_MESSAGE . ' ' . $e->getMessage(), 500);
        }
    }

    public function getContractHistoryAttachments(Request $request)
    {
        try
        {
            $attachment = $this->erpDocumentAttachmentsRepository
                ->getHistoryAttachments($request);

            return $this->sendResponse($attachment, 'Successfully Retrieved');
        } catch (\Exception $e)
        {
            return $this->sendError(self::UNEXPECTED_ERROR_MESSAGE . ' ' . $e->getMessage(), 500);
        }
    }

    public function deleteHistoryAttachment(Request $request)
    {
        try
        {
            $formData = $request->all();
            $attachmentId = $formData['attachmentID'];
            return $this->erpDocumentAttachmentsRepository
                ->deleteHistoryAttachment($attachmentId);
        } catch (\Exception $e)
        {
            return $this->sendError(self::UNEXPECTED_ERROR_MESSAGE . ' ' . $e->getMessage(), 500);
        }
    }
    public function contractHistoryDelete(Request $request)
    {
        try
        {
            $this->contractHistoryService->contractHistoryDelete($request->all());
            return $this->sendSuccess('Contract extension record successfully deleted');
        } catch (ContractCreationException $e)
        {
            return $this->sendError($e->getMessage(), 500);
        } catch (\Exception $e)
        {
            return $this->sendError(self::UNEXPECTED_ERROR_MESSAGE . ' ' . $e->getMessage(), 500);
        }
    }

    public function updateContractStatusAmendment(Request $request)
    {
        try
        {
            $this->contractAmendmentService->updateContractStatusAmendment($request->all());
            return $this->sendSuccess('Contract amendment active successfully');
        } catch (ContractCreationException $e)
        {
            return $this->sendError($e->getMessage(), 500);
        } catch (\Exception $e)
        {
            return $this->sendError(self::UNEXPECTED_ERROR_MESSAGE . ' ' . $e->getMessage(), 500);

        }
    }

    public function deleteContractBoqAmend(Request $request)
    {
        try
        {
            $this->contractAmendmentService->deleteContractBoqAmendment($request->all());
            return $this->sendSuccess('BOQ Item deleted successfully');
        }
        catch (ContractCreationException $e)
        {
            return $this->sendError($e->getMessage(), 500);
        }
        catch (\Exception $e)
        {
            return $this->sendError(self::UNEXPECTED_ERROR_MESSAGE . ' ' . $e->getMessage(), 500);
        }
    }

    public function deleteMilestoneAmd(Request $request)
    {
        try
        {
            $this->contractAmendmentService->deleteMilestoneAmd($request->all());
            return $this->sendSuccess('Milestone deleted successfully');
        }
        catch (ContractCreationException $e)
        {
            return $this->sendError($e->getMessage(), 500);
        }
        catch (\Exception $e)
        {
            return $this->sendError(self::UNEXPECTED_ERROR_MESSAGE . ' ' . $e->getMessage(), 500);
        }
    }

    public function deleteContractDeliverablesAmd(Request $request)
    {
        try
        {
            $this->contractAmendmentService->deleteContractDeliverablesAmd($request->all());
            return $this->sendSuccess('Milestone deleted successfully');
        }
        catch (ContractCreationException $e)
        {
            return $this->sendError($e->getMessage(), 500);
        }
        catch (\Exception $e)
        {
            return $this->sendError(self::UNEXPECTED_ERROR_MESSAGE . ' ' . $e->getMessage(), 500);
        }
    }

    public function contractDocumentAmd(Request $request)
    {
        try
        {
           return $this->contractAmendmentService->getContractData($request->all());
        }
        catch (ContractCreationException $e)
        {
            return $this->sendError($e->getMessage(), 500);
        }
        catch (\Exception $e)
        {
            return $this->sendError(self::UNEXPECTED_ERROR_MESSAGE . ' ' . $e->getMessage(), 500);
        }
    }
    public function getContractAmdHistory(Request $request)
    {
        try
        {
             $data = $this->contractAmendmentService->getHistoryData($request->all());
            $responseData = ['data' => $data];
            return response()->json($responseData);
        }
        catch (ContractCreationException $e)
        {
            return $this->sendError($e->getMessage(), 500);
        }
        catch (\Exception $e)
        {
            return $this->sendError(self::UNEXPECTED_ERROR_MESSAGE . ' ' . $e->getMessage(), 500);
        }
    }

    public function confirmContractAmendment(Request $request)
    {
        try
        {
           $input = $request->all();
           $companyId = $input['selectedCompanyID'];
           $historyUuid = $input['historyUuid'];
           $contractUuid = $input['contractUuid'];
           $getContractHistory = ContractManagementUtils::getContractHistoryData($historyUuid);
           $getContractData = ContractManagementUtils::checkContractExist($contractUuid,$companyId);

            $this->contractHistoryService->confirmHistoryDocument
            (
                $getContractHistory->id,$getContractData->id,$companyId,1
            );

            return $this->sendSuccess('Contract amendment document confirmed successfully');
        }
        catch (ContractCreationException $e)
        {
            return $this->sendError($e->getMessage(), 500);
        }
        catch (\Exception $e)
        {
            return $this->sendError(self::UNEXPECTED_ERROR_MESSAGE . ' ' . $e->getMessage(), 500);
        }
    }

    public function additionalDocumentAmendment(Request $request)
    {
        try
        {
            $data = $this->contractAmendmentService->getAdditionalDocument($request->all());
            $responseData = ['data' => $data];
            return response()->json($responseData);
        }
        catch (ContractCreationException $e)
        {
            return $this->sendError($e->getMessage(), 500);
        }
        catch (\Exception $e)
        {
            return $this->sendError(self::UNEXPECTED_ERROR_MESSAGE . ' ' . $e->getMessage(), 500);
        }
    }
}
