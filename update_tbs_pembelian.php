<?php

include 'sanitasi.php';
include 'db.php';


$kode_barang = stringdoang($_POST['kode_barang']);
$jumlah_baru = angkadoang($_POST['jumlah_baru']);


$query7 = $db->query("SELECT * FROM barang WHERE kode_barang = '$kode_barang'");
$data1 = mysqli_fetch_array($query7);

// mencari jumlah Barang
            $query0 = $db->query("SELECT SUM(jumlah_barang) AS jumlah_pembelian FROM detail_pembelian WHERE kode_barang = '$data1[kode_barang]'");
            $cek0 = mysqli_fetch_array($query0);
            $jumlah_pembelian = $cek0['jumlah_pembelian'];

            $query1 = $db->query("SELECT SUM(jumlah) AS jumlah_item_masuk FROM detail_item_masuk WHERE kode_barang = '$data1[kode_barang]'");
            $cek1 = mysqli_fetch_array($query1);
            $jumlah_item_masuk = $cek1['jumlah_item_masuk'];

            $query2 = $db->query("SELECT SUM(jumlah_retur) AS jumlah_retur_penjualan FROM detail_retur_penjualan WHERE kode_barang = '$data1[kode_barang]'");
            $cek2 = mysqli_fetch_array($query2);
            $jumlah_retur_penjualan = $cek2['jumlah_retur_penjualan'];

            $query20 = $db->query("SELECT SUM(jumlah_awal) AS jumlah_stok_awal FROM stok_awal WHERE kode_barang = '$data1[kode_barang]'");
            $cek20 = mysqli_fetch_array($query20);
            $jumlah_stok_awal = $cek20['jumlah_stok_awal'];

            $query200 = $db->query("SELECT SUM(selisih_fisik) AS jumlah_fisik FROM detail_stok_opname WHERE kode_barang = '$data1[kode_barang]'");
            $cek200 = mysqli_fetch_array($query200);
            $jumlah_fisik = $cek200['jumlah_fisik'];
//total barang 1
            $total_1 = $jumlah_pembelian + $jumlah_item_masuk + $jumlah_retur_penjualan + $jumlah_stok_awal + $jumlah_fisik;


 

            $query3 = $db->query("SELECT SUM(jumlah_barang) AS jumlah_penjualan FROM detail_penjualan WHERE kode_barang = '$data1[kode_barang]'");
            $cek3 = mysqli_fetch_array($query3);
            $jumlah_penjualan = $cek3['jumlah_penjualan'];


            $query4 = $db->query("SELECT SUM(jumlah) AS jumlah_item_keluar FROM detail_item_keluar WHERE kode_barang = '$data1[kode_barang]'");
            $cek4 = mysqli_fetch_array($query4);
            $jumlah_item_keluar = $cek4['jumlah_item_keluar'];

            $query5 = $db->query("SELECT SUM(jumlah_retur) AS jumlah_retur_pembelian FROM detail_retur_pembelian WHERE kode_barang = '$data1[kode_barang]'");
            $cek5 = mysqli_fetch_array($query5);
            $jumlah_retur_pembelian = $cek5['jumlah_retur_pembelian'];



//total barang 2
            $total_2 = $jumlah_penjualan + $jumlah_item_keluar + $jumlah_retur_pembelian;



            $stok_barang = $total_1 - $total_2;

            $over = $data1['over_stok'];

            $a = $stok_barang + $jumlah_baru;


            if ($over < $a) {

      echo '<div class="alert alert-warning">
            <strong>PERHATIAN!</strong> Persediaan Barang Mencapai Over Stok!
        </div>';

}



$id = stringdoang($_POST['id']);
$jumlah_barang = angkadoang($_POST['jumlah_barang']);
$harga = angkadoang($_POST['harga']);
$potongan = angkadoang($_POST['potongan']);
$tax = angkadoang($_POST['tax']);
$jumlah_baru = angkadoang($_POST['jumlah_baru']);

$x = $jumlah_barang * $harga;
$sub_lama = $x - $potongan;

$subtotal = $harga * $jumlah_baru - $potongan;
$tax_tbs = $tax / $sub_lama * 100;
$jumlah_tax = $tax_tbs * $subtotal / 100;


$query = $db->prepare("UPDATE tbs_pembelian SET jumlah_barang = ?, subtotal = ?, tax = ? WHERE id = ?");

$query->bind_param("iiis",
	$jumlah_baru, $subtotal, $jumlah_tax, $id);

$query->execute();


    if (!$query) 
    {
    die('Query Error : '.$db->errno.
    ' - '.$db->error);
    }
    else 
    {
      echo '<div class="alert alert-success">
            <strong>SUKSES!</strong> Edit Barang Berhasil!
        </div>';
    }
    //Untuk Memutuskan Koneksi Ke Database
mysqli_close($db);   

?>