<?php
require('./include/header.php');
$error = "";

// Fetch gender options
$sql_gender = "SELECT * FROM `genders`";
$genders = mysqli_query($conn, $sql_gender);

// Fetch marital status options
$sql_ms = "SELECT * FROM `maritial_statuses`";
$maritial_statuses = mysqli_query($conn, $sql_ms);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $lab_no = mysqli_real_escape_string($conn, $_POST['lab_no']);
    $dept_no = mysqli_real_escape_string($conn, $_POST['dept_no']);
    $test_date = mysqli_real_escape_string($conn, $_POST['test_date']);
    $result_date = mysqli_real_escape_string($conn, $_POST['result_date']);
    $result_desc = mysqli_real_escape_string($conn, $_POST['result_desc']);
    $test_values = [];

    // Test results
    $tests = ['HB', 'WBC', 'MP', 'PCV', 'MCV', 'MCH', 'MCHC', 'RBC', 'Platelets', 'Hypochromic', 'Macrocytosis', 'Microcytosis', 'Anisocytosis', 'Poikilocytosis'];

    // Start transaction
    mysqli_begin_transaction($conn);

    try {
        // Insert person
        $sql_insert_person = "INSERT INTO persons (name, dob, age, gender_id, ms_id, contact, role_id) VALUES (?, ?, ?, ?, ?, ?, 3)";
        $stmt_insert_person = mysqli_prepare($conn, $sql_insert_person);
        mysqli_stmt_bind_param($stmt_insert_person, 'ssiiss', $_POST['name'], $_POST['dob'], $_POST['age'], $_POST['gender'], $_POST['maritial_status'], $_POST['contact']);
        mysqli_stmt_execute($stmt_insert_person);
        $person_id = mysqli_insert_id($conn); // Get last inserted person_id

        // Insert test results
        foreach ($tests as $test_name) {
            $test_values[] = mysqli_real_escape_string($conn, $_POST[$test_name]);
        }

        // Fetch the test_ids
        $test_ids = [];
        $sql_test_ids = "SELECT test_id FROM tests";
        $result_test_ids = mysqli_query($conn, $sql_test_ids);
        while ($row = mysqli_fetch_assoc($result_test_ids)) {
            $test_ids[] = $row['test_id'];
        }

        // Insert test results using last inserted person_id
        $sql_insert_results = "INSERT INTO results (lab_no, dept_no, test_date, result_date, result_desc, test_id, result_value, person_id)
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_insert_results = mysqli_prepare($conn, $sql_insert_results);
        mysqli_stmt_bind_param($stmt_insert_results, 'sssssssi', $lab_no, $dept_no, $test_date, $result_date, $result_desc, $test_id, $test_value, $person_id);

        foreach ($test_ids as $index => $test_id) {
            $test_value = $test_values[$index];
            mysqli_stmt_execute($stmt_insert_results);
        }

        // Commit transaction
        mysqli_commit($conn);
        echo "New person and test results added successfully!";

        // Redirect to the results page
        header("Location: results.php");
        exit();
    } catch (Exception $e) {
        // Rollback transaction
        mysqli_rollback($conn);
        $error = "Error: " . $e->getMessage();
    }
}

?>
<main id="main" class="main">

    <div class="pagetitle">
        <h1>Add Test Result</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="results.php">Results</a></li>
                <li class="breadcrumb-item active">Add Test Results</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Add Test Result</h5>
                        <?php if(!empty($error)) { ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $error; ?>
                        </div>
                        <?php } ?>
                        <!-- General Form Elements -->
                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <h5 class="text-center">Basic Details</h5>
                            <div class="row mb-3">
                                <label for="lab_no" class="col-sm-2 col-form-label">Lab #</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="lab_no" id="lab_no">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="dept_no" class="col-sm-2 col-form-label">Dept #</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="dept_no" id="dept_no">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="test_date" class="col-sm-2 col-form-label">Test Date</label>
                                <div class="col-sm-10">
                                    <input type="date" class="form-control" name="test_date" id="test_date">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="result_date" class="col-sm-2 col-form-label">Result Date</label>
                                <div class="col-sm-10">
                                    <input type="date" class="form-control" name="result_date" id="result_date">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="name" class="col-sm-2 col-form-label">Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="name" id="name" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="dob" class="col-sm-2 col-form-label">Date of Birth</label>
                                <div class="col-sm-10">
                                    <input type="date" class="form-control" name="dob" id="dob" >
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="age" class="col-sm-2 col-form-label">Age</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" name="age" id="age" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Gender</label>
                                <div class="col-sm-10">
                                    <select class="form-select" aria-label="Gender Select" name="gender">
                                        <option selected disabled>Open this select menu</option>
                                        <?php while ($gender = mysqli_fetch_assoc($genders)) { ?>
                                            <option value="<?php echo $gender['gender_id'] ?>"><?php echo $gender['gender'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Maritial Status</label>
                                <div class="col-sm-10">
                                    <select class="form-select" aria-label="Maritial Status Select" name="maritial_status">
                                        <option selected disabled>Open this select menu</option>
                                        <?php while ($maritial_status = mysqli_fetch_assoc($maritial_statuses)) { ?>
                                            <option value="<?php echo $maritial_status['ms_id'] ?>"><?php echo $maritial_status['status'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="contact" class="col-sm-2 col-form-label">Phone/Cell</label>
                                <div class="col-sm-10">
                                    <input type="tel" class="form-control" name="contact" id="contact" >
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="result_desc" class="col-sm-2 col-form-label">Remarks</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" style="height: 100px" name="result_desc" ></textarea>
                                </div>
                            </div>
                            <h5 class="text-center">Test Results</h5>
                            <div class="row mb-3 g-3">
                                <?php
                                $tests = ['HB', 'WBC', 'MP', 'PCV', 'MCV', 'MCH', 'MCHC', 'RBC', 'Platelets', 'Hypochromic', 'Macrocytosis', 'Microcytosis', 'Anisocytosis', 'Poikilocytosis'];
                                foreach ($tests as $test) { ?>
                                    <div class="col-md-3">
                                        <div class="col-md-12">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" name="<?php echo $test; ?>" id="floating<?php echo $test; ?>" placeholder="<?php echo $test; ?>">
                                                <label for="floating<?php echo $test; ?>"><?php echo $test; ?></label>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Save Test Result</label>
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-primary" name="submitBtn">Save</button>
                                </div>
                            </div>
                        </form><!-- End General Form Elements -->
                    </div>
                </div>
            </div>
        </div>
    </section>
</main><!-- End #main -->
<?php require('./include/footer.php'); ?>