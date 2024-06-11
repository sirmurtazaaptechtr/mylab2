<?php
require('./include/header.php');

// Fetch tests
$sql_tests = "SELECT * FROM `tests`";
$tests = mysqli_query($conn, $sql_tests);

// Fetch genders
$sql_genders = "SELECT * FROM `genders`";
$genders = mysqli_query($conn, $sql_genders);

// Fetch marital statuses
$sql_marital_statuses = "SELECT * FROM `maritial_statuses`";
$marital_statuses = mysqli_query($conn, $sql_marital_statuses);
?>

<main id="main" class="main">

    <div class="pagetitle">
        <h1>Generate Test Report</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Generate Test Report</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Select Criteria</h5>

                        <form method="POST" action="generate_report.php">
                            <div class="row mb-3">
                                <label for="min_age" class="col-sm-2 col-form-label">Min Age</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" name="min_age" id="min_age" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="max_age" class="col-sm-2 col-form-label">Max Age</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" name="max_age" id="max_age" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="gender" class="col-sm-2 col-form-label">Gender</label>
                                <div class="col-sm-10">
                                    <select class="form-select" aria-label="Gender Select" name="gender" id="gender" required>
                                        <option selected disabled>Select Gender</option>
                                        <?php while ($gender = mysqli_fetch_assoc($genders)) { ?>
                                            <option value="<?php echo $gender['gender_id'] ?>"><?php echo $gender['gender'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="marital_status" class="col-sm-2 col-form-label">Marital Status</label>
                                <div class="col-sm-10">
                                    <select class="form-select" aria-label="Marital Status Select" name="marital_status" id="marital_status" required>
                                        <option selected disabled>Select Marital Status</option>
                                        <?php while ($ms = mysqli_fetch_assoc($marital_statuses)) { ?>
                                            <option value="<?php echo $ms['ms_id'] ?>"><?php echo $ms['status'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="test" class="col-sm-2 col-form-label">Test</label>
                                <div class="col-sm-10">
                                    <select class="form-select" aria-label="Test Select" name="test" id="test" required>
                                        <option selected disabled>Select Test</option>
                                        <?php while ($test = mysqli_fetch_assoc($tests)) { ?>
                                            <option value="<?php echo $test['test_id'] ?>"><?php echo $test['test_name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Generate Report</label>
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-primary" name="generateBtn">Generate</button>
                                </div>
                            </div>
                        </form><!-- End Form -->
                    </div>
                </div>
            </div>
        </div>
    </section>
</main><!-- End #main -->

<?php require('./include/footer.php'); ?>