<?php include 'session_login.php';
//memasukkan file session login, header, navbar, db.php
include 'header.php';
include 'navbar.php';
include 'sanitasi.php';
include 'db.php';


$session_id = session_id();
?>

<!-- js untuk tombol shortcut -->
 <script src="shortcut.js"></script>
<!-- js untuk tombol shortcut -->

<!--untuk membuat agar tampilan form terlihat rapih dalam satu tempat -->
 <div style="padding-left: 5%; padding-right: 5%">

  <!-- Tampilan Modal -->
<div id="modal-pelanggan" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Isi Modal-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"> Terakhir Belanja</h4>
      </div>
      <div class="modal-body"> <!--membuat kerangka untuk tempat tabel -->


<span class="modal_retur_baru">

      <!--perintah agar modal update-->

<div class="table-responsive">
      <!-- membuat agar ada garis pada tabel, disetiap kolom-->
        <table id="table-pelanggan" class="table table-bordered">
    <thead> <!-- untuk memberikan nama pada kolom tabel -->
      <th> Nomor Faktur </th>
      <th> Pelanggan </th>
      <th> Total Belanja </th>
      <th> Tanggal </th>
      <th> Keterangan </th>
      
    </thead> <!-- tag penutup tabel -->
    
  </table> <!-- tag penutup table-->
<?php 
    //menampilkan seluruh data yang ada pada tabel penjualan
  $perintah = $db->query("SELECT lama_tidak_aktif,aktif_kembali,satuan_tidak_aktif FROM setting_member");
  $ambil = mysqli_fetch_array($perintah);

  $satuan_tidak_aktif = $ambil['satuan_tidak_aktif']; 
  $lama_tidak_aktif = $ambil['lama_tidak_aktif']; 
  $aktif_kembali = $ambil['aktif_kembali'];


      if ($ambil['satuan_tidak_aktif'] == 1) {
        $satuan_tidak_aktif = "Bulan";
      }
      else if ($ambil['satuan_tidak_aktif'] == 2) {
        $satuan_tidak_aktif = "Tahun";
      }

   ?>

   </div>
<br>
<h6 style="text-align: left ; color: red" id="text-pelanggan">
  <i> * Pelanggan ini sudah tidak aktif, karena sudah tidak belanja selama <?php echo $lama_tidak_aktif." " .$satuan_tidak_aktif; ?> dan akan aktif kembali jika sudah <?php echo $aktif_kembali; ?> Kali Belanja </i></h6>

</span>

</div> <!-- tag penutup modal body -->

      <!-- tag pembuka modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div> <!--tag penutup moal footer -->
    </div>

  </div>
</div>



<!--tampilan modal-->

    <div class="modal fade modal-ext" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">

    <!-- isi modal-->
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><center><h3><b>Data Barang Hadiah</b></h3></center></h4>
      </div>
      <div class="modal-body">

  <div class="table-responsive">
<center>  <table id="tabel_cari" class="table table-bordered">
  <thead> <!-- untuk memberikan nama pada kolom tabel -->

            <th> Kode Produk </th>
            <th> Nama Produk </th>
            <th> Satuan</th>
            <th> Poin</th>
  </thead> <!-- tag penutup tabel -->
  </table></center>
  </div>

