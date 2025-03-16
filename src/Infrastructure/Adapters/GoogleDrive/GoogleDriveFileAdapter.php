<?php

declare(strict_types=1);

namespace JRA\HexaDrive\Infrastructure\Adapters\GoogleDrive;

use Exception;
use Google\Service\Drive;
use JRA\HexaDrive\Domain\Exception\FileManagerException;
use JRA\HexaDrive\Domain\FileManagerInterface;
use Psr\Http\Message\ResponseInterface;

class GoogleDriveFileAdapter implements FileManagerInterface
{
    private Drive $drive_service;
    private ?string $folder_id;

    public function __construct(Drive $drive_service, ?string $folder_id = null)
    {
        $this->drive_service = $drive_service;
        $this->folder_id = $folder_id;
    }

    /**
     * @throws FileManagerException
     */
    public function uploadFile(string $path, string $content): string
    {
        try {
            $file_metadata = new Drive\DriveFile([
                'name' => basename($path),
                'parents' => $this->folder_id ? [$this->folder_id] : []
            ]);

            $file = $this->drive_service->files->create($file_metadata, [
                'data' => $content,
                'mimeType' => 'application/octet-stream',
                'uploadType' => 'multipart',
                'fields' => 'id'
            ]);

            return $file->id;
        } catch (Exception $exception) {
            throw new FileManagerException("Unable to upload file: " . $exception->getMessage(), 0, $exception);
        }
    }

    /**
     * @throws FileManagerException
     */
    public function downloadFile(string $file_id): string
    {
        try {
            $response = $this->drive_service->files->get($file_id, ['alt' => 'media']);

            if (!$response instanceof ResponseInterface) {
                throw new FileManagerException("Unexpected response type when downloading file.");
            }

            return $response->getBody()->getContents();
        } catch (Exception $exception) {
            throw new FileManagerException("Unable to download file: " . $exception->getMessage(), 0, $exception);
        }
    }

    /**
     * @throws FileManagerException
     */
    public function deleteFile(string $file_id): void
    {
        try {
            $this->drive_service->files->delete($file_id);
        } catch (Exception $exception) {
            throw new FileManagerException("Unable to delete file: " . $exception->getMessage(), 0, $exception);
        }
    }

    /**
     * @throws FileManagerException
     */
    public function renameFile(string $file_id, string $new_name): void
    {
        try {
            $file_metadata = new Drive\DriveFile([
                'name' => basename($new_name),
            ]);
            $this->drive_service->files->update($file_id, $file_metadata);
        } catch (Exception $exception) {
            throw new FileManagerException("Unable to rename file: " . $exception->getMessage(), 0, $exception);
        }
    }

    public function moveFile(string $file_id, string $destination_folder_id): void
    {
        // TODO: Implement moveFile() method.
    }

    /**
     * @throws FileManagerException
     */
    public function listFiles(): array
    {
        try {
            if (empty($this->folder_id)) {
                throw new FileManagerException("No folder ID specified for listing files.");
            }

            $files = [];

            $response = $this->drive_service->files->listFiles([
                'q' => "'$this->folder_id' in parents",
                'fields' => 'files(id, name, mimeType, modifiedTime)'
            ]);

            foreach ($response->getFiles() as $file) {
                $files[] = [
                    'id' => $file->getId(),
                    'name' => $file->getName(),
                    'mimeType' => $file->getMimeType(),
                    'modifiedTime' => $file->getModifiedTime()
                ];
            }

            return $files;
        } catch (Exception $exception) {
            throw new FileManagerException("Unable to list files." . $exception->getMessage(), 0, $exception);
        }
    }

    /**
     * Get the current name of a Google Drive's file
     *
     * @throws FileManagerException
     */
    public function getFilename(string $file_id): string
    {
        try {
            $file = $this->drive_service->files->get($file_id, ['fields' => 'name']);
            return $file->getName();
        } catch (Exception $exception) {
            throw new FileManagerException("Unable to get filename: " . $exception->getMessage(), 0, $exception);
        }
    }
}
