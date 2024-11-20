<?php
include 'db_connection.php';

// Fetch totals
$totalHotels = $conn->query("SELECT COUNT(*) as total FROM Hotel")->fetch_assoc()['total'];
$totalFood = $conn->query("SELECT COUNT(*) as total FROM Food")->fetch_assoc()['total'];

// Fetch monthly data
$monthlyFoodData = $conn->query("SELECT MONTHNAME(created_at) as month, COUNT(*) as count 
                                 FROM Food 
                                 WHERE YEAR(created_at) = YEAR(CURDATE()) 
                                 GROUP BY MONTH(created_at)");
$monthlyFood = [];
while ($row = $monthlyFoodData->fetch_assoc()) {
    $monthlyFood[] = $row;
}

?>
<!DOCTYPE html>
<html>
<head>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h1>Dashboard</h1>
    <div class="dashboard">
        <div class="card">
            <h2>Total Hotels</h2>
            <p><?php echo $totalHotels; ?></p>
        </div>
        <div class="card">
            <h2>Total Food Items</h2>
            <p><?php echo $totalFood; ?></p>
        </div>
    </div>

    <h2 style="text-align: center; margin-top: 50px">Food Items Added Monthly</h2>
    <canvas id="foodChart"></canvas>
	<!-- Include chart data -->
	<script type="application/json" id="chart-labels">
    <?php echo json_encode(array_column($monthlyFood, 'month')); ?>
	</script>
	<script type="application/json" id="chart-data">
    <?php echo json_encode(array_column($monthlyFood, 'count')); ?>
	</script>

    <script>
        const labels = <?php echo json_encode(array_column($monthlyFood, 'month')); ?>;
        const data = <?php echo json_encode(array_column($monthlyFood, 'count')); ?>;

        console.log("Labels array: ", labels);
        console.log("Data array: ", data);

        const ctx = document.getElementById('foodChart').getContext('2d');

        if (ctx) {
            console.log("Chart context found, initializing Chart.js...");
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
        } else {
            console.log("Chart context not found.");
        }
    </script>
</body>
</html>
