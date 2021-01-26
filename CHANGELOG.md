# Changelog

All notable changes to `uccello` will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [2.0.0] - 2021-01-26

### Added
- Uccello is now compatible with Laravel 8.x.
- New Blade sections in the detail view to allow the overriding of the tabs.
- New Blade sections in the list view to propose other export formats.
- Creation of the `Uccello\Core\Support\Traits\IsExportable` trait.
- Possibility to use new Export writer.
- The `System information` block is now created by default.
- With the `for_crud = true` option, a capability is now available only for modules linked to a `Model` class.
- New methods for Module : `isPublic()` and `isPrivate()`.

### Changes
- Migrations concerning the same tables or modules have been merged.
- `Uccello\Core\Http\Controllers\Core\ExportController` now uses `Uccello\Core\Support\Traits\IsExportable` trait.
- `Uccello\Core\Exports\RecordsExport` was renamed `Uccello\Core\Exports\ExportManager`.
- Now uses `h5` instead of `h4` for modal title.
- Now modal footers are fixed.

### Deprecated
- Nothing

### Fixed
- A block now is hidden if no fields can be displayed in the current view.

### Removed
- Export to `pdf` format was removed. It will always be possible to add this format manually.
- `mpdf/mdpf` is no longer a default requirement.
- Custom links was removed. It is easier to use view overriding for that.

### Security
- Nothing
