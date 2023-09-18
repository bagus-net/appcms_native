<?php
include_once "../library/inc.seslogin.php";

# MEMBACA PETUGAS YANG LOGIN
$kodeUser	= $_SESSION['SES_LOGIN'];


# ========================================================================================================
# JIKA TOMBOL SIMPAN TRANSAKSI DIKLIK
if(isset($_POST['btnSimpan'])){
	# Baca variabel data from
	$txtTglPinjam 	= InggrisTgl($_POST['txtTglPinjam']);
	$txtTglKembali 	= InggrisTgl($_POST['txtTglKembali']);
	$txtNim			= $_POST['txtNim'];
	$txtKodeBuku	= $_POST['txtKodeBuku'];

	# Validasi Kotak isi Form
	$pesanError = array();
//	if (trim($txtTglPinjam)=="") {
//		$pesanError[] = "Data <b>Tgl. Peminjaman</b> belum diisi, pilih pada kalender !";		
//	}
//	if (trim($txtTglKembali)=="--") {
//		$pesanError[] = "Data <b>Tgl. Harus Kembali</b> belum diisi, pilih pada kalender !";		
//	}

	if (trim($txtNim)=="") {
		$pesanError[] = "Data <b>Kode/Nama Customer </b> belum diisi, untuk memudahkan lakukan Pencarian Customer !";		
	}
	else {
		// Validasi Kode NIM/ Kode Mahasiswa apakah ada dalam database
		$cekSql	= "SELECT * FROM mcust WHERE kode='$txtNim' OR nama='$txtNim'";
		$cekQry	= mysql_query($cekSql, $koneksidb) or die ("Error cek ".mysql_error());
		$cekData= mysql_fetch_array($cekQry);
		if(mysql_num_rows($cekQry) < 1) {
			$pesanError[] = "Data <b>Customer Tidak Dikenali</b>, data tidak ada dalam database !";
		}
		
//		if($cekData['status_pinjam']=="Pinjam") {
//			$pesanError[] = "Data <b>NIM $txtNim Sedang Pinjam</b>, peminjaman sebelumnya harus dikembalikan dulu !";
//		}
	}
	if (trim($txtKodeBuku)=="") {
		$pesanError[] = "Data <b>Kode Jenis</b> belum diisi, untuk memudahkan lakukan Pencarian !";		
	}
	else {
		// Validasi Kode Buku
		$cek2Sql	= "SELECT * FROM mjenis WHERE kode='$txtKodeBuku'";
		$cek2Qry	= mysql_query($cek2Sql, $koneksidb) or die ("Error cek ".mysql_error());
		$cek2Data	= mysql_fetch_array($cek2Qry);

		if(mysql_num_rows($cek2Qry) < 1) {
			$pesanError[] = "Data <b>Jenis Tidak Dikenali</b>, data tidak ada dalam database !";
		}
		
//		if($cek2Data['status']== "Keluar") {
//			$pesanError[] = "Data <b>Kode Buku ( $txtKodeBuku ) Sedang Keluar </b>, tidak dapat dipinjam lagi !";
//		}
	}
		
	# JIKA ADA PESAN ERROR DARI VALIDASI
	if (count($pesanError)>=1 ){
		echo "<div class='mssgBox'>";
		echo "<img src='../images/attention.png'> <br><hr>";
			$noPesan=0;
			foreach ($pesanError as $indeks=>$pesan_tampil) { 
			$noPesan++;
				echo "&nbsp;&nbsp; $noPesan. $pesan_tampil<br>";	
			} 
		echo "</div> <br>"; 
	}
	else {
		# SIMPAN DATA KE DATABASE
		# Jika jumlah error pesanError tidak ada, maka penyimpanan dilakukan. Data dari tmp dipindah ke tabel peminjaman dan peminjaman_item
		
		// Membaca Kode Mahasiswa dan Kelas Sekarang, karena pada form yang diinput adalah NIM, sedang yang disimpan ke Transaksi adalah Kode Mahasiswa

		$bacaSql	= "SELECT kode FROM mcust WHERE kode='$txtNim' OR nama='$txtNim'";
		$bacaQry	= mysql_query($bacaSql, $koneksidb) or die ("Error baca ".mysql_error());
		$bacaData	= mysql_fetch_array($bacaQry);
			$kodeMahasiswa	= $bacaData['kode'];
			
		// Simpan data Transaksi Utama ke tabel Peminjaman
		$noTransaksi = buatKode("morder", "PJ");
		$mySql	= "INSERT INTO morder (kode, tgl, kodesupp, jeniskode, modifyby)
					VALUES ('$noTransaksi', '$txtTglPinjam', '$kodeMahasiswa', '$txtKodeBuku', '$kodeUser')";
		mysql_query($mySql, $koneksidb) or die ("Gagal query".mysql_error());
		

		// Update status Pinjam Mahasiswa (Keluar artinya Sedang Dipinjam)
//		$editSql = "UPDATE mahasiswa SET status_pinjam ='Pinjam' WHERE kd_mahasiswa='$kodeMahasiswa'";
//		mysql_query($editSql, $koneksidb) or die ("Gagal Query edit Mahasiswa ".mysql_error());
				
		// Update status Buku//
//		$edit2Sql = "UPDATE buku_inventaris SET status ='Keluar' WHERE kd_inventaris='$txtKodeBuku'";
//		mysql_query($edit2Sql, $koneksidb) or die ("Gagal Query edit Buku ".mysql_error());
				
		// Refresh form
		echo "<meta http-equiv='refresh' content='0; url=?open=Tinjauan Order-Baru'>";
		
		// Refresh form
		echo "<script>";
		echo "window.open('peminjaman_cetak.php?noPinjam=$noTransaksi', width=330,height=330,left=100, top=25)";
		echo "</script>";
	}	
}

