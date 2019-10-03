<?php

declare(strict_types=1);

namespace App\Services\Registration;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Services\Storage\Contracts\UserStorageInterface;

class RegistrationService implements RegistrationServiceInterface
{
    /**
     * @var int
     */
    protected $apiTokenLength = 80;

    /**
     * @var UserStorageInterface
     */
    private $storage;

    public function __construct(UserStorageInterface $storage, int $apiTokenLength = 80)
    {
        $this->storage = $storage;
        $this->apiTokenLength = $apiTokenLength;
    }

    /**
     * @param string $email
     * @param string $password
     *
     * @return User
     */
    public function getUserByCredentials(string $email, string $password): ?User
    {
        /** @var User $user */
        $user = $this->findUserByEmail($email);

        if (!$user) {
            return null;
        }

        if (!Hash::check($password, $user->password)) {
            return null;
        }

        $user->api_token = $this->generateApiToken();
        $user->save();

        return $user;
    }

    /**
     * @return string
     */
    public function generateApiToken(): string
    {
        return Str::random($this->apiTokenLength);
    }

    /**
     * @param string $email
     * @param string $password
     * @param string $name
     *
     * @return User
     *
     * @throws \Exception
     */
    public function register(string $email, string $password, string $name): User
    {
        if ($this->findUserByEmail($email)) {
            throw new \InvalidArgumentException(sprintf('User with email "%s" already exists.', $email));
        }

        $this->storage->create([
            'email' => $email,
            'password' => Hash::make($password),
            'name' => $name,
            'is_admin' => false,
        ]);

        return $this->findUserByEmail($email);
    }

    /**
     * @param string $email
     *
     * @return User|null
     */
    private function findUserByEmail(string $email): ?User
    {
        return $this->storage->findBy(['email', '=', $email]);
    }
}
