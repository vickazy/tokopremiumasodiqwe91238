<?php include 'session_login.php';


    //memasukkan file session login, header, navbar, db
    include 'header.php';
    include 'navbar.php';
    include 'db.php';
    include 'sanitasi.php';


    $session_id = session_id();

$query_default_ppn = $db->query("SELECT setting_ppn, nilai_ppn FROM perusahaan");
$data_default_ppn = mysqli_fetch_array($query_default_ppn);
$default_ppn = $data_default_ppn['setting_ppn'];
$nilai_ppn = $data_default_ppn['nilai_ppn'];


$query_setting_antrian_order = $db->query("SELECT setting_tampil FROM setting_antrian");
$data_setting_antrian_order = mysqli_fetch_array($query_setting_antrian_order);

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
    $( "#tanggal_jt" ).datepicker({dateFormat: "yy-mm-dd"});
    });
</script>

<!-- js untuk tombol shortcut -->
 <script src="shortcut.js"></script>
<!-- js untuk tombol shortcut -->

<!--membuat tampilan form agar terlihat rapih dalam satu tempat-->
<div style="padding-left: 5%; padding-right: 2%">

<h3> FORM PEMBELIAN </h3><hr>

 <div class="row">
   <div class="col-sm-8">


    <form enctype="multipart/form-data" role="form" action="formpembelian.php" method="post ">

        <input type="hidden" name="session_id" id="session_id" class="form-control" readonly="" value="<?php echo $session_id; ?>" required="" >

        <div class="row">

            <div class="col-sm-3">
              <label> Suplier (Alt+P) </label><br>
                <select name="suplier" id="nama_suplier" class="form-control chosen" required="" data-placeholder="SILAKAN PILIH...">
                 <option value="">--SILAHKAN PILIH--</option>
                  <?php
                    // menampilkan seluruh data yang ada pada tabel suplier
                    $query = $db->query("SELECT id,nama FROM suplier");
                    // menyimpan data sementara yang ada pada $query
                    while($data = mysqli_fetch_array($query))
                    {
                      echo "<option value='".$data['id'] ."'>".$data['nama'] ."</option>";
                    }
                  ?>
                </select>
            </div>

            <div class="col-sm-2">
              <label> Gudang </label><br>
                <select name="kode_gudang" id="kode_gudang" class="form-control chosen" required="" data-placeholder="SILAKAN PILIH...">
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

<div class="col-sm-2">
<label class="gg">PPN</label>
<select type="hidden" style="font-size:15px; height:35px" name="ppn" id="ppn" class="form-control chosen span-chosen">
  <?php if ($default_ppn == 'Include'): ?>
    <option selected>Include</option>
    <option>Exclude</option>
    <option>Non</option>
  <?php endif ?>

  <?php if ($default_ppn == 'Exclude'): ?>
    <option selected>Exclude</option>
    <option>Non</option>
    <option>Include</option>
  <?php endif ?>

  <?php if ($default_ppn == 'Non'): ?>
    <option selected>Non</option>
    <option>Include</option>
    <option>Exclude</option>
  <?php endif ?>
</select>
</div>
        </div> <!-- END ROW KOLOM SUPLIER -->

    </form> <!-- tag penutup form -->


<!--BAGIAN MODAL --> <!--BAGIAN MODAL --> <!--BAGIAN MODAL --> <!--BAGIAN MODAL --> <!--BAGIAN MODAL -->

<!-- MODAL PENCARIAN PRODUK  -->
    <div id="myModal" class="modal" role="dialog">
      <div class="modal-dialog">
        <!-- Isi Modal-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Data Barang</h4>
          </div>
          <div class="modal-body">
              <span class="modal_baru">
                <div class="table-resposive">
                  <center>
                    <table id="tabel_cari" class="table table-bordered table-sm">
                          <thead> <!-- untuk memberikan nama pada kolom tabel -->

                              <th> Kode Barang </th>
                              <th> Nama Barang </th>
                              <th> Harga Beli </th>
                              <th> Jumlah Barang </th>
                              <th> Satuan </th>
                              <th> Kategori </th>
                              <th> Suplier </th>

                          </thead> <!-- tag penutup tabel -->
                    </table>
                  </center>
                </div>
              </span>
          </div>
          <!-- tag pembuka modal footer -->
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div> <!--tag penutup moal footer -->
          </div>

      </div>
    </div>
<!-- END MODAL PENCARIAN PRODUK  -->


<!--MULAI MODAL ORDER -->
<div id="modal_order" class="modal" role="dialog">
  <div class="modal-dialog modal-lg">
    <!-- isi modal-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Data Order Pembelian</h4>
      </div>
      <div class="modal-body">
      <div class="table-resposive">
        <table id="table_order" align="center" class="table table-bordered table-sm">
            <thead>
              <th> Faktur Order  </th>
              <th> Suplier</th>
              <th> Tanggal </th>
              <th> Jam </th>
              <th> Total </th>
              <th> Keterangan </th>
            </thead>
        </table>
    </div>
  </div> <!-- tag penutup modal-body-->
      <div class="modal-footer">
        <button type="button" order="" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div><!-- END MODAL ORDER -->


<!-- MODAL HAPUS DATA PRODUK -->
    <div id="modal_hapus" class="modal" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Konfirmsi Hapus Data Tbs Pembelian</h4>
          </div>
          <div class="modal-body">
            <p>Apakah Anda yakin Ingin Menghapus Data ini ?</p>
            <form >
              <div class="form-group">
               <input type="text" id="nama-barang" class="form-control" readonly="">
               <input type="hidden" id="id_hapus" class="form-control" >
              </div>
            </form>

            <div class="alert alert-success" style="display:none">
             <strong>Berhasil!</strong> Data berhasil Di Hapus
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-info" id="btn_jadi_hapus"><span class='glyphicon glyphicon-ok-sign'> </span>Ya </button>
            <button type="button" class="btn btn-warning" data-dismiss="modal"><span class='glyphicon glyphicon-remove-sign'> </span> Batal </button>
          </div>
        </div>
      </div>
    </div>
<!-- END MODAL HAPUS DATA PRODUK  -->

<!-- MODAL EDIT DATA PRODUK -->
    <div id="modal_edit" class="modal" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Edit Data Pembelian Barang</h4>
          </div>
          <div class="modal-body">
            <form role="form">
             <div class="form-group">
               <label for="email">Jumlah Baru:</label>
               <input type="text" class="form-control" autocomplete="off" id="barang_edit"><br>
               <label for="email">Jumlah Lama:</label>
               <input type="text" class="form-control" id="barang_lama" readonly="">
               <input type="hidden" class="form-control" id="harga_edit" readonly="">
               <input type="hidden" class="form-control" id="kode_edit">
               <input type="hidden" class="form-control" id="potongan_edit" readonly="">
               <input type="hidden" class="form-control" id="tax_edit" readonly="">
               <input type="hidden" class="form-control" id="id_edit">
             </div>

             <button type="submit" id="submit_edit" class="btn btn-default">Submit</button>
            </form>

            <span id="alert"> </span>

            <div class="alert-edit alert-success" style="display:none">
             <strong>Berhasil!</strong> Data berhasil Di Edit
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
<!-- END EDIT DATA PRODUK   -->

<!--BAGIAN MODAL --> <!--BAGIAN MODAL --> <!--BAGIAN MODAL --> <!--BAGIAN MODAL --> <!--BAGIAN MODAL -->



  <br>
  <form id="form_barcode" class="form-inline">
    <div class="form-group">
        <input type="text" style="height:15px" name="kode_barcode" id="kode_barcode" class="form-control" autocomplete="off" placeholder="Scan / Ketik Barcode">
    </div>
        <button type="submit" id="submit_barcode" class="btn btn-primary" style="font-size:15px" ><i class="fa fa-barcode"></i> Submit Barcode</button> ||  
        <button type="button" class="btn btn-info" id="cari_produk_pembelian" accesskey="s" data-toggle="modal" data-target="#myModal"><i class='fa fa-search'> </i> Cari Produk (F1)</button>

        <?php 

        if ($data_setting_antrian_order['setting_tampil'] == 'Tampil')
        {
        ?>
        <button type="button" id="daftar_order" class="btn btn-purple" data-toggle="modal"><i class='fa  fa-search'></i> Cari Order (F6) </button>  

        <?php } ?>
  </form>


  <!--START FORM id="formtambahproduk" -->
  <form class="form-group" role="form" id="formtambahproduk">

    <div class="row"><!--ROW id="formtambahproduk" -->

      <!--BUTTON TAMPIL MODAL PRODUK-->

      <div class="col-sm-3"><br>
        <select style="font-size:15px; height:30px" type="text" name="kode_barang" id="kode_barang" class="form-control chosen" data-placeholder="SILAKAN PILIH...">
          <option value="">SILAKAN PILIH...</option>
           <?php
            include 'cache.class.php';
              $c = new Cache();
              $c->setCache('produk');
              $data_c = $c->retrieveAll();

              foreach ($data_c as $key) {
                echo '<option id="opt-produk-'.$key['kode_barang'].'" value="'.$key['kode_barang'].'" data-kode="'.$key['kode_barang'].'" nama-barang="'.$key['nama_barang'].'" harga="'.$key['harga_beli'].'"  harga_jual="'.$key['harga_jual'].'"  satuan="'.$key['satuan'].'" kategori="'.$key['kategori'].'" status="'.$key['status'].'" suplier="'.$key['suplier'].'" limit_stok="'.$key['limit_stok'].'" over_stok="'.$key['over_stok'].'" ber-stok="'.$key['berkaitan_dgn_stok'].'" tipe_barang="'.$key['tipe_barang'].'" id-barang="'.$key['id'].'" > '. $key['kode_barang'].' ( '.$key['nama_barang'].' ) </option>';
              }
            ?>
        </select>
      </div>


        <input style="height:25px;font-size:15px" type="hidden" class="form-control" name="nama_barang" id="nama_barang" placeholder="Nama Barang" readonly="">

      <div class="col-sm-1">
        <input  style="height:20px" type="text" class="form-control" accesskey="j" name="jumlah_barang" id="jumlah_barang" autocomplete="off" placeholder="Qty">
      </div>

      <div class="col-sm-2" style="width:90px">
        <select type="text" name="satuan_konversi" id="satuan_konversi" class="form-control"  required="" style="font-size:13px;">
         
        </select>
      </div>

      <div class="col-sm-2" style="width:90px">
        <input  style="height:20px" type="text" id="harga_baru" name="harga_baru" class="form-control" placeholder="Harga">
      </div>

      <div class="col-sm-2" style="width:90px">
        <input  style="height:20px" type="text" class="form-control" name="potongan" autocomplete="off" id="potongan1" data-toggle="tooltip" data-placement="top" title="Jika Ingin Potongan Dalam Bentuk Persen (%), input : 10%" placeholder="Disc." >
      </div>

  <div class="col-sm-1">
    <?php if ($default_ppn == 'Include'): ?>
      <input style="height:15px;" type="text" class="form-control" name="tax" autocomplete="off" id="tax1" value="<?php echo $nilai_ppn ?>" placeholder="Tax%" >
    <?php else: ?>
      <input style="height:15px;" type="text" class="form-control" name="tax" autocomplete="off" id="tax1" placeholder="Tax%" >
    <?php endif ?>
  </div>

      <div class="col-sm-3">
        <button type="submit" id="submit_produk" class="btn btn-success"><i class='fa fa-plus'></i> Tambah (F3) </button>
        <br><br>
      </div>