# TAMPILKAN DATA KE FORM
// $noTransaksi 	= buatKode("morder", "TJO");
$noTransaksi 	= isset($_POST['txtNim']) ? $_POST['txtNim'] : '';
$dataTglPinjam 	= isset($_POST['txtTglPinjam']) ? $_POST['txtTglPinjam'] : date('d-m-Y');
$dataTglKembali = isset($_POST['txtTglKembali']) ? $_POST['txtTglKembali'] : '';
$dataNim		= isset($_POST['txtNim']) ? $_POST['txtNim'] : '';
$dataNamaMhs	= isset($_POST['txtNamaMhs']) ? $_POST['txtNamaMhs'] : '';

$dataKodeBuku	= isset($_POST['txtKodeBuku']) ? $_POST['txtKodeBuku'] : '';
$dataJudulBuku	= isset($_POST['txtJudulBuku']) ? $_POST['txtJudulBuku'] : '';

# SAAT KOTAK NIM DIINPUT DATA NOMOR SISWA, MAKA OTOMATIS FORM TERISI
# MEMBACA DATA BAYARAN SISWA. Saat NIM diinput, otomatis form akan Submit dan menjalankan skrip ini
if(isset($_POST['txtNim'])) {
	$mySql ="SELECT * FROM mcust WHERE kode ='$dataNim' OR nama ='$dataNim'";
	$myQry = mysql_query($mySql, $koneksidb) or die ("Gagal Query".mysql_error());
	if(mysql_num_rows($myQry) >=1) {
		$myData= mysql_fetch_array($myQry);
		$dataNim		= $myData['kode'];
		$dataNamaMhs	= $myData['nama'];
	}
	else {
		// Jika tidak ditemukan, datanya disamapan dengan skrip form Post di atas
		$dataNim		= $dataNim;
		$dataNamaMhs	= $dataNamaMhs;
	}
}

