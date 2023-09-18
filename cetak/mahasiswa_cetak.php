<?php
session_start();
include_once "../library/inc.seslogin.php";
include_once "../library/inc.connection.php";
include_once "../library/inc.library.php";

# TAMPILKAN DATA LOGIN UNTUK DIEDIT
$Kode	 = $_GET['Kode']; 
$mySql	 = "SELECT * FROM mahasiswa WHERE kd_mahasiswa='$Kode'";
$myQry	 = mysql_query($mySql, $koneksidb)  or die ("Query data salah: ".mysql_error());
$myData	 = mysql_fetch_array($myQry);

// menampilkan gambar utama
if ($myData['foto']=="") {
	$fileGambar = "noimage.jpg";
}
else {
	$fileGambar	= $myData['foto'];
}
?>
<html>
<head>
<title>:: Cetak Data Mahasiswa</title>
<link href="../styles/styles_cetak.css" rel="stylesheet" type="text/css">
</head>
<body>
<h2>DATA MAHASISWA </h2>
<table width="100%" class="table-list" border="0" cellpadding="4" cellspacing="1">
  <tr>
    <td width="231"><strong>Kode</strong></td>
    <td width="3">:</td>
    <td width="1019"><?php echo $myData['kd_mahasiswa']; ?></td>
  </tr>
  <tr>
    <td><strong>NIM Mhs </strong></td>
    <td><b>:</b></td>
    <td> <?php echo $myData['nim']; ?></td>
  </tr>
  <tr>
    <td><strong>Nama Mhs </strong></td>
    <td><b>:</b></td>
    <td><?php echo $myData['nm_mahasiswa']; ?></td>
  </tr>
  <tr>
    <td><strong>Kelamin</strong></td>
    <td>:</td>
    <td><?php echo $myData['kelamin']; ?></td>
  </tr>
  <tr>
    <td><b>Agama</b></td>
    <td><b>:</b></td>
    <td><?php echo $myData['agama']; ?></td>
  </tr>
  <tr>
    <td><b>Tempat, Tgl. Lahir </b></td>
    <td><b>:</b></td>
    <td><?php echo $myData['tempat_lahir'].", ".IndonesiaTgl($myData['tanggal_lahir']); ?></td>
  </tr>
  <tr>
    <td><strong>Alamat Tinggal </strong></td>
    <td><b>:</b></td>
    <td><?php echo $myData['alamat']; ?></td>
  </tr>
  <tr>
    <td><strong>No. Telepon </strong></td>
    <td><b>:</b></td>
    <td><?php echo $myData['no_telepon']; ?></td>
  </tr>
  <tr>
    <td><strong>Tahun Angkatan </strong></td>
    <td><b>:</b></td>
    <td><?php echo $myData['th_angkatan']; ?></td>
  </tr>
  <tr>
    <td><b>Status Aktif </b></td>
    <td><b>:</b></td>
    <td><?php echo $myData['status_aktif']; ?></td>
  </tr>
  <tr>
    <td><b>Status Pinjam </b></td>
    <td><b>:</b></td>
    <td><?php echo $myData['status_pinjam']; ?></td>
  </tr>
  <tr>
    <td align="center"><img src="../foto/<?php echo $fileGambar; ?>" width="112" height="156" border="0"></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
<img src="../images/btn_print.png" height="20" onClick="javascript:window.print()" />
</body>
</html>