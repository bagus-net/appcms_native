<?php
include_once "library/inc.seslogin.php";
include_once "library/inc.connection.php";
include_once "library/inc.library.php";

// Baca variabel URL browser
$kodePenerbit = isset($_GET['kodePenerbit']) ? $_GET['kodePenerbit'] : 'Semua'; 
// Baca variabel dari Form setelah di Post
$kodePenerbit = isset($_POST['cmbPenerbit']) ? $_POST['cmbPenerbit'] : $kodePenerbit;

// Membuat filter SQL
if ($kodePenerbit=="Semua") {
	//Query #1 (semua data)
	$filterSQL 	= "";
}
else {
	//Query #2 (filter)
	$filterSQL 	= " WHERE mbarang.kodesupp ='$kodePenerbit'";
}

# TMBOL CETAK DIKLIK
if(isset($_POST['btnCetak'])) {
	// Buka file
	echo "<script>";
	echo "window.open('cetak/buku_penerbit.php?kodePenerbit=$kodePenerbit', width=330)";
	echo "</script>";
}


# UNTUK PAGING (PEMBAGIAN HALAMAN)
$baris 		= 50;
$halaman 	= isset($_GET['hal']) ? $_GET['hal'] : 0;
$pageSql 	= "SELECT * FROM mbarang $filterSQL";
$pageQry 	= pg_query($koneksidb, $pageSql) or die("Error paging:".pg_last_error());
$jmlData	= pg_num_rows($pageQry);
$maksData	= ceil($jmlData/$baris);
?>
<h2> LAPORAN DATA ITEM BOX PER CUSTOMER </h2>
<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" name="form1" target="_self">
  <table width="1100" border="0"  class="table-list">
    <tr>
      <td bgcolor="#CCCCCC"><strong>FILTER DATA </strong></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td width="138"><strong> Customer </strong></td>
      <td width="10"><strong>:</strong></td>
      <td width="638">
	  <select name="cmbPenerbit">
        <option value="Semua">....</option>
        <?php
	  $bacaSql = "SELECT * FROM mcust ORDER BY nama";
	  $bacaQry = pg_query($koneksidb, $bacaSql) or die ("Gagal Query".pg_last_error());
	  while ($bacaData = pg_fetch_array($bacaQry)) {
		if ($bacaData['kode'] == $kodePenerbit) {
			$cek = " selected";
		} else { $cek=""; }
		echo "<option value='$bacaData[kode]' $cek>$bacaData[nama]</option>";
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

<table class="table-list" width="1000" border="0" cellspacing="1" cellpadding="2">
  <tr>
    <td width="22" bgcolor="#CCCCCC"><strong>No</strong></td>
    <td width="130" bgcolor="#CCCCCC"><strong>Kode</strong></td>
    <td width="365" bgcolor="#CCCCCC"><strong>Nama</strong></td>
    <td width="100" bgcolor="#CCCCCC"><strong>Ukuran</strong></td>
    <td width="48" bgcolor="#CCCCCC"><strong>Flute</strong></td>
    <td width="200" bgcolor="#CCCCCC"><strong>Substance</strong></td>
    <td width="48" bgcolor="#CCCCCC"><strong>Type</strong></td>
    <td width="70" bgcolor="#CCCCCC"><strong>Joint</strong></td> 
    
  </tr>
  <?php
	// Skrip menampilkan data dari database
	$mySql 	= "SELECT * FROM mbarang $filterSQL ORDER BY kode LIMIT $baris offset $halaman  ";
	$myQry 	= pg_query($koneksidb, $mySql)  or die ("Query salah : ".pg_last_error());
	$nomor  = $halaman; 
	while ($myData = pg_fetch_array($myQry)) {
		$nomor++;
	?>
  <tr>
    <td> <?php echo $nomor; ?> </td>
    <td> <?php echo $myData['kode']; ?> </td>
    <td> <?php echo $myData['nama']; ?> </td>
    <td> <?php echo $myData['ukuran']; ?> </td>
    <td> <?php echo $myData['flute']; ?></td> 
    <td> <?php echo $myData['skwa']; ?> </td>
    <td> <?php echo $myData['type']; ?></td> 
    <td> <?php echo $myData['sambungan']; ?></td> 
    
<!--    <td><?php echo format_angka($myData['jumlah_halaman']); ?></td> -->
    
  </tr>
  <?php } ?>
  <tr class="selKecil">
    <td colspan="3"><strong>Jumlah Data :</strong> <?php echo $jmlData; ?> </td>
    <td colspan="4" align="right">
	<strong>Halaman ke :</strong>
    <?php
	for ($h = 1; $h <= $maksData; $h++) {
		$list[$h] = $baris * $h - $baris;
		echo " <a href='?open=Laporan-Buku-Penerbit&hal=$list[$h]&kodePenerbit=$kodePenerbit'>$h</a> ";
	}
	?></td>
  </tr>
</table>
<a href="cetak/buku_penerbit.php?kodePenerbit=<?php echo $kodePenerbit; ?>" target="_blank">Cetak</a>
