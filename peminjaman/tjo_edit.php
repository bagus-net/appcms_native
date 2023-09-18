<?php
//include_once "library/inc.seslogin.php";
//include_once "library/inc.connection.php";
//include_once "library/inc.library.php";

include_once "../library/inc.seslogin.php";


# SKRIP SAAT TOMBOL SIMPAN DIKLIK
if(isset($_POST['btnSimpan'])){
	# Membaca Variabel Form
	$txtNim				= $_POST['txtNim'];
	$txtNama			= $_POST['txtNama'];
	$cmbKelamin			= $_POST['cmbKelamin'];
	$cmbAgama			= $_POST['cmbAgama'];
	$txtTempatLahir		= $_POST['txtTempatLahir'];
//	$txtTanggal			= InggrisTgl($_POST['txtTanggal']);
	$txtAlamat			= $_POST['txtAlamat'];
	$txtNoTelepon		= $_POST['txtNoTelepon'];
	$cmbjoinn			= $_POST['cmbjoin'];
	$cmbStatus			= $_POST['cmbStatus'];
	$cmbflute			= $_POST['cmbflute'];
	
	
	# Validasi form, jika kosong sampaikan pesan error
	$pesanError = array();
	if (trim($txtNim)=="") {
		$pesanError[] = "Data <b>Kontak </b> tidak boleh kosong !";		
	}
	
//	else {
//		# Validasi NIM Mahasiswa, jika sudah ada akan ditolak
	//	$Kode	= $_POST['txtKode'];
//		$cekSql	= "SELECT * FROM mcust WHERE nama='$txtNama' AND NOT(kode='$Kode')";
//		$cekQry	= pg_query($koneksidb, $cekSql) or die ("Eror Query".pg_last_error()); 
//		if(pg_num_rows($cekQry)>=1){
	//		$pesanError[] = "Maaf, Nama <b> $txtNama </b> sudah terpakai, ganti dengan yang lain";
//		}
//	}

	if (trim($txtNama)=="") {
		$pesanError[] = "Data <b>Nama </b> tidak boleh kosong !";		
	}
	if (trim($cmbAgama)=="Kosong") {
		$pesanError[] = "Data <b>Agama</b> tidak boleh kosong !";		
	}	
	if (trim($txtTempatLahir)=="") {
		$pesanError[] = "Data <b>Tempat Lahir</b> tidak boleh kosong !";		
	}
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
//			$file_foto = $_POST['txtNamaFile'];
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
		$mySql	= "UPDATE mcust SET kontak='$txtNim', nama='$txtNama', cpkp='$cmbKelamin', 
					lnama='$cmbAgama', kota='$txtTempatLahir', 
					alamat='$txtAlamat', telp='$txtNoTelepon', 
					cstatus='$cmbStatus' 
					WHERE kode ='$Kode'";
					
		$myQry	= pg_query($koneksidb, $mySql) or die ("Gagal query".pg_last_error());
		if($myQry){
			echo "<meta http-equiv='refresh' content='0; url=?open=Mcust-Data'>";
		}
		exit;
	}	
}

