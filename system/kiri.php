<script>
function validasi(form){
		  if (form.id.value == ""){
			alert("Anda belum mengisikan Kode Barang.");
			form.id.focus();
			return (false);
		  }
}
</script>
<?php
include "../config/session_member.php";
if ($_GET[module]=='home'){
	echo "<h3>Selamat datang $_SESSION[namalengkap] 
	<span style='float:right'><a href='media.php?module=home&grafik=kategori'><input type='button' value='Grafik Kategori'></a> <a href='media.php?module=home&grafik=bulanan'><input type='button' value='Grafik Bulanan'></a> <a href='media.php?module=home&grafik=tahunan'><input type='button' value='Grafik Tahunan'></a></h3>";
	
	if ($_GET['grafik'] == 'kategori'){
		include "diagram.php"; 
	}elseif ($_GET['grafik'] == 'tahunan'){
		include "diagram-tahun.php"; 
	}else{
		include "diagram-kategori.php"; 
	}

	
}elseif ($_GET[module]=='edithome'){
  $sql=mysql_query("SELECT * FROM statis WHERE halaman='home'");
  $r=mysql_fetch_array($sql);
    echo "<h3>Edit Data Home Page</h3><br>
		  <table width=100%>
			<form action='' method='POST'>
			 <input type='text' name='judul' value='$r[judul]' style='width:70%'>
			 <textarea style='width:100%' height:400px' name='detail'>$r[detail]</textarea><br><br>
			 <input type='submit' name='submit' value='Update Data'>
			</form>
		  </table>"; 
		  
	if (isset($_POST[submit])){
		mysql_query("UPDATE statis SET judul='$_POST[judul]', detail='$_POST[detail]' where halaman='home'");
		header('location:home');
	}
}

elseif ($_GET[module]=='faktur'){
  		echo "<h3>Semua Transaksi Barang Masuk</h3>
		<span style='float:right;'>
			<form action='' method='POST' style='margin-right:22px'>
			Cari no Faktur : <input type='text' name='kata' style='width:200px; margin-bottom:3px;'/>
			<input type='submit' name='cari' value='cari'>
			</form>
		</span><br/>
			 <div class='h_line'></div>";
		
	echo "<input type=button value='Buat Faktur Baru' onclick=\"window.location.href='media.php?module=tambahfaktur';\">
	<table class='data'>
			<tr class='data'>
				<th class='data' width='30px'>No</th>
				<th class='data'>No Faktur</th>
				<th class='data'>Jumlah Total</th>
				<th class='data'>Waktu Transaksi</th>
				<th class='data'>Action</th>
			</tr>";

    $p      = new Paging;
    $batas  = 10;
    $posisi = $p->cariPosisi($batas);
	if (isset($_POST[cari])){
		$tampil = mysql_query("SELECT * FROM faktur where no_faktur LIKE '%$_POST[kata]%'");
	}else{
		$tampil = mysql_query("SELECT * FROM faktur ORDER BY no_faktur DESC LIMIT $posisi,$batas");
	}
    $no = $posisi+1;
    while($r=mysql_fetch_array($tampil)){
	  $total = mysql_num_rows(mysql_query("SELECT * FROM produk_pembelian where id_faktur='$r[id_faktur]'"));
      $tgl = explode(' ',$r[tanggal]);
	  $tgll = $tgl[0];
	  $jam  = $tgl[1];
	  $tanggal=tgl_indo($tgll);
	  
      $harga=format_rupiah($r[harga]);
	  $harga_grosir=format_rupiah($r[harga_grosir]);
	  if(($no % 2)==0){ $warna="#ffffff"; }
	  else{ $warna="#E1E1E1"; }

	  if ($total == ''){ $tot = '0';
	  }else{ $tot = $total; }
	  
      echo "<tr bgcolor=$warna class='data'>
				<td class='data' width='30px'>$no</td>
				<td class='data'>$r[no_faktur]</td>
				<td class='data'><b style='color:Red'>$tot</b> Produk</td>
				<td class='data'>$tanggal, $jam WIB</td>
				<td class='data' width='35px'><center>
				<a href='detail-produk-$r[no_faktur].html'><input type='button' value=' Lihat / Tambah Transaksi '></a>
				</center>
				</td>
			</tr>";
      $no++;
    }
	$jmldata = mysql_num_rows(mysql_query("SELECT * FROM faktur"));
    $jmlhalaman  = $p->jumlahHalaman($jmldata, $batas);
    $linkHalaman = $p->navHalaman($_GET[halaman], $jmlhalaman);
    echo "</table>Halaman : $linkHalaman";
}

