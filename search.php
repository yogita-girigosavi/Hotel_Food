<?php
include 'db_connection.php';

$query = isset($_GET['query']) ? $conn->real_escape_string($_GET['query']) : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="stylesheet" href="search_style.css">
</head>
<body>
    <h1>Search Results for "<?php echo htmlspecialchars($query); ?>"</h1>

    <h2 style="margin-left: 20px">Hotels</h2>
    <div id="hotelResults" style="margin-left: 20px; width: 95%">
        <?php
        if ($query) {
            $hotelSearch = "SELECT * FROM Hotel WHERE HotelName LIKE '%$query%'";
            $hotelResult = $conn->query($hotelSearch);

            if ($hotelResult->num_rows > 0) {
                while ($row = $hotelResult->fetch_assoc()) {
                    echo "<div class='hotel-item'>
                            <h2>{$row['hotelname']}</h2>
                            <p>Location: {$row['Location']}</p>
                            <p>Rating: {$row['Rating']}</p>
                          </div>";
                }
            } else {
                echo "<p>No matching hotels found.</p>";
            }
        } else {
            echo "<p>Please enter a search term.</p>";
        }
        ?>
    </div>

    <h2 style="margin-left: 20px">Food</h2>
    <div id="foodResults" style="margin-left: 20px; width: 95%">
        <?php
        if ($query) {
            $foodSearch = "SELECT Food.FoodName, Food.Price, Hotel.HotelName 
                           FROM Food 
                           JOIN Hotel ON Food.HotelID = Hotel.HotelID
                           WHERE Food.FoodName LIKE '%$query%' OR Hotel.HotelName LIKE '%$query%'";
            $foodResult = $conn->query($foodSearch);

            if ($foodResult->num_rows > 0) {
                while ($row = $foodResult->fetch_assoc()) {
                    echo "<div class='food-item'>
                            <h2>{$row['FoodName']} - \${$row['Price']}</h2>
                            <p>Hotel: {$row['HotelName']}</p>
                          </div>";
                }
            } else {
                echo "<p>No matching food items found.</p>";
            }
        }
        ?>
    </div>
</body>
</html>