</div> <!-- tag penutup modal-body-->
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div><!-- end of modal data barang  -->




                    <h3> FORM PENUKARAN POIN </h3>
    <div class="row">  <!--div class="row"-->


        <div class="col-sm-8"><!--div class="col-sm-8"-->



                    <!--div class="row"><!ROW-

                            <div class="col-sm-4">
                                <label> Pelanggan </label><br><br>
                                <select name="kode_pelanggan" id="kd_pelanggan" class="form-control chosen" required="" autofocus="" data-placeholder="SILAKAN PILIH...">>
                                <option value="">SILAKAN PILIH...</option>

 
                                  </select>
                          </div>


                          <div class="col-sm-2">
                              <label>Total Poin</label><br>
                              <input type="text" class="form-control" name="jumlah_poin" id="jumlah_poin" autocomplete="off" placeholder="Jumlah Poin" readonly="">
                          </div>

                            <div class="col-sm-2">
                              <label> Tanggal</label><br>
                              <input type="text" class="form-control datepicker" name="tanggal" id="tanggal" autocomplete="off" placeholder="Tanggal" value="<?php echo date("Y-m-d"); ?>">
                          </div>

                    </div>ROW-->

                    <br>
                    <button type="button" id="cari_produk" class="btn btn-info " data-toggle="modal" data-target="#myModal"><i class='fa  fa-search'></i> Cari (F1)  </button> 
                    <br><br>


                  <form class="form" role="form" id="formtambahproduk">

                  <div class="row">

                      <div class="col-sm-3">
                       <select style="font-size:15px; height:20px" type="text" name="kode_barang" id="kode_barang" class="form-control chosen" data-placeholder="SILAKAN PILIH...">
                        <option value="">SILAKAN PILIH...</option>
                           <?php 

                            include 'cache.class.php';
                              $c = new Cache();
                              $c->setCache('produk');
                              $data_c = $c->retrieveAll();

                              foreach ($data_c as $key ) {

                                  $cek = $db->query("SELECT kode_barang,quantity_poin FROM master_poin WHERE kode_barang = '$key[kode_barang]' ");

                                  $rows = mysqli_num_rows($cek);
                                  $data = mysqli_fetch_array($cek);

                                   if ($rows > 0) {
                                   
                                   echo '<option id="opt-produk-'.$key['kode_barang'].'" value="'.$key['kode_barang'].'" data-kode="'.$key['kode_barang'].'" 
                                   nama-barang="'.$key['nama_barang'].'" poin="'.$data['quantity_poin'].'" satuan="'.$key['satuan'].'" id-barang="'.$key['id'].'" > '. $key['kode_barang'].' ( '.$key['nama_barang'].' ) </option>';
                                   
                                   }
                            }

                            ?>
                        </select>
                      </div>

                          <input type="hidden" class="form-control" name="nama_barang" id="nama_barang" autocomplete="off" placeholder="nama">

                    <div class="col-sm-2">
                      <input style="height:13px;" type="text" class="form-control" name="jumlah_barang" autocomplete="off" id="jumlah_barang" placeholder="Jumlah" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
                    </div>

                    <div class="col-sm-2">
                      <input style="height:13px;" type="text" class="form-control" name="poin" autocomplete="off" id="poin" placeholder="Poin" readonly="" >
                    </div>

                    <button id="submit_produk" class="btn btn-success" style="font-size:15px" >Tukar (F3)</button>

                    <input type="hidden" id="satuan" name="satuan" class="form-control"  required="">
                    <input type="hidden" id="stok" name="satuan" class="form-control"  required="">
                  </div>

                    </form>


                    <br>

                <!--Table TBS TUKAR POIN -->  
                <span id='result'>         
                
                <div class="table-responsive"> <!--tag untuk membuat garis pada tabel-->  
                <span id="span_tbs">  
                <table  id="tabel_tukar_poin" class="table table-sm">
                <thead>
                <th> Kode Produk </th>
                <th> Nama Produk</th>
                <th> Satuan </th>
                <th> Jumlah </th>
                <th> Poin </th>
                <th> Subtotal </th>
                <th> Hapus </th>
                
                </thead>
                
                           </table>
                </span>
                </div>
                </span>   
