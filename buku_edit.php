<?php
include_once "library/inc.seslogin.php";
include_once "library/inc.connection.php";
include_once "library/inc.library.php";

# SKRIP SAAT TOMBOL SIMPAN DIKLIK
if(isset($_POST['btnSimpan'])){
	# Membaca Variabel Form
	$txtJudulBuku		= $_POST['txtJudulBuku'];
	$txtIsbn			= $_POST['txtIsbn'];
	$txtPenulis			= $_POST['txtPenulis'];
	$cmbPenerbit		= $_POST['cmbPenerbit'];
	$txtTahunTerbit		= $_POST['txtTahunTerbit'];
	$txtJumlahHalaman	= $_POST['txtJumlahHalaman'];
	$txtBonus			= $_POST['txtBonus'];
	$txtBahasa			= $_POST['txtBahasa'];	
	$txtJumlah			= $_POST['txtJumlah'];
	$txtSinopsis		= $_POST['txtSinopsis'];
	$txtLokasiRak		= $_POST['txtLokasiRak'];
	$cmbKategori		= $_POST['cmbKategori'];
	
	
	# Validasi form, jika kosong sampaikan pesan error
	$pesanError = array();
	if (trim($txtJudulBuku)=="") {
		$pesanError[] = "Data <b>Judul Buku</b> tidak boleh kosong !";		
	}
	if (trim($txtIsbn)=="") {
		$pesanError[] = "Data <b>ISBN</b> tidak boleh kosong !";		
	}
	if (trim($txtPenulis)=="") {
		$pesanError[] = "Data <b>Penulis</b> tidak boleh kosong !";		
	}		
	if (trim($cmbPenerbit)=="Kosong") {
		$pesanError[] = "Data <b>Kode Penerbit</b> tidak boleh kosong !";		
	}
	if (trim($txtTahunTerbit)=="") {
		$pesanError[] = "Data <b>Tahun Terbit</b> tidak boleh kosong !";		
	}	
	if ($txtTahunTerbit > date('Y')) {
		$pesanError[] = "Data <b>Tahun terbit maksimal tahun sekarang ".date('Y')."</b>, tidak boleh lebih !";		
	}	
	if (trim($txtJumlahHalaman)=="") {
		$pesanError[] = "Data <b>Jumlah Halaman</b> tidak boleh kosong !";		
	}	
	if (trim($txtBonus)=="") {
		$pesanError[] = "Data <b>Bonus</b> tidak boleh kosong !";		
	}	
	if (trim($txtBahasa)=="") {
		$pesanError[] = "Data <b>Bahasa</b> tidak boleh kosong !";		
	}
	if (trim($txtJumlah)==""  or ! is_numeric(trim($txtJumlah))) {
		$pesanError[] = "Data <b>Jumlah Buku</b> tidak boleh kosong, harus berisi angka !";	
	}
	if ($txtJumlah < 1 ) {
		$pesanError[] = "Data <b>Jumlah Buku Minimal 1</b>, tidak boleh kurang dari 1 !";		
	}
	if (trim($txtSinopsis)=="") {
		$pesanError[] = "Data <b>Sinopsis</b> tidak boleh kosong !";		
	}	
	if (trim($txtLokasiRak)=="") {
		$pesanError[] = "Data <b>LokasiRak</b> tidak boleh kosong !";		
	}	
	if (trim($cmbKategori)=="Kosong") {
		$pesanError[] = "Data <b>Kategori 1</b> belum ada yang dipilih !";		
	}
	
	# JIKA ADA PESAN ERROR DARI VALIDASI
	if (count($pesanError)>=1 ){
		echo "<div class='mssgBox'>";
		echo "<img src='images/attention.png'> <br><hr>";
			$noPesan=0;
			foreach ($pesanError as $indeks=>$pesan_tampil) { 
				$noPesan++;
				echo "&nbsp;&nbsp; $noPesan. $pesan_tampil<br>";	
			} 
		echo "</div> <br>"; 
	}
	else {
		# SIMPAN DATA KE DATABASE. 
		// Jika tidak menemukan error, simpan data ke database
		
		# baca, keadaan gambar
		if (empty($_FILES['namaFile']['name'])) {
			$nama_file = $_POST['txtNamaFileH'];
		}
		else  {
			// Jika file gambar lama ada, akan dihapus
			if(file_exists("cover/".$_POST['txtNamaFileH'])) {
				unlink("cover/".$_POST['txtNamaFileH']);	
			}

			// Jika gambar lama kosong, atau ada gambar baru, maka Mengkopi file gambar
			$nama_file = $_FILES['namaFile']['name'];
			$nama_file = stripslashes($nama_file);
			$nama_file = str_replace("'","",$nama_file);
			$nama_file = $_POST['txtKode'].".".$nama_file;
			copy($_FILES['namaFile']['tmp_name'],"cover/".$nama_file);					
		}
		
		$tanggalMasuk	= date('Y-m-d');
		
		// Simpan data
		$Kode	= $_POST['txtKode'];
		$mySql	= "UPDATE buku SET judul_buku='$txtJudulBuku', isbn='$txtIsbn', 
					penulis='$txtPenulis', kd_penerbit='$cmbPenerbit', tahun_terbit='$txtTahunTerbit', 
					jumlah_halaman='$txtJumlahHalaman',bonus='$txtBonus', 
					bahasa='$txtBahasa', jumlah='$txtJumlah', file_gambar='$nama_file', sinopsis='$txtSinopsis', 
					lokasi_rak='$txtLokasiRak', kd_kategori='$cmbKategori'
					WHERE kd_buku ='$Kode'";
		$myQry	= mysql_query($mySql, $koneksidb) or die ("Gagal query".mysql_error());
		if($myQry){
			// KODE INVENTARIS KOLEKSI BUKU
			$txtJumlahLama		= $_POST['txtJumlahLama'];
			
			// Jika Ada penambahan jumlah buku baru
			if($txtJumlah > $txtJumlahLama) {
				$jumlahBaru			= $txtJumlah - $txtJumlahLama;
				
				// Membuat Kode koleksi Buku (Kode Inventaris)
				for($i =1; $i <= $jumlahBaru; $i++) {
					$kodeInventaris = buatKodeInventaris("buku_inventaris", $Kode.".", $Kode);
					
					$mySql	= "INSERT INTO buku_inventaris(kd_inventaris, kd_buku, tanggal_masuk, status) VALUES('$kodeInventaris', '$Kode', '$tanggalMasuk', 'Tersedia')";
					mysql_query($mySql, $koneksidb) or die ("Gagal query 2".mysql_error());
				}
			}
			
			// Jika Ada Pengurangan jumlah buku baru
			if($txtJumlah < $txtJumlahLama) {
				$jumlahHilang		= $txtJumlahLama - $txtJumlah;
				
				// Mengambil data terakhir, sebanyak selisih jumlah yang akan dihapus
				$mySql	= "SELECT * FROM buku_inventaris WHERE kd_buku='$Kode' ORDER BY kd_inventaris DESC LIMIT $jumlahHilang";
				$myQry  = mysql_query($mySql, $koneksidb) or die ("Gagal query 3".mysql_error());
				while($myData = mysql_fetch_array($myQry)) {
					$kodeInv= $myData['kd_inventaris'];
					$mySql	= "DELETE FROM buku_inventaris WHERE kd_inventaris ='$kodeInv'";
					mysql_query($mySql, $koneksidb) or die ("Gagal hapus inventaris".mysql_error());
				}
			}
			
			echo "<meta http-equiv='refresh' content='0; url=?open=Buku-Data'>";
		}
		exit;
	}	
}

