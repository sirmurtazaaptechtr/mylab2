<?php
require('./include/header.php');
$error = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $min_age = mysqli_real_escape_string($conn, $_POST['min_age']);
    $max_age = mysqli_real_escape_string($conn, $_POST['max_age']);
    $test_id = mysqli_real_escape_string($conn, $_POST['test']);
    $min_range = mysqli_real_escape_string($conn, $_POST['min_range']);
    $max_range = mysqli_real_escape_string($conn, $_POST['max_range']);

    // Calculate the date range for the age group
    $current_date = date("Y-m-d");
    $min_dob = date("Y-m-d", strtotime($current_date . " - $max_age years"));
    $max_dob = date("Y-m-d", strtotime($current_date . " - $min_age years"));

    // Fetch test results based on the criteria
    $sql_report = "SELECT p.name, p.dob, p.age, r.result_value, r.test_date 
                   FROM results r
                   JOIN persons p ON r.person_id = p.person_id
                   WHERE p.dob BETWEEN ? AND ? AND r.test_id = ? AND r.result_value BETWEEN ? AND ?";

    $stmt_report = mysqli_prepare($conn, $sql_report);
    mysqli_stmt_bind_param($stmt_report, 'ssiidd', $min_dob, $max_dob, $test_id, $min_range, $max_range);
    mysqli_stmt_execute($stmt_report);
    $result_report = mysqli_stmt_get_result($stmt_report);
    
    // Check if results are found
    if (mysqli_num_rows($result_report) > 0) {
        echo "<div class='container mt-5'>
                <h2>Test Report</h2>
                <table class='table table-bordered'>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Date of Birth</th>
                            <th>Test Date</th>
                            <th>Result Value</th>
                        </tr>
                    </thead>
                    <tbody>";
        
        while ($row = mysqli_fetch_assoc($result_report)) {
            echo "<tr>
                    <td>{$row['name']}</td>
                    <td>{$row['dob']}</td>
                    <td>{$row['test_date']}</td>
                    <td>{$row['result_value']}</td>
                  </tr>";
        }
        
        echo "  </tbody>
              </table>
            </div>";
    } else {
        echo "<div class='alert alert-info'>No results found for the selected criteria.</div>";
    }
}
?>

<?php require('./include/footer.php'); ?>