<!--end tABLE tbs Penjualan-->

                <h6 style="text-align: left ; color: red"><i> * Klik 2x pada kolom jumlah barang jika ingin mengedit.</i></h6>
                <h6 style="text-align: left ;"><i><b> * Short Key (F2) untuk mencari Kode Produk atau Nama Produk.</b></i></h6>
                <h6 style="text-align: left ;"><i><b> * Short Key (F4) untuk mencari pelanggan.</b></i></h6>



        </div><!--end div class="col-sm-8"-->

        <div class="col-sm-4">

                      <div class="card card-block">
                        
                          <div class="row">
                                <div class="col-sm-6">
                                    <label> Pelanggan </label><br><br>
                                    <select name="kode_pelanggan" id="kd_pelanggan" class="form-control chosen" required="" autofocus="" data-placeholder="SILAKAN PILIH...">
                                    <option value="">SILAKAN PILIH...</option>

                                              
                                    <?php 
                                      
                                      //untuk menampilkan semua data pada tabel pelanggan dalam DB
                                      $query = $db->query("SELECT id,nama_pelanggan ,kode_pelanggan FROM pelanggan");

                                      //untuk menyimpan data sementara yang ada pada $query
                                      while($data = mysqli_fetch_array($query))                                      {



                                      echo "<option id='opt-pelanggan-".$data['id']."' value='".$data['id'] ."'>".$data['kode_pelanggan'] ." || ".$data['nama_pelanggan'] ."</option>";

                                             
                                      }
                                      
                                      
                                      ?>
                                      </select>
                              </div>


                              <div class="col-sm-6">
                                  <label>Total Poin</label><br>
                                  <input type="text" class="form-control" name="jumlah_poin" id="jumlah_poin" autocomplete="off" placeholder="Jumlah Poin" readonly="">
                              </div>
                          </div>


                         <div class="row">
                            <div class="col-sm-6">
                              <label> Tanggal</label><br>
                              <input type="text" class="form-control datepicker" name="tanggal" id="tanggal" autocomplete="off" placeholder="Tanggal" value="<?php echo date("Y-m-d"); ?>">
                          </div>

                           <div class="col-sm-6">
                              <label> Keterangan</label><br>
                              <input type="text" class="form-control" name="keterangan" id="keterangan" autocomplete="off" placeholder="Keterangan" >
                          </div>
                      </div>

                         <div class="row">

                              <div class="col-sm-6">
                                <label style="font-size:15px"> <b> Subtotal Poin</b></label><br>
                                <input style="height: 25px; width:90%; font-size:20px;"  type="text" name="subtotal" id="subtotal" class="form-control" placeholder="Subtotal" readonly="" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
                              </div>

                              <div class="col-sm-6">
                                <label style="font-size:15px"> <b> Sisa Poin </b></label><br>
                                <input style="height: 25px; width:90%; font-size:20px;"  type="text" name="sisa_poin" id="sisa_poin" class="form-control" placeholder="Sisa Poin" readonly="" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
                              </div>
                          </div>

                          <button  id="transaksi_baru" class="btn btn-info" style="display: none" style="font-size:15px">Transaksi Baru (Ctrl + M) </button>

                          <a href='' id="cetak_tukar" style="display: none;" class="btn btn-warning" target="blank"> Cetak Besar</a>
                          <a href='' id="cetak_tukar_kecil" style="display: none;" class="btn btn-danger" target="blank"> Cetak </a>

                          <button type="submit" id="simpan" class="btn btn-primary" style="font-size:15px">  Simpan (F10)</button>

                          <button id="batal_tukar" class="btn btn-warning" style="font-size:15px">  Batal (Ctrl + B) </button>




                      </div>
        </div>


    </div>  <!--end div class="row"-->

</div><!--end untuk membuat agar tampilan form terlihat rapih dalam satu tempat -->



<script>
//untuk form awal langsung ke kode barang focus
$(document).ready(function(){
    $("#kode_barang").focus();

});

</script>

<script type="text/javascript">
  //SELECT CHOSSESN    
$(".chosen").chosen({no_results_text: "Maaf, Data Tidak Ada!",search_contains:true});

</script>

<script>
  $(function() {
    $( ".datepicker" ).pickadate({ selectYears: 100, format: 'yyyy-mm-dd'});
  });

</script>

<script type="text/javascript">
  $(document).ready(function(){
      $(document).on('change','#kd_pelanggan',function(){

          var pelanggan = $(this).val();

          $.post("cek_aktif_pelanggan.php",{pelanggan:pelanggan}, function(info){
            if (info == 1) {

                          $(".chosen").chosen({no_results_text: "Maaf, Data Tidak Ada!"});      
                          $("#kd_pelanggan").val('').trigger("chosen:updated");
                          $("#kd_pelanggan").trigger("chosen:open");
                          $("#modal-pelanggan").modal('show');

                          $('#table-pelanggan').DataTable().destroy();

                            var dataTable = $('#table-pelanggan').DataTable( {
                            "processing": true,
                            "serverSide": true,
                            "info": false,
                            "language": {
                          "emptyTable":     "My Custom Message On Empty Table"
                      },
                            "ajax":{
                              url :"proses_terakhir_belanja.php", // json datasource
                               "data": function ( d ) {
                                  d.pelanggan = pelanggan;
                                  // d.custom = $('#myInput').val();
                                  // etc
                              },
                                  type: "post",  // method  , by default get
                              error: function(){  // error handling
                                $(".tbody").html("");
                                $("#table-pelanggan").append('<tbody class="tbody"><tr><th colspan="3"></th></tr></tbody>');
                                $("#table-pelanggan_processing").css("display","none");
                                
                              }
                            }
                      


                          } );

  


                }
                else
                { 

                      $.post("cek_poin_pelanggan.php",{pelanggan:pelanggan},function(data){

                    data = data.replace(/\s/g, '');
                      $("#jumlah_poin").val(tandaPemisahTitik(data));
                  });
                  

                }
          });





          

      });
  });
</script>



<!--Start Ajax Modal Cari-->
<script type="text/javascript" language="javascript" >
   $(document).ready(function() {
        var dataTable = $('#tabel_cari').DataTable( {
          "processing": true,
          "serverSide": true,
          "ajax":{
            url :"modal_barang_hadiah.php", // json datasource
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
              $(nRow).attr('satuan', aData[4]);
              $(nRow).attr('poin', aData[3]);


                 }

        });    
     
  });
 </script>
