<?php
// Periksa session Login dan Koneksi
include_once "library/inc.seslogin.php";
include_once "library/inc.connection.php";
?>
<h2> LAPORAN DATA USER</h2>

<table class="table-list" width="600" border="0" cellspacing="1" cellpadding="3">
  <tr>
    <td width="20" bgcolor="#CCCCCC"><strong>No</strong></td>
    <td width="40" bgcolor="#CCCCCC"><strong>Nama</strong></td>
    <td width="336" bgcolor="#CCCCCC"><strong>Level </strong></td>
    <td width="175" bgcolor="#CCCCCC"><strong>User Login </strong></td>
  </tr>
  
<?php
// Skrip menampilkan data User
$mySql 	= "SELECT * FROM users ORDER BY nama ASC";
$myQry 	= pg_query($koneksidb, $mySql)  or die ("Query  salah : ".pg_last_error());
$nomor  = 0; 
while ($myData = pg_fetch_array($myQry)) {
	$nomor++;
?>

  <tr>
	<td> <?php echo $nomor; ?> </td>
	<td> <?php echo $myData['nama']; ?> </td>
	<td> <?php echo $myData['level']; ?> </td>
	<td> <?php echo $myData['keterangan']; ?> </td>
  </tr>
  
<?php } ?>
</table>
<br />
<a href="cetak/user.php" target="_blank">Cetak</a>
<a href="index.php" style="border: 1px solid #666; background-color:#fff">&nbsp;&nbsp;&nbsp;Close&nbsp;&nbsp;&nbsp;</button></a>
