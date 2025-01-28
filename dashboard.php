


<?php
// Include the database connection

error_reporting(E_ERROR | E_WARNING | E_PARSE);

require 'database.php';

$message = ""; // Initialize a variable for displaying messages

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['cbc_report'])) {
    $patientName = $_POST['patient_name'];
    $fileName = $_FILES['cbc_report']['name'];
    $targetDir = "uploads/";
    $targetFile = $targetDir . basename($fileName);

    if (move_uploaded_file($_FILES['cbc_report']['tmp_name'], $targetFile)) {
        if (file_exists($targetFile)) {
            // Call the Python script with proper arguments
            $command = escapeshellcmd("python scripts/main_script.py") . " " . escapeshellarg($targetFile) . " " . escapeshellarg($patientName);
            $output = shell_exec($command);

            $message = $output ? "Report uploaded and processed successfully." : "Error: Python script execution failed.";
        } else {
            $message = "Error: File not found after upload.";
        }
    } else {
        $message = "Error: Failed to move uploaded file.";
    }
}

// Fetch reports from the database
$stmt = $pdo->query("SELECT * FROM cbc_reports ORDER BY upload_date DESC");

$reports = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Prepare data for visualization
$chartLabels = [];
$hemoglobinData = [];
$wbcData = [];

foreach ($reports as $report) {
    $chartLabels[] = htmlspecialchars($report['upload_date']);
    $hemoglobinData[] = (float)$report['hemoglobin_level'];
    $wbcData[] = (float)$report['wbc_count'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sickle Cell Management Dashboard</title>
    <link rel="stylesheet" href="assetsstyles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container">
        <h1>Sickle Cell Management System</h1>
        <?php if (!empty($message)): ?>
            <p><?php echo $message; ?></p>
        <?php endif; ?>

        <h2>Upload CBC Report</h2>
        <form action="dashboard.php" method="POST" enctype="multipart/form-data">
            <label for="patient_name">Patient Name:</label>
            <input type="text" name="patient_name" id="patient_name" required>
            <label for="cbc_report">Upload CBC Report (PDF only):</label>
            <input type="file" name="cbc_report" id="cbc_report" accept=".pdf" required>
            <button type="submit">Upload and Process</button>
        </form>
        <hr>
        <h2>View Uploaded Reports</h2>
        <?php if (count($reports) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Patient Name</th>
                        <th>Report</th>
                        <th>Hemoglobin</th>
                        <th>WBC Count</th>
                        <th>Upload Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reports as $report): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($report['patient_name']); ?></td>
                            <td><a href="<?php echo htmlspecialchars($report['report_path']); ?>" target="_blank">View Report</a></td>
                            <td><?php echo htmlspecialchars($report['hemoglobin_level']); ?></td>
                            <td><?php echo htmlspecialchars($report['wbc_count']); ?></td>
                            <td><?php echo htmlspecialchars($report['upload_date']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No reports available to display.</p>
        <?php endif; ?>

        <hr>
        <h2>Your CBC Trends</h2>
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
