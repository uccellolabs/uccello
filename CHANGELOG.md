# Changelog

All notable changes to `uccello` will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [3.0.0] - 2021-04-25
### Added
- Uccello now uses repository `Uccello\Core\Repositories\RecordRepository` to retrieve module records.

### Changed
- Controllers uses trait `Uccello\Core\Support\Traits\UccelloController` instead of extends an overrided controller class.
- Routes now respects default schema for CRUD.
- Class `Uccello\Core\Database\Eloquent\Model` now extends `App\Model\UccelloModel`.
