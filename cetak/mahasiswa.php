<?php
session_start();
include_once "../library/inc.seslogin.php";
include_once "../library/inc.connection.php";
include_once "../library/inc.library.php";
?>
<html>
<head>
<title>:: Laporan Data Mahasiswa</title>
<link href="../styles/styles_cetak.css" rel="stylesheet" type="text/css">
</head>
<body>
<h2>LAPORAN DATA MAHASISWA </h2>
<table class="table-list" width="800" border="0" cellspacing="1" cellpadding="2">
  <tr>
    <td width="27" align="center" bgcolor="#F5F5F5"><strong>No</strong></td>
    <td width="36" bgcolor="#F5F5F5"><strong>NIS</strong></td>
    <td width="162" bgcolor="#F5F5F5"><strong>Nama Mahasiswa </strong></td>
    <td width="62" bgcolor="#F5F5F5"><strong>Kelamin</strong></td>
    <td width="311" bgcolor="#F5F5F5"><strong>Alamat</strong></td>
    <td width="90" bgcolor="#F5F5F5"><strong>No. Telepon </strong></td>
    <td width="76" bgcolor="#F5F5F5"><strong> Status </strong></td>
  </tr>
  <?php
	// Skrip menampilkan data dari database
	$mySql 	= "SELECT * FROM mahasiswa  ORDER BY kd_mahasiswa";
	$myQry 	= mysql_query($mySql, $koneksidb)  or die ("Query salah 1 : ".mysql_error());
	$nomor  = 0; 
	while ($myData = mysql_fetch_array($myQry)) {
		$nomor++;
		$Kode	= $myData['kd_mahasiswa'];
	?>
  <tr>
    <td align="center"><?php echo $nomor; ?></td>
    <td><?php echo $myData['nim']; ?></td>
    <td><?php echo $myData['nm_mahasiswa']; ?></td>
    <td><?php echo $myData['kelamin']; ?></td>
    <td><?php echo $myData['alamat']; ?></td>
    <td><?php echo $myData['no_telepon']; ?></td>
    <td><?php echo $myData['status_aktif']; ?></td>
  </tr>
  <?php } ?>
</table>
<img src="../images/btn_print.png" height="20" onClick="javascript:window.print()" />
</body>
</html>