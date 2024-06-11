<?php
// Include header file
require('./include/header.php');

// Fetch all results with person name, marital status, and gender
$sql_results = "
    SELECT 
        p.age, r.result_id, r.test_date, r.result_date, r.HB, r.WBC, r.MP, r.PCV, r.MCV, r.MCH, r.MCHC, r.RBC, r.Platelets, 
        r.Hypochromic, r.Macrocytosis, r.Microcytosis, r.Anisocytosis, r.Poikilocytosis, r.lab_no, r.dept_no, 
        r.ref_phy, r.result_desc,
        p.name, p.dob, p.contact,
        g.gender, 
        r.person_id AS personid,
        ms.status AS marital_status
    FROM results r
    JOIN persons p ON r.person_id = p.person_id
    JOIN genders g ON p.gender_id = g.gender_id
    JOIN maritial_statuses ms ON p.ms_id = ms.ms_id";

$result_results = mysqli_query($conn, $sql_results);
?>

<main id="main" class="main">

    <div class="pagetitle">
        <h1>Manage Test Results</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Results</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->


    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Test Results</h5>
                        <a href="addtestresult.php" class="btn btn-primary mb-3">Add New Result</a>

                        <!-- Table with stripped rows -->
                        <div class="table-responsive">
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Gender</th>
                                        <th scope="col">Marital Status</th>
                                        <th scope="col">Age</th>
                                        <th scope="col">Test Date</th>
                                        <th scope="col">Result Date</th>
                                        <th scope="col">HB</th>
                                        <th scope="col">WBC</th>
                                        <th scope="col">MP</th>
                                        <th scope="col">PCV</th>
                                        <th scope="col">MCV</th>
                                        <th scope="col">MCH</th>
                                        <th scope="col">MCHC</th>
                                        <th scope="col">RBC</th>
                                        <th scope="col">Platelets</th>
                                        <th scope="col">Hypochromic</th>
                                        <th scope="col">Macrocytosis</th>
                                        <th scope="col">Microcytosis</th>
                                        <th scope="col">Anisocytosis</th>
                                        <th scope="col">Poikilocytosis</th>
                                        <th scope="col">Lab No</th>
                                        <th scope="col">Dept No</th>
                                        <th scope="col">Referring Physician</th>
                                        <th scope="col">Result Description</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                  if ($result_results && mysqli_num_rows($result_results) > 0) {
                      while ($row = mysqli_fetch_assoc($result_results)) {
                          echo "<tr>";
                          echo "<th scope='row'>" . htmlspecialchars($row['result_id']) . "</th>";
                          echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                          echo "<td>" . htmlspecialchars($row['gender']) . "</td>";
                          echo "<td>" . htmlspecialchars($row['marital_status']) . "</td>";
                          echo "<td>" . htmlspecialchars($row['age']) . "</td>";
                          echo "<td>" . htmlspecialchars($row['test_date']) . "</td>";
                          echo "<td>" . htmlspecialchars($row['result_date']) . "</td>";
                          echo "<td>" . htmlspecialchars($row['HB']) . "</td>";
                          echo "<td>" . htmlspecialchars($row['WBC']) . "</td>";
                          echo "<td>" . htmlspecialchars($row['MP']) . "</td>";
                          echo "<td>" . htmlspecialchars($row['PCV']) . "</td>";
                          echo "<td>" . htmlspecialchars($row['MCV']) . "</td>";
                          echo "<td>" . htmlspecialchars($row['MCH']) . "</td>";
                          echo "<td>" . htmlspecialchars($row['MCHC']) . "</td>";
                          echo "<td>" . htmlspecialchars($row['RBC']) . "</td>";
                          echo "<td>" . htmlspecialchars($row['Platelets']) . "</td>";
                          echo "<td>" . htmlspecialchars($row['Hypochromic']) . "</td>";
                          echo "<td>" . htmlspecialchars($row['Macrocytosis']) . "</td>";
                          echo "<td>" . htmlspecialchars($row['Microcytosis']) . "</td>";
                          echo "<td>" . htmlspecialchars($row['Anisocytosis']) . "</td>";
                          echo "<td>" . htmlspecialchars($row['Poikilocytosis']) . "</td>";
                          echo "<td>" . htmlspecialchars($row['lab_no']) . "</td>";
                          echo "<td>" . htmlspecialchars($row['dept_no']) . "</td>";
                          echo "<td>" . htmlspecialchars($row['ref_phy']) . "</td>";
                          echo "<td>" . htmlspecialchars($row['result_desc']) . "</td>";
                          ?>
                          <td>
                            <a href="edittestresult.php?person_id=<?php echo $row['personid']; ?>&result_id=<?php echo $row['result_id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                            <a href="deletetestresult.php?person_id=<?php echo $row['personid']; ?>&result_id=<?php echo $row['result_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this test result?');">Delete</a>
                          </td>
                          <?php
                          echo "</tr>";
                      }
                  }
                  ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- End Table with stripped rows -->

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