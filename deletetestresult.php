<?php
require('./include/header.php');
$error = "";
$success = "";

// Check if an ID is provided to delete the result
if (!isset($_GET['result_id'])) {
    $error = "No result ID provided.";
} else {
    $result_id = $_GET['result_id'];

    // Start transaction
    mysqli_begin_transaction($conn);

    try {
        // Fetch the person_id associated with the result_id
        $sql_fetch_person_id = "SELECT person_id FROM results WHERE result_id = ?";
        $stmt_fetch_person_id = mysqli_prepare($conn, $sql_fetch_person_id);
        mysqli_stmt_bind_param($stmt_fetch_person_id, 'i', $result_id);
        mysqli_stmt_execute($stmt_fetch_person_id);
        $result_fetch_person_id = mysqli_stmt_get_result($stmt_fetch_person_id);
        $person_data = mysqli_fetch_assoc($result_fetch_person_id);

        if (!$person_data) {
            throw new Exception("No result found with the provided ID.");
        }

        $person_id = $person_data['person_id'];

        // Delete all test results associated with the person_id
        $sql_delete_results = "DELETE FROM results WHERE person_id = ?";
        $stmt_delete_results = mysqli_prepare($conn, $sql_delete_results);
        mysqli_stmt_bind_param($stmt_delete_results, 'i', $person_id);
        mysqli_stmt_execute($stmt_delete_results);

        // Delete the person associated with the test results
        $sql_delete_person = "DELETE FROM persons WHERE person_id = ?";
        $stmt_delete_person = mysqli_prepare($conn, $sql_delete_person);
        mysqli_stmt_bind_param($stmt_delete_person, 'i', $person_id);
        mysqli_stmt_execute($stmt_delete_person);

        // Commit transaction
        mysqli_commit($conn);
        $success = "Test result and associated person deleted successfully!";
    } catch (Exception $e) {
        // Rollback transaction
        mysqli_rollback($conn);
        $error = "Error: " . $e->getMessage();
    }
}

?>
<main id="main" class="main">

    <div class="pagetitle">
        <h1>Delete Test Result</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="results.php">Results</a></li>
                <li class="breadcrumb-item active">Delete Test Result</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Delete Test Result</h5>
                        <?php if (!empty($error)) { ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $error; ?>
                        </div>
                        <?php } elseif (!empty($success)) { ?>
                        <div class="alert alert-success" role="alert">
                            <?php echo $success; ?>
                        </div>
                        <?php } else { ?>
                        <div class="alert alert-warning" role="alert">
                            Are you sure you want to delete this test result and the associated person?
                            <form method="GET" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                <input type="hidden" name="result_id" value="<?php echo $_GET['result_id']; ?>">
                                <button type="submit" class="btn btn-danger mt-3">Confirm Delete</button>
                            </form>
                        </div>
                        <?php } ?>
                    </div>
                </div>

            </div>
        </div>
    </section>

</main><!-- End #main -->
<?php require('./include/footer.php'); ?>
