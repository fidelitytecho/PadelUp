<?php

namespace App\Providers;

use App\Repositories\AcademyRepository;
use App\Repositories\admin\A_CreateEventRepository;
use App\Repositories\admin\Bookings\A_AllBookingsRepository;
use App\Repositories\admin\Interfaces\A_CreateEventInterface;
use App\Repositories\admin\Interfaces\Bookings\A_AllBookingsInterface;
use App\Repositories\BookingRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\CompanyRepository;
use App\Repositories\CourtRepository;
use App\Repositories\CurrencyRepository;
use App\Repositories\CustomerRepository;
use App\Repositories\FcmRepository;
use App\Repositories\Interfaces\AcademyInterface;
use App\Repositories\Interfaces\BookingInterface;
use App\Repositories\Interfaces\CategoryInterface;
use App\Repositories\Interfaces\CompanyInterface;
use App\Repositories\Interfaces\CourtInterface;
use App\Repositories\Interfaces\CurrencyInterface;
use App\Repositories\Interfaces\CustomerInterface;
use App\Repositories\Interfaces\FcmInterface;
use App\Repositories\Interfaces\NewsInterface;
use App\Repositories\Interfaces\PaymentInterface;
use App\Repositories\Interfaces\PaymobInterface;
use App\Repositories\Interfaces\PlayerInterface;
use App\Repositories\Interfaces\PlaysetInterface;
use App\Repositories\Interfaces\ProductInterface;
use App\Repositories\Interfaces\PurchaseAttemptInterface;
use App\Repositories\Interfaces\PurchaseInterface;
use App\Repositories\Interfaces\ReceiptInterface;
use App\Repositories\Interfaces\ServiceInterface;
use App\Repositories\Interfaces\UserInterface;
use App\Repositories\Interfaces\WalletHistoryInterface;
use App\Repositories\NewsRepository;
use App\Repositories\PaymentRepository;
use App\Repositories\PaymobRepository;
use App\Repositories\PlayerRepository;
use App\Repositories\PlaysetRepository;
use App\Repositories\ProductRepository;
use App\Repositories\PurchaseAttemptRepository;
use App\Repositories\PurchaseRepository;
use App\Repositories\ReceiptRepository;
use App\Repositories\ServiceRepository;
use App\Repositories\UserRepository;
use App\Repositories\WalletHistoryRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            UserInterface::class,
            UserRepository::class);
        $this->app->bind(
            FcmInterface::class,
            FcmRepository::class);
        $this->app->bind(
            CategoryInterface::class,
            CategoryRepository::class);
        $this->app->bind(
            BookingInterface::class,
            BookingRepository::class);
        $this->app->bind(
            PaymobInterface::class,
            PaymobRepository::class);
        $this->app->bind(
            ServiceInterface::class,
            ServiceRepository::class);
        $this->app->bind(
            CustomerInterface::class,
            CustomerRepository::class);
        $this->app->bind(
            CourtInterface::class,
            CourtRepository::class);
        $this->app->bind(
            PaymentInterface::class,
            PaymentRepository::class);
        $this->app->bind(
            PurchaseInterface::class,
            PurchaseRepository::class);
        $this->app->bind(
            WalletHistoryInterface::class,
            WalletHistoryRepository::class);
        $this->app->bind(
            PurchaseAttemptInterface::class,
            PurchaseAttemptRepository::class);
        $this->app->bind(
            A_AllBookingsInterface::class,
            A_AllBookingsRepository::class);
        $this->app->bind(
            NewsInterface::class,
            NewsRepository::class);
        $this->app->bind(
            A_CreateEventInterface::class,
            A_CreateEventRepository::class);
        $this->app->bind(
            CurrencyInterface::class,
            CurrencyRepository::class);
        $this->app->bind(
            CompanyInterface::class,
            CompanyRepository::class);
        $this->app->bind(
            AcademyInterface::class,
            AcademyRepository::class);
        $this->app->bind(
            ProductInterface::class,
            ProductRepository::class);
        $this->app->bind(
            PlaysetInterface::class,
            PlaysetRepository::class);
        $this->app->bind(
            PlayerInterface::class,
            PlayerRepository::class);
        $this->app->bind(
            ReceiptInterface::class,
            ReceiptRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
