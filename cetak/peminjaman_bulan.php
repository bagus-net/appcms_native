<?php
session_start();
include_once "../library/inc.seslogin.php";
include_once "../library/inc.connection.php";
include_once "../library/inc.library.php";

// Membuat daftar bulan
$listBulan = array("00" => "-", "01" => "Januari", "02" => "Februari", "03" => "Maret",
				 "04" => "April", "05" => "Mei", "06" => "Juni", "07" => "Juli",
				 "08" => "Agustus", "09" => "September", "10" => "Oktober",
				 "11" => "November", "12" => "Desember");

// Membaca data Bulan dan Tahun dari URL
$dataTahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');
$dataBulan = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');

if($dataBulan and $dataTahun) {
	if($dataBulan == "00") {
		// Jika tidak memilih bulan
		$filterSQL = "WHERE LEFT(tgl_peminjaman,4)='$dataTahun'";
	}
	else {
		// Jika memilih bulan dan tahun
		$filterSQL = "WHERE LEFT(tgl_peminjaman,4)='$dataTahun' AND MID(tgl_peminjaman,6,2)='$dataBulan'";
	}
}
else {
	$filterSQL = "";
}
?>
<html>
<head>
<title>:: Laporan Data Peminjaman per Bulan - Program Perpustakaan</title>
<link href="../styles/styles_cetak.css" rel="stylesheet" type="text/css">
</head>
<body>
<h2>LAPORAN DATA PEMINJAMAN PER BULAN</h2>
<table width="800" border="0"  class="table-list">
  <tr>
    <td bgcolor="#F5F5F5"><strong>KETERANGAN</strong></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="134"><strong> Bulan/Tahun </strong></td>
    <td width="15"><strong>:</strong></td>
    <td width="637"><?php echo $listBulan[$dataBulan]; ?> , <?php echo $dataTahun; ?></td>
  </tr>
</table>
<br />
<table class="table-list" width="800" border="0" cellspacing="1" cellpadding="2">
  <tr>
    <td width="23" bgcolor="#F5F5F5"><strong>No</strong></td>
    <td width="86" bgcolor="#F5F5F5"><strong>No. Pinjam </strong></td>
    <td width="86" bgcolor="#F5F5F5"><strong>Tgl. Pinjam </strong></td>
    <td width="121" bgcolor="#F5F5F5"><strong>Tgl. Hrs Kembali </strong></td>
    <td width="304" bgcolor="#F5F5F5"><strong>Mahasiswa</strong></td>
    <td width="78" bgcolor="#F5F5F5"><strong>Kode Buku </strong> </td>
    <td width="66" bgcolor="#F5F5F5"><strong>Status</strong></td>
  </tr>
  <?php
	// Perintah untuk menampilkan peminjaman dengan Filter Bulan
	$mySql = "SELECT peminjaman.*, mahasiswa.nim, mahasiswa.nm_mahasiswa FROM peminjaman 
			LEFT JOIN mahasiswa ON peminjaman.kd_mahasiswa = mahasiswa.kd_mahasiswa
			$filterSQL 
			ORDER BY no_peminjaman DESC";
	$myQry = mysql_query($mySql, $koneksidb)  or die ("Query 1 salah : ".mysql_error());
	$nomor = 0;
	while ($myData = mysql_fetch_array($myQry)) {
		$nomor++;
	?>
  <tr>
    <td><?php echo $nomor; ?></td>
    <td><?php echo $myData['no_peminjaman']; ?></td>
    <td><?php echo IndonesiaTgl($myData['tgl_peminjaman']); ?></td>
    <td><?php echo IndonesiaTgl($myData['tgl_peminjaman']); ?></td>
    <td><?php echo $myData['nim']."/ ".$myData['nm_mahasiswa']; ?></td>
    <td><?php echo $myData['kd_inventaris']; ?></td>
    <td><?php echo $myData['status_pinjam']; ?></td>
  </tr>
  <?php } ?>
</table>
<img src="../images/btn_print.png" height="20" onClick="javascript:window.print()" />
</body>
</html>