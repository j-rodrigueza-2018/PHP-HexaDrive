<?php

namespace JRA\HexaDrive\Tests\Integration;

use Exception;
use JRA\HexaDrive\Domain\Exception\FileManagerException;
use JRA\HexaDrive\Infrastructure\Factories\GoogleDrive\GoogleDriveClientFactory;
use JRA\HexaDrive\Infrastructure\GoogleDrive\GoogleDriveFileAdapter;
use JRA\HexaDrive\Infrastructure\GoogleDriveAdapter;
use PHPUnit\Framework\TestCase;

class GoogleDriveFileAdapterTest extends TestCase
{
    private GoogleDriveFileAdapter $adapter;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        $this->adapter = new GoogleDriveFileAdapter(GoogleDriveClientFactory::createClient());
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

        echo "✅ File uploaded successfully! File ID: $file_id\n";
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

    /**
     * @throws FileManagerException
     */
    public function testDeleteFile(): void
    {
        $file_path = 'test-file.txt';
        $file_content = 'This is a test file for Google Drive.';

        $file_id = $this->adapter->uploadFile($file_path, $file_content);
        $this->assertNotEmpty($file_id, "File upload failed: No file ID returned.");

        $this->adapter->deleteFile($file_id);
        echo "✅ File deleted successfully!\n";

        $this->expectException(FileManagerException::class);
        $this->adapter->deleteFile($file_id);
    }

    /**
     * @throws FileManagerException
     */
    public function testRenameFile(): void
    {
        $file_path = 'test-rename.txt';
        $file_content = 'This file will be renamed.';

        // Subimos el archivo
        $file_id = $this->adapter->uploadFile($file_path, $file_content);
        $this->assertNotEmpty($file_id, "File upload failed: No file ID returned.");

        // Renombramos el archivo
        $new_name = "renamed-file.txt";
        $this->adapter->renameFile($file_id, $new_name);

        $updated_filename = $this->adapter->getFilename($file_id);
        $this->assertEquals($new_name, $updated_filename, "File rename failed!");

        echo "✅ File renamed successfully!\n";
    }

    /**
     * @throws FileManagerException
     */
    public function testListFiles(): void
    {
        $file_path = 'test-list.txt';
        $file_content = 'This is a test file for listing.';

        // Subimos un archivo para asegurarnos de que haya algo en la lista
        $file_id = $this->adapter->uploadFile($file_path, $file_content);
        $this->assertNotEmpty($file_id, "File upload failed: No file ID returned.");

        // Listamos los archivos
        $files = $this->adapter->listFiles();
        $this->assertNotEmpty($files, "File list is empty!");

        // Verificamos que el archivo subido está en la lista
        $found = false;

        foreach ($files as $file) {
            if ($file['id'] === $file_id) {
                $found = true;
                break;
            }
        }

        $this->assertTrue($found, "Uploaded file not found in list!");

        print_r($files);
        echo "✅ Files listed successfully!\n";
    }
}