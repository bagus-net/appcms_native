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
	if (trim($txtTglPinjam)=="") {
		$pesanError[] = "Data <b>Tgl. Peminjaman</b> belum diisi, pilih pada kalender !";		
	}
	if (trim($txtTglKembali)=="--") {
		$pesanError[] = "Data <b>Tgl. Harus Kembali</b> belum diisi, pilih pada kalender !";		
	}
	if (trim($txtNim)=="") {
		$pesanError[] = "Data <b>Kode/NIM Mahasiswa</b> belum diisi, untuk memudahkan lakukan Pencarian Mahasiswa !";		
	}
	else {
		// Validasi Kode NIM/ Kode Mahasiswa apakah ada dalam database
		$cekSql	= "SELECT * FROM mahasiswa WHERE kd_mahasiswa='$txtNim' OR nim='$txtNim'";
		$cekQry	= mysql_query($cekSql, $koneksidb) or die ("Error cek ".mysql_error());
		$cekData= mysql_fetch_array($cekQry);
		if(mysql_num_rows($cekQry) < 1) {
			$pesanError[] = "Data <b>NIM Tidak Dikenali</b>, data tidak ada dalam database !";
		}
		if($cekData['status_pinjam']=="Pinjam") {
			$pesanError[] = "Data <b>NIM $txtNim Sedang Pinjam</b>, peminjaman sebelumnya harus dikembalikan dulu !";
		}
	}
	if (trim($txtKodeBuku)=="") {
		$pesanError[] = "Data <b>Kode Buku</b> belum diisi, untuk memudahkan lakukan Pencarian !";		
	}
	else {
		// Validasi Kode Buku
		$cek2Sql	= "SELECT * FROM buku_inventaris WHERE kd_inventaris='$txtKodeBuku'";
		$cek2Qry	= mysql_query($cek2Sql, $koneksidb) or die ("Error cek ".mysql_error());
		$cek2Data	= mysql_fetch_array($cek2Qry);
		if($cek2Data['status']== "Keluar") {
			$pesanError[] = "Data <b>Kode Buku ( $txtKodeBuku ) Sedang Keluar </b>, tidak dapat dipinjam lagi !";
		}
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
		$bacaSql	= "SELECT kd_mahasiswa FROM mahasiswa WHERE kd_mahasiswa='$txtNim' OR nim='$txtNim'";
		$bacaQry	= mysql_query($bacaSql, $koneksidb) or die ("Error baca ".mysql_error());
		$bacaData	= mysql_fetch_array($bacaQry);
			$kodeMahasiswa	= $bacaData['kd_mahasiswa'];
			
		// Simpan data Transaksi Utama ke tabel Peminjaman
		$noTransaksi = buatKode("peminjaman", "PJ");
		$mySql	= "INSERT INTO peminjaman (no_peminjaman, tgl_peminjaman, tgl_harus_kembali, kd_mahasiswa, kd_inventaris, status_pinjam, kd_user)
					VALUES ('$noTransaksi', '$txtTglPinjam', '$txtTglKembali', '$kodeMahasiswa', '$txtKodeBuku', 'Pinjam', '$kodeUser')";
		mysql_query($mySql, $koneksidb) or die ("Gagal query".mysql_error());
		

		// Update status Pinjam Mahasiswa (Keluar artinya Sedang Dipinjam)
		$editSql = "UPDATE mahasiswa SET status_pinjam ='Pinjam' WHERE kd_mahasiswa='$kodeMahasiswa'";
		mysql_query($editSql, $koneksidb) or die ("Gagal Query edit Mahasiswa ".mysql_error());
				
		// Update status Buku
		$edit2Sql = "UPDATE buku_inventaris SET status ='Keluar' WHERE kd_inventaris='$txtKodeBuku'";
		mysql_query($edit2Sql, $koneksidb) or die ("Gagal Query edit Buku ".mysql_error());
				
		// Refresh form
		echo "<meta http-equiv='refresh' content='0; url=?open=Peminjaman-Baru'>";
		
		// Refresh form
		echo "<script>";
		echo "window.open('peminjaman_cetak.php?noPinjam=$noTransaksi', width=330,height=330,left=100, top=25)";
		echo "</script>";
	}	
}

# TAMPILKAN DATA KE FORM

$noTransaksi 	= ''; // buatKode("peminjaman", "PJ");
$dataTglPinjam 	= isset($_POST['txtTglPinjam']) ? $_POST['txtTglPinjam'] : date('d-m-Y');
$dataTglKembali = isset($_POST['txtTglKembali']) ? $_POST['txtTglKembali'] : '';
$dataNim		= isset($_POST['txtNim']) ? $_POST['txtNim'] : '';
$dataNamaMhs	= isset($_POST['txtNamaMhs']) ? $_POST['txtNamaMhs'] : '';

