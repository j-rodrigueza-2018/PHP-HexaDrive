# PHP-HexaDrive

**PHP-HexaDrive** is a modern PHP library for managing files and folders in **Google Drive**, built with a clean and
decoupled **Hexagonal Architecture**.

## ✨ Features

- 📁 Upload, download, list, rename and delete files
- 📂 Create, list, rename and delete folders
- 🔌 Hexagonal Architecture: decoupled, testable and clean
- ⚙️ PSR-4 autoloading and interface-based adapters
- 🌐 Ready to extend to other providers (e.g. AWS S3, Dropbox, etc.)
- ✅ Comes with integration tests using real Google Drive accounts

## 🛠 Installation

```bash
composer require j-rodrigueza-2018/php-hexadrive
```


## ⚙️ Configuration

1. Go to [Google Cloud Console](https://console.cloud.google.com/), enable the **Google Drive API** and create a **Service Account**.
2. Download the `credentials.json` file.
3. Share your target Google Drive folder with the service account email.
4. Add a custom key in the credentials file for the folder ID:

```json
{
  "type": "service_account",
  "project_id": "...",
  "...": "...",
  "folder_id": "YOUR_FOLDER_ID"
}
```

5. Save the file as `google-credentials.json` in your project root  
   (or pass a custom path to the factory).

---

## 🚀 Basic Usage

### Upload and Download a File

```php
use JRA\HexaDrive\Infrastructure\Factories\GoogleDrive\GoogleDriveCloudServiceFactory;
use JRA\HexaDrive\Infrastructure\Adapters\GoogleDrive\GoogleDriveFileAdapter;

// Initialize service
$service = (new GoogleDriveCloudServiceFactory('/path/to/google-credentials.json'))->create();
$file_manager = new GoogleDriveFileAdapter($service);

// Upload
$file_id = $file_manager->uploadFile('example.txt', 'Hello world!');

// Download
$content = $file_manager->downloadFile($file_id);

// Delete
$file_manager->deleteFile($file_id);
```

---

### Folder Management

```php
use JRA\HexaDrive\Infrastructure\Adapters\GoogleDrive\GoogleDriveFolderAdapter;

$service = (new GoogleDriveCloudServiceFactory('/path/to/google-credentials.json'))->create();
$folder_manager = new GoogleDriveFolderAdapter($service);

// Create
$new_folder_id = $folder_manager->createFolder('MyFolder');

// Rename
$folder_manager->renameFolder($new_folder_id, 'RenamedFolder');

// Delete
$folder_manager->deleteFolder($new_folder_id);
```

---

## 📄 License

This project is licensed under the [MIT License](LICENSE).

[![MIT License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)