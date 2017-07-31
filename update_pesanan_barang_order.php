<?php session_start();


include 'sanitasi.php';
include 'db.php';


    $session_id = session_id();

$kode_barang = stringdoang($_POST['kode_barang']);
$jumlah_baru = angkadoang($_POST['jumlah_baru']);
$jumlah_lama = angkadoang($_POST['jumlah_lama']);
$potongan = angkadoang($_POST['potongan']);
$harga = angkadoang($_POST['harga']);
$jumlah_tax = angkadoang($_POST['jumlah_tax']);
$subtotal = angkadoang($_POST['subtotal']);


$user = $_SESSION['nama'];
$id = stringdoang($_POST['id']);


$query00 = $db->query("SELECT * FROM tbs_penjualan_order WHERE id = '$id'");
$data = mysqli_fetch_array($query00);
$kode = $data['kode_barang'];
$nomor = $data['no_faktur_order'];

$query = $db->prepare("UPDATE tbs_penjualan_order SET jumlah_barang = ?, subtotal = ?, tax = ?, potongan = ? WHERE id = ?");


$query->bind_param("iiiii",
    $jumlah_baru, $subtotal, $jumlah_tax,$potongan, $id);

$query->execute();

    if (!$query) 
    {
    die('Query Error : '.$db->errno.
    ' - '.$db->error);
    }
    else
    {

    }

    
    $query9 = $db->query("SELECT * FROM fee_produk WHERE nama_petugas = '$user' AND kode_produk = '$kode'");
    $cek9 = mysqli_fetch_array($query9);
    $prosentase = $cek9['jumlah_prosentase'];
    $nominal = $cek9['jumlah_uang'];

        if ($prosentase != 0)

            {
            
            $fee_prosentase_produk = $prosentase * $subtotal / 100;
            
            $query1 = $db->query("UPDATE tbs_fee_produk SET jumlah_fee = '$fee_prosentase_produk' WHERE nama_petugas = '$user' AND kode_produk = '$kode' AND no_faktur = '$nomor'");
                 
            
            }

   elseif ($nominal != 0) 

            {
            
            $fee_nominal_produk = $nominal * $jumlah_baru;

            $query01 = $db->query("UPDATE tbs_fee_produk SET jumlah_fee = '$fee_nominal_produk' WHERE nama_petugas = '$user' AND kode_produk = '$kode' AND no_faktur = '$nomor'");

            }



if (isset($_POST['no_faktur'])) {
  
    $no_faktur = stringdoang($_POST['no_faktur']);
// menampilakn hasil penjumlah subtotal ALIAS total penjualan dari tabel tbs_penjualan berdasarkan data no faktur
 $query_total = $db->query("SELECT SUM(subtotal) AS total_penjualan FROM tbs_penjualan_order WHERE no_faktur_order = '$no_faktur'");

}else{


// menampilakn hasil penjumlah subtotal ALIAS total penjualan dari tabel tbs_penjualan berdasarkan data no faktur
 $query_total = $db->query("SELECT SUM(subtotal) AS total_penjualan FROM tbs_penjualan_order WHERE session_id = '$session_id'");
}
 
 // menyimpan data sementara yg ada pada $query
 $data_total = mysqli_fetch_array($query_total);
 $total = $data_total['total_penjualan'];


echo$total;
      //Untuk Memutuskan Koneksi Ke Database
mysqli_close($db);   

?>
