<?php
include_once "../library/inc.connection.php";
include_once "../library/inc.library.php";

if($_GET) {
	# Baca variabel URL
	$noPinjam = $_GET['Kode'];
	
	# Perintah untuk mendapatkan data dari tabel peminjaman
	$mySql = "SELECT morder.* FROM morder 
			  WHERE morder.kode='$noPinjam'";
			  
	$myQry = pg_query($koneksidb, $mySql)  or die ("Query 1 salah : ".pg_last_error());
	$myData = pg_fetch_array($myQry);
	
			$KodeInventaris	=  $myData['kode'];
			
	# Membaca Kode Buku dan Judul
//	$my2Sql = "SELECT judul_buku FROM buku, buku_inventaris 
//				WHERE buku.kd_buku = buku_inventaris.kd_buku AND buku_inventaris.kd_inventaris ='$KodeInventaris'";
//	$my2Qry = mysql_query($my2Sql, $koneksidb)  or die ("Query 2 salah : ".mysql_error());
//	$my2Data = mysql_fetch_array($my2Qry);

}
else {
	echo "Nomor Transaksi Tidak Terbaca";
	exit;
}
?>
<html>
<head>
<title>:: Cetak Tinjauan Order </title>
<link href="../styles/styles_cetak.css" rel="stylesheet" type="text/css">
</head>
<body>
<h2> Tinjauan Order </h2>
<table width="800" border="0" cellspacing="1" cellpadding="4" class="table-print">
  <tr>
    <td bgcolor="#CCCCCC"><strong>TRANSAKSI</strong></td>
    <td>&nbsp;</td>
    <td valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td width="179"><b>No. Tinjauan </b></td>
    <td width="10"><b>:</b></td>
    <td width="583" valign="top"><strong><?php echo $myData['kode']; ?></strong></td>
  </tr>
  <tr>
    <td><b>Tanggal </b></td>
    <td><b>:</b></td>
    <td valign="top"><?php echo IndonesiaTgl($myData['tgl']); ?></td>
  </tr>
<!--
  <tr>
    <td><b>Tgl. Hrs Kembali </b></td>
    <td><b>:</b></td>
    <td valign="top"><?php echo IndonesiaTgl($myData['tgl_harus_kembali']); ?></td>
  </tr>
-->  

  <tr>
    <td><b>Nama Customer</b></td>
    <td><b>:</b></td>
    <td valign="top"><?php echo $myData['kodesupp']."/ ".$myData['nama']; ?></td>
  </tr>
  
<!--
  <tr>
    <td><strong>Status</strong></td>
    <td><b>:</b></td>
    <td valign="top"><?php echo $myData['status_pinjam']; ?></td>
  </tr>
-->  

  <tr>
    <td bgcolor="#CCCCCC"><strong>BUKU</strong></td>
    <td><b>:</b></td>
    <td valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td><strong>Kode</strong></td>
    <td><b>:</b></td>
    <td valign="top"><?php echo $myData['jeniskode']; ?></td>
  </tr>

<!--
  <tr>
    <td><strong>Judul </strong></td>
    <td><b>:</b></td>
    <td valign="top"><?php echo $my2Data['Keterangan']; ?></td>
  </tr>
-->  
  
</table>

<br/>
<img src="../images/btn_print.png" height="20" onClick="javascript:window.print()" />
</body>
</html>