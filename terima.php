<?php

include 'fungsikirim.php';

//mengambil nomor pengirim dari NowSMS
$nopengirim = $_GET['pengirim'];

//membuat nomor pengirim menjadi 08...
if (substr($nopengirim, 0, 3) == "+62") {
    $nopengirim = "0" . substr($nopengirim, 3, strlen($nopengirim) - 3);
} 
if (substr($nopengirim, 0, 2) == "62") {
    $nopengirim = "0" . substr($nopengirim, 2, strlen($nopengirim) - 2);
}

//$_GET['pesan'] > mengambil pesan dari NowSMS
//strtoupper > pesan yang diterima dijadikan huruf besar semua       
//trim > menghilangkan spasi di awal dan di akhir pesan
$pesan = strtoupper(trim($_GET['pesan']));

koneksi();
insertInbox($nopengirim, $pesan);
$idkortim = selectKortim($nopengirim);

//mengetahui apakah yang mengirim ini dosen, korwil, atau kortim
if($idkortim!=NULL) {
	$idpengirim = $idkortim;
} 

if($idpengirim==NULL) {
	$pesanKirim = "Maaf,+nomor+Anda+tidak+terdaftar+dalam+database+kami.+Silahkan+hubungi+admin.";
	trigger($nopengirim, $pesan);
}

if (substr($pesan, 0, 1) == "G") { //untuk ganti nomor (baru bisa buat dosen)
	$pesanKirim = gantiNomorDosen($nopengirim, $pesan);
	trigger($nopengirim, $pesanKirim);
} elseif (substr($pesan, 0, 1) == "P") { //untuk problem
	$isiProblem = substr($_GET['pesan'], 2);
	mysql_query("INSERT INTO problem VALUES('',curdate(),curtime(),'$idpengirim','$isiProblem',tanggapan)");
	$pesanKirim = "Kendala+berhasil+dilaporkan.+Terima+kasih.";
	trigger($nopengirim, $pesanKirim);
} elseif (substr($pesan, 0, 2) == "EL") { //untuk entry listing
	if (substr($idpengirim, 0, 2) !== "KT") { //hanya bisa dilakukan oleh Kortim
		$pesanKirim = "Maaf,+Anda+tidak+memiliki+akses+untuk+entry+listing.";
		trigger($nopengirim, $pesanKirim);
	} else {
		#code...
	}
} else {
	$pesanKirim = "Maaf,+format+sms+Anda+tidak+dikenali.";
	trigger($nopengirim, $pesanKirim);
}

?>