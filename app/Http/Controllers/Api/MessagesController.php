<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
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
     * @param MessageStorageInterface $storage
     *
     * @return JsonResponse
     */
    public function index(MessageStorageInterface $storage): JsonResponse
    {
        return response()->json($this->formatData($storage->page(1, 10)));
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
        $service->createMessage($request->user()->id, $request->get('text'));

        return response()->json($this->formatData([]), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param int                     $id
     * @param MessageStorageInterface $storage
     *
     * @return JsonResponse
     */
    public function show($id, MessageStorageInterface $storage): JsonResponse
    {
        return response()->json($this->formatData($storage->find($id)));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int                   $id
     * @param Request               $request
     * @param BoardServiceInterface $service
     *
     * @return JsonResponse
     *
     * @throws \Exception
     */
    public function destroy($id, Request $request, BoardServiceInterface $service): JsonResponse
    {
        $service->deleteMessage($request->user()->id, (int)$id);

        return response()->json($this->formatData('ok'));
    }
}
