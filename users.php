<?php
require('./include/header.php');

$sql = "SELECT *,persons.person_id as person_id FROM `persons` 
LEFT JOIN `logins` ON `persons`.person_id = `logins`.person_id
LEFT JOIN `genders` ON `persons`.gender_id = `genders`.gender_id
LEFT JOIN `roles` ON `persons`.role_id = `roles`.role_id
LEFT JOIN `maritial_statuses` ON `persons`.ms_id = `maritial_statuses`.ms_id
";
$result = mysqli_query($conn,$sql);

?>
<main id="main" class="main">

  <div class="pagetitle">
    <h1>All Users</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
        <li class="breadcrumb-item active">Users</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section">
    <div class="row">
      <div class="col-lg-12">

        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Users</h5>                        
            <!-- Table with stripped rows -->
            <div class="table-responsive">
              <table class="table datatable">
                <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">User ID</th>
                  <th scope="col">Name</th>
                  <th scope="col">Date of Birth</th>
                  <th scope="col">Contact</th>
                  <!-- <th scope="col">Gender ID</th> -->
                  <th scope="col">Gender</th>
                  <!-- <th scope="col">Maritial Status ID</th> -->
                  <th scope="col">Maritial Status</th>
                  <!-- <th scope="col">Role ID</th> -->
                  <th scope="col">Role</th>
                  <th scope="col">Username</th>
                  <th scope="col">Password</th>
                  <th scope="col">Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php $srno=1; while($data = mysqli_fetch_assoc($result)) {?>
                <tr>
                  <th scope="row"><?php echo $srno;?></th>
                  <td><?php echo $data['person_id']?></td>
                  <td><?php echo $data['name']?></td>
                  <td><?php echo $data['dob']?></td>
                  <td><?php echo $data['contact']?></td>
                  <!-- <td><?php // echo $data['gender_id']?></td> -->
                  <td><?php echo $data['gender']?></td>
                  <!-- <td><?php // echo $data['ms_id']?></td> -->
                  <td><?php echo $data['status']?></td>
                  <!-- <td><?php // echo $data['role_id']?></td> -->
                  <td><?php echo $data['role']?></td>
                  <td><?php echo $data['username']?></td>
                  <td><?php echo $data['password']?></td>
                  <td>
                    <a href="edituser.php?person_id=<?php echo $data["person_id"];?>">Edit</a> | 
                    <a href="deleteuser.php?person_id=<?php echo $data["person_id"];?>" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                  </td>
                </tr>
                <?php $srno++; }?>
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

<?php require('./include/footer.php'); ?>