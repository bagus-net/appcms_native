<?php
include_once "../library/inc.seslogin.php";

# FILTER PEMBELIAN PER BULAN/TAHUN
# Bulan dan Tahun Terpilih
$bulan		= isset($_GET['bulan']) ? $_GET['bulan'] : date('m'); // Baca dari URL, jika tidak ada diisi bulan sekarang
$dataBulan 	= isset($_POST['cmbBulan']) ? $_POST['cmbBulan'] : $bulan; // Baca dari form Submit, jika tidak ada diisi dari $bulan

$tahun	   	= isset($_GET['tahun']) ? $_GET['tahun'] : date('Y'); // Baca dari URL, jika tidak ada diisi tahun sekarang
$dataTahun 	= isset($_POST['cmbTahun']) ? $_POST['cmbTahun'] : $tahun; // Baca dari form Submit, jika tidak ada diisi dari $tahun

# Membuat Filter Bulan
if($dataBulan and $dataTahun) {
	if($dataBulan == "00") {
		// Jika tidak memilih bulan
		$filterSQL = "AND LEFT(pengembalian.tgl_kembali,4)='$dataTahun'";
	}
	else {
		// Jika memilih bulan dan tahun
		$filterSQL = "AND LEFT(pengembalian.tgl_kembali,4)='$dataTahun' AND MID(pengembalian.tgl_kembali,6,2)='$dataBulan'";
	}
}
else {
	$filterSQL = "";
}
# ==================================================================

# UNTUK PAGING (PEMBAGIAN HALAMAN)
$baris 		= 100;
$halaman 	= isset($_GET['hal']) ? $_GET['hal'] : 0;
$pageSql 	= "SELECT * FROM pengembalian, peminjaman WHERE peminjaman.no_peminjaman = pengembalian.no_peminjaman $filterSQL ";
$pageQry 	= mysql_query($pageSql, $koneksidb) or die ("error paging: ".mysql_error());
$jmlData	= mysql_num_rows($pageQry);
$maksData	= ceil($jmlData/$baris);
?>
<h1><b>DATA PENGEMBALIAN BUKU</b> </h1>

<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" name="form1" target="_self">
  <table width="800" border="0"  class="table-list">
    <tr>
      <td bgcolor="#CCCCCC"><strong>FILTER DATA</strong></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><strong>Bulan/ Tahun </strong></td>
      <td><strong>:</strong></td>
      <td><select name="cmbBulan">
          <?php
		// Membuat daftar Nama Bulan
		$listBulan = array("00" => "....", 
						"01" => "01. Januari", 
						"02" => "02. Februari", 
						"03" => "03. Maret",
						"04" => "04. April", 
						"05" => "05. Mei", 
						"06" => "06. Juni", 
						"07" => "07. Juli",
						"08" => "08. Agustus", 
						"09" => "09. September", 
						"10" => "10. Oktober",
						"11" => "11. November", 
						"12" => "12. Desember");
						 
		// Menampilkan Nama Bulan ke ComboBox (List/Menu)
		foreach($listBulan as $bulanKe => $bulanNm) {
			if ($bulanKe == $dataBulan) {
				$cek = " selected";
			} else { $cek=""; }
			echo "<option value='$bulanKe' $cek>$bulanNm</option>";
		}
	  ?>
        </select>
          <select name="cmbTahun">
            <?php
		# Baca tahun terendah(awal) di tabel Transaksi
		$thnSql = "SELECT MIN(LEFT(tgl_kembali,4)) As tahun_kecil, MAX(LEFT(tgl_kembali,4)) As tahun_besar FROM pengembalian";
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
        </select></td>
    </tr>
    <tr>
      <td width="137">&nbsp;</td>
      <td width="10">&nbsp;</td>
      <td width="639"><input name="btnTampil" type="submit" value=" Tampilkan " /></td>
    </tr>
  </table>
</form>

<table width="800" border="0" cellpadding="2" cellspacing="1" class="table-border">
  <tr>
    <td colspan="2">
	<table class="table-list" width="100%" border="0" cellspacing="1" cellpadding="2">
      <tr>
        <th width="30" align="center">No</th>
        <th width="97">No. Pinjam </th>
        <th width="87">Tgl. Pinjam </th>
        <th width="130">Tgl. Kembali </th>
        <th width="277">Mahasiswa </th>
        <th width="96" align="right">Denda(Rp) </th>
        <td align="center" bgcolor="#CCCCCC"><strong>Tools</strong></td>
      </tr>
      <?php
	  // Skrip menampilkan data dari database
	$mySql = "SELECT pengembalian.*, peminjaman.tgl_peminjaman, mahasiswa.kd_mahasiswa, mahasiswa.nm_mahasiswa FROM pengembalian, peminjaman
			 LEFT JOIN mahasiswa ON peminjaman.kd_mahasiswa = mahasiswa.kd_mahasiswa
			 WHERE peminjaman.no_peminjaman = pengembalian.no_peminjaman $filterSQL
			 ORDER BY no_peminjaman DESC LIMIT $halaman, $baris";
	$myQry = mysql_query($mySql, $koneksidb)  or die ("Query salah : ".mysql_error());
	$nomor = $halaman; 
	while ($myData = mysql_fetch_array($myQry)) {
		$nomor++;
		$Kode = $myData['no_pengembalian'];
		?>
      <tr>
        <td><?php echo $nomor; ?></td>
        <td><?php echo $myData['no_peminjaman']; ?></td>
        <td><?php echo IndonesiaTgl($myData['tgl_peminjaman']); ?></td>
        <td><?php echo IndonesiaTgl($myData['tgl_kembali']); ?></td>
        <td><?php echo $myData['kd_mahasiswa']."/ ".$myData['nm_mahasiswa']; ?></td>
        <td align="right"><?php echo format_angka($myData['denda']); ?></td>
        <td width="41" align="center"><a href="pengembalian_cetak.php?Kode=<?php echo $Kode; ?>" target="_blank">Cetak</a></td>
        </tr>
      <?php } ?>
    </table></td>
  </tr>
  <tr class="selKecil">
    <td width="299"><b>Jumlah Data :<?php echo $jmlData; ?></b></td>
    <td width="480" align="right"><b>Halaman ke :</b>
      <?php
	for ($h = 1; $h <= $maksData; $h++) {
		$list[$h] = $baris * $h - $baris;
		echo " <a href='?open=Pengembalian-Tampil&hal=$list[$h]&bulan=$dataBulan&tahun=$dataTahun'>$h</a> ";
	}
	?></td>
  </tr>
</table>
