<?php

function koneksi() {
    $host = "localhost";
    $user = "root";
    $pass = "";
    $dbname = "pkl54_monitoringsmsgateway";
    mysql_connect($host, $user, $pass) or die('Connections failed.');
    mysql_select_db($dbname) or die('Database not found.');
}

function insertInbox($pengirim, $pesan) {
    mysql_query("INSERT INTO inbox VALUES('',curdate(),curtime(),'$pengirim','$pesan')");
}
/*
function selectUser($nopengirim) {
	$user = mysql_query("SELECT iduser FROM user_sms WHERE nohp = '$nopengirim'");
	if (!$user) {
		return NULL;
	}
	$hasiluser = mysql_fetch_array($user);
	return $hasiluser[0];
}

function selectNamaUser($iduser) {
	$nama = mysql_query("SELECT nama FROM user_sms WHERE iduser = '$iduser'");
	$hasilnama = mysql_fetch_array($nama);
	return $hasilnama[0];
}
*/

function selectDosen($nopengirim) {
	$user = mysql_query("SELECT iddosen FROM dosen WHERE nohp = '$nopengirim' OR nohpcadangan = '$nopengirim'");
	if (!$user) {
		return NULL;
	} else {
		$hasiluser = mysql_fetch_array($user);
		return $hasiluser[0];
	}
}

function selectKorwil($nopengirim) {
	$user = mysql_query("SELECT nokorwil FROM korwil WHERE nohp = '$nopengirim' OR nohpcadangan = '$nopengirim'");
	if (!$user) {
		return NULL;
	} else {
		$hasiluser = mysql_fetch_array($user);
		return $hasiluser[0];
	}
}

function selectKortim($nopengirim) {
	$user = mysql_query("SELECT nokortim FROM kortim WHERE nohp = '$nopengirim' OR nohpcadangan = '$nopengirim'");
	if (!$user) {
		return NULL;
	} else {
		$hasiluser = mysql_fetch_array($user);
		return $hasiluser[0];
	}
}

function cekListing($kota, $kec, $nbs) {
    $query = mysql_query("SELECT listing FROM nbs 
                WHERE kodekota='$kota' 
                AND kodekecamatan='$kec'  
                AND nbs='$nbs'");
    $queryArray = mysql_fetch_array($query);
    return $queryArray[0];
}

function gantiNomorDosen($nopengirim, $pesan) {
	$pesanArray = explode(" ", $pesan);
	$nomorLama = $pesanArray[1];
	$nomorBaru = $pesanArray[2];

	if ($nomorBaru == "" or strlen($nomorBaru) < 10 OR strlen($nomorBaru) > 14) {
        $pesanError = "Maaf,+format+nomor+hp+baru+yang+akan+diganti+tidak+sesuai.";
        return $pesanError;
    }

    $cek = mysql_query("SELECT iddosen FROM dosen WHERE nohp = '$nomorLama' OR nohpcadangan = '$nomorLama'");
    $cekArray = mysql_fetch_array($cek);
    if ($cekArray[0] == "") {
        $pesanKirim = "Maaf,+nomor+HP+Anda+belum+terdaftar,+silakan+hubungi+CP.";
        return $pesanKirim;
    }

    $iddosen = $cekArray[0];
    $query1 = mysql_query("SELECT nohp FROM dosen WHERE nohp ='$nopengirim' AND iddosen='$iddosen' ");
    $query2 = mysql_query("SELECT nohpcadangan FROM dosen WHERE nohpcadangan ='$nopengirim' AND iddosen='$iddosen'");
    $query1FetchArray = mysql_fetch_array($query1);
    $query2FetchArray = mysql_fetch_array($query2);
    if ($query1FetchArray[0] != "") {
        mysql_query("UPDATE dosen SET nohp = '$nomorBaru' WHERE iddosen='$iddosen'");
    }
    if ($query2FetchArray[0] != "") {
        mysql_query("UPDATE dosen SET nohpcadangan = '$nomorBaru' WHERE iddosen='$iddosen'");
    }

    mysql_query("INSERT INTO rekap_gantinomor VALUES(curdate(),curtime(),'$iddosen','$nomorLama','$nomorBaru')");
    $pesanKirim = "No+HP+Anda+sudah+di-update+menjadi+$nomorBaru.+Terima+Kasih+:)";
    return $pesanKirim;
}

?>