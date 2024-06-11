<?php
// Include header file
require('./include/header.php');

// Query to fetch summary data
$sql_summary = "SELECT 
                    'HB' AS test_name, COUNT(HB) AS test_count FROM results
                UNION
                SELECT 
                    'WBC' AS test_name, COUNT(WBC) AS test_count FROM results
                UNION
                SELECT 
                    'MP' AS test_name, COUNT(MP) AS test_count FROM results
                UNION
                SELECT 
                    'PCV' AS test_name, COUNT(PCV) AS test_count FROM results
                UNION
                SELECT 
                    'MCV' AS test_name, COUNT(MCV) AS test_count FROM results
                UNION
                SELECT 
                    'MCH' AS test_name, COUNT(MCH) AS test_count FROM results
                UNION
                SELECT 
                    'MCHC' AS test_name, COUNT(MCHC) AS test_count FROM results
                UNION
                SELECT 
                    'RBC' AS test_name, COUNT(RBC) AS test_count FROM results
                UNION
                SELECT 
                    'Platelets' AS test_name, COUNT(Platelets) AS test_count FROM results
                UNION
                SELECT 
                    'Hypochromic' AS test_name, COUNT(Hypochromic) AS test_count FROM results
                UNION
                SELECT 
                    'Macrocytosis' AS test_name, COUNT(Macrocytosis) AS test_count FROM results
                UNION
                SELECT 
                    'Microcytosis' AS test_name, COUNT(Microcytosis) AS test_count FROM results
                UNION
                SELECT 
                    'Anisocytosis' AS test_name, COUNT(Anisocytosis) AS test_count FROM results
                UNION
                SELECT 
                    'Poikilocytosis' AS test_name, COUNT(Poikilocytosis) AS test_count FROM results";
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
