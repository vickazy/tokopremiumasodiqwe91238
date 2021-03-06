<?php include 'session_login.php';

// memasukan file session login,  header, navbar, db.php,
include 'header.php';
include 'navbar.php';
include 'db.php';
include 'sanitasi.php';

 
 $nomor_faktur = stringdoang($_GET['no_faktur']);
 $id_pelanggan = stringdoang($_GET['kode_pelanggan']);
 $nama_gudang = stringdoang($_GET['nama_gudang']);
 $kode_gudang = stringdoang($_GET['kode_gudang']);


    $query_pelanggan = $db->query("SELECT kode_pelanggan,nama_pelanggan,level_harga FROM pelanggan WHERE id = '$id_pelanggan'");
    $data_pelanggan = mysqli_fetch_array($query_pelanggan);
    $kode_pelanggan = $data_pelanggan['kode_pelanggan'];
    $level_harga = $data_pelanggan['level_harga'];

//perhitungan jumlah bayar lama (jumlah_bayar + potongan)
    $query_detail_pembayaran_piutang = $db->query("SELECT SUM(jumlah_bayar) AS jumlah_bayar, SUM(potongan) AS potongan FROM detail_pembayaran_piutang WHERE no_faktur_penjualan = '$nomor_faktur'");
    $data_query_detail_pembayaran_piutang = mysqli_fetch_array($query_detail_pembayaran_piutang);
    $jumlah_bayar = $data_query_detail_pembayaran_piutang['jumlah_bayar'];
    $potongan_piutang = $data_query_detail_pembayaran_piutang['potongan'];

    $jumlah_bayar_lama = $jumlah_bayar + $potongan_piutang;
//perhitungan jumlah bayar (jumlah_bayar + potongan)

//ambil data no_faktur yang di edit dari penjualan
    $query_penjualan = $db->query("SELECT biaya_admin,potongan,tax,ppn,total,tanggal, tanggal_jt, tunai, sisa, nilai_kredit, keterangan FROM penjualan WHERE no_faktur = '$nomor_faktur'");
    $data_penjualan = mysqli_fetch_array($query_penjualan);
    $potongan = $data_penjualan['potongan'];
    $ppn = $data_penjualan['ppn'];
    $tax = $data_penjualan['tax'];
    $biaya_admin = $data_penjualan['biaya_admin'];
    $tanggal_ganti = $data_penjualan['tanggal'];
    $total_akhir = $data_penjualan['total'];
    $tunai = $data_penjualan['tunai'];
    $kembalian = $data_penjualan['sisa'];
    $kredit = $data_penjualan['nilai_kredit'];
//ambil data no_faktur yang di edit dari penjualan

//ambil data untuk form pembayaran yang akan di edit diambil dari perhitunga berikut
    $query_detail_penjualan = $db->query("SELECT SUM(subtotal) AS subtotal FROM detail_penjualan WHERE no_faktur = '$nomor_faktur'");
    $data_detail_penjualan = mysqli_fetch_array($query_detail_penjualan);
    $subtotal_persen = $data_detail_penjualan['subtotal'];
   
    $potongan_persen = $potongan / $subtotal_persen * 100;
    $hasil_persen = $potongan_persen;

    $subtotal_tax = $subtotal_persen - $potongan;
    $hasil_sub = intval($subtotal_tax);

    $biaya_adm_persen = $biaya_admin / $subtotal_persen * 100;
    $hasil_adm = intval($biaya_adm_persen);

    $potongan_tax = $tax / $hasil_sub * 100;
    $hasil_tax = intval($potongan_tax);
//ambil data untuk form pembayaran yang akan di edit diambil dari perhitunga berikut

 ?>



 <style type="text/css">
  .disabled {
    opacity: 0.6;
    cursor: not-allowed;
    disabled: true;
}
</style>


<script>
  $(function() {
    $( ".tanggal" ).datepicker({dateFormat: "yy-mm-dd"});
  });


  Number.prototype.format = function(n, x, s, c) {
    var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\D' : '$') + ')',
        num = this.toFixed(Math.max(0, ~~n));

    return (c ? num.replace('.', c) : num).replace(new RegExp(re, 'g'), '$&' + (s || ','));
};

  </script>

<!--untuk membuat agar tampilan form terlihat rapih dalam satu tempat -->
 <div style="padding-left: 5%; padding-right: 5%">


  <!--membuat teks dengan ukuran h3-->      
  <h3>EDIT PENJUALAN : <?php echo $nomor_faktur;?></h3><br>


<!--membuat agar tabel berada dalam baris tertentu-->
 <div class="row">
<div class="col-sm-8">
  

<!-- membuat form menjadi beberpa bagian -->
<form enctype="multipart/form-data" role="form" action="formpenjualan.php" method="post ">
        

<div class="row">

<div class="form-group col-sm-4">
  <label> Kode Pelanggan </label>
  <select type="text" name="kode_pelanggan" id="kd_pelanggan" class="form-control chosen"  required="" autofocus="">
  <option value="<?php echo $kode_pelanggan; ?>" class="opt-pelanggan-<?php $kode_pelanggan;?>" data-level="<?php $kode_pelanggan;?>" ><?php echo $kode_pelanggan; ?> - <?php echo $data_pelanggan['nama_pelanggan']; ?></option>   
  <?php 
    
    //untuk menampilkan semua data pada tabel pelanggan dalam DB
    $query = $db->query("SELECT kode_pelanggan,nama_pelanggan,level_harga FROM pelanggan");

    //untuk menyimpan data sementara yang ada pada $query
    while($data = mysqli_fetch_array($query))
    {
    
    echo "<option value='".$data['kode_pelanggan'] ."' class='opt-pelanggan-".$data['kode_pelanggan']."' data-level='".$data['level_harga'] ."'>".$data['kode_pelanggan'] ." - ".$data['nama_pelanggan'] ."</option>";
    }
    
    
    ?>
  </select>
</div>


<button type="button" style="height:45px;" id="cari_produk_penjualan" class="btn btn-info" data-toggle="modal" data-target="#myModal"><i class='fa  fa-search'></i>  Cari (F1) </button>
<button type="button" style="height:45px;" id="daftar_order" class="btn btn-success" data-toggle="modal" data-target="#modal_order"><i class='fa  fa-search'></i> Cari Order (F6) </button>

<button class="btn btn-primary" type="button" style="height:45px; display: none" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample"><i class='fa fa-list-ol'> </i>
Antrian  </button>

<button class="btn btn-warning" type="button" style="height:45px;" data-toggle="collapse" data-target="#sss" aria-expanded="false" aria-controls="collapseExample"><i class='fa fa-list-ol'> </i>
Order </button>


<button class="btn btn-deep-purple btn-sm"  style="height:45px; width: 45px;" type="button" id="tombol-warning"><i class="fa fa-arrow-down" aria-hidden="true"></i></button>

<button class="btn btn-indigo btn-sm" type="button" id="tombol-purpel" style="display: none; height:45px; width: 45px;"><i class="fa fa-arrow-down" aria-hidden="true"></i></button>

<div style="display: none" id="span-colapse">
<div class="form-group  col-sm-2">
    <label> Gudang </label><br>
       <select name="kode_gudang" id="kode_gudang" class="form-control chosen span-chosen" required="" >
          <option value="<?php echo $kode_gudang; ?>"><?php echo $nama_gudang; ?></option>
          <?php 
          
          // menampilkan seluruh data yang ada pada tabel suplier
          $query = $db->query("SELECT kode_gudang,nama_gudang FROM gudang");
          
          // menyimpan data sementara yang ada pada $query
          while($data = mysqli_fetch_array($query))
          {
          
          echo "<option value='".$data['kode_gudang'] ."'>".$data['nama_gudang'] ."</option>";
          }
          
          
          ?>
    </select>
</div>

<div class="form-group  col-sm-2">
    <label>Sales</label>
      <select name="sales" id="sales" class="form-control chosen span-chosen" required="">
      <?php 
    
      //untuk menampilkan semua data pada tabel pelanggan dalam DB
      $query01 = $db->query("SELECT id,nama FROM user WHERE status_sales = 'Iya'");

      //untuk menyimpan data sementara yang ada pada $query
      while($data01 = mysqli_fetch_array($query01))
      {
      echo "<option value='".$data01['id'] ."'>".$data01['nama'] ."</option>";
      }
      ?>
  </select>
</div>

<div class="form-group col-sm-2">
  <label> Level Harga </label><br>
  <select type="text" style="font-size:13px; height:35px" name="level_harga" id="level_harga" class="form-control chosen span-chosen" required="" >
  <option value="harga_1">Level 1</option>
  <option value="harga_2">Level 2</option>
  <option value="harga_3">Level 3</option>
  <option value="harga_4">Level 4</option>
  <option value="harga_5">Level 5</option>
  <option value="harga_6">Level 6</option>
  <option value="harga_7">Level 7</option>
  </select>
</div>



<div class="form-group  col-sm-2">
      <label>PPN</label>
          <select name="ppn" id="ppn"  style="font-size:13px; height:35px" class="form-control chosen span-chosen">
            <option value="<?php echo $ppn; ?>"><?php echo $ppn; ?></option>  
            <option >Include</option>  
            <option >Exclude</option>
            <option >Non</option>          
     </select>
</div>
</div>
</div><!--end div row data informasi pelanggan -->


<div class="row">


    <input type="hidden"  style="height:15px;font-size:15px" name="no_faktur" id="nomor_faktur_penjualan" class="form-control" readonly="" value="<?php echo $nomor_faktur; ?>" required="" >



<div class="form-group  col-sm-2">
      <label> Tanggal </label>
      <input type="text" style="height:15px;font-size:15px" name="tanggal" id="tanggal"  value="<?php echo $tanggal_ganti; ?>" class="form-control tanggal" autocomplete="off" >
</div>

<!--form barcode barang -->
<form id="form_barcode" class="form-inline"> 
  <br>
    <div class="form-group col-sm-2">
        <input type="text" style="height:15px" name="kode_barcode" id="kode_barcode" class="form-control" autocomplete="off" placeholder="Scan / Ketik Barcode">
    </div>
        
    <button type="submit" id="submit_barcode" class="btn btn-primary" style="font-size:15px" ><i class="fa fa-barcode"></i> Submit Barcode</button>
             
</form>
<!--end form barcode barang -->

<input type="hidden" name="ppn_input" id="ppn_input" value="<?php echo $ppn; ?>" class="form-control" placeholder="ppn input">  
<input type="hidden" name="tipe_produk" id="tipe_produk" class="form-control" >  

</div>

</form><!--tag penutup form-->


<?php 
$hud = $db->query("SELECT setting_tampil FROM setting_antrian");
$my = mysqli_fetch_array($hud);

