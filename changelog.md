# Craft Slim Bridge Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## 1.1.3 - 2021-11-23
### Fixed

- Fixed an issue where Yii would override content-type headers.

## 1.1.2 - 2021-10-16
### Fixed

- Fixed an issue where not every code path handled special URIs.

## 1.1.1 - 2021-10-16
### Fixed

- Fixed an issue with handling special URIs like `__home__` and `__404__`.

## 1.1.0 - 2021-10-13
### Added

- Added the ability to route the Entry and Category elements through the Slim app.

## 1.0.0 - 2021-09-27
### Added

- This is the initial release of Craft Slim Bridge.
- Craft Slim Bridge allows the use of Slim, FastRoute, and PSR standard interfaces when building the front-end of a Craft site.
