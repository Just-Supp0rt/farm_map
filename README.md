# 🧱 Big Farm: Layout Planner (Unofficial)

A simple interactive farm layout planner inspired by *Big Farm: Mobile Harvest*. This is a companion tool that helps players plan their farm design on a tile-based map with ease.

---

## 🚀 Features

- 🗺 72×72 grid map (8×8 tiles of 9×9 cells)
- 🔐 Tile locking/unlocking based on player's progress
- 🧱 Place buildings of various types with size restrictions
- 🚫 Prevent overlapping building placements
- 🗑 Delete mode and full "Delete All" option
- ❌ Visual delete mode with red highlight for buildings under cursor
- 🧩 Foldable categories for building types
- 💾 Layout and UI state saved to `localStorage`
- 🎨 Color-coded buildings
- 🌐 Offline-ready (pure HTML/JS/CSS, no server needed)
- 🏷 Buildings show abbreviation or emoji in the top-left of each building (e.g., 🐓 for Chicken Coop)
- ℹ Tooltips on grid cells for better readability and building identification
- 📤 Export and import layout as JSON (file or manual paste)
- 📊 Live summary panel (counts buildings per type and category)
- 📈 Google Analytics integration (GDPR-friendly, anonymized IP tracking)
## 🆕 Recently Added (v0.7)

- ❌ Visual delete mode with red highlight when hovering buildings
- 🐓 Icons/emojis displayed directly in grid cells
- 📈 Google Analytics added (anonymized, GDPR-friendly)

---


## 🏗 Building Categories

- **Stable**: Chicken Coop, Pigsty, Cowshed, etc.
- **Orchards**: Apple, Cherry, Almond, Peach
- **Fields**: Field, Meadow
- **Decorations**: Flowerbed, Tree, Hay Bale, etc.
- **Machines**: Silo, Composter, Manufactory, Jam Kitchen

> All building definitions are editable in a JSON-like structure, making it easy for the community to expand the library.

---

## 📦 Development & Deployment

No build tools or dependencies required. Just upload the `.html` file to any static hosting (Netlify, GitHub Pages, Vercel, your own domain).

---

## 🔭 Roadmap (as of v0.7)

### ✅ Current version: **v0.7**
- Visual delete mode with red highlight
- Emoji icons directly in the grid
- Summary panel updates properly after import/delete
- Google Analytics (anonymized)

---

### 🔹 v0.8 – Shareable Layout URLs
- 🔗 Export entire layout (buildings + unlocked tiles) into a compact URL
- 📥 Load layout from the URL (using `#` or query string)
- 🧪 Fallback for invalid/malformed URLs
- 📋 "Copy shareable link" button in the UI

---

### 🔹 v0.9 – Building Info & UX Polish
- ℹ️ Tooltips or modal on building click/hover with full info (name, category, size)
- 🔍 Search bar in sidebar
- 🎯 Improved button layout for mobile
- 🧭 Visual outline for unlocked tiles

---

### 🔹 v1.0 – Save, Share, and Load Like a Pro
- 💾 Named saved layouts (in localStorage)
- 🔄 Easily switch between layouts
- ☁️ (Optional) GitHub Gist or Pastebin integration for cloud saves
- 🧭 Basic tutorial / onboarding flow
- 📲 Touch/mobile-friendly spacing

---

## 🔮 Post-1.0 Ideas

### v1.1 – Power Tools
- ⌨️ Keyboard shortcuts (Del to delete, U to unlock)
- 🔁 Rotation support (90°)
- 🔍 Zoom & pan

### v1.2 – Themes & Visual Polish
- 🌙 Dark mode
- 🎨 Custom color themes
- 🎮 Pixel-art building icons

### v1.3 – Community Features
- 📷 Public layout gallery
- 🏆 "Featured builds"
- 🗳 Feedback form

---

> Want something added to the roadmap? Open an issue or suggest it in the Discord! 🚜

## 📜 License

This project is open-source and unofficial. It's intended as a community tool and has no affiliation with Goodgame Studios.
