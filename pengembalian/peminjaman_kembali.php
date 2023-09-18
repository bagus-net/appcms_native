<?php
include_once "../library/inc.seslogin.php";

# MEMBACA USER YANG LOGIN
$kodeUser	= $_SESSION['SES_LOGIN'];

# JIKA TOMBOL SIMPAN TRANSAKSI DIKLIK
if(isset($_POST['btnSimpan'])){
	# Baca variabel data from
	$txtKode		= $_POST['txtKode'];
	$txtKodeBuku	= $_POST['txtKodeBuku'];
	$txtKodeSiswa	= $_POST['txtKodeSiswa'];
	$txtTglHarusKembali 	= InggrisTgl($_POST['txtTglHarusKembali']);
	$txtTglKembali 	= InggrisTgl($_POST['txtTglKembali']);
	$txtDenda		= $_POST['txtDenda'];
	$txtKeterangan	= $_POST['txtKeterangan'];
	
	# Validasi Kotak isi Form
	$pesanError = array();
	if (trim($txtKode)=="") {
		$pesanError[] = "Data <b>Kode (No Peminjaman)</b> tidak terbaca  !";		
	}
	if (trim($txtTglKembali)=="") {
		$pesanError[] = "Data <b>Tgl. Kembali</b> belum diisi, pilih pada kalender !";		
	}
	if (trim($txtDenda)=="" or ! is_numeric(trim($txtDenda))) {
		$pesanError[] = "Data <b>Besar Denda (Rp)</b> belum diisi (harus diisi angka), beri nilai 0 jika tidak ada denda !";		
	}
	if (trim($txtKeterangan)=="") {
		$pesanError[] = "Data <b>Keterangan</b> belum diisi, silahkan dilengkapi !";		
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

		# Periksa apakah sudah Pengembalian
		$cek2Sql ="SELECT * FROM pengembalian WHERE no_peminjaman='$txtKode'";
		$cek2Qry = mysql_query($cek2Sql, $koneksidb) or die ("Gagal Query Tmp".mysql_error());
		if (mysql_num_rows($cek2Qry) >= 1) {
			// Update data pengembaliannya
			$mySql	= "UPDATE pengembalian SET tgl_kembali = '$txtTglKembali', denda = '$txtDenda', 
						keterangan = '$txtKeterangan', kd_user = '$kodeUser'
						WHERE no_peminjaman ='$txtKode' ";
			mysql_query($mySql, $koneksidb) or die ("Gagal query edit".mysql_error());			
		}
		else {
			// Buat data baru
			$noTransaksi = buatKode("pengembalian", "PK");
			$mySql	= "INSERT INTO pengembalian (no_pengembalian, no_peminjaman, tgl_kembali, denda, keterangan, kd_user)
						VALUES ('$noTransaksi', '$txtKode', '$txtTglKembali', '$txtDenda', '$txtKeterangan', '$kodeUser')";
			mysql_query($mySql, $koneksidb) or die ("Gagal query simpan".mysql_error());
			
			// Update status pinjam pada tabel peminjaman
			$updateSql = "UPDATE peminjaman SET status_pinjam = 'Kembali' WHERE no_peminjaman='$txtKode'";
			mysql_query($updateSql, $koneksidb) or die ("Gagal update status pinjam ".mysql_error());
			
			// Update status pinjam pada tabel mahasiswa
			$updateSql = "UPDATE mahasiswa SET status_pinjam = 'Bebas' WHERE kd_mahasiswa='$txtKodeSiswa'";
			mysql_query($updateSql, $koneksidb) or die ("Gagal update status pinjam ".mysql_error()); 

			// Update status buku (Tersedia artinya Tidak Dipinjam)
			$editSql = "UPDATE buku_inventaris SET status ='Tersedia' WHERE kd_inventaris='$txtKodeBuku'";
			mysql_query($editSql, $koneksidb) or die ("Gagal Query ".mysql_error());
		}
		// Refresh form
		echo "<meta http-equiv='refresh' content='0; url=?open=Peminjaman-Tampil'>";
	}	
}

# TAMPILKAN DATA KE FORM
$Kode	 = $_GET['Kode']; 
$mySql	 = "SELECT peminjaman.*, buku.judul_buku FROM peminjaman 
			LEFT JOIN buku_inventaris ON peminjaman.kd_inventaris = buku_inventaris.kd_inventaris
			LEFT JOIN buku ON buku_inventaris.kd_buku = buku.kd_buku 
			WHERE peminjaman.no_peminjaman = '$Kode'";
