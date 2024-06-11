<?php
require('./include/header.php');

// Fetch gender options
$sql_gender = "SELECT * FROM `genders`";
$genders = mysqli_query($conn, $sql_gender);

// Fetch marital status options
$sql_ms = "SELECT * FROM `maritial_statuses`";
$maritial_statuses = mysqli_query($conn, $sql_ms);

$error_message = "";
$success_message = "";

// Initialize variables for form data
$person_id = $name = $dob = $age = $gender_id = $ms_id = $contact = "";
$result_id = $lab_no = $dept_no = $test_date = $result_date = $result_desc = "";
$HB = $WBC = $MP = $PCV = $MCV = $MCH = $MCHC = $RBC = $Platelets = "";
$Hypochromic = $Macrocytosis = $Microcytosis = $Anisocytosis = $Poikilocytosis = "";

// Fetch existing data if the person_id is provided
if (isset($_GET['person_id'])) {
    $person_id = $_GET['person_id'];
    $result_id = $_GET['result_id'];

    // Fetch person details
    $sql_person = "SELECT * FROM persons WHERE person_id = ?";
    $stmt_person = mysqli_prepare($conn, $sql_person);
    mysqli_stmt_bind_param($stmt_person, 'i', $person_id);
    mysqli_stmt_execute($stmt_person);
    $result_person = mysqli_stmt_get_result($stmt_person);
    $person = mysqli_fetch_assoc($result_person);

    if ($person) {
        $name = $person['name'];
        $dob = $person['dob'];
        $age = $person['age'];
        $contact = $person['contact'];
        $gender_id = $person['gender_id'];
        $ms_id = $person['ms_id'];
    } else {
        $error_message = "Person not found.";
    }

    // Fetch test results details
    $sql_result = "SELECT * FROM results WHERE person_id = ?";
    $stmt_result = mysqli_prepare($conn, $sql_result);
    mysqli_stmt_bind_param($stmt_result, 'i', $person_id);
    mysqli_stmt_execute($stmt_result);
    $result_result = mysqli_stmt_get_result($stmt_result);
    $result = mysqli_fetch_assoc($result_result);

    if ($result) {
        $lab_no = $result['lab_no'];
        $dept_no = $result['dept_no'];
        $test_date = $result['test_date'];
        $result_date = $result['result_date'];
        $result_desc = $result['result_desc'];
        $HB = $result['HB'];
        $WBC = $result['WBC'];
        $MP = $result['MP'];
        $PCV = $result['PCV'];
        $MCV = $result['MCV'];
        $MCH = $result['MCH'];
        $MCHC = $result['MCHC'];
        $RBC = $result['RBC'];
        $Platelets = $result['Platelets'];
        $Hypochromic = $result['Hypochromic'];
        $Macrocytosis = $result['Macrocytosis'];
        $Microcytosis = $result['Microcytosis'];
        $Anisocytosis = $result['Anisocytosis'];
        $Poikilocytosis = $result['Poikilocytosis'];
    } else {
        $error_message = "Test result not found.";
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data
    $result_id = mysqli_real_escape_string($conn, $_POST['result_id']);
    $person_id = mysqli_real_escape_string($conn, $_POST['person_id']);
    $lab_no = mysqli_real_escape_string($conn, $_POST['lab_no']);
    $dept_no = mysqli_real_escape_string($conn, $_POST['dept_no']);
    $test_date = mysqli_real_escape_string($conn, $_POST['test_date']);
    $result_date = mysqli_real_escape_string($conn, $_POST['result_date']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $age = mysqli_real_escape_string($conn, $_POST['age']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $gender_id = mysqli_real_escape_string($conn, $_POST['gender']);
    $ms_id = mysqli_real_escape_string($conn, $_POST['maritial_status']);
    $result_desc = mysqli_real_escape_string($conn, $_POST['result_desc']);
    $HB = mysqli_real_escape_string($conn, $_POST['HB']);
    $WBC = mysqli_real_escape_string($conn, $_POST['WBC']);
    $MP = mysqli_real_escape_string($conn, $_POST['MP']);
    $PCV = mysqli_real_escape_string($conn, $_POST['PCV']);
    $MCV = mysqli_real_escape_string($conn, $_POST['MCV']);
    $MCH = mysqli_real_escape_string($conn, $_POST['MCH']);
    $MCHC = mysqli_real_escape_string($conn, $_POST['MCHC']);
    $RBC = mysqli_real_escape_string($conn, $_POST['RBC']);
    $Platelets = mysqli_real_escape_string($conn, $_POST['Platelets']);
    $Hypochromic = mysqli_real_escape_string($conn, $_POST['Hypochromic']);
    $Macrocytosis = mysqli_real_escape_string($conn, $_POST['Macrocytosis']);
    $Microcytosis = mysqli_real_escape_string($conn, $_POST['Microcytosis']);
    $Anisocytosis = mysqli_real_escape_string($conn, $_POST['Anisocytosis']);
    $Poikilocytosis = mysqli_real_escape_string($conn, $_POST['Poikilocytosis']);

    // Update the person details
    $sql_update_person = "UPDATE persons SET name=?, dob=?, age=?, gender_id=?, ms_id=?, contact=? WHERE person_id=?";
    $stmt_update_person = mysqli_prepare($conn, $sql_update_person);
    mysqli_stmt_bind_param($stmt_update_person, 'ssisiii', $name, $dob, $age, $gender_id, $ms_id, $contact, $person_id);
    $result_update_person = mysqli_stmt_execute($stmt_update_person);

    // Update test result
    $sql_update_result = "UPDATE results SET lab_no=?, dept_no=?, test_date=?, result_date=?, result_desc=?, RBC=?, WBC=?, HB=?, PCV=?, MCV=?, MCH=?, MCHC=?, Platelets=?, Hypochromic=?, Macrocytosis=?, Microcytosis=?, Anisocytosis=?, Poikilocytosis=? WHERE person_id=?";
    $stmt_update_result = mysqli_prepare($conn, $sql_update_result);
    mysqli_stmt_bind_param($stmt_update_result, 'sssssssssssssssssi', $lab_no, $dept_no, $test_date, $result_date, $result_desc, $RBC, $WBC, $HB, $PCV, $MCV, $MCH, $MCHC, $Platelets, $Hypochromic, $Macrocytosis, $Microcytosis, $Anisocytosis, $Poikilocytosis, $person_id);
    $result_update_result = mysqli_stmt_execute($stmt_update_result);

    if ($result_update_person && $result_update_result) {
        $success_message = "Test result updated successfully!";
    } else {
        $error_message = "Error updating test result: " . mysqli_error($conn);
    }
}

// Fetch existing test result details
$person_id = mysqli_real_escape_string($conn, $_GET['person_id']);
$sql_select_result = "SELECT * FROM persons INNER JOIN results ON persons.person_id = results.person_id WHERE persons.person_id = $person_id";
$result = mysqli_query($conn, $sql_select_result);
$row = mysqli_fetch_assoc($result);
?>

<main id="main" class="main">

    <div class="pagetitle">
        <h1>Edit Test Result</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="results.php">Results</a></li>
                <li class="breadcrumb-item active">Edit Test Result</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Edit Test Result</h5>

                        <!-- General Form Elements -->
                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <h5 class="text-center">Basic Details</h5>
                            <div class="row mb-3">
                                <label for="result_id" class="col-sm-2 col-form-label">Result ID</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="result_id" id="result_id"
                                        value="<?php echo htmlspecialchars($result_id); ?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="person_id" class="col-sm-2 col-form-label">Person ID</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="person_id" id="person_id"
                                        value="<?php echo htmlspecialchars($person_id); ?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="lab_no" class="col-sm-2 col-form-label">Lab #</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="lab_no" id="lab_no"
                                        value="<?php echo htmlspecialchars($lab_no); ?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="dept_no" class="col-sm-2 col-form-label">Dept #</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="dept_no" id="dept_no"
                                        value="<?php echo htmlspecialchars($dept_no); ?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="test_date" class="col-sm-2 col-form-label">Test Date</label>
                                <div class="col-sm-10">
                                    <input type="date" class="form-control" name="test_date" id="test_date"
                                        value="<?php echo htmlspecialchars($test_date); ?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="result_date" class="col-sm-2 col-form-label">Result Date</label>
                                <div class="col-sm-10">
                                    <input type="date" class="form-control" name="result_date" id="result_date"
                                        value="<?php echo htmlspecialchars($result_date); ?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="name" class="col-sm-2 col-form-label">Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="name" id="name"
                                        value="<?php echo htmlspecialchars($name); ?>" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="dob" class="col-sm-2 col-form-label">Date of Birth</label>
                                <div class="col-sm-10">
                                    <input type="date" class="form-control" name="dob" id="dob"
                                        value="<?php echo htmlspecialchars($dob); ?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="age" class="col-sm-2 col-form-label">Age</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="age" id="age"
                                        value="<?php echo htmlspecialchars($age); ?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="contact" class="col-sm-2 col-form-label">Contact</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="contact" id="contact"
                                        value="<?php echo htmlspecialchars($contact); ?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="gender" class="col-sm-2 col-form-label">Gender</label>
                                <div class="col-sm-10">
                                    <select class="form-control" name="gender" id="gender" required>
                                        <option value="">Select Gender</option>
                                        <?php while ($gender = mysqli_fetch_assoc($genders)) { ?>
                                        <option value="<?php echo $gender['gender_id']; ?>" <?php if
                                            ($gender_id==$gender['gender_id']) echo 'selected' ; ?>>
                                            <?php echo $gender['gender']; ?>
                                        </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="maritial_status" class="col-sm-2 col-form-label">Marital Status</label>
                                <div class="col-sm-10">
                                    <select class="form-control" name="maritial_status" id="maritial_status" required>
                                        <option value="">Select Marital Status</option>
                                        <?php while ($ms = mysqli_fetch_assoc($maritial_statuses)) { ?>
                                        <option value="<?php echo $ms['ms_id']; ?>" <?php if ($ms_id==$ms['ms_id'])
                                            echo 'selected' ; ?>>
                                            <?php echo $ms['status']; ?>
                                        </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <h5 class="text-center">Test Results</h5>
                            <div class="row mb-3">
                                <label for="result_desc" class="col-sm-2 col-form-label">Result Description</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="result_desc" id="result_desc"
                                        style="height: 100px"><?php echo htmlspecialchars($result_desc); ?></textarea>
                                </div>
                            </div>
                            <div class="row mb-3 g-3">
                                <div class="col-md-3">
                                    <div class="col-md-12">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" placeholder="HB" name="HB" id="HB"
                                                value="<?php echo htmlspecialchars($HB); ?>">
                                            <label for="HB">Hemoglobin</label>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="col-md-12">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" placeholder="WBC" name="WBC"
                                                id="WBC" value="<?php echo htmlspecialchars($WBC); ?>">
                                            <label for="WBC">White Blood Cells</label>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="col-md-12">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" placeholder="MP" name="MP" id="MP"
                                                value="<?php echo htmlspecialchars($MP); ?>">
                                            <label for="MP">Malarial Parasite</label>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="col-md-12">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" placeholder="PCV" name="PCV"
                                                id="PCV" value="<?php echo htmlspecialchars($PCV); ?>">
                                            <label for="PCV">Packed Cell Volume</label>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="col-md-12">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" placeholder="MCV" name="MCV"
                                                id="MCV" value="<?php echo htmlspecialchars($MCV); ?>">
                                            <label for="MCV">Mean Corpuscular Volume</label>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="col-md-12">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" placeholder="MCH" name="MCH"
                                                id="MCH" value="<?php echo htmlspecialchars($MCH); ?>">
                                            <label for="MCH">MCH</label>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="col-md-12">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" placeholder="MCHC" name="MCHC"
                                                id="MCHC" value="<?php echo htmlspecialchars($MCHC); ?>">
                                            <label for="MCHC">MCHC</label>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="col-md-12">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" placeholder="RBC" name="RBC"
                                                id="RBC" value="<?php echo htmlspecialchars($RBC); ?>">
                                            <label for="RBC">Red Blood Cell</label>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="col-md-12">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" placeholder="Platelets"
                                                name="Platelets" id="Platelets"
                                                value="<?php echo htmlspecialchars($Platelets); ?>">
                                            <label for="Platelets">Platelets</label>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="col-md-12">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" placeholder="Hypochromic"
                                                name="Hypochromic" id="Hypochromic"
                                                value="<?php echo htmlspecialchars($Hypochromic); ?>">
                                            <label for="Hypochromic">Hypochromic</label>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="col-md-12">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" placeholder="Macrocytosis"
                                                name="Macrocytosis" id="Macrocytosis"
                                                value="<?php echo htmlspecialchars($Macrocytosis); ?>">
                                            <label for="Macrocytosis">Macrocytosis</label>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="col-md-12">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" placeholder="Microcytosis"
                                                name="Microcytosis" id="Microcytosis"
                                                value="<?php echo htmlspecialchars($Microcytosis); ?>">
                                            <label for="Microcytosis">Microcytosis</label>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="col-md-12">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" placeholder="Anisocytosis"
                                                name="Anisocytosis" id="Anisocytosis"
                                                value="<?php echo htmlspecialchars($Anisocytosis); ?>">
                                            <label for="Anisocytosis">Anisocytosis</label>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="col-md-12">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" placeholder="Poikilocytosis"
                                                name="Poikilocytosis" id="Poikilocytosis"
                                                value="<?php echo htmlspecialchars($Poikilocytosis); ?>">
                                            <label for="Poikilocytosis">Poikilocytosis</label>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <button type="reset" class="btn btn-secondary">Reset</button>
                            </div>
                        </form><!-- End General Form Elements -->
                    </div>
                </div>
            </div>
        </div>
    </section>
</main><!-- End #main -->
<?php require('./include/footer.php'); ?>