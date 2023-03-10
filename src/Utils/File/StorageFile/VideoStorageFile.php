<?php

namespace MyCustom\Utils\File\StorageFile;

use MyCustom\Utils\File\StorageFileUtil;

use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use ProtoneMedia\LaravelFFMpeg\MediaOpener;
use ProtoneMedia\LaravelFFMpeg\Filters\WatermarkFactory;

use FFMpeg\Format\FormatInterface;
use FFMpeg\Format\Video\X264;
use FFMpeg\Format\Video\WebM;

use Closure;

final class VideoStorageFile extends StorageFileUtil
{
    public MediaOpener $file;

    private int $width;
    private int $height;
    private int $seconds;

    private ?Closure $progressCallback;

    function __construct(string $dirName, string $baseName)
    {
        parent::__construct($dirName, $baseName);

        $this->setChild();
    }
    function __clone()
    {
        parent::__clone();

        $this->file             = clone $this->file;

        $this->width            = clone $this->width;
        $this->height           = clone $this->height;
        $this->seconds          = clone $this->seconds;

        $this->progressCallback = clone $this->progressCallback;
    }
    public function params(): array
    {
        $params = [
            "width"   => $this->width,
            "height"  => $this->height,
            "seconds" => $this->seconds,
        ];

        return array_merge($params, parent::params());
    }

    public function save(string $dirName, string $baseName, string $format = null): self
    {
        $this->file->export();

        if (!is_null($this->progressCallback)) $this->file->onProgress($this->progressCallback);

        $this->file->inFormat($this->format($format))->save($dirName . "/tmp/" . $baseName);
        rename(storage_path("app/" . $dirName . "/tmp/" . $baseName), storage_path("app/" . $dirName . "/" . $baseName));
        $this->reset($dirName, $baseName);
        return $this;
    }
    protected function setChild(): void
    {
        $this->file = FFMpeg::open($this->filePath);

        $stream        = $this->file->getVideoStream();
        $this->width   = $stream->get("width");
        $this->height  = $stream->get("height");
        $this->seconds = $stream->getDurationInSeconds();

        $this->progressCallback = null;
    }

    public function width(): int
    {
        return $this->width;
    }
    public function height(): int
    {
        return $this->height;
    }
    public function seconds(): int
    {
        return $this->seconds;
    }

    public function onProgress(Closure $progressCallback): self
    {
        $this->progressCallback = $progressCallback;
        return $this;
    }
    public function resize(int $width, int $height): self
    {
        $this->file->resize($width, $height);
        return $this;
    }
    public function watermark(Closure $watermark): self
    {
        $this->file->addWatermark($watermark);
        return $this;
    }
    public function watermarkFactory(): WatermarkFactory
    {
        return new WatermarkFactory;
    }
    public function filter(Closure|array $filter): self
    {
        $this->file->addFilter($filter);
        return $this;
    }


    // 動画の形式を変更する
    private function format(string $format = null): FormatInterface
    {
        return match ($format) {
            "mp4"  => new X264("libmp3lame", "libx264"),
            "webm" => new WebM(),
        };
    }
    private function encode(string $extension): self
    {
        $this->file->inFormat($this->format($extension));
        return $this->saveRename($this->fileName . "." . $extension);
    }
    public function encodeMP4(): self
    {
        return $this->encode("mp4");
    }
    public function encodeWEBM(): self
    {
        return $this->encode("webm");
    }
}
