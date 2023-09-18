<?php
include_once "library/inc.seslogin.php";
include_once "library/inc.connection.php";
include_once "library/inc.library.php";

# SKRIP SAAT TOMBOL SIMPAN DIKLIK
if(isset($_POST['btnSimpan'])){
	# Membaca Variabel Form
	$txtNim				= $_POST['txtNim'];
	$txtNama			= $_POST['txtNama'];
	$cmbKelamin			= $_POST['cmbKelamin'];
	$cmbAgama			= $_POST['cmbAgama'];
	$txtTempatLahir		= $_POST['txtTempatLahir'];
	$txtTanggal			= InggrisTgl($_POST['txtTanggal']);
	$txtAlamat			= $_POST['txtAlamat'];
	$txtNoTelepon		= $_POST['txtNoTelepon'];
	$cmbAngkatan		= $_POST['cmbAngkatan'];
	$cmbStatus			= $_POST['cmbStatus'];
	
	
	# Validasi form, jika kosong sampaikan pesan error
	$pesanError = array();
	if (trim($txtNim)=="") {
		$pesanError[] = "Data <b>NIM Mahasiswa</b> tidak boleh kosong !";		
	}
	else {
		# Validasi NIM Mahasiswa, jika sudah ada akan ditolak
		$Kode	= $_POST['txtKode'];
		$cekSql	= "SELECT * FROM mahasiswa WHERE nim='$txtNim' AND NOT(kd_mahasiswa='$Kode')";
		$cekQry	= mysql_query($cekSql, $koneksidb) or die ("Eror Query".mysql_error()); 
		if(mysql_num_rows($cekQry)>=1){
			$pesanError[] = "Maaf, NIM <b> $txtNim </b> sudah terpakai, ganti dengan yang lain";
		}
	}
	if (trim($txtNama)=="") {
		$pesanError[] = "Data <b>Nama Mahasiswa</b> tidak boleh kosong !";		
	}
	if (trim($cmbAgama)=="Kosong") {
		$pesanError[] = "Data <b>Agama</b> tidak boleh kosong !";		
	}	
	if (trim($txtTempatLahir)=="") {
		$pesanError[] = "Data <b>Tempat Lahir</b> tidak boleh kosong !";		
	}
	if (trim($txtTanggal)=="") {
			$pesanError[] = "Data <b>Tgl. Lahir</b> tidak boleh kosong !";			
	}
	if (trim($txtAlamat)=="") {
		$pesanError[] = "Data <b>Alamat Lengkap</b> tidak boleh kosong !";		
	}
	if (trim($txtNoTelepon)=="") {
		$pesanError[] = "Data <b>No. Telepon</b> tidak boleh kosong !";		
	}
	if(trim($cmbAngkatan)=="Kosong") {
		$pesanError[] = "Data <b>Tahun Angkatan</b> belum dipilih !";		
	}
	if (trim($cmbStatus)=="Kosong") {
		$pesanError[] = "Data <b>Status Aktif</b> tidak boleh kosong !";		
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
		$Kode	= $_POST['txtKode'];

		# SKRIP UNTUK MENYIMPAN FOTO/GAMBAR
		if (empty($_FILES['namaFile']['tmp_name'])) {
			// Jika file baru tidak ada, mama file gambar lama yang dibaca
			$file_foto = $_POST['txtNamaFile'];
		}
		else  {
			// Jika file gambar lama ada, akan dihapus
			if(! $_POST['txtNamaFile']=="") {
			if(file_exists("foto/".$_POST['txtNamaFile'])) {
				unlink("foto/".$_POST['txtNamaFile']);	
			}
			}

			// Membaca nama file foto/gambar
			$file_foto = $_FILES['namaFile']['name'];
			$file_foto = stripslashes($file_foto);
			$file_foto = str_replace("'","",$file_foto);
			
			// Simpan gambar
			$file_foto = $Kode.".".$file_foto;
			copy($_FILES['namaFile']['tmp_name'],"foto/".$file_foto);					
		}

		// SQL Simpan data ke tabel database
		$mySql	= "UPDATE mahasiswa SET nim='$txtNim', nm_mahasiswa='$txtNama', kelamin='$cmbKelamin', 
					agama='$cmbAgama', tempat_lahir='$txtTempatLahir', 
					tanggal_lahir='$txtTanggal', alamat='$txtAlamat', no_telepon='$txtNoTelepon', 
					foto='$file_foto', th_angkatan='$cmbAngkatan',  status_aktif='$cmbStatus' 
					WHERE kd_mahasiswa ='$Kode'";
		$myQry	= mysql_query($mySql, $koneksidb) or die ("Gagal query".mysql_error());
		if($myQry){
			echo "<meta http-equiv='refresh' content='0; url=?open=Mahasiswa-Data'>";
		}
		exit;
	}	
}

