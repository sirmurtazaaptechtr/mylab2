<?php
require('./include/connection.php');

if (isset($_GET['person_id'])) {
    $person_id = $_GET['person_id'];

    $sql = "DELETE FROM persons WHERE person_id='$person_id'";

    if (mysqli_query($conn,$sql)) {
        echo "Record deleted successfully";
    } else {
        echo "Error: " . $sql . "<br>" .  mysqli_connect_error();
    }

    header("Location: users.php");
} else {
    echo "Invalid ID";
}
?>
