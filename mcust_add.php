<?php
include_once "library/inc.seslogin.php";
include_once "library/inc.connection.php";
include_once "library/inc.library.php";

# SKRIP SAAT TOMBOL SIMPAN DIKLIK
if(isset($_POST['btnSimpan'])){
	# Membaca Variabel Form
	$txtKode			= $_POST['txtKode'];
	$txtNim				= $_POST['txtNim'];
	$txtNama			= $_POST['txtNama'];
	$cmbKelamin			= $_POST['cmbKelamin'];
	$cmbAgama			= $_POST['cmbAgama'];
	$txtTempatLahir		= $_POST['txtTempatLahir'];
//	$txtTanggal			= InggrisTgl($_POST['txtTanggal']);
	$txtAlamat			= $_POST['txtAlamat'];
	$txtNoTelepon		= $_POST['txtNoTelepon'];
//	$cmbAngkatan		= $_POST['cmbAngkatan'];
	$cmbStatus			= $_POST['cmbStatus'];
	
	
	# Validasi form, jika kosong sampaikan pesan error
	$pesanError = array();
	if (trim($txtNama)=="") {
		$pesanError[] = "Data <b>Nama Customer </b> tidak boleh kosong !";		
	}
	else {
		# Validasi NIM Mahasiswa, jika sudah ada akan ditolak
		$cekSql	= "SELECT * FROM mcust WHERE kode='$txtKode'";
		$cekQry	= pg_query($koneksidb, $cekSql) or die ("Eror Query".pg_last_error()); 
		if(pg_num_rows($cekQry)>=1){
			$pesanError[] = "Maaf, Kode <b> $txtKode </b> sudah terpakai, ganti dengan yang lain";
		}
	}
	if (trim($cmbAgama)=="Kosong") {
		$pesanError[] = "Data <b>Initial</b> tidak boleh kosong !";		
	}	
//	if (trim($txtTempatLahir)=="") {
//		$pesanError[] = "Data <b>Tempat Lahir</b> tidak boleh kosong !";		
//	}

//	if (trim($txtTanggal)=="") {
//			$pesanError[] = "Data <b>Tgl. Lahir</b> tidak boleh kosong !";			
//	}
	if (trim($txtAlamat)=="") {
		$pesanError[] = "Data <b>Alamat Lengkap</b> tidak boleh kosong !";		
	}
	if (trim($txtNoTelepon)=="") {
		$pesanError[] = "Data <b>No. Telepon</b> tidak boleh kosong !";		
	}
//	if(trim($cmbAngkatan)=="Kosong") {
//		$pesanError[] = "Data <b>Tahun Angkatan</b> belum dipilih !";		
//	}
	if (trim($cmbStatus)=="Kosong") {
		$pesanError[] = "Data <b>Kategori</b> tidak boleh kosong !";		
	}	
	
	# Menampilkan pesan Error dari validasi
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
	//	$kodeBaru	= buatKode("mahasiswa", "M");


		# SKRIP UNTUK MENYIMPAN FOTO/GAMBAR
		if (! empty($_FILES['namaFile']['tmp_name'])) {
			// Membaca nama file foto/gambar
			$file_foto = $_FILES['namaFile']['name'];
			$file_foto = stripslashes($file_foto);
			$file_foto = str_replace("'","",$file_foto);
			
			// Simpan gambar
			$file_foto = $kodeBaru.".".$file_foto;
			copy($_FILES['namaFile']['tmp_name'],"foto/".$file_foto);
		}
		else {
			// Jika tidak ada foto/gambar
			$file_foto = "";
		}
		
		# Skrip untuk menyimpan data ke database
		$mySql	= "INSERT INTO mcust (kode, nama, cpkp, lnama, alamat,kontak,kota, 
									  telp, cstatus) 
					VALUES('$txtKode', '$txtNama', '$cmbKelamin', '$cmbAgama', '$txtAlamat', '$txtNim', '$txtTempatLahir',
					                  '$txtNoTelepon','$cmbStatus')";
		$myQry	= pg_query($koneksidb, $mySql) or die ("Gagal query".pg_last_error());
		if($myQry){
			echo "<meta http-equiv='refresh' content='0; url=?open=Mcust-Data'>";
		}
		exit;
	}	
}

# VARIABEL DATA UNTUK DIBACA FORM
// Supaya saat ada pesan error, data di dalam form tidak hilang. Jadi, tinggal meneruskan/memperbaiki yg salah

// Data untuk form, datanya dari dalam Form itu sendiri
$dataKode		= isset($_POST['txtKode']) ? $_POST['txtKode'] : '';  //''; // buatKode("mahasiswa", "M");
$dataNim		= isset($_POST['txtNim']) ? $_POST['txtNim'] : '';
$dataNama		= isset($_POST['txtNama']) ? $_POST['txtNama'] : '';
$dataKelamin	= isset($_POST['cmbKelamin']) ? $_POST['cmbKelamin'] : '';
$dataAgama		= isset($_POST['cmbAgama']) ? $_POST['cmbAgama'] : '';
$dataTempatLahir= isset($_POST['txtTempatLahir']) ? $_POST['txtTempatLahir'] : '';
$dataTanggal	= isset($_POST['txtTanggal']) ? $_POST['txtTanggal'] : '';
$dataAlamat		= isset($_POST['txtAlamat']) ? $_POST['txtAlamat'] : '';
$dataNoTelepon	= isset($_POST['txtNoTelepon']) ? $_POST['txtNoTelepon'] : '';
$dataAngkatan	= isset($_POST['cmbAngkatan']) ? $_POST['cmbAngkatan'] : date('Y');
$dataStatus		= isset($_POST['cmbStatus']) ? $_POST['cmbStatus'] : '';

