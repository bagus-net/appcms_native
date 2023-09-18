<?php
// Validasi Login
include_once "library/inc.seslogin.php";
// Koneksi database
include_once "library/inc.connection.php";

// Periksa ada atau tidak variabel Kode pada URL (alamat browser)
if(isset($_GET['Kode'])){
	// Hapus data sesuai Kode yang didapat di URL
	$Kode	= $_GET['Kode'];
	$mySql = "DELETE FROM users WHERE nama='$Kode' AND keterangan !='admin'";
	$myQry = pg_query($koneksidb, $mySql) or die ("Eror hapus data".pg_error());
	if($myQry){
		// Refresh halaman
		echo "<meta http-equiv='refresh' content='0; url=?open=User-Data'>";
	}
}
else {
	// Jika tidak ada data Kode ditemukan di URL
	echo "<b>Data yang dihapus tidak ada</b>";
}
?>