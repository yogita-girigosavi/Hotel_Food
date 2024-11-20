<?php include 'db_connection.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Listing</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        function filterFood() {
            const sort = document.getElementById('foodSort').value; // Get selected sorting option
            const foodListContainer = document.getElementById('foodList'); // Container for food items
            const foodItems = Array.from(foodListContainer.children); // Convert NodeList to Array

            // Sort food items based on the selected option
            if (sort === 'price') {
                foodItems.sort((a, b) => {
                    const priceA = parseFloat(a.querySelector('h2').textContent.split('- $')[1] || 0);
                    const priceB = parseFloat(b.querySelector('h2').textContent.split('- $')[1] || 0);
                    return priceA - priceB; // Ascending order of price
                });
            } else if (sort === 'name') {
                foodItems.sort((a, b) => {
                    const nameA = a.querySelector('h2').textContent.toLowerCase();
                    const nameB = b.querySelector('h2').textContent.toLowerCase();
                    return nameA.localeCompare(nameB); // Alphabetical order
                });
            }

            // Re-render sorted food items
            foodListContainer.innerHTML = ''; // Clear existing list
            foodItems.forEach(item => foodListContainer.appendChild(item)); // Append sorted items
        }
    </script>
</head>
<body>
    <h1>Food Listing</h1>
    <form id="searchForm" onsubmit="performSearch(event)" style="margin-left: 16px">
    <input type="text" id="searchQuery" name="query" placeholder="Search hotels or food..." required>
    <button type="submit">Search</button>
	</form>

    <select id="foodSort" style="margin-left: 20px">
        <option value="name">Sort by Name</option>
        <option value="price">Sort by Price</option>
    </select>
    <button type="button" onclick="filterFood()">Apply</button>

    <div id="foodList" style="margin-left: 20px; width: 95%;">
        <?php
        $query = "SELECT Food.FoodName, Food.Price, Hotel.HotelName 
                  FROM Food 
                  JOIN Hotel ON Food.HotelID = Hotel.HotelID 
                  ORDER BY FoodName ASC";
        $result = $conn->query($query);

        while ($row = $result->fetch_assoc()) {
            echo "<div class='food-item'>
                    <h2>{$row['FoodName']} - \${$row['Price']}</h2>
                    <p>Hotel: {$row['HotelName']}</p>
                  </div>";
        }
        ?>
    </div>
</body>
</html>