if ($my['setting_tampil'] == 'Tampil')
{
?>



</form>

<style>


tr:nth-child(even){background-color: #f2f2f2}


</style>

<div class="collapse" id="collapseExample">
 <table id="tableuser" class="table-border table-sm">
   <thead>
  <th style='background-color: #4CAF50; color:white'>No Faktur </th>   
  <th style='background-color: #4CAF50; color:white'>Kode Pelanggan</th>   
  <th style='background-color: #4CAF50; color:white'>Nama Pelanggan</th>
  <th style='background-color: #4CAF50; color:white'>Subtotal</th>
  <th style='background-color: #4CAF50; color:white' > Bayar</th>
   </thead>
<tbody>

  <?php
                
                //menampilkan semua data yang ada pada tabel tbs penjualan dalam DB
                $perintah = $db->query("SELECT p.id,p.no_faktur,p.total,p.kode_pelanggan,p.tanggal,p.tanggal_jt,p.jam,p.user,p.sales,p.kode_meja,p.status,p.potongan,p.tax,p.sisa,p.kredit,g.nama_gudang,p.kode_gudang,pl.nama_pelanggan FROM penjualan p INNER JOIN gudang g ON p.kode_gudang = g.kode_gudang INNER JOIN pelanggan pl ON p.kode_pelanggan = pl.id WHERE p.status = 'Simpan Sementara' ORDER BY p.id DESC ");
                
                //menyimpan data sementara yang ada pada $perintah
                
                while ($data1 = mysqli_fetch_array($perintah))
                {
                //menampilkan data
                echo "<tr>
                <td style='font-size:15px'>". $data1['no_faktur'] ."</td>
                <td style='font-size:15px'>". $data1['kode_pelanggan'] ."</td>
                <td style='font-size:15px;'>". $data1['nama_pelanggan'] ."</td>
                <td style='font-size:15px'>". $data1['total'] ."</td>
                <td style='font-size:15px'>
                <a href='proses_pesanan_barang.php?no_faktur=".$data1['no_faktur']."&kode_pelanggan=".$data1['kode_pelanggan']."&nama_pelanggan=".$data1['nama_pelanggan']."&nama_gudang=".$data1['nama_gudang']."&kode_gudang=".$data1['kode_gudang']."' class='btn btn-warning' > Rp</a> 
                 </td>
                </tr>";
                }

                ?>


</tbody>
 </table>
</div>
<?php
}
?>

<!--tampilan modal-->
<div id="modal_order" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <!-- isi modal-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Data Order</h4>
      </div>
        <div class="modal-body">
            <div class="table-resposive">
                <table id="table_order" align="center" class="table table-bordered table-sm">
                <thead>
                <th> No Faktur Order  </th>
                <th >Kode Pelanggan</th>
                <th> Tanggal </th>
                <th> Jam </th>
                <th> Total </th>
                <th> Keterangan </th>
                <th> Petugas Kasir </th>             
                </thead>
          </table>
        </div>
    </div> <!-- tag penutup modal-body-->
      <div class="modal-footer">
        <button type="button" order="" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
  </div><!--  <div class="modal-content"> -->

  </div>
</div><!-- end of modal data barang  -->



<!--tampilan modal-->
<div id="myModal" class="modal " role="dialog">
  <div class="modal-dialog modal-lg">
    <!-- isi modal-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><center><h3><b>Data Barang</b></h3></center></h4>
      </div>
      <div class="modal-body">
 
<!-- Nav tabs -->
<ul class="nav nav-tabs tabs-2 black" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" data-toggle="tab" href="#panel1" role="tab">Cari Barang</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#panel2" id="daftar_parcel" role="tab">Cari Parcel</a>
    </li> 
</ul>
<!-- Tab panels -->
<div class="tab-content card">

    <!--Panel 1-->
    <div class="tab-pane  in show active" id="panel1">
        
            <div class="table-responsive">
              <table id="tabel_cari" class="table table-bordered table-sm">
                   <thead> <!-- untuk memberikan nama pada kolom tabel -->
                        <th> Kode Barang </th>
                        <th> Nama Barang </th>
                        <th> Harga Jual Level 1</th>
                        <th> Harga Jual Level 2</th>
                        <th> Harga Jual Level 3</th>
                        <th> Harga Jual Level 4 </th>
                        <th> Harga Jual Level 5</th>
                        <th> Harga Jual Level 6</th>
                        <th> Harga Jual Level 7</th>
                        <th> Jumlah Barang </th>
                        <th> Satuan </th>
                        <th> Kategori </th>  
                  </thead> <!-- tag penutup tabel -->
               </table>
            </div>
    </div>
    <!--/.Panel 1-->

    <!--Panel 2-->
    <div class="tab-pane " id="panel2">
      <div class="table-responsive">
          <table id="table_parcel" align="center" class="table">
                <thead>
                        <th> Kode Parcel </th>
                        <th> Nama Parcel </th>
                        <th> Jumlah Parcel </th>
                        <th> Harga Level 1</th>
                        <th> Harga Level 2</th>
                        <th> Harga Level 3</th>
                        <th> Harga Level 4 </th>
                        <th> Harga Level 5</th>
                        <th> Harga Level 6</th>
                        <th> Harga Level 7</th>
                </thead>
          </table>
      </div>

    </div>
    <!--/.Panel 2--> 
</div>
        <div class="table-resposive">
      <span class="modal_baru"></span>
      </div>
</div> <!-- tag penutup modal-body-->
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
  </div>
  </div>
</div><!-- end of modal data barang  -->


<!-- Modal Hapus data -->
<div id="modal_hapus" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Konfirmsi Hapus Data Edit Pembelian</h4>
      </div>
      <div class="modal-body">
   <p>Apakah Anda yakin Ingin Menghapus Data ini ?</p>
   <form >
    <div class="form-group">
     <input type="text" id="nama-barang" class="form-control" readonly=""> 
     <input type="text" id="kode-barang" class="form-control" readonly=""> 
     <input type="hidden" id="id_hapus" class="form-control" >
    </div>
   </form>
   
  <div class="alert alert-success" style="display:none">
   <strong>Berhasil!</strong> Data berhasil Di Hapus
  </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-info" id="btn_jadi_hapus">Ya</button>
        <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
      </div>
    </div>
  </div>
</div><!-- end of modal hapus data  -->               


<!-- Modal Untuk Confirm PESAN alert-->
<div id="modal_promo_alert" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>       
    </div>
        <div class="modal-body">
            <span id="tampil_alert">
            </span>
        </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-danger" id="closed_alert_promo" data-dismiss="modal">Closed (Ctrl + G)</button>
    </div>
    </div>
  </div>
</div>
<!--modal end pesan alert-->



<div id="modal_alert" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h3 style="color:orange" class="modal-title"><span class="glyphicon glyphicon-info-sign">Info!</span></h3>
        <h4>Maaf No Transaksi <strong><?php echo $nomor_faktur; ?></strong> tidak dapat dihapus atau di edit, karena telah terdapat Transaksi Pembayaran Piutang atau Retur Penjualan. Dengan daftar sebagai berikut :</h4>
      </div>

      <div class="modal-body">
      <span id="modal-alert">
       </span>


     </div>

      <div class="modal-footer">
        <h6 style="text-align: left"><i> * jika ingin menghapus atau mengedit data,<br>
        silahkan hapus terlebih dahulu Transaksi Pembayaran Piutang atau Retur Penjualan</i></h6>
        <button type="button" class="btn btn-warning btn-close" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>





<!-- membuat form prosestbspenjual -->
<form class="form" action="proses_tambah_edit_penjualan.php" role="form" id="formtambahproduk">

<div class="row 1">


  <div class="col-sm-3">
   <select style="font-size:15px; height:20px" type="text" name="kode_barang" id="kode_barang" class="form-control chosen" data-placeholder="SILAKAN PILIH...">
    <option value="">SILAKAN PILIH...</option>
       <?php 

        include 'cache.class.php';
          $c = new Cache();
          $c->setCache('produk');
          $data_c = $c->retrieveAll();

          foreach ($data_c as $key) {
            echo '<option id="opt-produk-'.$key['kode_barang'].'" value="'.$key['kode_barang'].'" data-kode="'.$key['kode_barang'].'" nama-barang="'.$key['nama_barang'].'" harga="'.$key['harga_jual'].'" harga_jual_2="'.$key['harga_jual2'].'" harga_jual_3="'.$key['harga_jual3'].'" harga_jual_4="'.$key['harga_jual4'].'" harga_jual_5="'.$key['harga_jual5'].'" harga_jual_6="'.$key['harga_jual6'].'" harga_jual_7="'.$key['harga_jual7'].'" satuan="'.$key['satuan'].'" kategori="'.$key['kategori'].'" status="'.$key['status'].'" suplier="'.$key['suplier'].'" limit_stok="'.$key['limit_stok'].'" ber-stok="'.$key['berkaitan_dgn_stok'].'" tipe_barang="'.$key['tipe_barang'].'" id-barang="'.$key['id'].'" > '. $key['kode_barang'].' ( '.$key['nama_barang'].' ) </option>';
          }

          $cache_parcel = new Cache();
          $cache_parcel->setCache('produk_parcel');
          $data_parcel = $cache_parcel->retrieveAll();

          foreach ($data_parcel as $key_parcel) {
            echo '<option id="opt-produk-'.$key_parcel['kode_parcel'].'" value="'.$key_parcel['kode_parcel'].'" data-kode="'.$key_parcel['kode_parcel'].'" nama-barang="'.$key_parcel['nama_parcel'].'" harga="'.$key_parcel['harga_parcel'].'" harga_barang_2="'.$key_parcel['harga_parcel_2'].'" harga_barang_3="'.$key_parcel['harga_parcel_3'].'" harga_barang_4="'.$key_parcel['harga_parcel_4'].'" harga_barang_5="'.$key_parcel['harga_parcel_5'].'" harga_barang_6="'.$key_parcel['harga_parcel_6'].'" harga_barang_7="'.$key_parcel['harga_parcel_7'].'"  ber-stok="Barang" tipe_barang="Barang" id-barang="'.$key_parcel['id'].'" satuan="125"> '. $key_parcel['kode_parcel'].' ( '.$key_parcel['nama_parcel'].' ) </option>';
          }

        ?>
    </select>
  </div>

<input type="hidden" class="form-control" name="nama_barang" id="nama_barang" placeholder="Nama Barang" readonly="">

<div class="col-sm-2">
     <input type="text" style="height:15px;" class="form-control" name="jumlah_barang" autocomplete="off" id="jumlah_barang" placeholder="Jumlah Barang" required="">
 </div>

<div class="col-sm-2">
    <select type="text" style="font-size:15px; height:35px"  name="satuan_konversi" id="satuan_konversi" class="form-control"  required="" style="height:50px;font-size:15px; width: 100px" >    
     
    </select>
</div>
  
<div class="form-group col-sm-2">
    <input style="height:15px;" type="text" class="form-control" name="potongan" ata-toggle="tooltip" data-placement="top" title="Jika Ingin Potongan Dalam Bentuk Persen (%), input : 10%" autocomplete="off" id="potongan1" placeholder="Potongan " >
</div>


<div class="form-group col-sm-1">
    <input style="height:15px;font-size:15px;" type="text" class="form-control" name="tax" autocomplete="off" id="tax1"  placeholder="Tax(%)" >
</div>

<button type="submit" id="submit_produk" class="btn btn-success" style="font-size:15px" >Submit (F3) </button>



</div><!--end div class="row 1"-->


  <input type="hidden" class="form-control" name="jumlah_barang_tbs" id="jumlah_barang_tbs">
  <input type="hidden" class="form-control" name="real_id" id="real_id" value="<?php echo $id_pelanggan ?>">
  <input type="hidden" class="form-control" name="limit_stok" id="limit_stok">
  <input type="hidden" placeholder="Stok" class="form-control" name="jumlahbarang" id="jumlahbarang">
  <input type="hidden" class="form-control" name="ber_stok" id="ber_stok" placeholder="Ber Stok" >
  <input type="hidden" class="form-control" name="harga_lama" id="harga_lama">
  <input type="hidden" class="form-control" name="harga_baru" id="harga_baru">
  <input type="hidden" id="satuan_produk" name="satuan_produk" class="form-control" value="" >
  <input type="hidden" id="harga_produk" placeholder="Harga / Level" name="harga" class="form-control" value="" >
  <input type="hidden" id="harga_konversi" name="harga_konversi" class="form-control">
  <input type="hidden" id="id_produk" name="id_produk" placeholder="id_produk" class="form-control" value="" >  
  <input type="hidden" name="no_faktur" id="no_faktur0" class="form-control" value="<?php echo $nomor_faktur; ?>" >

</form> <!-- tag penutup form -->          
                
  <div class="table-responsive"> <!--tag untuk membuat garis pada tabel-->  
        <span id="span_tbs">  
            <table id="tabel_tbs_penjualan" class="table table-sm">
                <thead>
                    <th> Kode</th>
                    <th style="width:1000%"> Nama</th>
                    <th> Jumlah</th>
                    <th> Satuan </th>
                    <th> Harga </th>
                    <th> Potongan </th>
                    <th> Pajak </th>
                    <th> Subtotal </th>
                    <th> Hapus </th>
                    </thead>
                <tbody id="tbody">
                </tbody>              
            </table>
      </span>
</div>

                <h6 style="text-align: left ; color: red" ><i> * Klik 2x pada kolom jumlah barang jika ingin mengedit.</i></h6>
                <h6 style="text-align: left ;"><i>* Short Key (F2) untuk mencari Kode Produk atau Nama Produk.</i></h6>
                <h6 style="text-align: left ;"><i><b> * Data Order yang lama (Sudah tergabung). Silakan di Edit table di atas </b></i></h6>

<span id="tbs_bonus_penjualan"><!--start table_tbs_bnus_penjualan-->
<h5><b>Produk Promo</b></h5>
  <div class="table-responsive"> <!--tag untuk membuat garis pada tabel--> 
            <table id="table_tbs_bonus_penjualan" class="table table-sm">
                <thead>
                  <th> Kode  </th>
                  <th> Nama </th>
                  <th> Jumlah </th>
                  <th> satuan </th>
                  <th> Harga Promo </th>
                  <th> Subtotal </th>
                  <th> Keterangan </th>
                  <th> Hapus </th>
                </thead>
            </table>
   </div>
</span><!--end span table tbs_bonus_Penjualan-->


<div class="collapse" id="sss">

<div class="card card-block">

<div class="row">
    <div class="col-sm-4">
        <span id="select_order">
            <select style="font-size:15px; height:35px" name="hapus_order" id="hapus_order" class="form-control gg" required="" >
          <?php 
          
          // menampilkan seluruh data yang ada pada tabel suplier
          $query = $db->query("SELECT no_faktur_order FROM tbs_penjualan WHERE no_faktur = '$nomor_faktur' AND no_faktur_order != '' ");
          
          // menyimpan data sementara yang ada pada $query
          while($data = mysqli_fetch_array($query))
          {
        
                echo "<option value='".$data['no_faktur_order'] ."'>".$data['no_faktur_order'] ."</option>";
          
          }
          
          
          ?>
          </select>
    <input type="hidden" class="form-control" name="total_perorder" id="total_perorder">
     </span>
</div>

   <div class="col-sm-4"> 
     <button type="submit" id="btn-hps-order" class="btn btn-danger" style="font-size:15px" > Hapus </button>
   </div> 

</div>

  <h5><b>Data Order</b></h5> 
        <div class="table-responsive"> <!--tag untuk membuat garis pada tabel-->
            <span id="order_data">  
                <table id="table_tbs_order" class="table table-sm">
                    <thead>
                    <th style="width:500%"> No Faktur Order  </th>
                    <th> Kode  </th>
                    <th style="width:1000%"> Nama </th>
                    <th> Jumlah </th>
                    <th> Satuan </th>
                    <th> Harga </th>
                    <th> Subtotal </th>
                    <th> Potongan </th>
                    <th> Pajak </th>
                  </thead> 
                </table>
          </span>
      </div>  

</div><!-- end <div class="card card-block"> -->
</div><!-- end <div class="colapse"> -->




</div><!--DIV END cOL SM 6 1-->

<div class="col-sm-4">
 <form action="proses_bayar_edit_jual.php" id="form_jual" method="POST" >
    
    <style type="text/css">
    .disabled {
    opacity: 0.6;
    cursor: not-allowed;
    disabled: false;
    }
    </style>

    <div class="card card-block">


<div class="row">

<div class="form-group  col-sm-12">
      <label> Subtotal </label><br>
      <b><input type="text" name="total" id="total2" class="form-control" style="height: 20px; width:90%; font-size:20px;" placeholder="Total" readonly="" ></b>
</div>

<div class="form-group col-sm-6">
        <select style="display:none;" class="form-control" id="biaya_admin_select" name="biaya_admin_select" >
              <option value="0" selected=""> Silahkan Pilih </option>
                <?php 
                $get_biaya_admin = $db->query("SELECT persentase,nama FROM biaya_admin");
                while ( $take_admin = mysqli_fetch_array($get_biaya_admin))
                {
                echo "<option value='".$take_admin['persentase']."'>".$take_admin['nama']." ".$take_admin['persentase']."%</option>";
                }
                ?>
       </select>  
</div>

</div><!--end <div class="row">-->

<div class="row">

  <div class="col-xs-6">          
      <input type="hidden" name="biaya_admin_rupiah"  style="height:15px;font-size:15px" id="biaya_adm" class="form-control" placeholder="Biaya Admin Rp" autocomplete="off" >
  </div>

   <div class="col-xs-6">
       <input type="hidden" name="biaya_admin_persen" style="height:15px;font-size:15px" id="biaya_admin_persen" class="form-control" placeholder="Biaya Admin %" autocomplete="off" >
   </div>

</div> <!--end <div class="row">-->

<div class="row">
    
   <div class="form-group col-sm-6">  
          <label> Diskon ( Rp )</label><br>
          <input type="text" name="potongan" id="potongan_penjualan" value="<?php echo koma($potongan,2); ?>" style="height:15px;font-size:15px" class="form-control" placeholder="" autocomplete="off"  >    
  </div>


    <div class="form-group col-sm-6">
        <label> Diskon ( % )</label><br>
        <input type="text" name="potongan_persen" id="potongan_persen" value="<?php echo koma($hasil_persen,2); ?>" style="height:15px;font-size:15px" class="form-control" placeholder="" autocomplete="off" >
   </div>

   <input type="text" style="display: none" name="tax" id="tax" value="<?php echo $hasil_tax; ?>" style="height:15px;font-size:15px" class="form-control"  autocomplete="off" >  

</div> <!--end <div class="row">-->
          
          
<div class="row">

  <div class="form-group col-sm-6">
      <label> Tanggal </label><br>
      <input type="text" name="tanggal_jt" id="tanggal_jt" style="height:15px;font-size:15px" placeholder="Tanggal JT" class="form-control tanggal" value="<?php echo $data_penjualan['tanggal_jt']; ?>" autocomplete="off">
  </div>


<div class="form-group  col-sm-6">
    <label> Cara Bayar </label><br>
        <select type="text" name="cara_bayar" id="carabayar1" class="form-control" required=""  >
          <option value=""> Silahkan Pilih </option>
             <?php 
             $sett_akun = $db->query("SELECT sa.kas, da.nama_daftar_akun FROM setting_akun sa INNER JOIN daftar_akun da ON sa.kas = da.kode_daftar_akun");
             $data_sett = mysqli_fetch_array($sett_akun);

             echo "<option selected value='".$data_sett['kas']."'>".$data_sett['nama_daftar_akun'] ."</option>";
             
             $query = $db->query("SELECT nama_daftar_akun, kode_daftar_akun FROM daftar_akun WHERE tipe_akun = 'Kas & Bank'");
             while($data = mysqli_fetch_array($query))
             {
              echo "<option value='".$data['kode_daftar_akun']."'>".$data['nama_daftar_akun'] ."</option>";
              }
             ?>
    </select>
</div>

</div> <!--end <div class="row">-->

          <input type="hidden" name="tax_rp" id="tax_rp" class="form-control"  autocomplete="off" >
           <label style="display: none"> Adm Bank  (%)</label>
          <input type="hidden" name="adm_bank" id="adm_bank"  value="" class="form-control" >
          
          
<div class="row">

    <div class="form-group col-sm-6">
          <label style="font-size:15px"> Total Akhir</label><br>
          <b><input type="text" name="total" id="total1" class="form-control" value="" style="height: 20px; width:90%; font-size:20px;" placeholder="Total" readonly="" ></b>
    </div>

    <div class="form-group col-sm-6">
        <label> Pembayaran </label><br>
        <b><input type="text" name="pembayaran" id="pembayaran_penjualan" style="height: 20px; width:90%; font-size:20px;" autocomplete="off" class="form-control" style="font-size: 20px" value="<?php echo rp($tunai) ?>" ></b>
    </div>

</div> <!--end <div class="row">-->


<div class="row">

      <div class="col-sm-6">
          <label> Kembalian </label><br>
          <b><input type="text" name="sisa_pembayaran" id="sisa_pembayaran_penjualan" style="height:15px;font-size:15px" class="form-control"  readonly="" required=""  style="font-size: 20px" value="<?php echo rp($kembalian) ?>" ></b>
      </div>
          
      <div class="col-sm-6">
          <label> Kredit </label><br>
          <b><input type="text" name="kredit" id="kredit" class="form-control" style="height:15px;font-size:15px"  readonly="" required="" value="<?php echo $kredit ?>" ></b>
      </div>

</div> <!--end <div class="row">-->
          
      <label> Keterangan </label><br>
        <textarea type="text" name="keterangan" id="keterangan" class="form-control"> <?php echo $data_penjualan['keterangan'] ?></textarea>

          <b><input type="hidden" name="zxzx" id="zxzx" class="form-control" style="height: 50px; width:90%; font-size:25px;"  readonly="" required="" ></b>
          <b><input type="hidden" name="jumlah_bayar_lama" id="jumlah_bayar_lama" value="<?php echo $jumlah_bayar_lama; ?>" class="form-control" style="height: 50px; width:90%; font-size:25px;"  readonly=""></b>

<?php 

if ($_SESSION['otoritas'] == 'Pimpinan') {
 echo '<label style="display:none"> Total Hpp </label><br>
          <input type="hidden" name="total_hpp" id="total_hpp" style="height: 50px; width:90%; font-size:25px;" class="form-control" placeholder="" readonly="" required="">';
}

mysqli_close($db); 
 ?>

          <input type="hidden" name="jumlah" id="jumlah1" class="form-control" placeholder="jumlah">   <br> 
          <!-- memasukan teks pada kolom kode pelanggan, dan nomor faktur penjualan namun disembunyikan -->
          <input type="hidden" name="no_faktur" id="nofaktur" class="form-control" value="<?php echo $nomor_faktur; ?>" required="" >
          <input type="hidden" name="kode_pelanggan" id="k_pelanggan" class="form-control" required="" >

</div>


    <button type="submit" id="penjualan" class="btn btn-info" data-faktur='<?php echo $nomor_faktur ?>' style="font-size:15px">Bayar (F8)</button>
    <button type="submit" id="piutang" class="btn btn-warning" data-faktur='<?php echo $nomor_faktur; ?>' style="font-size:15px">Piutang (F9)</button>


          
  <div class="row">
      <div class="col-sm-3">
      <a href="penjualan.php?status=semua" id="transaksi_baru" class="btn btn-primary"  style="display: none;">Kembali</a>
  </div>
    
  <div class="col-sm-3">
      <a href='cetak_penjualan_tunai.php?no_faktur=<?php echo $nomor_faktur; ?>' id="cetak_tunai"  style="display: none;" class="btn btn-success" target="blank">Cetak Tunai </a>
  </div>

  <div class="col-sm-3">
      <a href='cetak_penjualan_tunai_besar.php?no_faktur=<?php echo $nomor_faktur; ?>' id="cetak_tunai_besar" style="display: none;"  class="btn btn-info" target="blank">Cetak Besar</a>
  </div>
    
  <div class="col-sm-3">
    <a href='cetak_penjualan_piutang.php?no_faktur=<?php echo $nomor_faktur ?>' id="cetak_piutang"  style="display: none;" class="btn btn-warning" target="blank"> <span class="  glyphicon glyphicon-print"> </span> Cetak Piutang </a>
  </div>

        </div>      
    </div>

</form>

</div>
        <br>
          <div class="alert alert-success" id="alert_berhasil" style="display:none">
          <strong>Success!</strong> Pembayaran Berhasil
      </div>
</div><!-- end of row -->   
          


</div><!-- end of container -->


    
<script>
//untuk menampilkan data tabel
$(document).ready(function(){
    $("#kd_pelanggan").focus();
});

</script>

<!-- SHORTCUT -->
<!-- js untuk tombol shortcut -->
 <script src="shortcut.js"></script>
<!-- js untuk tombol shortcut -->


<script type="text/javascript">
  //SELECT CHOSSESN    
$(".chosen").chosen({no_results_text: "Maaf, Data Tidak Ada!",search_contains:true});    
</script>


<script> 
    shortcut.add("f2", function() {
        // Do something

        $("#kode_barang").focus();

    });

    
    shortcut.add("f1", function() {
        // Do something

        $("#cari_produk_penjualan").click();
       $("div#tabel_cari_filter input").focus();

    }); 

    shortcut.add("f6", function() {
        // Do something

        $("#daftar_order").click();
        $("div#table_order_filter input").focus();

    }); 
    
    shortcut.add("f3", function() {
        // Do something

        $("#submit_produk").click();

    }); 

    
    shortcut.add("f4", function() {
        // Do something

        $("#carabayar1").focus();

    }); 

    
    shortcut.add("f7", function() {
        // Do something

        $("#pembayaran_penjualan").focus();

    }); 

    
    shortcut.add("f8", function() {
        // Do something

        $("#penjualan").click();

    }); 

    
    shortcut.add("f9", function() {
        // Do something

        $("#piutang").click();

    }); 

    
    shortcut.add("f10", function() {
        // Do something

        $("#simpan_sementara").click();

    }); 

    
    shortcut.add("ctrl+b", function() {
        // Do something

    var session_id = $("#session_id").val()

        window.location.href="batal_penjualan.php?session_id="+session_id+"";


    }); 

     shortcut.add("ctrl+k", function() {
        // Do something

        $("#cetak_langsung").click();


    }); 
</script>
<!-- SHORTCUT -->


<!--DATA TABLE MENGGUNAKAN AJAX-->
<script type="text/javascript" language="javascript" >
      $(document).ready(function() {

  $(document).on('click', '#daftar_order', function (e) {

            $('#table_order').DataTable().destroy();

          var dataTable = $('#table_order').DataTable( {
          "processing": true,
          "serverSide": true,
          "ajax":{
            url :"datatable_daftar_order.php", // json datasource
           
            type: "post",  // method  , by default get
            error: function(){  // error handling
              $(".employee-grid-error").html("");
              $("#table_order").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
              $("#employee-grid_processing").css("display","none");
            }
        },
            
            "fnCreatedRow": function( nRow, aData, iDataIndex ) {

              $(nRow).attr('class', "pilih_order");
              $(nRow).attr('data-order', aData[0]);
              $(nRow).attr('data-total', aData[4]);

          },
        });

        $("#form").submit(function(){
        return false;
        });
        

      } );
    } );
</script>
<!--/DATA TABLE MENGGUNAKAN AJAX-->

<!--DATA TABLE PRODUK PARCEL MENGGUNAKAN AJAX-->
<script type="text/javascript" language="javascript" >
  $(document).ready(function() {
    $(document).on('click', '#daftar_parcel', function (e) {
        $('#table_parcel').DataTable().destroy();

          var dataTable = $('#table_parcel').DataTable( {
          "processing": true,
          "serverSide": true,
          "ajax":{
            url :"datatable_daftar_parcel.php", // json datasource           
            type: "post",  // method  , by default get
            error: function(){  // error handling
              $(".employee-grid-error").html("");
              $("#table_parcel").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
              $("#employee-grid_processing").css("display","none");
            }
        },
            
            "fnCreatedRow": function( nRow, aData, iDataIndex ) {

              $(nRow).attr('class', "pilih-parcel");
              $(nRow).attr('data-kode', aData[0]);
              $(nRow).attr('data-nama', aData[1]);
              $(nRow).attr('data-stok', aData[2]);
              $(nRow).attr('data-harga1', aData[3]);
              $(nRow).attr('data-harga2', aData[4]);
              $(nRow).attr('data-harga3', aData[5]);
              $(nRow).attr('data-harga4', aData[6]);
              $(nRow).attr('data-harga5', aData[7]);
              $(nRow).attr('data-harga6', aData[8]);
              $(nRow).attr('data-harga7', aData[9]);
              $(nRow).attr('data-faktur', aData[10]);
              $(nRow).attr('data-id', aData[11]);

          },
        });

      $("#form").submit(function(){
        return false;
      });
        
    });
  });
</script>
<!--/DATA TABLE PRODUK PARCEL MENGGUNAKAN AJAX-->


<!--untuk memasukkan perintah java script-->
<script type="text/javascript">

// jika dipilih, nim akan masuk ke input dan modal di tutup
  $(document).on('click', '.pilih_order', function (e) {

    var no_faktur = $("#nomor_faktur_penjualan").val();


$.post("ambil_edit_order_penjualan.php",{no_faktur_order:$(this).attr('data-order'),no_faktur:no_faktur},function(data){

      $("#modal_order").modal('hide');


$.post("ambil_select_order_edit.php",{no_faktur:no_faktur},function(data){
  $("#select_order").html(data);
  });

});





/*var biaya_adm = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#biaya_adm").val()))));
    if (biaya_adm == '' )
    {
      biaya_adm = 0;
    }*/

var total_perorder = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($(this).attr('data-total')))));
    if (total_perorder == '' ) 
     {
       total_perorder = 0;
    }
      
 var subtotal = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#total2").val()))));
    if (subtotal == '') 
     {
      subtotal = 0;
    }

