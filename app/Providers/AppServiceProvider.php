<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\User;
use App\Models\Message;
use Illuminate\Container\Container;
use Illuminate\Support\ServiceProvider;
use App\Services\MessageBoard\BoardService;
use App\Services\Storage\UserEloquentStorage;
use App\Services\Storage\MessageEloquentStorage;
use App\Services\Registration\RegistrationService;
use App\Services\MessageBoard\BoardServiceInterface;
use App\Services\Storage\Contracts\UserStorageInterface;
use App\Services\Registration\RegistrationServiceInterface;
use App\Services\Storage\Contracts\MessageStorageInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        $this->registerStorage();
        $this->registerBoardService();
        $this->registerRegistrationService();
    }

    /**
     * @return void
     */
    protected function registerStorage(): void
    {
        $this->app->bind(UserStorageInterface::class, static function () {
            return new UserEloquentStorage(new User());
        });

        $this->app->bind(MessageStorageInterface::class, static function () {
            return new MessageEloquentStorage(new Message());
        });
    }

    /**
     * @return void
     */
    protected function registerBoardService(): void
    {
        $this->app->bind(BoardServiceInterface::class, static function (Container $container) {
            return new BoardService(
                $container->make(UserStorageInterface::class),
                $container->make(MessageStorageInterface::class)
            );
        });
    }

    /**
     * @return void
     */
    protected function registerRegistrationService(): void
    {
        $this->app->bind(RegistrationServiceInterface::class, static function (Container $container) {
            return new RegistrationService($container->make(UserStorageInterface::class), 10);
        });
    }
}
