<?php
// Include header file
require('./include/header.php');

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data and prevent SQL injection
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $gender_id = mysqli_real_escape_string($conn, $_POST['gender']);
    $ms_id = mysqli_real_escape_string($conn, $_POST['marital_status']);
    $role_id = mysqli_real_escape_string($conn, $_POST['role']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Insert into persons table
    $sql_insert_person = "INSERT INTO persons (name, dob, contact, gender_id, ms_id, role_id) VALUES ('$name', '$dob', '$contact', '$gender_id', '$ms_id', '$role_id')";
    mysqli_query($conn, $sql_insert_person);

    // Get the last inserted person_id
    $person_id = mysqli_insert_id($conn);

    // Insert into logins table
    $sql_insert_login = "INSERT INTO logins (username, password, person_id) VALUES ('$username', '$password', '$person_id')";
    mysqli_query($conn, $sql_insert_login);

    // Redirect to the users page
    header("Location: users.php");
    exit();
}
?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Add New User</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="users.php">Users</a></li>
                <li class="breadcrumb-item active">Add New User</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">User Details</h5>
                        <!-- User Form -->
                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="dob" class="form-label">Date of Birth</label>
                                <input type="date" class="form-control" id="dob" name="dob" required>
                            </div>
                            <div class="mb-3">
                                <label for="contact" class="form-label">Contact</label>
                                <input type="text" class="form-control" id="contact" name="contact" required>
                            </div>
                            <div class="mb-3">
                                <label for="gender" class="form-label">Gender</label>
                                <select class="form-select" id="gender" name="gender" required>
                                    <option value="">Select Gender</option>
                                    <?php
                                    $sql_gender = "SELECT * FROM genders";
                                    $result_gender = mysqli_query($conn, $sql_gender);
                                    while ($row = mysqli_fetch_assoc($result_gender)) {
                                        echo "<option value='" . $row['gender_id'] . "'>" . $row['gender'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="marital_status" class="form-label">Marital Status</label>
                                <select class="form-select" id="marital_status" name="marital_status" required>
                                    <option value="">Select Marital Status</option>
                                    <?php
                                    $sql_marital_status = "SELECT * FROM maritial_statuses";
                                    $result_marital_status = mysqli_query($conn, $sql_marital_status);
                                    while ($row = mysqli_fetch_assoc($result_marital_status)) {
                                        echo "<option value='" . $row['ms_id'] . "'>" . $row['status'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="role" class="form-label">Role</label>
                                <select class="form-select" id="role" name="role" required>
                                    <option value="">Select Role</option>
                                    <?php
                                    $sql_role = "SELECT * FROM roles";
                                    $result_role = mysqli_query($conn, $sql_role);
                                    while ($row = mysqli_fetch_assoc($result_role)) {
                                        echo "<option value='" . $row['role_id'] . "'>" . $row['role'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Add User</button>
                        </form>
                        <!-- End User Form -->
                    </div>
                </div>
            </div>
        </div>
    </section>
</main><!-- End #main -->

<?php require('./include/footer.php'); ?>
