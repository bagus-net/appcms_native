<?php
session_start();
include_once "../library/inc.seslogin.php";
include_once "../library/inc.connection.php";
include_once "../library/inc.library.php";
?>
<html>
<head>
<title>:: Laporan Data User</title>
<link href="../styles/styles_cetak.css" rel="stylesheet" type="text/css">
</head>
<body>
<h2>LAPORAN DATA USER </h2>
<table class="table-list" width="600" border="0" cellspacing="1" cellpadding="2">
  <tr>
    <td width="30" align="center" bgcolor="#F5F5F5"><strong>No</strong></td>
    <td width="51" bgcolor="#F5F5F5"><strong>Nama User</strong></td>
    <td width="338" bgcolor="#F5F5F5"><strong>Keterangan </strong></td>
    <td width="160" bgcolor="#F5F5F5"><strong>Level</strong></td>
  </tr>
  <?php
	// Skrip menampilkan data dari database
	$mySql 	= "SELECT * FROM users ORDER BY nama";
	$myQry 	= pg_query($koneksidb, $mySql)  or die ("Query salah 1 : ".pg_last_error());
	$nomor  = 0; 
	while ($myData = pg_fetch_array($myQry)) {
		$nomor++;
		$Kode	= $myData['nama'];
	?>
  <tr>
    <td align="center"><?php echo $nomor; ?></td>
    <td><?php echo $myData['nama']; ?></td>
    <td><?php echo $myData['keterangan']; ?></td>
    <td><?php echo $myData['level']; ?></td>
  </tr>
  <?php } ?>
</table>
<img src="../images/btn_print.png" height="20" onClick="javascript:window.print()" />
</body>
</html>