<?php

declare(strict_types=1);

namespace JRA\HexaDrive\Infrastructure\Adapters\GoogleDrive;

use Exception;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use JRA\HexaDrive\Domain\Exception\FolderManagerException;
use JRA\HexaDrive\Domain\FolderManagerInterface;

class GoogleDriveFolderAdapter implements FolderManagerInterface
{
    private Drive $drive_service;

    public function __construct(Drive $drive_service)
    {
        $this->drive_service = $drive_service;
    }

    /**
     * @throws FolderManagerException
     */
    public function createFolder(string $name, ?string $parent_folder_id = null): string
    {
        try {
            $folder_metadata = new DriveFile([
                'name' => $name,
                'mimeType' => 'application/vnd.google-apps.folder',
                'parents' => $parent_folder_id ? [$parent_folder_id] : [],
            ]);

            $folder = $this->drive_service->files->create($folder_metadata, ['fields' => 'id']);

            return $folder->getId();
        } catch (Exception $exception) {
            throw new FolderManagerException("Unable to create folder: " . $exception->getMessage(), 0, $exception);
        }
    }

    /**
     * @throws FolderManagerException
     */
    public function deleteFolder(string $folder_id): void
    {
        try {
            $this->drive_service->files->delete($folder_id);
        } catch (Exception $exception) {
            throw new FolderManagerException("Unable to delete folder: " . $exception->getMessage(), 0, $exception);
        }
    }

    /**
     * @throws FolderManagerException
     */
    public function renameFolder(string $folder_id, string $new_name): void
    {
        try {
            $folder_metadata = new DriveFile([
                'name' => $new_name
            ]);

            $this->drive_service->files->update($folder_id, $folder_metadata);
        } catch (Exception $exception) {
            throw new FolderManagerException("Unable to rename folder: " . $exception->getMessage(), 0, $exception);
        }
    }
}