# TAMPILKAN DATA LOGIN UNTUK DIEDIT
$Kode	 = $_GET['Kode']; 
$mySql	 = "SELECT * FROM morder WHERE kode ='$Kode'";
$myQry	 = pg_query($koneksidb, $mySql)  or die ("Query data salah: ".pg_last_error());
$myData	 = pg_fetch_array($myQry);

	// Menyimpan data ke variabel temporary (sementara)
	$dataKode		= $myData['kode'];
	$dataNim		= isset($_POST['txtNim']) ? $_POST['txtNim'] : $myData['kodestock'];
	$dataNama		= isset($_POST['txtNama']) ? $_POST['txtNama'] : $myData['kodesupp'];
	$dataKelamin	= isset($_POST['cmbKelamin']) ? $_POST['cmbKelamin'] : $myData['type'];
	$dataAgama		= isset($_POST['cmbAgama']) ? $_POST['cmbAgama'] : $myData['status'];
	$dataTempatLahir= isset($_POST['txtTempatLahir']) ? $_POST['txtTempatLahir'] : $myData['sat3'];
	$dataTanggal	= isset($_POST['txtTanggal']) ? $_POST['txtTanggal'] : IndonesiaTgl($myData['tgl']);
	$dataAlamat		= isset($_POST['txtAlamat']) ? $_POST['txtAlamat'] : $myData['nama'];
	$dataNoTelepon	= isset($_POST['txtNoTelepon']) ? $_POST['txtNoTelepon'] : $myData['ukuran'];
	$dataSkwa		= isset($_POST['txtSkwa']) ? $_POST['txtSkwa'] : $myData['skwa'];
	$dataWarna		= isset($_POST['txtWarna']) ? $_POST['txtWarna'] : $myData['warna'];
	$datajoin		= isset($_POST['cmbjoin']) ? $_POST['cmbjoin'] : $myData['sambungan'];
	$datagrg		= isset($_POST['txtgrg']) ? $_POST['txtgrg'] : $myData['grg'];	
	$dataTglkrm		= isset($_POST['txtTglkrm']) ? $_POST['txtTglkrm'] : IndonesiaTgl($myData['tglkrm']);
	$dataflute		= isset($_POST['cmbflute']) ? $_POST['cmbflute'] : $myData['flute'];
	$dataLilin		= isset($_POST['cmbLilin']) ? $_POST['cmbLilin'] : $myData['lilin'];
	$dataBungkus	= isset($_POST['cmbBungkus']) ? $_POST['cmbBungkus'] : $myData['bungkus'];
	$dataLkertas	= isset($_POST['txtLkertas']) ? $_POST['txtLkertas'] : $myData['lkertas'];	
	$dataOut		= isset($_POST['txtOut']) ? $_POST['txtOut'] : $myData['outk'];	
	$dataLbersih	= isset($_POST['txtLbersih']) ? $_POST['txtLbersih'] : $myData['lbersih'];	
	$dataLuas		= isset($_POST['txtLuas']) ? $_POST['txtLuas'] : $myData['luas'];	
	$dataBerat		= isset($_POST['txtBerat']) ? $_POST['txtBerat'] : $myData['berat'];	
	$dataIndex		= isset($_POST['txtIndex']) ? $_POST['txtIndex'] : $myData['h_index'];	
	$dataDisc		= isset($_POST['txtDisc']) ? $_POST['txtDisc'] : $myData['h_disc'];	
	$dataTwaste		= isset($_POST['txtTwaste']) ? $_POST['txtTwaste'] : $myData['lkertas'] - $myData['lbersih'];	
	$dataJorder		= isset($_POST['txtJorder']) ? $_POST['txtJorder'] : $myData['jorder'];	
	$dataRm			= isset($_POST['txtRm']) ? $_POST['txtRm'] : $myData['reorder'];	
	$dataHrgjual	= isset($_POST['txtHrgjual']) ? $_POST['txtHrgjual'] : $myData['h_jual'];	
	$dataHrgkg		= isset($_POST['txtHrgkg']) ? $_POST['txtHrgkg'] : $myData['h_jual'] / $myData['berat'];	
	$dataHrgcop		= isset($_POST['txtHrgcop']) ? $_POST['txtHrgcop'] : $myData['h_beli'];	
	$dataCopkg		= isset($_POST['txtCopkg']) ? $_POST['txtCopkg'] : $myData['h_beli'] / $myData['berat'];	
	$dataHrgpl		= isset($_POST['txtHrgpl']) ? $_POST['txtHrgpl'] : $myData['h_jual12'];	
	$dataOngkrm		= isset($_POST['txtOngkrm']) ? $_POST['txtOngkrm'] : $myData['h_jualk'];	
	$dataOngkom		= isset($_POST['txtOngkom']) ? $_POST['txtOngkom'] : $myData['h_jual1k'];	
	$dataOngbks		= isset($_POST['txtOngbks']) ? $_POST['txtOngbks'] : $myData['h_jual11'];	
	$dataOnglln		= isset($_POST['txtOnglln']) ? $_POST['txtOnglln'] : $myData['h_jual1'];	
	$dataOnglain	= isset($_POST['txtOnglain']) ? $_POST['txtOnglain'] : $myData['h_jual1'];	
	