<!--BAGIAN HIDDEN --> <!--BAGIAN HIDDEN --> <!--BAGIAN HIDDEN --> <!--BAGIAN HIDDEN --> <!--BAGIAN HIDDEN -->
        <input type="hidden" class="form-control"  name="over_stok" id="over_stok" autocomplete="off" placeholder="Over Stok">
        <input type="hidden" id="harga_produk" name="harga" class="form-control" placeholder="Harga Lama" required="">
        <input type="hidden" id="harga_lama" name="harga_lama" class="form-control" required="">
        <input type="hidden" class="form-control" name="jumlahbarang" id="jumlahbarang">
        <input type="hidden" id="satuan_produk" name="satuan" class="form-control" value="" required="">
        <input type="hidden" id="id_produk" name="id_produk" class="form-control" value="" required="">
        <input type="hidden" name="session_id" id="session_id" class="form-control" value="<?php echo $session_id; ?>" required="" >
<!--BAGIAN HIDDEN --> <!--BAGIAN HIDDEN --> <!--BAGIAN HIDDEN --> <!--BAGIAN HIDDEN --> <!--BAGIAN HIDDEN -->

<!-- UNTUK PERINGTAN AJIKA HARGA BELI YANG DIUBAH LEBI BESAR DARI HARGA JUAL -->
<input type="hidden" id="harga_jual" name="harga_jual" class="form-control" placeholder="Harga Jual" required="">

    </div><!--END ROW id="formtambahproduk" -->

  </form>
<!--END FORM id="formtambahproduk" -->

<!--TABEl TBS PEMBELIAN AJAX -->
    <span id="span_tbs">
      <div class="table-responsive">
        <table id="tabel_tbs_pembelian" class="table table-bordered table-sm">
          <thead> <!-- untuk memberikan nama pada kolom tabel -->

            <th> Kode Barang </th>
            <th> Nama Barang </th>
            <th> Jumlah Barang </th>
            <th> Satuan </th>
            <th> Harga </th>
            <th> Potongan </th>
            <th> Tax </th>
            <th> Subtotal </th>
            <th> Hapus </th>

          </thead> <!-- tag penutup tabel -->
        </table>
      </div>
    </span>
<!--TABEl TBS PEMBELIAN AJAX -->
                <h6 style="text-align: left ; color: red"><i> * Klik 2x pada kolom Jumlah Barang jika ingin mengedit.</i></h6>
                <h6 style="text-align: left ;"><i><b> * Short Key (F2) untuk mencari Kode Produk atau Nama Produk.</b></i></h6>
        <?php 

        if ($data_setting_antrian_order['setting_tampil'] == 'Tampil')
        {
        ?>

<button class="btn btn-warning" id="btnOrder" type="button" data-toggle="collapse" aria-expanded="false" aria-controls="collapseExample"><i class='fa fa-list-ol'> </i>
Order Pembelian</button>

<button class="btn btn-purple" id="btnOrderClose" type="button" data-toggle="collapse" aria-expanded="false" aria-controls="collapseExample" style="display: none"><i class='fa fa-list-ol'> </i>
Order Pembelian</button>

        
        <?php } ?>

<span id="OrderPembelian" style="display: none">
    <div class="row">
      <div class="col-sm-3">
        <span id="select_order">

          <select style="font-size:15px; height:35px" name="hapus_order" id="hapus_order" class="form-control" required="" >
            <?php 
              $query = $db->query("SELECT no_faktur_order FROM tbs_pembelian WHERE session_id = '$session_id' AND no_faktur_order IS NOT NULL GROUP BY no_faktur_order");

              while($data = mysqli_fetch_array($query)){
               echo "<option value='".$data['no_faktur_order'] ."'>".$data['no_faktur_order'] ."</option>";
              }
            ?>
          </select>
          
          <input type="hidden" class="form-control" name="total_perorder" id="total_perorder" placeholder="Total /Order">
        </span>
      </div>
      <div class="col-sm-4">
        <button type="submit" id="btn-hps-order" class="btn btn-danger" style="font-size:15px"> <i class="fa fa-trash"></i> Hapus </button>
      </div>
    </div>

    <div class="card card-block">
        <div class="table-responsive"> <!--tag untuk membuat garis pada tabel-->
            <table id="table_tbs_order" class="table table-bordered table-sm" align="center">
                <thead>
                      <th> Faktur Order  </th>
                      <th> Kode  </th>
                      <th> Nama </th>
                      <th> Jumlah </th>
                      <th> Satuan </th>
                      <th> Harga </th>
                      <th> Potongan </th>
                      <th> Pajak </th>
                      <th> Subtotal </th>
                </thead>
            </table>
        </div>  
    </div>
</span>


</div> <!--col-sm-8-->



<form action="proses_bayar_beli.php" id="form_beli" method="POST"><!--tag pembuka form-->


<style type="text/css">
  .disabled {
    opacity: 0.6;
    cursor: not-allowed;
    disabled: false;
}
</style>

<div class="col-sm-4">

  <div class="card card-block" style="width:80%; ">

      <div class="row">
        <div class="col-sm-6">
          <b><label> Subtotal</label><br>
              <input type="text" name="total" id="total_pembelian1" class="form-control" placeholder="" readonly="" style="font-size: 20px; height: 15px" ></b>
        </div>
        <div class="col-sm-6">

        </div>
      </div>

      <div class="row">
        <div class="col-sm-6">
          <label> Potongan ( Rp ) </label><br>
            <input type="text" name="potongan" id="potongan_pembelian" data-diskon="" style="height:15px;font-size:15px" class="form-control" autocomplete="off" placeholder=" ">
        </div>
        <div class="col-sm-6">
          <label> Potongan ( % ) </label><br>
            <input type="text" name="potongan_persen" id="potongan_persen" style="height:15px;font-size:15px" class="form-control" autocomplete="off" placeholder="">
        </div>
        <div class="col-sm-4" style="display: none">
          <label> Tax ( % )</label><br>
            <input type="text" name="tax" id="tax" class="form-control" style="height:15px;font-size:15px" autocomplete="off" data-pajak="" placeholder="" >
        </div>
      </div>

      <div class="row">
        <div class="col-sm-6">
          <label> Tanggal Jatuh Tempo </label><br>
            <input type="text" name="tanggal_jt" id="tanggal_jt" placeholder="" style="height:25px;font-size:15px" value="" class="form-control" >
        </div>

        <div class="col-sm-6">
          <label> Cara Bayar (F4) </label><br>
            <b><select type="text" name="cara_bayar" id="carabayar1" class="form-control chosen"  required="" style="font-size: 16px" ></b>
            <b><option value=""> Silahkan Pilih </option></b>
              <?php

                 $sett_akun = $db->query("SELECT sa.kas, da.nama_daftar_akun FROM setting_akun sa INNER JOIN daftar_akun da ON sa.kas = da.kode_daftar_akun");
                         $data_sett = mysqli_fetch_array($sett_akun);

                         echo "<option selected value='".$data_sett['kas']."'>".$data_sett['nama_daftar_akun'] ."</option>";

                         $query = $db->query("SELECT nama_daftar_akun, kode_daftar_akun FROM daftar_akun WHERE tipe_akun = 'Kas & Bank'");
                         while($data = mysqli_fetch_array($query))
                         {

                         echo "<option value='".$data['kode_daftar_akun']."'>".$data['nama_daftar_akun'] ."</option>";

                         }

                //Untuk Memutuskan Koneksi Ke Database
                mysqli_close($db);


              ?>

            </select>
          </div>
        </div>

        <div class="row">
          <div class="col-sm-6">
            <b><label> Total Akhir</label><br>
              <input type="text" name="total" id="total_pembelian" class="form-control" placeholder="" readonly="" style="font-size: 20px; height: 15px" ></b>
          </div>
          <div class="col-sm-6">
            <b><label> Pembayaran (F7) </label><br>
              <input type="text" name="pembayaran" id="pembayaran_pembelian" autocomplete="off" class="form-control" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);"  style="font-size: 20px; height: 15px"></b>
          </div>
        </div>

        <div class="row">
          <div class="col-sm-6">
            <label> Kembalian </label><br>
              <b><input type="text" name="sisa_pembayaran" id="sisa_pembayaran_pembelian" style="height:15px;font-size:15px" class="form-control" placeholder=" " readonly="" style="font-size: 20px"></b>
          </div>
          <div class="col-sm-6">
            <label> Kredit </label><br>
              <b><input type="text" name="kredit" id="kredit" class="form-control" style="height:15px;font-size:15px" placeholder="" readonly=""></b>
          </div>
        </div>

  </div>


<!-- BAGIAN FILE HIDDEN --> <!-- BAGIAN FILE HIDDEN --> <!-- BAGIAN FILE HIDDEN --> <!-- BAGIAN FILE HIDDEN -->
        <input type="hidden" name="jumlah" id="jumlah1" class="form-control" placeholder="">
        <input type="hidden" name="ppn_input" id="ppn_input" value="Include" class="form-control" placeholder="ppn input">
        <input type="hidden" name="session_id" class="form-control" value="<?php echo $session_id; ?>" required="" >
        <input value="Umum" type="hidden" name="suplier" id="supplier" class="form-control" required="" >
<!-- BAGIAN FILE HIDDEN --> <!-- BAGIAN FILE HIDDEN --> <!-- BAGIAN FILE HIDDEN --> <!-- BAGIAN FILE HIDDEN -->

<!-- BAGIAN TOMBOL --> <!-- BAGIAN TOMBOL --> <!-- BAGIAN TOMBOL --> <!-- BAGIAN TOMBOL -->
        <button type="submit" id="pembayaran" class="btn btn-info"></i>Bayar (F8)</button>
        <button type="button" id="transaksi_baru" style="display: none" class="btn btn-info"><i class="fa fa-refresh"></i>Transaksi Baru </button>

        <button type="submit" id="hutang" class="btn btn-warning">Hutang (F9)</button>
        <!--membuaat link pada tombol batal-->
        <button type="button" id="batal" class="btn btn-danger">Batal (F10)</button>

        <a href="cetak_pembelian_tunai.php" id="cetak_tunai" style="display: none;" class="btn btn-success" target="blank"><i class="fa fa-print"> </i> Cetak Tunai </a>

        <a href="cetak_pembelian_hutang.php" id="cetak_hutang" style="display: none;" class="btn btn-success" target="blank"> <i class="  fa fa-print"> </i> Cetak Hutang </a>

<!-- BAGIAN TOMBOL --> <!-- BAGIAN TOMBOL --> <!-- BAGIAN TOMBOL -->  <!-- BAGIAN TOMBOL -->


  </form><!--tag penutup form-->

  <div class="alert alert-success" id="alert_berhasil" style="display:none">
  <strong>Success!</strong> Pembayaran Berhasil
  </div>


</div><!--END COL_SM_4-->

</div> <!--END ROW-->

</div><!-- END CONTAINER -->



<!-- untuk memasukan perintah javascript -->
<script type="text/javascript">

// jika dipilih, nim akan masuk ke input dan modal di tutup
  $(document).on('click', '.pilih', function (e) {
  document.getElementById("kode_barang").value = $(this).attr('data-kode');
  $("#kode_barang").trigger('chosen:updated');
  document.getElementById("nama_barang").value = $(this).attr('nama-barang');
  document.getElementById("over_stok").value = $(this).attr('over_stok');
  document.getElementById("satuan_produk").value = $(this).attr('satuan');
  document.getElementById("satuan_konversi").value = $(this).attr('satuan');
  document.getElementById("harga_produk").value = $(this).attr('harga');
  document.getElementById("harga_lama").value = $(this).attr('harga');
  document.getElementById("id_produk").value = $(this).attr('id-barang');
  document.getElementById("harga_baru").value = $(this).attr('harga');
  document.getElementById("jumlahbarang").value = $(this).attr('jumlah-barang');


  //JSON SATUAN PRODUK
  var kode_barang = $("#kode_barang").val();

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
  //AKHIR JSON SATUAN PRODUK



  $('#myModal').modal('hide');
  $("#jumlah_barang").focus();
  });



