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
     * @param string $title
     *
     * @return bool
     */
    public function createMessage(int $userId, string $text, string $title): bool;

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
