# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/), and this project adheres to [Semantic Versioning](https://semver.org/).

## [2.2.0] - 2026-04-22

### Changed
- Require `alesitom/hybrid-id: ^4.4` (was `^4.1`). Tested against v4.4.0.

### Compatibility note — hybrid-id v4.4.0
- No code changes needed in this adapter. The Doctrine integration does not call `HybridIdGenerator::getNode()` (whose return type was narrowed to `?string` in v4.4.0) and does not implement `ProfileRegistryInterface` (whose `register()` signature gained an optional `int $node = 2` parameter).
- New helpers `minForDateTime()` / `maxForDateTime()` on `HybridIdGenerator` may be useful for range queries in DQL — see the core package's `docs/api-reference.md`.

## [2.1.0] - 2026-02

Previous releases are documented only on GitHub: https://github.com/alesitom/hybrid-id-doctrine/releases
