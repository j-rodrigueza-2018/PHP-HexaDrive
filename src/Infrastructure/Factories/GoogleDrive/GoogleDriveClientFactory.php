<?php

declare(strict_types=1);

namespace JRA\HexaDrive\Infrastructure\Factories\GoogleDrive;

use Exception;
use Google\Client;
use Google\Service\Drive;
use JRA\HexaDrive\Domain\ClientFactoryInterface;

class GoogleDriveClientFactory implements ClientFactoryInterface
{
    private const string CREDENTIALS_PATH = __DIR__ . "/../../../../google-credentials.json";

    /**
     * @throws Exception
     */
    public static function createClient(): Drive
    {
        if (!file_exists(self::CREDENTIALS_PATH)) {
            throw new Exception("Google credentials file does not exist: " . self::CREDENTIALS_PATH);
        }

        try {
            $client = new Client();
            $client->setAuthConfig(self::CREDENTIALS_PATH);
            $client->addScope(Drive::DRIVE_FILE);

            return new Drive($client);
        } catch (Exception $exception) {
            throw new Exception("Failed to initialize Google Drive client: {$exception->getMessage()}", 0, $exception);
        }
    }
}