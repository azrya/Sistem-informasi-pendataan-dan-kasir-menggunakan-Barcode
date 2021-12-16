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

echo "<center><h2 style='margin-bottom:3px;'>MyStock (POS)</h2>
					Jl. Raya Wonorejo, Poncokusumo, Malang<br>
					No Telpon. 082234263768</center><hr/>";
echo "  <center><h2>Return Data Produk</h2></center>
		<table width=100% cellpadding=6>
          <tr style='color:#fff; height:35px;' bgcolor=#000>
          <th class='data'>No</th>
				<th class='data'>Kode Produk</th>
				<th class='data'>Nama Produk</th>
				<th class='data'>Nama Supplier</th>
				<th class='data'>Harga Pokok</th>
				<th class='data'>Jumlah</th>
          </tr>";


    $tampil = mysql_query("SELECT c.nama_supplier, a.id_return, a.id_produk, a.id_supplier, a.jumlah, b.kode_produk, b.nama_produk, b.harga, b.harga_grosir, b.harga_pokok, b.satuan  
					FROM `return_produk`a JOIN produk b ON a.id_produk=b.id_produk JOIN supplier c ON a.id_supplier=c.id_supplier ORDER BY a.id_return DESC");
  
    $no = $posisi+1;
    while($r=mysql_fetch_array($tampil)){
      $tanggal=tgl_indo($r[tgl_masuk]);
      $harga=format_rupiah($r[harga]);
	  $harga_pokok=format_rupiah($r[harga_pokok]);
	  $harga_grosir=format_rupiah($r[harga_grosir]);

	  $in = mysql_fetch_array(mysql_query("SELECT a.id_produk, sum(a.jumlah) as masuk FROM `produk_pembelian` a where a.id_produk='$r[id_produk]'"));
      $out = mysql_fetch_array(mysql_query("SELECT a.id_produk, sum(a.jumlah) as keluar FROM `orders_detail` a where a.id_produk='$r[id_produk]'"));
      $stok = $in[masuk]-$out[keluar];

	  if(($no % 2)==0){
			$warna="#ffffff";
		  }
		  else{
			$warna="#E1E1E1";
		  }
      echo "<tr class='data'><td class='data'>$no</td>
				<td class='data'>$r[kode_produk]</td>
                <td class='data'>$r[nama_produk]</td>
                <td class='data'>$r[nama_supplier]</td>
				<td class='data'>Rp $harga_pokok</td>
				<td class='data' align=center>$r[jumlah] $r[satuan]</td>
			</tr>";
      $no++;
    }
    echo "</table><br/><tr><td><br/><span style='float:right; text-align:center;'>MyStock, $tgl_sekarang <br/>
										Karyawan<br/></br></br>
								(.............................................)
								<br/>$_SESSION[namalengkap]</span></td></tr>";
?>