# 📦 Changelog

All notable changes to this project will be documented in this file.
## [0.7] - 2025-03-23
### Added
- Visual delete mode with red highlight for buildings under cursor
- Icons displayed directly in grid using emojis (🐖, 🏭 etc.)
- Google Analytics integration (GDPR-friendly with IP anonymization)

### Improved
- Abbreviation or emoji shown only in the top-left tile of each building
- Better UX for delete mode with immediate visual feedback
---
## [0.6] - 2025-03-23
### Fixed
- Summary panel now updates correctly after importing a layout and after deleting buildings.


---
## [0.5] - 2025-03-23
### Added
- Export & Import: Users can now save and load their farm layout as JSON files.
- Icons: Buildings can now include optional emoji icons, displayed on the map.
- Summary Panel: Real-time summary of building counts by category and total number placed.

---
## [0.4] - 2025-03-22
### Added
- Map cells now show **abbreviated text (2-letter code)** representing each building (e.g., CC for Chicken Coop).
- Added **tooltips on map cells** showing full building name on hover.
- Improves layout readability when many buildings are placed.

---
## [0.3] - 2025-03-22
### Added
- Version number is now displayed in the UI (bottom of sidebar).
- Tooltips on building buttons now show name and size (e.g., "Goat Stable - 6x6").
- Prepared building UI for optional future image previews or info panels.

---

## [0.2] - 2025-03-22
### Added
- Automatically unfolds a building’s category when selected (UX improvement).
- Ensures selected building button stays visibly active even if category was collapsed.
- Introduced version tracking and changelog generation for future updates.

---

## [0.1] - 2025-03-22
### Initial Version
- Basic 72×72 grid map based on 8×8 tiles (9×9 cells each).
- Building placement with categories: Stable, Orchards, Fields, Decorations, Machines.
- Locked/unlocked tile system with manual unlock via prompt.
- Delete mode to remove individual buildings.
- Prevents building overlap.
- Color-coded buildings based on data.
- Data-driven buildings JSON (community editable).
