<?php
session_start();
include_once "../library/inc.seslogin.php";
include_once "../library/inc.connection.php";
include_once "../library/inc.library.php";

if($_GET) {
	# Baca variabel URL
	$noPinjam = $_GET['noPinjam'];
	
	# Perintah untuk mendapatkan data dari tabel peminjaman
	$mySql = "SELECT peminjaman.*,  mahasiswa.nim, mahasiswa.nm_mahasiswa FROM peminjaman 
			  LEFT JOIN mahasiswa ON peminjaman.kd_mahasiswa = mahasiswa.kd_mahasiswa
			  WHERE peminjaman.no_peminjaman='$noPinjam'";
	$myQry = mysql_query($mySql, $koneksidb)  or die ("Query salah : ".mysql_error());
	$myData = mysql_fetch_array($myQry);
			$KodeInventaris	=  $myData['kd_inventaris'];
			
	# Membaca Kode Buku dan Judul
	$my2Sql = "SELECT judul_buku FROM buku, buku_inventaris 
				WHERE buku.kd_buku = buku_inventaris.kd_buku AND buku_inventaris.kd_inventaris ='$KodeInventaris'";
	$my2Qry = mysql_query($my2Sql, $koneksidb)  or die ("Query 2 salah : ".mysql_error());
	$my2Data = mysql_fetch_array($my2Qry);
}
else {
	echo "Nomor Transaksi Tidak Terbaca";
	exit;
}
?>
<html>
<head>
<title>:: Cetak Peminjaman </title>
<link href="../styles/styles_cetak.css" rel="stylesheet" type="text/css"></head>
<body>
<h2> PEMINJAMAN BUKU </h2>
<table width="800" border="0" cellspacing="1" cellpadding="4" class="table-print">
  <tr>
    <td bgcolor="#CCCCCC"><strong>TRANSAKSI</strong></td>
    <td>&nbsp;</td>
    <td valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td width="179"><b>No. Peminjaman </b></td>
    <td width="10"><b>:</b></td>
    <td width="583" valign="top"><strong><?php echo $myData['no_peminjaman']; ?></strong></td>
  </tr>
  <tr>
    <td><b>Tgl. Peminjaman </b></td>
    <td><b>:</b></td>
    <td valign="top"><?php echo IndonesiaTgl($myData['tgl_peminjaman']); ?></td>
  </tr>
  <tr>
    <td><b>Tgl. Hrs Kembali </b></td>
    <td><b>:</b></td>
    <td valign="top"><?php echo IndonesiaTgl($myData['tgl_harus_kembali']); ?></td>
  </tr>
  <tr>
    <td><b>Mahasiswa</b></td>
    <td><b>:</b></td>
    <td valign="top"><?php echo $myData['nim']."/ ".$myData['nm_mahasiswa']; ?></td>
  </tr>
  <tr>
    <td><strong>Status</strong></td>
    <td><b>:</b></td>
    <td valign="top"><?php echo $myData['status_pinjam']; ?></td>
  </tr>
  <tr>
    <td bgcolor="#CCCCCC"><strong>BUKU</strong></td>
    <td><b>:</b></td>
    <td valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td><strong>Kode</strong></td>
    <td><b>:</b></td>
    <td valign="top"><?php echo $myData['kd_inventaris']; ?></td>
  </tr>
  <tr>
    <td><strong>Judul </strong></td>
    <td><b>:</b></td>
    <td valign="top"><?php echo $my2Data['judul_buku']; ?></td>
  </tr>
</table>
<br/>
<img src="../images/btn_print.png" height="20" onClick="javascript:window.print()" />
</body>
</html>