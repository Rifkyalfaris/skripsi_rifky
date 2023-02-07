<?php

require_once 'config/Database.php'; 
require_once 'config/Library.php'; 

use Config\Library;

if (isset($_POST['sent'])) {
    $db = new Database();
    $conn = $db->connect();
    if ($_POST['sent'] == 'nb') {
        $data = [];
        // $command = escapeshellcmd('/usr/custom/test.py');
        // $output = shell_exec($command);
        $output = exec('python Library/nb.py');
        echo $output;
    
        // echo json_encode($data);
    } else if ($_POST['sent'] == 'kmeans') {
        $data = [];
        $output = exec('python Library/kmeans.py');
        echo $output;
    
    } else {
        echo Library::jsonRes(false, 'Have not method');
    }
} else {
    echo Library::jsonRes(false, 'Does not anything post');
}
