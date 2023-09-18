<?php
// Validasi Login
include_once "library/inc.seslogin.php";
// Koneksi database
include_once "library/inc.connection.php";

// Jika data Kode ditemukan dari URL (alamat browser)
if(isset($_GET['Kode'])){
	$Kode	= $_GET['Kode'];
	// Hapus data sesuai Kode yang terbaca
	$mySql = "DELETE FROM msales WHERE kode='$Kode'";
	$myQry = pg_query($koneksidb, $mySql ) or die ("Eror hapus data".pg_last_error());
	if($myQry){
		// Refresh halaman
		echo "<meta http-equiv='refresh' content='0; url=?open=Penerbit-Data'>";
	}
}
else {
	// Jika tidak ada data Kode ditemukan di URL
	echo "<b>Data yang dihapus tidak ada</b>";
}
?>