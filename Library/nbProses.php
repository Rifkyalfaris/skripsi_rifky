<?php
function dec(){
	return 4;
}

function jumlah_data_latih($db_object, $where=null){
	$sql = "SELECT COUNT(*) FROM tbl_dataset ".$where." Limit 1";
	$res = $db_object->query($sql);
	// $rows = $db_object->db_fetch_array($res);
	if($res->num_rows > 0) {
		$data =  mysqli_fetch_all($res, MYSQLI_NUM);
		// $rows = $res->fetch_array(MYSQLI_NUM);
		return $data[0];
	}
}

/**
 * 
 * @param type $db_object
 * @param type $id_data_uji
 * @param type $jenis_kelamin
 * @param type $usia
 * @param type $sekolah
 * @param type $jawaban_a
 * @param type $jawaban_b
 * @param type $jawaban_c
 * @param type $jawaban_d
 * @return array
 */
function ProsesNaiveBayes($db_object, $id_data_uji=0, $mar, $apr, $mei, $show_perhitungan=false){
	
	$jumlah_data = jumlah_data_latih($db_object);//jumlah data latih
	$jumlah_tinggi = jumlah_data_latih($db_object, " WHERE minat='Tinggi'");//jumlah Tinggi
	$jumlah_rendah = jumlah_data_latih($db_object, " WHERE minat='Rendah'");//jumlah Rendah

	$p_tinggi = $jumlah_tinggi/$jumlah_data;
	$p_rendah = $jumlah_rendah/$jumlah_data;

	//jumlah atribut 
	// $jumlah_jenis_barang_dapur_tinggi = jumlah_data_latih($db_object, " WHERE jenis_barang='PERALATAN DAPUR' AND minat='Tinggi'");
	// $jumlah_jenis_barang_makanan_tinggi = jumlah_data_latih($db_object, " WHERE jenis_barang='PERALATAN MAKANAN' AND minat='Tinggi'");
	// $jumlah_jenis_barang_kebersihan_tinggi = jumlah_data_latih($db_object, " WHERE jenis_barang='PERALATAN KEBERSIHAN' AND minat='Tinggi'");

	// $jumlah_jenis_barang_dapur_rendah = jumlah_data_latih($db_object, " WHERE jenis_barang='PERALATAN DAPUR' AND minat='Rendah'");
	// $jumlah_jenis_barang_makanan_rendah = jumlah_data_latih($db_object, " WHERE jenis_barang='PERALATAN MAKANAN' AND minat='Rendah'");
	// $jumlah_jenis_barang_kebersihan_rendah = jumlah_data_latih($db_object, " WHERE jenis_barang='PERALATAN KEBERSIHAN' AND minat='Rendah'");
        
	// //probabilitas atribut 
	// $p_jenis_barang_dapur_tinggi = $jumlah_jenis_barang_dapur_tinggi/$jumlah_tinggi;
	// $p_jenis_barang_makanan_tinggi = $jumlah_jenis_barang_makanan_tinggi/$jumlah_tinggi;
	// $p_jenis_barang_kebersihan_tinggi = $jumlah_jenis_barang_kebersihan_tinggi/$jumlah_tinggi;
        
	// $p_jenis_barang_dapur_rendah = $jumlah_jenis_barang_dapur_rendah/$jumlah_rendah;
	// $p_jenis_barang_makanan_rendah = $jumlah_jenis_barang_makanan_rendah/$jumlah_rendah;
	// $p_jenis_barang_kebersihan_rendah = $jumlah_jenis_barang_kebersihan_rendah/$jumlah_rendah;
        
	//display table probabilitas 
	// if($show_perhitungan){
		// echo "<table class='table table-bordered table-striped  table-hover' style='width:40%'>";
		// 	echo "<tr>";
		// 		echo "<td><b><u>Jenis Barang:</u></b></td>";
		// 		echo "<td>Tinggi</td>";
		// 		echo "<td>Rendah</td>";
		// 	echo "</tr>";
		// 	echo "<tr>";
		// 		echo "<td>PERALATAN DAPUR</td>";
		// 		echo "<td>".number_format($p_jenis_barang_dapur_tinggi, dec())."</td>";
		// 		echo "<td>".number_format($p_jenis_barang_dapur_rendah, dec())."</td>";
		// 	echo "</tr>";
		// 	echo "<tr>";
			//                 echo "<td>PERALATAN MAKANAN</td>";
			//                 echo "<td>".number_format($p_jenis_barang_makanan_tinggi, dec())."</td>";
			//                 echo "<td>".number_format($p_jenis_barang_makanan_rendah, dec())."</td>";
		// 	echo "</tr>";
			//         echo "<tr>";
		// 		echo "<td>PERALATAN KEBERSIHAN</td>";
		// 		echo "<td>".number_format($p_jenis_barang_kebersihan_tinggi, dec())."</td>";
		// 		echo "<td>".number_format($p_jenis_barang_kebersihan_rendah, dec())."</td>";
		// 	echo "</tr>";
		// echo "</table>";

		// echo "<br>";
	// }

	//jawaban_a
	//x jawaban_a 
	$jumlah_total_mar_tinggi = get_jumlah_sum_atribut($db_object, "mar", "Tinggi");
	$x_total_mar_tinggi = $jumlah_total_mar_tinggi/$jumlah_tinggi;
	//x jawaban_a  
	$jumlah_total_mar_rendah = get_jumlah_sum_atribut($db_object, "mar", "Rendah");
	$x_total_mar_rendah = $jumlah_total_mar_rendah/$jumlah_rendah;

	if($show_perhitungan){
		// echo "<br>";
		// echo "<strong><u>Atribut Maret:<br></u></strong>";
		// echo "X Maret Tinggi=".number_format($x_total_mar_tinggi, dec())."<br>";
		// echo "X Maret Rendah=".number_format($x_total_mar_rendah, dec())."<br>";
		// echo "<br>";
	}

	$s2_total_mar_tinggi = get_s2_populasi($db_object, 'mar', 'Tinggi', $x_total_mar_tinggi, $jumlah_tinggi);
	$s2_total_mar_rendah = get_s2_populasi($db_object, 'mar', 'Rendah', $x_total_mar_rendah, $jumlah_rendah);

	if($show_perhitungan){
		// echo "S2 Maret Tinggi=".number_format($s2_total_mar_tinggi, dec())."<br>";
		// echo "S2 Maret Rendah=".number_format($s2_total_mar_rendah, dec())."<br>";
		// echo "<br>";
	}

	// //S jawaban_a 
	// $s_total_mar_tinggi = sqrt($s2_total_mar_tinggi);
	// //S jawaban_a 
	// $s_total_mar_rendah = sqrt($s2_total_mar_rendah);

	if($show_perhitungan){
		// echo "S Maret Tinggi =".number_format($s_total_mar_tinggi, dec())."<br>";
		// echo "S Maret Rendah =".number_format($s_total_mar_rendah, dec())."<br>";
	}

	//s2 ^2 jawaban_a 
	$s2_pangkat2_mar_tinggi = pow($s2_total_mar_tinggi, 2);
	//s2 ^2 jawaban_a 
	$s2_pangkat2_mar_rendah = pow($s2_total_mar_rendah, 2);
	
	//===================================================================================
	//x jawaban_a 
	$jumlah_total_apr_tinggi = get_jumlah_sum_atribut($db_object, "apr", "Tinggi");
	$x_total_apr_tinggi = $jumlah_total_apr_tinggi/$jumlah_tinggi;
	//x jawaban_a  
	$jumlah_total_apr_rendah = get_jumlah_sum_atribut($db_object, "apr", "Rendah");
	$x_total_apr_rendah = $jumlah_total_apr_rendah/$jumlah_rendah;

	if($show_perhitungan){
		// echo "<br>";
		// echo "<strong><u>Atribut April:<br></u></strong>";
		// echo "X April Tinggi=".number_format($x_total_apr_tinggi, dec())."<br>";
		// echo "X April Rendah=".number_format($x_total_apr_rendah, dec())."<br>";
		// echo "<br>";
	}

	$s2_total_apr_tinggi = get_s2_populasi($db_object, 'apr', 'Tinggi', $x_total_apr_tinggi, $jumlah_tinggi);
	$s2_total_apr_rendah = get_s2_populasi($db_object, 'apr', 'Rendah', $x_total_apr_rendah, $jumlah_rendah);

	if($show_perhitungan){
		// echo "S2 April Tinggi=".number_format($s2_total_apr_tinggi, dec())."<br>";
		// echo "S2 April Rendah=".number_format($s2_total_apr_rendah, dec())."<br>";
		// echo "<br>";
	}

	// //S jawaban_a 
	// $s_total_apr_tinggi = sqrt($s2_total_apr_tinggi);
	// //S jawaban_a 
	// $s_total_apr_rendah = sqrt($s2_total_apr_rendah);
	// if($show_perhitungan){
	// 	// echo "S April Tinggi =".number_format($s_total_apr_tinggi, dec())."<br>";
	// 	// echo "S April Rendah =".number_format($s_total_apr_rendah, dec())."<br>";
	// }

	//s2 ^2 jawaban_a 
	$s2_pangkat2_apr_tinggi = pow($s2_total_apr_tinggi, 2);
	//s2 ^2 jawaban_a 
	$s2_pangkat2_apr_rendah = pow($s2_total_apr_rendah, 2);

	//===================================================================================
	//x jawaban_a 
	$jumlah_total_mei_tinggi = get_jumlah_sum_atribut($db_object, "mei", "Tinggi");
	$x_total_mei_tinggi = $jumlah_total_mei_tinggi/$jumlah_tinggi;
	//x jawaban_a  
	$jumlah_total_mei_rendah = get_jumlah_sum_atribut($db_object, "mei", "Rendah");
	$x_total_mei_rendah = $jumlah_total_mei_rendah/$jumlah_rendah;

	if($show_perhitungan){
		// echo "<br>";
		// echo "<strong><u>Atribut Mei:<br></u></strong>";
		// echo "X Mei Tinggi=".number_format($x_total_mei_tinggi, dec())."<br>";
		// echo "X Mei Rendah=".number_format($x_total_mei_rendah, dec())."<br>";
		// echo "<br>";
	}

	$s2_total_mei_tinggi = get_s2_populasi($db_object, 'mei', 'Tinggi', $x_total_mei_tinggi, $jumlah_tinggi);
	$s2_total_mei_rendah = get_s2_populasi($db_object, 'mei', 'Rendah', $x_total_mei_rendah, $jumlah_rendah);

	if($show_perhitungan){
		// echo "S2 Mei Tinggi=".number_format($s2_total_mei_tinggi, dec())."<br>";
		// echo "S2 Mei Rendah=".number_format($s2_total_mei_rendah, dec())."<br>";
		// echo "<br>";
	}

	// //S jawaban_a 
	// $s_total_mei_tinggi = sqrt($s2_total_mei_tinggi);
	// //S jawaban_a 
	// $s_total_mei_rendah = sqrt($s2_total_mei_rendah);

	// if($show_perhitungan){
	// 	// echo "S Mei Tinggi =".number_format($s_total_mei_tinggi, dec())."<br>";
	// 	// echo "S Mei Rendah =".number_format($s_total_mei_rendah, dec())."<br>";
	// }

	//s2 ^2 jawaban_a sanguin
	$s2_pangkat2_mei_tinggi = pow($s2_total_mei_tinggi, 2);
	//s2 ^2 jawaban_a koleris
	$s2_pangkat2_mei_rendah = pow($s2_total_mei_rendah, 2);

	//===================================================================================
	//#HITUNG PROBABILITAS DENGAN DATA UJI
	if($show_perhitungan){
		// echo "<strong><h3>Probabilitas<br></h3></strong>";
	}
	$dua_phi = (2*3.14);
        
	$depan_mar_tinggi = sqrt($dua_phi*$s2_total_mar_tinggi);
	$belakang_mar_tinggi = exp( ((pow($mar-$x_total_mar_tinggi,2)) / (2*$s2_pangkat2_mar_tinggi)));
	$prob_mar_tinggi = 1/($depan_mar_tinggi * $belakang_mar_tinggi);
	//koleris
	$depan_mar_rendah = sqrt($dua_phi*$s2_total_mar_rendah);
	$belakang_mar_rendah = exp( ((pow($mar-$x_total_mar_rendah,2)) / (2*$s2_pangkat2_mar_rendah)));
	$prob_mar_rendah = 1/($depan_mar_rendah * $belakang_mar_rendah);

	//display
	if($show_perhitungan){
		// echo "<br>";
		// echo "P(mar|Tinggi)=".number_format($prob_mar_tinggi, dec())."<br>";
		// echo "P(mar|Rendah)=".number_format($prob_mar_rendah, dec())."<br>";
	}
	//======================================================================
	//#jawaban_b
	$depan_apr_tinggi = sqrt($dua_phi*$s2_total_apr_tinggi);
	$belakang_apr_tinggi = exp( ((pow($apr-$x_total_apr_tinggi,2)) / (2*$s2_pangkat2_apr_tinggi)));
	$prob_apr_tinggi = 1/($depan_apr_tinggi * $belakang_apr_tinggi);
	//koleris
	$depan_apr_rendah = sqrt($dua_phi*$s2_total_apr_rendah);
	$belakang_apr_rendah = exp( ((pow($apr-$x_total_apr_rendah,2)) / (2*$s2_pangkat2_apr_rendah)));
	$prob_apr_rendah = 1/($depan_apr_rendah * $belakang_apr_rendah);

	//display
	if($show_perhitungan){
		// echo "<br>";
		// echo "P(mar|Tinggi)=".number_format($prob_apr_tinggi, dec())."<br>";
		// echo "P(mar|Rendah)=".number_format($prob_apr_rendah, dec())."<br>";
	}
	//======================================================================
	//#jawaban_c
	$depan_mei_tinggi = sqrt($dua_phi*$s2_total_mei_tinggi);
	$belakang_mei_tinggi = exp( ((pow($mei-$x_total_mei_tinggi,2)) / (2*$s2_pangkat2_mei_tinggi)));
	$prob_mei_tinggi = 1/($depan_mei_tinggi * $belakang_mei_tinggi);
	//koleris
	$depan_mei_rendah = sqrt($dua_phi*$s2_total_mei_rendah);
	$belakang_mei_rendah = exp( ((pow($mei-$x_total_mei_rendah,2)) / (2*$s2_pangkat2_mei_rendah)));
	$prob_mei_rendah = 1/($depan_mei_rendah * $belakang_mei_rendah);

	//display
	if($show_perhitungan){
		// echo "<br>";
		// echo "P(mar|Tinggi)=".number_format($prob_mei_tinggi, dec())."<br>";
		// echo "P(mar|Rendah)=".number_format($prob_mei_rendah, dec())."<br>";
	}
	//======================================================================

	$nilai_tinggi = $p_tinggi * $prob_mar_tinggi * $prob_apr_tinggi * $prob_mei_tinggi;
	if($show_perhitungan){
		// echo "<br>";
		// echo "Nilai Tinggi = ".number_format($p_tinggi, dec())
		//                     ." x ".number_format($prob_mar_tinggi, dec())
		//                     ." x ".number_format($prob_apr_tinggi, dec())
		//                     ." x ".number_format($prob_mei_tinggi, dec())
		//                     ." = ".number_format($nilai_tinggi, 20);
	}
	$nilai_rendah = $p_rendah * $prob_mar_rendah * $prob_apr_rendah * $prob_mei_rendah;
	if($show_perhitungan){
		// echo "<br>";
		// echo "Nilai Rendah = ".number_format($p_rendah, dec())
		//                     ." x ".number_format($prob_mar_rendah, dec())
		//                     ." x ".number_format($prob_apr_rendah, dec())
		//                     ." x ".number_format($prob_mei_rendah, dec())
		//                     ." = ".number_format($nilai_rendah, 20);
	}

    $hasil_prediksi = '';
    
    if($nilai_tinggi>=$nilai_rendah){
        $hasil_prediksi = 'Tinggi';
    }
    elseif($nilai_rendah>=$nilai_tinggi){
    	$hasil_prediksi = 'Rendah';
    }

	//     echo "<strong>";
	//     echo "<h2>";
	//     echo "Hasil prediksi = ".$hasil_prediksi;
	//     echo "</h2>";
	//     echo "</strong>";
	//     echo "<br>";

	//    $nilai_sanguin = number_format($nilai_sanguin, 50);
	//    $nilai_koleris = number_format($nilai_koleris, 50);
	
    // if($id_data_uji>0){
    //     $res_hasil = update_hasil_prediksi($db_object, $id_data_uji, $hasil_prediksi, $nilai_tinggi, $nilai_rendah);
    // }

    return array($hasil_prediksi, $nilai_tinggi, $nilai_rendah);
      
}
	
