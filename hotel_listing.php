<?php include 'db_connection.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Listing</title>
    <link rel="stylesheet" href="styles.css">
	<script>
    function filterHotels() {
        const sortElement = document.getElementById('hotelSort');
        if (!sortElement) {
            console.error("Element with ID 'hotelSort' not found.");
            return;
        }

        const sort = sortElement.value; // Get sorting criterion
        const hotelList = document.getElementById('hotelList').children;
        let hotels = Array.from(hotelList); // Convert NodeList to Array

        console.log("Before sorting:", hotels.map(hotel => hotel.innerHTML));

        hotels.sort((a, b) => {
            if (sort === 'rating') {
                const ratingA = parseFloat(a.querySelector('p:nth-of-type(2)').textContent.split(': ')[1] || 0);
                const ratingB = parseFloat(b.querySelector('p:nth-of-type(2)').textContent.split(': ')[1] || 0);
                return ratingB - ratingA; // Descending order
            }
            return a.querySelector('h2').textContent.localeCompare(b.querySelector('h2').textContent);
        });

        console.log("After sorting:", hotels.map(hotel => hotel.innerHTML));

        // Clear and re-render the sorted list
        const hotelContainer = document.getElementById('hotelList');
        hotelContainer.innerHTML = '';
        hotels.forEach(hotel => hotelContainer.appendChild(hotel));
    }


	
	</script>

</head>
<body>
    <h1>Hotel Listing</h1>
    <form id="searchForm" onsubmit="performSearch(event)" style="margin-left: 16px">
    <input type="text" id="searchQuery" name="query" placeholder="Search hotels or food..." required>
    <button type="submit">Search</button>
	</form>

    <select id="hotelSort" style="margin-left: 20px">
        <option value="name">Sort by Name</option>
        <option value="rating">Sort by Rating</option>
    </select>
    <button onclick="filterHotels()" type="button">Apply</button>

    <div id="hotelList" style="margin-left: 20px; width: 95%;">
        <?php
        $query = "SELECT * FROM Hotel ORDER BY HotelName ASC";
        $result = $conn->query($query);

        while ($row = $result->fetch_assoc()) {
            echo "<div class='hotel-item'>
                    <h2>{$row['hotelname']}</h2>
                    <p>Location: {$row['Location']}</p>
                    <p>Rating: {$row['Rating']}</p>
                  </div>";
        }
        ?>
    </div>
</body>
</html>
