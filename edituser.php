<?php
// Include header file
require('./include/header.php');

// Get the user ID from the query string
$person_id = isset($_GET['person_id']) ? intval($_GET['person_id']) : 0;

// Fetch user details if ID is valid
if ($person_id > 0) {
    $sql_person = "SELECT * FROM persons WHERE person_id = $person_id";
    $result_person = mysqli_query($conn, $sql_person);

    if ($result_person && mysqli_num_rows($result_person) > 0) {
        $person = mysqli_fetch_assoc($result_person);

        // Fetch login details
        $sql_login = "SELECT * FROM logins WHERE person_id = $person_id";
        $result_login = mysqli_query($conn, $sql_login);
        $login = mysqli_fetch_assoc($result_login);
    } else {
        echo "User not found.";
        exit();
    }
} else {
    echo "Invalid user ID.";
    exit();
}

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

    // Update persons table
    $sql_update_person = "UPDATE persons SET name='$name', dob='$dob', contact='$contact', gender_id='$gender_id', ms_id='$ms_id', role_id='$role_id' WHERE person_id=$person_id";
    mysqli_query($conn, $sql_update_person);

    // Update logins table
    $sql_update_login = "UPDATE logins SET username='$username', password='$password' WHERE person_id=$person_id";
    mysqli_query($conn, $sql_update_login);

    // Redirect to the users page
    header("Location: users.php");
    exit();
}
?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Update User</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="users.php">Users</a></li>
                <li class="breadcrumb-item active">Update User</li>
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
                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?person_id=' . $person_id; ?>">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($person['name']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="dob" class="form-label">Date of Birth</label>
                                <input type="date" class="form-control" id="dob" name="dob" value="<?php echo htmlspecialchars($person['dob']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="contact" class="form-label">Contact</label>
                                <input type="text" class="form-control" id="contact" name="contact" value="<?php echo htmlspecialchars($person['contact']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="gender" class="form-label">Gender</label>
                                <select class="form-select" id="gender" name="gender" required>
                                    <option value="">Select Gender</option>
                                    <?php
                                    $sql_gender = "SELECT * FROM genders";
                                    $result_gender = mysqli_query($conn, $sql_gender);
                                    while ($row = mysqli_fetch_assoc($result_gender)) {
                                        $selected = ($row['gender_id'] == $person['gender_id']) ? 'selected' : '';
                                        echo "<option value='" . $row['gender_id'] . "' $selected>" . $row['gender'] . "</option>";
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
                                        $selected = ($row['ms_id'] == $person['ms_id']) ? 'selected' : '';
                                        echo "<option value='" . $row['ms_id'] . "' $selected>" . $row['status'] . "</option>";
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
                                        $selected = ($row['role_id'] == $person['role_id']) ? 'selected' : '';
                                        echo "<option value='" . $row['role_id'] . "' $selected>" . $row['role'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($login['username']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" value="<?php echo htmlspecialchars($login['password']); ?>" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Update User</button>
                        </form>
                        <!-- End User Form -->
                    </div>
                </div>
            </div>
        </div>
    </section>
</main><!-- End #main -->

<?php require('./include/footer.php'); ?>