function update_hasil_prediksi($db_object, $id, $hasil, $nilai_tinggi, $nilai_rendah){
	$sql = "UPDATE data_uji "
                . "SET "
                . "minat_hasil='$hasil', "
                . "nilai_tinggi='$nilai_tinggi', "
                . "nilai_rendah='$nilai_rendah'
                WHERE id=$id";
	return $db_object->query($sql);
}


function get_jumlah_sum_atribut($db_object, $atribut, $minat){
	$sql = "SELECT SUM($atribut) FROM tbl_dataset WHERE minat='$minat'";
	$res = $db_object->query($sql);
	$row = $db_object->db_fetch_array($res);
	return $row[0];
}

function get_jumlah_atribut($db_object, $atribut, $nilai, $minat){
	$sql = "SELECT COUNT(*) FROM tbl_dataset WHERE $atribut='$nilai' AND minat='$minat'";
	$res = $db_object->query($sql);
	$row = $db_object->db_fetch_array($res);
	return $row[0];
}


function get_s2_populasi($db_object, $atribut, $minat, $x_params, $jml_params){
	$sql = "SELECT $atribut FROM tbl_dataset WHERE minat='$minat'";
	$res = $db_object->query($sql);
	$sum_power = 0;
	while($row = $db_object->db_fetch_array($res)){
		$power = pow($row[0]-$x_params,2);
		$sum_power += $power;
	}
	$s2 = $sum_power/($jml_params-1);
	return $s2;
}