</script> <!--tag penutup perintah java script-->

<script type="text/javascript" language="javascript" >
  $(document).ready(function() {
    $(document).on('click', '#daftar_order', function (e) {

      var session_id = $("#session_id").val();

      $.post('cek_order_pembelian.php',{session_id:session_id}, function(data){
    
        if(data == 1){

            alert("Anda Sudah Melakukan Order Pembelian, Silakan Lihat Daftar Order Pembelian !");

            var table_tbs_order = $('#table_tbs_order').DataTable();
                table_tbs_order.draw();

            $('#table_tbs_order').DataTable().destroy();
            var dataTable = $('#table_tbs_order').DataTable( {
                    "processing": true,
                    "serverSide": true,
                    "ajax":{
                      url :"datatable_tbs_order_pembelian.php", // json datasource
                     
                      type: "post",  // method  , by default get
                      error: function(){  // error handling
                        $(".employee-grid-error").html("");
                        $("#table_tbs_order").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                        $("#employee-grid_processing").css("display","none");
                      }
                  },      
              });

              $("#OrderPembelian").show();
              $("#btnOrder").hide();
              $("#btnOrderClose").show();

         }
         else{

            $('#table_order').DataTable().destroy();

                var dataTable = $('#table_order').DataTable( {
                "processing": true,
                "serverSide": true,
                "ajax":{
                  url :"datatable_daftar_order_pembelian.php", // json datasource
                  "data": function ( d ) {
                        d.suplier = $("#nama_suplier").val();
                        // d.custom = $('#myInput').val();
                        // etc
                      },                 
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
                    $(nRow).attr('data-gudang', aData[6]);
                    $(nRow).attr('data-suplier', aData[7]);
                    $(nRow).attr('data-id', aData[8]);

                },
              });

              $("#modal_order").modal('show');
         }

      });

        $("#form").submit(function(){
        return false;
        });  

    });
  });
</script>


<!--/DATA TABLE MENGGUNAKAN AJAX-->
<script type="text/javascript">
  $(document).on('click', '.pilih_order', function (e) {  

          document.getElementById("nama_suplier").value = $(this).attr('data-suplier');
          $('#nama_suplier').prop('disabled', true).trigger("chosen:updated");

          document.getElementById("kode_gudang").value = $(this).attr('data-gudang');
          $('#kode_gudang').prop('disabled', true).trigger("chosen:updated");

          $.post("ambil_order_pembelian.php",{no_faktur_order:$(this).attr('data-order')},function(data){

            $("#modal_order").modal('hide');

            $.get("ambil_select_order_pembelian.php",function(data){
              $("#select_order").html(data);
            });

          });//END ambil_order_pembelian.php

          var total_perorder = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($(this).attr('data-total')))));
          if (total_perorder == '' ){
             total_perorder = 0;
          };

          var subtotal = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#total_pembelian1").val()))));
          if (subtotal == ''){
            subtotal = 0;
          };

          var total_akhir1 = parseInt(subtotal) + parseInt(total_perorder);

          var pot_fakt_per = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#potongan_persen").val()))));
          if (pot_fakt_per == "" || pot_fakt_per == 0 ){
              pot_fakt_per = 0;
          };

          var pot_fakt_rp = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#potongan_pembelian").val()))));
          if (pot_fakt_rp == ""  || pot_fakt_rp == 0 ){
              pot_fakt_rp = 0;
          }

          var tax_faktur = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#tax").val()))));

          if (pot_fakt_per == 0){
            
              var potongaaan = pot_fakt_rp;
              var pot_fakt_per = parseInt(potongaaan) / parseInt(total_akhir1) * 100;

              if (pot_fakt_rp == 0 ){   
              var total_akhier = parseInt(total_akhir1);
              }
              else{
              var total_akhier = parseInt(total_akhir1) - parseInt(pot_fakt_rp);
              }
              
              //Hitung pajak
              if (tax_faktur != 0 ) {
              var hasil_tax = parseInt(total_akhier) * parseInt(tax_faktur) / 100;
              
              }
              else
              {
              var hasil_tax = 0;
              }
              //end hitung pajak
              
              var total_akhir = parseInt(total_akhier) + parseInt(Math.round(hasil_tax));


          }//END pot_fakt_per == 0

          else if(pot_fakt_rp == 0){

            var potongaaan = pot_fakt_per;
              var pos = potongaaan.search("%");
              var potongan_persen = potongaaan;
              var   potongan_persen = potongan_persen.replace("%","");
              var potongaaan = parseInt(total_akhir1) * parseInt(potongan_persen.replace(',','.')) / 100;
              
              if (pot_fakt_rp == 0 ){  
              var total_akhier = parseInt(total_akhir1);
              }
              else{
              var total_akhier = parseInt(total_akhir1) /*+ parseInt(biaya_adm.replace(',','.'))*/ - parseInt(potongaaan); 
              }
              
              //Hitung pajak
              if (tax_faktur != 0) {
              var hasil_tax = parseInt(total_akhier) * parseInt(tax_faktur) / 100;        
              }
              else{
              var hasil_tax = 0;
              }
              //end hitung pajak
              
              var total_akhir = parseInt(total_akhier) + parseInt(Math.round(hasil_tax));

          }//END pot_fakt_rp == 0

          else if(pot_fakt_rp != 0 && pot_fakt_per != 0){

              var potongaaan = pot_fakt_per;
              var pos = potongaaan.search("%");
              var potongan_persen = potongaaan;
              var potongan_persen = potongan_persen.replace("%","");
              var potongaaan = parseInt(total_akhir1) * parseInt(potongan_persen.replace(',','.')) / 100;
              
              if ( pot_fakt_rp == 0 ){      
              var total_akhier = parseInt(total_akhir1);
              }
              else{
              var total_akhier = parseInt(total_akhir1) - parseInt(potongaaan); 
              }
              
              //Hitung pajak
              if (tax_faktur != 0){
              var hasil_tax = parseInt(total_akhier) * parseInt(tax_faktur) / 100;
              }
              else{
              var hasil_tax = 0;
              }
              //end hitung pajak
              var total_akhir = parseInt(total_akhier) + parseInt(Math.round(hasil_tax));

  

          }//pot_fakt_rp != 0 && pot_fakt_per != 0
        

            $("#tax_rp").val(hasil_tax);
            $("#total_pembelian1").val(tandaPemisahTitik(total_akhir1));
            $("#total_pembelian").val(tandaPemisahTitik(total_akhir));

            if (pot_fakt_rp == 0){
                $("#potongan_pembelian").val();
              }
            else{    
                $("#potongan_pembelian").val(potongaaan);
              }

    $('#table_tbs_order').DataTable().destroy();
          var dataTable = $('#table_tbs_order').DataTable( {
          "processing": true,
          "serverSide": true,
          "ajax":{
            url :"datatable_tbs_order_pembelian.php", // json datasource
           
            type: "post",  // method  , by default get
            error: function(){  // error handling
              $(".employee-grid-error").html("");
              $("#table_tbs_order").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
              $("#employee-grid_processing").css("display","none");
            }
        },      
    });

    $("#OrderPembelian").show();
    $("#btnOrder").hide();
    $("#btnOrderClose").show();

});
</script>
<!--end javascript order all-->

<!-- SCRIPT AJAX -->
<script>
  $(document).ready(function(){
      $("#nama_suplier").trigger('chosen:open');
  });
</script>

<script type="text/javascript">
  $(document).ready(function() {
    var dataTable = $('#tabel_tbs_pembelian').DataTable( {
      "processing": true,
      "serverSide": true,
      "ajax":{
        url :"data_tbs_pembelian.php", // json datasource
        "data": function ( d ) {
          d.session_id = $("#session_id").val();
          // d.custom = $('#myInput').val();
          // etc
        },

         type: "post",  // method  , by default get
         error: function(){  // error handling
           $(".employee-grid-error").html("");
           $("#tabel_tbs_pembelian").append('<tbody class="employee-grid-error"><tr><th colspan="3">Data Tidak Ditemukan.. !!</th></tr></tbody>');
           $("#employee-grid_processing").css("display","none");
           }
      },
        "fnCreatedRow": function( nRow, aData, iDataIndex ) {
           $(nRow).attr('class','tr-id-'+aData[11]+'');
         }
    });
  });
</script>

<script type="text/javascript" language="javascript" >
   $(document).ready(function() {

        var dataTable = $('#tabel_cari').DataTable( {
          "processing": true,
          "serverSide": true,
          "ajax":{
            url :"modal_beli_baru.php", // json datasource
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
              $(nRow).attr('over_stok', aData[8]);
              $(nRow).attr('satuan', aData[7]);
              $(nRow).attr('harga', aData[2]);
              $(nRow).attr('id-barang', aData[9]);
              $(nRow).attr('jumlah-barang', aData[3]);
              $(nRow).attr('kategori', aData[5]);
              $(nRow).attr('suplier', aData[6]);

            }

        });


  });

 </script>

<!-- SCRIPT AJAX -->

<script>
//SCRIPT BARCODE INPUT
$(document).on('click', '#submit_barcode', function (e) {
  var kode_barang = $("#kode_barcode").val();
  var suplier = $("#nama_suplier").val();

  if (suplier == ''){
  alert("Suplier Harus Dipilih");
  $("#nama_suplier").trigger('chosen:open');
  }
  else{

  // JAVASCRIPT BARCODE
  $.post("barcode_pembelian.php",{kode_barang:kode_barang},function(data){
    if (data == 3){
      alert("Barcode Yang Anda Masukan Tidak Ada , Silakan Periksa Kembali ");
      $("#kode_barcode").val('');
      $("#kode_barcode").focus();
    }
    else{

                    $(".tr-kode-"+kode_barang+"").remove();
                    $("#ppn").attr("disabled", true).trigger('chosen:updated');
                    $("#nama_barang").val('');
                    $("#jumlah_barang").val('');
                    $("#potongan1").val('');
                    $("#kode_barcode").val('');

            //perhitungan form pembayaran (total & subtotal / biaya admin) 

            var total_perorder = data;

                if (total_perorder == '') {
                  total_perorder = 0;
                }

            var pot_fakt_per = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#potongan_persen").val()))));
            var pot_fakt_rp = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#potongan_pembelian").val()))));
            var subtotal = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#total_pembelian1").val()))));

            if (subtotal == ''){
                subtotal = 0;
              }

            var total_akhir1 = parseInt(subtotal,10) + parseInt(total_perorder,10);

              if (pot_fakt_per == '0%') {
                  var potongaaan = pot_fakt_rp;
                  var potongaaan = parseInt(potongaaan,10) / parseInt(total_akhir1,10) * 100;
                  var pot_pers = parseInt(pot_fakt_rp,10) / parseInt(total_akhir1,10) * 100;
                  var total_akhir = parseInt(total_akhir1,10) - parseInt(pot_fakt_rp,10);
                }

              else if(pot_fakt_rp == 0){
                  var potongaaan = pot_fakt_per;
                  var pos = potongaaan.search("%");
                  var potongan_persen = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(potongaaan))));
                      potongan_persen = potongan_persen.replace("%","");
                      potongaaan = total_akhir1 * potongan_persen / 100;

                 var total_akhir = parseInt(total_akhir1,10) - parseInt(Math.round(potongaaan,10));
                 var pot_pers = parseInt(potongaaan,10) / parseInt(total_akhir1,10) * 100;
              }

              else if(pot_fakt_rp != 0 && pot_fakt_rp != 0){
                 var potongaaan = pot_fakt_per;
                 var pos = potongaaan.search("%");
                 var potongan_persen = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(potongaaan))));
                     potongan_persen = potongan_persen.replace("%","");
                    
                    if(potongan_persen != 0 ) {
                      potongaaan = total_akhir1 * potongan_persen / 100;
                    }
                    else {
                      potongaaan = 0;
                    }

                var total_akhir = parseInt(total_akhir1,10) - parseInt(Math.round(potongaaan,10));
                var pot_pers = parseInt(potongaaan,10) / parseInt(total_akhir1,10) * 100;
              }

        $("#potongan_pembelian").val(Math.round(potongaaan,10));
        $("#potongan_persen").val(Math.round(pot_pers));
        $("#total_pembelian").val(tandaPemisahTitik(Math.round(total_akhir)));
        $("#total_pembelian1").val(tandaPemisahTitik(total_akhir1));
        
              //AWAL PERHITUNGAN PEMBAYARAN 
                  var pembayaran = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#pembayaran_pembelian").val()))));
                  var sisa = pembayaran - total_akhir;
                  var sisa_kredit = total_akhir - pembayaran;

                  if (sisa < 0  ){

                     $("#kredit").val(sisa_kredit);
                     $("#sisa_pembayaran_pembelian").val('0');
                     $("#tanggal_jt").attr("disabled", false);
                  }
                  else{

                    $("#sisa_pembayaran_pembelian").val(sisa);
                    $("#kredit").val('0');
                    $("#tanggal_jt").attr("disabled", true);
                  }
              //AKHIR PERHITUNGAN PEMBAYARAN
                  
            // datatable ajax pembaruan
                var tabel_tbs_pembelian = $('#tabel_tbs_pembelian').DataTable();
                    tabel_tbs_pembelian.draw();
    
    }// end else untuk stok tidak mencukupi

  });
  }

  /// JAVASCRIPT BARCODE


