<?php 
  session_start();
  error_reporting(0);
  ?>
<head>
<title>Report - MyStock</title>
<style>
.input1 {
	height: 20px;
	font-size: 12px;
	padding-left: 5px;
	margin: 5px 0px 0px 5px;
	width: 97%;
	border: none;
	color: red;
}
table {
	border: 1px solid #cecece;
}
.td {
	border: 1px solid #cecece;
}
#kiri{
width:50%;
float:left;
}

#kanan{
width:50%;
float:right;
padding-top:20px;
margin-bottom:9px;
}
</style>
</head>

<body onload="window.print()">
<?php 
  include "../config/koneksi.php";
  include "../config/fungsi_indotgl.php";
  include "../config/library.php";
  include "../config/fungsi_rupiah.php";

  if ($_GET[stat]=='1'){
    $status = 'Eceran';
    $sql2=mysql_query("SELECT orders_detail.*, produk.harga as harga, produk.kode_produk, produk.nama_produk, produk.id_produk, produk.satuan FROM orders_detail, produk 
                          WHERE orders_detail.id_produk=produk.id_produk AND orders_detail.id_orders='$_GET[id]'");
  }else{
    $status = 'Grosir';
    $sql2=mysql_query("SELECT orders_detail.*, produk.harga_grosir as harga, produk.kode_produk, produk.nama_produk, produk.id_produk, produk.satuan FROM orders_detail, produk 
                          WHERE orders_detail.id_produk=produk.id_produk AND orders_detail.id_orders='$_GET[id]'");
  }  

echo "<center><h2 style='margin-bottom:3px; text-transform:uppercase'>$status - FHM Repair Works</h2>
   Jl. Raya Wonorejo - Poncokusumo<br>
	No Telpon. 082234263768.</center><hr/>";
				
   $edit = mysql_query("SELECT * FROM orders a JOIN costumer b ON a.id_costumer=b.id_costumer WHERE a.id_orders='$_GET[id]'");
    $r    = mysql_fetch_array($edit);
    $tanggal=tgl_indo($r[tgl_order]);
		
    echo "<div class='post_title'><b>Detail Informasi Order.</b></div>
          <form method=POST action=$aksi?module=order&act=update>
          <input type=hidden name=id value=$r[id_orders]>
          <table width=100%>
          <tr><td style='width:200px'>No. Faktur</td>        <td> : $r[no_orders]</td></tr>
          <tr><td style='width:200px'>Nama Costumer</td>        <td> : $r[nama_costumer]</td></tr>
          <tr><td>Tgl. & Jam Order</td> <td> : $tanggal & $r[jam_order]</td></tr>
          </table></form>";

  // tampilkan rincian produk yang di order
  
  
  echo "<table width=100%>
        <tr style='color:#fff; height:35px;' bgcolor=#000><th>Nama Produk</th><th>Jumlah</th><th>Harga $status</th><th>Sub Total</th></tr>";
  
  while($s=mysql_fetch_array($sql2)){
     // rumus untuk menghitung subtotal dan total	
	  $subtotal1    = ($s[harga] * $s[jumlah])* $s[diskon]/100 ;
    $subtotal2    = $s[harga] * $s[jumlah] ;
	  $subtotal    = $subtotal2 - $subtotal1 ;
    $total       = $total + $subtotal;
    $subtotal_rp = format_rupiah($subtotal);    
    $total_rp    = format_rupiah($total);    
    $harga       = format_rupiah($s[harga]);
    echo "<tr><td>$s[nama_produk]</td><td>$s[jumlah] $s[satuan]</td><td>Rp. $harga</td><td>Rp. $subtotal_rp</td></tr>";
  }   
		
    $by = mysql_fetch_array(mysql_query("SELECT * FROM orders where id_orders='$_GET[id]'")); 
    $kembali = $by[bayar] - $total;

echo "<div class='post_title'><b>Detail Order yang harus di bayar.</b></div>
	  <table width=100%>
    <tr><td width=120px>Total</td><td> Rp. <b>$total_rp</b></td></tr>   
    <tr><td>Bayar</td><td> Rp. <b>".format_rupiah($by[bayar])."</b></td></tr>   
    <tr><td>Kembali</td><td> Rp. <b>".format_rupiah($kembali)."</b></td></tr>   
      </table>
	  
	  <tr><td col><br/><span style='float:right; text-align:center;'> FHM Repair Works, $tgl_sekarang <br/>
										Petugas<br/></br></br>
								(.............................................)
								<br/>$_SESSION[namalengkap]</span></td></tr>";
if ($_GET[page]=='report'){

}else{
echo "<script>window.alert('Oke !!!');
        window.location=('keranjang-belanja-$_GET[stat].html')</script>";  		
}					
?>