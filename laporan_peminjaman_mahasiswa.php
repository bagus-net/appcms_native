<?php
include_once "library/inc.seslogin.php";
include_once "library/inc.connection.php";
include_once "library/inc.library.php";

# SAAT KOTAK NIS DIINPUT DATA NOMOR SISWA, MAKA OTOMATIS FORM TERISI
# MEMBACA DATA BAYARAN SISWA. Saat NIS diinput, otomatis form akan Submit dan menjalankan skrip ini
// Tampilan data pada form
$nim				= isset($_GET['nim']) ? $_GET['nim'] : '';
$dataNim			= isset($_POST['txtNim']) ? $_POST['txtNim'] : $nim;
$dataNamaMahasiswa	= isset($_POST['txtNamaMahasiswa']) ? $_POST['txtNamaMahasiswa'] : '';

// Query untuk filter data
$mySql ="SELECT * FROM mahasiswa WHERE nim ='$dataNim' OR kd_mahasiswa ='$dataNim'";
$myQry = mysql_query($mySql, $koneksidb) or die ("Gagal Query".mysql_error());
$myData= mysql_fetch_array($myQry);
if(mysql_num_rows($myQry) >=1) {
	$dataKode		= $myData['kd_mahasiswa'];
	$dataNim		= $myData['nim'];
	$dataNamaMahasiswa	= $myData['nm_mahasiswa'];
	
	// Filter SQL
	$filterSQL = "WHERE peminjaman.kd_mahasiswa='$dataKode'";
}
else {
	// Jika tidak ditemukan, datanya disamapan dengan skrip form Post di atas
	$dataNim		= $dataNim;
	$dataNamaMahasiswa	= $dataNamaMahasiswa;
	
	$filterSQL = "";
}


# UNTUK PAGING (PEMBAGIAN HALAMAN)
$barisData 	= 50;
$halaman 	= isset($_GET['hal']) ? $_GET['hal'] : 0;
$pageSql 	= "SELECT * FROM peminjaman $filterSQL";
$pageQry 	= mysql_query($pageSql, $koneksidb) or die ("error paging: ".mysql_error());
$jumData	= mysql_num_rows($pageQry);
$maksData	= ceil($jumData/$barisData);
?>
<SCRIPT language="JavaScript">
function submitform() {
	document.form1.submit();
}
</SCRIPT>
<h2>LAPORAN PEMINJAMAN PER MAHASISWA </h2>
<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" name="form1" target="_self">
  <table width="800" border="0"  class="table-list">
    <tr>
      <td bgcolor="#CCCCCC"><strong>FILTER DATA </strong></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td width="137"><strong>NIM Mhs </strong></td>
      <td width="10"><strong>:</strong></td>
      <td width="639"><input type="text" name="txtNim"  id="txtNim" value="<?php echo $dataNim; ?>" onchange="javascript:submitform();"/>
        <input name="btnTampil" type="submit" value=" Tampilkan " />
        <input name="btnCetak" type="submit"  value=" Cetak " /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><input name="txtNamaMahasiswa" id="txtNamaMahasiswa" value="<?php echo $dataNamaMahasiswa; ?>" size="60" maxlength="100" readonly="readonly"/></td>
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
		echo " <a href='?open=Laporan-Peminjaman-Mahasiswa&hal=$list[$h]&nim=$dataNim'>$h</a> ";
	}
	?>
    </strong></td>
  </tr>
</table>
<a href="cetak/peminjaman_mahasiswa.php?nim=<?php echo $dataNim; ?>" target="_blank"><img src="images/btn_print2.png" height="18" border="0" title="Cetak ke Format HTML"/></a>