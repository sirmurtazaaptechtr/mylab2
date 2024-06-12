<?php
require('./include/header.php');

// Fetch gender options
$sql_gender = "SELECT * FROM `genders`";
$genders = mysqli_query($conn, $sql_gender);

// Fetch marital status options
$sql_ms = "SELECT * FROM `maritial_statuses`";
$maritial_statuses = mysqli_query($conn, $sql_ms);

// Initialize variables for form data
$name = $dob = $age = $gender_id = $ms_id = $contact = "";
$lab_no = $dept_no = $test_date = $result_date = $result_desc = "";
$HB = $WBC = $MP = $PCV = $MCV = $MCH = $MCHC = $RBC = $Platelets = "";
$Hypochromic = $Macrocytosis = $Microcytosis = $Anisocytosis = $Poikilocytosis = "";
$error_message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate required fields
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $gender_id = mysqli_real_escape_string($conn, $_POST['gender']);
    $ms_id = mysqli_real_escape_string($conn, $_POST['maritial_status']);

    if (empty($name) || empty($gender_id) || empty($ms_id)) {
        $error_message = "Name, Gender, and Marital Status are required fields.";
    } else {
        $dob = !empty($_POST['dob']) ? mysqli_real_escape_string($conn, $_POST['dob']) : null;
        $age = !empty($_POST['age']) ? mysqli_real_escape_string($conn, $_POST['age']) : (isset($dob) ? floor((time() - strtotime($dob)) / 31556926) : null);
        $contact = mysqli_real_escape_string($conn, $_POST['contact']);

        $lab_no = mysqli_real_escape_string($conn, $_POST['lab_no']);
        $dept_no = mysqli_real_escape_string($conn, $_POST['dept_no']);
        $test_date = mysqli_real_escape_string($conn, $_POST['test_date']);
        $result_date = mysqli_real_escape_string($conn, $_POST['result_date']);
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

        // Insert into persons table
        $sql_person = "INSERT INTO persons (name, dob, age, contact, gender_id, ms_id) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt_person = mysqli_prepare($conn, $sql_person);
        mysqli_stmt_bind_param($stmt_person, 'ssissi', $name, $dob, $age, $contact, $gender_id, $ms_id);

        if (mysqli_stmt_execute($stmt_person)) {
            // Get the last inserted person_id
            $person_id = mysqli_insert_id($conn);

            // Insert into results table
            $sql_result = "INSERT INTO results (person_id, lab_no, dept_no, test_date, result_date, HB, WBC, MP, PCV, MCV, MCH, MCHC, RBC, Platelets, Hypochromic, Macrocytosis, Microcytosis, Anisocytosis, Poikilocytosis, result_desc)
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt_result = mysqli_prepare($conn, $sql_result);
            mysqli_stmt_bind_param($stmt_result, 'isssssssssssssssssss', $person_id, $lab_no, $dept_no, $test_date, $result_date, $HB, $WBC, $MP, $PCV, $MCV, $MCH, $MCHC, $RBC, $Platelets, $Hypochromic, $Macrocytosis, $Microcytosis, $Anisocytosis, $Poikilocytosis, $result_desc);

            if (mysqli_stmt_execute($stmt_result)) {
                echo "New test result added successfully!";
                
                // Redirect to the results page
                header("Location: results.php");
                exit();
            } else {
                $error_message = "Error inserting into results: " . mysqli_error($conn);
            }
        } else {
            $error_message = "Error inserting into persons: " . mysqli_error($conn);
        }
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

                        <?php if (!empty($error_message)) { ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo $error_message; ?>
                            </div>
                        <?php } ?>

                        <!-- General Form Elements -->
                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <h5 class="text-center">Basic Details</h5>
                            <div class="row mb-3">
                                <label for="lab_no" class="col-sm-2 col-form-label">Lab #</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="lab_no" id="lab_no" value="<?php echo htmlspecialchars($lab_no); ?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="dept_no" class="col-sm-2 col-form-label">Dept #</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="dept_no" id="dept_no" value="<?php echo htmlspecialchars($dept_no); ?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="test_date" class="col-sm-2 col-form-label">Test Date</label>
                                <div class="col-sm-10">
                                    <input type="date" class="form-control" name="test_date" id="test_date" value="<?php echo htmlspecialchars($test_date); ?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="result_date" class="col-sm-2 col-form-label">Result Date</label>
                                <div class="col-sm-10">
                                    <input type="date" class="form-control" name="result_date" id="result_date" value="<?php echo htmlspecialchars($result_date); ?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="name" class="col-sm-2 col-form-label">Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="name" id="name" value="<?php echo htmlspecialchars($name); ?>" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="dob" class="col-sm-2 col-form-label">Date of Birth</label>
                                <div class="col-sm-10">
                                    <input type="date" class="form-control" name="dob" id="dob" value="<?php echo htmlspecialchars($dob); ?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="age" class="col-sm-2 col-form-label">Age</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="age" id="age" value="<?php echo htmlspecialchars($age); ?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="contact" class="col-sm-2 col-form-label">Contact</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="contact" id="contact" value="<?php echo htmlspecialchars($contact); ?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="gender" class="col-sm-2 col-form-label">Gender</label>
                                <div class="col-sm-10">
                                    <select class="form-control" name="gender" id="gender" required>
                                        <option value="">Select Gender</option>
                                        <?php while ($gender = mysqli_fetch_assoc($genders)) { ?>
                                            <option value="<?php echo $gender['gender_id']; ?>" <?php if ($gender_id == $gender['gender_id']) echo 'selected'; ?>>
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
                                            <option value="<?php echo $ms['ms_id']; ?>" <?php if ($ms_id == $ms['ms_id']) echo 'selected'; ?>>
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
                                    <textarea class="form-control" name="result_desc" id="result_desc" style="height: 100px"><?php echo htmlspecialchars($result_desc); ?></textarea>
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