var total_akhir1 = parseFloat(subtotal.replace(",",".")) + parseFloat(total_perorder);


    var pot_fakt_per = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#potongan_persen").val()))));
   if (pot_fakt_per == "" || pot_fakt_per == 0,00 ) 
      {
        pot_fakt_per = 0.00;
      }

    var pot_fakt_rp = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#potongan_penjualan").val()))));
 if (pot_fakt_rp == ""  || pot_fakt_rp == 0,00 ) 
      {
        pot_fakt_rp = 0.00;
      }

   var tax_faktur = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#tax").val()))));


    if (pot_fakt_per == 0) 
    {
      var potongaaan = pot_fakt_rp;
      var pot_fakt_per = parseFloat(potongaaan.replace(",",".")) / parseFloat(total_akhir1) * 100;


  if (pot_fakt_rp == 0.00 )
    {   
      var total_akhier = parseFloat(total_akhir1);
    }
else
    {
      var total_akhier = parseFloat(total_akhir1) /*+ parseFloat(biaya_adm.replace(",","."))*/ - parseFloat(pot_fakt_rp.replace(",","."));
    }

 //Hitung pajak
        if (tax_faktur != 0 ) {
        var hasil_tax = parseFloat(total_akhier) * parseFloat(tax_faktur) / 100;

        }
        else
        {
        var hasil_tax = 0;
        }
 //end hitung pajak
    
    var total_akhir = parseFloat(total_akhier) + parseFloat(Math.round(hasil_tax));


  }//END pot_fakt_per == 0

else if(pot_fakt_rp == 0)
    {
      var potongaaan = pot_fakt_per;
      var pos = potongaaan.search("%");
      var potongan_persen = potongaaan;
       var   potongan_persen = potongan_persen.replace("%","");
     var potongaaan = parseFloat(total_akhir1) * parseFloat(potongan_persen.replace(',','.')) / 100;

  if (pot_fakt_rp == 0.00 )
{  
    var total_akhier = parseFloat(total_akhir1);
}
else
{
     var total_akhier = parseFloat(total_akhir1) /*+ parseFloat(biaya_adm.replace(',','.'))*/ - parseFloat(potongaaan); 
}

//Hitung pajak
        if (tax_faktur != 0) {
        var hasil_tax = parseFloat(total_akhier) * parseFloat(tax_faktur) / 100;

        }
        else
        {
        var hasil_tax = 0;
        }
//end hitung pajak
   
   var total_akhir = parseFloat(total_akhier) + parseFloat(Math.round(hasil_tax));

}//end pot_fakt_rp == 0

else if(pot_fakt_rp != 0 && pot_fakt_per != 0)
  {
      var potongaaan = pot_fakt_per;
      var pos = potongaaan.search("%");
      var potongan_persen = potongaaan;
      var   potongan_persen = potongan_persen.replace("%","");
      var potongaaan = parseFloat(total_akhir1) * parseFloat(potongan_persen.replace(',','.')) / 100;

 if (pot_fakt_rp == 0.00 ){      
    var total_akhier = parseFloat(total_akhir1);
  }
else{
    var total_akhier = parseFloat(total_akhir1) /*+ parseFloat(biaya_adm.replace(',','.'))*/ - parseFloat(potongaaan); 
  }

//Hitung pajak
        if (tax_faktur != 0) {
        var hasil_tax = parseFloat(total_akhier) * parseFloat(tax_faktur) / 100;
        }
        else{
        var hasil_tax = 0;
        }
//end hitung pajak
    var total_akhir = parseFloat(total_akhier) + parseFloat(Math.round(hasil_tax));


 }//pot_fakt_rp != 0 && pot_fakt_per != 0


// ganti / update data pembayaran 
    $("#total1").val(total_akhir.format(2, 3, '.', ','));
  
  if (pot_fakt_rp == 0.00)
    {
      $("#potongan_penjualan").val();
    }
  else
    {    
      $("#potongan_penjualan").val(potongaaan.format(2, 3, '.', ','));
    }
      $("#tax_rp").val(hasil_tax.format(2, 3, '.', ','));
      $("#total2").val(total_akhir1.format(2, 3, '.', ','));
// ganti / update data pembayaran 


// ambil datatable yang terbaru
            $('#table_tbs_order').DataTable().destroy();
          var dataTable = $('#table_tbs_order').DataTable( {
          "processing": true,
          "serverSide": true,
          "ajax":{
            url :"datatable_tbs_order.php", // json datasource
           
            type: "post",  // method  , by default get
            error: function(){  // error handling
              $(".employee-grid-error").html("");
              $("#table_tbs_order").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
              $("#employee-grid_processing").css("display","none");
            }
        },      
    });
// ambil datatable yang terbaru



});

</script>
<!--end javascript order all-->


<!--START INPUT DARI MODAL PARCEL-->
<script type="text/javascript">
//AMBIL DAN INPUT KE FORM DARI CARI PARCEL
$(document).on('click', '.pilih-parcel', function (e) {

  document.getElementById("kode_barang").value = $(this).attr('data-kode');
  $("#kode_barang").trigger('chosen:updated');

  document.getElementById("nama_barang").value = $(this).attr('data-nama');
  document.getElementById("ber_stok").value = "Barang";
  document.getElementById("id_produk").value = $(this).attr('data-id');
  document.getElementById("satuan_konversi").value = "125"
  document.getElementById("satuan_produk").value = "125";

  var kode_barang = $("#kode_barang").val();

 $.post('cek_kode_barang_tbs_penjualan.php',{kode_barang:kode_barang}, function(data){
  if(data == 1){
    alert("Anda Tidak Bisa Menambahkan Barang Yang Sudah Ada, Silakan Edit atau Pilih Barang Yang Lain !");

    $("#kode_barang").val('');
    $("#kode_barang").trigger('chosen:updated');
    $("#kode_barang").trigger('chosen:open');
    $("#nama_barang").val('');
   }//penutup if

    });////penutup function(cek_kode_barang_tbs_penjualan)

var level_harga = $("#level_harga").val();

var harga_level_1 = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($(this).attr('data-harga1')))));
var harga_level_2 = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($(this).attr('data-harga2')))));  
var harga_level_3 = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($(this).attr('data-harga3')))));
var harga_level_4 = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($(this).attr('data-harga4')))));
var harga_level_5 = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($(this).attr('data-harga5')))));  
var harga_level_6 = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($(this).attr('data-harga6')))));
var harga_level_7 = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($(this).attr('data-harga7')))));
console.log(harga_level_1);

if (level_harga == "harga_1") {
  $("#harga_produk").val(harga_level_1);
  $("#harga_lama").val(harga_level_1);
  $("#harga_baru").val(harga_level_1);
  $('#kolom_cek_harga').val('1');
}

else if (level_harga == "harga_2") {
  $("#harga_produk").val(harga_level_2);
  $("#harga_baru").val(harga_level_2);
  $("#harga_lama").val(harga_level_2);
  $('#kolom_cek_harga').val('1');
}

else if (level_harga == "harga_3") {
  $("#harga_produk").val(harga_level_3);
  $("#harga_lama").val(harga_level_3);
  $("#harga_baru").val(harga_level_3);
  $('#kolom_cek_harga').val('1');
}

else if (level_harga == "harga_4") {
  $("#harga_produk").val(harga_level_4);
  $("#harga_lama").val(harga_level_4);
  $("#harga_baru").val(harga_level_4);
  $('#kolom_cek_harga').val('1');
}

else if (level_harga == "harga_5") {
  $("#harga_produk").val(harga_level_5);
  $("#harga_lama").val(harga_level_5);
  $("#harga_baru").val(harga_level_5);
  $('#kolom_cek_harga').val('1');
}

else if (level_harga == "harga_6") {
  $("#harga_produk").val(harga_level_6);
  $("#harga_lama").val(harga_level_6);
  $("#harga_baru").val(harga_level_6);
  $('#kolom_cek_harga').val('1');
}

else if (level_harga == "harga_7") {
  $("#harga_produk").val(harga_level_7);
  $("#harga_lama").val(harga_level_7);
  $("#harga_baru").val(harga_level_7);
  $('#kolom_cek_harga').val('1');
}
document.getElementById("jumlahbarang").value = $(this).attr('data-stok');

         $.getJSON("cek_data_satuan_konversi_parcel.php", function(result){

                $(".span-satuan").remove();  
                   
                //satuan konversi
                $.each(result.satuan, function(i, item) {//  $.each(result.satuan, 
                $("#option_satuan-"+result.satuan[i].id).remove();
                var option_barang = "<option class='span-satuan' id='option_satuan-"+result.satuan[i].id+"' value='"+result.satuan[i].id+"' data-nama='"+result.satuan[i].nama+"'>"+result.satuan[i].nama+"</option>"
                $("#satuan_konversi").show().append(option_barang);

           });//  $.each(result.satuan, 
                                              
       });

 
  $("#jumlah_barang").focus();
  $('#myModal').modal('hide'); 


});

</script>



<!--java scrip order all-->
<script type="text/javascript" language="javascript" >

  $(document).on('click', '#btn-hps-order', function (e) {

var hapus_order = $("#hapus_order").val();
var no_faktur = $("#nomor_faktur_penjualan").val();

$.post("hapus_order_tbs_edit.php",{hapus_order:hapus_order,no_faktur:no_faktur},function(data){


$.post("ambil_select_order_edit.php",{no_faktur:no_faktur},function(data){
  $("#select_order").html(data);
  });

    
}); 


// ambil datatable yang terbaru
            $('#table_tbs_order').DataTable().destroy();
          var dataTable = $('#table_tbs_order').DataTable( {
          "processing": true,
          "serverSide": true,
          "ajax":{
            url :"datatable_edit_tbs_order.php", // json datasource
            "data": function ( d ) {
                    d.no_faktur = $("#nomor_faktur_penjualan").val();
                                // d.custom = $('#myInput').val();
                                // etc
            },
            type: "post",  // method  , by default get
            error: function(){  // error handling
              $(".employee-grid-error").html("");
              $("#table_tbs_order").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
              $("#employee-grid_processing").css("display","none");
            }
        },      
    });


// ambil datatable yang terbaru
var total_perorder = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#total_perorder").val()))));
          if (total_perorder == '') 
          {
          total_perorder = 0;
          }
       
 var subtotal = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#total2").val()))));
          if (subtotal == '') 
          {
          subtotal = 0;
          }

var total_akhir1 = parseFloat(subtotal.replace(",",".")) - parseFloat(total_perorder.replace(",","."));

/*var biaya_adm = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#biaya_adm").val()))));
    if (biaya_adm == '' || biaya_adm == 0,00)
    {
      biaya_adm = 0;
    }*/

var pot_fakt_per = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#potongan_persen").val()))));
   if (pot_fakt_per == "" || pot_fakt_per == 0,00) {
        pot_fakt_per = 0.00;
      }

var pot_fakt_rp = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#potongan_penjualan").val()))));
 if (pot_fakt_rp == "" || pot_fakt_rp == 0,00 ) {
        pot_fakt_rp = 0.00;
      }


var tax_faktur = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#tax").val()))));
    if (pot_fakt_per == 0) {
      var potongaaan = pot_fakt_rp;

      var pot_fakt_per = parseFloat(potongaaan) / parseFloat(total_akhir1) * 100;


if (pot_fakt_rp == 0.00 ){
   var total_akhier = parseFloat(total_akhir1);
}
else{
  var total_akhier = parseFloat(total_akhir1) /*+ parseFloat(biaya_adm.replace(',','.'))*/ - parseFloat(pot_fakt_rp.replace(',','.'));
}

//Hitung pajak
  if (tax_faktur != 0 ) {
        var hasil_tax = parseFloat(total_akhier) * parseFloat(tax_faktur) / 100;

        }
  else{
        var hasil_tax = 0;
        }
//end hitung pajak

    var total_akhir = parseFloat(total_akhier) + parseFloat(Math.round(hasil_tax));


    }
    else if(pot_fakt_rp == 0)
    {
      var potongaaan = pot_fakt_per;
      var pos = potongaaan.search("%");
      var potongan_persen = potongaaan;
       var   potongan_persen = potongan_persen.replace("%","");
     var potongaaan = parseFloat(total_akhir1) * parseFloat(potongan_persen.replace(',','.')) / 100;

  if ( pot_fakt_rp == 0.00 )
{   
    var total_akhier = parseFloat(total_akhir1);
}
else
{
   var total_akhier = parseFloat(total_akhir1) /*+ parseFloat(biaya_adm.replace(',','.'))*/ - parseFloat(potongaaan);
}

//Hitung pajak
        if (tax_faktur != 0) {
        var hasil_tax = parseFloat(total_akhier) * parseFloat(tax_faktur) / 100;

        }
        else
        {
        var hasil_tax = 0;
        }
//end hitung pajak

   var total_akhir = parseFloat(total_akhier) /*+ parseFloat(Math.round(hasil_tax))*/;

    }
     else if(pot_fakt_rp != 0 && pot_fakt_per != 0)
    {
      var potongaaan = pot_fakt_per;
      var pos = potongaaan.search("%");
      var potongan_persen = potongaaan;
       var   potongan_persen = potongan_persen.replace("%","");
     var potongaaan = parseFloat(total_akhir1) * parseFloat(potongan_persen.replace(",",".")) / 100;

  if (pot_fakt_rp == 0.00 )
{   
    var total_akhier = parseFloat(total_akhir1);
}
else
{
    var total_akhier = parseFloat(total_akhir1) /*+ parseFloat(biaya_adm.replace(",","."))*/ - parseFloat(potongaaan);
}


//Hitung pajak
   if (tax_faktur != 0) 
      {
        var hasil_tax = parseFloat(total_akhier) * parseFloat(tax_faktur) / 100;
      }
   else
      {
        var hasil_tax = 0;
      }
//end hitung pajak

    var total_akhir = parseFloat(total_akhier) + parseFloat(Math.round(hasil_tax));

    }


      $("#total1").val(total_akhir.format(2, 3, '.', ','));
  if (pot_fakt_rp == 0.00)
      {
        $("#potongan_penjualan").val();
      }
      else
      {    
        $("#potongan_penjualan").val(potongaaan.format(2, 3, '.', ','));
      }

      $("#tax_rp").val(hasil_tax.format(2, 3, '.', ','));
      $("#total2").val(total_akhir1.format(2, 3, '.', ','));


});

