<?php 
include 'config/Library.php';
include 'config/Database.php';
// include 'config/App.php';

use Config\Library;
use App\DotEnv;
use App\Library\NaiveBayes;

$hash = '$argon2id$v=19$m=65536,t=4,p=1$S1NyM2M0bDgxckJPY2o5SA$ffeGfSwmqjOqy1CnNft4WGignt1zJznI5/5G5BP/9VQ';
// $hash = password_hash('asdqwe123', PASSWORD_ARGON2ID );
// $input = 'asdqwe123';
$input = 'P@554mysql';
if (password_verify($input, $hash)) {
// if (crypt($input, $hash)) {
    echo 'Password is valid!';
} else {
    echo 'Invalid password.';
}

echo '<hr/>';
$text = 'THIS IS A TEST TEXT';
// $pattern = 'TEST';
$pattern = 'tes';
// $text = 'dfxxiaofangdfere';
// $pattern = 'xiaofang';

// echo 'Find it , the position is '.BM::BoyerMoore($text, $pattern);

// include 'algo/BoyerMooreV2.php';
// BoyerMoore($pattern, strlen($pattern), $text, strlen($text));

echo '<hr/>';
echo Library::jsonRes(true, 'Create Success');


// echo '<hr/>';
// (new DotEnv(__DIR__ . '/.env'))->load();
// echo getenv('APP_ENV');

echo '<hr/>';
$db = new Database();
$conn = $db->connect();
$data = [];
// $query = "SELECT * FROM about";
// // $query = "SELECT a.id, a.ask, a.respon, ad.id AS aboutDetailId, ad.desc FROM about a LEFT JOIN aboutDetail ad ON ad.aboutId = a.id";
// $result = $conn->query($query);
// if($result->num_rows > 0) {
//     $data =  mysqli_fetch_all($result, MYSQLI_ASSOC);
// }

echo json_encode($data);
// ======================================================================
echo '<hr/>';

require 'Library/nbProses.php';
// $getLabel = '(CASE WHEN table_hoax.label = 1 THEN "Fact" ELSE "Hoax" END) AS Label, judul as Judul, narasi as Narasi';
$getLabel = 'select * from tbl_dataset limit 1';
$result = $conn->query($getLabel);
if($result->num_rows > 0) {
    $resData =  mysqli_fetch_all($result, MYSQLI_ASSOC);
    echo json_encode($resData);

    ProsesNaiveBayes($conn, $resData[0]['name'], $resData[0]['mar'], $resData[0]['apr'], $resData[0]['mei']);
}


// ======================================================================
echo '<hr/>';
require 'Library/naivebayes1.php';
// $data = olahData(getData("dt_training.txt"));
$query = "SELECT * FROM tbl_dataset";
$result = $conn->query($query);
if($result->num_rows > 0) {
    $data =  mysqli_fetch_all($result, MYSQLI_ASSOC);

    $uji = [];
    for ($i=1; $i < count($data); $i++) {
        // $uji[] = [
        //     'text' => $data[$i]['name'],
        //     'class' => $data[$i]['label'],
        // ];
        // $uji[] = [$data[$i]['name'],$data[$i]['mar'],$data[$i]['apr'],$data[$i]['mei'],$data[$i]['label']];
        $uji[] = [$data[$i]['name'], $data[$i]['label']];
    }
    // print_r(json_encode($uji));

    // $nb = new NaiveBayes($uji, ['Tinggi', 'Rendah']);
    // $nb = new NaiveBayes($uji, ['Name', 'stokmar', 'stokapr', 'stokmei']);
    $nb = new NaiveBayes($uji, ['Name']);

    // pengujian
    $queryText = 'select DISTINCT name from tbl_dataset
            ORDER BY RAND()
            LIMIT 1';
    $result = $conn->query($queryText);
    if($result->num_rows > 0) {
        $text =  mysqli_fetch_all($result, MYSQLI_ASSOC);
        $res = $nb->predict($text);

        // $data_test = ["Cerah","Normal","Kencang"];
        // print_r(json_encode($nb->run()->predict($text)));
    }

    // print_r([ 'train' => $train, 'result' => $res, ]);
}



// print_r(json_encode($data));