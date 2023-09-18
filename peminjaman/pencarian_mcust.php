<?php
session_start();
include_once "../library/inc.seslogin.php";
include_once "../library/inc.connection.php";
include_once "../library/inc.library.php";

// include_once "../library/inc.seslogin.php";
// include_once "../library/inc.connection.php";

//include_once "../library/inc.seslogin.php";

//include_once "../library/inc.library.php";

// Membaca data dari Pencarian

$kataKunci 		= isset($_GET['kataKunci']) ? $_GET['kataKunci'] : '';
$kataKunci 		= isset($_POST['txtKataKunci']) ? $_POST['txtKataKunci'] : $kataKunci;

# TOMBOL CARI DIKLIK
if (isset($_POST['btnCari'])) {
	// Query dan filter pencarian
	$cariSQL 	= " AND ( mcust.nama LIKE '%".$kataKunci."%' OR mcust.kode ='$kataKunci')";
//	$cariSQL 	= "";
}
else {
	$cariSQL 	= "";
}

# UNTUK PAGING (PEMBAGIAN HALAMAN)
$baris 		= 50;
$halaman 	= isset($_GET['hal']) ? $_GET['hal'] : 0;
$pageSql 	= "SELECT * FROM mcust WHERE kode<>'' $cariSQL";

//$pageQry 	= pg_query($pageSql, $koneksidb) or die("Error paging:".mysql_error());

$pageQry 	= pg_query($koneksidb, $pageSql)  or die ("Query  salah : ".pg_last_error());
// $jmlData	= mysql_num_rows($pageQry);
$jmlData	= pg_num_rows($pageQry);
$maksData	= ceil($jmlData/$baris);
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Pencarian Customer</title>
<link href="../styles/style.css" rel="stylesheet" type="text/css"></head>
<body>
<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" name="form1" target="_self">
<h1><b>PENCARIAN Customer </b></h1>

<table width="800" border="0"  class="table-list">
  <tr>
    <td bgcolor="#CCCCCC"><strong>FILTER DATA </strong></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="134"><strong>Cari Kode/ Nama </strong></td>
    <td width="11"><strong>:</strong></td>
    <td width="641"><input name="txtKataKunci" type="text" value="<?php echo $kataKunci; ?>" size="40" maxlength="100" />
        <input name="btnCari" type="submit"  value="Cari" /></td>
  </tr>
</table>
<table class="table-list" width="1200" border="0" cellspacing="1" cellpadding="2">
  <tr>
    <td width="28" bgcolor="#CCCCCC"><strong>No</strong></td>
    <td width="39" bgcolor="#CCCCCC"><strong>Kode</strong></td>
    <td width="363" bgcolor="#CCCCCC"><strong>Nama Customer </strong></td>
    <td width="39" bgcolor="#CCCCCC"><strong>Initial</strong></td>
    <td width="395" bgcolor="#CCCCCC"><strong>Alamat</strong></td>
    <td width="87" bgcolor="#CCCCCC"><strong>Kota  </strong></td>
    <td width="87" bgcolor="#CCCCCC"><strong>Telephone  </strong></td>
    <td width="86" bgcolor="#CCCCCC"><strong>Kontak</strong></td>
    </tr>
  <?php
	// Skrip menampilkan data dari database
//	$mySql 	= "SELECT * FROM mahasiswa WHERE status_pinjam = 'Bebas' $cariSQL ORDER BY kd_mahasiswa ASC LIMIT $halaman, $baris";

//	$pageSql 	= "SELECT * FROM mcust "  
	
// ORDER BY kode ASC LIMIT $halaman, $baris";
//	$myQry 	= mysql_query($mySql, $koneksidb)  or die ("Query salah : ".mysql_error());

//	$myQry 	= pg_query($koneksidb, $mySql)  or die ("Query  salah : ".pg_last_error());

//	$pageQry 	= pg_query($koneksidb, $pageSql)  or die ("Query  salah : ".pg_last_error());

	$mySql = "SELECT * FROM mcust where kode<>'' $cariSQL ORDER BY kode ASC ";  // LIMIT $halaman, $baris";
		
	$myQry = pg_query($koneksidb, $mySql)  or die ("Query salah : ".pg_last_error());


	$nomor  = $halaman; 
	while ($myData = pg_fetch_array($myQry)) {
//	while ($myData = mysql_fetch_array($myQry)) {
		$nomor++;
	?>
  <tr>
    <td><?php echo $nomor; ?> </td>
    <td><a href="#" onClick="window.opener.document.getElementById('txtNim').value = '<?php echo $myData['kode']; ?>';
								window.opener.document.getElementById('txtNamaMhs').value = '<?php echo $myData['nama']; ?>';
								 window.close();"><b><?php echo $myData['kode']; ?></b></a></td>
    <td><?php echo $myData['nama']; ?></td>
    <td><?php echo $myData['lnama']; ?></td>
    <td><?php echo $myData['alamat']; ?></td>
    <td><?php echo $myData['kota']; ?></td>
    <td><?php echo $myData['telp']; ?></td>
    <td><?php echo $myData['kontak']; ?></td>
    </tr>
  <?php } ?>
  <tr class="selKecil">
    <td colspan="4"><strong>Jumlah Data :</strong> <?php echo $jmlData; ?> </td>
    <td colspan="3" align="right"><strong>Halaman ke :</strong>
    <?php
	for ($h = 1; $h <= $maksData; $h++) {
		$list[$h] = $baris * $h - $baris;
		echo " <a href='pencarian_mcust.php?hal=$list[$h]'>$h</a> ";
	}
	?></td>
  </tr>
</table>
</form>
</body>
</html>