$("#form_barcode").submit(function(){
    return false;
    
    });
});
 </script> 


<script>
   //perintah javascript yang diambil dari form tbs pembelian dengan id=form tambah produk


   $("#submit_produk").click(function(){

    var session_id = $("#session_id").val();
    var suplier = $("#nama_suplier").val();
    var kode_barang = $("#kode_barang").val();
    var nama_barang = $("#nama_barang").val();
    var jumlah_barang = $("#jumlah_barang").val();
    var harga = $("#harga_produk").val();
    var harga_baru = $("#harga_baru").val();
    if (harga == harga_baru) {
      harga = harga;

    }
    else{       
      harga = harga_baru;
    }
    var harga_jual = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#harga_jual").val()))));

    var potongan = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#potongan1").val()))));
    var jumlahbarang = $("#jumlahbarang").val();
    var satuan = $("#satuan_konversi").val();
    var kode_gudang = $("#kode_gudang").val();
    var ppn = $("#ppn").val();
    var subtotal = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#total_pembelian1").val()))));
    var tax = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#tax1").val()))));
    var pot_fakt_per = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#potongan_persen").val()))));
    var pot_fakt_rp = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#potongan_pembelian").val()))));
        if (tax == '') {
      tax = 0;
    };


    if (subtotal == '')
    {
    subtotal = 0;
    }

        if (potongan == '')
          {
             potongan = 0;
          }

        else
          {
            var pos = potongan.search("%");
           if (pos > 0)
            {
               var potongan_persen = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#potongan1").val()))));
               potongan_persen = potongan_persen.replace("%","");
               potongan = jumlah_barang * harga * potongan_persen / 100 ;
            };
          }

var subtotal_murni = parseInt(jumlah_barang) * parseInt(harga_baru);


    if (ppn == 'Exclude') {
         var total1 = parseInt(jumlah_barang,10) * parseInt(harga,10) - parseInt(potongan,10);
         var total_tax_exclude = parseInt(total1,10) * parseInt(tax,10) / 100;
         var total = parseInt(total1,10) + parseInt(Math.round(total_tax_exclude,10));
    }
    else
    {
        var total = parseInt(jumlah_barang,10) * parseInt(harga,10) - parseInt(potongan,10);
    }

    var total_akhir1 = parseInt(subtotal,10) + parseInt(total,10);


  if (pot_fakt_per == '0%') {
      var potongaaan = pot_fakt_rp;
      var potongaaan = parseInt(potongaaan,10) / parseInt(total_akhir1,10) * 100;
      var pot_pers = parseInt(pot_fakt_rp,10) / parseInt(total_akhir1,10) * 100;
      var total_akhir = parseInt(total_akhir1,10) - parseInt(pot_fakt_rp,10);
    }

  else if(pot_fakt_rp == 0){
      var potongaaan = pot_fakt_per;
      var pos = potongaaan.search("%");
      var potongan_persen = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(potongaaan))));
          potongan_persen = potongan_persen.replace("%","");
          potongaaan = total_akhir1 * potongan_persen / 100;

     var total_akhir = parseInt(total_akhir1,10) - parseInt(Math.round(potongaaan,10));
     var pot_pers = parseInt(potongaaan,10) / parseInt(total_akhir1,10) * 100;
    }

  else if(pot_fakt_rp != 0 && pot_fakt_rp != 0){
      var potongaaan = pot_fakt_per;
      var pos = potongaaan.search("%");
      var potongan_persen = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(potongaaan))));
          potongan_persen = potongan_persen.replace("%","");
          if(potongan_persen != 0 )
          {
            potongaaan = total_akhir1 * potongan_persen / 100;
          }
          else
          {
            potongaaan = 0;
          }


      var total_akhir = parseInt(total_akhir1,10) - parseInt(Math.round(potongaaan,10));
      var pot_pers = parseInt(potongaaan,10) / parseInt(total_akhir1,10) * 100;
    }





  if (kode_barang == ''){
    alert("Kode Barang Harus Diisi");
  }
  else if (jumlah_barang == ''){
    alert("Jumlah Barang Harus Diisi");
  }
  else if (jumlah_barang == 0){
    alert("Jumlah Barang Tidak Boleh 0");
  }
  else if (potongan > subtotal_murni){
      alert("Anda Tidak Bisa Menambahkan Barang , Potongan telah melebihi subtotal !");
      $("#potongan1").val('');
      $("#potongan1").focus();
      $("#tax1").val('')
  }
  else if (suplier == ''){
    alert("Suplier Harus Dipilih");
    $("#nama_suplier").trigger('chosen:open');
  }
  else if (kode_gudang == ''){
    alert("Kode Gudang Harus Diisi");
    $("#kode_gudang").trigger('chosen:open');
  }
  else if (ppn == ''){
    alert("PPN Harus Diisi");
    $("#ppn").trigger('chosen:open');
  }
  else if (tax > 100){
    alert("Tax Tidak Boleh Lebih Dari 100%");
  }
  else{

    var harga_awal = $("#harga_produk").val();
    var harga_terbaru = $("#harga_baru").val();

    if(harga_awal != harga_terbaru){

      var pesan_alert = confirm("Ada perubahan Harga, Tetap Lanjutkan ?");
          
      if (pesan_alert == true) {

        var selisih_harga = parseInt(harga_jual,10) - parseInt(harga,10);
      
        if (selisih_harga < 0) {
          var pesan_alert = confirm("Total Harga '"+nama_barang+"' Lebih Besar Dari Harga Jual. Tetap Lanjutkan ?");
          
          if (pesan_alert == true) {

            $('#opt-produk-'+kode_barang).attr("harga",harga);
            $('#opt-produk-'+kode_barang).attr("harga",harga);
          //awal proses insert ke TBS 
          $.post("prosestbspembelian.php",{session_id:session_id,kode_barang:kode_barang,nama_barang:nama_barang,jumlah_barang:jumlah_barang,harga:harga,harga_baru:harga_baru,potongan:potongan,tax:tax,satuan:satuan,ppn:ppn,tax:tax},function(data){

            var tabel_tbs_pembelian = $('#tabel_tbs_pembelian').DataTable();
            tabel_tbs_pembelian.draw();

            $("#kode_barang").val('');
            $("#kode_barang").trigger('chosen:updated');
            $("#jumlah_barang").val('');
            $("#harga_produk").val('');
            $("#nama_barang").val('');
            $("#harga_produk").val('');
            $("#harga_baru").val('');

            $("#ppn").attr("disabled", true).trigger('chosen:updated');
            $("#potongan_pembelian").val(Math.round(potongaaan,10));
            $("#potongan_persen").val(Math.round(pot_pers));
            $("#total_pembelian").val(tandaPemisahTitik(Math.round(total_akhir)));
            $("#total_pembelian1").val(tandaPemisahTitik(total_akhir1));
                  //AWAL PERHITUNGAN PEMBAYARAN 
                  var pembayaran = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#pembayaran_pembelian").val()))));
                  var sisa = pembayaran - total_akhir;
                  var sisa_kredit = total_akhir - pembayaran;

                  if (sisa < 0  ){

                     $("#kredit").val(sisa_kredit);
                     $("#sisa_pembayaran_pembelian").val('0');
                     $("#tanggal_jt").attr("disabled", false);
                  }
                  else{

                    $("#sisa_pembayaran_pembelian").val(sisa);
                    $("#kredit").val('0');
                    $("#tanggal_jt").attr("disabled", true);
                  }

                  //AKHIR PERHITUNGAN PEMBAYARAN
            $("#jumlah_barang").val('');
            $("#potongan1").val('');
            $("#submit_produk").hide()

          });
          //awal proses insert ke TBS 

        } //breakert IF pesan alert selisih harga
        else{ // breaket elese pesan alert selisih harga
          $("#harga_baru").val(harga_awal);
          $("#submit_produk").show();
        }//akhir breakert dari else pesan alert selisih harga      
      } //if selisih harga
      else{
      //awal proses insert ke TBS 
      
            $('#opt-produk-'+kode_barang).attr("harga",harga);
            $('#opt-produk-'+kode_barang).attr("harga",harga);

          $.post("prosestbspembelian.php",{session_id:session_id,kode_barang:kode_barang,nama_barang:nama_barang,jumlah_barang:jumlah_barang,harga:harga,harga_baru:harga_baru,potongan:potongan,tax:tax,satuan:satuan,ppn:ppn,tax:tax},function(data){

            var tabel_tbs_pembelian = $('#tabel_tbs_pembelian').DataTable();
            tabel_tbs_pembelian.draw();

            $("#kode_barang").val('');
            $("#kode_barang").trigger('chosen:updated');
            $("#jumlah_barang").val('');
            $("#harga_produk").val('');
            $("#nama_barang").val('');
            $("#harga_produk").val('');
            $("#harga_baru").val('');

            $("#ppn").attr("disabled", true).trigger('chosen:updated');
            $("#potongan_pembelian").val(Math.round(potongaaan,10));
            $("#potongan_persen").val(Math.round(pot_pers));
            $("#total_pembelian").val(tandaPemisahTitik(Math.round(total_akhir)));
            $("#total_pembelian1").val(tandaPemisahTitik(total_akhir1));
                  //AWAL PERHITUNGAN PEMBAYARAN 
                  var pembayaran = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#pembayaran_pembelian").val()))));
                  var sisa = pembayaran - total_akhir;
                  var sisa_kredit = total_akhir - pembayaran;

                  if (sisa < 0  ){

                     $("#kredit").val(sisa_kredit);
                     $("#sisa_pembayaran_pembelian").val('0');
                     $("#tanggal_jt").attr("disabled", false);
                  }
                  else{

                    $("#sisa_pembayaran_pembelian").val(sisa);
                    $("#kredit").val('0');
                    $("#tanggal_jt").attr("disabled", true);
                  }

                  //AKHIR PERHITUNGAN PEMBAYARAN
            $("#jumlah_barang").val('');
            $("#potongan1").val('');
            $("#submit_produk").hide()
          });
          //awal proses insert ke TBS 
      }
      
      } //if perubahan harga alert true
      else{ // else perubahan harga 
          $("#harga_baru").val(harga_awal);
          $("#submit_produk").show();

      } // akhir else perubahan harga
    } // if harga awal tidak sama dengan harga baru
    else{ // else jika harga sama dengan ahrga sebelumnya 


            $('#opt-produk-'+kode_barang).attr("harga",harga);
            $('#opt-produk-'+kode_barang).attr("harga",harga);
          //awal proses insert ke TBS 
          $.post("prosestbspembelian.php",{session_id:session_id,kode_barang:kode_barang,nama_barang:nama_barang,jumlah_barang:jumlah_barang,harga:harga,harga_baru:harga_baru,potongan:potongan,tax:tax,satuan:satuan,ppn:ppn,tax:tax},function(data){

            var tabel_tbs_pembelian = $('#tabel_tbs_pembelian').DataTable();
            tabel_tbs_pembelian.draw();

            $("#kode_barang").val('');
            $("#kode_barang").trigger('chosen:updated');
            $("#jumlah_barang").val('');
            $("#harga_produk").val('');
            $("#nama_barang").val('');
            $("#harga_produk").val('');
            $("#harga_baru").val('');

            $("#ppn").attr("disabled", true).trigger('chosen:updated');
            $("#potongan_pembelian").val(Math.round(potongaaan,10));
            $("#potongan_persen").val(Math.round(pot_pers));
            $("#total_pembelian").val(tandaPemisahTitik(Math.round(total_akhir)));
            $("#total_pembelian1").val(tandaPemisahTitik(total_akhir1));
                  //AWAL PERHITUNGAN PEMBAYARAN 
                  var pembayaran = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#pembayaran_pembelian").val()))));
                  var sisa = pembayaran - total_akhir;
                  var sisa_kredit = total_akhir - pembayaran;

                  if (sisa < 0  ){

                     $("#kredit").val(sisa_kredit);
                     $("#sisa_pembayaran_pembelian").val('0');
                     $("#tanggal_jt").attr("disabled", false);
                  }
                  else{

                    $("#sisa_pembayaran_pembelian").val(sisa);
                    $("#kredit").val('0');
                    $("#tanggal_jt").attr("disabled", true);
                  }

                  //AKHIR PERHITUNGAN PEMBAYARAN
            $("#jumlah_barang").val('');
            $("#potongan1").val('');
            $("#submit_produk").hide()
          });
          //awal proses insert ke TBS 

    } // akhir else jika harga sama dengan ahrga sebelumnya 
    
  }

});