$dataKodeBuku	= isset($_POST['txtKodeBuku']) ? $_POST['txtKodeBuku'] : '';
$dataJudulBuku	= isset($_POST['txtNJudulBuku']) ? $_POST['txtJudulBuku'] : '';

# SAAT KOTAK NIM DIINPUT DATA NOMOR SISWA, MAKA OTOMATIS FORM TERISI
# MEMBACA DATA BAYARAN SISWA. Saat NIM diinput, otomatis form akan Submit dan menjalankan skrip ini
if(isset($_POST['txtNim'])) {
	$mySql ="SELECT * FROM mahasiswa WHERE nim ='$dataNim' OR kd_mahasiswa ='$dataNim'";
	$myQry = mysql_query($mySql, $koneksidb) or die ("Gagal Query".mysql_error());
	if(mysql_num_rows($myQry) >=1) {
		$myData= mysql_fetch_array($myQry);
		$dataNim		= $myData['nim'];
		$dataNamaMhs	= $myData['nm_mahasiswa'];
	}
	else {
		// Jika tidak ditemukan, datanya disamapan dengan skrip form Post di atas
		$dataNim		= $dataNim;
		$dataNamaMhs	= $dataNamaMhs;
	}
}

# SAAT KOTAK KODE BUKU DIINPUT DATA, MAKA OTOMATIS FORM TERISI
if(isset($_POST['txtKodeBuku'])) {
	$mySql ="SELECT buku.judul_buku, buku_inventaris.* FROM buku_inventaris LEFT JOIN buku ON buku_inventaris.kd_buku = buku.kd_buku 
				WHERE buku_inventaris.kd_inventaris ='$dataKodeBuku'";
	$myQry = mysql_query($mySql, $koneksidb) or die ("Gagal Query".mysql_error());
	if(mysql_num_rows($myQry) >=1) {
		$myData= mysql_fetch_array($myQry);
		$dataKodeBuku	= $myData['kd_inventaris'];
		$dataJudulBuku	= $myData['judul_buku'];
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
      <td colspan="3"><h1>PEMINJAMAN BUKU </h1></td>
    </tr>
    <tr>
      <td bgcolor="#CCCCCC"><strong>DATA TRANSAKSI </strong></td>
      <td bgcolor="#CCCCCC">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td width="26%"><strong>No. Peminjaman </strong></td>
      <td width="2%"><strong>:</strong></td>
      <td width="72%"><input name="txtNomor" value="<?php echo $noTransaksi; ?>" size="23" maxlength="20" readonly="readonly"/></td>
    </tr>
    <tr>
      <td><strong>Tgl. Peminjaman </strong></td>
      <td><strong>:</strong></td>
      <td><input type="text" name="txtTglPinjam" class="tcal" value="<?php echo $dataTglPinjam; ?>" /></td>
    </tr>
    <tr>
      <td><strong>Tgl. Harus Kembali </strong></td>
      <td><strong>:</strong></td>
      <td><input type="text" name="txtTglKembali" class="tcal" value="<?php echo $dataTglKembali; ?>" /></td>
    </tr>
    <tr>
      <td bgcolor="#CCCCCC"><strong>DATA MAHASISWA </strong></td>
      <td bgcolor="#CCCCCC">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><strong>NIM</strong></td>
      <td><strong>:</strong></td>
      <td><input type="text" name="txtNim"  id="txtNim" value="<?php echo $dataNim; ?>" onChange="javascript:submitform();"/>
      <b><a href="javaScript: void(0)" onclick="popup('pencarian_mhs.php')" target="_self">Cari Mhs </a></b></td>
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
      <td bgcolor="#CCCCCC"><strong>INPUT  BUKU </strong></td>
      <td bgcolor="#CCCCCC">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><strong>Kode  Buku </strong></td>
      <td><strong>:</strong></td>
      <td><input name="txtKodeBuku" id="txtKodeBuku" value="<?php echo $dataKodeBuku; ?>" size="20" maxlength="20" onChange="javascript:submitform();"/>
      <input name="btnTambah" type="submit" style="cursor:pointer;" value=" Tambah " />
	  <b><a href="javaScript: void(0)" onclick="popup('pencarian_buku.php')" target="_self">Cari Buku </a></b>
      </td>
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
      <td><input name="btnSimpan" type="submit" style="cursor:pointer;" value=" SIMPAN PINJAMAN " /></td>
    </tr>
  </table>
  <br>
</form>
