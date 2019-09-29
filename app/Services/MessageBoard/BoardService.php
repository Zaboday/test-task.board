<?php

declare(strict_types=1);

namespace App\Services\MessageBoard;

use App\Models\User;
use App\Exceptions\EntityNotFoundException;
use App\Services\Storage\Contracts\UserStorageInterface;
use App\Services\Storage\Contracts\MessageStorageInterface;

/**
 * Board actions.
 */
class BoardService implements BoardServiceInterface
{
    /**
     * @var UserStorageInterface
     */
    private $userStorage;

    /**
     * @var MessageStorageInterface
     */
    protected $messageStorage;

    public function __construct(UserStorageInterface $userStorage, MessageStorageInterface $messageStorage)
    {
        $this->userStorage = $userStorage;
        $this->messageStorage = $messageStorage;
    }

    /**
     * @param int    $userId
     * @param string $text
     *
     * @return bool
     */
    public function createMessage(int $userId, string $text): bool
    {
        $this->findUser($userId);

        return $this->messageStorage->create([
            [
                'user_id' => $userId,
                'text' => $text,
            ],
        ]);
    }

    /**
     * Delete message by author or admin.
     *
     * @param int $userIdWhoDelete
     * @param int $messageId
     *
     * @throws \Exception
     */
    public function deleteMessage(int $userIdWhoDelete, int $messageId): void
    {
        $user = $this->findUser($userIdWhoDelete);
        $message = $this->messageStorage->find($messageId);
        if (!$message) {
            throw new EntityNotFoundException('Message not found.');
        }
        if ($message->user_id !== $user->id && !$user->is_admin) {
            throw new \InvalidArgumentException('Only Author or Admin can delete Message.');
        }
        $message->delete();
    }

    /**
     * @param int $userId
     *
     * @return User
     */
    private function findUser(int $userId): User
    {
        $user = $this->userStorage->find($userId);
        if (!$user) {
            throw new EntityNotFoundException('User not found.');
        }

        return $user;
    }
}
