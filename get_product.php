<?php
include 'DBConn.php';
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $result = mysqli_query($conn, "SELECT * FROM tblclothes WHERE clothes_id=$id");
    echo json_encode(mysqli_fetch_assoc($result));
}
?>