$myQry	 = mysql_query($mySql, $koneksidb)  or die ("Query data salah: ".mysql_error());
$myData	 = mysql_fetch_array($myQry);

// Jika Status sudah dikembalikan, maka halaman beralih ke halaman utama
if($myData['status_pinjam'] =="Kembali") {
	echo "PEMINJAMAN SUDAH DIKEMBALIKAN ";
	
	// Refresh form
	echo "<meta http-equiv='refresh' content='1; url=?open=Peminjaman-Tampil'>";
	exit;
}

	$dataNomor		= buatKode("pengembalian", "PK");
	$dataKode 		= isset($_POST['txtKode']) ? $_POST['txtKode'] : $myData['no_peminjaman'];
	$dataKodeSiswa	= isset($_POST['txtKodeSiswa']) ? $_POST['txtKodeSiswa'] : $myData['kd_mahasiswa'];
	$dataTglPeminjaman 	= isset($_POST['txtTglPeminjaman']) ? $_POST['txtTglPeminjaman'] : IndonesiaTgl($myData['tgl_peminjaman']);
	$dataTglHarusKembali 	= isset($_POST['txtTglHarusKembali']) ? $_POST['txtTglHarusKembali'] : IndonesiaTgl($myData['tgl_harus_kembali']);
	$dataTglKembali = isset($_POST['txtTglKembali']) ? $_POST['txtTglKembali'] : date('d-m-Y');
	$dataDenda		= isset($_POST['txtDenda']) ? $_POST['txtDenda'] : '';
	$dataKeterangan	= isset($_POST['txtKeterangan']) ? $_POST['txtKeterangan'] : '';
?>
<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" name="form1" target="_self">
  <table width="800" cellpadding="3" cellspacing="1"  class="table-list">
    <tr>
      <td colspan="3"><h1>PENGEMBALIAN BUKU </h1></td>
    </tr>
    <tr>
      <td bgcolor="#CCCCCC"><strong>DATA TRANSAKSI </strong></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td width="26%"><strong>No. Peminjaman </strong></td>
      <td width="2%"><strong>:</strong></td>
      <td width="72%"><input name="txtNomor" value="<?php echo $dataNomor; ?>" size="23" maxlength="20" readonly="readonly"/>
          <input name="txtKode" type="hidden" value="<?php echo $dataKode; ?>" />
          <input name="txtKodeSiswa" type="hidden" value="<?php echo $dataKodeSiswa; ?>" /></td>
    </tr>
    <tr>
      <td><strong>Tgl. Peminjaman </strong></td>
      <td><strong>:</strong></td>
      <td><input type="text" name="txtTglPeminjaman" class="tcal" value="<?php echo $dataTglPeminjaman; ?>" /></td>
    </tr>
    <tr>
      <td><strong>Tgl. Tempo </strong></td>
      <td><strong>:</strong></td>
      <td><input type="text" name="txtTglHarusKembali" class="tcal" value="<?php echo $dataTglHarusKembali; ?>" /></td>
    </tr>
    <tr>
      <td><strong>Tgl. Kembali </strong></td>
      <td><strong>:</strong></td>
      <td><input type="text" name="txtTglKembali" class="tcal" value="<?php echo $dataTglKembali; ?>" /></td>
    </tr>
    <tr>
      <td bgcolor="#CCCCCC"><strong>DATA BUKU </strong></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><strong>Kode</strong></td>
      <td><strong>:</strong></td>
      <td><?php echo $myData['kd_inventaris']; ?>
      <input name="txtKodeBuku" type="hidden" value="<?php echo  $myData['kd_inventaris']; ?>" /></td>
    </tr>
    <tr>
      <td><strong>Judul Buku </strong></td>
      <td><strong>:</strong></td>
      <td><?php echo $myData['judul_buku']; ?></td>
    </tr>

    <tr>
      <td bgcolor="#CCCCCC"><strong>DENDA</strong></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><strong>Denda (Rp.) </strong></td>
      <td><strong>:</strong></td>
      <td><input type="text" name="txtDenda" value="<?php echo $dataDenda; ?>" /></td>
    </tr>
    <tr>
      <td><strong>Keterangan</strong></td>
      <td><strong>:</strong></td>
      <td><input name="txtKeterangan" value="<?php echo $dataKeterangan; ?>" size="80" maxlength="100" /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><input name="btnSimpan" type="submit" style="cursor:pointer;" value=" SIMPAN " /></td>
    </tr>
  </table>
  <br>
</form>