$("form").submit(function(){
     return false;
});
</script>


<script type="text/javascript">
$(document).ready(function(){
  $("#cari_produk_pembelian").click(function(){
    var session_id = $("#session_id").val();
    $.post("cek_tbs_pembelian.php",{session_id:session_id},function(data){
          if (data == 1) {
               $('#ppn').attr('disabled', true);
          }
      });
    $("#submit_produk").show('');
  });

});
</script>



 <script>
   //perintah javascript yang diambil dari form proses_bayar_beli.php dengan id=form_beli
  $("#pembayaran").click(function(){

       var session_id = $("#session_id").val();
       var no_faktur = $("#nomorfaktur").val();
       var sisa_pembayaran = $("#sisa_pembayaran_pembelian").val();
       var kredit = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#kredit").val()))));
       var suplier1 = $("#nama_suplier").val();
       var tanggal_jt = $("#tanggal_jt").val();
       var total = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#total_pembelian").val()))));
       var total_1 = $("#total_pembelian1").val();
       var potongan = $("#potongan_pembelian").val();
       var potongan_persen = $("#potongan_persen").val();
       var tax = $("#tax").val();
       var tax1 = $("#tax1").val();
       var cara_bayar = $("#carabayar1").val();
       var pembayaran = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#pembayaran_pembelian").val()))));
       var kode_gudang = $("#kode_gudang").val();
       var ppn = $("#ppn").val();
       var ppn_input = $("#ppn_input").val();
       var sisa = pembayaran - total;
       var sisa_kredit = total - pembayaran;



    if (sisa_pembayaran < 0){
      alert("Jumlah Pembayaran Tidak Mencukupi");
    }
    else if (suplier1 == ""){
      alert("Suplier Harus Di Isi");
      $("#nama_suplier").trigger('chosen:updated');
      $("#nama_suplier").trigger('chosen:open');
    }
     else if (pembayaran == ""){
      alert("Pembayaran Harus Di Isi");
    }
    else if (pembayaran == ""){

      alert("Pembayaran Harus Di Isi");

    }
    else if (sisa < 0){
      alert("Silakan Bayar Hutang ");

    }
    else if (total == "" || total == 0){
      alert(" Anda Belum Melakukan Pembelian ");
    }
    else if (kode_gudang == ""){
      alert(" Kode Gudang Harus Diisi ");
      $("#kode_gudang").trigger('chosen:open');
    }
    else{
    

          $("#pembayaran").hide();
          $("#hutang").hide();
          $("#batal").hide();
          $("#transaksi_baru").show();
          $("#total_pembelian").val('');
          $("#pembayaran_pembelian").val('');
          $("#sisa_pembayaran_pembelian").val('');
          $("#kredit").val('');
          $("#potongan_pembelian").val('');
          $("#potongan_persen").val('');

         $.post("proses_bayar_beli.php",{total_1:total_1,kode_gudang:kode_gudang,session_id:session_id,no_faktur:no_faktur,sisa_pembayaran:sisa_pembayaran,kredit:kredit,suplier:suplier1,tanggal_jt:tanggal_jt,total:total,potongan:potongan,potongan_persen:potongan_persen,tax:tax,tax1:tax1,cara_bayar:cara_bayar,pembayaran:pembayaran,sisa:sisa,sisa_kredit:sisa_kredit,ppn:ppn,ppn_input:ppn_input},function(info) {

          var no_faktur = info;

          $("#cetak_tunai").attr('href', 'cetak_pembelian_tunai.php?no_faktur='+no_faktur);
          $("#cetak_hutang").attr('href', 'cetak_pembelian_hutang.php?no_faktur='+no_faktur);
          $("#tax").val('');
          $("#alert_berhasil").show();
          $("#cetak_tunai").show();
          $("#pembayaran_pembelian").val('');
          $("#sisa_pembayaran_pembelian").val('');
          $("#tax").val('');


         });

        //Table TBS AJAX
        var tabel_tbs_pembelian = $('#tabel_tbs_pembelian').DataTable();
           tabel_tbs_pembelian.draw();

        var table_tbs_order = $('#table_tbs_order').DataTable();
            table_tbs_order.draw();
        //Akhir Table TBS AJAX
  
    }

  });

  $("form").submit(function(){
    return false;
  });

</script>

<script>
//perintah javascript yang diambil dari form proses_bayar_beli.php dengan id=form_beli
  $("#hutang").click(function(){

      var session_id = $("#session_id").val();
      var no_faktur = $("#nomorfaktur").val();
      var sisa_pembayaran = $("#sisa_pembayaran_pembelian").val();
      var kredit = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#kredit").val()))));
      var suplier = $("#nama_suplier").val();
      var tanggal_jt = $("#tanggal_jt").val();
      var total = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#total_pembelian").val()))));
      var total_1 = $("#total_pembelian1").val();
      var potongan = $("#potongan_pembelian").val();
      var potongan_persen = $("#potongan_persen").val();
      var tax = $("#tax").val();
      var tax1 = $("#tax1").val();
      var cara_bayar = $("#carabayar1").val();
      var pembayaran = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#pembayaran_pembelian").val()))));

      if (pembayaran == ''){
        pembayaran = 0;
      }

      var kode_gudang = $("#kode_gudang").val();
      var ppn = $("#ppn").val();
      var ppn_input = $("#ppn_input").val();

      var sisa = pembayaran - total;
      var sisa_kredit = total - pembayaran;
      kredit = sisa_kredit;

      if (suplier == ""){
        alert("Suplier Harus Di Isi");

        $("#nama_suplier").trigger('chosen:updated');
        $("#nama_suplier").trigger('chosen:open');
      }
      else if (kode_gudang == ""){
        alert(" Kode Gudang Harus Diisi ");
      }
      else if (tanggal_jt == ""){
        alert ("Tanggal Jatuh Tempo Harus Di Isi");
        $("#tanggal_jt").focus();
      }
      else if (sisa_kredit == "" ){
        alert ("Jumlah Pembayaran Tidak Mencukupi");
      }
      else if (total == "" || total == 0){
        alert("Total Kosong, Anda Belum Melakukan Pembelian");
      }
      else{

          $("#kredit").val(sisa_kredit);
          $("#pembayaran").hide();
          $("#hutang").hide();
          $("#batal").hide();
          $("#transaksi_baru").show();

          $.post("proses_bayar_beli.php",{total_1:total_1,kode_gudang:kode_gudang,session_id:session_id,no_faktur:no_faktur,sisa_pembayaran:sisa_pembayaran,kredit:kredit,suplier:suplier,tanggal_jt:tanggal_jt,total:total,potongan:potongan,potongan_persen:potongan_persen,tax:tax,tax1:tax1,cara_bayar:cara_bayar,pembayaran:pembayaran,sisa:sisa,sisa_kredit:sisa_kredit,ppn:ppn,ppn_input:ppn_input},function(info) {

          var no_faktur = info;

          $("#cetak_tunai").attr('href', 'cetak_pembelian_tunai.php?no_faktur='+no_faktur);
          $("#cetak_hutang").attr('href', 'cetak_pembelian_hutang.php?no_faktur='+no_faktur);
          $("#alert_berhasil").show();
          $("#cetak_hutang").show();
          $("#pembayaran_pembelian").val('');
          $("#sisa_pembayaran_pembelian").val('');
          $("#tanggal_jt").val('');

          $("#pembayaran_pembelian").val('');
          $("#sisa_pembayaran_pembelian").val('');
          $("#kredit").val('');
          $("#tanggal_jt").val('');

          }); //akhir proses bayar beli pada HUTANG !!

 

      //Table TBS AJAX
         var tabel_tbs_pembelian = $('#tabel_tbs_pembelian').DataTable();
           tabel_tbs_pembelian.draw();

            var table_tbs_order = $('#table_tbs_order').DataTable();
                table_tbs_order.draw();
          //Ajax table TBS !!
                


      } //Akhir ELSE UNTUK PROSES BAYAR BELI !!
      //mengambil no_faktur pembelian agar berurutan
    });

          $("form").submit(function(){
              return false;
          });

            $("#hutang").mouseleave(function(){

               $.get('no_faktur_bl.php', function(data) {
               /*optional stuff to do after getScript */

               $("#nomorfaktur").val(data);
               $("#no_faktur0").val(data);
               });
               var suplier = $("#nama_suplier").val();
               if (suplier == ""){
               $("#nama_suplier").attr("disabled", false);
               }

            });

  </script>


<script type="text/javascript">
//berfunsi untuk mencekal username ganda
 $(document).ready(function(){
  $(document).on('click', '.pilih', function (e) {
    var session_id = $("#session_id").val();
    var kode_barang = $("#kode_barang").val();
 $.post('cek_kode_barang_tbs_pembelian.php',{kode_barang:kode_barang,session_id:session_id}, function(data){

  if(data == 1){
    alert("Anda Tidak Bisa Menambahkan Barang Yang Sudah Ada, Silakan Edit atau Pilih Barang Yang Lain !");
    $("#kode_barang").val('');
    $("#kode_barang").trigger('chosen:updated');
    $("#nama_barang").val('');
   }//penutup if

    });////penutup function(data)

    });//penutup click(function()
  });//penutup ready(function()
