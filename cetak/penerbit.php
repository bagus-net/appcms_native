<?php
session_start();
include_once "../library/inc.seslogin.php";
include_once "../library/inc.connection.php";
include_once "../library/inc.library.php";
?>
<html>
<head>
<title>:: Laporan Data Sales</title>
<link href="../styles/styles_cetak.css" rel="stylesheet" type="text/css">
</head>
<body>
<h2>LAPORAN DATA SALES </h2>
<table class="table-list" width="800" border="0" cellspacing="1" cellpadding="2">
  <tr>
    <td width="30" align="center" bgcolor="#F5F5F5"><strong>No</strong></td>
    <td width="66" bgcolor="#F5F5F5"><strong>Kode</strong></td>
    <td width="567" bgcolor="#F5F5F5"><strong>Nama Sales </strong></td>
    <td width="116" align="right" bgcolor="#F5F5F5"><strong>Jumlah Customer </strong></td>
  </tr>
  <?php
	// Skrip menampilkan data Penerbit dari database
	$mySql 	= "SELECT * FROM msales ORDER BY kode";
	$myQry 	= pg_query($koneksidb, $mySql)  or die ("Query salah 1 : ".pg_last_error());
	$nomor  = 0; 
	while ($myData = pg_fetch_array($myQry)) {
		$nomor++;
		$Kode	= $myData['kode'];
		
		// Melihat jumlah buku yang tersedia 
		$my2Sql 	= "SELECT COUNT(*) As jumlah FROM mcust WHERE kodesls = '$Kode'";
		$my2Qry	= pg_query($koneksidb, $my2Sql)  or die ("Query salah 2 : ".pg_last_error());
		$my2Data= pg_fetch_array($my2Qry);
	?>
  <tr>
    <td align="center"><?php echo $nomor; ?></td>
    <td><?php echo $myData['kode']; ?></td>
    <td><?php echo $myData['nama']; ?></td>
    <td align="right"><?php echo format_angka($my2Data['jumlah']); ?></td>
  </tr>
  <?php } ?>
</table>
<img src="../images/btn_print.png" height="20" onClick="javascript:window.print()" />
</body>
</html>