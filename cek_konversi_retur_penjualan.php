<?php session_start();


include 'db.php';

 $satuan_konversi = $_GET['satuan_konversi'];
 $id_produk = $_GET['id_produk'];
 $harga_produk = $_GET['harga_produk'];
 $harga_pcs = $_GET['harga_pcs'];
 

 $query = $db->query("SELECT COUNT(*) AS jumlah_total, konversi * $harga_pcs AS harga_pokok,konversi FROM satuan_konversi WHERE id_satuan = '$satuan_konversi' AND id_produk = '$id_produk'");
 $data = mysqli_fetch_array($query);




 echo json_encode($data);



        //Untuk Memutuskan Koneksi Ke Database

        mysqli_close($db);
        
  ?>


