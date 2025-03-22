<!DOCTYPE html>
<html lang="en">
<head>
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
         width: 200px;
         background: #f4f4f4;
         padding: 1rem;
         box-sizing: border-box;
         border-right: 1px solid #ccc;
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
         background-color: #66bb6a !important;
      }
      .tile-outline {
         outline: 2px dashed #555;
      }
      .building-button {
         display: block;
         margin-bottom: 10px;
         padding: 6px;
         background: #ddd;
         cursor: pointer;
         border: 1px solid #aaa;
      }
      .building-button.active {
         background: #a5d6a7;
      }
      #unlockBtn {
         margin-top: 1rem;
         padding: 6px;
         width: 100%;
         background: #90caf9;
         border: none;
         cursor: pointer;
      }
   </style>
</head>
<body>
<div id="sidebar">
   <h3>Buildings</h3>
   <div id="buildingList"></div>
   <button id="unlockBtn">Unlock Tile</button>
</div>
<div id="grid"></div>

<script>
   const CELL_SIZE = 16;
   const GRID_CELLS = 72; // 72x72 grid (8x8 tiles of 9x9)
   const TILE_SIZE = 9;
   const TILES = 8; // 8x8 tiles

   let selectedBuilding = null;
   let unlockedTiles = new Set(["0,0"]); // Start with top-left tile unlocked
   let layout = []; // Stored buildings

   const buildings = [
      { id: "barn", name: "Barn", width: 3, height: 3, color: "#a52a2a" },
      { id: "field", name: "Field", width: 2, height: 2, color: "#228B22" },
      { id: "coop", name: "Chicken Coop", width: 2, height: 2, color: "#ffcc00" }
   ];

   const gridEl = document.getElementById("grid");
   const buildingListEl = document.getElementById("buildingList");

   function getTileCoord(x, y) {
      return [Math.floor(x / TILE_SIZE), Math.floor(y / TILE_SIZE)].join(",");
   }

   function isTileUnlocked(x, y) {
      return unlockedTiles.has(getTileCoord(x, y));
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
            cell.addEventListener("click", () => placeBuilding(x, y));
            row.appendChild(cell);
         }
         gridEl.appendChild(row);
      }
      layout.forEach(b => drawBuilding(b));
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
            }
         }
      }
   }

   function placeBuilding(x, y) {
      if (!selectedBuilding) return;
      // Check bounds and tile unlock
      for (let dy = 0; dy < selectedBuilding.height; dy++) {
         for (let dx = 0; dx < selectedBuilding.width; dx++) {
            const cx = x + dx;
            const cy = y + dy;
            if (cx >= GRID_CELLS || cy >= GRID_CELLS || !isTileUnlocked(cx, cy)) return;
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
   }

   function saveLayout() {
      localStorage.setItem("farmLayout", JSON.stringify(layout));
      localStorage.setItem("farmUnlocked", JSON.stringify([...unlockedTiles]));
   }

   function loadLayout() {
      layout = JSON.parse(localStorage.getItem("farmLayout") || "[]");
      unlockedTiles = new Set(JSON.parse(localStorage.getItem("farmUnlocked") || '["0,0"]'));
   }

   function drawBuildingButtons() {
      buildings.forEach(b => {
         const btn = document.createElement("button");
         btn.textContent = b.name;
         btn.className = "building-button";
         btn.onclick = () => {
            document.querySelectorAll(".building-button").forEach(b => b.classList.remove("active"));
            btn.classList.add("active");
            selectedBuilding = b;
         };
         buildingListEl.appendChild(btn);
      });
   }

   document.getElementById("unlockBtn").addEventListener("click", () => {
      const tileX = prompt("Tile X (0-7)?");
      const tileY = prompt("Tile Y (0-7)?");
      if (tileX !== null && tileY !== null) {
         unlockedTiles.add(`${tileX},${tileY}`);
         saveLayout();
         drawGrid();
      }
   });

   loadLayout();
   drawBuildingButtons();
   drawGrid();
</script>
</body>
</html>