# TAMPILKAN DATA LOGIN UNTUK DIEDIT
$Kode	 = $_GET['Kode']; 
$mySql	 = "SELECT * FROM buku WHERE kd_buku='$Kode'";
$myQry	 = mysql_query($mySql, $koneksidb)  or die ("Query data salah: ".mysql_error());
$myData	 = mysql_fetch_array($myQry);

	// Menyimpan data ke variabel temporary (sementara)
	$dataKode				= $myData['kd_buku'];
	$dataJudulBuku			= isset($_POST['txtJudulBuku']) ? $_POST['txtJudulBuku'] : $myData['judul_buku'];
	$dataIsbn				= isset($_POST['txtIsbn']) ? $_POST['txtIsbn'] : $myData['isbn'];
	$dataPenulis			= isset($_POST['txtPenulis']) ? $_POST['txtPenulis'] : $myData['penulis'];
	$dataPenerbit			= isset($_POST['cmbPenerbit']) ? $_POST['cmbPenerbit'] : $myData['kd_penerbit'];
	$dataTahunTerbit		= isset($_POST['txtTahunTerbit']) ? $_POST['txtTahunTerbit'] : $myData['tahun_terbit'];
	$dataJumlahHalaman		= isset($_POST['txtJumlahHalaman']) ? $_POST['txtJumlahHalaman'] : $myData['jumlah_halaman'];
	$dataBonus				= isset($_POST['txtBonus']) ? $_POST['txtBonus'] : $myData['bonus'];
	$dataBahasa				= isset($_POST['txtBahasa']) ? $_POST['txtBahasa'] : $myData['bahasa'];
	$dataJumlah				= isset($_POST['txtJumlah']) ? $_POST['txtJumlah'] : $myData['jumlah'];
	$dataGambar				= $myData['file_gambar'];
	$dataSinopsis			= isset($_POST['txtSinopsis']) ? $_POST['txtSinopsis'] : $myData['sinopsis'];
	$dataLokasiRak			= isset($_POST['txtLokasiRak']) ? $_POST['txtLokasiRak'] : $myData['lokasi_rak'];
	$dataKategori			= isset($_POST['cmbKategori']) ? $_POST['cmbKategori'] : $myData['kd_kategori'];
