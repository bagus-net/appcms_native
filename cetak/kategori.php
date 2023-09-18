<?php
session_start();
include_once "../library/inc.seslogin.php";
include_once "../library/inc.connection.php";
include_once "../library/inc.library.php";
?>
<html>
<head>
<title>:: Laporan Data Kategori</title>
<link href="../styles/styles_cetak.css" rel="stylesheet" type="text/css">
</head>
<body>
<h2>LAPORAN DATA KATEGORI </h2>
<table class="table-list" width="700" border="0" cellspacing="1" cellpadding="2">
  <tr>
    <td width="32" align="center" bgcolor="#F5F5F5"><strong>No</strong></td>
    <td width="567" bgcolor="#F5F5F5"><strong>Nama Kategori </strong></td>
    <td width="85" align="right" bgcolor="#F5F5F5"><strong>Qty Buku </strong></td>
  </tr>
  <?php
	// Skrip menampilkan data dari database
	$mySql 	= "SELECT * FROM kategori ORDER BY nm_kategori";
	$myQry 	= mysql_query($mySql, $koneksidb)  or die ("Query salah 1 : ".mysql_error());
	$nomor  = 0; 
	while ($myData = mysql_fetch_array($myQry)) {
		$nomor++;
		$Kode	= $myData['kd_kategori'];
		
		// Melihat jumlah buku yang tersedia, relasi Kategori -> Kategori 2 -> Buku 
		$my2Sql 	= "SELECT COUNT(*) As jumlah FROM buku WHERE  kd_kategori = '$Kode'";
		$my2Qry	= mysql_query($my2Sql, $koneksidb)  or die ("Query salah 2 : ".mysql_error());
		$my2Data= mysql_fetch_array($my2Qry);
	?>
  <tr>
    <td align="center"><?php echo $nomor; ?></td>
    <td><?php echo $myData['nm_kategori']; ?></td>
    <td align="right"><?php echo $my2Data['jumlah']; ?></td>
  </tr>
  <?php } ?>
</table>
<img src="../images/btn_print.png" height="20" onClick="javascript:window.print()" />
</body>
</html>