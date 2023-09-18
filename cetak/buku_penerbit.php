<?php
session_start();
include_once "../library/inc.seslogin.php";
include_once "../library/inc.connection.php";
include_once "../library/inc.library.php";

# Variabel SQL
$filterSQL	= "";

# Temporary Variabel form
$kodePenerbit	= isset($_GET['kodePenerbit']) ? $_GET['kodePenerbit'] : '';

if($_GET) {
	# PILIH KATEGORI
	if ($kodePenerbit =="Semua") {
		$filterSQL = "";
		$namaPenerbit= "-";
	}
	else {
		$filterSQL = " WHERE kd_penerbit ='$kodePenerbit'";
		
		// Mendapatkan informasi
		$infoSql = "SELECT * FROM penerbit WHERE kd_penerbit='$kodePenerbit'";
		$infoQry = mysql_query($infoSql, $koneksidb);
		$infoData= mysql_fetch_array($infoQry);
		$namaPenerbit= $infoData['nm_penerbit'];
	}
} // End GET
else {
	$penerbitSQL= "";
}
?>
<html>
<head>
<title> :: Laporan Data Buku per Penerbit</title>
<link href="../styles/styles_cetak.css" rel="stylesheet" type="text/css">
</head>
<body>
<h2>LAPORAN DATA BUKU - PER PENERBIT </h2>
<table width="900" border="0"  class="table-list">

<tr>
  <td bgcolor="#F5F5F5"><strong>KETERANGAN</strong></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
</tr>
<tr>
  <td width="134"><b>Nama Penerbit </b></td>
  <td width="15"><b>:</b></td>
  <td width="737"><?php echo $namaPenerbit; ?></td>
</tr>
</table>
  
<table class="table-list" width="900" border="0" cellspacing="1" cellpadding="2">
  <tr>
    <td width="27" bgcolor="#F5F5F5"><strong>No</strong></td>
    <td width="51" bgcolor="#F5F5F5"><strong>Kode</strong></td>
    <td width="447" bgcolor="#F5F5F5"><strong>Judul Buku</strong></td>
    <td width="141" bgcolor="#F5F5F5"><strong>Penulis</strong></td>
    <td width="66" bgcolor="#F5F5F5"><strong>Jumlah</strong></td>
    <td width="56" bgcolor="#F5F5F5"><strong>Halaman</strong></td>
    <td width="76" bgcolor="#F5F5F5"><strong>Th Terbit</strong></td>
  </tr>
  <?php
	// Skrip menampilkan data dari database
	$mySql 	= "SELECT * FROM buku $filterSQL ORDER BY kd_buku ASC";
	$myQry 	= mysql_query($mySql, $koneksidb)  or die ("Query salah : ".mysql_error());
	$nomor  = 0; 
	while ($myData = mysql_fetch_array($myQry)) {
		$nomor++;
	?>
  <tr>
    <td><?php echo $nomor; ?> </td>
    <td><?php echo $myData['kd_buku']; ?> </td>
    <td><?php echo $myData['judul_buku']; ?> </td>
    <td><?php echo $myData['penulis']; ?> </td>
    <td><?php echo $myData['jumlah']; ?> </td>
    <td><?php echo format_angka($myData['jumlah_halaman']); ?></td>
    <td><?php echo $myData['tahun_terbit']; ?></td>
  </tr>
  <?php } ?>
</table>
<img src="../images/btn_print.png" height="20" onClick="javascript:window.print()" />
</body>
</html>