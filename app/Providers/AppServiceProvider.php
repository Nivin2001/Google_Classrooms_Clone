<?php

namespace App\Providers;

use App\Models\Classwork;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        // $this->app->bind('x',function(){
        //     return new \App\Services\x();
        // });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //ما رح يتنفذوا لانه السايكل للارافيل ما بتروح ع session و بتبداه فما حيقدر يحدد اليوزر اذا ،فبنعرفه بiddleware 
        // $user = Auth::user();
        // App::setLocale($user->profile->locale);
        // App::setlocale('ar');
        Paginator::useBootstrapFive();
        // Paginator::defaultView();
        Relation::enforceMorphMap([
            'classwork' => Classwork::class,
            'post' => Post::class,
            'user' => User::class,
        ]);
    }
}