?>
<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" name="form1" target="_self" enctype="multipart/form-data" >
  <table width="100%" class="table-list" border="0" cellpadding="4" cellspacing="1">
    <tr>
      <th colspan="3" scope="col">TAMBAH DATA CUSTOMER </th>
    </tr>
    
    <tr>
      <td width="231"><strong>Kode</strong></td>
      <td width="5"><strong>:</strong></td>
      <td width="967"><input name="txtKode" value="<?php echo $dataKode; ?>" size="10" maxlength="10" /></td>
    </tr>
<!--    
    
    <tr>
      <td width="231"><strong>Kode</strong></td>
      <td width="5"><strong>:</strong></td>
      <td width="967"><input name="textfield" value="<?php echo $dataKode; ?>" size="10" maxlength="10" readonly="readonly"/></td>
    </tr>

-->

    <tr>
      <td><b>Inital</b></td>
      <td><strong>:</strong></td>
      <td><b>
        <select name="cmbAgama">
          <option value="Kosong">....</option>
          <?php
		  $pilihan	= array("PT", "CV", "UD", "Bpk", "Ibu");
          foreach ($pilihan as $nilai) {
            if ($dataAgama==$nilai) {
                $cek=" selected";
            } else { $cek = ""; }
            echo "<option value='$nilai' $cek>$nilai</option>";
          }
          ?>
        </select>
      </b></td>
    </tr>
    
    <tr>
      <td><strong>Nama </strong></td>
      <td><strong>:</strong></td>
      <td><input name="txtNama" value="<?php echo $dataNama; ?>" size="70" maxlength="100" /></td>
    </tr>
    <tr>
      <td><strong>PKP</strong></td>
      <td><strong>:</strong></td>
      <td><b>
        <select name="cmbKelamin">
          <?php
		  $pilihan	= array("P" => "PKP (P)", "N" => "Non PKP (N)");
          foreach ($pilihan as $nilai => $judul) {
            if ($dataKelamin==$nilai) {
                $cek=" selected";
            } else { $cek = ""; }
            echo "<option value='$nilai' $cek> $judul</option>";
          }
          ?>
        </select>
      </b></td>
    </tr>

    <tr>
      <td><strong>Alamat </strong></td>
      <td><strong>:</strong></td>
      <td><input name="txtAlamat" type="text" value="<?php echo $dataAlamat; ?>" size="80" maxlength="200" /></td>
    </tr>
    <tr>
      <td><b>Kota </b></td>
      <td><strong>:</strong></td>
      <td><input name="txtTempatLahir" type="text" value="<?php echo $dataTempatLahir; ?>" size="50" maxlength="100" />
    </tr>

    <tr>
      <td><strong>No. Telepon </strong></td>
      <td><strong>:</strong></td>
      <td><input name="txtNoTelepon" value="<?php echo $dataNoTelepon; ?>" size="40" maxlength="20" /></td>
    </tr>

    <tr>
      <td><strong>Kontak Person </strong></td>
      <td><strong>:</strong></td>
      <td><input name="txtNim" value="<?php echo $dataNim; ?>" size="30" maxlength="20" /></td>
    </tr>
    

<!--    
    <tr>
      <td><b>Agama</b></td>
      <td><strong>:</strong></td>
      <td><b>
        <select name="cmbAgama">
          <option value="Kosong">....</option>
          <?php
		  $pilihan	= array("Islam", "Kristen", "Katolik", "Hindu", "Budha");
          foreach ($pilihan as $nilai) {
            if ($dataAgama==$nilai) {
                $cek=" selected";
            } else { $cek = ""; }
            echo "<option value='$nilai' $cek>$nilai</option>";
          }
          ?>
        </select>
      </b></td>
    </tr>

    
    <tr>
      <td><b>NPWP </b></td>
      <td><strong>:</strong></td>
      <td><input name="txtTempatLahir" type="text" value="<?php echo $dataTempatLahir; ?>" size="50" maxlength="100" />
      , 
      <input type="text" name="txtTanggal" class="tcal" value="<?php echo $dataTanggal; ?>" /></td>
    </tr>

    <tr>
      <td><strong>Foto Mahasiswa </strong></td>
      <td><strong>:</strong></td>
      <td><input name="namaFile" type="file" size="60" /></td>
    </tr>
    <tr>
      <td><strong>Tahun Angkatan </strong></td>
      <td><b>:</b></td>
      <td><select name="cmbAngkatan">
          <option value="Kosong">....</option>
          <?php 
		for ($thn = date('Y') - 4; $thn <= date('Y'); $thn++) {
			if($thn==$dataAngkatan) { $cek=" selected";} else { $cek="";}
			echo "<option value='$thn' $cek>$thn</option>";
		}
		?>
      </select></td>
    </tr>
-->
    
    <tr>
      <td><b>Kategori</b></td>
      <td><strong>:</strong></td>
      <td><b>
        <select name="cmbStatus">
          <option value="Kosong">....</option>
          <?php
		  $pilihan	= array("1.Kredit","2.DP 50%","3.Full Bayar","4.DP 30%");
          foreach ($pilihan as $nilai) {
            if ($dataStatus==$nilai) {
                $cek=" selected";
            } else { $cek = ""; }
            echo "<option value='$nilai' $cek>$nilai</option>";
          }
          ?>
        </select>
      </b></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><input type="submit" name="btnSimpan" value=" SIMPAN "></td>
    </tr>
  </table>
</form>
