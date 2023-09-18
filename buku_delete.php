<?php
include_once "library/inc.seslogin.php";
include_once "library/inc.connection.php";
include_once "library/inc.library.php";

// Membaca data dari URL
$Kode	= $_GET['Kode'];
if(isset($Kode)){
	// Skrip Menghapus Foto/Gambar Siswa
	$mySql = "SELECT * FROM buku WHERE kd_buku='$Kode'";
	$myQry = mysql_query($mySql, $koneksidb)  or die ("Query salah : ".mysql_error());
	$myData= mysql_fetch_array($myQry);
	
	$fileGambar	= $myData['file_gambar'];
	if(! $fileGambar =="") {
		if(file_exists("cover/$fileGambar")) {
			// Jika file gambarnya ada, akan dihapus
			unlink("cover/$fileGambar"); 
		}
	}
	// Skrip menghapus data dari tabel database
	$mySql = "DELETE FROM buku WHERE kd_buku='$Kode'";
	mysql_query($mySql, $koneksidb) or die ("Error query delete 1".mysql_error());
	
	// Skrip menghapus data dari tabel database
	$mySql = "DELETE FROM buku_inventaris WHERE kd_buku='$Kode'";
	mysql_query($mySql, $koneksidb) or die ("Error query delete 2".mysql_error());
	
	// Refresh
	echo "<meta http-equiv='refresh' content='0; url=?open=Buku-Data'>";
}
else {
	echo "Data yang dihapus tidak ada";
}
?>
