<?php
if ($_GET[module]=='produk'){
	    echo "<h3>Semua Produk no Faktur : $_GET[kode]<span style='float:right'><a style='float:right;' target='_BALNK' href='print-produk.php?kode=$_GET[kode]'>Cetak Laporan Produk</a></span></h3><br/>
          <input type=button value='Tambah Master dan Pembelian Produk' onclick=\"window.location.href='media.php?module=tambahproduk&no=$_GET[kode]';\">
          <span style='float:right;'>
			<form action='media.php' method='GET' style='margin-right:22px'>
							  <input type='hidden' name='module' value='editproduk' style='width:200px; margin-bottom:3px;'/>
				Tambahkan : <input type='text' name='kdp' autofocus style='width:200px; margin-bottom:3px;' placeholder='Input Kode Produk...'/>
							  <input type='hidden' name='no' value='$_GET[kode]' style='width:200px; margin-bottom:3px;'/>
				<input type='submit' name='cari' value='cari'>
			</form>
		  </span><br/>
		  
			 <div class='h_line'></div>
		<table id='twitter-table' class='data'>
			<tr class='data'>
				<th class='data'>No</th>
				<th class='data'>Kode Produk</th>
				<th class='data'>Nama Produk</th>
				<th class='data'>Harga Ecer</th>
				<th class='data'>Harga Grosir</th>
				<th class='data'>Harga Pokok</th>
				<th class='data'>Jumlah</th>
				<th  class='data' align='center' width='70px;'>Action</th>
			</tr>";
	$ifa = mysql_fetch_array(mysql_query("SELECT * FROM faktur where no_faktur='$_GET[kode]'"));

	if (isset($_POST[cari]) OR isset($_REQUEST[kata])){
		$tampil = mysql_query("SELECT c.nama_supplier, a.id_produk_pembelian, a.id_faktur, a.id_produk, a.id_supplier, a.jumlah, a.tanggal_masuk, a.username, b.kode_produk, b.nama_produk, b.harga, b.harga_grosir, b.harga_pokok, b.satuan  
					FROM `produk_pembelian`a JOIN produk b ON a.id_produk=b.id_produk JOIN supplier c ON a.id_supplier=c.id_supplier where a.id_faktur='$ifa[id_faktur]' AND b.kode_produk='$_POST[kata]' ORDER BY a.id_produk_pembelian");
	}else{
		$per_page = 10;
		$page_query = mysql_query("SELECT COUNT(*) FROM produk_pembelian where id_faktur='$ifa[id_faktur]'");
		$pages = ceil(mysql_result($page_query, 0) / $per_page);
		$page = (isset($_GET['p'])) ? (int)$_GET['p'] : 1;
		$start = ($page - 1) * $per_page;
		
		$tampil = mysql_query("SELECT c.nama_supplier, a.id_produk_pembelian, a.id_faktur, a.id_produk, a.id_supplier, a.jumlah, a.tanggal_masuk, a.username, b.kode_produk, b.nama_produk, b.harga, b.harga_grosir, b.harga_pokok, b.satuan  
					FROM `produk_pembelian`a JOIN produk b ON a.id_produk=b.id_produk JOIN supplier c ON a.id_supplier=c.id_supplier where a.id_faktur='$ifa[id_faktur]' ORDER BY a.id_produk_pembelian ASC LIMIT $start, $per_page");
    }
	$no = $start+1;
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
                <td class='data'><a href='#' title='Pemasok : $r[nama_supplier]'>$r[nama_produk]</td>
                <td class='data'>Rp $harga</td>
				<td class='data'>Rp $harga_grosir</td>
				<td class='data'>Rp $harga_pokok</td>
				<td class='data' align=center>$r[jumlah] $r[satuan]</td>
				<td class='data' align='center'><a href=media.php?module=hapusproduk&id=$r[id_produk]&no=$_GET[kode]&idf=$r[id_faktur]>Hapus</a></td>
		        </tr>";
      $no++;
    }
    echo "</table>";
			echo "<div style='clear:both'></div>Halaman : ";
			if($pages >= 1 && $page <= $pages){
				for($x=1; $x<=$pages; $x++){
					echo ($x == $page) ? '
						<a href="halaman-detail-produk-'.$_GET[kode].'-'.$x.'.html">'.$x.'</a> | ' : '
						<a href="halaman-detail-produk-'.$_GET[kode].'-'.$x.'.html">'.$x.'</a>';
				}
			}
	
}elseif ($_GET[module]=='tambahproduk'){
echo "<h3>Tambah Master dan Pembelian Produk Baru di Faktur : $_GET[no]</h3><br/>
          <form method=POST action='media.php?module=aksitambahproduk' enctype='multipart/form-data'>
          <table>
		  <tr><td width=100>No Faktur</td>     <td> : <input type=text name='no' value='$_GET[no]' size=10 readonly='on'></td></tr>
		  <tr><td width=100>Kode Produk</td>     <td> : <input type=text name='kode_produk' size=10></td></tr>
          <tr><td>Nama Produk</td>     <td> : <input type=text name='nama_produk' size=60></td></tr>
          <tr><td>Kategori</td>  <td> : 
          <select name='kategori'>
            <option value=0 selected>- Pilih Kategori -</option>";
            $tampil=mysql_query("SELECT * FROM kategori_produk ORDER BY nama_kategori");
            while($r=mysql_fetch_array($tampil)){
              echo "<option value=$r[id_kategori]>$r[nama_kategori]</option>";
            }
    echo "</select></td></tr>
		  <tr><td>Supplier </td>  <td> : 
          <select name='id_supplier'>
            <option value=0 selected>- Pilih Supplier -</option>";
            $tampil=mysql_query("SELECT * FROM supplier ORDER BY nama_supplier");
            while($r=mysql_fetch_array($tampil)){
              echo "<option value=$r[id_supplier]>$r[nama_supplier]</option>";
            }
    echo "</select></td></tr>
          <tr><td>Harga</td>     <td> : <input type=text name='harga' size=20></td></tr>
		  <tr><td>Harga grosir</td>     <td> : <input type=text name='harga_grosir' size=20></td></tr>
		  <tr><td>Harga Pokok</td>     <td> : <input type=text name='harga_pokok' size=20></td></tr>
		  <tr><td>Satuan</td>     <td> : <input type=text name='satuan' size=20></td></tr>
          <tr><td>Stok</td>     <td> : <input type=text name='stok' size=20></td></tr>
		  <input type=hidden name='berat' size=20 value='0'>
		  <tr><td>Diskon</td>     <td> : <input type=text name='diskon' size=20></td></tr>
          <tr><td>Deskripsi</td>  <td> : <textarea name='deskripsi' style='width: 470px; height: 60px;'></textarea>
          <tr><td colspan=2><br/><input style='float:right;' type=button value=Batal onclick=self.history.back()>
							<input style='float:right; margin-right:5px;' type=submit value='Simpan dan Tambahkan'></td></tr>
          </table></form>";
		  
}elseif ($_GET[module]=='aksitambahproduk'){
	$ifa = mysql_fetch_array(mysql_query("SELECT * FROM faktur where no_faktur='$_POST[no]'"));
	$hitung = mysql_num_rows(mysql_query("SELECT * FROM produk where kode_produk='$_POST[kode_produk]'"));
	if ($hitung >= 1){
		echo "<script>window.alert('Maaf, Kode Produk Sudah ada di system.');
				window.location=('produk-$_POST[kode_produk].html')</script>"; 
	}else{
		mysql_query("INSERT INTO produk(kode_produk,
										nama_produk,
										id_kategori,
										harga,
										harga_grosir,
										harga_pokok,
										satuan,
										berat,
										diskon,
										deskripsi,
										tgl_masuk) 
								VALUES('$_POST[kode_produk]',
									   '$_POST[nama_produk]',
									   '$_POST[kategori]',
									   '$_POST[harga]',
									   '$_POST[harga_grosir]',
									   '$_POST[harga_pokok]',
									   '$_POST[satuan]',
									   '$_POST[berat]',
									   '$_POST[diskon]',
									   '$_POST[deskripsi]',
									   '$tgl_sekarang')");
		$idp = mysql_insert_id();
		$tglbeli = date("Y-m-d H:i:s");
		mysql_query("INSERT INTO produk_pembelian(id_faktur,
										id_produk,
										id_supplier,
										jumlah,
										tanggal_masuk,
										username) 
								VALUES('$ifa[id_faktur]',
									   '$idp',
									   '$_POST[id_supplier]',
									   '$_POST[stok]',
									   '$tglbeli',
									   '$_SESSION[namauser]')");

	header('location:detail-produk-'.$_POST[no].'.html');
	}
}elseif ($_GET[module]=='editproduk'){
    $edit = mysql_query("SELECT * FROM produk WHERE kode_produk='$_GET[kdp]'");
    $r    = mysql_fetch_array($edit);
    $temukan    = mysql_num_rows($edit);

	    echo "<h3>Tambah Data Pembelian Produk Faktur $_GET[no].</h3><br/>";

    if ($temukan <= 0){
    	echo "<center style='margin-top:10%'>Maaf, Produk Dengan Kode <b>$_GET[kdp]</b> Tidak Ditemukan !!!<br>
    				   Tambahkan Master Produk dan Pembelian untuk Faktur <b>$_GET[no]</b>
    				   <br> <button><a href='media.php?module=tambahproduk&no=$_GET[no]'>Tambah Master dan Pembelian</a></button></center>";
    }else{

	    echo "<form method=POST enctype='multipart/form-data' action=media.php?module=aksieditproduk>
	          <input type=hidden name=id value=$r[id_produk]>
	          <table>
			  <tr><td width=100>No Faktur</td>     <td> : <input type=text name='idf' value='$_GET[no]' size=10 readonly='on' style='background:#e3e3e3'></td></tr>
			  <tr><td width=100>Kode Produk</td>     <td> : <input type=text name='kode_produk' value='$r[kode_produk]' size=10 readonly='on' style='background:#e3e3e3'></td></tr>
	          <tr><td width=100>Nama Produk</td>     <td> : <input type=text name='judul' size=60 value='$r[nama_produk]' readonly='on' style='background:#e3e3e3'></td></tr>
	          <tr><td>Satuan</td>     <td> : <input type=text name='satuan' value='$r[satuan]' size=20 readonly='on' style='background:#e3e3e3'></td></tr>
			  <tr><td>Supplier</td>  <td> : <select style='background:#fff' name='id_supplier'>";
	 
	          $tampil=mysql_query("SELECT * FROM supplier ORDER BY nama_supplier");
	          if ($r[id_supplier]==0){
	            echo "<option value=0 selected>- Pilih Supplier -</option>";
	          }   

	          while($w=mysql_fetch_array($tampil)){
	            if ($r[id_supplier]==$w[id_supplier]){
	              echo "<option value=$w[id_supplier] selected>$w[nama_supplier]</option>";
	            }
	            else{
	              echo "<option value=$w[id_supplier]>$w[nama_supplier]</option>";
	            }
	          }
	    $in = mysql_fetch_array(mysql_query("SELECT a.id_produk, sum(a.jumlah) as masuk FROM `produk_pembelian` a where a.id_produk='$r[id_produk]'"));
	    $out = mysql_fetch_array(mysql_query("SELECT a.id_produk, sum(a.jumlah) as keluar FROM `orders_detail` a where a.id_produk='$r[id_produk]'"));
	    $stok = $in[masuk]-$out[keluar];

	    echo "</select></td></tr>
			  <tr><td>Stok dan Jumlah</td>     <td> : <input type=text value='$stok' size=10 disabled  style='background:#e3e3e3'> + <input type=text name='stokmasuk' size=15 placeholder='Jumlah Masuk'></td></tr>
			  <input type=hidden name='berat' size=20 value='0'>
	          <tr><td colspan=2><br/><input style='float:right;' type=button value=Batal onclick=self.history.back()>
								<input style='float:right;margin-right:5px' type=submit value=Tambahkan></td></tr>
	         </table></form>";
    }
		 
}elseif ($_GET[module]=='aksieditproduk'){
		$tglbeli = date("Y-m-d H:i:s");
		$ifa = mysql_fetch_array(mysql_query("SELECT * FROM faktur where no_faktur='$_POST[idf]'"));
		mysql_query("INSERT INTO produk_pembelian(id_faktur,
										id_produk,
										id_supplier,
										jumlah,
										tanggal_masuk,
										username) 
								VALUES('$ifa[id_faktur]',
									   '$_POST[id]',
									   '$_POST[id_supplier]',
									   '$_POST[stokmasuk]',
									   '$tglbeli',
									   '$_SESSION[namauser]')");
 
  header('location:detail-produk-'.$_POST[idf].'.html');
  
}elseif ($_GET[module]=='hapusproduk'){
  mysql_query("DELETE FROM produk_pembelian WHERE id_produk='$_GET[id]' AND id_faktur='$_GET[idf]'");

  header('location:detail-produk-'.$_GET[no].'.html');
}
?>