</script>




<script type="text/javascript">

// untuk memunculkan data cek total pembelian
$(document).ready(function(){

  var session_id = $("#session_id").val();

$.post("cek_total_pembelian.php",
    {
        session_id: "<?php echo $session_id; ?>"
    },
    function(data){
        $("#total_pembelian"). val(data);
        $("#total_pembelian1").val(data);
    });

});


</script>



<!-- INPUT JUMLAH PRODUK -->
<script>
  $(document).ready(function(){
    $("#jumlah_barang").keyup(function(){
      var nama_barang = $("#nama_barang").val();
      var jumlah_barang = $("#jumlah_barang").val();
      if (jumlah_barang == "") {
          jumlah_barang = 0;
      }

      var jumlahbarang =$("#jumlahbarang").val();
      var over_stok = $("#over_stok").val();
      var stok = parseFloat(jumlah_barang) + parseFloat(jumlahbarang);


      if( over_stok < stok && over_stok != 0 ){

            alert("Persediaan Produk '"+nama_barang+"' Ini Melebihi Batas Over Stok.");
            $("#jumlah_barang").val('');
            $("#jumlah_barang").focus();
      }


  });
});

</script>


<!--membuat menampilkan no faktur dan suplier pada tax-->
<script>

$(document).ready(function(){
    $("#nama_suplier").change(function(){
      var suplier = $("#nama_suplier").val();
      $("#supplier").val(suplier);

    });
});
</script>


<script>

$(document).ready(function(){
      var cara_bayar = $("#carabayar1").val();
      $.post('cek_jumlah_kas1.php', {cara_bayar : cara_bayar}, function(data) {
        $("#jumlah1").val(data);
      });
});
</script>

<script>

$(document).ready(function(){
    $("#carabayar1").change(function(){
      var cara_bayar = $("#carabayar1").val();

      //metode POST untuk mengirim dari file cek_jumlah_kas.php ke dalam variabel "dari akun"
      $.post('cek_jumlah_kas1.php', {cara_bayar : cara_bayar}, function(data) {
        /*optional stuff to do after success */

      $("#jumlah1").val(data);

      });



    });
});
</script>

<script>

// untuk memunculkan jumlah kas secara otomatis
$(document).ready(function(){
  $("#pembayaran_pembelian").keyup(function(){

    var pembayaran = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#pembayaran_pembelian").val()))));
    var total = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#total_pembelian").val()))));
    var kredit = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#kredit").val()))));
    var sisa = pembayaran - total;
    var sisa_kredit = total - pembayaran;

    if (sisa < 0  ){

       $("#kredit").val(sisa_kredit);
       $("#sisa_pembayaran_pembelian").val('0');
       $("#tanggal_jt").attr("disabled", false);
    }
    else{

      $("#sisa_pembayaran_pembelian").val(sisa);
      $("#kredit").val('0');
      $("#tanggal_jt").attr("disabled", true);
    }



    var jumlah = $("#pembayaran_pembelian").val();
    var jumlah_kas = $("#jumlah1").val();
    var sisa = jumlah_kas - jumlah;
    var carabayar1 = $("#carabayar1").val();

    if (sisa < 0 || carabayar1 == ""){
        
        alert("Jumlah Kas Tidak Mencukupi Atau Kolom Cara Bayar Masih Kosong");     

        $("#submit").hide();
        $("#pembayaran_pembelian").val(0);
        $("#potongan_pembelian").val('');
        $("#potongan_persen").val('');
        $("#tax").val('');
        $("#kredit").val(sisa_kredit);

    }
    else {
        $("#submit").show();
    }
  });
});
</script>

<script type="text/javascript">
  $("#potongan_persen").keyup(function(){

      var potongan_persen = $("#potongan_persen").val();

            if (potongan_persen > 100){
              alert("Potongan Tidak Boleh Lebih Dari 100%");
              $("#potongan_pembelian").val('');
              $("#potongan_persen").val('');
             }
  });
</script>

      <script>


      $(document).ready(function(){
      $("#potongan_persen").keyup(function(){
      var potongan_persen = $("#potongan_persen").val();
      var pembayaran = $("#pembayaran_pembelian").val();
      var tax = $("#tax").val();

      if (tax == "") {
        tax = 0;
      }


      var total = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#total_pembelian1").val()))));
      var potongan_rp = ((total * potongan_persen) / 100);
      var sisa_potongan = total - potongan_rp;
      var kredit = parseInt(sisa_potongan, 10) - parseInt(pembayaran,10);
      var kembalian = parseInt(pembayaran,10) - parseInt(sisa_potongan, 10);
      var t_tax = ((parseInt(sisa_potongan,10) * parseInt(tax,10)) / 100);
      var hasil_akhir = parseInt(sisa_potongan, 10) + parseInt(t_tax,10);

      if (kembalian < 0) {
      $("#kredit").val(kredit);
      $("#sisa_pembayaran_pembelian").val('0');
      }
      if (kredit < 0) {
      $("#kredit").val('0');
      $("#sisa_pembayaran_pembelian").val(kembalian);
      }

             if (potongan_rp != ""){
             $("#potongan_pembelian").attr("readonly", true);
             }

             else{
              $("#potongan_pembelian").attr("readonly", false);
             }

      $("#total_pembelian").val(tandaPemisahTitik(Math.round(hasil_akhir)));
      $("#potongan_pembelian").val(tandaPemisahTitik(Math.round(potongan_rp)));

      });
      });

      $(document).ready(function(){
      $("#potongan_pembelian").keyup(function(){
      var potongan_pembelian = $("#potongan_pembelian").val();
      var pembayaran = $("#pembayaran_pembelian").val();
      var total = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#total_pembelian1").val()))));
      var potongan_persen = ((potongan_pembelian / total) * 100);
      var sisa_potongan = total - potongan_pembelian;
      var tax = $("#tax").val();

      if (tax == "") {
        tax = 0;
      }

      var kredit = parseInt(sisa_potongan, 10) - parseInt(pembayaran,10);
      var kembalian = parseInt(pembayaran,10) - parseInt(sisa_potongan, 10);
      var t_tax = ((parseInt(sisa_potongan,10) * parseInt(tax,10)) / 100);
      var hasil_akhir = parseInt(sisa_potongan, 10) + parseInt(t_tax,10);

      if (kembalian < 0) {
      $("#kredit").val(kredit);
      $("#sisa_pembayaran_pembelian").val('0');
      }
      if (kredit < 0) {
      $("#kredit").val('0');
      $("#sisa_pembayaran_pembelian").val(kembalian);
      }


             if (potongan_persen != ""){
             $("#potongan_persen").attr("readonly", true);
             }

             else{
              $("#potongan_persen").attr("readonly", false);
             }

      $("#total_pembelian").val(tandaPemisahTitik(Math.round(hasil_akhir)));
      $("#potongan_persen").val(tandaPemisahTitik(Math.round(potongan_persen)));

      var diskon_persen = $("#potongan_persen").val();

            if (diskon_persen > 100){
              alert("Potongan Tidak Boleh Lebih Dari 100%")
              $("#potongan_pembelian").val('');
              $("#potongan_persen").val('');
             }

      });
      });

</script>

<script type="text/javascript">

      $(document).ready(function(){


      $("#tax").keyup(function(){

              var potongan = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#potongan_pembelian").val()))));
              var cara_bayar = $("#carabayar1").val();
              var tax = $("#tax").val();
              var pembayaran = $("#pembayaran_pembelian").val();
              var total = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#total_pembelian1").val()))));
              var t_total = total - potongan;

              if (tax == "") {
                tax = 0;
              }

              var t_tax = ((parseInt(t_total,10) * parseInt(tax,10)) / 100);
              var total_akhir = parseInt(t_total,10) + parseInt(t_tax,10);
              var kredit = parseInt(total_akhir, 10) - parseInt(pembayaran, 10);
              var kembalian = parseInt(pembayaran, 10) - parseInt(total_akhir, 10);


              if (kembalian < 0) {
              $("#kredit").val(kredit);
              $("#sisa_pembayaran_pembelian").val('0');
              }
              if (kredit < 0) {
              $("#kredit").val('0');
              $("#sisa_pembayaran_pembelian").val(kembalian);
              }



              $("#total_pembelian").val(parseInt(total_akhir));

              if (tax > 100) {
                alert ('Jumlah Tax Tidak Boleh Lebih Dari 100%');
                 $("#tax").val('');

              }


      });
    });

</script>



      <script type="text/javascript">

      $(".chosen").chosen({no_results_text: "Maaf, Data Tidak Ada!",search_contains:true});

      </script>



<script type="text/javascript">
    $(document).ready(function(){

//fungsi hapus data
$(document).on('click', '.btn-hapus-tbs', function (e) {
  var session_id = $("#session_id").val();
    var nama_barang = $(this).attr("data-barang");
    var id = $(this).attr("data-id");
    var subtotal_tbs = $(this).attr("data-subtotal");
    var total = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#total_pembelian1").val()))));
    var potongan_persen = $("#potongan_persen").val();
    var tax = $("#tax").val();

      if (total == '')
        {
          total = 0;
        };

      if (potongan_persen == '')
        {
          potongan_persen = 0;
        };

      if (tax == '')
        {
          tax = 0;
        };
    var total_akhir = parseInt(total,10) - parseInt(subtotal_tbs,10);
    var diskon_faktur = parseInt(total_akhir,10) * parseInt(potongan_persen,10) / 100;
    var sub_krg_diskon = parseInt(total_akhir,10) - parseInt(diskon_faktur,10);
    var tax_faktur = parseInt(sub_krg_diskon,10) * parseInt(tax,10) / 100;
    var total_bener = parseInt(sub_krg_diskon,10) + parseInt(tax_faktur,10);

  var pesan_alert = confirm("Apakah Anda Yakin Ingin Menghapus '"+nama_barang+"' "+ "?");
  if (pesan_alert == true) {


      $("#total_pembelian").val(tandaPemisahTitik(total_bener));
      $("#total_pembelian1").val(tandaPemisahTitik(total_akhir));
      $("#potongan_pembelian").val(tandaPemisahTitik(diskon_faktur));

                  //AWAL PERHITUNGAN PEMBAYARAN 
                  var pembayaran = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#pembayaran_pembelian").val()))));
                  var sisa = pembayaran - total_akhir;
                  var sisa_kredit = total_akhir - pembayaran;

                  if (sisa < 0  ){

                     $("#kredit").val(sisa_kredit);
                     $("#sisa_pembayaran_pembelian").val('0');
                     $("#tanggal_jt").attr("disabled", false);
                  }
                  else{

                    $("#sisa_pembayaran_pembelian").val(sisa);
                    $("#kredit").val('0');
                    $("#tanggal_jt").attr("disabled", true);
                  }

                  //AKHIR PERHITUNGAN PEMBAYARAN
                  
      $.post("hapustbs_pembelian.php",{id:id},function(data){
      if (data == 'sukses') {

        $(".tr-id-"+id+"").remove();

      }
      });

      $('#tabel_tbs_pembelian').DataTable().destroy();
      var dataTable = $('#tabel_tbs_pembelian').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax":{
          url :"data_tbs_pembelian.php", // json datasource
          "data": function ( d ) {
            d.session_id = $("#session_id").val();
            // d.custom = $('#myInput').val();
            // etc
          },

           type: "post",  // method  , by default get
           error: function(){  // error handling
             $(".employee-grid-error").html("");
             $("#tabel_tbs_pembelian").append('<tbody class="employee-grid-error"><tr><th colspan="3">Data Tidak Ditemukan.. !!</th></tr></tbody>');
             $("#employee-grid_processing").css("display","none");
             }
        },
          "fnCreatedRow": function( nRow, aData, iDataIndex ) {
             $(nRow).attr('class','tr-id-'+aData[11]+'');
           }
      });

      }
  else {

      }

      $.post("cek_tbs_pembelian.php",{session_id:session_id},function(data){
          if (data == 0) {
               $("#ppn").attr("disabled", false).trigger('chosen:updated');
          }
      });

    });

