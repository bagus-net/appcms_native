<?php
session_start();
include_once "../library/inc.connection.php";
include_once "../library/inc.library.php";

date_default_timezone_set("Asia/Jakarta");
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>TRANSAKSI PEMINJAMAN</title>
<link href="../styles/style.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="../plugins/tigra_calendar/tcal.css" />
<script type="text/javascript" src="../plugins/tigra_calendar/tcal.js"></script> 
<script type="text/javascript" src="../plugins/js.popupWindow.js"></script>
</head>
<body>
<table width="400" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><img src="../images/logocms.png" width="499" height="80"></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><a href="?open=Tinjauan-Baru" target="_self">Tinjauan Baru</a> | <a href="?open=Tinjauan-Tampil" target="_self">Tampil Tinjauan Order </a> </td>
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
		case 'Tinjauan-Baru' :
			if(!file_exists ("tjo_baru.php")) die ("File program tidak ada !"); 
			include "tjo_baru.php";	break;
		case 'Tinjauan-Tampil' : 
			if(!file_exists ("tjo_tampil.php")) die ("File program tidak ada !"); 
			include "tjo_tampil.php";	break;
		case 'Tinjauan-Edit' : 
			if(!file_exists ("tjo_edit.php")) die ("File program tidak ada !"); 
			include "tjo_edit.php";	break;
//			if(!file_exists ("peminjaman_tampil.php")) die ("File program tidak ada !"); 
//			include "peminjaman_tampil.php";	break;
		case 'Peminjaman-Hapus' : 
			if(!file_exists ("peminjaman_hapus.php")) die ("File program tidak ada !"); 
			include "peminjaman_hapus.php";	break;
	}
}
else {
	include "tjo_baru.php";
}
?>
</body>
</html>
