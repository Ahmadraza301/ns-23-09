// JavaScript to handle adding/removing rows dynamically
document.addEventListener("DOMContentLoaded", function() {
   const table = document.querySelector(".shop-details");
   const tbody = table.querySelector("tbody");
   const addRowButton = document.querySelector(".add-row");

   addRowButton.addEventListener("click", function() {
      const newRow = document.createElement("tr");
      newRow.innerHTML = `
         <td><input type="text" name="shop_name[]" class="box" required maxlength="100" placeholder="enter shop name"></td>
         <td><input type="number" name="stocks[]" min="0" class="box" required placeholder="enter stocks"></td>
         <td><button type="button" class="remove-row">Remove</button></td>
      `;
      tbody.appendChild(newRow);
   });

   // Handle row removal
   table.addEventListener("click", function(event) {
      if (event.target.classList.contains("remove-row")) {
         const row = event.target.closest("tr");
         tbody.removeChild(row);
      }
   });
});