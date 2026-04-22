# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/), and this project adheres to [Semantic Versioning](https://semver.org/).

## [2.2.0] - 2026-04-22

### Changed
- Require `alesitom/hybrid-id: ^4.4` (was `^4.1`). Tested against v4.4.0.

### Compatibility note — hybrid-id v4.4.0
- `HybridIdGenerator::getNode()` now returns `?string` (was `string`), returning `null` for nodeless profiles (`compact` and custom profiles with `node: 0`). The Laravel adapter itself does not call this method, so no user-facing change; only relevant if you fetch the underlying generator via the container and call `getNode()` on a nodeless profile.
- `ProfileRegistryInterface::register()` has a new optional `int $node = 2` parameter. Custom implementations of this interface (uncommon in Laravel apps) must add the new parameter.

## [2.1.0] - 2026-02

Previous releases are documented only on GitHub: https://github.com/alesitom/hybrid-id-laravel/releases
