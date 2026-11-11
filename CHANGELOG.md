# Changelog

All notable changes to `filament-surveys` will be documented in this file.

## v2.1.0 - 2026-11-11

- Add a "Duplicar" action to the surveys table. It asks for the new title, start date and end date, and copies the survey with its questions and options. Participants and responses are not copied.
- Add `Survey::duplicate()` to duplicate a survey programmatically.

## v2.0.2 - 2026-04-07

Update resource action syntax and component imports for Filament v4

## v2.0.1 - 2026-04-07

Update resource action syntax and component imports for Filament v4

## implementation of compatibility with filament v4/v5 - 2026-04-07

Updates dependencies to support Filament v4/v5 and Pest 3, while increasing PHPStan analysis to level 9. This change introduces model factories, comprehensive test coverage, and switches participant unique links to UUIDs. The survey filling interface is also refactored to support question sections and improved validation.

## 1.0.0 - 202X-XX-XX

- initial release