$mySqlx	 = "SELECT * FROM msales WHERE kode ='$dataNim'";
$myQryx	 = pg_query($koneksidb, $mySqlx)  or die ("Query data salah: ".pg_last_error());
$myDatax	 = pg_fetch_array($myQryx);

	$dataNamasales		= isset($_POST['txtNamasales']) ? $_POST['txtNamasales'] : $myDatax['nama'];

	
//	$dataAngkatan	= isset($_POST['cmbAngkatan']) ? $_POST['cmbAngkatan'] : $myData['th_angkatan'];
//	$dataStatus		= isset($_POST['cmbStatus']) ? $_POST['cmbStatus'] : $myData['cstatus'];
	
?>
<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" name="form1" target="_self" enctype="multipart/form-data" >
  <table width="100%" class="table-list" border="0" cellpadding="4" cellspacing="1">
    <tr>
      <th colspan="3" scope="col">UBAH TINJAUAN ORDER </th>
    </tr>


    <tr>
      <td width="230"><strong>Kode</strong></td>
      <td><b>:</b></td>
      <td><input name="txtTempatLahir" type="text" value="<?php echo $dataKode; ?>" size="20" maxlength="50" />
        Tgl :
        <input type="text" name="txtTanggal" class="tcal" value="<?php echo $dataTanggal; ?>" />
        DT  :
        <input type="text" name="txtTglkrm" class="tcal" value="<?php echo $dataTglkrm; ?>" /></td>
    </tr>

    <tr>
      <td width="230"><strong>Sales</strong></td>
      <td width="5">:</td>
      <td width="968"><input name="textfield" value="<?php echo $dataNim; ?>" size="20" maxlength="10" readonly="readonly"/>
      <input name="txtKode" type="hidden" value="<?php echo $dataNim; ?>" />
        Nama  :
      <input name="txtnamasales" type="text" value="<?php echo $dataNamasales; ?>" size="20" maxlength="100" /></td>
    </tr>


    <tr>
      <td><b>Status</b></td>
      <td><b>:</b></td>
      <td><b>
        <select name="cmbAgama">
          <option value="Kosong">....</option>
          <?php
		  $pilihan	= array("New", "Repeat");
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
      <td>:</td>
      <td><input name="txtNama" value="<?php echo $dataNama; ?>" size="70" maxlength="100" /></td>
    </tr>
    
    <tr>    
      <td><strong>Type</strong></td>
      <td><b>:</b></td>
      <td><b>
        <select name="cmbKelamin">
          <?php
		  $pilihan	= array("A1" => "A1", "L" => "Layer (L)");
          foreach ($pilihan as $nilai => $judul) {
            if ($dataKelamin==$nilai) {
                $cek=" selected";
            } else { $cek = ""; }
            echo "<option value='$nilai' $cek> $judul </option>";
          }
          ?>
        </select>
      </b></td>
   </tr>
   
    <tr>    
   
      <td><strong>Flute</strong></td>
      <td><b>:</b></td>
      <td><b>
        <select name="cmbflute">
          <?php
		  $pilihan	= array("BF", "CF", "BCF");
          foreach ($pilihan as $nilai => $judul) {
            if ($dataflute==$nilai) {
                $cek=" selected";
            } else { $cek = ""; }
            echo "<option value='$nilai' $cek> $judul</option>";
          }
          ?>
        </select>
      </b></td>

   </tr>
    

    <tr>
      <td><strong>Item </strong></td>
      <td>:</td>
      <td><input name="txtAlamat" type="text" value="<?php echo $dataAlamat; ?>" size="80" maxlength="200" /></td>
    </tr>

    <tr>
      <td><b>Satuan </b></td>
      <td><b>:</b></td>
      <td><input name="txtTempatLahir" type="text" value="<?php echo $dataTempatLahir; ?>" size="10" maxlength="10" />
    </tr>

    <tr>
      <td><strong>Ukuran </strong></td>
      <td>:</td>
      <td><input name="txtNoTelepon" value="<?php echo $dataNoTelepon; ?>" size="40" maxlength="20" /></td>
    </tr>

    <tr>
      <td><b>Creasing </b></td>
      <td><b>:</b></td>
      <td><input name="txtgrg" type="text" value="<?php echo $datagrg; ?>" size="50" maxlength="100" /></td>
    </tr>


    
    <tr>
      <td><strong>Substance </strong></td>
      <td>:</td>
      <td><input name="txtSkwa" value="<?php echo $dataSkwa; ?>" size="40" maxlength="20" /></td>
    </tr>



    <tr>
      <td><strong>Warna</strong></td>
      <td><strong>:</strong></td>
      <td><input name="txtWarna" value="<?php echo $dataWarna; ?>" size="30" maxlength="20" /></td>
    </tr>


