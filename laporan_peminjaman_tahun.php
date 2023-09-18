<?php
include_once "library/inc.seslogin.php";
include_once "library/inc.connection.php";
include_once "library/inc.library.php";

# Tahun Terpilih
$tahun	   	= isset($_GET['tahun']) ? $_GET['tahun'] : date('Y'); // Baca dari URL, jika tidak ada diisi tahun sekarang
$dataTahun 	= isset($_POST['cmbTahun']) ? $_POST['cmbTahun'] : $tahun; // Baca dari form Submit, jika tidak ada diisi dari $tahun

# Membuat Filter Bulan
if($dataTahun) {
	// Jika tidak memilih bulan
	$filterSQL = "WHERE LEFT(tgl_peminjaman,4)='$dataTahun'";
}
else {
	$filterSQL = "";
}

# TMBOL CETAK DIKLIK
if (isset($_POST['btnCetak'])) {
		// Buka file
		echo "<script>";
		echo "window.open('cetak/peminjaman_tahun.php?tahun=$dataTahun')";
		echo "</script>";
}


# UNTUK PAGING (PEMBAGIAN HALAMAN)
$barisData 	= 50;
$halaman 	= isset($_GET['hal']) ? $_GET['hal'] : 0;
$pageSql 	= "SELECT * FROM peminjaman $filterSQL";
$pageQry 	= mysql_query($pageSql, $koneksidb) or die ("error paging: ".mysql_error());
$jumData	= mysql_num_rows($pageQry);
$maksData	= ceil($jumData/$barisData);
?>
<h2>LAPORAN PEMINJAMAN PER TAHUN </h2>
<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" name="form1" target="_self">
  <table width="900" border="0"  class="table-list">
    <tr>
      <td bgcolor="#CCCCCC"><strong>FILTER DATA </strong></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td width="138"><strong>Tahun Peminjaman  </strong></td>
      <td width="10"><strong>:</strong></td>
  <td width="738"><select name="cmbTahun">
            <?php
		# Baca tahun terendah(awal) di tabel Transaksi
		$thnSql = "SELECT MIN(LEFT(tgl_peminjaman,4)) As tahun_kecil, MAX(LEFT(tgl_peminjaman,4)) As tahun_besar FROM peminjaman";
		$thnQry	= mysql_query($thnSql, $koneksidb) or die ("Error".mysql_error());
		$thnRow	= mysql_fetch_array($thnQry);
		$thnKecil = $thnRow['tahun_kecil'];
		$thnBesar = $thnRow['tahun_besar'];
		
		// Menampilkan daftar Tahun, dari tahun terkecil sampai Terbesar (tahun sekarang)
		for($thn= $thnKecil; $thn <= $thnBesar; $thn++) {
			if ($thn == $dataTahun) {
				$cek = " selected";
			} else { $cek=""; }
			echo "<option value='$thn' $cek>$thn</option>";
		}
	  ?>
        </select>
          <input name="btnTampil" type="submit" value=" Tampilkan " />
          <input name="btnCetak" type="submit"  value=" Cetak " /></td>
    </tr>
  </table>
</form>

<table class="table-list" width="900" border="0" cellspacing="1" cellpadding="2">
  <tr>
    <td width="22" align="center" bgcolor="#CCCCCC"><strong>No</strong></td>
    <td width="88" bgcolor="#CCCCCC"><strong>No. Pinjam </strong></td>
    <td width="87" bgcolor="#CCCCCC"><strong>Tgl. Pinjam </strong></td>
    <td width="130" bgcolor="#CCCCCC"><strong>Tgl. Hrs Kembali </strong></td>
    <td width="339" bgcolor="#CCCCCC"><strong>Mahasiswa</strong></td>
    <td width="80" bgcolor="#CCCCCC"><strong>Kode Buku </strong> </td>
    <td width="76" bgcolor="#CCCCCC"><strong>Status</strong></td>
    <td align="center" bgcolor="#CCCCCC"><strong>Tools</strong></td>
  </tr>
  <?php
	// Perintah untuk menampilkan peminjaman dengan Filter Bulan
	$mySql = "SELECT peminjaman.*, mahasiswa.nim, mahasiswa.nm_mahasiswa FROM peminjaman 
			LEFT JOIN mahasiswa ON peminjaman.kd_mahasiswa = mahasiswa.kd_mahasiswa
			$filterSQL 
			ORDER BY no_peminjaman DESC LIMIT $halaman, $barisData";
	$myQry = mysql_query($mySql, $koneksidb)  or die ("Query 1 salah : ".mysql_error());
	$nomor = $halaman;
	while ($myData = mysql_fetch_array($myQry)) {
		$nomor++;
		// Membaca Kode peminjaman/ Nomor transaksi
		$noPinjam = $myData['no_peminjaman'];
	?>
  <tr>
    <td><?php echo $nomor; ?></td>
    <td><?php echo $myData['no_peminjaman']; ?></td>
    <td><?php echo IndonesiaTgl($myData['tgl_peminjaman']); ?></td>
    <td><?php echo IndonesiaTgl($myData['tgl_peminjaman']); ?></td>
    <td><?php echo $myData['nim']."/ ".$myData['nm_mahasiswa']; ?></td>
    <td><?php echo $myData['kd_inventaris']; ?></td>
    <td><?php echo $myData['status_pinjam']; ?></td>
    <td width="37" align="center"><a href="cetak/peminjaman_cetak.php?noPinjam=<?php echo $noPinjam; ?>" target="_blank">Cetak</a></td>
  </tr>
  <?php } ?>
  <tr>
    <td colspan="4"><strong>Jumlah Data :<?php echo $jumData; ?></strong></td>
    <td colspan="4" align="right"><strong>Halaman ke :
        <?php
	for ($h = 1; $h <= $maksData; $h++) {
		$list[$h] = $barisData * $h - $barisData;
		echo " <a href='?open=Laporan-Peminjaman-Tahun&hal=$list[$h]&tahun=$dataTahun'>$h</a> ";
	}
	?>
    </strong></td>
  </tr>
</table>
<a href="cetak/peminjaman_tahun.php?tahun=<?php echo $dataTahun; ?>" target="_blank"><img src="images/btn_print2.png" height="18" border="0" title="Cetak ke Format HTML"/></a>