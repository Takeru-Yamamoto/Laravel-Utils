<?php

namespace MyCustom\UtilsResource;

use Illuminate\Support\ServiceProvider as Provider;

use MyCustom\Utils\Manager\DateUtilManager;
use MyCustom\Utils\Manager\FileUtilManager;
use MyCustom\Utils\Manager\HttpUtilManager;
use MyCustom\Utils\Manager\TimeUtilManager;

class ServiceProvider extends Provider
{
    /**
     * publications配下をpublishする際に使うルートパス
     *
     * @var string
     */
    private string $publicationsPath = __DIR__ . "/publications";

    public function register(): void
    {
        $this->app->singleton("DateUtil", function () {
            return new DateUtilManager();
        });

        $this->app->singleton("FileUtil", function () {
            return new FileUtilManager();
        });

        $this->app->singleton("HttpUtil", function () {
            return new HttpUtilManager();
        });

        $this->app->singleton("TimeUtil", function () {
            return new TimeUtilManager();
        });
    }

    public function boot(): void
    {
        $this->publications();
    }

    /**
     * publicationsディレクトリ配下を公開する
     */
    private function publications()
    {
        // 共通タグ
        $this->publishes([
            $this->publicationsPath . "/config" => config_path(),
        ], "mycustom");

        // Presentation Domain のみ
        $this->publishes([
            $this->publicationsPath . "/config" => config_path(),
        ], "mycustom-utils");
    }
}