<!--Start Ajax Modal Cari-->


<!--Start tbs poin-->
<script type="text/javascript" language="javascript" >
   $(document).ready(function() {
        var dataTable = $('#tabel_tukar_poin').DataTable( {
          "processing": true,
          "serverSide": true,
          "ajax":{
            url :"table_tbs_tukar_poin.php", // json datasource
            type: "post",  // method  , by default get
            error: function(){  // error handling
              $(".employee-grid-error").html("");
              $("#tabel_tukar_poin").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
              $("#tabel_tukar_poin_processing").css("display","none");
              
            }
          },

        });    
     
  });
 </script>
<!--Start Ajax tbs poin-->


<!--START INPUT DARI MODAL CARI-->
<script type="text/javascript">
//AMBIL DAN INPUT KE FORM DARI CARI BARANG
$(document).on('click', '.pilih', function (e) {

  document.getElementById("kode_barang").value = $(this).attr('data-kode');
  $("#kode_barang").trigger('chosen:updated');
  document.getElementById("nama_barang").value = $(this).attr('nama-barang');
  document.getElementById("satuan").value = $(this).attr('satuan');
  document.getElementById("poin").value = $(this).attr('poin');

  var kode_barang = $("#kode_barang").val();

 $.post('cek_barang_tbs_tukar_poin.php',{kode_barang:kode_barang}, function(data){
  if(data == 1){
    alert("Anda Tidak Bisa Menambahkan Barang Yang Sudah Ada, Silakan Edit atau Pilih Barang Yang Lain !");

    $("#kode_barang").val('');
    $("#poin").val('');
    $("#kode_barang").trigger('chosen:updated');
    $("#kode_barang").trigger('chosen:open');
    $("#nama_barang").val('');
   }//penutup if

    });////penutup function(cek_kode_barang_tbs_penjualan)

  $.post("cek_stok_barang_hadiah.php",{kode_barang:kode_barang},function(data){

  $("#stok").val(data)
                                            
   });

  $('#myModal').modal('hide'); 
  $("#jumlah_barang").focus();


});

</script>




<script type="text/javascript">
// START script untuk pilih kode barang menggunakan chosen     
  $(document).ready(function(){

        $(document).on('change','#kode_barang',function(e){

              var kode_barang = $(this).val();
              var nama_barang = $('#opt-produk-'+kode_barang).attr("nama-barang");
              var satuan = $('#opt-produk-'+kode_barang).attr("satuan");
              var poin = $('#opt-produk-'+kode_barang).attr("poin");

               $.post("cek_barang_tbs_tukar_poin.php",{kode_barang:kode_barang},function(data){

                        if (data == 1) {
                              alert("Barang yang anda pilih sudah ada, silahkan pilih barang lain!");

                                $("#kode_barang").chosen("destroy");
                                $(".chosen").chosen({no_results_text: "Maaf, Data Tidak Ada!"});    
                                $("#satuan").val('');
                                $("#kode_barang").val('');
                                $("#poin").val('');
                                $("#nama_barang").val('');
                                $("#stok").val('');
                                $("#kode_barang").trigger('chosen:updated');
                                $("#kode_barang").trigger('chosen:open');

                         }
                         else
                        {  

                                $.post("cek_stok_barang_hadiah.php",{kode_barang:kode_barang},function(data){

                                 $("#stok").val(data);
                                });


                                $("#satuan").val(satuan);
                                $("#kode_barang").val(kode_barang);
                                $("#nama_barang").val(nama_barang); 
                                $("#poin").val(tandaPemisahTitik(poin));   
                        }
   // CEK STOK
           
     

                   

              });


                      

        });
  }); 
  // end script untuk pilih kode barang menggunakan chosen   
</script>



