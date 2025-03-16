<?php

declare(strict_types=1);

namespace JRA\HexaDrive\Domain;

interface FolderManagerInterface
{
    /**
     * Creates up a folder in the storage
     *
     * @param string $name
     * @param string|null $parent_folder_id
     * @return string
     */
    public function createFolder(string $name, ?string $parent_folder_id = null): string;


    /**
     * Deletes a folder in the storage
     *
     * @param string $folder_id
     * @return void
     */
    public function deleteFolder(string $folder_id): void;


    /**
     * Renames a folder in the storage
     *
     * @param string $folder_id
     * @param string $new_name
     * @return void
     */
    public function renameFolder(string $folder_id, string $new_name): void;
}