//end fungsi hapus data



              $('form').submit(function(){

              return false;
              });
        });




     function tutupmodal() {
     $(".modal").modal("hide")
     }
     function tutupalert() {
     $(".alert").hide("fast")
     }

</script>




<script type="text/javascript" language="javascript" >
  $(document).on('click', '#btn-hps-order', function (e) {
    var no_faktur = $("#hapus_order").val();

        $.post("hapus_order_pembelian_tbs.php",{no_faktur:no_faktur},function(data){
          
          $("#order_data").html(data);
          $.get("ambil_select_order.php",function(info){
            $("#select_order").html(info);
          });

        }); 

        $('#nama_suplier').prop('disabled', false).trigger("chosen:updated");
        $('#kode_gudang').prop('disabled', false).trigger("chosen:updated");

    var table_tbs_order = $('#table_tbs_order').DataTable();
        table_tbs_order.draw();

    var total_perorder = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#total_perorder").val()))));
        if (total_perorder == ''){
          total_perorder = 0;
        }
    var subtotal = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#total_pembelian1").val()))));
        if (subtotal == ''){
          subtotal = 0;
        }
    var total_akhir1 = parseInt(subtotal) - parseInt(total_perorder);

    var pot_fakt_per = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#potongan_persen").val()))));
       if (pot_fakt_per == "" || pot_fakt_per == 0) {
          pot_fakt_per = 0;
        }

    var pot_fakt_rp = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#potongan_pembelian").val()))));
       if (pot_fakt_rp == "" || pot_fakt_rp == 0 ) {
          pot_fakt_rp = 0;
        }


    var tax_faktur = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#tax").val()))));
       if (pot_fakt_per == 0) {

          var potongaaan = pot_fakt_rp;
          var pot_fakt_per = parseInt(potongaaan) / parseInt(total_akhir1) * 100;

          if (pot_fakt_rp == 0 ){
             var total_akhier = parseInt(total_akhir1);
          }
          else{
            var total_akhier = parseInt(total_akhir1) - parseInt(pot_fakt_rp);
          }

          if (tax_faktur != 0 ){
            var hasil_tax = parseInt(total_akhier) * parseInt(tax_faktur) / 100;
          }
          else{
            var hasil_tax = 0;
          }

          var total_akhir = parseInt(total_akhier) + parseInt(Math.round(hasil_tax));

      }
     else if(pot_fakt_rp == 0){

          var potongaaan = pot_fakt_per;
          var pos = potongaaan.search("%");
          var potongan_persen = potongaaan;
          var   potongan_persen = potongan_persen.replace("%","");
          var potongaaan = parseInt(total_akhir1) * parseInt(potongan_persen) / 100;

          if ( pot_fakt_rp == 0 ){   
            var total_akhier = parseInt(total_akhir1);
          }
          else{
             var total_akhier = parseInt(total_akhir1) - parseInt(potongaaan);
          }

          if (tax_faktur != 0) {
            var hasil_tax = parseInt(total_akhier) * parseInt(tax_faktur) / 100;
          }
          else{
            var hasil_tax = 0;
          }

          var total_akhir = parseInt(total_akhier);

      }
     else if(pot_fakt_rp != 0 && pot_fakt_per != 0){

          var potongaaan = pot_fakt_per;
          var pos = potongaaan.search("%");
          var potongan_persen = potongaaan;
          var   potongan_persen = potongan_persen.replace("%","");
          var potongaaan = parseInt(total_akhir1) * parseInt(potongan_persen) / 100;

          if ( pot_fakt_rp == 0 ){   
            var total_akhier = parseInt(total_akhir1);
          }
          else{
            var total_akhier = parseInt(total_akhir1) /*+ parseInt(biaya_adm)*/ - parseInt(potongaaan);
          }

         if (tax_faktur != 0){
              var hasil_tax = parseInt(total_akhier) * parseInt(tax_faktur) / 100;
            }
         else{
              var hasil_tax = 0;
            }

          var total_akhir = parseInt(total_akhier) + parseInt(Math.round(hasil_tax));

      }

      $("#total_pembelian").val(tandaPemisahTitik(total_akhir));
      $("#tax_rp").val(hasil_tax);
      $("#total_pembelian1").val(tandaPemisahTitik(total_akhir1));

      if (pot_fakt_rp == 0){
        $("#potongan_pembelian").val();
      }
      else{  
        $("#potongan_pembelian").val(potongaaan);
      }
});
</script>




<script type="text/javascript">
$(document).ready(function(){
  $(document).on('click', '#batal', function (e) {
    var pesan_alert = confirm("Apakah Anda Yakin Ingin Membatalkan Pembelian Ini ?");
    if (pesan_alert == true) {

        $.get("batal_pembelian.php",function(data){

           $("#total_pembelian1").val('0');
           $("#potongan_pembelian").val('0');
           $("#potongan_persen").val('0');
           $("#total_pembelian").val('0');
           $("#pembayaran_pembelian").val('0');
           $("#sisa_pembayaran_pembelian").val('0');
           $("#kredit").val('0');
           var tabel_tbs_pembelian = $('#tabel_tbs_pembelian').DataTable();
           tabel_tbs_pembelian.draw();

        });
    }

  });
  });
</script>

<!--
<script type="text/javascript">

        $(document).ready(function(){
        $("#kode_barang").keyup(function(){

          var kode_barang = $(this).val();
          var session_id = $("#session_id").val();

          $.post("cek_barang_pembelian.php",
          {
          kode_barang: kode_barang
          },
          function(data){
          $("#jumlahbarang").val(data);
          });

          $.post('cek_kode_barang_tbs_pembelian.php',{kode_barang:kode_barang,session_id:session_id}, function(data){

          if(data == 1){
          alert("Anda Tidak Bisa Menambahkan Barang Yang Sudah Ada, Silakan Edit atau Pilih Barang Yang Lain !");
          $("#kode_barang").val('');
          $("#kode_barang").trigger('chosen:updated');
          $("#nama_barang").val('');
          }//penutup if

          });////penutup function(data)

      $.getJSON('lihat_nama_barang_pembelian.php',{kode_barang:$(this).val()}, function(json){

      if (json == null)
      {

        $('#nama_barang').val('');
        $('#over_stok').val('');
        $('#harga_produk').val('');
        $('#satuan_produk').val('');
        $('#satuan_konversi').val('');
        $('#harga_baru').val('');
      }

      else
      {
        $('#nama_barang').val(json.nama_barang);
        $('#over_stok').val(json.over_stok);
        $('#harga_produk').val(json.harga_beli);
        $('#satuan_produk').val(json.satuan);
        $('#satuan_konversi').val(json.satuan);
        $('#harga_baru').val(json.harga_beli);
      }

        });

        });
        });



</script>

-->

<script type="text/javascript">

  $(document).ready(function(){
  $("#kode_barang").change(function(){

    var kode_barang = $(this).val();
    var session_id = $("#session_id").val();
    var nama_barang = $('#opt-produk-'+kode_barang).attr("nama-barang");
    var harga_jual = $('#opt-produk-'+kode_barang).attr("harga_jual");
    var jumlah_barang = $('#opt-produk-'+kode_barang).attr("jumlah-barang");
    var satuan = $('#opt-produk-'+kode_barang).attr("satuan");
    var kategori = $('#opt-produk-'+kode_barang).attr("kategori");
    var status = $('#opt-produk-'+kode_barang).attr("status");
    var suplier = $('#opt-produk-'+kode_barang).attr("suplier");
    var limit_stok = $('#opt-produk-'+kode_barang).attr("limit_stok");
    var over_stok = $('#opt-produk-'+kode_barang).attr("over_stok");
    var harga_lama = $('#opt-produk-'+kode_barang).attr("harga");
    var harga_produk = $('#opt-produk-'+kode_barang).attr("harga");
    var harga_baru = $('#opt-produk-'+kode_barang).attr("harga");
    var ber_stok = $('#opt-produk-'+kode_barang).attr("ber-stok");
    var tipe_barang = $('#opt-produk-'+kode_barang).attr("tipe_barang");
    var id_barang = $('#opt-produk-'+kode_barang).attr("id-barang");
    var level_harga = $("#level_harga").val();



    $("#kode_barang").val(kode_barang);
    $("#nama_barang").val(nama_barang);
    $("#jumlah_barang").val(jumlah_barang);
    $("#satuan_produk").val(satuan);
    $("#harga_lama").val(harga_lama);
    $("#harga_produk").val(harga_produk);
    $("#harga_baru").val(harga_baru);
    $("#satuan_konversi").val(satuan);
    $("#over_stok").val(over_stok);
    $("#limit_stok").val(limit_stok);
    $("#ber_stok").val(ber_stok);
    $("#id_produk").val(id_barang);
    $("#harga_jual").val(harga_jual);

if (ber_stok == 'Barang') {

    $.post('ambil_jumlah_produk.php',{kode_barang:kode_barang}, function(data){
      if (data == "") {
        data = 0;
      }
      $("#jumlahbarang").val(data);
      $('#kolom_cek_harga').val('1');
    });

}


$.post('cek_kode_barang_tbs_pembelian.php',{kode_barang:kode_barang,session_id:session_id}, function(data){

  if(data == 1){
          alert("Anda Tidak Bisa Menambahkan Barang Yang Sudah Ada, Silakan Edit atau Pilih Barang Yang Lain !");

          $("#kode_barang").chosen("destroy");
          $("#kode_barang").val('');
          $("#kode_barang").trigger('chosen:updated');
          $("#nama_barang").val('');
          $("#kode_barang").trigger('chosen:open');
          $(".chosen").chosen({no_results_text: "Maaf, Data Tidak Ada!",search_contains:true});
   }//penutup if

  //JSON SATUAN PRODUK
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
  //AKHIR JSON SATUAN PRODUK

  });

  $("#submit_produk").show('');
  });

  });

</script>