# SAAT KOTAK KODE BUKU DIINPUT DATA, MAKA OTOMATIS FORM TERISI
if(isset($_POST['txtKodeBuku'])) {
	$mySql ="SELECT kode,keterangan FROM mjenis WHERE kode ='$dataKodeBuku'";
	$myQry = mysql_query($mySql, $koneksidb) or die ("Gagal Query".mysql_error());
	if(mysql_num_rows($myQry) >=1) {
		$myData= mysql_fetch_array($myQry);
		$dataKodeBuku	= $myData['kode'];
		$dataJudulBuku	= $myData['keterangan'];
	}
	else {
		// Jika tidak ditemukan, datanya disamapan dengan skrip form Post di atas
		$dataKodeBuku	= $dataKodeBuku;
		$dataJudulBuku	= $dataJudulBuku;
	}
}
?>
<SCRIPT language="JavaScript">
function submitform() {
	document.form1.submit();
}
</SCRIPT>
<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" name="form1" target="_self">
  <table width="800" cellpadding="3" cellspacing="1"  class="table-list">
    <tr>
      <td colspan="3"><h3>TINJAUAN ORDER </h3></td>
    </tr>
    <tr>
      <td bgcolor="#CCCCCC"><strong>DATA TRANSAKSI </strong></td>
      <td bgcolor="#CCCCCC">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td width="26%"><strong>No. Tinjauan Order </strong></td>
      <td width="2%"><strong>:</strong></td>
      <td width="72%"><input name="txtNomor" value="<?php echo $noTransaksi; ?>" size="23" maxlength="20" readonly="readonly"/></td>
    </tr>
    <tr>
      <td><strong>Tgl. Tinjauan </strong></td>
      <td><strong>:</strong></td>
      <td><input type="text" name="txtTglPinjam" class="tcal" value="<?php echo $dataTglPinjam; ?>" /></td>
    </tr>
    
<!--     <tr> 
      <td><strong>Tgl. Harus Kembali </strong></td>
      <td><strong>:</strong></td>
      <td><input type="text" name="txtTglKembali" class="tcal" value="<?php echo $dataTglKembali; ?>" /></td>
    </tr>     
-->


    <tr>
      <td bgcolor="#CCCCCC"><strong>DATA Customer </strong></td>
      <td bgcolor="#CCCCCC">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><strong>Kode Customer</strong></td>
      <td><strong>:</strong></td>
      <td><input type="text" name="txtNim"  id="txtNim" value="<?php echo $dataNim; ?>" onChange="javascript:submitform();"/>
      <b><a href="javaScript: void(0)" onclick="popup('pencarian_mcust.php')" target="_self">Cari Customer </a></b></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><input name="txtNamaMhs" id="txtNamaMhs" value="<?php echo $dataNamaMhs; ?>" size="80" maxlength="100" disabled="disabled"/></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td bgcolor="#CCCCCC"><strong>Jenis Item </strong></td>
      <td bgcolor="#CCCCCC">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>


<!--

    <tr>
      <td><strong>Kode Jenis</strong></td>
      <td><strong>:</strong></td>
      <td><input name="txtKodeBuku" id="txtKodeBuku" value="<?php echo $dataKodeBuku; ?>" size="20" maxlength="20" onChange="javascript:submitform();"/>
      <input name="btnTambah" type="submit" style="cursor:pointer;" value=" Tambah " />
	  <b><a href="javaScript: void(0)" onclick="popup('pencarian_mcust.php')" target="_self">Cari Jenis </a></b>
      </td>
    </tr>
 -->
    
    <tr>
      <td><strong>Kode Jenis</strong></td>
      <td><strong>:</strong></td>
      <td><input type="text" name="txtKodeBuku"  id="txtKodeBuku" value="<?php echo $dataKodeBuku; ?>" onChange="javascript:submitform();"/>
      <b><a href="javaScript: void(0)" onclick="popup('pencarian_mbarang.php')" target="_self">Cari Jenis </a></b></td>
    </tr>
    
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><b>
        <input name="txtJudulBuku" id="txtJudulBuku" value="<?php echo $dataJudulBuku; ?>" size="80" maxlength="200" disabled="disabled"/>
      </b></td>
    </tr>




    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><input name="btnSimpan" type="submit" style="cursor:pointer;" value=" SIMPAN TINJAUAN " /></td>
    </tr>
  </table>
  <br>
</form>