<script type="text/javascript">
    $(document).ready(function(){
        $(document).on('click','#submit_produk',function(e){

          var tanggal = $("#tanggal").val();
          var pelanggan = $("#kd_pelanggan").val();
          var kode_barang = $("#kode_barang").val();
          var nama_barang = $("#nama_barang").val();
          var satuan = $("#satuan").val();
          var stok = $("#stok").val();
          var poin = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#poin").val()))));
          if (poin == '') {
            poin = 0;
          };
          var jumlah_barang = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#jumlah_barang").val()))));
          if (jumlah_barang ==  '') {
            jumlah_barang = 0;
          };
          var poin_pelangan = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#jumlah_poin").val()))));
          if (poin_pelangan == '') {
            poin_pelangan = 0;
          };
          var subtotal_tbs = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#subtotal").val()))));
          if (subtotal_tbs == '') {
            subtotal_tbs = 0;
          };
          var sisa_poin = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#sisa_poin").val()))));
          if (sisa_poin == '') {
            sisa_poin = 0;
          }

          //hitung sisa poin
          
          var subtotal = parseInt(jumlah_barang, 10) * parseInt(poin ,10);

          var total_akhir = parseInt(subtotal_tbs, 10) + parseInt(subtotal ,10);

          var hitung_sisa_poin = parseInt(poin_pelangan, 10) - parseInt(total_akhir ,10);

          // hitung stok
          var hitung = parseInt(stok,10) - parseInt(jumlah_barang,10); 

           if (pelanggan == '') {
             alert("Pelanggan Harus di Isi !");
            $("#kd_pelanggan").trigger('chosen:updated');
            $("#kd_pelanggan").trigger('chosen:open');

           }
          else if (poin_pelangan == 0) {
             alert("Total Poin Pelanggan 0!");
           }
          else if (kode_barang == '') {
            alert("Anda belum memilih barang!");
            $("#kode_barang").trigger('chosen:updated');
            $("#kode_barang").trigger('chosen:open');
          }
          else if (jumlah_barang == 0) {
            alert("Jumlah Barang Harus di Isi dan tidak boleh nol!");
              $("#jumlah_barang").focus();
          }
          else if (poin == 0) {
            alert("Total Poin Produk 0!");

          }
          else if (hitung < 0) {
             alert("Jumlah Barang Melebihi Stok!");
              $("#jumlah_barang").val('');
              $("#jumlah_barang").focus();
          }
          else
          {

                              if (hitung_sisa_poin < 0) {
                                alert("Total Poin tidak mencukupi!");

                                                              $("#kode_barang").chosen("destroy");
                                                              $(".chosen").chosen({no_results_text: "Maaf, Data Tidak Ada!"});    
                                                              $("#satuan").val('');
                                                              $("#poin").val('');
                                                              $("#jumlah_barang").val('');
                                                              $("#kode_barang").val('');
                                                              $("#nama_barang").val('');
                                                              $("#kode_barang").trigger('chosen:updated');
                                                              $("#kode_barang").trigger('chosen:open');

                              }
                              else
                              {
                                           $("#subtotal").val(tandaPemisahTitik(total_akhir));
                                           $("#sisa_poin").val(tandaPemisahTitik(hitung_sisa_poin));

                                           $.post("prosestbstukarpoin.php",{kode_barang:kode_barang,nama_barang:nama_barang,jumlah_barang:jumlah_barang,poin:poin,subtotal:subtotal,satuan:satuan,pelanggan:pelanggan,tanggal:tanggal},function(data){


                                              $('#kd_pelanggan').prop('disabled', true).trigger("chosen:updated");

                                                $('#tabel_tukar_poin').DataTable().destroy();
                                                  var dataTable = $('#tabel_tukar_poin').DataTable( {
                                                      "processing": true,
                                                      "serverSide": true,
                                                      "ajax":{
                                                        url :"table_tbs_tukar_poin.php", // json datasource
                                                        type: "post",  // method  , by default get
                                                        error: function(){  // error handling
                                                          $(".employee-grid-error").html("");
                                                          $("#tabel_tukar_poin").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                                                          $("#tabel_tukar_poin_processing").css("display","none");
                                                          
                                                        }
                                                      },

                                                    });    

                                                $("#kode_barang").val('');
                                                $("#kode_barang").val('').trigger("chosen:updated");
                                                $("#kode_barang").trigger("chosen:open");
                                                $("#nama_barang").val('');
                                                $("#poin").val('');
                                                $("#satuan").val('');
                                                $("#jumlah_barang").val('');

                                               });

                              };
          }


            $("form").submit(function(){
            return false;  
            })
        });

        
    });
</script>


<script>
//untuk form awal langsung ke kode barang focus
$(document).ready(function(){
    $("#kode_barang").trigger('chosen:open');

      
            $.get("cek_pelanggan_poin.php",function(data){
            
            if (data != 0) {
                
                var pelanggan = data;

                $("#kd_pelanggan").val(data).trigger("chosen:updated");
                $('#kd_pelanggan').prop('disabled', true).trigger("chosen:updated");

                 $.get("cek_subtotal_poin.php",function(info){

                  $("#subtotal").val(tandaPemisahTitik(info));

                          $.post("cek_poin_pelanggan.php",{pelanggan:pelanggan}, function(data){

                                var hitung_sisa_poin = parseInt(data, 10) - parseInt(info ,10);
                                            data = data.replace(/\s+/g, '');

                                $("#jumlah_poin").val(tandaPemisahTitik(data));
                                
                                $("#sisa_poin").val(tandaPemisahTitik(hitung_sisa_poin));                    

                           });
                 });


            }
            else
            {
            
            $("#kd_pelanggan").trigger('chosen:open'); 
            }

      });


});

