<?php
session_start();
error_reporting(0);
include "../config/koneksi.php";
include "../config/library.php";
include "../config/session_member.php";

$module=$_GET[module];
$act=$_GET[act];
$kd = mysql_fetch_array(mysql_query("SELECT * FROM produk where kode_produk='$_GET[id]'"));
if ($module=='keranjang' AND $act=='tambah'){
	$sid = $_SESSION[namauser];
	$in = mysql_fetch_array(mysql_query("SELECT a.id_produk, sum(a.jumlah) as masuk FROM `produk_pembelian` a where a.id_produk='$kd[id_produk]'"));
    $out = mysql_fetch_array(mysql_query("SELECT a.id_produk, sum(a.jumlah) as keluar FROM `orders_detail` a where a.id_produk='$kd[id_produk]'"));
    $stok = $in[masuk]-$out[keluar];
	
	$st=mysql_fetch_array(mysql_query("SELECT sum(jumlah) as jumlah FROM orders_temp WHERE id_produk='$kd[id_produk]'"));
	
  if ($stok <= 0){
  	if ($_GET[cust]==''){
      	echo "<script>window.alert('Maaf, Stok Produk Habis $total_stok..');
			window.location=('media.php?module=keranjangbelanja&stat=$_GET[stat]')</script>";
  	}else{
  		echo "<script>window.alert('Maaf, Stok Produk Habis $total_stok..');
			window.location=('media.php?module=keranjangbelanja&stat=$_GET[stat]&cust=$_GET[cust]')</script>";
  	}
  }elseif ($stok < $st[jumlah]){
  	if ($_GET[cust]==''){
	  	echo "<script>window.alert('Maaf, Stok Produk Tidak Mencukupi..');
			window.location=('media.php?module=keranjangbelanja&stat=$_GET[stat]')</script>";
	  }else{
	  	echo "<script>window.alert('Maaf, Stok Produk Tidak Mencukupi..');
			window.location=('media.php?module=keranjangbelanja&stat=$_GET[stat]&cust=$_GET[cust]')</script>";
	  }
  }
  else{
	// check if the product is already
	// in cart table for this session
	$sql = mysql_query("SELECT id_produk FROM orders_temp
			WHERE id_produk='$kd[id_produk]' AND id_session='$sid'");
	$ketemu=mysql_num_rows($sql);
	if ($ketemu==0){
		// put the product in cart table
		mysql_query("INSERT INTO orders_temp (id_produk, jumlah, id_session, tgl_order_temp, jam_order_temp)
				VALUES ('$kd[id_produk]', 1, '$sid', '$tgl_sekarang', '$jam_sekarang')");
	} else {
		// update product quantity in cart table
		mysql_query("UPDATE orders_temp 
		        SET jumlah = jumlah + 1
				WHERE id_session ='$sid' AND id_produk='$kd[id_produk]'");		
	}	
	header('Location:media.php?module=keranjangbelanja&stat='.$_GET[stat].'&cust='.$_GET[cust].'');
  }				
}

elseif ($module=='keranjang' AND $act=='hapus'){
	mysql_query("DELETE FROM orders_temp WHERE id_orders_temp='$_GET[id]'");
	if ($_GET[cust]==''){
		header('Location:media.php?module=keranjangbelanja&stat='.$_GET[stat].'');
	}else{
		header('Location:media.php?module=keranjangbelanja&stat='.$_GET[stat].'&cust='.$_GET[cust].'');
	}			
}

elseif ($module=='keranjang' AND $act=='update'){
  $id       = $_POST[id];
  $jml_data = count($id);
  $stok   = $_POST[stok]; 
  $jumlah   = $_POST[jml]; // quantity
for ($i=1; $i <= $jml_data; $i++){
	if ($jumlah[$i] > $stok[$i]){
			echo "<script>window.alert('Maaf, Stok Produk Tidak Mencukupi..');
        			window.location=('keranjang-belanja-$_GET[stat].html')</script>";
	}else{
    	mysql_query("UPDATE orders_temp SET jumlah = '".$jumlah[$i]."'
                                      WHERE id_orders_temp = '".$id[$i]."'");
	}
}
	if ($_GET[cust]==''){
		header('Location:media.php?module=keranjangbelanja&stat='.$_GET[stat].'');
	}else{
		header('Location:media.php?module=keranjangbelanja&stat='.$_GET[stat].'&cust='.$_GET[cust].'');
	}			
}
?>
