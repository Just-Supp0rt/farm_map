<!DOCTYPE html>
<html lang="en">
<head>
   <!-- Google tag (gtag.js) -->
   <script async src="https://www.googletagmanager.com/gtag/js?id=G-NFPR3JJ8JJ"></script>
   <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-NFPR3JJ8JJ', { 'anonymize_ip': true });
   </script>
   <script id="Cookiebot" src="https://consent.cookiebot.com/uc.js" data-cbid="c35e5ab6-274e-4dc1-bb39-39107a95f910" type="text/javascript" async></script>
   <meta charset="UTF-8" />
   <meta name="viewport" content="width=device-width, initial-scale=1.0" />
   <title>Farm Map Planner</title>
   <style>
      body {
         margin: 0;
         display: flex;
         height: 100vh;
         font-family: sans-serif;
      }
      #sidebar {
         width: 250px;
         background: #f4f4f4;
         padding: 1rem;
         box-sizing: border-box;
         border-right: 1px solid #ccc;
         overflow-y: auto;
      }
      #grid {
         flex-grow: 1;
         overflow: auto;
         background: #e0e0e0;
      }
      .cell {
         width: 16px;
         height: 16px;
         box-sizing: border-box;
         border: 1px solid #ccc;
         float: left;
      }
      .row::after {
         content: "";
         display: block;
         clear: both;
      }
      .locked {
         background: #999 !important;
      }
      .building {
         /* background-color removed so JS color takes effect */
      }
      .tile-outline {
         outline: 2px dashed #555;
      }
      .building-button {
         display: block;
         margin-bottom: 6px;
         padding: 6px;
         background: #ddd;
         cursor: pointer;
         border: 1px solid #aaa;
      }
      .building-button.active {
         background: #a5d6a7;
      }
      .category {
         margin-bottom: 1rem;
      }
      .category h4 {
         margin-bottom: 0.5rem;
         border-bottom: 1px solid #ccc;
         cursor: pointer;
         display: flex;
         justify-content: space-between;
         align-items: center;
      }
      .category.collapsed .category-content {
         display: none;
      }
      #unlockBtn, #deleteBtn, #clearBtn {
         margin-top: 1rem;
         padding: 6px;
         width: 100%;
         background: #90caf9;
         border: none;
         cursor: pointer;
      }
      #deleteBtn {
         background: #ef5350;
      }
      #clearBtn {
         background: #ffb74d;
      }
      #toggleAll {
         margin-bottom: 1rem;
         width: 100%;
         padding: 6px;
         background: #ce93d8;
         border: none;
         cursor: pointer;
      }
      .highlight {
         outline: 2px solid red;
      }
   </style>
</head>
<body>
<div id="sidebar">
   <button id="exportBtn">Export Layout</button>
   <button id="importBtn">Import Layout</button>
   <input type="file" id="fileInput" style="display:none" />
   <h3>Buildings</h3>
   <button id="toggleAll">Collapse/Expand All</button>
   <div id="buildingList"></div>
   <button id="deleteBtn">Delete Mode</button>
   <button id="unlockBtn">Unlock Tile</button>
   <button id="clearBtn">Delete All Buildings</button>
   <div id="summaryPanel"></div>
   <p style="margin-top: 1rem; font-size: 0.85em; color: #666; text-align: center;">Version v0.7</p>
</div>
<div id="grid"></div>

