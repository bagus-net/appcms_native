<?php
/* koneksi ke db */
//mysql_connect("localhost", "saya", "akujaya") or die(mysql_error());
//mysql_select_db("demo") or die(mysql_error());

include_once "library/inc.seslogin.php";
include_once "library/inc.connection.php";
include_once "library/inc.library.php";

/* akhir koneksi db */

/* penanganan form */
if (isset($_POST['Input'])) {
	$nim  	= strip_tags($_POST['nim']);
	$nama  	= strip_tags($_POST['nama']);
	$alamat = strip_tags($_POST['alamat']);
	
	//input ke db
//	$query = "INSERT INTO mtbox (kode,keterangan,fgbr) VALUES('$kode','$nama','$alamat')";
	$query  	= "INSERT INTO mtbox (kode, keterangan, fgbr)
						VALUES ('$nim', '$nama', '$alamat')";
	
			//mysql_escape_string($kode), 
		//	mysql_escape_string($keterangan), 
	//		mysql_escape_string($fgbr)
//		);
//	$sql = mysql_query($query);

	$sql = pg_query($koneksidb,$query) or die ("Gagal query".pg_last_error());

	$pesan = "";
	if ($sql) {
		$pesan = "Data berhasil disimpan";
	} else {
		$pesan = "Data gagal disimpan ";
		$pesan .= mysql_error();
	}
	$response = array('pesan'=>$pesan, 'data'=>$_POST);
	echo json_encode($response);
	exit;
	
} else if (isset($_POST['Edit'])) {
	$nim  	= strip_tags($_POST['nim']);
	$nama  	= strip_tags($_POST['nama']);
	$alamat = strip_tags($_POST['alamat']);
	
	//update data
	$query = sprintf("UPDATE mtbox SET nama='%s', alamat='%s' WHERE nim='%s'", 
			mysql_escape_string($nama), 
			mysql_escape_string($alamat),
			mysql_escape_string($nim)
		);
//	$sql = mysql_query($query);
	$sql = pg_query($koneksidb,$query, ) or die ("Gagal query".pg_last_error());
	
	$pesan = "";
	if ($sql) {
		$pesan = "Data berhasil disimpan";
	} else {
		$pesan = "Data gagal disimpan ";
		$pesan .= mysql_error();
	}
	$response = array('pesan'=>$pesan, 'data'=>$_POST);
	echo json_encode($response);
	exit;
} else if (isset($_POST['Delete'])) {
	$nim  	= strip_tags($_POST['nim']);
	
	//delete data
	$query = sprintf("DELETE FROM mahasiswa WHERE nim='%s'", 
			mysql_escape_string($nim)
		);
	$sql = mysql_query($query);
	$pesan = "";
	if ($sql) {
		$pesan = "Data berhasil dihapus";
	} else {
		$pesan = "Data gagal dihapus ";
		$pesan .= mysql_error();
	}
	$response = array('pesan'=>$pesan, 'data'=>$_POST);
	echo json_encode($response);
	exit;
} else if (isset($_GET['action']) && $_GET['action'] == 'getdata') {
		
	$page = (isset($_POST['page']))?$_POST['page']: 1;
	$rp = (isset($_POST['rp']))?$_POST['rp'] : 10;
	$sortname = (isset($_POST['sortname']))? $_POST['sortname'] : 'nama';
	$sortorder = (isset($_POST['sortorder']))? $_POST['sortorder'] : 'asc';
			
	$sort = "ORDER BY $sortname $sortorder";
	$start = (($page-1) * $rp);
	$limit = "LIMIT $start, $rp";
	
	$query = (isset($_POST['query']))? $_POST['query'] : '';
	$qtype = (isset($_POST['qtype']))? $_POST['qtype'] : '';
	
	$where = "";
	if ($query) $where .= "WHERE $qtype LIKE '%$query%' ";
	
	$query = "SELECT nim, nama, alamat ";
	$query_from =" FROM msales ";
	
	$query .= $query_from . " $where $sort $limit";
		
	$query_total = "SELECT COUNT(*)". $query_from." ".$where;
	
//	$sql = mysql_query($query) or die($query);
	$sql = pg_query($koneksidb, $query)  or die ("Query  salah : ".pg_last_error());
	
//	$sql_total = mysql_query($query_total) or die($query_total);

	$sql_total = pg_query($koneksidb,$query_total) or die($query_total);
	
//	$total = mysql_fetch_row($sql_total);

	$total = pg_fetch_row($sql_total);

	$data = $_POST;
	$data['total'] = $total[0];
	$datax = array();
	$datax_r = array();
//	while ($row = mysql_fetch_row($sql)) {
	while ($row = pg_fetch_row($sql)) {
		$rows['id'] = $row[0];
		$datax['cell'] = $row;
		array_push($datax_r, $datax);
	}
	$data['rows'] = $datax_r;
	echo json_encode($data);
	exit;
} else if (isset($_GET['action']) && $_GET['action'] == 'get_mhs') {
	$nim = $_GET['nim'];
	$query = "SELECT * FROM msales WHERE nim='$nim'";
//	$sql = mysql_query($query);
//	$row = mysql_fetch_assoc($sql);

	$sql = pg_query($koneksidb,$query);
	$row = pg_fetch_assoc($sql);

	echo json_encode ($row);
	exit;
}
?>
<html>
	<head>
		<title>Entri, Edit, Delete, Tampil Data dengan PHP dan Ajax</title>
		<style type="text/css">
		.labelfrm {
			display:block;
			font-size:small;
			margin-top:5px;
		}
		.error {
			font-size:small;
			color:red;
		}
		</style>
		<script type="text/javascript" src="libs/jquery.min.js"></script>
		<script type="text/javascript" src="libs/jquery.form.js"></script>
		<script type="text/javascript" src="libs/jquery.validate.min.js"></script>
		<link rel="stylesheet" type="text/css" href="libs/flexigrid/css/flexigrid.css">
		<script type="text/javascript" src="libs/jquery.cookie.js"></script>
		<script type="text/javascript" src="libs/flexigrid/js/flexigrid.js"></script>
		<script type="text/javascript">
		$(document).ready(function() {
			resetForm();
            //aktifkan ajax di form
            var options = {
				success	  : showResponse,
				beforeSubmit:  function(){
					return $("#frm").valid();
				},
				resetForm : true,
				clearForm : true,
				dataType  : 'json'
			};
			$('#frm').ajaxForm(options); 
			
			//validasi form dgn jquery validate
			$('#frm').validate({
				rules: {
					nim : {
						digits: true,
						minlength:10,
						maxlength:10
					}
				},
				messages: {
					nim: {
						required: "Kolom nim harus diisi",
						minlength: "Kolom nim harus terdiri dari 10 digit",
						maxlength: "Kolom nim harus terdiri dari 10 digit",
						digits: "NIM harus berupa angka"
					},
					nama: {
						required: "Nama harus diisi dengan benar"
					}
				}
			});
			
			//flexigrid handling
			$('#flex1').flexigrid
			(
				{
				url: 'index.php?action=getdata',
				dataType: 'json',
				
				colModel : [
					{display: 'NIM', name : 'kode', width : 100, sortable : true, align: 'left', process: doaction},
					{display: 'Nama', name : 'keterangan', width : 200, sortable : true, align: 'left', process: doaction},
					{display: 'Alamat', name : 'fgbr', width : 400, sortable : true, align: 'left', process: doaction}
					],
				searchitems : [
					{display: 'NIM', name : 'kode'},
					{display: 'Nama', name : 'keterangan', isdefault: true}
					],
					
				sortname: 'nama',
				sortorder: 'asc',
				usepager: true,
				title: 'Data Mahasiswa',
				useRp: true,
				rp: 15,
				width: 700,
				height: 400
				}
			);
			
        }); 
        function doaction( celDiv, id ) {
			$( celDiv ).click( function() {
				var nim = $(this).parent().parent().children('td').eq(0).text();
				$.getJSON ('index.php',{action:'get_mhs',nim:nim}, function (json) {
					$('#nim').val(json.nim);
					$('#nama').val(json.nama);
					$('#alamat').val(json.alamat);
				}); 
				$('#nim').attr('readonly','readonly');
				$('#input').attr('disabled','disabled');
				$('#edit, #delete').removeAttr('disabled');
			});
		}
        function showResponse(responseText, statusText) {
			var data = responseText['data'];
			var pesan = responseText['pesan'];
			alert(pesan);
			resetForm();
			$('#flex1').flexReload();
		}
		function resetForm() {
			$('#input').removeAttr('disabled');
			$('#edit, #delete').attr('disabled','disabled');
			$('#nim').removeAttr('readonly');
		}
		</script>
	</head>
	<body>
		<h1>Data Mahasiswa</h1>
		<form action="" method="post" id="frm" onReset="resetForm()">
			<label for="nim" class="labelfrm">NIM: </label>
			<input type="text" name="nim" id="nim" maxlength="10" class="required" size="15"/>
			
			<label for="nama" class="labelfrm">NAMA: </label>
			<input type="text" name="nama" id="nama" size="30" class="required"/>
			
			<label for="alamat" class="labelfrm">ALAMAT: </label>
			<textarea name="alamat" id="alamat" cols="40" rows="4" class="required"></textarea>
			
			<label for="submit" class="labelfrm">&nbsp;</label>
			<input type="submit" name="Input" value="Input" id="input"/>
			<input type="submit" name="Edit" value="Edit" id="edit"/>
			<input type="submit" name="Delete" value="Delete" id="delete"/>
			<input type="reset" name="Clear" value="Clear" id="clear"/>
		</form>
		
		<table id="flex1" style="display:none"></table>
	</body>
</html>