?>
<SCRIPT language="JavaScript">
function submitform() {
	document.form1.submit();
}
</SCRIPT>
<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" name="form1" target="_self" enctype="multipart/form-data" >
  <table width="100%" class="table-list" border="0" cellpadding="4" cellspacing="1">
    <tr>
      <th colspan="3" scope="col">UBAH DATA BUKU </th>
    </tr>
    <tr>
      <td width="181"><strong>Kode</strong></td>
      <td width="3">:</td>
      <td width="1019"><input name="textfield" value="<?php echo $dataKode; ?>" size="10" maxlength="10" readonly="readonly"/>
      <input name="txtKode" type="hidden" value="<?php echo $dataKode; ?>" /></td>
    </tr>
    <tr>
      <td><b>Judul Buku </b></td>
      <td><b>:</b></td>
      <td><input name="txtJudulBuku" type="text"     value="<?php echo $dataJudulBuku; ?>" size="80" maxlength="200" /></td>
    </tr>
    <tr>
      <td><strong>ISBN</strong></td>
      <td><b>:</b></td>
      <td><input name="txtIsbn" type="text" value="<?php echo $dataIsbn; ?>" size="40" maxlength="40" /></td>
    </tr>
    <tr>
      <td><strong>Penulis</strong></td>
      <td>:</td>
      <td><input name="txtPenulis" value="<?php echo $dataPenulis; ?>" size="80" maxlength="100" /></td>
    </tr>
    <tr>
      <td><b>Penerbit</b></td>
      <td><b>:</b></td>
      <td><select name="cmbPenerbit">
          <option value="Kosong">....</option>
          <?php
	  $bacaSql = "SELECT * FROM Penerbit ORDER BY kd_penerbit";
	  $bacaQry = mysql_query($bacaSql, $koneksidb) or die ("Gagal Query".mysql_error());
	  while ($bacaRow = mysql_fetch_array($bacaQry)) {
	  	if ($dataPenerbit == $bacaRow['kd_penerbit']) {
			$cek = " selected";
		} else { $cek=""; }
	  	echo "<option value='$bacaRow[kd_penerbit]' $cek>$bacaRow[nm_penerbit]</option>";
	  }
	  ?>
      </select></td>
    </tr>
    <tr>
      <td><b>Tahun Terbit </b></td>
      <td><b>:</b></td>
      <td><input name="txtTahunTerbit" type="text" value="<?php echo $dataTahunTerbit; ?>" size="10" maxlength="4" /></td>
    </tr>
    <tr>
      <td><b>Jumlah Halaman </b></td>
      <td><b>:</b></td>
      <td><input name="txtJumlahHalaman" type="text" value="<?php echo $dataJumlahHalaman; ?>" size="10" maxlength="4" /></td>
    </tr>
    <tr>
      <td><strong>Bonus Penyerta </strong></td>
      <td><b>:</b></td>
      <td><input name="txtBonus" type="text"  value="<?php echo $dataBonus; ?>" size="60" maxlength="100" /></td>
    </tr>
    <tr>
      <td><strong>Bahasa</strong></td>
      <td><b>:</b></td>
      <td><input name="txtBahasa" type="text" value="<?php echo $dataBahasa; ?>" size="60" maxlength="100" /></td>
    </tr>
    <tr>
      <td><b>Jumlah</b></td>
      <td><b>:</b></td>
      <td><input name="txtJumlah" type="text"   value="<?php echo $dataJumlah; ?>" size="10" maxlength="4" />
      <input name="txtJumlahLama" type="hidden" value="<?php echo $myData['jumlah']; ?>" /></td>
    </tr>
    <tr>
      <td><b>File Gambar </b></td>
      <td><b>:</b></td>
      <td><input name="namaFile" type="file" id="namaFile" size="50" />
      <input name="txtNamaFileH" type="hidden" value="<?php echo $dataGambar; ?>" /></td>
    </tr>
    <tr>
      <td><b>Sinopsis</b></td>
      <td><b>:</b></td>
      <td><textarea name="txtSinopsis" id="elm1" cols="60" rows="10"><?php echo $dataSinopsis; ?></textarea></td>
    </tr>
    <tr>
      <td><b>Lokasi Rak </b></td>
      <td><b>:</b></td>
      <td><input name="txtLokasiRak" type="text" value="<?php echo $dataLokasiRak; ?>" size="20" maxlength="20" /></td>
    </tr>
    <tr>
      <td><b>Kategori</b></td>
      <td><b>:</b></td>
      <td>
	    <select name="cmbKategori" >
          <option value="Kosong">....</option>
          <?php
	  $bacaSql = "SELECT * FROM kategori ORDER BY kd_kategori";
	  $bacaQry = mysql_query($bacaSql, $koneksidb) or die ("Gagal Query".mysql_error());
	  while ($bacaRow = mysql_fetch_array($bacaQry)) {
	  	if ($dataKategori == $bacaRow['kd_kategori']) {
			$cek = " selected";
		} else { $cek=""; }
	  	echo "<option value='$bacaRow[kd_kategori]' $cek>$bacaRow[nm_kategori]</option>";
	  }
	  ?>
      </select></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><input type="submit" name="btnSimpan" value=" SIMPAN "></td>
    </tr>
  </table>
</form>