<script>
   const CELL_SIZE = 16;
   const GRID_CELLS = 72;
   const TILE_SIZE = 9;
   const TILES = 8;

   let selectedBuilding = null;
   let selectedCategory = null;
   let unlockedTiles = new Set(["0,0"]);
   let layout = [];
   let deleteMode = false;

   const COLLAPSE_KEY = "categoryCollapseState";
   let collapseState = JSON.parse(localStorage.getItem(COLLAPSE_KEY) || '{}');

   const buildingData = {
      "Stable": [
         { "id": "chicken_coop", "name": "Chicken Coop", "abbr": "CC", "icon": "🐓", "width": 2, "height": 2, "color": "#ffcc00" },
         { "id": "pigsty", "name": "Pigsty", "abbr": "PS", "icon": "🐖", "width": 3, "height": 3, "color": "#d2691e" },
         { "id": "cowshed", "name": "Cowshed", "abbr": "CS", "icon": "🐄", "width": 3, "height": 3, "color": "#8b4513" },
         { "id": "goat_stable", "name": "Goat Stable", "abbr": "GS", "icon": "🐐", "width": 2, "height": 2, "color": "#a1887f" },
         { "id": "duck_coop", "name": "Duck Coop", "abbr": "DC", "icon": "🦆", "width": 2, "height": 2, "color": "#81d4fa" },
         { "id": "rabbit_hutch", "name": "Rabbit Hutch", "abbr": "RH", "icon": "🐇", "width": 2, "height": 2, "color": "#c5e1a5" },
         { "id": "sheep_pen", "name": "Sheep Pen", "abbr": "SP", "icon": "🐑", "width": 2, "height": 2, "color": "#e0e0e0" }
      ],
      "Orchards": [
         { "id": "apple_orchard", "name": "Apple Orchard", "abbr": "AO", "icon": "🍎", "width": 3, "height": 3, "color": "#c62828" },
         { "id": "cherry_orchard", "name": "Cherry Orchard", "abbr": "CO", "icon": "🍒", "width": 3, "height": 3, "color": "#ad1457" },
         { "id": "almond_orchard", "name": "Almond Orchard", "abbr": "AL", "icon": "🌰", "width": 3, "height": 3, "color": "#a1887f" },
         { "id": "peach_orchard", "name": "Peach Orchard", "abbr": "PO", "icon": "🍑", "width": 3, "height": 3, "color": "#f48fb1" }
      ],
      "Fields": [
         { "id": "field", "name": "Field", "abbr": "FI", "icon": "🌾", "width": 2, "height": 2, "color": "#388e3c" },
         { "id": "meadow", "name": "Meadow", "abbr": "ME", "icon": "🌿", "width": 2, "height": 2, "color": "#81c784" }
      ],
      "Decorations": [
         { "id": "flowerbed", "name": "Flowerbed", "abbr": "FB", "icon": "🌸", "width": 1, "height": 1, "color": "#e91e63" },
         { "id": "ornamental_tree", "name": "Ornamental Tree", "abbr": "OT", "icon": "🌳", "width": 1, "height": 1, "color": "#4caf50" },
         { "id": "hay_bale", "name": "Hay Bale", "abbr": "HB", "icon": "🌾", "width": 1, "height": 1, "color": "#fdd835" },
         { "id": "vegetable_patch", "name": "Vegetable Patch", "abbr": "VP", "icon": "🥕", "width": 2, "height": 2, "color": "#8bc34a" },
         { "id": "well", "name": "Well", "abbr": "WL", "icon": "⛲", "width": 1, "height": 1, "color": "#90a4ae" }
      ],
      "Machines": [
         { "id": "silo", "name": "Silo", "abbr": "SI", "icon": "🏗️", "width": 2, "height": 2, "color": "#6d4c41" },
         { "id": "composter", "name": "Composter", "abbr": "CM", "icon": "♻️", "width": 2, "height": 2, "color": "#5d4037" },
         { "id": "manufactory", "name": "Manufactory", "abbr": "MF", "icon": "🏭", "width": 3, "height": 3, "color": "#455a64" },
         { "id": "jam_kitchen", "name": "Jam Kitchen", "abbr": "JK", "icon": "🍓", "width": 2, "height": 2, "color": "#f06292" }
      ]
   };

   const gridEl = document.getElementById("grid");
   const buildingListEl = document.getElementById("buildingList");

   function getTileCoord(x, y) {
      return [Math.floor(x / TILE_SIZE), Math.floor(y / TILE_SIZE)].join(",");
   }

   function isTileUnlocked(x, y) {
      return unlockedTiles.has(getTileCoord(x, y));
   }

   function isOccupied(x, y) {
      return layout.some(b => x >= b.x && x < b.x + b.width && y >= b.y && y < b.y + b.height);
   }

   function drawGrid() {
      gridEl.innerHTML = "";
      for (let y = 0; y < GRID_CELLS; y++) {
         const row = document.createElement("div");
         row.classList.add("row");
         for (let x = 0; x < GRID_CELLS; x++) {
            const cell = document.createElement("div");
            cell.className = "cell";
            cell.dataset.x = x;
            cell.dataset.y = y;
            if (!isTileUnlocked(x, y)) cell.classList.add("locked");
            if (x % TILE_SIZE === 0 && y % TILE_SIZE === 0) cell.classList.add("tile-outline");
            cell.addEventListener("click", () => handleCellClick(x, y));
            row.appendChild(cell);
         }
         gridEl.appendChild(row);
      }
      layout.forEach(b => drawBuilding(b));
   }
   function updateSummary() {
      const summary = {};
      layout.forEach(b => {
         summary[b.name] = (summary[b.name] || 0) + 1;
      });

      const panel = document.getElementById("summaryPanel");
      panel.innerHTML = "<strong>Summary:</strong><br>";
      const total = layout.length;
      for (const [name, count] of Object.entries(summary)) {
         panel.innerHTML += `${count} × ${name}<br>`;
      }
      panel.innerHTML += `<br><strong>Total:</strong> ${total}`;
   }

   function drawBuilding(b) {
      for (let dy = 0; dy < b.height; dy++) {
         for (let dx = 0; dx < b.width; dx++) {
            const cx = b.x + dx;
            const cy = b.y + dy;
            const cell = document.querySelector(`.cell[data-x='${cx}'][data-y='${cy}']`);
            if (cell) {
               cell.style.backgroundColor = b.color;
               cell.classList.add("building");
               cell.title = b.name;

               // Only label the top-left cell of the building
               if (dx === 0 && dy === 0) {
                  cell.textContent = b.icon || b.abbr || b.name.slice(0, 2).toUpperCase();
                  cell.style.fontSize = "10px";
                  cell.style.textAlign = "center";
                  cell.style.lineHeight = "16px";
                  cell.style.fontWeight = "bold";
                  cell.style.color = "#000"; // consider dynamic contrast later
               }
            }
         }
      }
   }


   function handleCellClick(x, y) {
      if (deleteMode) {
         const building = layout.find(b => x >= b.x && x < b.x + b.width && y >= b.y && y < b.y + b.height);
         if (building) {
            // Highlight the building on hover
            for (let dy = 0; dy < building.height; dy++) {
               for (let dx = 0; dx < building.width; dx++) {
                  const cx = building.x + dx;
                  const cy = building.y + dy;
                  const cell = document.querySelector(`.cell[data-x='${cx}'][data-y='${cy}']`);
                  if (cell) {
                     cell.classList.add("highlight");
                     cell.addEventListener('mouseout', () => cell.classList.remove("highlight"));
                  }
               }
            }
         }
      } else {
         placeBuilding(x, y);
      }
   }


   function placeBuilding(x, y) {
      if (!selectedBuilding) return;
      for (let dy = 0; dy < selectedBuilding.height; dy++) {
         for (let dx = 0; dx < selectedBuilding.width; dx++) {
            const cx = x + dx;
            const cy = y + dy;
            if (cx >= GRID_CELLS || cy >= GRID_CELLS || !isTileUnlocked(cx, cy) || isOccupied(cx, cy)) return;
         }
      }
      const building = {
         ...selectedBuilding,
         x,
         y
      };
      layout.push(building);
      saveLayout();
      drawGrid();
      updateSummary();
   }

   function saveLayout() {
      localStorage.setItem("farmLayout", JSON.stringify(layout));
      localStorage.setItem("farmUnlocked", JSON.stringify([...unlockedTiles]));
      localStorage.setItem(COLLAPSE_KEY, JSON.stringify(collapseState));
   }

   function loadLayout() {
      layout = JSON.parse(localStorage.getItem("farmLayout") || "[]");
      unlockedTiles = new Set(JSON.parse(localStorage.getItem("farmUnlocked") || '["0,0"]'));
      collapseState = JSON.parse(localStorage.getItem(COLLAPSE_KEY) || '{}');
   }

   function drawBuildingButtons() {
      buildingListEl.innerHTML = "";
      for (const category in buildingData) {
         const section = document.createElement("div");
         section.className = "category";
         const content = document.createElement("div");
         content.className = "category-content";
         const title = document.createElement("h4");
         const icon = document.createElement("span");

         const isCollapsed = collapseState[category];
         if (isCollapsed) section.classList.add("collapsed");
         icon.textContent = isCollapsed ? "▶" : "▼";

         title.textContent = category;
         title.prepend(icon);
         title.addEventListener("click", () => {
            section.classList.toggle("collapsed");
            const collapsed = section.classList.contains("collapsed");
            collapseState[category] = collapsed;
            icon.textContent = collapsed ? "▶" : "▼";
            saveLayout();
         });
         section.appendChild(title);

         buildingData[category].forEach(b => {
            const btn = document.createElement("button");
            btn.textContent = b.name;
            btn.title = `${b.name} – ${b.width}×${b.height}`;
            btn.className = "building-button";
            if (selectedBuilding && selectedBuilding.id === b.id) {
               btn.classList.add("active");
               section.classList.remove("collapsed");
               collapseState[category] = false;
               icon.textContent = "▼";
            }
            btn.onclick = () => {
               document.querySelectorAll(".building-button").forEach(b => b.classList.remove("active"));
               btn.classList.add("active");
               selectedBuilding = b;
               selectedCategory = category;
               deleteMode = false;
            };
            content.appendChild(btn);
         });

         section.appendChild(content);
         buildingListEl.appendChild(section);
      }
   }

   document.getElementById("toggleAll").addEventListener("click", () => {
      const allCollapsed = Object.values(collapseState).every(Boolean);
      for (const key in buildingData) {
         collapseState[key] = !allCollapsed;
      }
      saveLayout();
      drawBuildingButtons();
   });

   document.getElementById("unlockBtn").addEventListener("click", () => {
      const tileX = prompt("Tile X (0-7)?");
      const tileY = prompt("Tile Y (0-7)?");
      if (tileX !== null && tileY !== null) {
         unlockedTiles.add(`${tileX},${tileY}`);
         saveLayout();
         drawGrid();
      }
   });

   document.getElementById("deleteBtn").addEventListener("click", () => {
      deleteMode = !deleteMode;
      selectedBuilding = null;
      document.querySelectorAll(".building-button").forEach(b => b.classList.remove("active"));
   });

   document.getElementById("clearBtn").addEventListener("click", () => {
      if (confirm("Are you sure you want to delete all buildings?")) {
         layout = [];
         saveLayout();
         drawGrid();
         updateSummary();
      }
   });
   document.getElementById("exportBtn").addEventListener("click", () => {
      const dataStr = JSON.stringify(layout, null, 2);
      const blob = new Blob([dataStr], { type: "application/json" });
      const url = URL.createObjectURL(blob);
      const a = document.createElement("a");
      a.href = url;
      a.download = "farm_layout.json";
      document.body.appendChild(a);
      a.click();
      document.body.removeChild(a);
      URL.revokeObjectURL(url);
   });


   document.getElementById("importBtn").addEventListener("click", () => {
      const input = document.createElement("input");
      input.type = "file";
      input.accept = "application/json";
      input.onchange = event => {
         const file = event.target.files[0];
         if (file) {
            const reader = new FileReader();
            reader.onload = e => {
               try {
                  const importedLayout = JSON.parse(e.target.result);
                  layout = importedLayout;
                  drawGrid();
                  updateSummary();
               } catch (error) {
                  alert("Invalid JSON file.");
               }
            };
            reader.readAsText(file);
         }
      };
      input.click();
   });


   loadLayout();
   drawBuildingButtons();
   drawGrid();
   updateSummary();

</script>
</body>
</html>