</script>
<script type="text/javascript">
  $(document).ready(function(){
    // show ajax tbs bonus
   $('#table_tbs_bonus_penjualan').DataTable().destroy();
            var dataTable = $('#table_tbs_bonus_penjualan').DataTable( {
            "processing": true,
            "serverSide": true,
            "info":     false,
            "language": { "emptyTable":     "My Custom Message On Empty Table" },
            "ajax":{
              url :"datatable_edit_tbs_bonus_penjualan.php", // json datasource
              "data": function ( d ) {
                    d.no_faktur = $("#nomor_faktur_penjualan").val();
                                // d.custom = $('#myInput').val();
                                // etc
            },
                  type: "post",  // method  , by default get
              error: function(){  // error handling
                $(".tbody").html("");
                $("#table_tbs_bonus_penjualan").append('<tbody class="tbody"><tr><th colspan="3"></th></tr></tbody>');
                $("#tableuser_processing").css("display","none");
                
              }
            }   

      });
//end show ajax tbs
  });
</script>

<script type="text/javascript"><!--AWAL PUNYYA HAPUS PROMO PENJUALAN-->
$(document).ready(function(){
  var no_faktur = $("#nomor_faktur_penjualan").val();
//fungsi hapus data TBS BONUS PENJUALAN
      $(document).on('click','.btn-hapus-tbsbonus',function(e){

          
            var idnya = $(this).attr("data-id");
            var kodenya = $(this).attr("data-kode-produk");
            var nama_barang = $(this).attr("data-produk");
            var qty = $(this).attr("data-qty");

            /*var total_disc_promo = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#subtotal_jual_disc").val()))));
            var subtotal_tbs = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#total2").val()))));
            var total_akhir = parseFloat(subtotal_tbs - total_disc_promo);*/

            // start hapus ajax TBS PENJUALAN
            var pesan_alert = confirm("Apakah Anda Yakin Ingin Menghapus "+nama_barang+""+ "?");
            if (pesan_alert == true) {

                    $.post("hapus_tbs_bonus_penjualan.php",{idnya:idnya,kodenya:kodenya},function(data){
                      
                      $('#table_tbs_bonus_penjualan').DataTable().destroy();

                                    var dataTable = $('#table_tbs_bonus_penjualan').DataTable( {
                                      "processing": true,
                                      "serverSide": true,
                                      "ajax":{
                                        url :"datatable_tbs_bonus_penjualan.php", // json datasource
                                       
                                         
                                          type: "post",  // method  , by default get
                                        error: function(){  // error handling
                                          $(".employee-grid-error").html("");
                                          $("#table_tbs_bonus_penjualan").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                                          $("#employee-grid_processing").css("display","none");
                                          }
                                      },
                                         "fnCreatedRow": function( nRow, aData, iDataIndex ) {

                                          $(nRow).attr('class','tr-id-'+aData[11]+'');         

                                      }
                                    });// end var dataTable = $('#table_tbs_bonus_penjualan').DataTable
                              //cek total keseluruhan tbs penjualan
                              $.getJSON("cek_total_edit_penjualan.php",{no_faktur:no_faktur},function(total_seluruhnya){
                                    //cek total keseluruhan bonus disc
                                    var total_penjualannya = total_seluruhnya.total_penjualan;

                                  $.getJSON("cek_total_bonus_promo_edit_penjualan.php",{no_faktur:no_faktur},function(total_subtotal_promo){
                                    console.log(total_subtotal_promo);
                                    console.log(total_penjualannya);
                                      if (total_subtotal_promo < 1) {
                                        var subtotal_promo = 0;
                                      }
                                      else{
                                        var subtotal_promo = total_subtotal_promo;
                                      }
                                      
                                      var subtotal_tampil = parseFloat(total_penjualannya) + parseFloat(subtotal_promo);
                                  //jika tbs ada maka
                                  if (total_seluruhnya != 0) {
                                    $("#total2").val(subtotal_tampil.format(2, 3, '.', ','));
                                  $("#total1").val(subtotal_tampil.format(2, 3, '.', ','));
                                  }
                                  else{
                                    $("#total2").val('0');
                                  $("#total1").val('0');
                                  }
                                  //jika tbs bonus ada maka 
                                  if (total_subtotal_promo < 1) {
                                    $("#tbs_bonus_penjualan").hide();
                                  }
                                  else{
                                    $("#tbs_bonus_penjualan").show();
                                  }
                                  // end if (tot == 0)
                                }); // end cek_total_bonus_promo
                              }); // end cek_total_seluruh

                    }); // end $.post("hapus_tbs_bonus_penjualan.php",{idnya:idnya,kodenya:kodenya},function(data)
            }// end if (pesan_alert == true)

      //end hapus ajax

      }); // end $(document).on('click','.btn-hapus-tbsbonus',function(e)
      $('form').submit(function(){
        return false;
      });// end $('form').submit(function()


  });// end $(document).ready(function()
  
</script><!--AKHIR PUNYYA HAPUS PROMO PENJUALAN-->

<script type="text/javascript">
   $(document).on('ready', function (e) {                
// START DATATABLE AJAX START TBS PENJUALAN
      $('#tabel_tbs_penjualan').DataTable().destroy();
      $('#table_tbs_order').DataTable().destroy();


            var dataTable = $('#tabel_tbs_penjualan').DataTable( {
            "processing": true,
            "serverSide": true,
            "info":     false,
            "language": { "emptyTable":     "My Custom Message On Empty Table" },
            "ajax":{
              url :"data_tbs_edit_penjualan.php", // json datasource
             "data": function ( d ) {
                    d.no_faktur = $("#nomor_faktur_penjualan").val();
                                // d.custom = $('#myInput').val();
                                // etc
            },
                  type: "post",  // method  , by default get
              error: function(){  // error handling
                $(".tbody").html("");
                $("#tabel_tbs_penjualan").append('<tbody class="tbody"><tr><th colspan="3"></th></tr></tbody>');
                $("#tableuser_processing").css("display","none");
                
              }
            }   

      });

// ambil datatable order yang terbaru
            $('#table_tbs_order').DataTable().destroy();

          var dataTable = $('#table_tbs_order').DataTable( {
          "processing": true,
          "serverSide": true,
          "ajax":{
            url :"datatable_edit_tbs_order.php", // json datasource
            "data": function ( d ) {
                    d.no_faktur = $("#nomor_faktur_penjualan").val();
                                // d.custom = $('#myInput').val();
                                // etc
            },

            type: "post",  // method  , by default get
            error: function(){  // error handling
              $(".employee-grid-error").html("");
              $("#table_tbs_order").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
              $("#employee-grid_processing").css("display","none");
            }
        },
        });
// ambil datatable order yang terbaru

        
        $("#span_tbs").show()
        $("#btnRujukLab").show()


// END DATATABLE AJAX END DATATABLE AJAX TBS PENJUALAN
});
 </script>


<!--untuk memasukkan perintah java script-->
<script type="text/javascript">

// jika dipilih, nim akan masuk ke input dan modal di tutup
  $(document).on('click', '.pilih', function (e) {
  document.getElementById("kode_barang").value = $(this).attr('data-kode');
  $("#kode_barang").trigger('chosen:updated');

  document.getElementById("nama_barang").value = $(this).attr('nama-barang');
  document.getElementById("limit_stok").value = $(this).attr('limit_stok');
  document.getElementById("satuan_produk").value = $(this).attr('satuan');
  document.getElementById("ber_stok").value = $(this).attr('ber-stok');
  document.getElementById("harga_lama").value = $(this).attr('harga');
  document.getElementById("harga_baru").value = $(this).attr('harga');
  document.getElementById("satuan_konversi").value = $(this).attr('satuan');
  document.getElementById("id_produk").value = $(this).attr('id-barang');
  
  var kode_barang = $("#kode_barang").val();
  var no_faktur = $("#nomor_faktur_penjualan").val();

$.post('cek_kode_barang_tbs_edit_penjualan.php',{kode_barang:kode_barang,no_faktur:no_faktur}, function(data){
  if(data == 1){
    alert("Anda Tidak Bisa Menambahkan Barang Yang Sudah Ada, Silakan Edit atau Pilih Barang Yang Lain !");

    $("#kode_barang").val('');
    $("#kode_barang").trigger('chosen:updated');
    $("#kode_barang").trigger('chosen:open');
    $("#nama_barang").val('');

   }//penutup if

    });////penutup function(cek_kode_barang_tbs_penjualan)

var level_harga = $("#level_harga").val();

var harga_level_1 = $(this).attr('harga');
var harga_level_2 = $(this).attr('harga_level_2');  
var harga_level_3 = $(this).attr('harga_level_3');
var harga_level_4 = $(this).attr('harga_level_4');
var harga_level_5 = $(this).attr('harga_level_5');  
var harga_level_6 = $(this).attr('harga_level_6');
var harga_level_7 = $(this).attr('harga_level_7');

if (level_harga == "harga_1") {
  $("#harga_produk").val(harga_level_1);
  $("#harga_lama").val(harga_level_1);
  $("#harga_baru").val(harga_level_1);
  $('#kolom_cek_harga').val('1');
}

else if (level_harga == "harga_2") {
  $("#harga_produk").val(harga_level_2);
  $("#harga_baru").val(harga_level_2);
  $("#harga_lama").val(harga_level_2);
  $('#kolom_cek_harga').val('1');
}

else if (level_harga == "harga_3") {
  $("#harga_produk").val(harga_level_3);
  $("#harga_lama").val(harga_level_3);
  $("#harga_baru").val(harga_level_3);
  $('#kolom_cek_harga').val('1');
}

else if (level_harga == "harga_4") {
  $("#harga_produk").val(harga_level_4);
  $("#harga_lama").val(harga_level_4);
  $("#harga_baru").val(harga_level_4);
  $('#kolom_cek_harga').val('1');
}

else if (level_harga == "harga_5") {
  $("#harga_produk").val(harga_level_5);
  $("#harga_lama").val(harga_level_5);
  $("#harga_baru").val(harga_level_5);
  $('#kolom_cek_harga').val('1');
}

else if (level_harga == "harga_6") {
  $("#harga_produk").val(harga_level_6);
  $("#harga_lama").val(harga_level_6);
  $("#harga_baru").val(harga_level_6);
  $('#kolom_cek_harga').val('1');
}

else if (level_harga == "harga_7") {
  $("#harga_produk").val(harga_level_7);
  $("#harga_lama").val(harga_level_7);
  $("#harga_baru").val(harga_level_7);
  $('#kolom_cek_harga').val('1');
}
  document.getElementById("jumlahbarang").value = $(this).attr('jumlah-barang');

         $.getJSON("cek_data_satuan_konversi.php?kode_barang="+kode_barang, function(result){

                $(".span-satuan").remove();  
                //satuan dasar
                $("#option_satuan-"+result.id_satuan_dasar).remove();
                var option_barang = "<option class='span-satuan' id='option_satuan-"+result.id_satuan_dasar+"' value='"+result.id_satuan_dasar+"' data-nama='"+result.nama_satuan_dasar+"'>"+result.nama_satuan_dasar+"</option>"
                $("#satuan_konversi").show().append(option_barang);
                   
                //satuan konversi
                $.each(result.satuan, function(i, item) {//  $.each(result.satuan, 
                $("#option_satuan-"+result.satuan[i].id).remove();
                var option_barang = "<option class='span-satuan' id='option_satuan-"+result.satuan[i].id+"' value='"+result.satuan[i].id+"' data-nama='"+result.satuan[i].nama+"'>"+result.satuan[i].nama+"</option>"
                $("#satuan_konversi").show().append(option_barang);

           });//  $.each(result.satuan, 
                                              
       });

$.post("lihat_promo_alert.php",{id_barang:$(this).attr('id-barang')},function(data){

   
    if (data.promo != null)
    {
      $("#modal_promo_alert").modal('show');
      $("#tampil_alert").html(data.promo);
    } 

});

  $('#myModal').modal('hide'); 
  $("#jumlah_barang").focus();


});

  </script>


  <script type="text/javascript">
$(document).ready(function(){
  //end cek level harga
  $("#level_harga").change(function(){
  
  var level_harga = $("#level_harga").val();
  var kode_barang = $("#kode_barang").val();
  var satuan_konversi = $("#satuan_konversi").val();
  var jumlah_barang = $("#jumlah_barang").val();
  var id_produk = $("#id_produk").val();

    $.getJSON("cek_level_harga_barang.php?level_harga="+level_harga+"&kode_barang="+kode_barang+"&jumlah_barang="+jumlah_barang+"&id_produk="+id_produk+"&satuan_konversi="+satuan_konversi,function(data){

            $("#harga_produk").val(data.harga_level);
            $("#harga_baru").val(data.harga_level);
            $("#harga_konversi").val(data.harga_konversi);
          });
    });
});
//end cek level harga
</script>





<!-- cek stok satuan konversi keyup-->
<script type="text/javascript">
  $(document).ready(function(){
    $("#jumlah_barang").keyup(function(){
      var jumlah_barang = $("#jumlah_barang").val();
      var satuan_konversi = $("#satuan_konversi").val();
      var kode_barang = $("#kode_barang").val();
      var id_produk = $("#id_produk").val();
      var no_faktur = $("#no_faktur0").val();
      var prev = $("#satuan_produk").val();

      $.post("cek_stok_konversi_edit_penjualan.php",
        {jumlah_barang:jumlah_barang,satuan_konversi:satuan_konversi,kode_barang:kode_barang,id_produk:id_produk,no_faktur:no_faktur},function(data){

          if (data < 0) {
            alert("Jumlah Melebihi Stok");
            $("#jumlah_barang").val('');
          $("#satuan_konversi").val(prev);
          }else{

                        $.getJSON("cek_setting_diskon_jumlah.php?jumlah_barang="+jumlah_barang+"&kode_barang="+kode_barang+"&id_produk="+id_produk+"&satuan_konversi="+satuan_konversi, function(data){
                        
                           if (data.potongan == 0) {

                          $("#potongan1").val('');                       
                                 
                          }else{

                          $("#potongan1").val(data.potongan);                       
                                 
                          };                   
                                 
                        });

            }

      });
    });
  });
</script>
<!-- cek stok satuan konversi keyup-->

<!-- cek stok satuan konversi change-->
<script type="text/javascript">
  $(document).ready(function(){
    $("#satuan_konversi").change(function(){
      var jumlah_barang = $("#jumlah_barang").val();
      var satuan_konversi = $("#satuan_konversi").val();
      var kode_barang = $("#kode_barang").val();
      var no_faktur = $("#no_faktur0").val();
      var id_produk = $("#id_produk").val();
      var prev = $("#satuan_produk").val();

      $.post("cek_stok_konversi_edit_penjualan.php",
        {jumlah_barang:jumlah_barang,satuan_konversi:satuan_konversi,kode_barang:kode_barang,id_produk:id_produk,no_faktur:no_faktur},function(data){

          if (data < 0) {
            alert("Jumlah Melebihi Stok");
            $("#jumlah_barang").val('');
          $("#satuan_konversi").val(prev);

          }else{

                        $.getJSON("cek_setting_diskon_jumlah.php?jumlah_barang="+jumlah_barang+"&kode_barang="+kode_barang+"&id_produk="+id_produk+"&satuan_konversi="+satuan_konversi, function(data){
                        
                           if (data.potongan == 0) {

                          $("#potongan1").val('');                       
                                 
                          }else{

                          $("#potongan1").val(data.potongan);                       
                                 
                          };                   
                                 
                        });

            }

      });
    });
  });
