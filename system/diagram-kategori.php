<?php 
$bulan = date("m");
$tahun = date("Y");
?>
<script type="text/javascript">
var chart1;
$(document).ready(function() {
      chart1 = new Highcharts.Chart({
         chart: {
            renderTo: 'containerj',
            type: 'column'
         },   
         title: {
				text: 'Laporan Jumlah Data Penjualan Terlaris Perkategori'
         },
         xAxis: {
            categories: ['Kategori Produk']
         },
         yAxis: {
            title: {
				text: 'Jumlah Penjualan'
            }
         },
              series:             
            [
            <?php 
			include "../config/koneksi.php";
				$sql   = "SELECT * FROM kategori_produk";

            $query = mysql_query( $sql )  or die(mysql_error());
            while( $ret = mysql_fetch_array( $query ) ){
            	$jenis=$ret['id_kategori'];     
				$nama=$ret['nama_kategori'];  
					$sql_jumlah = "SELECT SUM(a.jumlah) as jumlah FROM orders_detail a 
									JOIN orders b ON a.id_orders=b.id_orders 
										JOIN produk c ON a.id_produk=c.id_produk 
											where c.id_kategori='$jenis'";  
				 $query_jumlah = mysql_query( $sql_jumlah ) or die(mysql_error());
                 while( $data = mysql_fetch_array( $query_jumlah ) ){
					$jumlah = $data['jumlah']; 
                  }             
                  ?>
                  {
                      name: '<?php echo $nama;?>',
                      data: [<?php echo $jumlah; ?>]
                  },
                  <?php } ?>
            ]
      });
   });	
</script>
		<div id='containerj'></div>		