</script>


<script type="text/javascript">
    $(document).ready(function(){

        $(document).on('dblclick','.edit-jumlah',function(e){

                                    var id = $(this).attr("data-id");

                                    $("#text-jumlah-"+id).hide();

                                    $("#input-jumlah-"+id).attr("type", "text");


                                 });


                                     $(document).on('blur','.input_jumlah',function(e){

                                      var id = $(this).attr("data-id");
                                      var kode_barang = $(this).attr("data-kode");
                                      var jumlah_lama = $(this).attr("data-jumlah");
                                      var subtotal_lama = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#text-subtotal-"+id).text()))));
                                      var poin = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($(this).attr("data-poin")))));
                                      var jumlah_baru = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($(this).val()))));
                                      if (jumlah_baru == '') {
                                        jumlah_baru = 0;
                                      };
                                      var total_seluruh =  bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#subtotal").val()))));
                                      var poin_pelangan = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#jumlah_poin").val()))));
                                      if (poin_pelangan == '') {
                                        poin_pelangan = 0;
                                      };
                                      // hitung subtotal baru

                                      var subtotal = parseInt(jumlah_baru, 10) * parseInt(poin ,10);
                                      var total_akhir = parseInt(total_seluruh, 10) - parseInt(subtotal_lama ,10) + parseInt(subtotal, 10);
                                      var hitung_sisa_poin = parseInt(poin_pelangan, 10) - parseInt(total_akhir ,10);


                                      if (hitung_sisa_poin < 0) {

                                        alert("Total Poin tidak mencukupi!");

                                         $("#text-jumlah-"+id).show();
                                         $("#text-jumlah-"+id).text(tandaPemisahTitik(jumlah_lama));
                                         $("#input-jumlah-"+id).attr("type", "hidden");
                                         $("#input-jumlah-"+id).attr("data-jumlah", jumlah_lama);
                                        $("#input-jumlah-"+id).val(jumlah_lama);;

                                      }
                                      else if (jumlah_baru == 0) {
                                        alert("Jumlah Barang tidak boleh kosong atau nol!!");
                                         $("#text-jumlah-"+id).show();
                                         $("#text-jumlah-"+id).text(tandaPemisahTitik(jumlah_lama));
                                         $("#input-jumlah-"+id).attr("type", "hidden");
                                         $("#input-jumlah-"+id).attr("data-jumlah", jumlah_lama);
                                         $("#input-jumlah-"+id).val(jumlah_lama);;
                                      }
                                      else{

                                              // CEK STOK
                                              $.post("cek_stok_barang_hadiah.php",{kode_barang:kode_barang},function(data){

                                                // hitung stok
                                                var hitung = parseInt(data,10) - parseInt(jumlah_baru,10); 

                                                      if (hitung < 0) {
                                                        alert("Jumlah Barang Melebihi Stok!");
                                                         $("#text-jumlah-"+id).show();
                                                         $("#text-jumlah-"+id).text(tandaPemisahTitik(jumlah_lama));
                                                         $("#input-jumlah-"+id).attr("type", "hidden");
                                                         $("#input-jumlah-"+id).attr("data-jumlah", jumlah_lama);
                                                         $("#input-jumlah-"+id).val(jumlah_lama);

                                                      }
                                                      else
                                                      {

                                                                 $("#subtotal").val(tandaPemisahTitik(total_akhir));
                                                                 $("#sisa_poin").val(tandaPemisahTitik(hitung_sisa_poin));
                                                                 $("#text-subtotal-"+id).text(tandaPemisahTitik(subtotal));

                                                                 $("#text-jumlah-"+id).show();
                                                                 $("#text-jumlah-"+id).text(tandaPemisahTitik(jumlah_baru));
                                                                 $("#input-jumlah-"+id).attr("type", "hidden");
                                                                 $("#input-jumlah-"+id).attr("data-jumlah", jumlah_baru);

                                                                 $.post("update_tbs_tukar_poin.php",{jumlah_baru:jumlah_baru,id:id,subtotal:subtotal},function(info){
                                                
                                                                  });
                                                      }
                                                                      
                                                });

                                      }
                                  
                                    $("#kode_barang").trigger('chosen:open');
                                    
        });
                          

                          //fungsi hapus data TBS PENJUALAN
                        $(document).on('click','.btn-hapus-tbs',function(e){

                          var id = $(this).attr("data-id");
                          var nama_barang = $(this).attr("data-nama_barang");
                          var subtotal_lama = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#text-subtotal-"+id).text()))));
                          var total_seluruh =  bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#subtotal").val()))));
                          if (total_seluruh == '') {
                            total_seluruh = 0;
                          };
                          var poin_pelangan = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#jumlah_poin").val()))));
                          if (poin_pelangan == '') {
                              poin_pelangan = 0;
                              };

                                     

                        var total_akhir = parseInt(total_seluruh, 10) - parseInt(subtotal_lama ,10);        
                        var hitung_sisa_poin = parseInt(poin_pelangan, 10) - parseInt(total_akhir ,10);

                              var pesan_alert = confirm("Apakah Anda Yakin Ingin Menghapus "+nama_barang+""+ "?");

                            if (pesan_alert == true) {



                                      if (total_akhir == 0) {
                                                    
                                                          $("#kd_pelanggan").val('');
                                                          $("#jumlah_poin").val('');
                                                           $('#kd_pelanggan').prop('disabled', false).trigger("chosen:updated");
                                                          $("#kd_pelanggan").trigger("chosen:open");

                                                 
                                          }
                                          else
                                          {
                                            
                                                          $("#kode_barang").val('');
                                                          $("#kode_barang").val('').trigger("chosen:updated");
                                                          $("#kode_barang").trigger("chosen:open");
                                                          $("#nama_barang").val('');
                                                          $("#poin").val('');
                                                          $("#satuan").val('');
                                                          $("#jumlah_barang").val('');
                                       
                                          }

                                     $("#subtotal").val(tandaPemisahTitik(total_akhir));
                                      $("#sisa_poin").val(tandaPemisahTitik(hitung_sisa_poin));
                                    

                                    $.post("hapus_tbs_tukar_poin.php",{id:id},function(info){

                                                        $('#tabel_tukar_poin').DataTable().destroy();
                                                        var dataTable = $('#tabel_tukar_poin').DataTable( {
                                                            "processing": true,
                                                            "serverSide": true,
                                                            "ajax":{
                                                              url :"table_tbs_tukar_poin.php", // json datasource
                                                              type: "post",  // method  , by default get
                                                              error: function(){  // error handling
                                                                $(".employee-grid-error").html("");
                                                                $("#tabel_tukar_poin").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                                                                $("#tabel_tukar_poin_processing").css("display","none");
                                                                
                                                              }
                                                            },

                                                          });    
                                      
                                     });
                            }


                        });


                          //fungsi hapus data TBS PENJUALAN
                        $(document).on('keyup','#jumlah_barang',function(e){

                          var jumlah_barang = $(this).val();
                          var stok = $("#stok").val();

                          var hitung = parseInt(stok,10) - parseInt(jumlah_barang,10); 

                          if (hitung < 0) {
                            alert("Jumlah Barang Melebihi Stok!");
                              $(this).val('');
                              $(this).focus();
                          };

                        });

  });
