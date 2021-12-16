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
				<?php if (isset($_POST[submit])){ ?>
				text: 'Laporan Jumlah Data Penjualan Tahun <?php echo " $_POST[tahun]"; ?>'
				<?php }else{ ?>
				text: 'Laporan Jumlah Data Penjualan Tahun <?php echo " $tahun"; ?>'
				<?php } ?>
         },
         xAxis: {
            categories: ['Kode / Nama Produk Yang Terjual']
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
			if (isset($_POST[submit])){
				$sql   = "SELECT * FROM (SELECT a.*, b.tgl_order, b.jam_order, substring(tgl_order,1,4) as tahun, e.kode_produk 
						FROM `orders_detail` a JOIN orders b ON a.id_orders=b.id_orders 
							JOIN produk e ON a.id_produk=e.id_produk) c 
								where c.tahun='$_POST[tahun]' LIMIT 20";
			}else{
				$sql   = "SELECT * FROM (SELECT a.*, b.tgl_order, b.jam_order, substring(tgl_order,1,4) as tahun, e.kode_produk 
						FROM `orders_detail` a JOIN orders b ON a.id_orders=b.id_orders 
							JOIN produk e ON a.id_produk=e.id_produk) c 
								where c.tahun='$tahun' LIMIT 20";
			}
            $query = mysql_query( $sql )  or die(mysql_error());
            while( $ret = mysql_fetch_array( $query ) ){
            	$jenis=$ret['id_produk'];     
				$kode=$ret['kode_produk'];  
				$sql_jumlah = "SELECT SUM(jumlah) as jumlah FROM orders_detail where id_produk='$jenis'";  
                 $query_jumlah = mysql_query( $sql_jumlah ) or die(mysql_error());
                 while( $data = mysql_fetch_array( $query_jumlah ) ){
					$jumlah = $data['jumlah']; 
                  }             
                  ?>
                  {
                      name: '<?php echo $kode;?>',
                      data: [<?php echo $jumlah; ?>]
                  },
                  <?php } ?>
            ]
      });
   });	
</script>
		<div id='containerj'></div>		