<!--    
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
-->

    <tr>
      <td><b>Sambungan </b></td>
      <td><b>:</b></td>
      <td><b>
        <select name="cmbStatus">
          <option value="Kosong">....</option>
          <?php
		  $pilihan	= array("STITCH","GLUE","STITCH-GLUE","DOUBLE-STITCH");
          foreach ($pilihan as $nilai) {
            if ($datajoin==$nilai) {
                $cek=" selected";
            } else { $cek = ""; }
            echo "<option value='$nilai' $cek>$nilai</option>";
          }
          ?>
        </select>
      </b></td>
    </tr>

    <tr>
      <td><b>Lilin </b></td>
      <td><b>:</b></td>
      <td><b>
        <select name="cmbLilin">
          <option value="Kosong">....</option>
          <?php
		  $pilihan	= array("Luar","Dalam","Luar-Dalam");
          foreach ($pilihan as $nilai) {
            if ($dataLilin==$nilai) {
                $cek=" selected";
            } else { $cek = ""; }
            echo "<option value='$nilai' $cek>$nilai</option>";
          }
          ?>
        </select>
      </b></td>
    </tr>

    <tr>
      <td><b>Bungkus </b></td>
      <td><b>:</b></td>
      <td><b>
        <select name="cmbBungkus">
          <option value="Kosong">....</option>
          <?php
		  $pilihan	= array("Kertas","Plastik");
          foreach ($pilihan as $nilai) {
            if ($dataBungkus==$nilai) {
                $cek=" selected";
            } else { $cek = ""; }
            echo "<option value='$nilai' $cek>$nilai</option>";
          }
          ?>
        </select>
      </b></td>
    </tr>


    <tr>
      <td width="230"><strong>Lebar Kertas</strong></td>
      <td width="5">:</td>
      <td width="5"><input name="textfield" value="<?php echo $dataLkertas; ?>" size="10" maxlength="10" readonly="readonly"/>
      <input name="txtLkertas" type="hidden" value="<?php echo $dataLkertas; ?>" />
        Out  :
      <input name="txtOut" type="text" value="<?php echo $dataOut; ?>" size="10" maxlength="10" />
        Lebar Bersih :
      <input name="txtLbersih" type="text" value="<?php echo $dataLbersih; ?>" size="10" maxlength="10" />
        Trim Waste :
      <input name="txtTwaste" type="text" value="<?php echo $dataTwaste; ?>" size="10" maxlength="10" /></td>
      
    </tr>

    <tr>
      <td width="230"><strong>Luas</strong></td>
      <td width="5">:</td>
      <td width="5"><input name="textfield" value="<?php echo $dataLuas; ?>" size="10" maxlength="10" readonly="readonly"/>
      <input name="txtLuas" type="hidden" value="<?php echo $dataLuas; ?>" />
        Berat  :
      <input name="txtBerat" type="text" value="<?php echo $dataBerat; ?>" size="10" maxlength="10" /></td>
      
    </tr>

    <tr>
      <td width="230"><strong>Index</strong></td>
      <td width="5">:</td>
      <td width="5"><input name="textfield" value="<?php echo $dataIndex; ?>" size="10" maxlength="10" readonly="readonly"/>
      <input name="txtIndex" type="hidden" value="<?php echo $dataIndex; ?>" />
        Discount Index  :
      <input name="txtDisc" type="text" value="<?php echo $dataDisc; ?>" size="10" maxlength="10" /></td>      
    </tr>

    <tr>
      <td width="230"><strong>Jumlah Order</strong></td>
      <td width="5">:</td>
      <td width="5"><input name="textfield" value="<?php echo $dataJorder; ?>" size="10" maxlength="10" readonly="readonly"/>
      <input name="txtJorder" type="hidden" value="<?php echo $dataJorder; ?>" />
        Running Meter  :
      <input name="txtRm" type="text" value="<?php echo $dataRm; ?>" size="10" maxlength="10" /></td>      
    </tr>

    <tr>
      <td width="230"><strong>Harga Jual / Pcs</strong></td>
      <td width="5">:</td>
      <td width="5"><input name="textfield" value="<?php echo $dataHrgjual; ?>" size="10" maxlength="10" readonly="readonly"/>
      <input name="txtHrgjual" type="hidden" value="<?php echo $dataHrgjual; ?>" /> 

        Harga Jual/ Kg  :
      <input name="txtHrgkg" type="text" value="<?php echo $dataHrgkg; ?>" size="10" maxlength="10" /></td>      
    </tr>

    <tr>
      <td width="230"><strong>Harga COP / Pcs</strong></td>
      <td width="5">:</td>
      <td width="5"><input name="textfield" value="<?php echo $dataHrgcop; ?>" size="10" maxlength="10" readonly="readonly"/>
      <input name="txtHrgcop" type="hidden" value="<?php echo $dataHrgcop; ?>" />
        Harga COP / Kg  :
      <input name="txtCopkg" type="text" value="<?php echo $dataCopkg; ?>" size="10" maxlength="10" />      
        Harga Layer/Part  :
      <input name="txtHrgpl" type="text" value="<?php echo $dataHrgpl; ?>" size="10" maxlength="10" /></td>      
    </tr>

    <tr>
      <td width="230"><strong>Ongkos Kirim</strong></td>
      <td width="5">:</td>
      <td width="5"><input name="textfield" value="<?php echo $dataOngkrm; ?>" size="10" maxlength="10" readonly="readonly"/>
      <input name="txtOngkrm" type="hidden" value="<?php echo $dataOngkrm; ?>" /> 
        Ongkos Komisi  :
      <input name="txtOngkom" type="text" value="<?php echo $dataOngkom; ?>" size="10" maxlength="10" />      
        Ongkos Bungkus:
      <input name="txtOngbks" type="text" value="<?php echo $dataOngbks; ?>" size="10" maxlength="10" /></td>      
    </tr>


    <tr>
      <td width="230"><strong>Ongkos Lilin : </strong></td>
      <td width="5">:</td>
      <td width="5"><input name="textfield" value="<?php echo $dataOnglln; ?>" size="10" maxlength="10" readonly="readonly"/>
      <input name="txtOnglln" type="hidden" value="<?php echo $dataOnglln; ?>" /> 
        Ongkos Lain-lain  :
      <input name="txtOnglain" type="text" value="<?php echo $dataOnglain; ?>" size="10" maxlength="10" /></td>      
    </tr>


    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><input type="submit" name="btnSimpan" value=" SIMPAN "></td>
    </tr>
  </table>
</form>