</script>


<script type="text/javascript">
$(document).ready(function(){
      $(document).on('click','#simpan',function(e){
          var pelanggan = $("#kd_pelanggan").val();
          var poin_pelangan = $("#jumlah_poin").val();
          var tanggal = $("#tanggal").val();
          var keterangan = $("#keterangan").val();
          var total_poin = $("#subtotal").val();
          if (total_poin == '') {
            total_poin = 0;
          };
          var sisa_poin = $("#sisa_poin").val();

          if (total_poin == 0) {
                alert("Anda belum melakukan transaksi!");
                $("#kode_barang").val('');
                $("#kode_barang").trigger('chosen:updated');
                $("#kode_barang").trigger('chosen:open');
          }          
          else if (tanggal == '') {
                alert("Anda belum mengisi tanggal!");
                $("#tanggal").focus();

          }
          else
          {
            $("#transaksi_baru").show();
            $("#cetak_tukar").show();
            $("#cetak_tukar_kecil").show();
            $("#simpan").hide();
            $("#batal_tukar").hide();

            $.post("proses_simpan_tukar_poin.php",{pelanggan:pelanggan,poin_pelangan:poin_pelangan,total_poin:total_poin,sisa_poin:sisa_poin,tanggal:tanggal,keterangan:keterangan},function(data){

              $("#cetak_tukar").attr("href",'cetak_tukar_poin.php?no_faktur='+data+"&tanggal="+tanggal);
              $("#cetak_tukar_kecil").attr("href",'cetak_tukar_poin_kecil.php?no_faktur='+data+"&tanggal="+tanggal);
              $("#result").hide();
              $("#kd_pelanggan").val('');
              $('#kd_pelanggan').prop('disabled', true).trigger("chosen:updated");
              $("#jumlah_poin").val('');
              $("#subtotal").val('');
              $("#sisa_poin").val('');
              $("#stok").val('');
            });

          }


            var url = window.location.href;
             url = getPathFromUrl(url);
            history.pushState('', 'Toko',  url);

            function getPathFromUrl(url) {
              return url.split("?")[0];
            } 

            $("form").submit(function(){
            return false;  
            });

      });
});
</script>

