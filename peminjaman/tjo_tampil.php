<?php
include_once "../library/inc.seslogin.php";

# FILTER PEMBELIAN PER BULAN/TAHUN
# Bulan dan Tahun Terpilih

# PENCARIAN DATA  
if(isset($_POST['btnCari'])) {
//	$txtKataKunci	= trim($_POST['txtKataKunci']);
	
// OR buku.isbn ='$txtKataKunci'

//	$filterSQL = " WHERE .t. ";
//	$cariSQL 	= " AND ( morder.nama LIKE '%".$txtKataKunci."%' )";
	
	}

else {
	//Query #1 (all)
//	$filterSQL = " ";
//	$cariSQL 		= "";
//	$txtKataKunci	= "";
}


	if (isset($_POST['btnTampil'])){

		$keywords	=isset($_POST['spnama'])?$_POST['spnama']:null;
		$tglawal=$_POST['tahuna'].'-'.$_POST['bulana'].'-'.$_POST['tgla'];
		$tglakhir=$_POST['tahun'].'-'.$_POST['bulan'].'-'.$_POST['tgl'];
		$txtKataKunci	= trim($_POST['txtKataKunci']);
	
		$_SESSION['tglawal']=$_POST['tahuna'].'-'.$_POST['bulana'].'-'.$_POST['tgla'];
		$_SESSION['tglakhir']=$_POST['tahun'].'-'.$_POST['bulan'].'-'.$_POST['tgl'];
        $_SESSION['keywords'] = $keywords;
		$filterSQL = " where morder.tgl >= '$tglawal' and morder.tgl <= '$tglakhir' and morder.kodesupp LIKE '%$keywords%' ";
		
		if ($txtKataKunci=="")
		   {
			$cariSQL = "";
		   }	
		else {  
		    $cariSQL = " AND ( morder.nama LIKE '%".$txtKataKunci."%' ) ";
			}
			
			
	} else {
	    $tglawal=date('YYYY-mm-dd');$tglakhir=date('YYYY-mm-dd');$keywords='';
		$cariSQL = "";
		$filterSQL = "";
	    $txtKataKunci = "";

		 }

	if($tglawal == $tglakhir) {
//		$filterSQL = " where morder.tgl >= '$tglawal' and morder.tgl <= '$tglakhir'";			
//		$filterSQL = "";
		}
	else {
//		$filterSQL = " where morder.tgl >= '$tglawal' and morder.tgl <= '$tglakhir'";			
	}
	
$bulan		= isset($_GET['bulan']) ? $_GET['bulan'] : date('m'); // Baca dari URL, jika tidak ada diisi bulan sekarang
$dataBulan 	= isset($_POST['cmbBulan']) ? $_POST['cmbBulan'] : $bulan; // Baca dari form Submit, jika tidak ada diisi dari $bulan

$tahun	   	= isset($_GET['tahun']) ? $_GET['tahun'] : date('Y'); // Baca dari URL, jika tidak ada diisi tahun sekarang
$dataTahun 	= isset($_POST['cmbTahun']) ? $_POST['cmbTahun'] : $tahun; // Baca dari form Submit, jika tidak ada diisi dari $tahun

# Membuat Filter Bulan
if($dataBulan and $dataTahun) {
	if($dataBulan == "00") {
		// Jika tidak memilih bulan
//		$filterSQL = "WHERE LEFT(morder.tgl,4)='$dataTahun'";
//		$filterSQL = ""; //where morder.tf >= '$tglawal' and morder.tf <= '$tglakhir'";
	}
	else {
		// Jika memilih bulan dan tahun
//		$filterSQL = "WHERE LEFT(morder.tgl,4)='$dataTahun' AND MID(morder.tgl,6,2)='$dataBulan'";
//		$filterSQL = "";
	}
}
else {
//	$filterSQL = "";
}


# ==================================================================

# UNTUK PAGING (PEMBAGIAN HALAMAN)
$baris 		= 100;
$halaman 	= isset($_GET['hal']) ? $_GET['hal'] : 0;
$pageSql 	= "SELECT * FROM morder $filterSQL $cariSQL ";
$pageQry 	= pg_query($koneksidb, $pageSql) or die ("error paging: ".pg_last_error());
$jmlData	= pg_num_rows($pageQry);
$maksData	= ceil($jmlData/$baris);
?>
<h1><b>DATA Tinjauan Order</b></h1>

