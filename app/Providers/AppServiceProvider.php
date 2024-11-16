<?php

namespace App\Providers;

use App\Features\Ftp\Data\Repositories\FtpRepositoryImpl;
use App\Features\Ftp\Domain\Repositories\FtpRepositoryInterface;
use App\Services\SendinblueService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(SendinblueService::class, function ($app) {
            return new SendinblueService();
        });

        // Registrar la implementación del repositorio FTP
        $this->app->singleton(FtpRepositoryInterface::class, FtpRepositoryImpl::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
