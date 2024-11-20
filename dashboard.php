<?php
include 'db_connection.php';

// Get total hotels
$totalHotelsQuery = "SELECT COUNT(*) as total_hotels FROM Hotel";
$totalHotelsResult = $conn->query($totalHotelsQuery);
$totalHotels = $totalHotelsResult->fetch_assoc()['total_hotels'];

// Get total food items
$totalFoodQuery = "SELECT COUNT(*) as total_food FROM Food";
$totalFoodResult = $conn->query($totalFoodQuery);
$totalFood = $totalFoodResult->fetch_assoc()['total_food'];

// Get monthly food data
$monthlyFoodQuery = "SELECT MONTHNAME(created_at) as month, COUNT(*) as count 
                     FROM Food 
                     WHERE YEAR(created_at) = YEAR(CURDATE()) 
                     GROUP BY MONTH(created_at)";
$monthlyFoodResult = $conn->query($monthlyFoodQuery);

$monthlyFoodData = [];
while ($row = $monthlyFoodResult->fetch_assoc()) {
    $monthlyFoodData[] = $row;
}

$title = 'Dashboard';
$contentPage = 'dashboard_content.php';
include 'layout.php';