<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" name="form1" target="_self">
  <table width="1200" border="0"  class="table-list">
    <tr>
      <td bgcolor="#CCCCCC"><strong>FILTER DATA</strong></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    
    
	<tr bgcolor="#F8F8F8">     
    
<!--		<td width='10'></td> -->
		<td width='90'><h3>Tanggal awal</h3></td> 
<!-- 	    <td><strong>Bulan Tinjauan </strong></td> -->
		<td width='3'>:</td>
		<td width='160'><select name="tgla">
				<option selected value="<?php print isset($_POST['tgla'])?$_POST['tgla']:"01"; ?>"><?php print isset($_POST['tgla'])?$_POST['tgla']:"01"; ?></option>
					<?php for($i=1;$i<=31;$i++) {
						print "<option value='".$i."'>".$i."</option>";
						}
						?>
				</select>
				<select name="bulana">
				<option selected value="<?php print date('m') ?>"><?php print date('m') ?></option>
					<?php for($b=1;$b<=12;$b++) {
						print "<option value='".$b."'>".$b."</option>";
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
		</td> 
        
<!--		<td width='70'></td> -->
		<td width='100'><h3>Nama Customer</h3></td>
		<td width='3'>:</td>
		<td width='260'><h3><select name="spnama">
				<option selected value=""><?php print isset($_POST['spnama'])?$_POST['spnama']:null; ?></option>
<?php
				$q_stock=pg_query("select * from mcust order by kode");		  
				if (pg_num_rows($q_stock)>0) {
					while ($row=pg_fetch_array($q_stock)){
						print '<option value="'.$row['nama'].'">'.$row['nama'].'</option>';
					}
				}
?>
		</select></h3>
		</td>
		<td colspan='4' width='15'></td>
	</tr>
	<tr>
<!--		<td colspan='12' height='2'></td> -->
	</tr>  

	<tr>
<!--		<td></td> -->
		<td><h3>Tanggal akhir</h3></td>
		<td>:</td>
		<td>
		<select name="tgl">
				<option selected value="<?php print isset($_POST['Tgl'])?$_POST['tgl']:"01"; ?>"><?php print isset($_POST['tgl'])?$_POST['tgl']:"01"; ?></option>
					<?php for($i=1;$i<=31;$i++) {
						print "<option value='".$i."'>".$i."</option>";
						}
						?>
				</select>
				<select name="bulan">
				<option selected value="<?php print date('m') ?>"><?php print date('m') ?></option>
					<?php for($b=1;$b<=12;$b++) {
						print "<option value='".$b."'>".$b."</option>";
						}
					?>
				</select>
				<select name="tahun">
				<option selected value="<?php print date('Y') ?>"><?php print date('Y') ?></option>
					<?php for($t=2010;$t<=2030;$t++) {
						print "<option value='".$t."'>".$t."</option>";
						}
					?>
				</select>		
		</td>  

      <td><strong>Cari ITEM</strong></td>
      <td><strong>:</strong></td>
      <td><input name="txtKataKunci" type="text" value="<?php echo $txtKataKunci; ?>" size="40" maxlength="100" /></td>
      
<!--       <input name="btnCari" type="submit"  value=" Cari Item " /></td> -->

    
<!--
 //   <tr>
 //    <td><strong>Bulan Tinjauan </strong></td>
 //     <td><strong>:</strong></td>
  //    <td><select name="cmbBulan"> -->
  
          <?php
		// Membuat daftar Nama Bulan
//		$listBulan = array("00" => "....", 
//						"01" => "01. Januari", 
//						"02" => "02. Februari", 
//						"03" => "03. Maret",
//						"04" => "04. April", 
//						"05" => "05. Mei", 
//						"06" => "06. Juni", 
//						"07" => "07. Juli",
//						"08" => "08. Agustus", 
//						"09" => "09. September", 
//						"10" => "10. Oktober",
//						"11" => "11. November", 
//						"12" => "12. Desember");
						 
		// Menampilkan Nama Bulan ke ComboBox (List/Menu)
//		foreach($listBulan as $bulanKe => $bulanNm) {
//			if ($bulanKe == $dataBulan) {
//				$cek = " selected";
//			} else { $cek=""; }
//			echo "<option value='$bulanKe' $cek>$bulanNm</option>";
//		}
	  ?>
      
<!--        </select>
         <select name="cmbTahun"> 
      
           <?php
		# Baca tahun terendah(awal) di tabel Transaksi
		
//		$thnSql = "SELECT min(extract(year from tgl)) As tahun_kecil, max(extract(year from tgl)) As tahun_besar FROM morder";
//		$thnQry	= pg_query($thnSql, $koneksidb) or die ("Error".pg_last_error());
//		$thnRow	= pg_fetch_array($thnQry);
//		$thnKecil = $thnRow['tahun_kecil'];
//		$thnBesar = $thnRow['tahun_besar'];

	//	echo $thnkecil
	//	echo $thnbesar
		
		// Menampilkan daftar Tahun, dari tahun terkecil sampai Terbesar (tahun sekarang)
	//	for($thn= $thnKecil; $thn <= $thnBesar; $thn++) {
	//		if ($thn == $dataTahun) {
	//			$cek = " selected";
	//		} else { $cek=""; }
	//		echo "<option value='$thn' $cek>$thn</option>";
	//	}
	
	  ?>
        </select> -->
        
        </td> 
    </tr> 
    
    <tr>
      <td width="138">&nbsp;</td>
      <td width="10">&nbsp;</td>
      <td width="638"><input name="btnTampil" type="submit" value=" Tampilkan " /></td>
    </tr>
  </table>
  
</form>

<table width="1200" border="0" cellpadding="2" cellspacing="1" class="table-border">
  <tr>
    <td colspan="2">
	<table class="table-list" width="100%" border="0" cellspacing="1" cellpadding="2">
      <tr>
        <td width="28" align="center" bgcolor="#CCCCCC"><strong>No</strong></td>
        <td width="60" bgcolor="#CCCCCC"><strong>No. Tinjauan </strong></td>
        <td width="60" bgcolor="#CCCCCC"><strong>Tanggal </strong></td>
 <!--        <td width="120" bgcolor="#CCCCCC"><strong>Tgl. Hrs Kembali </strong></td> -->
        <td width="200" bgcolor="#CCCCCC"><strong>Nama Customer  </strong></td>
        <td width="350" bgcolor="#CCCCCC"><strong>Nama Item</strong></td> 
        <td colspan="3" align="center" bgcolor="#CCCCCC"><strong>Tools</strong></td> 
      </tr>
      <?php
	  // Skrip menampilkan data dari database
	  
	$mySql = "SELECT morder.* FROM morder $filterSQL $cariSQL 
				order by kode " ;
	
//	$mySql = "SELECT morder.*, mcust.kode as kodecust, mcust.nama FROM morder 
//			 LEFT JOIN mcust ON morder.kodesupp = mcust.kode
//			 $filterSQL
	//		 ORDER BY kode ";  // DESC LIMIT $halaman, $baris";
	
	$myQry = pg_query($koneksidb, $mySql)  or die ("Query salah : ".pg_last_error());
	$nomor = $halaman; 
	while ($myData = pg_fetch_array($myQry)) {
		$nomor++;
		$Kode = $myData['kode'];
		?>
      <tr>
        <td><?php echo $nomor; ?></td>
        <td><?php echo $myData['kode']; ?></td>
        <td><?php echo IndonesiaTgl($myData['tgl']); ?></td>
 <!--   <td><?php echo IndonesiaTgl($myData['tgl_harus_kembali']); ?></td> -->
<!--       <td><?php echo $myData['kodesupp']."/ ".$myData['nama']; ?></td> -->
       <td><?php echo $myData['kodesupp']; ?></td>
       <td><?php echo $myData['nama']; ?></td> 
 
        <td width="6%" align="center"><a href="?open=Tinjauan-Hapus&Kode=<?php echo $Kode; ?>" target="_self" alt="Delete Data" onclick="return confirm(' YAKIN AKAN MENGHAPUS DATA PEMINJAMAN INI ... ?')">Delete</a></td>
        <td width="6%" align="center"><a href="?open=Tinjauan-Edit&amp;Kode=<?php echo $Kode; ?>" target="_self">Edit</a></td>
        <td width="6%" align="center"><a href="tjo_cetak.php?Kode=<?php echo $Kode; ?>" target="_blank">Cetak</a></td>
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
