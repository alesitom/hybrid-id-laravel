# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/), and this project adheres to [Semantic Versioning](https://semver.org/).

## [2.3.0] - 2026-09-27

### Added
- Laravel 13 support: `illuminate/database` and `illuminate/support` now accept `^12.0|^13.0`; dev dependency `orchestra/testbench` accepts `^10.0|^11.0` (#20, #21)

### Removed
- Laravel 11 support. Laravel 11 left security support on 2026-03-12 and every 11.x release carries unfixed advisories (e.g. CVE-2026-48019), so Composer already refused to install it. Apps still on Laravel 11 stay on 2.2.x automatically (#20, #21)

### Changed
- Test toolchain moved to PHPUnit 12 (`phpunit/phpunit` dev constraint `^11.0` → `^12.0`) (#18, #19)
- README requirements list updated to Laravel 12 or 13 and `alesitom/hybrid-id ^4.4` (#21)

### CI
- Test matrix is now PHP (8.3, 8.4, 8.5) × Laravel (12, 13), pinning each Laravel line before install so every declared line is tested (#21)
- Bumped `actions/checkout` 6.0.2 → 7.0.1 (#16)
- Bumped `shivammathur/setup-php` 2.36.0 → 2.37.2 (#6, #13)
- Bumped `codecov/codecov-action` 5.5.2 → 7.1.1 (#8, #14, #17)

## [2.2.0] - 2026-04-22

### Changed
- Require `alesitom/hybrid-id: ^4.4` (was `^4.1`). Tested against v4.4.0.

### Compatibility note — hybrid-id v4.4.0
- `HybridIdGenerator::getNode()` now returns `?string` (was `string`), returning `null` for nodeless profiles (`compact` and custom profiles with `node: 0`). The Laravel adapter itself does not call this method, so no user-facing change; only relevant if you fetch the underlying generator via the container and call `getNode()` on a nodeless profile.
- `ProfileRegistryInterface::register()` has a new optional `int $node = 2` parameter. Custom implementations of this interface (uncommon in Laravel apps) must add the new parameter.

## [2.1.0] - 2026-02

Previous releases are documented only on GitHub: https://github.com/alesitom/hybrid-id-laravel/releases
