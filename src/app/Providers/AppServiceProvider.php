<?php
namespace App\Providers;

use Domain\Repositories\TestCaseRepositoryInterface;
use Illuminate\Support\ServiceProvider;
use Infra\Repositories\TestCaseRepository;

/**
 * Class AppServiceProvider
 * @package App\Providers
 */
class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(TestCaseRepositoryInterface::class, TestCaseRepository::class);
    }

    public function boot(): void
    {
    }
}
