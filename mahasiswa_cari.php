<?php
include_once "library/inc.seslogin.php"; // Hak akses Petugas=Yes, Admin = Yes
include_once "library/inc.library.php";

# ====================================================
// Membaca data dari Pencarian
$kataKunci 		= isset($_GET['kataKunci']) ? $_GET['kataKunci'] : '';
$kataKunci 		= isset($_POST['txtKataKunci']) ? $_POST['txtKataKunci'] : $kataKunci;

# TOMBOL CARI DIKLIK
if (isset($_POST['btnCari'])) {
	// Query dan filter pencarian
	$cariSQL 	= " AND ( mahasiswa.nm_mahasiswa LIKE '%".$kataKunci."%' OR mahasiswa.kd_mahasiswa ='$kataKunci' OR mahasiswa.nim ='$kataKunci')";
}
else {
	$cariSQL 	= "";
}

# UNTUK PAGING (PEMBAGIAN HALAMAN)
$baris 		= 50;
$halaman 	= isset($_GET['hal']) ? $_GET['hal'] : 0;
$pageSql 	= "SELECT * FROM mahasiswa WHERE status_aktif='Aktif'  $cariSQL";
$pageQry 	= mysql_query($pageSql, $koneksidb) or die("Error paging:".mysql_error());
$jmlData	= mysql_num_rows($pageQry);
$maksData	= ceil($jmlData/$baris);
?>
<body>
<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" name="form1" target="_self">
<h1><b>DATA MAHASISWA </b></h1>

<table width="800" border="0"  class="table-list">
  <tr>
    <td bgcolor="#CCCCCC"><strong>PENCARIAN</strong></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="109"><strong>Nama &amp; NIM </strong></td>
    <td width="11"><strong>:</strong></td>
    <td width="666"><input name="txtKataKunci" type="text" value="<?php echo $kataKunci; ?>" size="40" maxlength="100" />
        <input name="btnCari" type="submit"  value="Cari" /></td>
  </tr>
</table>
<table class="table-list" width="800" border="0" cellspacing="1" cellpadding="2">
  <tr>
    <td width="26" bgcolor="#CCCCCC"><strong>No</strong></td>
    <td width="4%" bgcolor="#CCCCCC">&nbsp;</td>
    <td width="62" bgcolor="#CCCCCC"><strong>NIM</strong></td>
    <td width="251" bgcolor="#CCCCCC"><strong>Nama Siswa </strong></td>
    <td width="32" bgcolor="#CCCCCC"><strong>L/P</strong></td>
    <td width="106" bgcolor="#CCCCCC"><strong>Th. Angkatan</strong></td>
    <td width="95" bgcolor="#CCCCCC"><strong>Stts Siswa  </strong></td>
    <td width="106" bgcolor="#CCCCCC"><strong>Stts Pinjam</strong></td>
    <td width="81" align="center" bgcolor="#CCCCCC"><strong>Tools</strong></td>
  </tr>
  <?php
	// Skrip menampilkan data dari database
	$mySql 	= "SELECT * FROM mahasiswa WHERE status_aktif='Aktif' 
				$cariSQL ORDER BY kd_mahasiswa ASC LIMIT $halaman, $baris";
	$myQry 	= mysql_query($mySql, $koneksidb)  or die ("Query salah : ".mysql_error());
	$nomor  = $halaman; 
	while ($myData = mysql_fetch_array($myQry)) {
		$nomor++;
		$Kode	= $myData['kd_mahasiswa'];
			
			// menampilkan gambar utama
			if ($myData['foto']=="") {
				$fileGambar = "noimage.jpg";
			}
			else {
				$fileGambar	= $myData['foto'];
			}
		
	?>
  <tr>
    <td><?php echo $nomor; ?> </td>
    <td ><img src="foto/<?php echo $fileGambar; ?>" width="28" height="39" border="0" /></td>
    <td> <?php echo $myData['nim']; ?> </td>
    <td><?php echo $myData['nm_mahasiswa']; ?></td>
    <td><?php echo $myData['kelamin']; ?></td>
    <td><?php echo $myData['th_angkatan']; ?></td>
    <td><?php echo $myData['status_aktif']; ?></td>
    <td><?php echo $myData['status_pinjam']; ?></td>
    <td align="center"><a href="cetak_kartu.php?Kode=<?php echo $Kode; ?>" target="_blank">Cetak Kartu</a></td>
  </tr>
  <?php } ?>
  <tr class="selKecil">
    <td colspan="5"><strong>Jumlah Data :</strong> <?php echo $jmlData; ?> </td>
    <td colspan="4" align="right"><strong>Halaman ke :</strong>
    <?php
	for ($h = 1; $h <= $maksData; $h++) {
		$list[$h] = $baris * $h - $baris;
		echo " <a href='?open=Mahasiswa-Cari&hal=$list[$h]'> $h</a> ";
	}
	?></td>
  </tr>
</table>
</form>
