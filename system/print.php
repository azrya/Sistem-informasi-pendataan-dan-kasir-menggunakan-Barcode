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

  $filter = $_POST[tahun].'-'.$_POST[bulan];
  $bulan = Bulan($_POST[bulan]);
  
if (isset($_POST[submit])){
echo "<center><h2 style='margin-bottom:3px;'>FHM Repair Works</h2>
    Laporan Daftar belanja pada : $bulan $_POST[tahun]<br>
	Jl. Raya Wonorejo - Poncokusumo<br>
	No Telpon. 082234263768</center><hr/>
				
		  <table width=100% cellpadding=6>
          <tr style='color:#fff; height:35px;' bgcolor=#000><th>No</th><th>No.order</th><th>Nama Kasir</th><th>Tgl. Pesan</th><th>Jam</th></tr>";
		  
		  $tampil=mysql_query("SELECT * FROM orders");
		  $no = $no+1;
		  while ($r=mysql_fetch_array($tampil)){
		  $tanggal=tgl_indo($r[tgl_order]);
		  echo "<tr bgcolor=$warna>
				<td align=center>$no</td>
				<td align=center>$r[id_orders]</td>
                <td>$r[nama_kasir]</td>
                <td>$tanggal</td>
                <td>$r[jam_order]</td></tr>";
				$no++;
}
echo "</table><tr><td><br/><span style='float:right; text-align:center;'> FHM Repair Works , $tgl_sekarang <br/>
										Petugas<br/></br></br>
								(.............................................)
								<br/>$_SESSION[namalengkap]</span></td></tr>";
}else{
echo "<center><h2 style='margin-bottom:3px;'>Nama Toko</h2>
    Laporan Daftar belanja pada : $bulan $_POST[tahun]<br>
	Alamat<br>
	Nomor Telpon</center><hr/>
				
		  <table width=100% cellpadding=6>
          <tr style='color:#fff; height:35px;' bgcolor=#000><th>No</th><th>No.order</th><th>Nama Produk</th><th>Berat</th><th>Jumlah</th><th>Harga</th><th>Subtotal</th></tr>";
		  
		  $tampil=mysql_query("SELECT orders_detail.jumlah * produk.harga as total, sum(orders_detail.jumlah) as jumlahh,  orders.id_orders, orders.nama_kustomer, orders.telpon, orders.tgl_order, orders.jam_order, orders.status_order, orders_detail.jumlah, produk.nama_produk, produk.berat, produk.harga  FROM orders left join orders_detail on orders.id_orders=orders_detail.id_orders 
		  left join produk on orders_detail.id_produk=produk.id_produk where tgl_order like '$filter%' GROUP BY orders.id_orders");
		  $no = $no+1;
		  while ($r=mysql_fetch_array($tampil)){
		  $tanggal=tgl_indo($r[tgl_order]);
		  echo "<tr bgcolor=$warna>
				<td align=center>$no</td>
				<td align=center>$r[id_orders]</td>
                <td>$r[nama_produk]</td>
				<td>$r[berat] Kg</td>
				<td>$r[jumlah]</td>
                <td>Rp $r[harga]</td>
                <td>Rp $r[total]</td></tr>";
				$no++;
}
echo "</table><tr><td><br/><span style='float:right; text-align:center;'> MyStock, $tgl_sekarang <br/>
										Petugas<br/></br></br>
								(.............................................)
								<br/>$_SESSION[namalengkap]</span></td></tr>";
}
?>