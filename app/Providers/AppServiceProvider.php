<?php

namespace App\Providers;

use App\Models\CustomField;
use App\Models\Event;
use App\Models\Group;
use App\Models\Member;
use App\Models\Page;
use App\Models\Tag;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Explicit bindings so path-mode tenant routes (/{tenant_slug}/…) resolve
        // route parameters correctly regardless of implicit-binding edge cases.
        Route::model('page', Page::class);
        Route::model('event', Event::class);
        Route::model('member', Member::class);
        Route::model('group', Group::class);
        Route::model('tag', Tag::class);
        Route::model('customField', CustomField::class);
    }
}