elseif ($_GET[module]=='tambahfaktur'){
	$tanggal = date("Y-m-d H:i:s");
	if (isset($_POST[simpan])){
		mysql_query("INSERT INTO faktur (no_faktur, tanggal) VALUES ('$_POST[no]','$_POST[tgl]')");
		echo "<script>window.alert('Sukses Tambahkan Faktur Baru');
        window.location=('faktur.html')</script>";
	}
	echo "<h3>Tambah Produk Baru di Faktur : $_GET[no]</h3><br/>
          <form method=POST action='' enctype='multipart/form-data'>
          <table>
		  <tr><td width=130>No Faktur</td>     <td> : <input type=text name='no' size=15></td></tr>
		  <tr><td>Tanggal Transaksi</td>     <td> : <input type=text name='tgl' value='$tanggal' size=30></td></tr>
		  <tr><td colspan=2><br/><input style='float:right;' type=button value=Batal onclick=self.history.back()>
							<input style='float:right; margin-right:5px;' name='simpan' type=submit value=Simpan></td></tr>
          </table></form>";
}

elseif ($_GET[module]=='semuaproduk'){
  		echo "<h3>Semua Produk / Barang</h3>
		<span style='float:right;'>
			<form action='' method='POST' style='margin-right:22px'>
			Cari Nama Produk : <input type='text' name='kata' style='width:200px; margin-bottom:3px;'/>
			<input type='submit' name='cari' value='cari'>
			</form>
		</span><br/>
			 <div class='h_line'></div>";
		
	echo "<table class='data'>
			<tr class='data'>
				<th class='data' width='30px'>No</th>
				<th class='data'>Kode Produk</th>
				<th class='data'>Nama Produk</th>
				<th class='data'>Harga Ecer</th>
				<th class='data'>Harga Grosir</th>
				<th class='data'>Stok</th>
				<th class='data'>Action</th>
			</tr>";

    $p      = new Paging;
    $batas  = 10;
    $posisi = $p->cariPosisi($batas);
	if (isset($_POST[cari])){
		$tampil = mysql_query("SELECT * FROM produk where nama_produk LIKE '%$_POST[kata]%' OR kode_produk LIKE '%$_POST[kata]%'");
	}else{
		$tampil = mysql_query("SELECT * FROM produk ORDER BY id_produk DESC LIMIT $posisi,$batas");
	}
    $no = $posisi+1;
    while($r=mysql_fetch_array($tampil)){
	$in = mysql_fetch_array(mysql_query("SELECT a.id_produk, sum(a.jumlah) as masuk FROM `produk_pembelian` a where a.id_produk='$r[id_produk]'"));
    $out = mysql_fetch_array(mysql_query("SELECT a.id_produk, sum(a.jumlah) as keluar FROM `orders_detail` a where a.id_produk='$r[id_produk]'"));
    $ret = mysql_fetch_array(mysql_query("SELECT a.id_produk, sum(a.jumlah) as returnn FROM `return_produk` a where a.id_produk='$r[id_produk]'"));
    $stok = ($in[masuk]-$out[keluar])-$ret[returnn];

	  $tanggal=tgl_indo($r[tgl_masuk]);
      $harga=format_rupiah($r[harga]);
	  $harga_grosir=format_rupiah($r[harga_grosir]);
	  if(($no % 2)==0){
			$warna="#ffffff";
	  }else{
			$warna="#E1E1E1";
	  }
      echo "<tr bgcolor=$warna class='data'>
				<td class='data' width='30px'>$no</td>
				<td class='data'>$r[kode_produk]</td>
				<td class='data'>$r[nama_produk]</td>
				<td class='data'>Rp $harga</td>
				<td class='data'>Rp $harga_grosir</td>";
				if ($stok <= 0){
					echo "<td style='background:red; color:#fff' class='data' align=center>Habis</td>";
				}else{
					echo "<td class='data' align=center>$stok $r[satuan]</td>";
				}
		  echo "<td class='data' width='35px'>
				<center>
				<a href='aksi.php?module=keranjang&act=tambah&id=$r[kode_produk]&stat=$_GET[stat]&cust=$_GET[cust]'><img src='../mos-css/img/oke.png'></a>&nbsp;
				</center>
				</td>
			</tr>";
      $no++;
    }
	$jmldata = mysql_num_rows(mysql_query("SELECT * FROM produk"));
    $jmlhalaman  = $p->jumlahHalaman($jmldata, $batas);
    $linkHalaman = $p->navHalaman($_GET[halaman], $jmlhalaman);
    echo "</table>Halaman : $linkHalaman";
}

