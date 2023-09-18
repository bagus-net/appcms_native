<?php
session_start();
include_once "../library/inc.seslogin.php";
include_once "../library/inc.connection.php";
include_once "../library/inc.library.php";

// Membaca data dari Pencarian
$kataKunci 		= isset($_GET['kataKunci']) ? $_GET['kataKunci'] : '';
$kataKunci 		= isset($_POST['txtKataKunci']) ? $_POST['txtKataKunci'] : $kataKunci;

# TOMBOL CARI DIKLIK
if (isset($_POST['btnCari'])) {
	// Query dan filter pencarian
	$cariSQL 	= " AND ( mahasiswa.nm_mahasiswa LIKE '%".$kataKunci."%' OR mahasiswa.kd_mahasiswa ='$kataKunci')";
}
else {
	$cariSQL 	= "";
}

# UNTUK PAGING (PEMBAGIAN HALAMAN)
$baris 		= 50;
$halaman 	= isset($_GET['hal']) ? $_GET['hal'] : 0;
$pageSql 	= "SELECT * FROM mahasiswa WHERE status_pinjam = 'Bebas'  $cariSQL";
$pageQry 	= mysql_query($pageSql, $koneksidb) or die("Error paging:".mysql_error());
$jmlData	= mysql_num_rows($pageQry);
$maksData	= ceil($jmlData/$baris);
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Pencarian Mahasiswa</title>
<link href="../styles/style.css" rel="stylesheet" type="text/css"></head>
<body>
<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" name="form1" target="_self">
<h1><b>PENCARIAN MAHASISWA </b></h1>

<table width="800" border="0"  class="table-list">
  <tr>
    <td bgcolor="#CCCCCC"><strong>FILTER DATA </strong></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="134"><strong>Cari NIM/ Nama </strong></td>
    <td width="11"><strong>:</strong></td>
    <td width="641"><input name="txtKataKunci" type="text" value="<?php echo $kataKunci; ?>" size="40" maxlength="100" />
        <input name="btnCari" type="submit"  value="Cari" /></td>
  </tr>
</table>
<table class="table-list" width="800" border="0" cellspacing="1" cellpadding="2">
  <tr>
    <td width="28" bgcolor="#CCCCCC"><strong>No</strong></td>
    <td width="66" bgcolor="#CCCCCC"><strong>NIM</strong></td>
    <td width="363" bgcolor="#CCCCCC"><strong>Nama Mahasiswa </strong></td>
    <td width="39" bgcolor="#CCCCCC"><strong>L/ P</strong></td>
    <td width="95" bgcolor="#CCCCCC"><strong>Th. Angkatan</strong></td>
    <td width="87" bgcolor="#CCCCCC"><strong>Stts Aktif  </strong></td>
    <td width="86" bgcolor="#CCCCCC"><strong>Stts Pinjam</strong></td>
    </tr>
  <?php
	// Skrip menampilkan data dari database
	$mySql 	= "SELECT * FROM mahasiswa WHERE status_pinjam = 'Bebas' $cariSQL ORDER BY kd_mahasiswa ASC LIMIT $halaman, $baris";
	$myQry 	= mysql_query($mySql, $koneksidb)  or die ("Query salah : ".mysql_error());
	$nomor  = $halaman; 
	while ($myData = mysql_fetch_array($myQry)) {
		$nomor++;
	?>
  <tr>
    <td><?php echo $nomor; ?> </td>
    <td><a href="#" onClick="window.opener.document.getElementById('txtNim').value = '<?php echo $myData['nim']; ?>';
								window.opener.document.getElementById('txtNamaMhs').value = '<?php echo $myData['nm_mahasiswa']; ?>';
								 window.close();"><b><?php echo $myData['nim']; ?></b></a></td>
    <td><?php echo $myData['nm_mahasiswa']; ?></td>
    <td><?php echo $myData['kelamin']; ?></td>
    <td><?php echo $myData['th_angkatan']; ?></td>
    <td><?php echo $myData['status_aktif']; ?></td>
    <td><?php echo $myData['status_pinjam']; ?></td>
    </tr>
  <?php } ?>
  <tr class="selKecil">
    <td colspan="4"><strong>Jumlah Data :</strong> <?php echo $jmlData; ?> </td>
    <td colspan="3" align="right"><strong>Halaman ke :</strong>
    <?php
	for ($h = 1; $h <= $maksData; $h++) {
		$list[$h] = $baris * $h - $baris;
		echo " <a href='pencarian_mahasiswa.php?hal=$list[$h]'>$h</a> ";
	}
	?></td>
  </tr>
</table>
</form>
</body>
</html>