<?php

declare(strict_types=1);

namespace JRA\HexaDrive\Infrastructure;

use Exception;
use Google\Client;
use Google\Service\Drive;
use JRA\HexaDrive\Domain\Exception\FileManagerException;
use JRA\HexaDrive\Domain\FileManagerInterface;
use Psr\Http\Message\ResponseInterface;

class GoogleDriveAdapter implements FileManagerInterface
{
    private Drive $drive_service;
    private ?string $folder_id;
    private const string CREDENTIALS_PATH = __DIR__ . "/../../google-credentials.json";

    /**
     * @throws FileManagerException
     */
    public function __construct(string $credentials_path = self::CREDENTIALS_PATH)
    {
        if (!file_exists($credentials_path)) {
            throw new FileManagerException("Google credentials file not found: $credentials_path");
        }

        try {
            $client = new Client();
            $client->setAuthConfig($credentials_path);
            $client->addScope(Drive::DRIVE_FILE);

            $this->drive_service = new Drive($client);

            $credentials = json_decode(file_get_contents($credentials_path), true);
            $this->folder_id = $credentials['folder_id'] ?? null;
        } catch (Exception $exception) {
            throw new FileManagerException("Google credentials could not be loaded: " . $exception->getMessage(), 0, $exception);
        }
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

    public function listFiles(?string $folder_id = null): array
    {
        return [];
        // TODO: Implement listFiles() method.
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
