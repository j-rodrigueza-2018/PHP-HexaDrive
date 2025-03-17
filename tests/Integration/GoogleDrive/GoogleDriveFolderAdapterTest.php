<?php

declare(strict_types=1);

namespace JRA\HexaDrive\Tests\Integration\GoogleDrive;

use Google\Service\Drive;
use Google\Service\Exception;
use JRA\HexaDrive\Domain\Exception\FolderManagerException;
use JRA\HexaDrive\Infrastructure\Adapters\GoogleDrive\GoogleDriveFolderAdapter;
use JRA\HexaDrive\Infrastructure\Factories\GoogleDrive\GoogleDriveCloudServiceFactory;
use Monolog\Test\TestCase;

class GoogleDriveFolderAdapterTest extends TestCase
{
    private GoogleDriveFolderAdapter $folder_adapter;
    private Drive $drive_service;

    /**
     * @throws \Exception
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->drive_service = (new GoogleDriveCloudServiceFactory())->create();
        $this->folder_adapter = new GoogleDriveFolderAdapter($this->drive_service);
    }

    /**
     * @throws FolderManagerException|Exception
     */
    public function testCreateFolder(): void
    {
        $folder_name = 'TestFolder_' . time();
        $folder_id = $this->folder_adapter->createFolder($folder_name);

        $this->assertNotEmpty($folder_id, 'Folder ID should not be empty');

        // Verify if folder exists on Google Drive
        $folder = $this->drive_service->files->get($folder_id, ['fields' => 'id, name']);
        $this->assertEquals($folder_name, $folder->getName());
    }

    /**
     * @throws FolderManagerException
     */
    public function testDeleteFolder(): void
    {
        $folder_name = 'TestFolder_' . time();
        $folder_id = $this->folder_adapter->createFolder($folder_name);

        $this->assertNotEmpty($folder_id, 'Folder ID should not be empty');

        $this->folder_adapter->deleteFolder($folder_id);

        $this->expectException(Exception::class);
        $this->drive_service->files->get($folder_id);
    }

    /**
     * @throws Exception
     * @throws FolderManagerException
     */
    public function testRenameFolder(): void
    {
        $original_folder_name = 'TestFolderRename_' . time();
        $folder_id = $this->folder_adapter->createFolder($original_folder_name);

        $this->assertNotEmpty($folder_id, 'Folder ID should not be empty');

        $new_folder_name = 'RenamedFolder_' . time();
        $this->folder_adapter->renameFolder($folder_id, $new_folder_name);

        $folder = $this->drive_service->files->get($folder_id, ['fields' => 'name']);

        $this->assertEquals($new_folder_name, $folder->getName(), 'Folder Name should have changed');
    }

}