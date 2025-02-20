<?php

declare(strict_types=1);

namespace JRA\HexaDrive\Tests\Integration\GoogleDriveAdapter;

use Google\Client;
use Google\Exception;
use JRA\HexaDrive\Domain\Exception\FileManagerException;
use JRA\HexaDrive\Infrastructure\GoogleDriveAdapter;
use PHPUnit\Framework\TestCase;

class GoogleDriveAdapterTest extends TestCase
{
    private GoogleDriveAdapter $adapter;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        $this->adapter = new GoogleDriveAdapter();
    }

    /**
     * @throws FileManagerException
     */
    public function testUploadFile(): void
    {
        $file_path = 'test-file.txt';
        $file_content = 'This is a test file for Google Drive.';

        $file_id = $this->adapter->uploadFile($file_path, $file_content);

        $this->assertNotEmpty($file_id, "File upload failed: No file ID returned.");

        echo "✅ File uploaded successfully! File ID: {$file_id}\n";
    }

    /**
     * @throws FileManagerException
     */
    public function testDownloadFile(): void
    {
        $file_path = 'test-file.txt';
        $file_content = 'This is a test file for Google Drive.';

        $file_id = $this->adapter->uploadFile($file_path, $file_content);
        $this->assertNotEmpty($file_id, "File upload failed: No file ID returned.");

        $downloaded_file_content = $this->adapter->downloadFile($file_id);

        $this->assertEquals($file_content, $downloaded_file_content, "File content does not match.");

        echo "✅ File downloaded successfully!\n";
    }
}