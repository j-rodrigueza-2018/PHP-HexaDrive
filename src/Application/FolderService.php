<?php

declare(strict_types=1);

namespace JRA\HexaDrive\Application;

use JRA\HexaDrive\Domain\FolderManagerInterface;

class FolderService
{
    private FolderManagerInterface $folder_manager;

    public function __construct(FolderManagerInterface $folder_manager)
    {
        $this->folder_manager = $folder_manager;
    }

    public function createFolder(string $name, ?string $parent_folder_id = null): string
    {
        return $this->folder_manager->createFolder($name, $parent_folder_id);
    }

    public function deleteFolder(string $folder_id): void
    {
        $this->folder_manager->deleteFolder($folder_id);
    }

    public function renameFolder(string $folder_id, string $new_name): void
    {
        $this->folder_manager->renameFolder($folder_id, $new_name);
    }
}