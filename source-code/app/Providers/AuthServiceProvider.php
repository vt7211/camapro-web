<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Policies\NewsPolicy;
use App\Policies\categoriesPolicy;
use App\Policies\commentsPolicy;
use App\Policies\menusPolicy;
use App\Policies\settingsPolicy;
use App\Policies\usersPolicy;
use App\Policies\visitPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
        // news::class => NewsPolicy::class,
    ];
    public function boot()
    {
        //{"news.create":true,"news.publish":true,"news.list":true,"news.draft":true,"cate.list":true,"cate.create":true,"cate.update":true,"cate.delete":true,"comment.list":true,"comment.create":true,"comment.update":true,"comment.delete":true,"menu.list":true,"menu.create":true,"menu.update":true,"menu.delete":true,"setting.list":true,"setting.update":true,"user.list":true,"user.create":true,"user.update":true,"user.delete":true}
        // "news.create":true,"news.publish":true,"news.list":true,"news.draft":true,
        // "cate.list":true,"cate.create":true,"cate.update":true,"cate.delete":true,
        // "comment.list":true,"comment.create":true,"comment.update":true,"comment.delete":true,
        // "menu.list":true,"menu.create":true,"menu.update":true,"menu.delete":true,
        // "setting.list":true,"setting.update":true,
        // "user.list":true,"user.create":true,"user.update":true,"user.delete":true
        $this->registerPolicies();

        // Gate::resource('news', NewsPolicy::class);
        Gate::define('news.list', NewsPolicy::class . '@list');
        Gate::define('news.create', NewsPolicy::class . '@create');
        Gate::define('news.update', NewsPolicy::class . '@update');
        Gate::define('news.delete', NewsPolicy::class . '@delete');
        Gate::define('news.draft', NewsPolicy::class . '@draft');

        Gate::define('cate.list', categoriesPolicy::class . '@list');
        Gate::define('cate.create', categoriesPolicy::class . '@create');
        Gate::define('cate.update', categoriesPolicy::class . '@update');
        Gate::define('cate.delete', categoriesPolicy::class . '@delete');

        Gate::define('comment.list', commentsPolicy::class . '@list');
        Gate::define('comment.create', commentsPolicy::class . '@create');
        Gate::define('comment.update', commentsPolicy::class . '@update');
        Gate::define('comment.delete', commentsPolicy::class . '@delete');

        Gate::define('visit.list', visitPolicy::class . '@list');
        Gate::define('visit.update', visitPolicy::class . '@update');
        Gate::define('visit.delete', visitPolicy::class . '@delete');
        
        Gate::define('menu.list', menusPolicy::class . '@list');
        Gate::define('menu.create', menusPolicy::class . '@create');
        Gate::define('menu.update', menusPolicy::class . '@update');
        Gate::define('menu.delete', menusPolicy::class . '@delete');

        Gate::define('setting.list', settingsPolicy::class . '@list');
        Gate::define('setting.update', settingsPolicy::class . '@update');

        Gate::define('user.list', usersPolicy::class . '@list');
        Gate::define('user.create', usersPolicy::class . '@create');
        Gate::define('user.update', usersPolicy::class . '@update');
        Gate::define('user.delete', usersPolicy::class . '@delete');
        Gate::define('user.changerole', usersPolicy::class . '@changerole');
    }
}
