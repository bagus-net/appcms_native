<?php
include_once "library/inc.seslogin.php";
include_once "library/inc.connection.php";
include_once "library/inc.library.php";

// Baca variabel URL browser
$kodePenerbit = isset($_GET['kodePenerbit']) ? $_GET['kodePenerbit'] : 'Semua'; 
// Baca variabel dari Form setelah di Post
$kodePenerbit = isset($_POST['cmbPenerbit']) ? $_POST['cmbPenerbit'] : $kodePenerbit;

// Membuat filter SQL
if ($kodePenerbit=="Semua") {
	//Query #1 (semua data)
	$filterSQL 	= "";
}
else {
	//Query #2 (filter)
	$filterSQL 	= " AND mbarang.kodesupp ='$kodePenerbit'";
}

# ================================================================

# PENCARIAN DATA  
if(isset($_POST['btnCari'])) {
	$txtKataKunci	= trim($_POST['txtKataKunci']);
	
// OR buku.isbn ='$txtKataKunci'

	$cariSQL 	= " AND ( mbarang.nama LIKE '%".$txtKataKunci."%' OR mbarang.kode ='$txtKataKunci' )";
	
	// Pencarian Multi String (beberapa kata)
	$keyWord 		= explode(" ", $txtKataKunci);
	if(count($keyWord) > 1) {
		foreach($keyWord as $kata) {
			$cariSQL	.= " OR mbarang.kode LIKE '%$kata%'";
		} 
	}
}
else {
	//Query #1 (all)
	$cariSQL 		= "";
	$txtKataKunci	= "";
}

# UNTUK PAGING (PEMBAGIAN HALAMAN)
$baris 		= 50;
$halaman 	= isset($_GET['hal']) ? $_GET['hal'] : 0;
$pageSql 	= "SELECT * FROM mbarang WHERE jenis='BJ' $filterSQL $cariSQL";
$pageQry 	= pg_query($koneksidb, $pageSql) or die("Error paging:".pg_last_error());
$jmlData	= pg_num_rows($pageQry);
$maksData	= ceil($jmlData/$baris);
?>
<h2> MANAJEMEN DATA BOX </h2>
<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" name="form1" target="_self">
  <table width="900" border="0"  class="table-list">
    <tr>
      <td bgcolor="#CCCCCC"><strong>FILTER DATA </strong></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td width="137"><strong> Customer </strong></td>
      <td width="10"><strong>:</strong></td>
      <td width="739">
	  <select name="cmbPenerbit">
        <option value="Semua">....</option>
		<?php
		// Skrip membaca data Penerbit
		$bacaSql = "SELECT * FROM mcust ORDER BY nama";
		$bacaQry = pg_query($koneksidb, $bacaSql) or die ("Gagal Query".pg_last_error());
		while ($bacaData = pg_fetch_array($bacaQry)) {
			if ($bacaData['kode'] == $kodePenerbit) {
				$cek = " selected";
			} else { $cek=""; }
			
			$Kode = $bacaData['kode'];
			// Menghitung jumlah Koleksi buku per Penerbit
			$baca2Sql = "SELECT COUNT(*) As koleksi FROM mcust WHERE kode = '$Kode'";
			$baca2Qry = pg_query($koneksidb, $baca2Sql) or die ("Gagal Query".pg_last_error());
			$baca2Data= pg_fetch_array($baca2Qry);
		
		echo "<option value='$bacaData[kode]' $cek>$bacaData[nama] ( $baca2Data[koleksi] )</option>";
		}
		?>
      </select>
      <input name="btnTampilkan" type="submit" value=" Tampilkan  "/></td>
    </tr>
    <tr>
      <td><strong>Cari Judul  / ITEM</strong></td>
      <td><strong>:</strong></td>
      <td><input name="txtKataKunci" type="text" value="<?php echo $txtKataKunci; ?>" size="40" maxlength="100" />
          <input name="btnCari" type="submit"  value=" Cari Item " /></td>
    </tr>
  </table>
</form>

