<?php

require_once 'config/Database.php'; 
require_once 'config/Library.php'; 
use Config\Library;

if (isset($_POST['sent'])) {
    $db = new Database();
    $conn = $db->connect();
    if ($_POST['sent'] == 'all') {
        $data = [];
        $query = "SELECT * FROM dataset WHERE keterangan LIKE '%Buy%'";
        $result = $conn->query($query);
        if($result->num_rows > 0) {
            $data =  mysqli_fetch_all($result, MYSQLI_ASSOC);
        }
    
        echo json_encode($data);
    } else if ($_POST['sent'] == 'id') {
        $id = $_POST['id'];
        $query   =  "SELECT * FROM dataset where id='$id'";
        $result =  $conn->query($query);
        if($result->num_rows > 0) {
            // $data =  mysqli_fetch_all($result, MYSQLI_ASSOC);
            $data =  mysqli_fetch_assoc($result);
        }
        echo json_encode($data);
    
    } else if ($_POST['sent'] == 'add') {
        $ask = $_POST['ask'];
        $respon = $_POST['respon'];
        $query = "INSERT INTO dataset(ask, respon) values('$ask','$respon')";
        $result =  $conn->query($query);
        if ($result) {
            echo Library::jsonRes(true, 'Create Success');
        } else {
            echo Library::jsonRes(false, 'Create Failed');
        }
    } else if ($_POST['sent'] == 'upd') {
        $id = $_POST['id'];
        $ask = $_POST['ask'];
        $respon = $_POST['respon'];
        $query = "UPDATE dataset SET ask='$ask', respon='$respon' WHERE id='$id'";
        $result =  $conn->query($query);
        if ($result) {
            echo Library::jsonRes(true, 'Data Updated Successfully');
        } else {
            echo Library::jsonRes(false, 'Failed to Update');
        }
    } else if ($_POST['sent'] == 'del') {
        $id = $_POST['id'];
        $query = "DELETE FROM dataset WHERE id='$id'";
        $result =  $conn->query($query);
        if ($result) {
            echo Library::jsonRes(true, 'Delete Success');
        } else {
            echo Library::jsonRes(false, 'Delete Failed');
        }
    } else {
        echo Library::jsonRes(false, 'Have not method');
    }
} else {
    echo Library::jsonRes(false, 'Does not anything post');
}
