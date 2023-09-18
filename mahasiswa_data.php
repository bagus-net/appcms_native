<?php
include_once "library/inc.seslogin.php";
include_once "library/inc.connection.php";
include_once "library/inc.library.php";

# ====================================================
// Membaca data dari Pencarian
$kataKunci 		= isset($_GET['kataKunci']) ? $_GET['kataKunci'] : '';
$kataKunci 		= isset($_POST['txtKataKunci']) ? $_POST['txtKataKunci'] : $kataKunci;

# TOMBOL CARI DIKLIK
if (isset($_POST['btnCari'])) {
	// Query dan filter pencarian
	$cariSQL 	= " AND ( mahasiswa.nm_mahasiswa LIKE '%".$kataKunci."%' OR mahasiswa.nim ='$kataKunci')";
}
else {
	$cariSQL 	= "";
}

# UNTUK PAGING (PEMBAGIAN HALAMAN)
$baris 		= 50;
$halaman 	= isset($_GET['hal']) ? $_GET['hal'] : 0;
$pageSql 	= "SELECT * FROM mahasiswa WHERE ( status_aktif='Aktif' OR status_aktif='Alumni' ) $cariSQL";
$pageQry 	= mysql_query($pageSql, $koneksidb) or die("Error paging:".mysql_error());
$jmlData	= mysql_num_rows($pageQry);
$maksData	= ceil($jmlData/$baris);
?>
<h2> MANAJEMEN DATA MAHASISWA </h2>
<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" name="form1" target="_self">
  <table width="800" border="0"  class="table-list">
    <tr>
      <td width="137" bgcolor="#CCCCCC"><strong>FILTER DATA </strong></td>
      <td width="10">&nbsp;</td>
      <td width="639">&nbsp;</td>
    </tr>
    <tr>
      <td><strong>Nama &amp; NIS </strong></td>
      <td><strong>:</strong></td>
      <td><input name="txtKataKunci" type="text" value="<?php echo $kataKunci; ?>" size="40" maxlength="100" />
          <input name="btnCari" type="submit"  value="Cari" /></td>
    </tr>
  </table>
</form>
 
<table width="800" border="0" cellspacing="1" cellpadding="3" class="table-border">
  <tr>
    <td>&nbsp;</td>
    <td align="right"><a href="?open=Mahasiswa-Add" target="_self"><img src="images/btn_add_data.png" height="30" border="0" /></a> </td>
  </tr>
  <tr>
    <td colspan="2"><table class="table-list"  width="800" border="0" cellspacing="1" cellpadding="3">
      <tr>
        <th width="3%" bgcolor="#CCCCCC">No</th>
        <th width="4%" bgcolor="#CCCCCC">&nbsp;</th>
        <th width="5%" bgcolor="#CCCCCC">Kode</th>
        <th width="8%" bgcolor="#CCCCCC">NIM</th>
        <th width="43%" bgcolor="#CCCCCC">Nama Mahasiswa </th>
        <th width="6%" bgcolor="#CCCCCC">L/ P </th>
        <th width="11%" bgcolor="#CCCCCC">Status Pjm</th>
        <td colspan="3" align="center" bgcolor="#CCCCCC"><strong>Tools</strong></td>
      </tr>
      <?php
		// Skrip menampilkan data dari database
		$mySql = "SELECT * FROM mahasiswa WHERE ( status_aktif='Aktif' OR status_aktif='Alumni' ) $cariSQL ORDER BY kd_mahasiswa ASC LIMIT $halaman, $baris";
		$myQry = mysql_query($mySql, $koneksidb)  or die ("Query salah : ".mysql_error());
		$nomor = $halaman; 
		while ($myData = mysql_fetch_array($myQry)) {
			$nomor++;
			$Kode = $myData['kd_mahasiswa'];
			
			// menampilkan gambar utama
			if ($myData['foto']=="") {
				$fileGambar = "noimage.jpg";
			}
			else {
				$fileGambar	= $myData['foto'];
			}
			
			// gradasi warna
			if($nomor%2==1) { $warna=""; } else {$warna="#F5F5F5";}
	?>
      <tr bgcolor="<?php echo $warna; ?>">
        <td><?php echo $nomor; ?></td>
        <td><img src="foto/<?php echo $fileGambar; ?>" width="28" height="39" border="0" /></td>
        <td><?php echo $myData['kd_mahasiswa']; ?></td>
        <td><?php echo $myData['nim']; ?></td>
        <td><?php echo $myData['nm_mahasiswa']; ?></td>
        <td><?php echo $myData['kelamin']; ?></td>
        <td><?php echo $myData['status_pinjam']; ?></td>
        <td width="7%" align="center"><a href="?open=Mahasiswa-Delete&Kode=<?php echo $Kode; ?>" target="_self" onclick="return confirm(' YAKIN AKAN MENGHAPUS DATA MAHASISWA INI ... ?')">Delete</a></td>
        <td width="7%" align="center"><a href="?open=Mahasiswa-Edit&Kode=<?php echo $Kode; ?>" target="_self">Edit</a></td>
        <td width="6%" align="center"><a href="cetak/mahasiswa_cetak.php?Kode=<?php echo $Kode; ?>" target="_blank">Cetak</a></td>
      </tr>
      <?php } ?>
    </table></td>
  </tr>
  <tr>
    <td><strong>Jumlah Data :</strong> <?php echo $jmlData; ?> </td>
    <td align="right"><strong>Halaman ke :</strong>
    <?php
	for ($h = 1; $h <= $maksData; $h++) {
		$list[$h] = $baris * $h - $baris;
		echo " <a href='?open=Mahasiswa-Data&hal=$list[$h]'>$h</a> ";
	}
	?></td>
  </tr>
</table>
