<?php

include 'fungsikirim.php';

//mengambil nomor pengirim dari NowSMS
$nopengirim = $_GET['pengirim'];

//membuat nomor pengirim menjadi 08...
if (substr($nopengirim, 0, 3) == "+62") {
    $nopengirim = "0" . substr($nopengirim, 3, strlen($nopengirim) - 3);
} else if (substr($nopengirim, 0, 2) == "62") {
    $nopengirim = "0" . substr($nopengirim, 2, strlen($nopengirim) - 2);
}

//$_GET['pesan'] > mengambil pesan dari NowSMS
//strtoupper > pesan yang diterima dijadikan huruf besar semua       
//trim > menghilangkan spasi di awal dan di akhir pesan
$pesan = strtoupper(trim($_GET['pesan']));

koneksi();
insertInbox($nopengirim, $pesan);

$userid = selectUser($nopengirim);

if ($userid != NULL) {
	koneksi();
	$namauser = selectNamaUser($userid);
	if(substr($pesan, 0, 3)=="TES") {
		if(substr($pesan, 4, 3)=="SMS") {
			$pesanKirim = "Tes+Sms+Berhasil,+".changeSpaceToPlus($namauser);
			trigger($nopengirim, $pesanKirim);
		} else {
			$pesanKirim = "Sms+Berhasil+dikirim,+".changeSpaceToPlus($namauser);
			trigger($nopengirim, $pesanKirim);
		}
	} else {
		$pesanKirim = "Format+SMS+Anda+salah,+".changeSpaceToPlus($namauser);
		trigger($nopengirim, $pesanKirim);
	}	
} else {
	$pesanKirim = "Maaf,+nomor+Anda+tidak+terdaftar+di+Database+kami.";
	trigger($nopengirim, $pesanKirim);
}

?>