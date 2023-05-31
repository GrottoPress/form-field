# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## [Unreleased] - 

### Changed
- Upgrade `grottopress/getter` package to v1.0

## [0.6.13] - 2023-04-13

### Added
- Add support for PHP 8

### Changed
- Replace Travis CI with GitHub actions

### Fixed
- Fix `TypeError` in `Laminas\Escaper\Escaper::escapeHtmlAttr()` calls

## [0.6.12] - 2020-02-12

### Changed
- Replace "aura/html" package with "laminas/laminas-escaper"
- Update codeception to version 4.1

## [0.6.11] - 2020-02-08

### Added
- Add support for PHP 7.4

## 0.6.10 - 2019-04-19

### Fixed
- Fix invalid UTF-8 exception for form input values

## 0.6.9 - 2019-04-18

### Added
- Add getters for field attributes

### Changed
- Make private methods `protected`

## 0.6.8 - 2019-04-18

### Fixed
- Allow underscore (`_`) at ends of slugs

## 0.6.7 - 2019-04-17

### Fixed
- Undo composer config files excluded from git archive

## 0.6.6 - 2019-04-17

### Added
- Add `.gitattributes`

## 0.6.5 - 2019-04-16

### Fixed
- Fix incorrect argument type passed to `Field::slugify()`

## 0.6.4 - 2019-04-16

### Fixed
- Fix composer dependency resolution issues in travis-ci build

## 0.6.3 - 2019-04-12

### Added
- Add support for PHP 7.3

## 0.6.2 - 2018-10-06

### Added
- Add `.editorconfig`

### Changed
- Rename `LICENSE.md` to `LICENSE`

## 0.6.1 - 2018-07-19

### Changed
- Wrap `label` tag around field if field has no id

## 0.6.0 - 2018-07-05

### Added
- File input field

## 0.5.0 - 2018-03-06

### Change
- Change attribute names from underscore-separated to camel-cased

## 0.4.1 - 2018-03-02

### Changed
- Ensure label html is rendered before displaying break tag

## 0.4.0 - 2018-03-02

### Added
- `.security.txt`
- Set up [travis-ci](https://travis-ci.org/GrottoPress/form-field)
- Shell script to tag and release new versions

### Removed
- Redundant doc blocks
- Replace PHPUnit with Codeception for tests

## 0.3.2 - 2017-11-20

### Fixed
- Fixed `0` displayed in field markup if `$args['choices']` or `$args['meta']` empty.

## 0.3.1 - 2017-11-16

### Changed
- Using strict equality (`===` and `!==`) for checks.

## 0.3.0 - 2017-09-28

### Changed
- Undo camelize render callbacks

## 0.2.0 - 2017-09-13

### Changed
- Code compliant with PSR-1, PSR-2, PSR-4.

## 0.1.1 - 2017-08-24

### Added
- Added a submit button field.
- Added ability to specify wrap HTML tag for a field.

### Changed
- Slugify field `type` with underscores intead of dashes.
- Enhanced `selected` and `checked` methods.

## 0.1.0 - 2017-08-13

### Added
- `Field` class
- Set up test suite with [PHPUnit](https://phpunit.de)
