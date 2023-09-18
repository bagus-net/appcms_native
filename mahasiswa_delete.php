<?php
include_once "library/inc.seslogin.php";
include_once "library/inc.connection.php";
include_once "library/inc.library.php";

// Membaca data dari URL
$Kode	= $_GET['Kode'];
if(isset($Kode)){
	// Membaca data foto mahasiswa
	$mySql	 = "SELECT * FROM mahasiswa WHERE kd_mahasiswa='$Kode'";
	$myQry	 = mysql_query($mySql, $koneksidb)  or die ("Query baca data salah: ".mysql_error());
	$myData	 = mysql_fetch_array($myQry);
	
	// Menghapus file foto
	if(! $myData['foto']=="") {
		if(file_exists("foto/".$myData['foto'])) {
			unlink("foto/".$myData['foto']);	
		}
	}
			
	// Skrip menghapus data dari tabel database
	$hapusSql = "DELETE FROM mahasiswa WHERE kd_mahasiswa='$Kode'";
	mysql_query($hapusSql, $koneksidb) or die ("Error query".mysql_error());
	
	// Refresh
	echo "<meta http-equiv='refresh' content='0; url=?open=Mahasiswa-Data'>";
}
else {
	echo "Data yang dihapus tidak ada";
}
?>