</script>
<!-- end cek stok satuan konversi change-->


<script>
$(document).ready(function(){
    $("#satuan_konversi").change(function(){

      var prev = $("#satuan_produk").val();
      var harga_lama = $("#harga_lama").val();
      var satuan_konversi = $("#satuan_konversi").val();
      var id_produk = $("#id_produk").val();
      var harga_produk = $("#harga_lama").val();
      var jumlah_barang = $("#jumlah_barang").val();
      var kode_barang = $("#kode_barang").val();
      

      $.getJSON("cek_konversi_penjualan.php",{kode_barang:kode_barang,satuan_konversi:satuan_konversi, id_produk:id_produk,harga_produk:harga_produk,jumlah_barang:jumlah_barang},function(info){

          if (satuan_konversi == prev) {
      
          $("#harga_produk").val(harga_lama);
          $("#harga_baru").val(harga_lama);
          $("#harga_konversi").val('0');

        }

        else{
          $("#harga_produk").val(harga_lama);
          $("#harga_baru").val(harga_lama);
          
                if (info.harga_pokok == 0) {
                
                $("#harga_konversi").val(info.harga_konversi);
                
                }else{
                
                $("#harga_konversi").val(info.harga_pokok);

                }
        }

      });

        
    });

});
</script>


<script>
//untuk menampilkan sisa penjualan secara otomatis
  $(document).ready(function(){

  $("#jumlah_barang").keyup(function(){
     var jumlah_barang = $("#jumlah_barang").val();
     var jumlahbarang = $("#jumlahbarang").val();
     var limit_stok = $("#limit_stok").val();
     var ber_stok = $("#ber_stok").val();
     var stok = jumlahbarang - jumlah_barang;


if (stok < 0 )

      {

       if (ber_stok == 'Jasa') 
       {
       
       }
       
       else{
       alert ("Jumlah Melebihi Stok!");
       $("#jumlah_barang").val('');
       }

    }

    else if( limit_stok > stok  ){

      alert ("Persediaan Barang Ini Sudah Mencapai Batas Limit Stok, Segera Lakukan Pembelian !");
    }
  });
})

</script>



<script>

//untuk menampilkan data yang diambil pada form tbs penjualan berdasarkan id=formtambahproduk
  $(document).on('click', '#submit_barcode', function (e) {

    var kode_barang = $("#kode_barcode").val();
    var level_harga = $("#level_harga").val();
    var sales = $("#sales").val();
    var no_faktur = $("#nomor_faktur_penjualan").val();

/// JAVASCRIPT BARCODE
$.post("barcode_edit.php",{kode_barang:kode_barang,sales:sales,level_harga:level_harga,no_faktur:no_faktur},function(data){

    if (data == 1){
  alert("Stok Tidak Mencukupi ,Segera Lakukan Pembelian");
  }
  else if (data == 3){
  alert("Kode Barang Yang Anda Masukan Tidak Ada , Silakan Periksa Kembali ");
  $("#kode_barcode").focus();
  }


else{



/// JAVASCRIPT MUNCULKAN ALERT PROMO (JIKA AADA PROMO DALAM BARANG ITU DARI INPUT BARCODE)
$.getJSON('lihat_nama_barang.php',{kode_barang:kode_barang}, function(json){
  console.log(json.id);

    $.post("lihat_promo_alert.php",{id_barang:json},function(data){

    if (data.promo != null)
    {
      $("#modal_promo_alert").modal('show');
      $("#tampil_alert").html(data.promo);
    } 
    });
});
///END  JAVASCRIPT ALERT PROMO

        $(".tr-kode-"+kode_barang+"").remove();

        $("#ppn").attr("disabled", true);
        $("#nama_barang").val('');
        $("#nama_barang").val('');
        $("#jumlah_barang").val('');
        $("#potongan1").val('');
        $("#kode_barcode").val('');
        $("#kode_barcode").focus();
//perhitungan form pembayaran (total & subtotal / biaya admin) 

 /*var biaya_adm = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#biaya_adm").val()))));
    if (biaya_adm == ''){
      biaya_adm = 0;
    }*/



var total_akhir1 = parseFloat(data);


  var pot_fakt_per = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#potongan_persen").val()))));
   if (pot_fakt_per == "" || pot_fakt_per == 0,00 || pot_fakt_rp == 0  ) {
        pot_fakt_per = 0.00;
      }

    var pot_fakt_rp = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#potongan_penjualan").val()))));
  if (pot_fakt_rp == "" || pot_fakt_rp == 0,00 || pot_fakt_rp == 0 ) {
        pot_fakt_rp = 0.00;
      }

           var tax_faktur = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#tax").val()))));


    if (pot_fakt_per == 0) {
      var potongaaan = pot_fakt_rp;

      var pot_fakt_per = parseFloat(potongaaan) / parseFloat(total_akhir1) * 100;



      if (pot_fakt_rp == 0.00 ){// prhitungan total Akhir      
             var total_akhier = parseFloat(total_akhir1);
        }
      else
        {
             var total_akhier = parseFloat(total_akhir1) /*+ parseFloat(biaya_adm.replace(',','.'))*/ - parseFloat(pot_fakt_rp.replace(',','.'));
        }// prhitungan total akhir



      if (tax_faktur != 0 ) { //Hitung pajak
        var hasil_tax = parseFloat(total_akhier) * parseFloat(tax_faktur) / 100;
      }
      else
      {
        var hasil_tax = 0;
    } //end hitung pajak
     
   
    var total_akhir = parseFloat(total_akhier) + parseFloat(Math.round(hasil_tax));


    }//if (pot_fakt_per == 0) {


    else if(pot_fakt_rp == 0.00){

      var potongaaan = pot_fakt_per;
      var pos = potongaaan.search("%");
      var potongan_persen = potongaaan;
       var   potongan_persen = potongan_persen.replace("%","");
     var potongaaan = parseFloat(total_akhir1) * parseFloat(potongan_persen.replace(',','.')) / 100;

        
      if (pot_fakt_rp == 0 ){ // prhitungan total akhir
          var total_akhier = parseFloat(total_akhir1);
      }
      else{
         var total_akhier = parseFloat(total_akhir1) /*+ parseFloat(biaya_adm.replace(',','.'))*/ - parseFloat(potongaaan);
      }// prhitungan total akhir


        if (tax_faktur != 0) {//Hitung pajak
        var hasil_tax = parseFloat(total_akhier) * parseFloat(tax_faktur) / 100;
        }
        else{
        var hasil_tax = 0;
        }//end hitung pajak

         var total_akhir = parseFloat(total_akhier) + parseFloat(Math.round(hasil_tax));

    }//else if(pot_fakt_rp == 0.00)

   else if(pot_fakt_rp != 0.00 && pot_fakt_per != 0.00){

      var potongaaan = pot_fakt_per;
      var pos = potongaaan.search("%");
      var potongan_persen = potongaaan;
      var   potongan_persen = potongan_persen.replace("%","");
      var potongaaan = parseFloat(total_akhir1) * parseFloat(potongan_persen.replace(',','.')) / 100;

        if (pot_fakt_rp == 0 ){
          var total_akhier = parseFloat(total_akhir1);
         }
        else{
         var total_akhier = parseFloat(total_akhir1) /*+ parseFloat(biaya_adm.replace(',','.'))*/ - parseFloat(potongaaan);
        }

        if (tax_faktur != 0) {//Hitung pajak
          var hasil_tax = parseFloat(total_akhier) * parseFloat(tax_faktur) / 100;
        }
        else{
          var hasil_tax = 0;
        }//end hitung pajak


    var total_akhir = parseFloat(total_akhier) + parseFloat(Math.round(hasil_tax));

    }//   else if(pot_fakt_rp != 0.00 && pot_fakt_per != 0.00){


//perhitungan form pembayaran (total & subtotal / biaya admin) 
// perhitungan rupiah total akhir
    $("#total1").val(total_akhir.format(2, 3, '.', ','));
    $("#total2").val(total_akhir1.format(2, 3, '.', ','));
// perhitungan rupiah total akhir
 if (pot_fakt_rp == 0.00)
      {
        $("#potongan_penjualan").val();
      }
      else
      {
        $("#potongan_penjualan").val(potongaaan.format(2, 3, '.', ','));
      }
      
      $("#tax_rp").val(hasil_tax.format(2, 3, '.', ','));
//perhitungan form pembayaran (total & subtotal / biaya admin) 

      }// end else untuk stok tidak mencukupi
  

               var tabel_tbs_penjualan = $('#tabel_tbs_penjualan').DataTable();//Pembaruan datatable baru 
                    tabel_tbs_penjualan.draw();
});
/// JAVASCRIPT BARCODE


$("#form_barcode").submit(function(){
    return false;
    });

});
 </script>  




<script>

   //untuk menampilkan data yang diambil pada form tbs penjualan berdasarkan id=formtambahproduk
  $(document).on('click', '#submit_produk', function (e) {

    var no_faktur = $("#nomor_faktur_penjualan").val();
    var kode_pelanggan = $("#kd_pelanggan").val();
    var kode_barang = $("#kode_barang").val();
    var nama_barang = $("#nama_barang").val();
    var jumlah_barang = $("#jumlah_barang").val();
    var harga_konversi = $("#harga_konversi").val();
    var harga_produk = $("#harga_produk").val();
    var satuan_konversi = $("#harga_konversi").val();
    var id_produk = $("#harga_produk").val();


    if (harga_konversi != 0) {
      var harga = harga_konversi;
    }else{
      var harga = harga_produk;
    };

    var harga_baru = $("harga_baru").val();
    var tax = $("#tax1").val();


  $.getJSON("cek_setting_diskon_jumlah.php?jumlah_barang="+jumlah_barang+"&kode_barang="+kode_barang+"&id_produk="+id_produk+"&satuan_konversi="+satuan_konversi, function(info){                                  

      if (info.potongan == 0) {

          var potongan = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#potongan1").val()))));
          
          }else{
          var potongan = info.potongan;
          }

    if (potongan == '')
    {
      potongan = 0;
    }
    var tax = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#tax1").val()))));
     if (tax == '')
    {
      tax = 0;
    }
    var jumlahbarang = $("#jumlahbarang").val();
    var satuan = $("#satuan_konversi").val();
    var sales = $("#sales").val();
    var a = $(".tr-kode-"+kode_barang+"").attr("data-kode-barang");
    var ber_stok = $("#ber_stok").val();
    var ppn = $("#ppn").val();
    var stok = parseFloat(jumlahbarang,10) - parseFloat(jumlah_barang,10);
    
    var subtotal = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#total2").val()))));
          if (subtotal == '') 
          {
          subtotal = 0;
          }

    var tax_faktur = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#tax").val()))));

    var pot_fakt_per = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#potongan_persen").val()))));
      if (pot_fakt_per == "" || pot_fakt_per == 0,00 || pot_fakt_rp == 0  ) 
      {
        pot_fakt_per = 0.00;
      }

    var pot_fakt_rp = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#potongan_penjualan").val()))));
    if (pot_fakt_rp == "" || pot_fakt_rp == 0,00 || pot_fakt_rp == 0 ) 
      {
        pot_fakt_rp = 0.00;
      }


  /* var biaya_adm = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#biaya_adm").val()))));
        if (biaya_adm == '')
        {
          biaya_adm = 0;
        }*/
 //PPN
  if (ppn == 'Exclude') {
  
         var total1 = parseFloat(jumlah_barang.replace(',','.'),2) * parseFloat(harga.replace(',','.'),2) - parseFloat(potongan.replace(',','.'),2);

         var total_tax_exclude = parseFloat(total1.replace(',','.'),2) * parseFloat(tax.replace(',','.'),2) / 100;

         
          var total = parseFloat(total1.replace(',','.'),2) + parseFloat(total_tax_exclude.replace(',','.'),2);

    }
    else{
        var total = parseFloat(jumlah_barang.replace(',','.'),2) * parseFloat(harga,2) - parseFloat(potongan,2);
    }
  //PPN
   

    var total_akhir1 = parseFloat(subtotal.replace(',','.'),2) + parseFloat(total,2);

 if (pot_fakt_per == 0.00) {

      var potongaaan = pot_fakt_rp;

      var pot_fakt_per = parseFloat(potongaaan,2) / parseFloat(total_akhir1,2) * 100;



//mendapatkan total akhir
      if ( pot_fakt_rp == 0.00) 
      {
          var total_akhier = parseFloat(total_akhir1,2);
      }
      else
      {
          var total_akhier = parseFloat(total_akhir1,2) /*+ parseFloat(biaya_adm.replace(',','.'),2)*/ - parseFloat(pot_fakt_rp.replace(',','.'),2);
      }
//mendapatkan total akhir


//Hitung pajak
    if (tax_faktur != 0 ) 
        {
          var hasil_tax = parseFloat(total_akhier.replace(',','.')) * parseFloat(tax_faktur.replace(',','.')) / 100;
        }
    else
        {
           var hasil_tax = 0;
        }
//end hitung pajak
    
    var total_akhir = parseFloat(total_akhier,2) + parseFloat(hasil_tax,2);


    }//end  if (pot_fakt_per == 0.00) 

else if(pot_fakt_rp == 0.00)
    {
      var potongaaan = pot_fakt_per;
      var pos = potongaaan.search("%");
      var potongan_persen = potongaaan;
      var   potongan_persen = potongan_persen.replace("%","");
      var potongaaan = parseFloat(total_akhir1.replace(',','.'),2) * parseFloat(potongan_persen.replace(',','.'),2) / 100;

      if (pot_fakt_rp == 0.00) 
      {
          var total_akhier = parseFloat(total_akhir1.replace(',','.'),2);
      }
      else
      {
          var total_akhier = parseFloat(total_akhir1.replace(',','.'),2) /*+ parseFloat(biaya_adm.replace(',','.'),2)*/ - parseFloat(potongaaan,2);
      }

//Hitung pajak
  if (tax_faktur != 0) 
    {
        var hasil_tax = parseFloat(total_akhier.replace(',','.'),2) * parseFloat(tax_faktur.replace(',','.'),2) / 100;
    }
 else
     {
        var hasil_tax = 0;
     }
//end hitung pajak
  
   var total_akhir = parseFloat(total_akhier,2) + parseFloat(hasil_tax,2);

    }// else if(pot_fakt_rp == 0.00)


else if(pot_fakt_rp != 0 && pot_fakt_per != 0)
    {
      var potongaaan = pot_fakt_per;
      var pos = potongaaan.search("%");
      var potongan_persen = potongaaan;
      var   potongan_persen = potongan_persen.replace("%","");
      var potongaaan = parseFloat(total_akhir1,2) * parseFloat(potongan_persen.replace(',','.'),2) / 100;

           if ( pot_fakt_rp == 0.00) 
        {
          var total_akhier = parseFloat(total_akhir1,2);
        }
        else
        {
          var total_akhier = parseFloat(total_akhir1,2) /* + parseFloat(biaya_adm.replace(',','.'),2)*/ - parseFloat(potongaaan,2);
        }

         //Hitung pajak
        if (tax_faktur != 0) {
        var hasil_tax = parseFloat(total_akhier.replace(',','.'),2) * parseFloat(tax_faktur.replace(',','.'),2) / 100;

        }
        else
        {
        var hasil_tax = 0;
        }
    //end hitung pajak

    var total_akhir = parseFloat(total_akhier,2) + parseFloat(hasil_tax,2);

    }//end else if(pot_fakt_rp != 0 && pot_fakt_per != 0)

        var pembayaran = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#pembayaran_penjualan").val()))));
        if(pembayaran == ''){
        pembayaran = 0;
        }
        var sisa = pembayaran - total_akhir1;
        var sisa_kredit = total_akhir1 - pembayaran;


      if (sisa < 0  ){
        $("#kredit").val(sisa_kredit);
        $("#sisa_pembayaran_penjualan").val('0');
        $("#tanggal_jt").attr("disabled", false);
      }
      else{
        $("#sisa_pembayaran_penjualan").val(sisa);
        $("#kredit").val('0');
        $("#tanggal_jt").attr("disabled", true);
      }
      

  if (jumlah_barang == ''){
  alert("Jumlah Barang Harus Diisi");
  }
  else if (kode_pelanggan == ''){
  alert("Kode Pelanggan Harus Dipilih");
  }
  else if (stok < 0 ){
  alert("Jumlah Barang Melebihi Stok");
  }

  
 else if (ber_stok == 'Jasa' ){

//pengambilan data form pembayaran
      $("#total1").val(total_akhir.format(2, 3, '.', ','));
      $("#total2").val(total_akhir1.format(2, 3, '.', ','));
 if (pot_fakt_rp == 0.00)
      {
        $("#potongan_penjualan").val();
      }
      else
      {
        $("#potongan_penjualan").val(potongaaan.format(2, 3, '.', ','));
      }
      $("#tax_rp").val(hasil_tax.format(2, 3, '.', ',')); //pengambilan data form pembayaran



    $(".tr-kode-"+kode_barang+"").remove();


 $.post("proses_tambah_edit_penjualan.php",{ppn:ppn,no_faktur:no_faktur,kode_barang:kode_barang,nama_barang:nama_barang,jumlah_barang:jumlah_barang,harga_konversi:harga_konversi,harga_produk:harga_produk,harga_baru:harga_baru,potongan:potongan,tax:tax,satuan:satuan,sales:sales},function(data){
     
     $("#kode_barang").focus();
      $("#ppn").attr("disabled", true);
     $("#tbody").prepend(data);
      $("#tabel_tbs_penjualan").show();

    $("#kode_barang").val('');
      $("#kode_barang").val('').trigger("chosen:updated");
      $("#kode_barang").trigger("chosen:open");

     $("#nama_barang").val('');
     $("#jumlah_barang").val('');
     $("#potongan1").val('');
     $("#tax1").val('');
     $("#pembayaran_penjualan").val('');
      $("#harga_konversi").val('');
    
//Pembaruan datatable baru 
     $('#tabel_tbs_penjualan').DataTable().destroy();

                        var dataTable = $('#tabel_tbs_penjualan').DataTable( {
                          "processing": true,
                          "serverSide": true,
                          "ajax":{
                            url :"data_tbs_edit_penjualan.php", // json datasource
                              "data": function ( d ) {
                                d.no_faktur = $("#nomor_faktur_penjualan").val();
                                // d.custom = $('#myInput').val();
                                // etc
                            },
                             
                              type: "post",  // method  , by default get
                            error: function(){  // error handling
                              $(".employee-grid-error").html("");
                              $("#tabel_tbs_penjualan").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                              $("#employee-grid_processing").css("display","none");
                              }
                          },
                             "fnCreatedRow": function( nRow, aData, iDataIndex ) {

                              $(nRow).attr('class','tr-id-'+aData[11]+'');         

                          }
                        });
//Pembaruan datatable baru 


     });

 }

  else if (stok < 0) {

    alert ("Jumlah Melebihi Stok Barang !");

  }

  else{

       //pengambilan data untuk form pembayaran
 if (pot_fakt_rp == 0.00)
      {
        $("#potongan_penjualan").val();
      }
      else
      {
        $("#potongan_penjualan").val(potongaaan.format(2, 3, '.', ','));
      }
      $("#total1").val(total_akhir.format(2, 3, '.', ','));
      $("#total2").val(total_akhir1.format(2, 3, '.', ','));
      $("#tax_rp").val(hasil_tax.format(2, 3, '.', ','));
      $("#kode_barang").focus();
//pengambilan data untuk form pembayaran


    $("#kode_barang").focus();

    $(".tr-kode-"+kode_barang+"").remove();

    $.post("proses_tambah_edit_penjualan.php",{ppn:ppn,no_faktur:no_faktur,kode_barang:kode_barang,nama_barang:nama_barang,jumlah_barang:jumlah_barang,harga_konversi:harga_konversi,harga_produk:harga_produk,harga_baru:harga_baru,potongan:potongan,tax:tax,satuan:satuan,sales:sales},function(data){
     
      $("#kode_barang").focus();
      $("#ppn").attr("disabled", true);
      $("#tbody").prepend(data);
      $("#kode_barang").val('');
      $("#kode_barang").val('').trigger("chosen:updated");
      $("#kode_barang").trigger("chosen:open");
      $("#nama_barang").val('');
      $("#jumlah_barang").val('');
      $("#potongan1").val('');
      $("#tax1").val('');
      $("#pembayaran_penjualan").val('');
      $("#harga_konversi").val('');
     

//Pembaruan datatable baru 
     $('#tabel_tbs_penjualan').DataTable().destroy();

                        var dataTable = $('#tabel_tbs_penjualan').DataTable( {
                          "processing": true,
                          "serverSide": true,
                          "ajax":{
                            url :"data_tbs_edit_penjualan.php", // json datasource
                              "data": function ( d ) {
                                d.no_faktur = $("#nomor_faktur_penjualan").val();
                                // d.custom = $('#myInput').val();
                                // etc
                            },
                             
                              type: "post",  // method  , by default get
                            error: function(){  // error handling
                              $(".employee-grid-error").html("");
                              $("#tabel_tbs_penjualan").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                              $("#employee-grid_processing").css("display","none");
                              }
                          },
                             "fnCreatedRow": function( nRow, aData, iDataIndex ) {

                              $(nRow).attr('class','tr-id-'+aData[11]+'');         

                          }
                        });
//Pembaruan datatable baru 
     
     });
}
    
   });    
});

  $("#formtambahproduk").submit(function(){
    return false;
});
</script>

