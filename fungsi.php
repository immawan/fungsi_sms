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

?>