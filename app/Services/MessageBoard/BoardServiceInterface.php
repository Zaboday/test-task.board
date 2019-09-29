<?php

declare(strict_types=1);

namespace App\Services\MessageBoard;

/**
 * Interface BoardServiceInterface.
 */
interface BoardServiceInterface
{
    /**
     * @param int    $userId
     * @param string $text
     *
     * @return bool
     */
    public function createMessage(int $userId, string $text): bool;

    /**
     * Delete message by author or admin.
     *
     * @param int $userIdWhoDelete
     * @param int $messageId
     *
     * @throws \Exception
     */
    public function deleteMessage(int $userIdWhoDelete, int $messageId): void;
}
