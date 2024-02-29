<?php

namespace Jiny\Config;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Facades\Blade;

class JinyConfigServiceProvider extends ServiceProvider
{
    private $package = "jiny-config";
    public function boot()
    {
        // 모듈: 라우트 설정
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', $this->package);

        // 데이터베이스
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');



    }

    public function register()
    {
        /* 라이브와이어 컴포넌트 등록 */
        $this->app->afterResolving(BladeCompiler::class, function () {
            // Form => json 저장
            Livewire::component('WireConfigPHP', \Jiny\Table\Http\Livewire\WireConfigPHP::class);
        });
    }
}
