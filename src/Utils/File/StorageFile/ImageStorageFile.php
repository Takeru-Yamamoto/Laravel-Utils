<?php

namespace MyCustom\Utils\File\StorageFile;

use MyCustom\Utils\File\StorageFileUtil;

use Intervention\Image\Facades\Image as FacadeImage;
use Intervention\Image\Image;

final class ImageStorageFile extends StorageFileUtil
{
    public Image $file;

    private int $width;
    private int $height;

    private array $positions;

    function __construct(string $dirName, string $baseName)
    {
        parent::__construct($dirName, $baseName);

        $this->positions = [
            "top-left",
            "top",
            "top-right",
            "left",
            "center",
            "right",
            "bottom-left",
            "bottom",
            "bottom-right",
        ];

        $this->setChild();
    }
    function __clone()
    {
        parent::__clone();

        $this->file   = clone $this->file;

        $this->width  = clone $this->width;
        $this->height = clone $this->height;
    }
    public function params(): array
    {
        $params = [
            "width"  => $this->width,
            "height" => $this->height,
        ];

        return array_merge($params, parent::params());
    }

    public function save(string $dirName, string $baseName, string $format = null): self
    {
        if (!is_null($format)) $this->encode($format, 100);
        $this->file->save(storage_path("app/" . $dirName . "/" . $baseName));
        $this->reset($dirName, $baseName);
        return $this;
    }
    protected function setChild(): void
    {
        $this->file = FacadeImage::make($this->storagePath);

        $this->width    = $this->file->width();
        $this->height   = $this->file->height();
    }

    public function width(): int
    {
        return $this->width;
    }
    public function height(): int
    {
        return $this->height;
    }

    // 画像の形式を変更する
    private function encode(string $extension, int $quality = 100): self
    {
        $this->file = $this->file->encode($extension, $quality);
        $this->setFileUtil($this->dirName, $this->fileName . "." . $extension);
        return $this;
    }
    public function encodeJPG(int $quality = 100): self
    {
        return $this->encode("jpg", $quality);
    }
    public function encodePNG(): self
    {
        return $this->encode("png");
    }
    public function encodeGIF(): self
    {
        return $this->encode("gif");
    }
    public function encodeWEBP(): self
    {
        return $this->encode("webp");
    }

    // 画像編集
    /* 
        画像をトリミングする
        width : トリミングする範囲の横幅
        height: トリミングする範囲の高さ
        x     : トリミングを開始するx座標
        y     : トリミングを開始するy座標
    */
    public function crop(int $width, int $height, int $x = 0, int $y = 0): self
    {
        $this->file = $this->file->crop($width, $height, $x, $y);
        return $this;
    }

