# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/), and this project adheres to [Semantic Versioning](https://semver.org/).

## [2.2.1] - 2026-09-27

Maintenance release. No changes to `src/` or to the public API — upgrading from 2.2.0 is a drop-in.

### Changed
- Test toolchain moved to PHPUnit 12 (`phpunit/phpunit` dev constraint `^11.0` → `^12.0`). PHPUnit 11 reached end of bugfix support on 2026-02-06 (#18, #19)
- Test doubles for `EntityManagerInterface` switched from `createMock()` to `createStub()` — they never configured expectations (#19)
- README requirements: `alesitom/hybrid-id ^4.1` → `^4.4`, matching `composer.json` (#20)

### CI
- Bumped `actions/checkout` 6.0.2 → 7.0.1 (#16)
- Bumped `shivammathur/setup-php` 2.36.0 → 2.37.2 (#6, #13)
- Bumped `codecov/codecov-action` 5.5.2 → 7.1.1 (#8, #14, #17)
- Corrected version comments on the `setup-php` and `codecov-action` SHA pins (#20)

## [2.2.0] - 2026-04-22

### Changed
- Require `alesitom/hybrid-id: ^4.4` (was `^4.1`). Tested against v4.4.0.

### Compatibility note — hybrid-id v4.4.0
- No code changes needed in this adapter. The Doctrine integration does not call `HybridIdGenerator::getNode()` (whose return type was narrowed to `?string` in v4.4.0) and does not implement `ProfileRegistryInterface` (whose `register()` signature gained an optional `int $node = 2` parameter).
- New helpers `minForDateTime()` / `maxForDateTime()` on `HybridIdGenerator` may be useful for range queries in DQL — see the core package's `docs/api-reference.md`.

## [2.1.0] - 2026-02

Previous releases are documented only on GitHub: https://github.com/alesitom/hybrid-id-doctrine/releases
