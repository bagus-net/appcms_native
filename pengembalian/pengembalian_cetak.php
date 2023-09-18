<?php
include_once "../library/inc.connection.php";
include_once "../library/inc.library.php";

if($_GET) {
	# Baca variabel URL
	$Kode = $_GET['Kode'];
	
	# Perintah untuk mendapatkan data dari tabel peminjaman, pengembalian dan mahasiswa
	$mySql = "SELECT pengembalian.*, peminjaman.tgl_peminjaman, mahasiswa.nim, mahasiswa.nm_mahasiswa FROM pengembalian, peminjaman
			 LEFT JOIN mahasiswa ON peminjaman.kd_mahasiswa = mahasiswa.kd_mahasiswa
			 WHERE peminjaman.no_peminjaman = pengembalian.no_peminjaman AND pengembalian.no_pengembalian='$Kode'";
	$myQry = mysql_query($mySql, $koneksidb)  or die ("Query salah : ".mysql_error());
	$myData = mysql_fetch_array($myQry);
	
	// Mendapatkan Kode Peminjaman
	$kodePinjam	= $myData['no_peminjaman'];
	
	$my2Sql	 = "SELECT peminjaman.*, buku.judul_buku FROM peminjaman 
				LEFT JOIN buku_inventaris ON peminjaman.kd_inventaris = buku_inventaris.kd_inventaris
				LEFT JOIN buku ON buku_inventaris.kd_buku = buku.kd_buku 
				WHERE peminjaman.no_peminjaman = '$kodePinjam'";
	$my2Qry	 = mysql_query($my2Sql, $koneksidb)  or die ("Query data salah: ".mysql_error());
	$my2Data	 = mysql_fetch_array($my2Qry);

}
else {
	echo "Nomor Transaksi Tidak Terbaca";
	exit;
}
?>
<html>
<head>
<title>:: Cetak Pengembalian</title>
<link href="../styles/styles_cetak.css" rel="stylesheet" type="text/css">
</head>
<body>
<h2> PENGEMBALIAN BUKU </h2>
<table width="700" border="0" cellspacing="1" cellpadding="4" class="table-print">
  <tr>
    <td bgcolor="#CCCCCC"><strong>TRANSAKSI</strong></td>
    <td>&nbsp;</td>
    <td valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td width="176"><b>No. Peminjaman </b></td>
    <td width="10"><b>:</b></td>
    <td width="486" valign="top"><strong><?php echo $myData['no_peminjaman']; ?></strong></td>
  </tr>
  <tr>
    <td><b>Tgl. Peminjaman </b></td>
    <td><b>:</b></td>
    <td valign="top"><?php echo IndonesiaTgl($myData['tgl_peminjaman']); ?></td>
  </tr>
  <tr>
    <td><b>Tgl.  Kembali </b></td>
    <td><b>:</b></td>
    <td valign="top"><?php echo IndonesiaTgl($myData['tgl_kembali']); ?></td>
  </tr>
  <tr>
    <td><b>Mahamahasiswa</b></td>
    <td><b>:</b></td>
    <td valign="top"><?php echo $myData['nim']."/ ".$myData['nm_mahasiswa']; ?></td>
  </tr>
  <tr>
    <td><strong>Denda (Rp) </strong></td>
    <td><b>:</b></td>
    <td valign="top"><?php echo format_angka($myData['denda']); ?></td>
  </tr>
  <tr>
    <td><strong>Keterangan</strong></td>
    <td><b>:</b></td>
    <td valign="top"><?php echo $myData['keterangan']; ?></td>
  </tr>
  <tr>
    <td bgcolor="#CCCCCC"><strong>DATA BUKU </strong></td>
    <td>&nbsp;</td>
    <td valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td><strong>Kode</strong></td>
    <td><b>:</b></td>
    <td valign="top"><?php echo $my2Data['kd_inventaris']; ?></td>
  </tr>
  <tr>
    <td><strong>Judul Buku </strong></td>
    <td><b>:</b></td>
    <td valign="top"><?php echo $my2Data['judul_buku']; ?></td>
  </tr>
</table>

<br/>
<img src="../images/btn_print.png" height="20" onClick="javascript:window.print()" />
</body>
</html>