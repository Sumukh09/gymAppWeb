<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// Assuming you have a database connection
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "gymApp";

$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve workout data from the database
$userID = $_SESSION["user_id"];
$sql = "SELECT exercise, AVG(sets) as avg_sets, AVG(reps) as avg_reps, AVG(weight) as avg_weight FROM workouts WHERE user_id = $userID GROUP BY exercise";
$result = $conn->query($sql);

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Workout Summary</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        h1 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Workout Summary</h1>

        <table id="workout-summary-table">
            <thead>
                <tr>
                    <th>Exercise</th>
                    <th>Average Sets</th>
                    <th>Average Reps</th>
                    <th>Average Weight</th>
                </tr>
            </thead>
            <tbody id="workout-summary-body">
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>{$row['exercise']}</td>";
                        echo "<td>{$row['avg_sets']}</td>";
                        echo "<td>{$row['avg_reps']}</td>";
                        echo "<td>{$row['avg_weight']}</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No workout data available.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