<script type="text/javascript">
  $(document).ready(function(){

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
</script>

<script type="text/javascript">
  $(document).ready(function(){

    $("#ppn").change(function(){
      var ppn = $("#ppn").val();
      $("#ppn_input").val(ppn);

      if (ppn == "Include"){
          $("#tax").attr("disabled", true);
          $("#tax1").attr("disabled", false);
          $("#tax").val("");
          $("#tax1").val("<?php echo $nilai_ppn ?>");
      }
      else if (ppn == "Exclude") {
          $("#tax").attr("disabled", true);
          $("#tax1").attr("disabled", false);
          $("#tax").val("");
          $("#tax1").val("<?php echo $nilai_ppn ?>");
      }
      else{
        $("#tax1").attr("disabled", true);
        $("#tax").attr("disabled", true);
        $("#tax1").val("");
        $("#tax").val("");
      }
    });

  });
</script>


 <script type="text/javascript">
                                 $(document).on('dblclick','.edit-jumlah',function(e){

                                      var id = $(this).attr("data-id");


                                        $("#text-jumlah-"+id+"").hide();
                                        $("#input-jumlah-"+id+"").attr("type", "text");


                                 });

                                 $(document).on('blur','.input_jumlah',function(e){

                                    var id = $(this).attr("data-id");
                                    var jumlah_baru = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($(this).val()))));
                                    var kode_barang = $(this).attr("data-kode");
                                    var harga = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($(this).attr("data-harga")))));
                                    var jumlah_lama = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#text-jumlah-"+id+"").text()))));
                                    var no_faktur = $("#nomorfaktur").val();
                                    var potongan = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#text-potongan-"+id+"").text()))));
                                    var tax = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#text-tax-"+id+"").text()))));
                                    var subtotal_lama = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#text-subtotal-"+id+"").text()))));
                                    if (tax == "") {
                                    tax = 0;
                                    }

                                    var potongan_persen = $("#potongan_persen").val();
                                    if (potongan_persen == '')
                                    {
                                    potongan_persen = 0;
                                    }

                                    var ppn = $("#ppn").val();

                                    var tax = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#text-tax-"+id+"").text()))));

                                    var subtotal_tbs = parseInt(harga,10) * parseInt(jumlah_lama,10) - parseInt(potongan,10);
                                    var subtotal = parseInt(harga,10) * parseInt(jumlah_baru,10) - parseInt(potongan,10);

                                    var subtotal_penjualan = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#total_pembelian1").val()))));

                                    var tax_tbs = tax / subtotal_tbs * 100;
                                    var jumlah_tax = tax_tbs * subtotal / 100;
                                    if (ppn == 'Exclude') {
                                    var sub_tampil = parseInt(subtotal,10) + parseInt(Math.round(jumlah_tax,10));
                                    var subtotal_penjualan = parseInt(subtotal_penjualan,10) - parseInt(subtotal_lama,10) + parseInt(sub_tampil,10);
                                    var diskon_faktur = parseInt(subtotal_penjualan,10) * parseInt(potongan_persen) / 100;
                                    var sub_akhir = parseInt(subtotal_penjualan,10) - parseInt(Math.round(diskon_faktur,10));
                                    }
                                    else{
                                    var sub_tampil = parseInt(subtotal,10);
                                    var subtotal_penjualan = parseInt(subtotal_penjualan,10) - parseInt(subtotal_lama,10) + parseInt(sub_tampil,10);
                                    var diskon_faktur = parseInt(subtotal_penjualan,10) * parseInt(potongan_persen) / 100;
                                    var sub_akhir = parseInt(subtotal_penjualan,10) - parseInt(Math.round(diskon_faktur,10));
                                    }

                                    var nama_barang = $(this).attr("data-nama");
                                    var jumlahbarang = $(this).attr("data-stok");
                                    var over_stok = $(this).attr("data-over");
                                    var stok = parseFloat(jumlah_baru) + parseFloat(jumlahbarang);

                                if (jumlah_baru == 0) {
                                    alert("Jumlah Barang Tidak Boleh Nol (0) atau Kosong");

                                    $("#input-jumlah-"+id+"").val(jumlah_lama);
                                    $("#text-jumlah-"+id+"").text(jumlah_lama);
                                    $("#text-jumlah-"+id+"").show();
                                    $("#input-jumlah-"+id+"").attr("type", "hidden");
                                }
                                else
                                {
                                    if( over_stok < stok && over_stok != 0 ){

                                      alert("Persediaan Produk '"+nama_barang+"' Ini Melebihi Batas Over Stok.");
                                      $("#jumlah_barang").val('');
                                      $("#jumlah_barang").focus();
                                      $("#input-jumlah-"+id+"").val(jumlah_lama);
                                      $("#text-jumlah-"+id+"").text(jumlah_lama);
                                      $("#text-jumlah-"+id+"").show();
                                      $("#input-jumlah-"+id+"").attr("type", "hidden");

                                    }
                                    else{
                                      

                                      $("#text-jumlah-"+id+"").show();
                                      $("#text-jumlah-"+id+"").text(jumlah_baru);
                                      $("#text-subtotal-"+id+"").text(tandaPemisahTitik(sub_tampil));
                                      $("#hapus-tbs-"+id+"").attr('data-subtotal', sub_tampil);
                                      $("#text-tax-"+id+"").text(Math.round(jumlah_tax));
                                      $("#input-jumlah-"+id+"").attr("type", "hidden");
                                      $("#total_pembelian").val(tandaPemisahTitik(sub_akhir));
                                      $("#total_pembelian1").val(tandaPemisahTitik(subtotal_penjualan));
                                      $("#potongan_pembelian").val(Math.round(diskon_faktur));


                                        //AWAL PERHITUNGAN PEMBAYARAN 
                                        var pembayaran = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#pembayaran_pembelian").val()))));
                                        var sisa = pembayaran - sub_akhir;
                                        var sisa_kredit = sub_akhir - pembayaran;

                                        if (sisa < 0  ){

                                           $("#kredit").val(sisa_kredit);
                                           $("#sisa_pembayaran_pembelian").val('0');
                                           $("#tanggal_jt").attr("disabled", false);
                                        }
                                        else{

                                          $("#sisa_pembayaran_pembelian").val(sisa);
                                          $("#kredit").val('0');
                                          $("#tanggal_jt").attr("disabled", true);
                                        }

                                        //AKHIR PERHITUNGAN PEMBAYARAN
                  
                                       $.post("update_pesanan_barang_beli.php",{harga:harga,jumlah_lama:jumlah_lama,jumlah_tax:jumlah_tax,potongan:potongan,id:id,jumlah_baru:jumlah_baru,kode_barang:kode_barang,sub_tampil:sub_tampil},function(){

                                      });

                                    }

                                    

                                    $("#kode_barang").trigger('chosen:open');
                                    //$("#pembayaran_pembelian").val("");

                                  }

                                 });

                             </script>

<script type="text/javascript">
  $(document).on('click', '.tidak_punya_otoritas', function (e) {
    var nama_barang = $(this).attr('data-nama');
    alert("Anda Tidak Punya Otoritas Untuk Edit Jumlah Produk "+nama_barang+"!!");
  });
</script>


<script>

$(document).ready(function(){
    $("#satuan_konversi").change(function(){

      var prev = $("#satuan_produk").val();
      var harga_lama = $("#harga_lama").val();
      var satuan_konversi = $("#satuan_konversi").val();
      var id_produk = $("#id_produk").val();


      $.getJSON("cek_satuan_konversi.php",{satuan_konversi:satuan_konversi, id_produk:id_produk},function(info){

        if (satuan_konversi == prev) {


          $("#harga_produk").val(harga_lama);
          $("#harga_baru").val(harga_lama);

        }

        else if (info.jumlah_total == 0) {
          alert('Satuan Yang Anda Pilih Tidak Tersedia Untuk Produk Ini !');
          $("#satuan_konversi").val(prev);
          $("#harga_produk").val('');
          $("#harga_baru").val('');

        }

        else{
          $("#harga_produk").val(info.harga_pokok);
          $("#harga_baru").val(info.harga_pokok);
        }

      });

    });

});
</script>


<!-- SHORTCUT -->

<script>
    shortcut.add("f1", function() {
        // Do something

        $("#cari_produk_pembelian").click();

    });
    shortcut.add("f2", function() {
        // Do something

        $("#kode_barang").trigger('chosen:open');

    });
    shortcut.add("f3", function() {
        // Do something

        $("#submit_produk").click();

    });
    shortcut.add("f4", function() {
        // Do something

        $("#carabayar1").trigger('chosen:open');

    });
    shortcut.add("f7", function() {
        // Do something

        $("#pembayaran_pembelian").focus();

    });
    shortcut.add("f8", function() {
        // Do something

        $("#pembayaran").click();

    });
    shortcut.add("f9", function() {
        // Do something

        $("#hutang").click();

    });
    shortcut.add("f6", function() {
        // Do something

        $("#daftar_order").click();

    });
    shortcut.add("f10", function() {
        // Do something

        $("#batal").click();

    });
    shortcut.add("alt+p", function() {
        // Do something
        $("#nama_suplier").trigger('chosen:open');

    });
</script>

<!-- SHORTCUT -->


<script type="text/javascript">

  $(document).ready(function(){
    $(document).on('click','#transaksi_baru',function(e){



            $('#tabel_tbs_pembelian').DataTable().destroy();
            var dataTable = $('#tabel_tbs_pembelian').DataTable({
              "processing": true,
              "serverSide": true,
              "ajax":{
                url :"data_tbs_pembelian.php", // json datasource
                "data": function ( d ) {
                  d.session_id = $("#session_id").val();
                  // d.custom = $('#myInput').val();
                  // etc
                },

                 type: "post",  // method  , by default get
                 error: function(){  // error handling
                   $(".employee-grid-error").html("");
                   $("#tabel_tbs_pembelian").append('<tbody class="employee-grid-error"><tr><th colspan="3">Data Tidak Ditemukan.. !!</th></tr></tbody>');
                   $("#employee-grid_processing").css("display","none");
                   }
              },
                "fnCreatedRow": function( nRow, aData, iDataIndex ) {
                   $(nRow).attr('class','tr-id-'+aData[11]+'');
                 }
            });

            $("#tabel_cari").DataTable().destroy();
            var dataTable = $('#tabel_cari').DataTable( {
            "processing": true,
            "serverSide": true,
            "ajax":{
              url :"modal_beli_baru.php", // json datasource
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
                $(nRow).attr('over_stok', aData[7]);
                $(nRow).attr('satuan', aData[7]);
                $(nRow).attr('harga', aData[2]);
                $(nRow).attr('id-barang', aData[8]);
                $(nRow).attr('jumlah-barang', aData[3]);
                $(nRow).attr('kategori', aData[5]);
                $(nRow).attr('suplier', aData[6]);


            }

        });
            var table_tbs_order = $('#table_tbs_order').DataTable();
                table_tbs_order.draw();

            $("#OrderPembelian").hide();
            $("#btnOrder").show();
            $("#btnOrderClose").hide();
            $("#nama_suplier").val('');
            $("#nama_suplier").trigger('chosen;updated');
            $("#nama_suplier").trigger('chosen:open');
            $("#pembayaran_pembelian").val('');
            $("#sisa_pembayaran_pembelian").val('');
            $("#kredit").val('');
            $("#potongan_pembelian").val('');
            $("#potongan_persen").val('');
            $("#tanggal_jt").val('');
            $("#total_pembelian").val('');
            $("#total_pembelian1").val('');
            $("#level_harga").val('');
            $("#keterangan").val('');
            $("#pembayaran").show();
            $("#hutang").show();
            $("#batal").show();
            $("#transaksi_baru").hide();
            $("#cetak_tunai").hide();
            $("#cetak_hutang").hide();
            $("#alert_berhasil").hide();
            $("#ppn").attr("disabled", false).trigger('chosen:updated');


    });
  });

</script>

<script type="text/javascript">
  $(document).on("click", "#btnOrder", function(){
      $('#table_tbs_order').DataTable().destroy();
          var dataTable = $('#table_tbs_order').DataTable( {
          "processing": true,
          "serverSide": true,
          "ajax":{
          url :"datatable_tbs_order_pembelian.php", // json datasource
           
            type: "post",  // method  , by default get
            error: function(){  // error handling
              $(".employee-grid-error").html("");
              $("#table_tbs_order").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
              $("#employee-grid_processing").css("display","none");
            }
        },      
    });

    $("#OrderPembelian").show();
    $("#btnOrder").hide();
    $("#btnOrderClose").show();
  });



  $(document).on("click", "#btnOrderClose", function(){
      
      var table_tbs_order = $('#table_tbs_order').DataTable();
          table_tbs_order.draw();

    $("#OrderPembelian").hide();
    $("#btnOrder").show();
    $("#btnOrderClose").hide();
  });
</script>

<!-- memasukan file footer.php -->
<?php include 'footer.php'; ?>