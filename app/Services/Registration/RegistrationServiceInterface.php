<?php

declare(strict_types=1);

namespace App\Services\Registration;

use App\Models\User;

interface RegistrationServiceInterface
{
    public function generateApiToken(): string;

    public function getUserByCredentials(string $email, string $password): ?User;

    public function register(string $email, string $password, string $name): User;
}
