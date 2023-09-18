<?php
include_once "library/inc.seslogin.php";
include_once "library/inc.connection.php";
include_once "library/inc.library.php";

// Baca variabel URL browser
$kodeKategori = isset($_GET['kodeKategori']) ? $_GET['kodeKategori'] : 'Semua'; 
// Baca variabel dari Form setelah di Post
$kodeKategori = isset($_POST['cmbKategori']) ? $_POST['cmbKategori'] : $kodeKategori;

// Membuat filter SQL
if ($kodeKategori=="Semua") {
	//Query #1 (semua data)
	$filterSQL 	= "";
}
else {
	//Query #2 (filter)
	$filterSQL 	= " WHERE buku.kd_kategori ='$kodeKategori'";
}

# TMBOL CETAK DIKLIK
if(isset($_POST['btnCetak'])) {
	// Buka file
	echo "<script>";
	echo "window.open('cetak/buku_kategori.php?kodeKategori=$kodeKategori', width=330)";
	echo "</script>";
}


# UNTUK PAGING (PEMBAGIAN HALAMAN)
$baris 		= 50;
$halaman 	= isset($_GET['hal']) ? $_GET['hal'] : 0;
$pageSql 	= "SELECT * FROM buku   $filterSQL";
$pageQry 	= mysql_query($pageSql, $koneksidb) or die("Error paging:".mysql_error());
$jmlData	= mysql_num_rows($pageQry);
$maksData	= ceil($jmlData/$baris);
?>
<h2> LAPORAN DATA BUKU PER KATEGORI </h2>
<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" name="form1" target="_self">
  <table width="800" border="0"  class="table-list">
    <tr>
      <td bgcolor="#CCCCCC"><strong>FILTER DATA </strong></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td width="136"><strong> Kategori </strong></td>
      <td width="10"><strong>:</strong></td>
      <td width="640">
	  <select name="cmbKategori">
        <option value="Semua">....</option>
        <?php
	  $bacaSql = "SELECT * FROM kategori ORDER BY nm_kategori";
	  $bacaQry = mysql_query($bacaSql, $koneksidb) or die ("Gagal Query".mysql_error());
	  while ($bacaData = mysql_fetch_array($bacaQry)) {
		if ($bacaData['kd_kategori'] == $kodeKategori) {
			$cek = " selected";
		} else { $cek=""; }
		echo "<option value='$bacaData[kd_kategori]' $cek>$bacaData[nm_kategori]</option>";
	  }
	  ?>
      </select>
      <input name="btnTampilkan" type="submit" value=" Tampilkan  "/></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><input name="btnCetak" type="submit" value=" Cetak " /></td>
    </tr>
  </table>
</form>

<table class="table-list" width="800" border="0" cellspacing="1" cellpadding="2">
  <tr>
    <td width="22" bgcolor="#CCCCCC"><strong>No</strong></td>
    <td width="51" bgcolor="#CCCCCC"><strong>Kode</strong></td>
    <td width="365" bgcolor="#CCCCCC"><strong>Judul Buku</strong></td>
    <td width="147" bgcolor="#CCCCCC"><strong>Penulis</strong></td>
    <td width="48" bgcolor="#CCCCCC"><strong>Jumlah</strong></td>
    <td width="61" bgcolor="#CCCCCC"><strong>Halaman</strong></td>
    <td width="70" bgcolor="#CCCCCC"><strong>Th Terbit</strong></td>
  </tr>
  <?php
	// Skrip menampilkan data dari database
	$mySql 	= "SELECT * FROM buku $filterSQL ORDER BY kd_buku ASC LIMIT $halaman, $baris";
	$myQry 	= mysql_query($mySql, $koneksidb)  or die ("Query salah : ".mysql_error());
	$nomor  = $halaman; 
	while ($myData = mysql_fetch_array($myQry)) {
		$nomor++;
	?>
  <tr>
    <td> <?php echo $nomor; ?> </td>
    <td> <?php echo $myData['kd_buku']; ?> </td>
    <td> <?php echo $myData['judul_buku']; ?> </td>
    <td> <?php echo $myData['penulis']; ?> </td>
    <td> <?php echo $myData['jumlah']; ?> </td>
    <td><?php echo format_angka($myData['jumlah_halaman']); ?></td>
    <td><?php echo $myData['tahun_terbit']; ?></td>
  </tr>
  <?php } ?>
  <tr class="selKecil">
    <td colspan="3"><strong>Jumlah Data :</strong> <?php echo $jmlData; ?> </td>
    <td colspan="4" align="right">
	<strong>Halaman ke :</strong>
    <?php
	for ($h = 1; $h <= $maksData; $h++) {
		$list[$h] = $baris * $h - $baris;
		echo " <a href='?open=Laporan-Buku-Kategori&hal=$list[$h]&kodeKategori=$kodeKategori'>$h</a> ";
	}
	?></td>
  </tr>
</table>
<a href="cetak/buku_kategori.php?kodeKategori=<?php echo $kodeKategori; ?>" target="_blank">Cetak</a>