<table width="1000" border="0" cellspacing="1" cellpadding="3" class="table-border">
  <tr>
    <td>&nbsp;</td>
    <td align="right"><a href="?open=Buku-Add" target="_self"><img src="images/btn_add_data.png" height="30" border="0" /></a> </td>
  </tr>
  <tr>
    <td colspan="2"><table class="table-list"  width="1000" border="0" cellspacing="1" cellpadding="3">
      <tr>
        <th width="3%" bgcolor="#CCCCCC"><strong>No</strong></th>
        <th width="4%" bgcolor="#CCCCCC">&nbsp;</th>
        <th width="15%" bgcolor="#CCCCCC"><strong>Kode</strong></th>
        <th width="40%" bgcolor="#CCCCCC"><b>Nama Item</b></th>
        <th width="15%" bgcolor="#CCCCCC"><strong>Ukuran</strong></th>
        <th width="15%" bgcolor="#CCCCCC"><strong>Flute</strong></th>
        <th width="25%" bgcolor="#CCCCCC"><strong>Customer</strong></th>
        <td colspan="3" align="center" bgcolor="#CCCCCC"><strong>Tools</strong></td>
      </tr>
      <?php
		// Skrip menampilkan data Buku dari database
		$mySql = "SELECT mbarang.*, mcust.nama as namacust FROM mbarang 
					LEFT JOIN mcust ON mbarang.kodesupp = mcust.kode
					WHERE mbarang.jenis ='BJ'  $filterSQL $cariSQL
					ORDER BY mbarang.kode ASC ";  //  LIMIT $halaman, $baris"; 
		$myQry = pg_query($koneksidb, $mySql)  or die ("Query salah : ".pg_last_error());
		$nomor = $halaman; 
		while ($myData = pg_fetch_array($myQry)) {
			$nomor++;
			$Kode = $myData['kode'];
			
			// Menghitung Koleksi Inventaris Buku
//			$my2Sql	= "SELECT COUNT(*) As jum_koleksi FROM buku_inventaris WHERE kd_buku = '$Kode'";
//			$my2Qry = mysql_query($my2Sql, $koneksidb)  or die ("Query salah : ".mysql_error());
//			$my2Data= mysql_fetch_array($my2Qry);
			
			// menampilkan gambar utama
//			if ($myData['file_gambar']=="") {
				$fileGambar = "noimage.jpg";
//			}
//			else {
//				$fileGambar	= $myData['file_gambar'];
//			}
	
			// gradasi warna
			if($nomor%2==1) { $warna=""; } else {$warna="#F5F5F5";}
	?>
      <tr bgcolor="<?php echo $warna; ?>">
        <td><?php echo $nomor; ?></td>
        <td><img src="cover/<?php echo $fileGambar; ?>" width="28" height="39" border="0" /></td>
        <td><?php echo $myData['kode']; ?></td>
        <td><?php echo $myData['nama']; ?></td>
        <td><?php echo $myData['ukuran']; ?></td> 
        <td><?php echo $myData['flute']; ?></td> 
        <td><?php echo $myData['namacust']; ?></td>
        <td width="6%" align="center"><a href="?open=Buku-Delete&amp;Kode=<?php echo $Kode; ?>" target="_self" onclick="return confirm(' YAKIN AKAN MENGHAPUS DATA BUKU INI ... ?')">Delete</a></td>
        <td width="6%" align="center"><a href="?open=Buku-Edit&amp;Kode=<?php echo $Kode; ?>" target="_self">Edit</a></td>
        <td width="6%" align="center" bgcolor="<?php echo $warna; ?>"><a href="cetak/buku_cetak.php?Kode=<?php echo $Kode; ?>" target="_blank">Cetak</a></td>
      </tr>
      <?php } ?>
    </table></td>
  </tr>
  <tr>
    <td><strong>Jumlah Data :</strong> <?php echo $jmlData; ?> </td>
    <td align="right"><strong>Halaman ke :</strong>
    <?php
	for ($h = 1; $h <= $maksData; $h++) {
		$list[$h] = $baris * $h - $baris;
		echo " <a href='?open=Buku-Data&hal=$list[$h]&kodePenerbit=$kodePenerbit'>$h</a> ";
	}
	?></td>
  </tr>
</table>
