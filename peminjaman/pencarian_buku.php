<?php
session_start();
include_once "../library/inc.seslogin.php";
include_once "../library/inc.connection.php";
include_once "../library/inc.library.php";

// Baca variabel URL browser
$kodeKategori = isset($_GET['kodeKategori']) ? $_GET['kodeKategori'] : 'Semua'; 
// Baca variabel dari Form setelah di Post
$kodeKategori = isset($_POST['cmbKategori']) ? $_POST['cmbKategori'] : $kodeKategori;

# FILTER KATEGORI
if ($kodeKategori=="Semua") {
	//Query #1 (semua data)
	$filterSQL 	= "";
}
else {
	//Query #2 (filter)
	$filterSQL 	= " WHERE buku.kd_kategori ='$kodeKategori'";
}


# ================================================================

// Membaca data dari Pencarian
$kataKunci 		= isset($_GET['kataKunci']) ? $_GET['kataKunci'] : '';
$kataKunci 		= isset($_POST['txtKataKunci']) ? $_POST['txtKataKunci'] : $kataKunci;

# TOMBOL CARI DIKLIK
if (isset($_POST['btnCari'])) {
	// Query dan filter pencarian
	$cariSQL 	= " AND ( buku.judul_buku LIKE '%".$kataKunci."%' OR buku.kd_buku ='$kataKunci' OR buku.isbn ='$kataKunci')";
}
else {
	$cariSQL 	= "";
}

# UNTUK PAGING (PEMBAGIAN HALAMAN)
$baris 		= 50;
$halaman 	= isset($_GET['hal']) ? $_GET['hal'] : 0;
$pageSql 	= "SELECT * FROM buku  $filterSQL $cariSQL";
$pageQry 	= mysql_query($pageSql, $koneksidb) or die("Error paging:".mysql_error());
$jmlData	= mysql_num_rows($pageQry);
$maksData	= ceil($jmlData/$baris);
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Pencarian Buku | Perpustakaan Kampus</title>
<link href="../styles/style.css" rel="stylesheet" type="text/css"></head>
<body>
<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" name="form1" target="_self">
<h1><b>PENCARIAN BUKU </b></h1>

<table width="900" border="0"  class="table-list">
  <tr>
    <td bgcolor="#CCCCCC"><strong>FILTER DATA </strong></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong> Kategori </strong></td>
    <td><strong>:</strong></td>
    <td><select name="cmbKategori">
      <option value="Semua">- Semua -</option>
      <?php
	  $bacaSql = "SELECT * FROM kategori ORDER BY kd_kategori";
	  $bacaQry = mysql_query($bacaSql, $koneksidb) or die ("Gagal Query".mysql_error());
	  while ($bacaData = mysql_fetch_array($bacaQry)) {
	  	if ($kodeKategori == $bacaData['kd_kategori']) {
			$cek = " selected";
		} else { $cek=""; }
	  	echo "<option value='$bacaData[kd_kategori]' $cek>$bacaData[nm_kategori]</option>";
	  }
	  ?>
    </select>
        <input name="btnTampil" type="submit" value=" Tampil "/></td>
  </tr>
  <tr>
    <td width="133"><strong>Cari </strong></td>
    <td width="11"><strong>:</strong></td>
    <td width="742"><input name="txtKataKunci" type="text" value="<?php echo $kataKunci; ?>" size="40" maxlength="100" />
        <input name="btnCari" type="submit"  value=" Cari Buku " /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><strong>* Kata Kunci: </strong>Kode/ ISBN/ Judul Buku </td>
  </tr>
</table>
<table class="table-list" width="900" border="0" cellspacing="1" cellpadding="2">
  <tr>
    <td width="27" bgcolor="#CCCCCC"><strong>No</strong></td>
    <td width="36" bgcolor="#CCCCCC">&nbsp;</td>
    <td width="56" bgcolor="#CCCCCC"><strong>Kode</strong></td>
    <td width="416" bgcolor="#CCCCCC"><strong>Judul Buku</strong></td>
    <td width="146" bgcolor="#CCCCCC"><strong>Penulis</strong></td>
    <td width="56" align="right" bgcolor="#CCCCCC"><strong>Jumlah</strong></td>
    <td width="56" align="right" bgcolor="#CCCCCC"><strong>Halaman</strong></td>
    <td width="66" align="right" bgcolor="#CCCCCC"><strong>Th Terbit</strong></td>
  </tr>
  <?php
	// Skrip menampilkan data dari database
	$mySql 	= "SELECT * FROM buku $filterSQL $cariSQL ORDER BY kd_buku ASC LIMIT $halaman, $baris";
	$myQry 	= mysql_query($mySql, $koneksidb)  or die ("Query salah : ".mysql_error());
	$nomor  = $halaman; 
	while ($myData = mysql_fetch_array($myQry)) {
		$nomor++;
		
		// menampilkan gambar utama
		if ($myData['file_gambar']=="") {
			$fileGambar = "noimage.jpg";
		}
		else {
			$fileGambar	= $myData['file_gambar'];
		}
	?>
  <tr>
    <td><?php echo $nomor; ?> </td>
    <td><img src="../cover/<?php echo $fileGambar; ?>" width="28" height="39" border="0" /></td>
    <td><?php echo $myData['kd_buku']; ?></b></a></td>
    <td>
	<?php
	echo "<b>"; 
	echo $myData['judul_buku'];  
	echo "</b> <br> <br>";
	
	// Menampilkan Kode Inventaris Buku yang Tersedia (Tidak dipinjam)
	$Kode	= $myData['kd_buku'];
	$my2Sql	= "SELECT * FROM buku_inventaris WHERE kd_buku='$Kode' AND status='Tersedia'";
	$my2Qry = mysql_query($my2Sql, $koneksidb)  or die ("Query salah : ".mysql_error());
	while ($my2Data = mysql_fetch_array($my2Qry)) {
	?>
		<a href="#" onClick="window.opener.document.getElementById('txtKodeBuku').value = '<?php echo $my2Data['kd_inventaris']; ?>';
								window.opener.document.getElementById('txtJudulBuku').value = '<?php echo $myData['judul_buku']; ?>';
								window.close();"><?php echo $my2Data['kd_inventaris']; ?></a> - 
	<?php } ?> </td>
    <td><?php echo $myData['penulis']; ?> </td>
    <td align="right"><?php echo $myData['jumlah']; ?> </td>
    <td align="right"><?php echo format_angka($myData['jumlah_halaman']); ?></td>
    <td align="right"><?php echo $myData['tahun_terbit']; ?></td>
  </tr>
  <?php } ?>
  <tr class="selKecil">
    <td colspan="4"><strong>Jumlah Data :</strong> <?php echo $jmlData; ?> </td>
    <td colspan="4" align="right"><strong>Halaman ke :</strong>
    <?php
	for ($h = 1; $h <= $maksData; $h++) {
		$list[$h] = $baris * $h - $baris;
		echo " <a href='pencarian_buku.php?hal=$list[$h]&kodeKategori=$kodeKategori'>$h</a> ";
	}
	?></td>
  </tr>
</table>
</form>
</body>
</html>