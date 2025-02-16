<?php

declare(strict_types=1);

namespace JRA\HexaDrive\Application;

use JRA\HexaDrive\Domain\FileManagerInterface;

class FileService
{
    private FileManagerInterface $file_manager;

    public function __construct(FileManagerInterface $file_manager)
    {
        $this->file_manager = $file_manager;
    }

    public function upload(string $path, string $content): string
    {
        return $this->file_manager->uploadFile($path, $content);
    }

    public function download(string $file_id): string
    {
        return $this->file_manager->downloadFile($file_id);
    }

    public function delete(string $file_id): void
    {
        $this->file_manager->deleteFile($file_id);
    }

    public function rename(string $file_id, string $new_name): void
    {
        $this->file_manager->renameFile($file_id, $new_name);
    }

    public function move(string $file_id, string $destination_folder_id): void
    {
        $this->file_manager->moveFile($file_id, $destination_folder_id);
    }

    public function list(?string $folder_id = null): array
    {
        return $this->file_manager->listFiles($folder_id);
    }
}