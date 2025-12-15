<?php
include 'db.php';

// Total crops available
$total_crops_result = $conn->query("SELECT COALESCE(SUM(quantity), 0) as total_qty FROM farmers");
$total_crops = $total_crops_result->fetch_assoc()['total_qty'];

// Total purchases and revenue
$total_purchase_result = $conn->query("SELECT COUNT(*) as total_transactions, COALESCE(SUM(total_price),0) as total_revenue FROM purchases");
$total_purchase = $total_purchase_result->fetch_assoc();

// Top-selling crops
$top_crops_result = $conn->query("SELECT crop_name, SUM(quantity) as sold_qty 
                                  FROM purchases 
                                  GROUP BY crop_name 
                                  ORDER BY sold_qty DESC 
                                  LIMIT 5");

$crop_names = [];
$sold_quantities = [];

while($row = $top_crops_result->fetch_assoc()){
    $crop_names[] = $row['crop_name'];
    $sold_quantities[] = $row['sold_qty'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>AgriChain Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: #d4f7d4; /* Light Green */
            color: #1b4d1b;
        }

        header {
            background: #2e8b57; /* Dark green */
            padding: 20px;
            text-align: center;
            color: white;
            font-size: 28px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .stats-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            padding: 30px 10px;
            flex-wrap: wrap;
        }

        .card {
            background: white;
            width: 260px;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.15);
            text-align: center;
            transition: 0.3s;
        }

        .card:hover {
            transform: scale(1.05);
        }

        .card h3 {
            margin: 0;
            font-size: 22px;
            color: #2e8b57;
        }

        .card p {
            font-size: 20px;
            font-weight: bold;
            margin-top: 10px;
        }

        h2 {
            text-align: center;
            margin-top: 20px;
            color: #2e8b57;
        }

        .chart-container {
            width: 85%;
            max-width: 800px;
            height: 400px;
            margin: 30px auto;
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.15);
        }

        .back-btn {
            display: block;
            width: 200px;
            margin: 30px auto;
            text-align: center;
            background: #2e8b57;
            color: white;
            padding: 12px;
            border-radius: 8px;
            font-weight: bold;
            text-decoration: none;
        }

        .back-btn:hover {
            background: #3cb371;
        }

        /* Mobile Responsive */
        @media (max-width: 600px) {
            .card {
                width: 90%;
            }
        }
    </style>
</head>
<body>

<header>AgriChain Dashboard</header>

<div class="stats-container">
    <div class="card">
        <h3>Total Crops Available</h3>
        <p><?php echo $total_crops; ?> kg</p>
    </div>

    <div class="card">
        <h3>Total Purchases</h3>
        <p><?php echo $total_purchase['total_transactions']; ?></p>
    </div>

    <div class="card">
        <h3>Total Revenue</h3>
        <p>$<?php echo $total_purchase['total_revenue']; ?></p>
    </div>
</div>

<h2>Top-Selling Crops</h2>

<?php if(count($crop_names) > 0): ?>
    <div class="chart-container">
        <canvas id="topCropsChart"></canvas>
    </div>
<?php else: ?>
    <p style="text-align:center;font-size:20px;">No sales data available.</p>
<?php endif; ?>

<a href="index.php" class="back-btn">Back to Home</a>

<script>
    const ctx = document.getElementById('topCropsChart')?.getContext('2d');
    if(ctx){
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($crop_names); ?>,
                datasets: [{
                    label: 'Quantity Sold (kg)',
                    data: <?php echo json_encode($sold_quantities); ?>,
                    backgroundColor: '#2e8b57',
                    borderColor: '#1b4d1b',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: { ticks: { font: { size: 14, weight: 'bold' } } },
                    y: { beginAtZero: true, ticks: { font: { size: 14, weight: 'bold' } } }
                }
            }
        });
    }
</script>

</body>
</html>
