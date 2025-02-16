<?php

namespace JRA\HexaDrive\Domain;

interface FileManagerInterface
{
    /**
     * Uploads a file to storage
     *
     * @param string $path The file path or name.
     * @param string $content The file content.
     * @return string The unique identifier of the uploaded file.
     */
    public function uploadFile(string $path, string $content): string;

    /**
     * Downloads a file from storage.
     *
     * @param string $file_id The file identifier.
     * @return string The file content.
     */
    public function downloadFile(string $file_id): string;

    /**
     * Deletes a file from storage.
     *
     * @param string $file_id The file identifier.
     * @return void
     */
    public function deleteFile(string $file_id): void;

    /**
     * Renames a file in the storage.
     *
     * @param string $file_id The file identifier.
     * @param string $new_name The new file name.
     * @return void
     */
    public function renameFile(string $file_id, string $new_name): void;

    /**
     * Moves a file to a new folder or location.
     *
     * @param string $file_id The file identifier.
     * @param string $destination_folder_id The destination folder identifier.
     * @return void
     */
    public function moveFile(string $file_id, string $destination_folder_id): void;

    /**
     * Lists files in a specific folder or root.
     *
     * @param string|null $folder_id The folder identifier (null for root).
     * @return array An array of file metadata (e.g., id, name, mimeType).
     */
    public function listFiles(?string $folder_id = null): array;
}