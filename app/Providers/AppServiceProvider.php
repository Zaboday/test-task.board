<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Message;
use App\Models\User;
use App\Services\MessageBoard\BoardService;
use App\Services\MessageBoard\BoardServiceInterface;
use App\Services\Storage\Contracts\MessageStorageInterface;
use App\Services\Storage\Contracts\UserStorageInterface;
use App\Services\Storage\MessageEloquentStorage;
use App\Services\Storage\UserEloquentStorage;
use Illuminate\Container\Container;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        $this->registerStorage();
        $this->registerBoardService();
    }

    /**
     * @return void
     */
    protected function registerStorage(): void
    {
        $this->app->bind(UserStorageInterface::class, static function (Container $container) {
            return new UserEloquentStorage(new User());
        });

        $this->app->bind(MessageStorageInterface::class, static function (Container $container) {
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
     * Bootstrap any application services.
     */
    public function boot()
    {
    }
}
