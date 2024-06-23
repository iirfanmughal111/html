<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Models\Game;
use App\Observers\GameObserver;
use App\Models\GameGuide;
use App\Observers\GameGuideObserver;
use App\Models\CoacheRating;
use App\Observers\CoacheRatingObserver;
use App\Models\User;
use App\Observers\UserObserver;
use Illuminate\Support\Facades\URL;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        Game::observe(GameObserver::class);
        GameGuide::observe(GameGuideObserver::class);
        CoacheRating::observe(CoacheRatingObserver::class);
        User::observe(UserObserver::class);
		URL::forceScheme('http');
    }
}
