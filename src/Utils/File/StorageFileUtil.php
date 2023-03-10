<?php

namespace MyCustom\Utils\File;

use MyCustom\Utils\File\FileUtil;

abstract class StorageFileUtil extends FileUtil
{
    function __construct(string $dirName, string $baseName)
    {
        parent::__construct(config("mycustoms.utils.logging_util_file", false), $this);

        $this->set($dirName, $baseName);
    }

    final private function set(string $dirName, string $baseName): void
    {
        $this->setFileUtil(
            $dirName,
            $baseName,
        );

        if (!$this->isExist()) throw $this->StorageFileNotFoundException();
    }

    abstract public function save(string $dirName, string $baseName, string $format = null): self;
    abstract protected function setChild(): void;

    public function saveRename(string $registerName): self
    {
        return $this->save($this->dirName, $registerName);
    }
    public function saveAs(string $format): self
    {
        return $this->save($this->dirName, $this->baseName, $format);
    }
    public function saveRenameAs(string $registerName, $format): self
    {
        return $this->save($this->dirName, $registerName, $format);
    }

    public function saveOverride(): self
    {
        return $this->save($this->dirName, $this->baseName);
    }

    final protected function reset(string $dirName, string $baseName): void
    {
        $this->delete();
        $this->set($dirName, $baseName);
        $this->setChild();
    }
}
