<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\PageRequest;
use App\Http\Requests\StoreUserMessage;
use App\Services\MessageBoard\BoardServiceInterface;
use App\Services\Storage\Contracts\MessageStorageInterface;

/**
 * Контроллер для обработки Message.
 */
class MessagesController extends BaseApiController
{
    /**
     * Display a listing of the resource.
     *
     * @param PageRequest             $request
     * @param MessageStorageInterface $storage
     *
     * @return JsonResponse
     */
    public function index(PageRequest $request, MessageStorageInterface $storage): JsonResponse
    {
        return response()->json(
            $this->formatResponseData(
                $storage->page($request->getPageNumber(), PageRequest::PAGE_SIZE, [], $request->getSortBy())),
            200,
            [PageRequest::HEADER_COUNT => $storage->count()]
        );
    }

    /**
     * Store a message in storage.
     *
     * @param StoreUserMessage      $request
     * @param BoardServiceInterface $service
     *
     * @return JsonResponse
     */
    public function store(StoreUserMessage $request, BoardServiceInterface $service): JsonResponse
    {
        $service->createMessage(
            $request->user()->id,
            $request->get('text'),
            $request->get('title')
        );

        return response()->json($this->formatResponseData([]), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param int                     $id
     * @param MessageStorageInterface $storage
     *
     * @return array
     */
    public function show($id, MessageStorageInterface $storage): array
    {
        return $this->formatResponseData($storage->find($id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int                   $id
     * @param Request               $request
     * @param BoardServiceInterface $service
     *
     * @return array
     *
     * @throws \Exception
     */
    public function destroy($id, Request $request, BoardServiceInterface $service): array
    {
        $service->deleteMessage($request->user()->id, (int) $id);

        return $this->formatResponseData('ok');
    }
}
