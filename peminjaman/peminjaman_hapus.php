<?php
include_once "../library/inc.seslogin.php";

// Periksa ada atau tidak variabel Kode pada URL (alamat browser)
if(isset($_GET['Kode'])){
	$Kode	= $_GET['Kode'];
	
	// ========================================================
	// Membaca Kode Siswa
	$mySql = "SELECT * FROM peminjaman WHERE no_peminjaman='$Kode'";
	$myQry = mysql_query($mySql, $koneksidb) or die ("Error SQl baca data".mysql_error());
	$myData= mysql_fetch_array($myQry);
		$kodeMhs	= $myData['kd_mahasiswa'];
		$kodeBuku	= $myData['kd_inventaris'];
	
	// Update status Pinjam Siswa (Bebas artinya Tidak Sedang Pinjam)
	$editSql = "UPDATE mahasiswa SET status_pinjam ='Bebas' WHERE kd_mahasiswa='$kodeMhs'";
	mysql_query($editSql, $koneksidb) or die ("Gagal Query edit Siswa ".mysql_error());
	// ========================================================
	
	// Update status buku (Tersedia artinya Tidak Dipinjam)
	$edit2Sql = "UPDATE buku_inventaris SET status ='Tersedia' WHERE kd_inventaris='$kodeBuku'";
	mysql_query($edit2Sql, $koneksidb) or die ("Gagal Query  ".mysql_error());
	
	// Hapus Transaksi Peminjaman
	$hapusSql = "DELETE FROM  peminjaman WHERE no_peminjaman='$Kode'";
	mysql_query($hapusSql, $koneksidb) or die ("Gagal Query ".mysql_error());

	
	// Refresh halaman
	echo "<meta http-equiv='refresh' content='0; url=?open=Peminjaman-Tampil'>";
}
else {
	// Jika tidak ada data Kode ditemukan di URL
	echo "<b>Data yang dihapus tidak ada</b>";
}
?>