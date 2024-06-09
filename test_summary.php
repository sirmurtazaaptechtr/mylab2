<?php
// Include header file
require('./include/header.php');

// Query to fetch summary data
$sql_summary = "SELECT t.test_name, COUNT(r.result_id) AS test_count 
                FROM tests t 
                LEFT JOIN results r ON t.test_id = r.test_id 
                GROUP BY t.test_name";
$summary_result = mysqli_query($conn, $sql_summary);
?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Test Summary</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Test Summary</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Summary of Test Results</h5>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Test Name</th>
                                    <th scope="col">Test Count</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while ($row = mysqli_fetch_assoc($summary_result)) {
                                    echo "<tr>";
                                    echo "<td>" . $row['test_name'] . "</td>";
                                    echo "<td>" . $row['test_count'] . "</td>";
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main><!-- End #main -->

<?php
// Include footer file
require('./include/footer.php');
?>
