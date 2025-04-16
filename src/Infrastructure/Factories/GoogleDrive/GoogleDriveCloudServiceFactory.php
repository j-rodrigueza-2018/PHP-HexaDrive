<?php

declare(strict_types=1);

namespace JRA\HexaDrive\Infrastructure\Factories\GoogleDrive;

use Exception;
use Google\Client;
use Google\Service\Drive;
use JRA\HexaDrive\Domain\CloudServiceFactoryInterface;

class GoogleDriveCloudServiceFactory implements CloudServiceFactoryInterface
{
    private const CREDENTIALS_PATH = __DIR__ . "/../../../../google-credentials.json";
    private static ?string $root_folder_id = null;

    /**
     * @throws Exception
     */
    public function create(string $credentials_path = ''): Drive
    {
        if (empty($credentials)) {
            $credentials_path = self::CREDENTIALS_PATH;
        }

        if (!file_exists($credentials_path)) {
            throw new Exception("Google credentials file does not exist: " . $credentials_path);
        }

        try {
            $client = new Client();
            $client->setAuthConfig($credentials_path);
            $client->addScope(Drive::DRIVE_FILE);

            self::$root_folder_id = json_decode(file_get_contents($credentials_path), true)['folder_id'] ?? null;

            return new Drive($client);
        } catch (Exception $exception) {
            throw new Exception("Failed to initialize Google Drive service: {$exception->getMessage()}", 0, $exception);
        }
    }

    public static function getRootFolderId(): string
    {
        return self::$root_folder_id;
    }
}