<!--<script type="text/javascript">
//menampilkan no urut faktur setelah tombol click di pilih
      $("#cari_produk_penjualan").click(function() {

      $("#alert_berhasil").hide();
      
      var no_faktur = $("#no_faktur0").val();
      //coding update jumlah barang baru "rabu,(9-3-2016)"
      $.post('modal_edit_penjualan.php',{no_faktur:no_faktur},function(data) {
      
      $(".modal_baru").html(data);
      $("#cetak_tunai").hide('');
      $("#cetak_tunai_besar").hide('');
      $("#cetak_piutang").hide('');
      });
      /* Act on the event */
      });

</script>-->


<script>
  $("#penjualan").click(function(){

        var no_faktur = $("#nomor_faktur_penjualan").val();
        var sisa_pembayaran = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah( $("#sisa_pembayaran_penjualan").val() ))));
        var kredit = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah( $("#kredit").val() )))); 
        var kode_pelanggan = $("#kd_pelanggan").val();
        var tanggal_jt = $("#tanggal_jt").val();
        var real_id = $("#real_id").val();

        var total = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah( $("#total1").val() )))); 
        var potongan =  bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah( $("#potongan_penjualan").val() ))));

        var biaya_adm =  bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#biaya_adm").val()))));

        var potongan_persen = $("#potongan_persen").val();
        var tax = $("#tax_rp").val();
        var cara_bayar = $("#carabayar1").val();
        var pembayaran = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah( $("#pembayaran_penjualan").val() ))));
        var total_hpp = $("#total_hpp").val();
        var harga = $("#harga_produk").val();
        var sales = $("#sales").val();
        var tanggal = $("#tanggal").val();
        var kode_gudang = $("#kode_gudang").val();
        var keterangan = $("#keterangan").val();
        var jumlah_bayar_lama = $("#jumlah_bayar_lama").val();
        var ppn_input = $("#ppn_input").val();
        var ppn = $("#ppn").val();
        var total2 = $("#total2").val();
        var sisa = pembayaran - total;
        var sisa_kredit = total - pembayaran;

        var jumlah_kredit_baru = parseFloat(kredit,10) - parseFloat(jumlah_bayar_lama,10);
       var x = parseFloat(jumlah_bayar_lama,10) + parseFloat(pembayaran,10);
       $("#zxzx").val(x);

  

 
 if (sisa_pembayaran < 0)
 {

  alert("Jumlah Pembayaran Tidak Mencukupi");

 }

else if (sisa < 0) 
 {

alert("Silakan Bayar Piutang");
 }

 else if (kode_pelanggan == "") 
 {

alert("Kode Pelanggan Harus Di Isi");

 }
else if (pembayaran == "") 
 {

alert("Pembayaran Harus Di Isi");

  $("#pembayaran_penjualan").focus();

 }

 else if (jumlah_bayar_lama == 0)

 {



  $("#penjualan").hide();
  $("#piutang").hide();
  $("#transaksi_baru").show();  

 $.post("proses_bayar_edit_jual.php",{real_id:real_id,biaya_adm:biaya_adm,total2:total2,kode_gudang:kode_gudang,tanggal:tanggal,no_faktur:no_faktur,sisa_pembayaran:sisa_pembayaran,kredit:kredit,kode_pelanggan:kode_pelanggan,tanggal_jt:tanggal_jt,total:total,potongan:potongan,potongan_persen:potongan_persen,tax:tax,cara_bayar:cara_bayar,pembayaran:pembayaran,sisa:sisa,sisa_kredit:sisa_kredit,total_hpp:total_hpp,harga:harga,sales:sales,keterangan:keterangan,jumlah_kredit_baru:jumlah_kredit_baru,x:x,ppn_input:ppn_input},function(info) {

   $("#total1").val('');
     $("#pembayaran_penjualan").val('');
     $("#sisa_pembayaran_penjualan").val('');
     $("#kredit").val('');

     $("#kd_pelanggan").val('');
     
     $("#table-baru").load("tabel-edit-tbs-penjualan.php?no_faktur=<?php echo $nomor_faktur; ?>");
     $("#alert_berhasil").show();
     $("#pembayaran_penjualan").val('');
     $("#sisa_pembayaran_penjualan").val('');
     $("#kredit").val('');
     $("#potongan_penjualan").val('');
     $("#potongan_persen").val('');
     $("#kode_meja").val('');
     $("#cetak_tunai").show();
     $("#cetak_tunai_besar").show();
       

     $("#span_tbs").hide();
   });

  }


else{

    if (x > total) {

    var no_faktur = $(this).attr("data-faktur");

    $.post('alert_piutang_penjualan.php',{no_faktur:no_faktur},function(data){
    
    
    $("#modal_alert").modal('show');
    $("#modal-alert").html(data);

  });

  }

}



 $("form").submit(function(){
    return false;
});

  });

      
  </script>


  
     <script>
       //perintah javascript yang diambil dari form proses_bayar_beli.php dengan id=form_beli
       $("#piutang").click(function(){

        var no_faktur = $("#nomor_faktur_penjualan").val();
        var sisa_pembayaran = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah( $("#sisa_pembayaran_penjualan").val() ))));
        var kredit = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah( $("#kredit").val() )))); 
        var kode_pelanggan = $("#kd_pelanggan").val();
        var tanggal_jt = $("#tanggal_jt").val();
        var total = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah( $("#total1").val() )))); 
        var potongan =  bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah( $("#potongan_penjualan").val() ))));
        var biaya_adm =  bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#biaya_adm").val()))));
        var real_id = $("#real_id").val();

        var potongan_persen = $("#potongan_persen").val();
        var tax = $("#tax_rp").val();
        var cara_bayar = $("#carabayar1").val();
        var pembayaran = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah( $("#pembayaran_penjualan").val() ))));
        var total_hpp = $("#total_hpp").val();
        var harga = $("#harga_produk").val();
        var sales = $("#sales").val();
        var tanggal = $("#tanggal").val();
        var kode_gudang = $("#kode_gudang").val();
        var keterangan = $("#keterangan").val();
        var jumlah_bayar_lama = $("#jumlah_bayar_lama").val();
        var ppn_input = $("#ppn_input").val();
        var total2 = $("#total2").val();
        var sisa =  pembayaran - total;
        var sisa_kredit = total - pembayaran;

        var jumlah_kredit_baru = parseFloat(kredit,10) - parseFloat(jumlah_bayar_lama,10);
        var x = parseFloat(jumlah_bayar_lama,10) + parseFloat(pembayaran,10);
        $("#zxzx").val(x);
        



       
      if (sisa_pembayaran == "" )
      {

        alert ("Jika Ingin Piutang Isi Jumlah Pembayaran 0");
        $("#pembayaran_penjualan").focus();

      }

       else if (kode_pelanggan == "") 
       {
       
       alert("Kode Pelanggan Harus Di Isi");
       
       }
       else if (tanggal_jt == "")
       {

        alert ("Tanggal Jatuh Tempo Harus Di Isi");
        $("#tanggal_jt").focus();

       }


      else if (jumlah_bayar_lama == 0 || x <= total )
      {

        $("#penjualan").hide();
        $("#piutang").hide();
        $("#transaksi_baru").show(); 
        
        $.post("proses_bayar_edit_jual.php",{real_id:real_id,biaya_adm:biaya_adm,total2:total2,kode_gudang:kode_gudang,tanggal:tanggal,no_faktur:no_faktur,sisa_pembayaran:sisa_pembayaran,kredit:kredit,kode_pelanggan:kode_pelanggan,tanggal_jt:tanggal_jt,total:total,potongan:potongan,potongan_persen:potongan_persen,tax:tax,cara_bayar:cara_bayar,pembayaran:pembayaran,sisa:sisa,sisa_kredit:sisa_kredit,total_hpp:total_hpp,harga:harga,sales:sales,keterangan:keterangan,jumlah_kredit_baru:jumlah_kredit_baru,x:x,ppn_input:ppn_input},function(info) {
        
        $("#table-baru").html(info);
        $("#alert_berhasil").show();
        $("#pembayaran_penjualan").val('');
        $("#sisa_pembayaran_penjualan").val('');
        $("#kredit").val('');
        $("#potongan_penjualan").val('');
        $("#potongan_persen").val('');
        $("#tanggal_jt").val('');
        $("#cetak_piutang").show();
             $("#span_tbs").hide();

        $("#total1").val('');
       $("#pembayaran_penjualan").val('');
       $("#sisa_pembayaran_penjualan").val('');
       $("#kredit").val('');
       $("#tanggal_jt").val('');
        
        });


      }

      else
      {
             if (x > total)

             {
              var no_faktur = $(this).attr("data-faktur");
              
              $.post('alert_piutang_penjualan.php',{no_faktur:no_faktur},function(data){
              
              
              $("#modal_alert").modal('show');
              $("#modal-alert").html(data);
              
              });

            }
       

       
      }  

  });

 $("form").submit(function(){
       return false;
       });

  </script>   

<script type="text/javascript">
    $(document).ready(function(){

        var no_faktur = $("#nomor_faktur_penjualan").val();
        
        $.getJSON("cek_total_edit_penjualan.php",{no_faktur:no_faktur},function(data){
                
                var total_penjualannya = data;

                                $.getJSON("cek_total_bonus_promo_edit_penjualan.php",{no_faktur:no_faktur},function(total_promo){
                                  console.log(total_promo);
                                  console.log(total_penjualannya);
                                  var sub_promo = total_promo;
                                      if (sub_promo < 1) {
                                        var subtotal_promo = 0;
                                      }
                                      else{
                                        var subtotal_promo = sub_promo;
                                      }

                                      var biaya_adm = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#biaya_adm").val()))));
                                      if (biaya_adm == '' || biaya_adm == 0,00 || biaya_adm == 0)
                                      {
                                        biaya_adm = 0.00;
                                      }

                                      var potongan_penjualan = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#potongan_penjualan").val()))));
                                      if (potongan_penjualan == '' || potongan_penjualan == 0,00 || potongan_penjualan == 0)
                                      {
                                        potongan_penjualan = 0.00;
                                      }
                                      
                                      var subtotal_tampil = parseFloat(total_penjualannya) + parseFloat(subtotal_promo);
                                  //jika tbs ada maka
                                  if (data != 0) {
                                    $("#total2").val(tandaPemisahTitik(subtotal_tampil));
                                  $("#total1").val(tandaPemisahTitik(subtotal_tampil));
                                  }
                                  else{
                                    $("#total2").val('0');
                                    $("#total1").val('0');
                                  }
                                  //jika tbs bonus ada maka 
                                  if (total_promo < 1) {
                                    $("#tbs_bonus_penjualan").hide();
                                  }
                                  else{
                                    $("#tbs_bonus_penjualan").show();
                                  }
                                  // end if (tot == 0)
                                }); // end cek_total_bonus_promo

                                var subtotal = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#total2").val()))));
                                var total = parseFloat(subtotal.replace(',','.')) - parseFloat(potongan_penjualan);
                                $("#total1").val(total.format(2, 3, '.', ','));

        });// end $.getJSON("cek_total_edit_penjualan.php",
      });// end $(document).ready(function()
</script>




        <script>
        //SCRIPT KEYUP JUMLAH BAYARNYA
        $(document).ready(function(){
        $("#pembayaran_penjualan").keyup(function(){
        var pembayaran =  bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#pembayaran_penjualan").val()))));
        var total =   bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#total1").val()))));
        var sisa = parseFloat(pembayaran.replace(',','.')) - parseFloat(total.replace(',','.'));
        var sisa_kredit = parseFloat(total.replace(',','.')) - parseFloat(pembayaran.replace(',','.')); 


        if (sisa < 0 )
        {
        $("#kredit").val(sisa_kredit.format(2, 3, '.', ','));
        $("#sisa_pembayaran_penjualan").val('0');
        $("#tanggal_jt").attr("disabled", false);
        
        }
        
        else  
        {
        $("#sisa_pembayaran_penjualan").val(sisa.format(2, 3, '.', ','));
        $("#kredit").val('0');
        $("#tanggal_jt").attr("disabled", true);
        
        } 
        
        
        });
        
        
        });
        </script>






        <script type="text/javascript">

$(document).ready(function(){

    $("#kd_pelanggan").change(function(){
        var kode_pelanggan = $("#kd_pelanggan").val();
        
        var level_harga = $(".opt-pelanggan-"+kode_pelanggan+"").attr("data-level");
        
        
        
        if(kode_pelanggan == 'Umum')
        {
           $("#level_harga").val('Level 1');
        }
        else 
        {
           $("#level_harga").val(level_harga);
        
        }
        
        
    });
});

          
        </script>