<script> 
    shortcut.add("f2", function() {
        // Do something

        $("#kode_barang").trigger('chosen:open');

    });

    shortcut.add("f4", function() {
        // Do something

        $("#kd_pelanggan").trigger('chosen:open');

    });

    shortcut.add("f1", function() {
        // Do something

        $("#cari_produk").click();

    }); 

    shortcut.add("f3", function() {
        // Do something

        $("#submit_produk").click();

    }); 

    shortcut.add("f10", function() {
        // Do something

        $("#simpan").click();

    }); 

    shortcut.add("ctrl+b", function() {
        // Do something
       $("#batal_tukar").click();


    }); 

        shortcut.add("ctrl+m", function() {
        // Do something
        $("#transaksi_baru").click();


    }); 



</script>


<script type="text/javascript">
    $(document).ready(function(){
        $(document).on('click','#transaksi_baru',function(e){

              $("#result").show();
              $('#tabel_tukar_poin').DataTable().destroy();
              var dataTable = $('#tabel_tukar_poin').DataTable( {
                  "processing": true,
                  "serverSide": true,
                   "ajax":{
                     url :"table_tbs_tukar_poin.php", // json datasource
                     type: "post",  // method  , by default get
                     error: function(){  // error handling
                     $(".employee-grid-error").html("");
                     $("#tabel_tukar_poin").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                     $("#tabel_tukar_poin_processing").css("display","none");
                     
                        }
                      },
    });    
                                      

              $("#transaksi_baru").hide();
            $("#cetak_tukar_kecil").hide();
              $("#cetak_tukar").hide();
              $("#simpan").show();
              $("#batal_tukar").show();
              $("#kd_pelanggan").val('');
              $('#kode_barang').trigger("chosen:open");
              $('#kd_pelanggan').prop('disabled', false).trigger("chosen:updated");
              $('#kd_pelanggan').trigger("chosen:open");
              $('#kode_barang').val('');
              $("#jumlah_poin").val('');
              $("#subtotal").val('');
              $("#sisa_poin").val('');

            var url = window.location.href;
             url = getPathFromUrl(url);
            history.pushState('', 'Toko',  url);

            function getPathFromUrl(url) {
              return url.split("?")[0];
            } 

            $("form").submit(function(){
            return false;  
            });
        });


        $(document).on('click','#batal_tukar',function(e){
          
          var pesan_alert = confirm("Apakah anda yakin ingin membatalkan transaksi ini??");
          if (pesan_alert == true) {
              $.get("batal_transaksi_tukar_poin.php",function(data){
                 
                 var tabel_tukar_poin = $('#tabel_tukar_poin').DataTable();
                 tabel_tukar_poin.draw();
                 $("#kd_pelanggan").val('');
                $('#kode_barang').trigger("chosen:open");
                $('#kd_pelanggan').prop('disabled', false).trigger("chosen:updated");
                $('#kd_pelanggan').trigger("chosen:open");
                $('#kode_barang').val('');
                $("#jumlah_poin").val('');
                $("#subtotal").val('');
                $("#sisa_poin").val('');
              });
          };

        });
    });
</script>


<script type="text/javascript">
$(document).ready(function(){

function getUrl(sParam) {
      var sPageURL = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');

    for (var i = 0; i < sURLVariables.length; i++)
    {
        var sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] == sParam)
        {
            return decodeURIComponent(sParameterName[1]);
        }
    }
}
});
</script>

<script type="text/javascript">
  $(window).bind('beforeunload', function(){
  return 'Apakah Yakin Ingin Meninggalkan Halaman Ini ? Karena Akan Membutuhkan Beberapa Waktu Untuk Membuka Kembali Halaman Ini!';
});
</script>

<?php include 'footer.php'; ?>