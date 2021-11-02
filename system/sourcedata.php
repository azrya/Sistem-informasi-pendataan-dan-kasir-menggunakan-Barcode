<?php
	mysql_connect("localhost","root","");
	mysql_select_db("dbpos");
	
$term = trim(strip_tags($_GET['term']));
$qstring = "SELECT kode_produk, nama_produk FROM produk WHERE kode_produk LIKE '".$term."%'";
$result = mysql_query($qstring);
while ($row = mysql_fetch_array($result)){
		$row['value']=htmlentities(stripslashes($row['kode_produk']));
		$row_set[] = $row;
}
//data hasil query yang dikirim kembali dalam format json
echo json_encode($row_set);
?>