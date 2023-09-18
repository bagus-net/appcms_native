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
		$filterSQL = "WHERE LEFT(morder.tgl,4)='$dataTahun'";
	}
	else {
		// Jika memilih bulan dan tahun
		$filterSQL = "WHERE LEFT(morder.tgl,4)='$dataTahun' AND MID(morder.tgl,6,2)='$dataBulan'";
	}
}
else {
	$filterSQL = "";
}
# ==================================================================

# UNTUK PAGING (PEMBAGIAN HALAMAN)
$baris 		= 100;
$halaman 	= isset($_GET['hal']) ? $_GET['hal'] : 0;
$pageSql 	= "SELECT * FROM morder ";  // $filterSQL ";
$pageQry 	= pg_query($koneksidb, $pageSql) or die ("error paging: ".pg_last_error());
$jmlData	= pg_num_rows($pageQry);
$maksData	= ceil($jmlData/$baris);
?>
<h1><b>DATA TINJAUAN ORDER </b></h1>

<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" name="form1" target="_self">
  <table width="800" border="0"  class="table-list">
    <tr>
      <td bgcolor="#CCCCCC"><strong>FILTER DATA</strong></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><strong>Bulan Peminjaman </strong></td>
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
				<select name="tahuna">
				<option selected value="<?php print date('Y') ?>"><?php print date('Y') ?></option>
					<?php for($t=2010;$t<=2030;$t++) {
						print "<option value='".$t."'>".$t."</option>";
						}
					?>
				</select>		

<!--
        </select>
          <select name="cmbTahun">
            <?php
		# Baca tahun terendah(awal) di tabel Transaksi
		$thnSql = "SELECT MIN(LEFT(tgl,4)) As tahun_kecil, MAX(LEFT(tgl,4)) As tahun_besar FROM morder";
		$thnQry	= pg_query($koneksidb,$thnSql) or die ("Error".pg_last_error());
		$thnRow	= pg_fetch_array($thnQry);
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
        -->
        
    </tr>
    
    <tr>
      <td width="138">&nbsp;</td>
      <td width="10">&nbsp;</td>
      <td width="638"><input name="btnTampil" type="submit" value=" Tampilkan " /></td>
    </tr>
  </table>
</form>

<table width="800" border="0" cellpadding="2" cellspacing="1" class="table-border">
  <tr>
    <td colspan="2">
	<table class="table-list" width="100%" border="0" cellspacing="1" cellpadding="2">
      <tr>
        <td width="28" align="center" bgcolor="#CCCCCC"><strong>No</strong></td>
        <td width="90" bgcolor="#CCCCCC"><strong>No. Pinjam </strong></td>
        <td width="80" bgcolor="#CCCCCC"><strong>Tgl. Pinjam </strong></td>
        <td width="120" bgcolor="#CCCCCC"><strong>Tgl. Hrs Kembali </strong></td>
        <td width="256" bgcolor="#CCCCCC"><strong>NIM/Nama Mahasiswa  </strong></td>
        <td width="80" bgcolor="#CCCCCC"><strong>Status </strong></td>
        <td colspan="2" align="center" bgcolor="#CCCCCC"><strong>Tools</strong></td>
      </tr>
      <?php
	  // Skrip menampilkan data dari database
	$mySql = "SELECT morder.*, msales.kode, msales.nama FROM morder 
			 LEFT JOIN mcust ON morder.kodestock=msales.kode
			 $filterSQL
			 ORDER BY kode ";   // LIMIT $baris offset $halaman ";   // , "; DESC 
			 
	$myQry = pg_query($mySql, $koneksidb)  or die ("Query salah : ".pg_last_error());
	$nomor = $halaman; 
	while ($myData = pg_fetch_array($myQry)) {
		$nomor++;
		$Kode = $myData['no_peminjaman'];
		?>
      <tr>
        <td><?php echo $nomor; ?></td>
        <td><?php echo $myData['kode']; ?></td>
        <td><?php echo IndonesiaTgl($myData['tgl']); ?></td>
        <td><?php echo IndonesiaTgl($myData['tgl_harus_kembali']); ?></td>
        <td><?php echo $myData['nim']."/ ".$myData['nm_mahasiswa']; ?></td>
        <td><?php echo $myData['status_pinjam']; ?></td>
        <td width="46" align="center"><a href="?open=Peminjaman-Hapus&Kode=<?php echo $Kode; ?>" target="_self" alt="Delete Data" onclick="return confirm(' YAKIN AKAN MENGHAPUS DATA PEMINJAMAN INI ... ?')">Delete</a></td>
        <td width="43" align="center"><a href="peminjaman_cetak.php?noPinjam=<?php echo $Kode; ?>" target="_blank">Cetak</a></td>
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
		echo " <a href='?open=Peminjaman-Tampil&hal=$list[$h]&bulan=$dataBulan&tahun=$dataTahun'>$h</a> ";
	}
	?></td>
  </tr>
</table>