    /* 
        画像のサイズを変更する
        width : 変更後の画像の横幅
        height: 変更後の画像の高さ
    */
    public function resize(?int $width, ?int $height): self
    {
        $this->file = $this->file->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        });
        return $this;
    }
    public function resizeVertical(int $height): self
    {
        return $this->resize(null, $height);
    }
    public function resizeHorizontal(int $width): self
    {
        return $this->resize($width, null);
    }

    /* 
        キャンバスサイズを変更する
        width   : 変更後のキャンバスサイズの横幅
        height  : 変更後のキャンバスサイズの高さ
        position: キャンバスサイズ変更の基準点
    */
    public function resizeCanvas(int $width, int $height, string $position = "center"): self
    {
        if (!in_array($position, $this->positions)) return $this;

        $this->file = $this->file->resizeCanvas($width, $height, $position, true);
        return $this;
    }

    /* 
        画像を歪まないようにトリミングする
        width   : トリミングする範囲の横幅
        height  : トリミングする範囲の高さ
        position: トリミングする範囲の基準点
    */
    public function fit(int $width, int $height, string $position = "center"): self
    {
        if (!in_array($position, $this->positions)) return $this;

        $this->file = $this->file->fit($width, $height, null, $position);
        return $this;
    }

    /* 
        画像を重ねる
        filePath: 重ねる画像のstorage/appからのpath
        position: 重ねる画像を配置する基準点
        x       : 重ねる画像を配置する基準点からの水平距離
        y       : 重ねる画像を配置する基準点からの垂直距離
    */
    public function insert(string $dirName, string $baseName, string $position = "top-left", int $x = 0, int $y = 0): self
    {
        if (!in_array($position, $this->positions)) return $this;

        $insert = new ImageStorageFile($dirName, $baseName);

        $this->file = $this->file->insert($insert->file, $position, $x, $y);
        return $this;
    }

    /* 
        画像を反転させる
        v: 上下
        h: 左右
    */
    public function flipVertical(): self
    {
        $this->file = $this->file->flip("v");
        return $this;
    }
    public function flipHorizontal(): self
    {
        $this->file = $this->file->flip("h");
        return $this;
    }

    /* 
        画像をモノクロにする
    */
    public function greyScale(): self
    {
        $this->file = $this->file->greyscale();
        return $this;
    }

    /* 
        画像の色を反転させる
    */
    public function colorInvert(): self
    {
        $this->file = $this->file->invert();
        return $this;
    }

    /* 
        画像の色数を変更する
        count: 画像に使用する色数
        matte: 透過させたい色のカラーコード
    */
    public function colorLimit(int $count, mixed $matte = null): self
    {
        $this->file = $this->file->limitColors($count, $matte);
        return $this;
    }

    /* 
        画像をぼやけさせる
        0  : ほとんどぼやけさせない
        100: 最大限ぼやけさせる
    */
    public function blur(int $amount): self
    {
        if ($amount < 0 || $amount > 100) return $this;
        $this->file = $this->file->blur($amount);
        return $this;
    }

    /* 
        画像を透過させる
        0  : 何も見えなくなる
        100: 透過無し
    */
    public function opacity(int $transparency): self
    {
        if ($transparency < 0 || $transparency > 100) return $this;

        $this->file = $this->file->opacity($transparency);
        return $this;
    }

    /* 
        画像にガンマ補正をする
        correction: ガンマ補正値
    */
    public function gamma(float $correction): self
    {
        $this->file = $this->file->gamma($correction);
        return $this;
    }

    /* 
        画像にモザイク処理をする
        size: 大きいほどモザイク処理がきつくなる
    */
    public function pixelate(int $size): self
    {
        $this->file = $this->file->pixelate($size);
        return $this;
    }

    /* 
        画像を鮮明にする
        0  : ほとんど鮮明にしない
        100: 最大限鮮明にする
    */
    public function sharpen(int $amount): self
    {
        if ($amount < 0 || $amount > 100) return $this;
        $this->file = $this->file->sharpen($amount);
        return $this;
    }

    /* 
        画像の明るさを変更する
        -100: 最大限暗くする
        0   : そのまま
        100 : 最大限明るくする
    */
    public function brightness(int $level): self
    {
        if ($level < -100 || $level > 100) return $this;

        $this->file = $this->file->brightness($level);
        return $this;
    }

    /* 
        画像のコントラストを変更する
        -100: 最大限コントラストを減らす
        0   : そのまま
        100 : 最大限コントラストを増やす
    */
    public function contrast(int $level): self
    {
        if ($level < -100 || $level > 100) return $this;

        $this->file = $this->file->brightness($level);
        return $this;
    }

    /* 
        画像の色調を変更する
        -100: 最大限その色を取り除く
        0   : そのまま
        100 : 最大限その色を追加する
    */
    public function colorize(int $red, int $green, int $blue): self
    {
        if ($red < -100 || $red > 100) return $this;
        if ($green < -100 || $green > 100) return $this;
        if ($blue < -100 || $blue > 100) return $this;

        $this->file = $this->file->colorize($red, $green, $blue);
        return $this;
    }

    /* 
        画像を回転する
        angle  : 反時計回りの回転角度
        bgcolor: 背景を塗りつぶすカラーコード
    */
    public function rotate(float $angle, mixed $bgcolor = null): self
    {
        $this->file = $this->file->rotate($angle, $bgcolor);
        return $this;
    }
}
