





<?php
// Start the session
session_start();

// Include the database connection
require 'database.php';

// Check if the user is logged in as a doctor
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'doctor') {
    header('Location: index.php'); // Redirect to login if not authenticated
    exit();
}

// Fetch all patient reports from the database
$stmt = $pdo->query("SELECT * FROM cbc_reports ORDER BY upload_date DESC");
$reports = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Prepare data for visualization
$chartLabels = [];
$hemoglobinData = [];
$wbcData = [];
$patientNames = [];

foreach ($reports as $report) {
    $chartLabels[] = htmlspecialchars($report['upload_date']);
    $hemoglobinData[] = (float)$report['hemoglobin_level'];
    $wbcData[] = (float)$report['wbc_count'];
    $patientNames[] = htmlspecialchars($report['patient_name']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Dashboard</title>
    <link rel="stylesheet" href="doctor_dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container">
        <h1>Doctor Dashboard</h1>

        <h2>Patient Reports</h2>
        <table>
            <thead>
                <tr>
                    <th>Patient Name</th>
                    <th>Report</th>
                    <th>Upload Date</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reports as $report): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($report['patient_name']); ?></td>
                        <td><a href="<?php echo htmlspecialchars($report['report_path']); ?>" target="_blank">View Report</a></td>
                        <td><?php echo htmlspecialchars($report['upload_date']); ?></td>
                        <td>
                            <form action="doctor_dashboard.php" method="POST">
                                <textarea name="note" placeholder="Add a note" required></textarea>
                                <input type="hidden" name="report_id" value="<?php echo isset($report['id']) ? htmlspecialchars($report['id']) : ''; ?>">
                                <button type="submit">Add Note</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <hr>
        <h2>Visualization of CBC Trends</h2>
        <canvas id="cbcChart" width="400" height="200"></canvas>
    </div>

    <script>
        const ctx = document.getElementById('cbcChart').getContext('2d');
        const cbcChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($chartLabels); ?>,
                datasets: [
                    {
                        label: 'Hemoglobin Levels',
                        data: <?php echo json_encode($hemoglobinData); ?>,
                        backgroundColor: 'rgba(255, 99, 132, 0.5)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1,
                        fill: false
                    },
                    {
                        label: 'WBC Counts',
                        data: <?php echo json_encode($wbcData); ?>,
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1,
                        fill: false
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            title: function(context) {
                                const index = context[0].dataIndex;
                                return <?php echo json_encode($patientNames); ?>[index];
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>