elseif ($_GET[module]=='keranjangbelanja'){
	if ($_GET[stat]=='1'){
		$status = 'Eceran';
		$sql = mysql_query("SELECT orders_temp.*, produk.harga as harga, produk.kode_produk, produk.nama_produk, produk.id_produk, produk.satuan FROM orders_temp, produk 
			                WHERE id_session='$_SESSION[namauser]' AND orders_temp.id_produk=produk.id_produk");
	}else{
		$status = 'Grosir';
		$sql = mysql_query("SELECT orders_temp.*, produk.harga_grosir as harga, produk.kode_produk, produk.nama_produk, produk.id_produk, produk.satuan FROM orders_temp, produk 
			                WHERE id_session='$_SESSION[namauser]' AND orders_temp.id_produk=produk.id_produk");
	}

  echo "<h3>Halaman untuk Transaksi $status.</h3><br>
			 <div class='h_line'></div>"; 
	?><form method='GET' action='aksi.php' onSubmit="return validasi(this)"> <?php
				echo "<input type='hidden' name='module' value='keranjang'>
				<input type='hidden' name='act' value='tambah'>
				<input type='hidden' name='stat' value='$_GET[stat]'>
				<input type='hidden' name='cust' value='$_GET[cust]'>
				<input id='kodeproduk' style='width:120px' type='text' name='id' placeholder='Barcode / Kode' autofocus>
				<input type='submit' value='Ok' style='margin-right:5px'>
				<input type=button value='Cari Produk' onclick=\"window.location.href='media.php?module=semuaproduk&stat=$_GET[stat]&cust=$_GET[cust]';\">
				<input type=button value='Cari Customer' onclick=\"window.location.href='semua-customer.html';\">
		  	</form>";

  $ketemu=mysql_num_rows($sql);
  	if ($_GET[cust]!=''){
  		$cs = mysql_fetch_array(mysql_query("SELECT * FROM costumer where id_costumer='$_GET[cust]'"));
  		echo "<table>
  					<tr><td width=110px>Nama Customer </td> 	<td> : $cs[nama_costumer]</td></tr>
  					<tr><td>No Telpon </td> 		<td> : $cs[no_telpon]</td></tr>
  					<tr><td>Alamat Lengkap </td> 	<td> : $cs[alamat_lengkap]</td></tr>
  			  </table>";
  	}


    echo "<form method=post action=aksi.php?module=keranjang&act=update&stat=$_GET[stat]&cust=$_GET[cust]>";
	echo "<table border=1 class='data'>
			<tr class='data'>
				<th class='data' width='40px'>Kode </th>
				<th class='data'>Nama Produk</th>
				<th class='data' width=90px>Jumlah</th>
				<th class='data' width=90px>Harga $status</th>
				<th class='data' width=90px>Subtotal</th>
				<th class='data' width='70px'></th>
			</tr>"; 
  
  $no=1;
  while($r=mysql_fetch_array($sql)){
    $subtotal    = $r[harga] * $r[jumlah];
	$subtotaldiskon = $subtotal * $r[diskon]/100;
	$diskontotal = $subtotal - $subtotaldiskon;
    $total       = $total + $subtotal - $subtotaldiskon;  
    $subtotal_rp = format_rupiah($diskontotal);
    $total_rp    = format_rupiah($total);
    $harga       = format_rupiah($r[harga]);
    if(($no % 2)==0){ $warna="#E1E1E1"; }
	else{ $warna="#ffffff"; }

	$in = mysql_fetch_array(mysql_query("SELECT a.id_produk, sum(a.jumlah) as masuk FROM `produk_pembelian` a where a.id_produk='$r[id_produk]'"));
    $out = mysql_fetch_array(mysql_query("SELECT a.id_produk, sum(a.jumlah) as keluar FROM `orders_detail` a where a.id_produk='$r[id_produk]'"));
    $stok = $in[masuk]-$out[keluar];

    echo "<tr bgcolor=$warna><input type=hidden name=id[$no] value=$r[id_orders_temp]>
              <td class='data' width='60px'>$r[kode_produk]</td>
			  <td  class='data' >$r[nama_produk]</td>
			  <input type=hidden name='stok[$no]' value='$stok' size=4>
              <td  class='data'><input style='margin-top:-5px; padding-top:2px' type=text name='jml[$no]' value='$r[jumlah]' size=1 onkeypress=\"return harusangka(event)\"> $r[satuan]</td>
              <td  class='data'>Rp $harga</td>
              <td  class='data' >Rp $subtotal_rp</td>
              <td  class='data' align=center><a href='aksi.php?module=keranjang&act=hapus&id=$r[id_orders_temp]&stat=$_GET[stat]&cust=$_GET[cust]'>Hapus</a></td>
          </tr>";
    $no++; 
  } 
	  echo "<tr><td colspan=2><input class='belanja' style='width:120px;' type=submit value='Update' border=0></td>
	  			<td colspan=2 align=right><br><b style='font-size:24px; color:#99CC00;'>Total : Rp. </b> </td>  
	  				<td colspan=2><input type='text' style='width:130px; font-weight:bold; padding:2px; font-size:24px; color:red; width:100%; background:#E1E1E1' value='$total_rp' disabled></td>
			</tr><br>
			</form>

			
			<form method=post action=media.php?module=simpantransaksi&stat=$_GET[stat]&cust=$_GET[cust]>
			<tr>	
				<td colspan=4 align=right><b style='font-size:24px; color:#99CC00;'>Bayar : Rp. </b> </td>
				<td colspan=2><input type='text' name='bayar' style='width:130px; font-weight:bold; padding:2px; font-size:24px; color:blue; width:100%' id='type2' onkeyup=\"kalkulatorTambah(getElementById('type1').value,this.value);\"></td>
				<input type='hidden' value='$_GET[stat]' name='status'>																														 <input type='hidden' style='width:130px; font-weight:bold; padding:2px; font-size:24px; color:red;' value='$total' disabled id='type1' onkeyup=\"kalkulatorTambah(this.value,getElementById('type2').value);\">
			</tr>
			<tr>	
				<td colspan=4 align=right><b style='font-size:24px; color:#99CC00; '>Kembali : Rp. </b></td>
				<td colspan=2><input type='text' style='width:130px; font-weight:bold; padding:2px; font-size:24px; color:#000; width:100%; background:#E1E1E1' id='result' readonly=on></td>
			</tr>

			<tr>
				<td colspan=6 align=right><br /><input class='belanja' type='submit' value='Selesai' style='height:36px; width:150px;'></a><br /></td>
			</tr>

        </table></form><br />";
  
}

elseif ($_GET[module]=='semuacostumer'){
  		echo "<h3>Semua Data Customer <span style='float:right'><a style='float:right;' target='_BALNK' href='print-customer.php'>Cetak Laporan Customer</a></span></h3></h3>
		<span style='float:right;'>
			<form action='' method='POST' style='margin-right:22px'>
			Cari Customer : <input type='text' name='kata' style='width:200px; margin-bottom:3px;' placeholder='Input Nama atau No Telpon'/>
			<input type='submit' name='cari' value='cari'>
			</form>
		</span><br/>
			 <div class='h_line'></div>";
		
	echo "<input type=button value='Tambahkan Customer' onclick=\"window.location.href='media.php?module=semuacostumer&custt=tambah';\">
		  <table class='data'>";
		  	if ($_GET[custt]=='tambah'){
			  	echo "<form action='' method='POST'><tr class='data'>
					<th class='data' width='30px'></th>
					<th class='data'><input type='text' name='a' placeholder='Input Nama'></th>
					<th class='data'><input type='text' name='b' placeholder='Input No Telp'></th>
					<th class='data' colspan='2'><input type='text' name='c' placeholder='Input Alamat'></th>
					<th class='data'><input type='submit' name='d' value='simpan'></th>
				</tr></form>";
			}

			if ($_GET[custt]=='update'){
				$m = mysql_fetch_array(mysql_query("SELECT * FROM costumer where id_costumer='$_GET[id]'"));
			  	echo "<form action='' method='POST'><tr class='data'>
					<th class='data' width='30px'></th>
					<input type='hidden' value='$m[id_costumer]' name='id'>
					<th class='data'><input type='text' name='a' value='$m[nama_costumer]' placeholder='Input Nama'></th>
					<th class='data'><input type='text' name='b' value='$m[no_telpon]' placeholder='Input No Telp'></th>
					<th class='data' colspan='2'><input type='text' name='c' value='$m[alamat_lengkap]' placeholder='Input Alamat'></th>
					<th class='data'><input type='submit' name='da' value='Update'></th>
				</tr></form>";
			}

			if (isset($_POST[d])){
				mysql_query("INSERT INTO costumer (nama_costumer, no_telpon, alamat_lengkap) VALUES ('$_POST[a]','$_POST[b]','$_POST[c]')");
				echo "<script>window.alert('Data Berhasil Ditambah');
						window.location=('semua-customer.html')</script>";
			}

			if (isset($_POST[da])){
				mysql_query("UPDATE costumer SET nama_costumer = '$_POST[a]',
												 no_telpon 	   = '$_POST[b]',
												 alamat_lengkap = '$_POST[c]' where id_costumer='$_POST[id]'");
				echo "<script>window.alert('Data Berhasil Diupdate');
						window.location=('semua-customer.html')</script>";
			}

			if (isset($_GET[delete])){
				mysql_query("DELETE FROM costumer where id_costumer='$_GET[delete]'");
				echo "<script>window.alert('Data Berhasil Dihapus');
						window.location=('semua-customer.html')</script>";
			}

			echo "<tr class='data'>
				<th class='data' width='30px'>No</th>
				<th class='data'>Nama Customer</th>
				<th class='data'>No Telpon</th>
				<th class='data'>Alamat Lengkap</th>
				<th class='data'>Total Transaksi</th>
				<th class='data' width='100px'>Action</th>
			</tr>";

    $p      = new Paging;
    $batas  = 10;
    $posisi = $p->cariPosisi($batas);
	if (isset($_POST[cari])){
		$tampil = mysql_query("SELECT * FROM costumer where nama_costumer LIKE '%$_POST[kata]%' OR no_telpon LIKE '%$_POST[kata]%'");
	}else{
		$tampil = mysql_query("SELECT * FROM costumer ORDER BY id_costumer ASC LIMIT $posisi,$batas");
	}
    $no = $posisi+1;
    while($r=mysql_fetch_array($tampil)){
      $tanggal=tgl_indo($r[tgl_masuk]);
      $harga=format_rupiah($r[harga]);
	  $harga_grosir=format_rupiah($r[harga_grosir]);
	  if(($no % 2)==0){ $warna="#ffffff"; }else{ $warna="#E1E1E1"; }
	  $cek = mysql_fetch_array(mysql_query("SELECT count(*) as total FROM orders where id_costumer='$r[id_costumer]'"));
      echo "<tr bgcolor=$warna class='data'>
				<td class='data' width='30px'>$no</td>
				<td class='data'>$r[nama_costumer]</td>
				<td class='data'>$r[no_telpon]</td>
				<td class='data'>$r[alamat_lengkap]</td>
				<td class='data'>$cek[total] Kali</td>
				<td valign=top><center>
					<a href='media.php?module=keranjangbelanja&stat=1&cust=$r[id_costumer]'><img src='../mos-css/img/oke.png'></a>&nbsp;";
					if ($_SESSION[leveluser]=='Admin'){
						echo "<a href='media.php?module=semuacostumer&custt=update&id=$r[id_costumer]'><img src='../mos-css/img/detail.png'></a>&nbsp; 
							  <a href='media.php?module=semuacostumer&delete=$r[id_costumer]' style='color:red; font-weight:bold; '>X</a>&nbsp; ";
					} 
					echo "</center></td>
			</tr>";
      $no++;
    }
	$jmldata = mysql_num_rows(mysql_query("SELECT * FROM costumer"));
    $jmlhalaman  = $p->jumlahHalaman($jmldata, $batas);
    $linkHalaman = $p->navHalaman($_GET[halaman], $jmlhalaman);
    echo "</table>Halaman : $linkHalaman";

}

elseif ($_GET[module]=='statuspembelian'){
	if (strlen($_POST[bulan]) == 1 ){
		$bulann = '0'.$_POST[bulan];
	}else{
		$bulann = $_POST[bulan];
	}
	
	if (strlen($_POST[bulanx]) == 1 ){
		$bulannx = '0'.$_POST[bulanx];
	}else{
		$bulannx = $_POST[bulanx];
	}
	
	if (strlen($_POST[tanggal]) == 1 ){
		$tanggall = '0'.$_POST[tanggal];
	}else{
		$tanggall = $_POST[tanggal];
	}
	if (strlen($_POST[tanggalx]) == 1 ){
		$tanggallx = '0'.$_POST[tanggalx];
	}else{
		$tanggallx = $_POST[tanggalx];
	}
	
	$mulai = $_POST[tahun].'-'.$bulann.'-'.$tanggall;
	$selesai = $_POST[tahunx].'-'.$bulannx.'-'.$tanggallx;
    echo "<h3>Laporan Pembelian Barang / Produk</h3><br>";
	echo "<form style='float:right; margin-right:20px' method='POST' action='status-pembelian.html'> Filter 
													<select name='tanggal' class='select'>";
													if (isset($_POST[lihat])){
														echo "<option value='$_POST[tanggal]' selected>$_POST[tanggal]</option>";
													}else{
														echo "<option value='' selected> Tanggal </option>";
													}
													for($n=1; $n<=31; $n++){
															echo "<option value='$n'>$n</option>";
														}
															echo "</select>
															
														<select name='bulan' class='select'>"; 
													$bln = array('','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
													
													if (isset($_POST[lihat])){
														$bl = $_POST[bulan];
														echo "<option value='$_POST[bulan]' selected>$bln[$bl]</option>";
													}else{
														echo "<option value='' selected> Bulan </option>";
													}

														for($n=1; $n<=12; $n++){
															echo "<option value='$n'>$bln[$n]</option>";
														}
															echo "</select>
														
														<select name='tahun' class='select'>"; 
													if (isset($_POST[lihat])){
														echo "<option value='$_POST[tahun]' selected> $_POST[tahun] </option>";
													}else{
														echo "<option value='' selected> Tahun </option>";
													}
														
														$tah = date("Y");
														for($n=2014; $n<=$tah; $n++){ 
															echo "<option value='$n'>$n</option>";
														} 
														
													  echo "</select>
													  
													  &nbsp sampai &nbsp <select name='tanggalx' class='select'>";
													  
													if (isset($_POST[lihat])){
														echo "<option value='$_POST[tanggalx]' selected>$_POST[tanggalx]</option>";
													}else{
														echo "<option value='' selected> Tanggal </option>";
													}
													
													for($n=1; $n<=31; $n++){
															echo "<option value='$n'>$n</option>";
														}
															echo "</select>
															
														<select name='bulanx' class='select'>"; 
														
													$bln = array('','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
													
													if (isset($_POST[lihat])){
														$blx = $_POST[bulanx];
														echo "<option value='$_POST[bulanx]' selected>$bln[$blx]</option>";
													}else{
														echo "<option value='' selected> Bulan </option>";
													}
														
													for($n=1; $n<=12; $n++){
															echo "<option value='$n'>$bln[$n]</option>";
														}
															echo "</select> 
														<select name='tahunx' class='select'>"; 
														
													if (isset($_POST[lihat])){
														echo "<option value='$_POST[tahunx]' selected> $_POST[tahunx] </option>";
													}else{
														echo "<option value='' selected> Tahun </option>";
													}
													
														$tah = date("Y");
														for($n=2014; $n<=$tah; $n++){ 
															echo "<option value='$n'>$n</option>";
														} 
														
													  echo "</select>";
	echo " <input type='submit' name='lihat' value='Lihat'></form>";
	echo "<div class='h_line'></div>";
        echo "<table class='data'>
			<tr class='data'>
				<th class='data' width='70px'>No Faktur</th>
				<th class='data'>Nama Customer</th>
				<th class='data'>Nama Kasir</th>
				<th class='data'>Tanggal</th>
				<th class='data'>Jam</th>
				<th class='data'>Action</th>
			</tr>"; 

    $p      = new Paging;
    $batas  = 10;
    $posisi = $p->cariPosisi($batas);	
	
	if (isset($_POST[lihat])){
		$tampil = mysql_query("SELECT a.*, b.nama_costumer FROM orders a JOIN costumer b ON a.id_costumer=b.id_costumer where nama_kasir='$_SESSION[namalengkap]' AND tgl_order BETWEEN '$mulai' AND '$selesai'  ORDER BY id_orders DESC LIMIT $posisi,$batas");
	}else{
		$tampil = mysql_query("SELECT a.*, b.nama_costumer FROM orders a JOIN costumer b ON a.id_costumer=b.id_costumer where nama_kasir='$_SESSION[namalengkap]' ORDER BY id_orders DESC LIMIT $posisi,$batas");
    }
	while($r=mysql_fetch_array($tampil)){
      $tanggal=tgl_indo($r[tgl_order]);
	  if(($no % 2)==0){
    $warna="#ffffff";
  }
  else{
    $warna="#E1E1E1";
  }
      echo "<tr bgcolor=$warna>
				<td class='data' align=center>$r[no_orders]</td>
				<td class='data'>$r[nama_costumer]</td>
                <td class='data'>$r[nama_kasir]</td>
                <td class='data'>$tanggal</td>
                <td class='data'>$r[jam_order]</td>
				<td class='data'><center><a target='_BLANK' href='faktur.php?id=$r[id_orders]&stat=$r[status]&page=report'>Cetak Struk Penjualan</a></td>
			</tr>";
      $no++;
    }
	$jmldata = mysql_num_rows(mysql_query("SELECT * FROM orders where nama_kustomer='$_SESSION[namalengkap]'"));
    $jmlhalaman  = $p->jumlahHalaman($jmldata, $batas);
    $linkHalaman = $p->navHalaman($_GET[halaman], $jmlhalaman);

    echo "</table>";
    echo "<br/>Halaman: $linkHalaman<br>";
}

elseif ($_GET[module]=='diagram'){
	echo "<form style='float:right; margin-right:15px' method=POST action='diagram.html'>
							Filter : <select name='bulan'>
									<option value=0 selected>- Pilih Bulan -</option>";
									$tampil=mysql_query("SELECT substring(tgl_order,6,2) as bulan FROM orders GROUP BY substring(tgl_order,6,2)");
									while($r=mysql_fetch_array($tampil)){
									$bulan = Bulan($r[bulan]);
									  echo "<option value=$r[bulan]>$bulan</option>";
									}	
							echo "</select>
							<select name='tahun'>
									<option value=0 selected>- Pilih Tahun -</option>";
									$tampil=mysql_query("SELECT substring(tgl_order,1,4) as tahun FROM orders GROUP BY substring(tgl_order,1,4)");
									while($r=mysql_fetch_array($tampil)){
									  echo "<option value=$r[tahun]>$r[tahun]</option>";
									}
							echo "</select>
						<input type='submit' name='submit' class='submitt' value='View'>
					</form><br>";
	include "diagram.php";
}

elseif ($_GET[module]=='diagramtahun'){
	echo "<form style='float:right; margin-right:15px' method=POST action='tahun-diagram.html'>
							Filter : 
							<select name='tahun'>
									<option value=0 selected>- Pilih Tahun -</option>";
									$tampil=mysql_query("SELECT substring(tgl_order,1,4) as tahun FROM orders GROUP BY substring(tgl_order,1,4)");
									while($r=mysql_fetch_array($tampil)){
									  echo "<option value=$r[tahun]>$r[tahun]</option>";
									}
							echo "</select>
						<input type='submit' name='submit' class='submitt' value='View'>
					</form><br>";
	include "diagram-tahun.php";
}

elseif ($_GET[module]=='diagramkategori'){
	echo "<br><br><br>";
	include "diagram-kategori.php";
}

elseif ($_GET[module]=='simpantransaksi'){
$cekkeranjang = mysql_num_rows(mysql_query("SELECT * FROM orders_temp where id_session='$_SESSION[namauser]'"));
if ($cekkeranjang == 0){
	echo "<script>window.alert('Maaf Transaksi Tidak Dapat Di Proses !!!');
        window.location=('keranjang-belanja-$_GET[stat].html')</script>";  
}else{
function isi_keranjang(){
	$isikeranjang = array();
	$sid = $_SESSION[namauser];
	$sql = mysql_query("SELECT * FROM orders_temp WHERE id_session='$sid'");
	
	while ($r=mysql_fetch_array($sql)) {
		$isikeranjang[] = $r;
	}
	return $isikeranjang;
}

$tgl_skrg = date("Ymd");
$jam_skrg = date("H:i:s");

$c = mysql_fetch_array(mysql_query("SELECT count(*) as total FROM orders"));
$no_orders = 'S'.date("ymd").'-'.sprintf("%04d", $c[total]+1);

$totbayar = str_replace(".","",$_POST['bayar']);
mysql_query("INSERT INTO orders(no_orders, id_costumer, nama_kasir, tgl_order, jam_order, bayar, status) 
             VALUES('$no_orders','$_GET[cust]','$_SESSION[namalengkap]','$tgl_skrg','$jam_sekarang','$totbayar','$_POST[status]')");
  
$id_orders=mysql_insert_id();
$isikeranjang = isi_keranjang();
$jml          = count($isikeranjang);

for ($i = 0; $i < $jml; $i++){
  mysql_query("INSERT INTO orders_detail(id_orders, id_produk, jumlah) 
               VALUES('$id_orders',{$isikeranjang[$i]['id_produk']}, {$isikeranjang[$i]['jumlah']})");
}

  mysql_query("DELETE FROM orders_temp
	  	         WHERE id_session = '$_SESSION[namauser]'");
?>
<script>
    if (confirm("Ingin Cetak Faktur?") == true) {
        document.location.href = "faktur.php?id=<?php echo $id_orders; ?>&stat=<?php echo $_GET[stat]; ?>";
    } else {
        document.location.href = "keranjang-belanja-<?php echo $_GET[stat]; ?>.html";
    }
</script>

<?php 
}
}

include "kasir.php";
include "produk.php";
include "return.php";
include "kategori.php";
include "suppliers.php"; 

if ($_GET[module]=='laporan'){
    echo "<h3>Manajemen Penjualan</b></h3><br/>
          <form target='_BLANK' style='float:right; margin-bottom:3px; margin-right:22px' method=POST action='print.php'>
							Laporan Perbulan : <select name='bulan'>
									<option value=0 selected>- Pilih Bulan -</option>";
									$tampil=mysql_query("SELECT substring(tgl_order,6,2) as bulan FROM orders GROUP BY substring(tgl_order,6,2)");
									while($r=mysql_fetch_array($tampil)){
									$bulan = Bulan($r[bulan]);
									  echo "<option value=$r[bulan]>$bulan</option>";
									}	
							echo "</select>
							<select name='tahun'>
									<option value=0 selected>- Pilih Tahun -</option>";
									$tampil=mysql_query("SELECT substring(tgl_order,1,4) as tahun FROM orders GROUP BY substring(tgl_order,1,4)");
									while($r=mysql_fetch_array($tampil)){
									  echo "<option value=$r[tahun]>$r[tahun]</option>";
									}
							echo "</select>
						<input type='submit' class='submitt' name='submit' value='Cetak Laporan'>
					</form>
					
		  <table class='data'>
          <tr class='data'>
			<th class='data' width=80px>No Faktur</th>
			<th class='data'>Nama Customer</th>
			<th class='data'>Nama Kasir</th>
			<th class='data' width='150px'>Waktu Penjualan</th>
			<th class='data' align='center' width='120px;'>Action</th>
		  </tr>";

    $p      = new Paging;
    $batas  = 10;
    $posisi = $p->cariPosisi($batas);

    $tampil = mysql_query("SELECT a.*, b.nama_costumer FROM orders a JOIN costumer b ON a.id_costumer=b.id_costumer  ORDER BY a.id_orders DESC LIMIT $posisi,$batas");
  
    while($r=mysql_fetch_array($tampil)){
      $tanggal=tgl_indo($r[tgl_order]);
	  if(($no % 2)==0){
			$warna="#ffffff";
		  }
		  else{
			$warna="#E1E1E1";
		  }
      echo "<tr class='data'><td class='data'>$r[no_orders]</td>
      			<td class='data'>$r[nama_costumer]</td>
                <td class='data'>$r[nama_kasir]</td>
                <td class='data'>$tanggal, $r[jam_order]</td>
		        <td class='data'><a href=media.php?module=detailorder&id=$r[id_orders]&stat=$r[status]>Detail</a> | 
					<a target='_BLANK' href='faktur.php?id=$r[id_orders]&stat=$r[status]&page=report'>Cetak Faktur</a>
				</td>
			</tr>";
      $no++;
    }
	 $jmldata = mysql_num_rows(mysql_query("SELECT * FROM orders"));
    $jmlhalaman  = $p->jumlahHalaman($jmldata, $batas);
    $linkHalaman = $p->navHalaman($_GET[halaman], $jmlhalaman);
    echo "</table><br/>Halaman : $linkHalaman";
	
}elseif ($_GET[module]=='detailorder'){
  if ($_GET[stat]=='1'){
    $status = 'Eceran';
    $sql2=mysql_query("SELECT orders_detail.*, produk.harga as harga, produk.kode_produk, produk.nama_produk, produk.id_produk, produk.satuan FROM orders_detail, produk 
                          WHERE orders_detail.id_produk=produk.id_produk AND orders_detail.id_orders='$_GET[id]'");
  }else{
    $status = 'Grosir';
    $sql2=mysql_query("SELECT orders_detail.*, produk.harga_grosir as harga, produk.kode_produk, produk.nama_produk, produk.id_produk, produk.satuan FROM orders_detail, produk 
                          WHERE orders_detail.id_produk=produk.id_produk AND orders_detail.id_orders='$_GET[id]'");
  } 

$edit = mysql_query("SELECT * FROM orders WHERE id_orders='$_GET[id]'");
    $r    = mysql_fetch_array($edit);
    $tanggal=tgl_indo($r[tgl_order]);

    echo "<h3>Detail Data Pesanan - $status.</h3><br>
          <table width=100%>
          <tr><td width=110px>No. Faktur</td>        <td> : $r[no_orders]</td></tr>
          <tr><td>Tgl. & Jam Order</td> <td> : $tanggal & $r[jam_order]</td></tr>
          <tr><td>Kasir Melayani</td> <td> : $r[nama_kasir]</td></tr>
          </table>";

  
  echo "<table class='data'>
        <tr class='data'>
			<th class='data'>Kode Produk</th>
			<th class='data'>Nama Produk</th>
			<th class='data'>Jumlah</th>
			<th class='data'>Harga $status</th>
			<th class='data'>Sub Total</th>
		</tr>";
  
  while($s=mysql_fetch_array($sql2)){
     // rumus untuk menghitung subtotal dan total	
	$subtotal1    = ($s[harga] * $s[jumlah])* $s[diskon]/100 ;
    $subtotal2    = $s[harga] * $s[jumlah] ;
	$subtotal    = $subtotal2 - $subtotal1 ;
    $total       = $total + $subtotal;
    $subtotal_rp = format_rupiah($subtotal);    
    $total_rp    = format_rupiah($total);    
    $harga       = format_rupiah($s[harga]);


    echo "<tr class='data'>
			<td class='data'>$s[kode_produk]</td>
			<td class='data'>$s[nama_produk]</td>
			<td class='data'>$s[jumlah] $s[satuan]</td>
			<td class='data'>Rp. $harga</td>
			<td class='data'>Rp. $subtotal_rp</td>
		</tr>";
  } 
		$by = mysql_fetch_array(mysql_query("SELECT * FROM orders where id_orders='$_GET[id]'")); 
    	$kembali = $by[bayar] - $total;

echo "
		<tr bgcolor='#e3e3e3'>
			<td class='data' colspan='4'>Total</td>
			<td class='data' colspan='5'> Rp. <b>$total_rp</b></td>
		</tr>
		<tr bgcolor='#e3e3e3'>
			<td class='data' colspan='4'>Bayar</td>
			<td class='data' colspan='5'> Rp. <b>".format_rupiah($by[bayar])."</b></td>
		</tr>
		<tr bgcolor='#e3e3e3'>
			<td class='data' colspan='4'>Kembali</td>
			<td class='data' colspan='5'> Rp. <b>".format_rupiah($kembali)."</b></td>
		</tr>
      </table>";
}
?>