<script type="text/javascript">
$(document).ready(function(){
// Key up untuk potongan persen
$("#potongan_persen").keyup(function(){

        var potongan_persen = $("#potongan_persen").val();

       if (potongan_persen == "") {
        potongan_persen = 0;
      }
        var total = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#total2").val()))));
        var potongan_penjualan = ((parseFloat(total.replace(',','.')) * parseFloat(potongan_persen)) / 100);
        var tax = $("#tax").val();

        /* var biaya_adm = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#biaya_adm").val()))));
        if (biaya_adm == '')
        {
          biaya_adm = 0.00;
        }*/

      var ambil_semula = parseFloat(total.replace(',','.')) /*+ parseFloat(biaya_adm.replace(',','.'));parseFloat(tax_rp)*/
        var sisa_potongan = parseFloat(total.replace(',','.')) - parseFloat(potongan_penjualan);


             /*var t_tax = ((parseFloat(sisa_potongan.replace(',','.')) * parseFloat(tax.replace(',','.'))) / 100);*/
             var hasil_akhir = parseFloat(sisa_potongan) /*+ parseFloat(biaya_adm.replace(',','.'));/*parseFloat(tax_rp)*/


         $("#total1").val(round(hasil_akhir.format(2, 3, '.', ',')));
        $("#potongan_penjualan").val(round(potongan_penjualan.format(2, 3, '.', ',')));
        
        if (potongan_persen > 100.00) {

          alert ("Potongan %, Tidak Boleh Lebih Dari 100%");
          $("#potongan_persen").val('');
          $("#potongan_penjualan").val('');
          $("#total1").val(ambil_semula.format(2, 3, '.', ','));
        }

      });


// Key Up untuk Potongan Rupiah
$("#potongan_penjualan").keyup(function(){

    var potongan_penjualan = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#potongan_penjualan").val()))));
    var total = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#total2").val()))));
    var potongan_persen = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#potongan_persen").val()))));

  if (potongan_penjualan == "") 
      {
        potongan_penjualan = 0,00;
      }


    if (potongan_penjualan == 0,00 || potongan_penjualan == "" )
    {
          var potongan_persen = 0.00;
    }
    else
    {
         var potongan_persen = ((parseFloat(potongan_penjualan.replace(',','.')) / parseFloat(total.replace(',','.'))) * 100);
    }

/*var biaya_adm = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#biaya_adm").val()))));
        if (biaya_adm == '')
        {
          biaya_adm = 0;
        }*/


   if(potongan_persen > 100.00)
      {
        alert("Potongan Tidak Dapat Lebih dari 100% !!");
         $("#potongan_persen").val('');
        $("#potongan_penjualan").val('');
      
     /* var tax = $("#tax").val();

        if (tax == "")
        {
          tax = 0;
        }
        var t_tax = ((parseFloat(total.replace(',','.')) * parseFloat(tax.replace(',','.'))) / 100);*/


           var hasil_akhir = parseFloat(total.replace(',','.')) /*+ parseFloat(biaya_adm.replace(',','.'));/*+ parseFloat(t_tax);*/
    

        $("#total1").val(hasil_akhir.format(2, 3, '.', ','));

      }
      else
      {


      
      $("#potongan_persen").val(potongan_persen.format(2, 3, '.', ','));

 if (potongan_penjualan == 0,00 || potongan_penjualan == "" ){

        var sisa_potongan = parseFloat(total.replace(',','.'));
       }
       else
      {
        var sisa_potongan = parseFloat(total.replace(',','.')) - parseFloat(potongan_penjualan.replace(',','.'));
      } 
             /* if (tax == 0)
              {
               var t_tax = 0;
              }
              
              var t_tax = ((parseFloat(sisa_potongan) * parseFloat(tax)) / 100);*/

             var hasil_akhir = parseFloat(sisa_potongan) /*+ parseFloat(biaya_adm.replace(',','.')); /*+ parseFloat(t_tax,10);*/

        
        $("#total1").val(hasil_akhir.format(2, 3, '.', ','));
        
}
      });
        
//TUTUP TIDAK MEMAKAI tax atau pajak DI PEMBAYARAN
/*$("#tax").keyup(function(){

        var potongan = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#potongan_penjualan").val() ))));
      if (potongan == "") {
        potongan = 0;
      }

        var potongan_persen = $("#potongan_persen").val();
               if (potongan_persen == "") {
        potongan_persen = 0;
      }
        
        var total = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#total2").val() ))));
       
              var cara_bayar = $("#carabayar1").val();
              var tax = $("#tax").val();

              var t_total = total - potongan;

              if (tax == "") {
                tax = 0;
              }
              else if (cara_bayar == "") {
                alert ("Kolom Cara Bayar Masih Kosong");
                 $("#tax").val('');
                 $("#potongan_penjualan").val('');
                 $("#potongan_persen").val('');
              }

                else if (tax > 100) {
                alert ('Jumlah Tax Tidak Boleh Lebih Dari 100%');
                 $("#tax").val('');
                 $("#tax_rp").val('');
                 $("#total1").val(tandaPemisahTitik(t_total));


              }
              
              else {


              var t_tax = ((parseFloat(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(t_total,10))))) * parseFloat(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(tax,10)))))) / 100);

              var total_akhir = parseFloat(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(t_total,10))))) + Math.round(parseFloat(t_tax,10));
              
              
            $("#tax_rp").val(parseFloat(t_tax));
            $("#total1").val(tandaPemisahTitik(total_akhir));
   }


        });*/

        });
        
        </script>


      <script type="text/javascript">
      
      $(".chosen").chosen({no_results_text: "Maaf, Data Tidak Ada!"});  
      
      </script>

<!-- KEMAREN SAMPAI EDIT PENJUALAN < DISINI -->


<!--<script type="text/javascript">
    $(document).ready(function(){
      
//fungsi hapus data 
$(document).on('click','.btn-hapus-tbs',function(e){

    
    var nama_barang = $(this).attr("data-barang");
    var id = $(this).attr("data-id");
     var kode_barang = $(this).attr("data-kode-barang");
    var subtotal_tbs = $(this).attr("data-subtotal");

      var total = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#total2").val()))));

      if (total == '') 
        {
          total = 0;
        };
      var total_akhir = parseFloat(total,10) - parseFloat(subtotal_tbs,10);

      $("#total1").val(tandaPemisahTitik(total_akhir));
      $("#total2").val(tandaPemisahTitik(total_akhir));


    $.post("hapus_edit_tbs_penjualan.php",{id:id,kode_barang:kode_barang},function(data){
    if (data == 'sukses') {


    $(".tr-id-"+id+"").remove();
    $("#pembayaran_penjualan").val('');
    
    }
    });

});
                  $('form').submit(function(){
              
              return false;
              });


    });
  
//end fungsi hapus data
</script>-->




<script type="text/javascript">
 
$(".btn-alert-hapus").click(function(){
     var no_faktur = $(this).attr("data-faktur");
    var kode_barang = $(this).attr("data-kode");

    $.post('alert_edit_penjualan.php',{no_faktur:no_faktur, kode_barang:kode_barang},function(data){
    
 
    $("#modal_alert").modal('show');
    $("#modal-alert").html(data); 

});

  });
</script>

 
<script type="text/javascript">

  $(document).ready(function(){
    $(document).on('click','.edit-jumlah-jual',function(e){
      var kode_barang = $(this).attr("data-kode-barang-input");
      var tipe_produk = $('#opt-produk-'+kode_barang).attr("tipe_barang");
      
      $("#tipe_produk").val(tipe_produk);
    });
  });

</script>
                           
                            <script type="text/javascript">
                                 
                                 
                                  $(document).on('dblclick','.edit-jumlah-jual',function(e){

                                    var id = $(this).attr("data-id");

                                    $("#text-jumlah-"+id+"").hide();

                                    $("#input-jumlah-"+id+"").attr("type", "text");

                                 });

                                     $(document).on('blur','.input_jumlah_jual',function(e){


                                    var id = $(this).attr("data-id");
                                    var jumlah_baru = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($(this).val()))));
                                   

                                    var kode_barang = $(this).attr("data-kode");
                                    var nama_satuan = $(this).attr("data-nama_satuan");
                                    var harga = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($(this).attr("data-harga")))));
                                    var jumlah_lama = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#text-jumlah-"+id+"").text()))));

                                    var satuan_konversi = $(this).attr("data-satuan");
                                    var ber_stok = $(this).attr("data-berstok");
                                    var no_faktur = $("#nomor_faktur_penjualan").val();
                                    var id_produk = $(this).attr("data-id_produk");
                                    var pembayaran =  bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#pembayaran_penjualan").val()))));
   

                                    var subtotal_lama = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#text-subtotal-"+id+"").text()))));

                                    var pot_fakt_rp = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#potongan_penjualan").val()))));
                                    if (pot_fakt_rp == "" || pot_fakt_rp == 0,00 ) {
                                    pot_fakt_rp = 0.00;
                                    }
                                     $.getJSON("setting_diskon_jumlah_editjual.php?no_faktur="+no_faktur+"&id="+id+"&jumlah_barang="+jumlah_baru+"&kode_barang="+kode_barang+"&id_produk="+id_produk+"&satuan_konversi="+satuan_konversi, function(data){                                  

                                    if (data.status == 0) {

                                    var potongan = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#text-potongan-"+id+"").text()))));
                                    var potongan = parseFloat(potongan.replace(',','.'),2);
                                    }else{

                                    var potongan = data.potongan;
                                    };  

                                    var tax_tbs = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#text-tax-"+id+"").text()))));
                                    var tax_fak = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#tax").val()))));

                                    if (tax_fak == '')
                                    {
                                      tax_fak = 0;
                                    }

                                    var tax = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#text-tax-"+id+"").text()))));
                                    var ppn = $("#ppn").val();
    //////////////////////PPN
              if (ppn == 'Exclude') {

                    var subtotal1 = parseFloat(harga.replace(',','.'),2) * parseFloat(jumlah_baru.replace(',','.'),2) - potongan;

                                    var subtotal_penjualan = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#total2").val()))));

                                    var subtotal_ex = parseFloat(subtotal_lama.replace(',','.'),2) - parseFloat(tax.replace(',','.'),2);

                                    var cari_tax = (parseFloat(tax.replace(',','.'),2) * 100) / parseFloat(subtotal_ex,2);


                                    var cari_tax1 = parseFloat(subtotal1,2) * parseFloat(cari_tax,2) / 100;

                                    var jumlah_tax = cari_tax1;

                                    var subtotal = parseFloat(subtotal1,2) + parseFloat(jumlah_tax,2);

                                     var subtotal_penjualan = parseFloat(subtotal_penjualan.replace(',','.'),2) - parseFloat(subtotal_lama.replace(',','.'),2) + subtotal;
                                    }
                                    else
                                    {

                                   var subtotal1 = parseFloat(harga.replace(',','.'),2) * parseFloat(jumlah_baru.replace(',','.'),2) - potongan;

                                    var subtotal_penjualan = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#total2").val()))));

                                    var cari_tax = parseFloat(subtotal_lama.replace(',','.'),2) - parseFloat(tax.replace(',','.'),2);
                                    var cari_tax1 = parseFloat(subtotal_lama.replace(',','.'),2) / parseFloat(cari_tax,2);

                                    var tax_ex = cari_tax1;

                                    var subtotal = subtotal1;
                                    var tax_ex1 = parseFloat(subtotal,2) / parseFloat(tax_ex,2);
                                    var tax_ex2 = parseFloat(subtotal,2) - parseFloat(tax_ex1,2);
                                    var jumlah_tax = tax_ex2;
                                    

                                    var subtotal_penjualan = parseFloat(subtotal_penjualan,2) - parseFloat(subtotal_lama.replace(',','.'),2) + parseFloat(subtotal,2);

                                    }
    ////////////////////////PPN
                                    var subtotal_penjualan = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#total2").val()))));
 
                                   


  
                                    var jumlah_kirim = jumlah_baru.replace(',','.');
                                    var harga_kirim = harga.replace(',','.');
                                    


                                    $.post("cek_stok_edit_penjualan.php",{jumlah_lama:jumlah_lama,kode_barang:kode_barang, jumlah_baru:jumlah_baru,satuan_konversi:satuan_konversi,no_faktur:no_faktur},function(data){

                                       if (data < 0) {

                                       alert ("Jumlah Yang Di Masukan Melebihi Stok !");

                                          if (nama_satuan == 'KG') {

                                          $("#input-jumlah-"+id+"").val(jumlah_lama.format(2, 3, '.', ','));
                                          $("#text-jumlah-"+id+"").text(jumlah_lama.format(2, 3, '.', ','));
                                          $("#text-jumlah-"+id+"").show();
                                          $("#input-jumlah-"+id+"").attr("type", "hidden");


                                          }else{

                                          $("#input-jumlah-"+id+"").val(jumlah_lama);
                                          $("#text-jumlah-"+id+"").text(jumlah_lama);
                                          $("#text-jumlah-"+id+"").show();
                                          $("#input-jumlah-"+id+"").attr("type", "hidden");

                                          };
                                     }

                                      else{

                                     $.post("update_pesanan_barang.php",{no_faktur:no_faktur,ppn:ppn,jumlah_lama:jumlah_lama,tax:tax,id:id,jumlah_baru:jumlah_kirim,kode_barang:kode_barang,potongan:potongan,harga:harga_kirim,jumlah_tax:jumlah_tax,subtotal:subtotal},function(info){
                        

                                    
                                    var subtotal_penjualan = parseFloat(info);

                                    var potongan_persen = $("#potongan_persen").val();
                                      if (potongan_persen == '' || potongan_persen == 0,00)
                                    {
                                      potongan_persen = 0;
                                    }

                                    if (potongan_persen == 0)
                                    {
                                      potongaaan = 0,00;
                                    }
                                    else
                                    {
                                      potongaaan = parseFloat(subtotal_penjualan,2) * parseFloat(potongan_persen.replace(',','.'),2) / 100;
                                    }

                                    
                                      if (pot_fakt_rp == 0.00)
                                      {
                                      $("#potongan_penjualan").val();
                                      }
                                      else
                                      {    
                                      $("#potongan_penjualan").val(potongaaan.format(2, 3, '.', ','));
                                      }

                                    
                                  
                                    var sub_total = parseFloat(subtotal_penjualan,2) - parseFloat(potongaaan,2);

                                    var tax_fakt = parseFloat(tax_fak.replace(',','.'),2) * parseFloat(sub_total,2) / 100;

                                    var pajak_faktur = tax_fakt;

                                    if (potongaaan == 0,00 )
                                    {
                                     var sub_akhir = (parseFloat(subtotal_penjualan,2));
                                    }
                                    else
                                    {
                                    var sub_akhir = parseFloat(subtotal_penjualan,2)  - parseFloat(potongaaan,2);
                                    }      

                                    var sisa = parseFloat(pembayaran.replace(',','.')) - sub_akhir;
                                    var sisa_kredit = sub_akhir - parseFloat(pembayaran.replace(',','.')); 

                                    
                                    if (sisa < 0 )
                                    {
                                    $("#kredit").val(sisa_kredit.format(2, 3, '.', ','));
                                    $("#sisa_pembayaran_penjualan").val('0');
                                    $("#tanggal_jt").attr("disabled", false);
                                    
                                    }
                                    
                                    else  
                                    {
                                    $("#sisa_pembayaran_penjualan").val(sisa.format(2, 3, '.', ','));
                                    $("#kredit").val('0');
                                    $("#tanggal_jt").attr("disabled", true);
                                    
                                    } 
                                    

                                    var tabel_tbs_penjualan = $('#tabel_tbs_penjualan').DataTable();
                                    tabel_tbs_penjualan.draw();
                                          $("#total2").val(subtotal_penjualan.format(2, 3, '.', ','));       
                                          $("#total1").val(sub_akhir.format(2, 3, '.', ','));    
                                          $("#tax_rp").val(pajak_faktur.format(2, 3, '.', ','));     

                                    });

                                   }

                                 });


       
                                  }); 

                                 });

                             </script>


<script type="text/javascript">
  
                                      $(".edit-jumlah-alert").dblclick(function(){

                                      var no_faktur = $(this).attr("data-faktur");
                                      var kode_barang = $(this).attr("data-kode");
                                      
                                      $.post('alert_edit_penjualan.php',{no_faktur:no_faktur, kode_barang:kode_barang},function(data){
                                      
                                        $("#modal_alert").modal('show');
                                        $("#modal-alert").html(data);
              
                                      });
                                    });
</script>

<script type="text/javascript">
    $(document).ready(function(){

var ppn_input = $("#ppn_input").val();

      if (ppn_input == "Include"){

      $("#tax").attr("disabled", true);
      $("#tax1").attr("disabled", false);
  }

  else if (ppn_input == "Exclude") {
    $("#tax1").attr("disabled", true);
      $("#tax").attr("disabled", false);
  }
  else{

    $("#tax1").attr("disabled", true);
      $("#tax").attr("disabled", true);
  }


// PPN KETIKA DI UBAH
    $("#ppn").change(function(){

    var ppn = $("#ppn").val();
    $("#ppn_input").val(ppn);

  if (ppn == "Include"){

      $("#tax").attr("disabled", true);
      $("#tax1").attr("disabled", false);
  }

  else if (ppn == "Exclude") {
    $("#tax1").attr("disabled", true);
      $("#tax").attr("disabled", false);
  }
  else{

    $("#tax1").attr("disabled", true);
      $("#tax").attr("disabled", true);
  }


  });
  });
</script>



