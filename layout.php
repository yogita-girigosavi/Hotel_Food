<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main Page</title>
    <link rel="stylesheet" href="dashboard_style.css">
    <style>
        /* General Styles */
        body {
            margin: 0;
			padding: 0;
            display: flex;
            height: 100vh;
            font-family: Arial, sans-serif;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 200px;
            background-color: #333;
            color: white;
            display: flex;
            flex-direction: column;
            padding: 20px;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .sidebar a {
            text-decoration: none;
            color: white;
            padding: 10px 15px;
            margin: 5px 0;
            border-radius: 4px;
            cursor: pointer;
        }

        .sidebar a:hover {
            background-color: #575757;
        }

        /* Content Area Styles */
        .content {
            flex: 1;
            padding: 0;
			margin: 0;
            overflow-y: auto;
        }

        .loader {
            display: none;
            text-align: center;
        }

    </style>
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Load content dynamically using AJAX
        function loadContent(page) {
            const contentArea = document.getElementById('content-area');
            const loader = document.getElementById('loader');

            // Show loader
            loader.style.display = 'block';
			

            // Fetch the content
            fetch(page)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.text();
                })
                .then(html => {
                    // Hide loader and display content
                    loader.style.display = 'none';
                    contentArea.innerHTML = html;
					
					// Check if the loaded content is dashboard_content.php
					if (page === 'dashboard_content.php') {
						initializeChart();
					}
                })
                .catch(error => {
                    // Show error message
                    loader.style.display = 'none';
                    contentArea.innerHTML = `<p>Error loading content: ${error.message}</p>`;
                });
        }

        // Load default page on page load
        window.onload = function () {
            loadContent('dashboard_content.php');
        };
		
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
		
		function performSearch(event) {
		event.preventDefault(); // Prevent form submission

		const query = document.getElementById('searchQuery').value;
		const contentArea = document.getElementById('content-area');
		const loader = document.getElementById('loader');

		// Show the loader
		loader.style.display = 'block';

		// Fetch search results
		fetch(`search.php?query=${encodeURIComponent(query)}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.text();
        })
        .then(html => {
            // Hide the loader and display the content
            loader.style.display = 'none';
            contentArea.innerHTML = html;
        })
        .catch(error => {
            // Hide the loader and display an error message
            loader.style.display = 'none';
            contentArea.innerHTML = `<p>Error loading search results: ${error.message}</p>`;
        });
	}
	
	// Initialize Chart.js chart
	function initializeChart() {
    // Ensure chart data exists
    const labels = JSON.parse(document.getElementById('chart-labels').textContent);
    const data = JSON.parse(document.getElementById('chart-data').textContent);

    const ctx = document.getElementById('foodChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Food Items Added',
                data: data,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
}

    </script>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Menu</h2>
        <a onclick="loadContent('dashboard_content.php')">Dashboard</a>
        <a onclick="loadContent('hotel_listing.php')">Hotel Listing</a>
        <a onclick="loadContent('food_listing.php')">Food Listing</a>
    </div>

    <!-- Content Area -->
    <div class="content">
        <div id="content-area"></div>
        <div id="loader" class="loader">Loading...</div>
		<canvas id="foodChart" style="display: none;"></canvas>
    </div>
</body>
</html>
