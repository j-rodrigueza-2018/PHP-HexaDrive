# Changelog - PHP-HexaDrive

All notable changes to this project will be documented in this file.

---

## [1.0.3] - 2025-04-16

### Fixed

- Credentials path's issue solved in `GoogleDriveCloudServiceFactory` class.

---

## [1.0.2] - 2025-04-16

### Changed

- Updated `GoogleDriveCloudServiceFactory::create()` to accept an optional path to a custom `google-credentials.json`
  filepath.

---

## [1.0.1] - 2025-04-16

### Removed

- Removed `Application/` layer.
- Deleted `FileService` and `FolderService` classes to simplify architecture and avoid redundant logic already handled
  by adapters.

---

## [1.0.0] - 2025-04-15

### Added

- Initial release, first version