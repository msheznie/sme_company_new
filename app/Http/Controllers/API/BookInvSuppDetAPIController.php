<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateBookInvSuppDetAPIRequest;
use App\Http\Requests\API\UpdateBookInvSuppDetAPIRequest;
use App\Models\BookInvSuppDet;
use App\Repositories\BookInvSuppDetRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\BookInvSuppDetResource;
use Response;

/**
 * Class BookInvSuppDetController
 * @package App\Http\Controllers\API
 */

class BookInvSuppDetAPIController extends AppBaseController
{
    /** @var  BookInvSuppDetRepository */
    private $bookInvSuppDetRepository;
    protected $errMessage = 'Book Inv Supp Det not found';

    public function __construct(BookInvSuppDetRepository $bookInvSuppDetRepo)
    {
        $this->bookInvSuppDetRepository = $bookInvSuppDetRepo;
    }

    /**
     * Display a listing of the BookInvSuppDet.
     * GET|HEAD /bookInvSuppDets
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $bookInvSuppDets = $this->bookInvSuppDetRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(BookInvSuppDetResource::collection($bookInvSuppDets),
            'Book Inv Supp Dets retrieved successfully');
    }

    /**
     * Store a newly created BookInvSuppDet in storage.
     * POST /bookInvSuppDets
     *
     * @param CreateBookInvSuppDetAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateBookInvSuppDetAPIRequest $request)
    {
        $input = $request->all();

        $bookInvSuppDet = $this->bookInvSuppDetRepository->create($input);

        return $this->sendResponse(new BookInvSuppDetResource($bookInvSuppDet),
            'Book Inv Supp Det saved successfully');
    }

    /**
     * Display the specified BookInvSuppDet.
     * GET|HEAD /bookInvSuppDets/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var BookInvSuppDet $bookInvSuppDet */
        $bookInvSuppDet = $this->bookInvSuppDetRepository->find($id);

        if (empty($bookInvSuppDet))
        {
            return $this->sendError($this->errMessage);
        }

        return $this->sendResponse(new BookInvSuppDetResource($bookInvSuppDet),
            'Book Inv Supp Det retrieved successfully');
    }

    /**
     * Update the specified BookInvSuppDet in storage.
     * PUT/PATCH /bookInvSuppDets/{id}
     *
     * @param int $id
     * @param UpdateBookInvSuppDetAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateBookInvSuppDetAPIRequest $request)
    {
        $input = $request->all();

        /** @var BookInvSuppDet $bookInvSuppDet */
        $bookInvSuppDet = $this->bookInvSuppDetRepository->find($id);

        if (empty($bookInvSuppDet))
        {
            return $this->sendError($this->errMessage);
        }

        $bookInvSuppDet = $this->bookInvSuppDetRepository->update($input, $id);

        return $this->sendResponse(new BookInvSuppDetResource($bookInvSuppDet),
            'BookInvSuppDet updated successfully');
    }

    /**
     * Remove the specified BookInvSuppDet from storage.
     * DELETE /bookInvSuppDets/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var BookInvSuppDet $bookInvSuppDet */
        $bookInvSuppDet = $this->bookInvSuppDetRepository->find($id);

        if (empty($bookInvSuppDet))
        {
            return $this->sendError($this->errMessage);
        }

        $bookInvSuppDet->delete();

        return $this->sendSuccess('Book Inv Supp Det deleted successfully');
    }
}