# TAMPILKAN DATA LOGIN UNTUK DIEDIT
$Kode	 = $_GET['Kode']; 
$mySql	 = "SELECT * FROM mahasiswa WHERE kd_mahasiswa='$Kode'";
$myQry	 = mysql_query($mySql, $koneksidb)  or die ("Query data salah: ".mysql_error());
$myData	 = mysql_fetch_array($myQry);

	// Menyimpan data ke variabel temporary (sementara)
	$dataKode		= $myData['kd_mahasiswa'];
	$dataNim		= isset($_POST['txtNim']) ? $_POST['txtNim'] : $myData['nim'];
	$dataNama		= isset($_POST['txtNama']) ? $_POST['txtNama'] : $myData['nm_mahasiswa'];
	$dataKelamin	= isset($_POST['cmbKelamin']) ? $_POST['cmbKelamin'] : $myData['kelamin'];
	$dataAgama		= isset($_POST['cmbAgama']) ? $_POST['cmbAgama'] : $myData['agama'];
	$dataTempatLahir= isset($_POST['txtTempatLahir']) ? $_POST['txtTempatLahir'] : $myData['tempat_lahir'];
	$dataTanggal	= isset($_POST['txtTanggal']) ? $_POST['txtTanggal'] : IndonesiaTgl($myData['tanggal_lahir']);
	$dataAlamat		= isset($_POST['txtAlamat']) ? $_POST['txtAlamat'] : $myData['alamat'];
	$dataNoTelepon	= isset($_POST['txtNoTelepon']) ? $_POST['txtNoTelepon'] : $myData['no_telepon'];
	$dataAngkatan	= isset($_POST['cmbAngkatan']) ? $_POST['cmbAngkatan'] : $myData['th_angkatan'];
	$dataStatus		= isset($_POST['cmbStatus']) ? $_POST['cmbStatus'] : $myData['status_aktif'];
	
?>
<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" name="form1" target="_self" enctype="multipart/form-data" >
  <table width="100%" class="table-list" border="0" cellpadding="4" cellspacing="1">
    <tr>
      <th colspan="3" scope="col">UBAH DATA MAHASISWA </th>
    </tr>
    <tr>
      <td width="230"><strong>Kode</strong></td>
      <td width="5">:</td>
      <td width="968"><input name="textfield" value="<?php echo $dataKode; ?>" size="10" maxlength="10" readonly="readonly"/>
      <input name="txtKode" type="hidden" value="<?php echo $dataKode; ?>" /></td>
    </tr>
    <tr>
      <td><strong>NIM Mhs </strong></td>
      <td><strong>:</strong></td>
      <td><input name="txtNim" value="<?php echo $dataNim; ?>" size="30" maxlength="20" /></td>
    </tr>
    <tr>
      <td><strong>Nama Mhs </strong></td>
      <td>:</td>
      <td><input name="txtNama" value="<?php echo $dataNama; ?>" size="70" maxlength="100" /></td>
    </tr>
    <tr>
      <td><strong>Kelamin</strong></td>
      <td><b>:</b></td>
      <td><b>
        <select name="cmbKelamin">
          <?php
		  $pilihan	= array("L" => "Laki-laki (L)", "P" => "Perempuan (P)");
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
      <td><b>Agama</b></td>
      <td><b>:</b></td>
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
      <td><b>Tempat, Tgl. Lahir </b></td>
      <td><b>:</b></td>
      <td><input name="txtTempatLahir" type="text" value="<?php echo $dataTempatLahir; ?>" size="50" maxlength="100" />
        ,
        <input type="text" name="txtTanggal" class="tcal" value="<?php echo $dataTanggal; ?>" /></td>
    </tr>
    <tr>
      <td><strong>Alamat Tinggal </strong></td>
      <td>:</td>
      <td><input name="txtAlamat" type="text" value="<?php echo $dataAlamat; ?>" size="80" maxlength="200" /></td>
    </tr>
    <tr>
      <td><strong>No. Telepon </strong></td>
      <td>:</td>
      <td><input name="txtNoTelepon" value="<?php echo $dataNoTelepon; ?>" size="40" maxlength="20" /></td>
    </tr>
    <tr>
      <td><strong>Foto Mahasiswa </strong></td>
      <td><strong>:</strong></td>
      <td><input name="namaFile" type="file" size="60" />
          <input name="txtNamaFile" type="hidden" value="<?php echo $myData['foto']; ?>" /></td>
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
    <tr>
      <td><b>Status (Aktif/ Alumni)</b></td>
      <td><b>:</b></td>
      <td><b>
        <select name="cmbStatus">
          <option value="Kosong">....</option>
          <?php
		  $pilihan	= array("Aktif", "Alumni");
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