<!--Start Ajax Modal Cari Barang-->
<script type="text/javascript" language="javascript" >
   $(document).ready(function() {
        var dataTable = $('#tabel_cari').DataTable( {
          "processing": true,
          "serverSide": true,
          "ajax":{
            url :"modal_edit_penjualan.php", // json datasource
            "data": function ( d ) {
                d.no_faktur = $("#nomor_faktur_penjualan").val();
                // d.custom = $('#myInput').val();
                // etc
            },
            type: "post",  // method  , by default get
            error: function(){  // error handling
              $(".employee-grid-error").html("");
              $("#tabel_cari").append('<tbody class="employee-grid-error"><tr><th colspan="3">Data Tidak Ditemukan.. !!</th></tr></tbody>');
              $("#employee-grid_processing").css("display","none");
              
            }
          },

          "fnCreatedRow": function( nRow, aData, iDataIndex ) {

             $(nRow).attr('class', "pilih");
              $(nRow).attr('data-kode', aData[0]);
              $(nRow).attr('nama-barang', aData[1]);
              $(nRow).attr('harga', aData[2]);
              $(nRow).attr('harga_level_2', aData[3]);
              $(nRow).attr('harga_level_3', aData[4]);
              $(nRow).attr('harga_level_4', aData[5]);
              $(nRow).attr('harga_level_5', aData[6]);
              $(nRow).attr('harga_level_6', aData[7]);
              $(nRow).attr('harga_level_7', aData[8]);
              $(nRow).attr('jumlah-barang', aData[9]);
              $(nRow).attr('satuan', aData[15]);
              $(nRow).attr('kategori', aData[11]);
              $(nRow).attr('status', aData[17]);
              $(nRow).attr('suplier', aData[12]);
              $(nRow).attr('limit_stok', aData[13]);
              $(nRow).attr('ber-stok', aData[14]);
              $(nRow).attr('tipe_barang', aData[16]);
              $(nRow).attr('id-barang', aData[18]);

          }

        });    
     
  });
 </script>
<!--Start Ajax Modal Cari-->


<script type="text/javascript">
// START script untuk pilih kode barang menggunakan chosen     
  $(document).ready(function(){
  $("#kode_barang").change(function(){

    var kode_barang = $(this).val();
    var nama_barang = $('#opt-produk-'+kode_barang).attr("nama-barang");
    var harga_jual = $('#opt-produk-'+kode_barang).attr("harga");
    var harga_jual2 = $('#opt-produk-'+kode_barang).attr('harga_jual_2');  
    var harga_jual3 = $('#opt-produk-'+kode_barang).attr('harga_jual_3');
    var harga_jual4 = $('#opt-produk-'+kode_barang).attr('harga_jual_4');
    var harga_jual5 = $('#opt-produk-'+kode_barang).attr('harga_jual_5');  
    var harga_jual6 = $('#opt-produk-'+kode_barang).attr('harga_jual_6');
    var harga_jual7 = $('#opt-produk-'+kode_barang).attr('harga_jual_7');
    var jumlah_barang = $('#opt-produk-'+kode_barang).attr("jumlah-barang");
    var satuan = $('#opt-produk-'+kode_barang).attr("satuan");
    var kategori = $('#opt-produk-'+kode_barang).attr("kategori");
    var status = $('#opt-produk-'+kode_barang).attr("status");
    var suplier = $('#opt-produk-'+kode_barang).attr("suplier");
    var limit_stok = $('#opt-produk-'+kode_barang).attr("limit_stok");
    var ber_stok = $('#opt-produk-'+kode_barang).attr("ber-stok");
    var tipe_produk = $('#opt-produk-'+kode_barang).attr("tipe_barang");
    var id_barang = $('#opt-produk-'+kode_barang).attr("id-barang");
    var level_harga = $("#level_harga").val();
    var no_faktur = $("#nomor_faktur_penjualan").val();

$.post("lihat_promo_alert.php",{id_barang:id_barang},function(data){

    if (data.promo != null)
    {
      $("#modal_promo_alert").modal('show');
      $("#tampil_alert").html(data.promo);
    } 

    console.log(data.promo);

});

   if (level_harga == "harga_1") {

        $('#harga_produk').val(harga_jual);
        $('#harga_baru').val(harga_jual);
        $('#harga_lama').val(harga_jual);
        $('#kolom_cek_harga').val('1');
        }
    else if (level_harga == "harga_2") {

        $('#harga_produk').val(harga_jual2);
        $('#harga_baru').val(harga_jual2);
        $('#harga_lama').val(harga_jual2);
        $('#kolom_cek_harga').val('1');
        }
    else if (level_harga == "harga_3") {

        $('#harga_produk').val(harga_jual3);
        $('#harga_baru').val(harga_jual3);
        $('#harga_lama').val(harga_jual3);
        $('#kolom_cek_harga').val('1');
        }
    else if (level_harga == "harga_4") {

        $('#harga_produk').val(harga_jual4);
        $('#harga_baru').val(harga_jual4);
        $('#harga_lama').val(harga_jual4);
        $('#kolom_cek_harga').val('1');
        }
    else if (level_harga == "harga_5") {

        $('#harga_produk').val(harga_jual5);
        $('#harga_baru').val(harga_jual5);
        $('#harga_lama').val(harga_jual5);
        $('#kolom_cek_harga').val('1');
        }
    else if (level_harga == "harga_6") {

        $('#harga_produk').val(harga_jual6);
        $('#harga_baru').val(harga_jual6);
        $('#harga_lama').val(harga_jual6);
        $('#kolom_cek_harga').val('1');
        }
    else if (level_harga == "harga_7") {

        $('#harga_produk').val(harga_jual7);
        $('#harga_baru').val(harga_jual7);
        $('#harga_lama').val(harga_jual7);
        $('#kolom_cek_harga').val('1');
        }



    $("#harga_konversi").val('0');
    $("#tipe_produk").val(tipe_produk);
    $("#kode_barang").val(kode_barang);
    $("#nama_barang").val(nama_barang);
    $("#jumlah_barang").val(jumlah_barang);
    $("#satuan_produk").val(satuan);
    $("#satuan_konversi").val(satuan);
    $("#limit_stok").val(limit_stok);
    $("#ber_stok").val(ber_stok);
    $("#id_produk").val(id_barang);

if (ber_stok == 'Barang') {

    $.post('ambil_jumlah_produk.php',{kode_barang:kode_barang}, function(data){
      if (data == "") {
        data = 0;
      }
      $("#jumlahbarang").val(data);
      $('#kolom_cek_harga').val('1');
    });

}

       $.getJSON("cek_data_satuan_konversi.php?kode_barang="+kode_barang, function(result){

                $(".span-satuan").remove();  
                //satuan dasar
                $("#option_satuan-"+result.id_satuan_dasar).remove();
                var option_barang = "<option class='span-satuan' id='option_satuan-"+result.id_satuan_dasar+"' value='"+result.id_satuan_dasar+"' data-nama='"+result.nama_satuan_dasar+"'>"+result.nama_satuan_dasar+"</option>"
                $("#satuan_konversi").show().append(option_barang);
                   
                //satuan konversi
                $.each(result.satuan, function(i, item) {//  $.each(result.satuan, 
                $("#option_satuan-"+result.satuan[i].id).remove();
                var option_barang = "<option class='span-satuan' id='option_satuan-"+result.satuan[i].id+"' value='"+result.satuan[i].id+"' data-nama='"+result.satuan[i].nama+"'>"+result.satuan[i].nama+"</option>"
                $("#satuan_konversi").show().append(option_barang);

           });//  $.each(result.satuan, 
                                              
       });


$.post('cek_kode_barang_tbs_edit_penjualan.php',{kode_barang:kode_barang,no_faktur:no_faktur}, function(data){
          
  if(data == 1){
          alert("Anda Tidak Bisa Menambahkan Barang Yang Sudah Ada, Silakan Edit atau Pilih Barang Yang Lain !");

          $("#kode_barang").chosen("destroy");
          $("#kode_barang").val('');
          $("#kode_barang").trigger('chosen:updated');
          $("#kode_barang").trigger('chosen:open');
          $("#nama_barang").val('');

          $(".chosen").chosen({no_results_text: "Maaf, Data Tidak Ada!",search_contains:false}); 
   }//penutup if     



  });

    

  });
  }); 
  // end script untuk pilih kode barang menggunakan chosen   
</script>

<script>
//Choosen Open select
$(document).ready(function(){
    $("#kode_barang").trigger('chosen:open');

});
</script>


 <script type="text/javascript">
    $(document).ready(function(){
      
//fungsi hapus data TBS PENJUALAN
$(document).on('click','.btn-hapus-tbs',function(e){

      var no_faktur = $(this).attr("data-faktur");
      var nama_barang = $(this).attr("data-barang");
      var id = $(this).attr("data-id");
      var kode_barang = $(this).attr("data-kode-barang");
      var subtotal = $(this).attr("data-subtotal");
      if (subtotal == '') {
      subtotal = 0;
    };

     var tax_faktur = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#tax").val()))));
     if (tax_faktur == '') {
      tax_faktur = 0;
    };

/*    var biaya_adm = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#biaya_adm").val()))));
    if (biaya_adm == '') {
      biaya_adm = 0;
    };*/

    var subtotal_tbs = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#total2").val()))));
    
    var pot_fakt_per = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#potongan_persen").val()))));
   if (pot_fakt_per == "" || pot_fakt_per == 0,00 || pot_fakt_rp == 0  ) {
        pot_fakt_per = 0.00;
      }

    var pot_fakt_rp = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#potongan_penjualan").val()))));
 if (pot_fakt_rp == "" || pot_fakt_rp == 0,00 || pot_fakt_rp == 0 ) {
        pot_fakt_rp = 0.00;
      }



  

// start hapus ajax TBS PENJUALAN
var pesan_alert = confirm("Apakah Anda Yakin Ingin Menghapus "+nama_barang+""+ "?");
if (pesan_alert == true) {

    $.post("hapustbs_penjualan.php",{no_faktur:no_faktur,id:id,kode_barang:kode_barang},function(data){
     var total_akhir1 = parseFloat(data);

   if (pot_fakt_per == 0.00) {

      var potongaaan = pot_fakt_rp;

      var potongaaan_per = parseFloat(potongaaan,2) / parseFloat(total_akhir1,2) * 100;
      var potongaaan = pot_fakt_rp;
      var hitung_tax = parseFloat(total_akhir1,2) - parseFloat(pot_fakt_rp,2);

      /*var tax_bener = parseFloat(hitung_tax) * parseFloat(tax_faktur) / 100;*/

    if (pot_fakt_rp == 0.00 ){
      var total_akhir = parseFloat(total_akhir1,2)/* + parseFloat(tax_bener)*/;
    }
    else{
      var total_akhir = parseFloat(total_akhir1,2) /* + parseFloat(biaya_adm.replace(',','.'),2)*/ - parseFloat(pot_fakt_rp.replace(',','.'),2)/* + parseFloat(tax_bener)*/;
    }

    }
    else if(pot_fakt_rp == 0.00)
    {
      var potongaaan = pot_fakt_per;
      var pos = potongaaan.search("%");
      var potongan_persen = potongaaan;
          potongan_persen = potongan_persen.replace("%","");

      potongaaan = parseFloat(total_akhir1,2) * parseFloat(potongan_persen.replace(',','.'),2) / 100;
      
      var potongaaan_per = pot_fakt_per;
      var hitung_tax = parseFloat(total_akhir1,2) - parseFloat(potongaaan,2);

      /*var tax_bener = parseFloat(hitung_tax) * parseFloat(tax_faktur) / 100;*/
    if (pot_fakt_rp == 0.00 ){
        var total_akhir = parseFloat(total_akhir1,2) /*+ parseFloat(Math.round(tax_bener))*/;
      }
    else{
     var total_akhir = parseFloat(total_akhir1,2) /* + parseFloat(biaya_adm.replace(',','.'),2)*/ - parseFloat(potongaaan,2) /*+ parseFloat(Math.round(tax_bener))*/;
        } 
    }
     else if(pot_fakt_rp != 0.00 && pot_fakt_rp != 0.00)
    {
      var potongaaan = pot_fakt_per;
      var pos = potongaaan.search("%");
      var potongan_persen = potongaaan;
          potongan_persen = potongan_persen.replace("%","");
      potongaaan = parseFloat(total_akhir1,2) * parseFloat(potongan_persen.replace(',','.'),2) / 100;
      
      var potongaaan_per = pot_fakt_per;
      var hitung_tax = parseFloat(total_akhir1,2) - parseFloat(potongaaan,2);

      /*var tax_bener = parseFloat(hitung_tax) * parseFloat(tax_faktur) / 100;*/
      if ( pot_fakt_rp == 0 ){
         var total_akhir = parseFloat(total_akhir1,2) /*+ parseFloat(Math.round(tax_bener))*/;
        }
      else{
         var total_akhir = parseFloat(total_akhir1,2) /* + parseFloat(biaya_adm.replace(',','.'),2)*/ - parseFloat(potongaaan,2) /*+ parseFloat(Math.round(tax_bener))*/;
        }
    
    }

        if (total_akhir1 != 0) {
        $("#total2").val(total_akhir1.format(2, 3, '.', ','));  
        $("#total1").val(total_akhir.format(2, 3, '.', ','));
      }else{

        $("#total2").val('0');  
        $("#total1").val('0');
      }

        


      var pembayaran = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#pembayaran_penjualan").val()))));
        if(pembayaran == ''){
        pembayaran = 0;
        }
        var sisa = pembayaran - total_akhir1;
        var sisa_kredit = total_akhir1 - pembayaran;


      if (sisa < 0  ){
        $("#kredit").val(sisa_kredit);
        $("#sisa_pembayaran_penjualan").val('0');
        $("#tanggal_jt").attr("disabled", false);
      }
      else{
        $("#sisa_pembayaran_penjualan").val(sisa);
        $("#kredit").val('0');
        $("#tanggal_jt").attr("disabled", true);
      }        if (pot_fakt_rp == 0.00){
        $("#potongan_penjualan").val();
        }
      else{
        $("#potongan_penjualan").val(potongaaan.format(2, 3, '.', ','));
      }
        

        $("#kode_barang").val('');
        $("#kode_barang").val('').trigger("chosen:updated");
        $("#kode_barang").trigger("chosen:open");


          var tabel_tbs_penjualan = $('#tabel_tbs_penjualan').DataTable();
          tabel_tbs_penjualan.draw();
            if (total_akhir1 == 0) {
              
              $("#potongan_persen").val('0');
              $("#ppn").val('Non');
              $("#ppn").attr('disabled',false);
              $("#tax1").attr("disabled", true);

            }
            else{
              $("#potongan_persen").val(potongaaan_per);
            }
            /*
            $("#tax_rp").val(Math.round(tax_bener));*/
            $("#kode_barang").trigger('chosen:open');    


        });
}
else {
    
    }
//end hapus ajax

});
                  $('form').submit(function(){
              
              return false;
              });


    });
  
//end fungsi hapus data
</script>

<script type="text/javascript">
   $(document).on('ready', function (e) {                
// START DATATABLE AJAX START TBS PENJUALAN
      $('#tabel_tbs_penjualan').DataTable().destroy();
            var dataTable = $('#tabel_tbs_penjualan').DataTable( {
            "processing": true,
            "serverSide": true,
            "info":     false,
            "language": { "emptyTable":     "My Custom Message On Empty Table" },
            "ajax":{
              url :"data_tbs_edit_penjualan.php", // json datasource
             "data": function ( d ) {
                d.no_faktur = $("#nomor_faktur_penjualan").val();
                  // d.custom = $('#myInput').val();
                  },
                  type: "post",  // method  , by default get
              error: function(){  // error handling
                $(".tbody").html("");
                $("#tabel_tbs_penjualan").append('<tbody class="tbody"><tr><th colspan="3"></th></tr></tbody>');
                $("#tableuser_processing").css("display","none");
                
              }
            }   

      });
        
        

// END DATATABLE AJAX END DATATABLE AJAX TBS PENJUALAN
});
 </script>

<!--START CEK PPN-->
<script type="text/javascript">
    $(document).ready(function(){

    // cek ppn exclude 
    var no_faktur = $("#no_faktur0").val();
    $.get("cek_ppn_ex_simpan.php",{no_faktur:no_faktur},function(data){
      if (data == 1) {
      $("#ppn").val('Exclude');
     $("#ppn").attr("disabled", true);
     $("#tax1").attr("disabled", false);
      }
      else if(data == 2){

      $("#ppn").val('Include');
     $("#ppn").attr("disabled", true);
       $("#tax1").attr("disabled", false);
      }
      else
      {

     $("#ppn").val('Non');
     $("#tax1").attr("disabled", true);

      }

    });


    $("#ppn").change(function(){

    var ppn = $("#ppn").val();
    $("#ppn_input").val(ppn);

  if (ppn == "Include"){

      $("#tax1").attr("disabled", false);

  }

  else if (ppn == "Exclude") {
    $("#tax1").attr("disabled", false);
  }
  else{

    $("#tax1").attr("disabled", true);
  }


  });
  });
</script>
<!--ENDING CEK PPN-->

<script type="text/javascript">
  $(document).ready(function(){
      $(document).on('click','#tombol-warning',function(){
        $(".span-chosen").chosen("destroy");
        $("#tombol-warning").hide();
        $("#tombol-purpel").show();
        $("#span-colapse").show();
        $(".chosen").chosen({no_results_text: "Maaf, Data Tidak Ada!",search_contains:true});   
      });
  });    
</script>

<script type="text/javascript">
  $(document).ready(function(){
      $(document).on('click','#tombol-purpel',function(){
        $(".span-chosen").chosen("destroy");
        $("#tombol-warning").show();
        $("#tombol-purpel").hide();
        $("#span-colapse").hide();
        $(".chosen").chosen({no_results_text: "Maaf, Data Tidak Ada!",search_contains:true});    
      });
  });    
</script>

<!-- memasukan file footer.php -->
<?php include 'footer.php'; ?>