<?php
session_start();
include_once "../library/inc.seslogin.php";
include_once "../library/inc.connection.php";
include_once "../library/inc.library.php";

# TAMPILKAN DATA LOGIN UNTUK DIEDIT
$Kode	 = $_GET['Kode']; 
$mySql	 = "SELECT buku.*, penerbit.nm_penerbit, kategori.nm_kategori FROM buku 
			LEFT JOIN penerbit ON buku.kd_penerbit = penerbit.kd_penerbit
			LEFT JOIN kategori ON buku.kd_kategori = kategori.kd_kategori
			WHERE buku.kd_buku='$Kode'";
$myQry	 = mysql_query($mySql, $koneksidb)  or die ("Query data salah: ".mysql_error());
$myData	 = mysql_fetch_array($myQry);

// menampilkan gambar utama
if ($myData['file_gambar']=="") {
	$fileGambar = "noimage.jpg";
}
else {
	$fileGambar	= $myData['file_gambar'];
}
?>
<html>
<head>
<title>:: Cetak Data Buku</title>
<link href="../styles/styles_cetak.css" rel="stylesheet" type="text/css">
</head>
<body>
<h2>DATA BUKU </h2>
<table width="100%" class="table-list" border="0" cellpadding="4" cellspacing="1">
  <tr>
    <td width="181"><strong>Kode</strong></td>
    <td width="3">:</td>
    <td width="1019"><?php echo $myData['kd_buku']; ?></td>
  </tr>
  <tr>
    <td><b>Judul Buku </b></td>
    <td><b>:</b></td>
    <td> <?php echo $myData['judul_buku']; ?></td>
  </tr>
  <tr>
    <td><strong>ISBN</strong></td>
    <td><b>:</b></td>
    <td><?php echo $myData['isbn']; ?></td>
  </tr>
  <tr>
    <td><strong>Penulis</strong></td>
    <td>:</td>
    <td><?php echo $myData['penulis']; ?></td>
  </tr>
  <tr>
    <td><b>Penerbit</b></td>
    <td><b>:</b></td>
    <td><?php echo $myData['nm_penerbit']; ?></td>
  </tr>
  <tr>
    <td><b>Tahun Terbit </b></td>
    <td><b>:</b></td>
    <td><?php echo $myData['tahun_terbit']; ?></td>
  </tr>
  <tr>
    <td><b>Jumlah Halaman </b></td>
    <td><b>:</b></td>
    <td><?php echo $myData['jumlah_halaman']; ?></td>
  </tr>
  <tr>
    <td><strong>Bonus</strong></td>
    <td><b>:</b></td>
    <td><?php echo $myData['bonus']; ?></td>
  </tr>
  <tr>
    <td><strong>Bahasa</strong></td>
    <td><b>:</b></td>
    <td><?php echo $myData['bahasa']; ?></td>
  </tr>
  <tr>
    <td><b>File Gambar </b></td>
    <td><b>:</b></td>
    <td><?php echo $myData['file_gambar']; ?></td>
  </tr>
  <tr>
    <td><b>Lokasi Rak </b></td>
    <td><b>:</b></td>
    <td><?php echo $myData['lokasi_rak']; ?></td>
  </tr>
  <tr>
    <td><b>Kategori</b></td>
    <td><b>:</b></td>
    <td><?php echo $myData['nm_kategori']; ?></td>
  </tr>
  <tr>
    <td><b>Jumlah</b></td>
    <td><b>:</b></td>
    <td><?php echo $myData['jumlah']; ?></td>
  </tr>
  <tr>
    <td><strong>Koleksi (Invnetaris) </strong></td>
    <td><b>:</b></td>
    <td>
	<table class="table-list"  width="400" border="0" cellspacing="1" cellpadding="3">
      <tr>
        <th width="52%" bgcolor="#CCCCCC"><strong>Kode Koleksi </strong></th>
        <th width="29%" bgcolor="#CCCCCC">Tgl. Masuk </th>
        <th width="19%" bgcolor="#CCCCCC"><strong>Status</strong></th>
      </tr>
      <?php
	// Skrip menampilkan data dari database
	$kodeBuku= $myData['kd_buku'];
	$my2Sql = "SELECT * FROM buku_inventaris WHERE kd_buku='$kodeBuku' ORDER BY kd_inventaris ASC";
	$my2Qry = mysql_query($my2Sql, $koneksidb)  or die ("Query salah : ".mysql_error());
	$nomor = 0; 
	while ($my2Data = mysql_fetch_array($my2Qry)) {
		$nomor++;
		// gradasi warna
		if($nomor%2==1) { $warna=""; } else {$warna="#F5F5F5";}
	?>
      <tr bgcolor="<?php echo $warna; ?>">
        <td><?php echo $my2Data['kd_inventaris']; ?></td>
        <td><?php echo IndonesiaTgl($my2Data['tanggal_masuk']); ?></td>
        <td><?php echo $my2Data['status']; ?></td>
      </tr>
      <?php } ?>
    </table></td>
  </tr>
  <tr>
    <td align="center"><img src="../cover/<?php echo $fileGambar; ?>" width="112" height="156" border="0"></td>
    <td>&nbsp;</td>
    <td><?php echo $myData['sinopsis']; ?></td>
  </tr>
</table>
<img src="../images/btn_print.png" height="20" onClick="javascript:window.print()" />
</body>
</html>