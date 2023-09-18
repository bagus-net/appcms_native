<?php
session_start();
include_once "../library/inc.ses_petugas.php";
include_once "../library/inc.connection.php";
include_once "../library/inc.library.php";
?>
<html>
<head>
<title>:: Laporan Data Jenis</title>
<link href="../styles/styles_cetak.css" rel="stylesheet" type="text/css">
</head>
<body>
<h2>LAPORAN DATA JENIS </h2>
<table class="table-list"  width="800" border="0" cellspacing="1" cellpadding="3">
  <tr>
    <td width="4%" bgcolor="#F5F5F5"><strong>No</strong></td>
    <td width="7%" bgcolor="#F5F5F5"><strong>Kode</strong></td>
    <td width="45%" bgcolor="#F5F5F5"><strong>Nama Jenis </strong></td>
    <td width="10%" bgcolor="#F5F5F5"><strong>Lama Pjm </strong></td>
    <td width="17%" align="right" bgcolor="#F5F5F5"><strong> D. Hilang (Rp) </strong></td>
    <td width="17%" align="right" bgcolor="#F5F5F5"><strong> D. Terlambat (Rp) </strong></td>
  </tr>
  <?php
	// Skrip menampilkan data dari database
	$mySql = "SELECT * FROM jenis ORDER BY kd_jenis ASC";
	$myQry = mysql_query($mySql, $koneksidb)  or die ("Query salah : ".mysql_error());
	$nomor = 0; 
	while ($myData = mysql_fetch_array($myQry)) {
		$nomor++;
		$Kode = $myData['kd_jenis'];
	?>
  <tr>
    <td><?php echo $nomor; ?></td>
    <td><?php echo $myData['kd_jenis']; ?></td>
    <td><?php echo $myData['nm_jenis']; ?></td>
    <td align="right"><?php echo $myData['lama_pinjam']; ?></td>
    <td align="right"><?php echo format_angka($myData['denda_hilang']); ?></td>
    <td align="right"><?php echo format_angka($myData['denda_terlambat']); ?></td>
  </tr>
  <?php } ?>
</table>
<img src="../images/btn_print.png" height="20" onClick="javascript:window.print()" />
</body>
</html>