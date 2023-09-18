<?php
session_start();
include_once "../library/inc.connection.php";
include_once "../library/inc.library.php";

date_default_timezone_set("Asia/Jakarta");
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>TRANSAKSI PENGEMBALIAN</title>
<link href="../styles/style.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="../plugins/tigra_calendar/tcal.css" />
<script type="text/javascript" src="../plugins/tigra_calendar/tcal.js"></script> 
<script type="text/javascript" src="../plugins/js.popupWindow.js"></script>
</head>
<body>
<table width="400" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><img src="../images/logo.png" width="499" height="80"></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><a href="?open=Peminjaman-Tampil" target="_self">Tampil Peminjaman</a> | <a href="?open=Pengembalian-Tampil" target="_self">Tampil Pengembalian</a> </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>


<?php 
# KONTROL MENU PROGRAM
if(isset($_GET['open'])) {
	// Jika mendapatkan variabel URL ?open
	switch($_GET['open']){				
		case 'Peminjaman-Kembali' :
			if(!file_exists ("peminjaman_kembali.php")) die ("File tidak ditemukan!"); 
			include "peminjaman_kembali.php";	break;
			
		case 'Peminjaman-Tampil' : 
			if(!file_exists ("peminjaman_tampil.php")) die ("File tidak ditemukan!"); 
			include "peminjaman_tampil.php";	break;

		case 'Pengembalian-Tampil' : 
			if(!file_exists ("pengembalian_tampil.php")) die ("File tidak ditemukan!"); 
			include "pengembalian_tampil.php";	break;
			
		case 'Pengembalian-Hapus' : 
			if(!file_exists ("pengembalian_hapus.php")) die ("File tidak ditemukan!"); 
			include "pengembalian_hapus.php";	break;
	}
}
else {
	include "peminjaman_tampil.php";
}
?>
</body>
</html>
