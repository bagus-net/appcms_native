<?php
// Validasi Login
include_once "library/inc.seslogin.php";
include_once "library/inc.connection.php";
?>
<table width="650" border="0" cellspacing="1" cellpadding="3">
  <tr>
    <td><h1>DATA USER </h1></td>
  </tr>
  <tr>
    <td><a href="?open=User-Add" target="_self"><img src="images/btn_add_data.png" height="30" border="0"  /></a></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><table class="table-list"  width="100%" border="0" cellspacing="1" cellpadding="3">
      <tr>
        <td width="4%" bgcolor="#CCCCCC"><strong>No</strong></td>
        <td width="7%" bgcolor="#CCCCCC"><strong>Nama</strong></td>
        <td width="54%" bgcolor="#CCCCCC"><strong>Level</strong></td>
        <td width="19%" bgcolor="#CCCCCC"><strong>Keterangan</strong></td>
        <td colspan="2" align="center" bgcolor="#CCCCCC"><strong>Tools</strong></td>
        </tr>
		
	<?php
	// Skrip menampilkan data User
	$mySql 	= "SELECT * FROM users ORDER BY nama ASC";
	$myQry 	= pg_query($koneksidb, $mySql)  or die ("Query  salah : ".pg_last_error());
	$nomor  = 0; 
	while ($myData = pg_fetch_array($myQry)) {
		$nomor++;
		$Kode = $myData['nama'];
	?>
	
	  <tr>
		<td> <?php echo $nomor; ?> </td>
		<td> <?php echo $myData['nama']; ?> </td>
		<td> <?php echo $myData['level']; ?> </td>
		<td> <?php echo $myData['keterangan']; ?> </td>
		<td width="8%"><a href="?open=User-Delete&Kode=<?php echo $Kode; ?>" target="_self" 
					onclick="return confirm('YAKIN AKAN MENGHAPUS DATA USER INI ... ?')">Delete</a></td>
		<td width="8%"><a href="?open=User-Edit&Kode=<?php echo $Kode; ?>" target="_self">Edit</a></td>
	  </tr>
	<?php } ?> 
    </table></td>
  </tr>
</table>
