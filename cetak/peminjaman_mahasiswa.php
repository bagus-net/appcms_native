<?php
session_start();
include_once "../library/inc.seslogin.php";
include_once "../library/inc.connection.php";
include_once "../library/inc.library.php";

# Variabel SQL
$filterSQL	= "";

# Temporary Variabel form
$nim	= isset($_GET['nim']) ? $_GET['nim'] : '';

if($_GET) {
	# PILIH KATEGORI
	if ($nim =="Semua") {
		$filterSQL = "";
		$nama= "-";
	}
	else {		
		// Mendapatkan informasi
		$infoSql = "SELECT * FROM mahasiswa WHERE nim='$nim'";
		$infoQry = mysql_query($infoSql, $koneksidb);
		$infoData= mysql_fetch_array($infoQry);
		
		// Membaca informasi
		$nama	= $infoData['nm_mahasiswa'];
		$kode	= $infoData['kd_mahasiswa'];
		
		// Membuat filter SQL
		$filterSQL = " WHERE peminjaman.kd_mahasiswa ='$kode'";
	}
} // End GET
else {
	$filterSQL= "";
}
?>
<html>
<head>
<title>:: Laporan Data Peminjaman per Mahasiswa - Program Perpustakaan</title>
<link href="../styles/styles_cetak.css" rel="stylesheet" type="text/css">
</head>
<body>
<h2>LAPORAN DATA PEMINJAMAN PER MAHASISWA </h2>
<table width="800" border="0"  class="table-list">
  <tr>
    <td bgcolor="#F5F5F5"><strong>KETERANGAN</strong></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="134"><strong>NIM Mahasiswa</strong></td>
    <td width="15"><strong>:</strong></td>
    <td width="637"><?php echo $nim; ?></td>
  </tr>
  <tr>
    <td><strong>Nama</strong></td>
    <td><strong>:</strong></td>
    <td><?php echo $nama; ?></td>
  </tr>
</table>
<br />
<table class="table-list" width="800" border="0" cellspacing="1" cellpadding="2">
  <tr>
    <td width="23" bgcolor="#F5F5F5"><strong>No</strong></td>
    <td width="86" bgcolor="#F5F5F5"><strong>No. Pinjam </strong></td>
    <td width="86" bgcolor="#F5F5F5"><strong>Tgl. Pinjam </strong></td>
    <td width="121" bgcolor="#F5F5F5"><strong>Tgl. Hrs Kembali </strong></td>
    <td width="304" bgcolor="#F5F5F5"><strong>Mahasiswa</strong></td>
    <td width="78" bgcolor="#F5F5F5"><strong>Kode Buku </strong> </td>
    <td width="66" bgcolor="#F5F5F5"><strong>Status</strong></td>
  </tr>
  <?php
	// Perintah untuk menampilkan peminjaman dengan Filter Bulan
	$mySql = "SELECT peminjaman.*, mahasiswa.nim, mahasiswa.nm_mahasiswa FROM peminjaman 
			LEFT JOIN mahasiswa ON peminjaman.kd_mahasiswa = mahasiswa.kd_mahasiswa
			$filterSQL 
			ORDER BY no_peminjaman DESC";
	$myQry = mysql_query($mySql, $koneksidb)  or die ("Query 1 salah : ".mysql_error());
	$nomor = 0;
	while ($myData = mysql_fetch_array($myQry)) {
		$nomor++;
	?>
  <tr>
    <td><?php echo $nomor; ?></td>
    <td><?php echo $myData['no_peminjaman']; ?></td>
    <td><?php echo IndonesiaTgl($myData['tgl_peminjaman']); ?></td>
    <td><?php echo IndonesiaTgl($myData['tgl_peminjaman']); ?></td>
    <td><?php echo $myData['nim']."/ ".$myData['nm_mahasiswa']; ?></td>
    <td><?php echo $myData['kd_inventaris']; ?></td>
    <td><?php echo $myData['status_pinjam']; ?></td>
  </tr>
  <?php } ?>
</table>
<img src="../images/btn_print.png" height="20" onClick="javascript:window.print()" />
</body>
</html>