<?php
require('./include/header.php');
$error = "";

// Fetch gender options
$sql_gender = "SELECT * FROM `genders`";
$genders = mysqli_query($conn, $sql_gender);

// Fetch marital status options
$sql_ms = "SELECT * FROM `maritial_statuses`";
$maritial_statuses = mysqli_query($conn, $sql_ms);

// Check if an ID is provided to edit the result
if (!isset($_GET['result_id'])) {
    $error = "No result ID provided.";
} else {
    $result_id = $_GET['result_id'];

    // Fetch the existing test result data
    $sql_result = "SELECT r.*, p.name, p.dob, p.age, p.gender_id, p.ms_id, p.contact 
                   FROM results r
                   JOIN persons p ON r.person_id = p.person_id
                   WHERE r.result_id = ?";
    $stmt_result = mysqli_prepare($conn, $sql_result);
    mysqli_stmt_bind_param($stmt_result, 'i', $result_id);
    mysqli_stmt_execute($stmt_result);
    $result_data = mysqli_stmt_get_result($stmt_result);
    $result_row = mysqli_fetch_assoc($result_data);

    if (!$result_row) {
        $error = "No result found with the provided ID.";
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($result_row)) {
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
        // Update person
        $sql_update_person = "UPDATE persons SET name = ?, dob = ?, age = ?, gender_id = ?, ms_id = ?, contact = ? WHERE person_id = ?";
        $stmt_update_person = mysqli_prepare($conn, $sql_update_person);
        mysqli_stmt_bind_param($stmt_update_person, 'ssiissi', $_POST['name'], $_POST['dob'], $_POST['age'], $_POST['gender'], $_POST['maritial_status'], $_POST['contact'], $result_row['person_id']);
        mysqli_stmt_execute($stmt_update_person);

        // Update test results
        foreach ($tests as $test_name) {
            $test_values[$test_name] = mysqli_real_escape_string($conn, $_POST[$test_name]);
        }

        // Fetch the test_ids
        $test_ids = [];
        $sql_test_ids = "SELECT test_id, test_name FROM tests";
        $result_test_ids = mysqli_query($conn, $sql_test_ids);
        while ($row = mysqli_fetch_assoc($result_test_ids)) {
            $test_ids[$row['test_name']] = $row['test_id'];
        }

        // Update test results using the result_id
        $sql_update_results = "UPDATE results SET lab_no = ?, dept_no = ?, test_date = ?, result_date = ?, result_desc = ?, result_value = ? WHERE result_id = ? AND test_id = ?";
        $stmt_update_results = mysqli_prepare($conn, $sql_update_results);

        foreach ($tests as $test_name) {
            $test_id = $test_ids[$test_name];
            $test_value = $test_values[$test_name];
            mysqli_stmt_bind_param($stmt_update_results, 'ssssssii', $lab_no, $dept_no, $test_date, $result_date, $result_desc, $test_value, $result_id, $test_id);
            mysqli_stmt_execute($stmt_update_results);
        }

        // Commit transaction
        mysqli_commit($conn);
        echo "Test result updated successfully!";

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
        <h1>Edit Test Result</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="results.php">Results</a></li>
                <li class="breadcrumb-item active">Edit Test Results</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Edit Test Result</h5>
                        <?php if (!empty($error)) { ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $error; ?>
                        </div>
                        <?php } elseif (isset($result_row)) { ?>
                        <!-- General Form Elements -->
                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?result_id=' . $result_id; ?>">
                            <h5 class="text-center">Basic Details</h5>
                            <div class="row mb-3">
                                <label for="lab_no" class="col-sm-2 col-form-label">Lab #</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="lab_no" id="lab_no" value="<?php echo $result_row['lab_no']; ?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="dept_no" class="col-sm-2 col-form-label">Dept #</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="dept_no" id="dept_no" value="<?php echo $result_row['dept_no']; ?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="test_date" class="col-sm-2 col-form-label">Test Date</label>
                                <div class="col-sm-10">
                                    <input type="date" class="form-control" name="test_date" id="test_date" value="<?php echo $result_row['test_date']; ?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="result_date" class="col-sm-2 col-form-label">Result Date</label>
                                <div class="col-sm-10">
                                    <input type="date" class="form-control" name="result_date" id="result_date" value="<?php echo $result_row['result_date']; ?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="name" class="col-sm-2 col-form-label">Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="name" id="name" required value="<?php echo $result_row['name']; ?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="dob" class="col-sm-2 col-form-label">Date of Birth</label>
                                <div class="col-sm-10">
                                    <input type="date" class="form-control" name="dob" id="dob" value="<?php echo $result_row['dob']; ?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="age" class="col-sm-2 col-form-label">Age</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" name="age" id="age" required value="<?php echo $result_row['age']; ?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Gender</label>
                                <div class="col-sm-10">
                                    <select class="form-select" aria-label="Gender Select" name="gender">
                                        <option selected disabled>Open this select menu</option>
                                        <?php while ($gender = mysqli_fetch_assoc($genders)) { ?>
                                            <option value="<?php echo $gender['gender_id'] ?>" <?php echo ($gender['gender_id'] == $result_row['gender_id']) ? 'selected' : ''; ?>>
                                                <?php echo $gender['gender'] ?>
                                            </option>
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
                                            <option value="<?php echo $maritial_status['ms_id'] ?>" <?php echo ($maritial_status['ms_id'] == $result_row['ms_id']) ? 'selected' : ''; ?>>
                                                <?php echo $maritial_status['status'] ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="contact" class="col-sm-2 col-form-label">Phone/Cell</label>
                                <div class="col-sm-10">
                                    <input type="tel" class="form-control" name="contact" id="contact" value="<?php echo $result_row['contact']; ?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="result_desc" class="col-sm-2 col-form-label">Remarks</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" style="height: 100px" name="result_desc"><?php echo $result_row['result_desc']; ?></textarea>
                                </div>
                            </div>
                            <h5 class="text-center">Test Results</h5>
                            <div class="row mb-3 g-3">
                                <?php
                                $tests = ['HB', 'WBC', 'MP', 'PCV', 'MCV', 'MCH', 'MCHC', 'RBC', 'Platelets', 'Hypochromic', 'Macrocytosis', 'Microcytosis', 'Anisocytosis', 'Poikilocytosis'];
                                prx($result_row);
                                foreach ($tests as $test) {
                                    $test_value = $result_row[$test];
                                ?>
                                    <div class="col-md-3">
                                        <div class="col-md-12">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" name="<?php echo $test; ?>" id="floating<?php echo $test; ?>" placeholder="<?php echo $test; ?>" value="<?php echo $test_value; ?>">
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
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main><!-- End #main -->
<?php require('./include/footer.php'); ?>
