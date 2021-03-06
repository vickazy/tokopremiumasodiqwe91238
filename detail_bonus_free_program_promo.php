<?php include 'session_login.php';


//memasukkan file session login, header, navbar, db.php
include 'header.php';
include 'navbar.php';
include 'sanitasi.php';
include 'db.php';

$id = stringdoang($_GET['id']);
$nama_program = stringdoang($_GET['nama']);
$kode_program = stringdoang($_GET['kode']);

$pilih_akses = $db->query("SELECT program_promo_free_tambah, program_promo_free_edit, program_promo_free_hapus FROM otoritas_master_data WHERE id_otoritas = '$_SESSION[otoritas_id]'");
$produk_promo = mysqli_fetch_array($pilih_akses);

 ?>

<div class="container">

 <span id="judul_bofree">
   <h3><b>Data Bonus Free produk</b></h3><hr>
 </span>
 <span id="judul_edit_bofree" style="display: none;">
   <h3><b>Edit Bonus Free produk</b></h3><hr>
 </span>

<a href="program_promo.php" class="btn btn-primary" data-toggle="tooltip" accesskey="k" id="kembali" class="btn btn-primary" data-placement='top' title='Klik untuk kembali ke utama.'><i class="fa fa-reply"></i> <u>K</u>embali</a>
<!--input untuk masuk ke data table-->
<input type="hidden" name="id_nya" id="id_nya" autocomplete="off" class="form-control" readonly="" value="<?php echo $id; ?>" style="height: 15px;">
<!--input untuk masuk ke data table-->
<?php 
if ($produk_promo['program_promo_free_tambah'] > 0) {
  echo '<button type="submit" id="tambah_free_produk" class="btn btn-success" style="background-color:#0277bd"><i class="fa fa-plus"> </i> Tambah</button>';
}
?>
<span id="tambh_free_produk" style="display: none;"><!--span untuk tambah produk-->
          <form class="form-group" role="form-horizontal" id="formfreeproduk" >
          <div class="row armun"><!--div class="row armun"-->
            
            <div class="col-sm-3"><!--/div class="col-sm-2 armun"-->
                <input type="hidden" name="id_program" id="id_program" autocomplete="off" class="form-control" readonly="" value="<?php echo $id ;?>" style="height: 15px;">
                <label>Nama Program</label>
                <input type="text" name="nama_program" id="nama_program" autocomplete="off" class="form-control" readonly="" value="<?php echo $nama_program ;?>" style="height: 15px; font-size: 120%;" placeholder="Nama Program">
            </div><!--div class="col-sm-2 armun"-->

            <div class="col-sm-2"><!--/div class="col-sm-2 armun"-->
               <label>Kode Free Produk</label>
                 <select style="font-size:15px; height:24px;" type="text" name="kode_barang" id="kode_barang" class="form-control chosen">
                  <option value="">SILAKAN PILIH...</option>
                    <?php 
                      include 'cache.class.php';
                        $c = new Cache();
                        $c->setCache('produk');
                        $data_c = $c->retrieveAll();

                        foreach ($data_c as $key) {
                          echo '<option id="opt-produk-'.$key['kode_barang'].'" value="'.$key['kode_barang'].'" data-kode="'.$key['kode_barang'].'" > '. $key['kode_barang'].' ( '.$key['nama_barang'].' ) </option>';
                        }

                        $cache_parcel = new Cache();
                        $cache_parcel->setCache('produk_parcel');
                        $data_parcel = $cache_parcel->retrieveAll();

                        foreach ($data_parcel as $key_parcel) {
                          echo '<option id="opt-produk-'.$key_parcel['kode_parcel'].'" value="'.$key_parcel['kode_parcel'].'" data-kode="'.$key_parcel['kode_parcel'].'"> '. $key_parcel['kode_parcel'].' ( '.$key_parcel['nama_parcel'].' ) </option>';
                        }

                    ?>
                </select>

                <input type="hidden" name="id_produk" id="id_produk" autocomplete="off" class="form-control" readonly="" style="height: 15px;">
                <input type="hidden" name="nama_produk" id="nama_produk" autocomplete="off" class="form-control" readonly="" style="height: 15px;">
                <input type="hidden" name="satuan" id="satuan" autocomplete="off" class="form-control" readonly="" style="height: 15px;">

            </div><!--div class="col-sm-2 armun"-->
            
            <div class="col-sm-2"><!--/div class="col-sm-2 armun"-->
            <label>Jumlah Fee Produk</label>
                <input type="text" name="qty" id="qty" autocomplete="off" class="form-control" style="height: 15px;"  placeholder="Qty Free Produk">
            </div><!--div class="col-sm-2 armun"-->

            <div class="col-sm-2"><!--/div class="col-sm-2 armun"-->
            <br>
              <button type="submit" id="tambah_produk" class="btn btn-primary" style="background-color:#0277bd"><i class="fa fa-plus"> </i> Submit</button>
            </div><!--div class="col-sm-2 armun"-->
          </div><!--/div class="row armun"-->
   
          
          </form>
  </span><!--Akhir span untuk tambah produk-->

<!--==============-->
<span id="edit_free_produk" style="display: none;"><!--span untuk EDIT produk-->
          <form class="form-group" role="form" id="formfreeproduk">
          <div class="row armun"><!--div class="row armun"-->
            
            <div class="col-sm-3"><!--/div class="col-sm-2 armun"-->
                <input type="hidden" name="id_program_edit" id="id_program_edit" autocomplete="off" class="form-control" readonly="" value="<?php echo $id ;?>" style="height: 15px;">
                <label>Nama Program</label>
                <input type="text" name="nama_program_edit" id="nama_program_edit" autocomplete="off" class="form-control" readonly="" value="<?php echo $nama_program ;?>" style="height: 15px; font-size: 120%;" placeholder="Nama Program">
            </div><!--div class="col-sm-2 armun"-->

            <div class="col-sm-2"><!--/div class="col-sm-2 armun"-->
                <label>Kode Free Produk</label>
                 <select style="font-size:15px; height:24px;" type="text" name="kode_barang_edit" id="kode_barang_edit" class="form-control chosen">
                  <option value="">SILAKAN PILIH...</option>
                    <?php 
                      include_once 'cache.class.php';
                        $c = new Cache();
                        $c->setCache('produk');
                        $data_c = $c->retrieveAll();

                        foreach ($data_c as $key) {
                          echo '<option id="opt-produk-'.$key['kode_barang'].'" value="'.$key['kode_barang'].'" data-kode="'.$key['kode_barang'].'" > '. $key['kode_barang'].' ( '.$key['nama_barang'].' ) </option>';
                        }

                        $cache_parcel = new Cache();
                        $cache_parcel->setCache('produk_parcel');
                        $data_parcel = $cache_parcel->retrieveAll();

                        foreach ($data_parcel as $key_parcel) {
                          echo '<option id="opt-produk-'.$key_parcel['kode_parcel'].'" value="'.$key_parcel['kode_parcel'].'" data-kode="'.$key_parcel['kode_parcel'].'"> '. $key_parcel['kode_parcel'].' ( '.$key_parcel['nama_parcel'].' ) </option>';
                        }

                    ?>
                </select>

                <input type="hidden" name="id_produk_edit" id="id_produk_edit" autocomplete="off" class="form-control" readonly="" style="height: 15px;">
            </div><!--div class="col-sm-2 armun"-->
            
            <div class="col-sm-2"><!--/div class="col-sm-2 armun"-->
                <label>Jumlah Free Produk</label>
                <input type="text" name="qty_edit" id="qty_edit" autocomplete="off" class="form-control" style="height: 15px; width: 45%;"  placeholder="Qty Free">

                <input type="hidden" name="id_edit" id="id_edit" autocomplete="off" class="form-control" readonly="" style="height: 15px;">

                <input type="hidden" name="satuan_edit" id="satuan_edit" autocomplete="off" class="form-control" readonly="" style="height: 15px;">

            </div><!--div class="col-sm-2 armun"-->

            <div class="col-sm-2"><!--/div class="col-sm-2 armun"-->
            <br>
              <button type="submit" id="submit_edit" class="btn btn-primary" style="background-color:#0277bd"><i class="fa fa-edit"> </i> EDIT</button>
            </div><!--div class="col-sm-2 armun"-->
          </div><!--/div class="row armun"-->
   
    </form>
</span><!--Akhir span untuk EDIT produk-->
<!--============-->

<!-- Modal Hapus data -->
<div id="modal_hapus" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Konfirmsi Hapus Data User</h4>
      </div>

      <div class="modal-body">
   
   <p>Apakah Anda yakin Ingin Menghapus Data ini ?</p>
   <form >
    <div class="form-group">
    <label> Nama Program :</label>
     <input type="text" id="nama_program_hapus" class="form-control" readonly=""> 
    </div>

    <div class="form-group">
    <label> Nama Produk :</label>
     <input type="text" id="nama_produk_hapus" class="form-control" readonly=""> 
     <input type="hidden" id="id_hapus" class="form-control" > 
    </div>
   
   </form> 

     </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-info" id="btn_jadi_hapus"><span class='glyphicon glyphicon-ok-sign'> </span> Ya</button>
        <button type="button" class="btn btn-warning" data-dismiss="modal"><span class='glyphicon glyphicon-remove-sign'> </span> Batal</button>
      </div>
    </div>

  </div>
</div><!-- end of modal hapus data  -->


<span id="table_le_kui">
  <div class="table-responsive"><!--membuat agar ada garis pada tabel disetiap kolom-->
  <table id="table_free_produk" class="table table-bordered table-sm">
    <thead>
      <th style="background-color: #4CAF50; color: white;"> Nama Program </th>
      <th style="background-color: #4CAF50; color: white;"> Nama Produk</th>
      <th style="background-color: #4CAF50; color: white;"> Qty </th>
<?php 
      if ($produk_promo['program_promo_free_edit'] > 0) {
        echo '<th style="background-color: #4CAF50; color: white;"> Edit </th>';
      }
     if ($produk_promo['program_promo_free_hapus'] > 0) {
       echo '<th style="background-color: #4CAF50; color: white;"> Hapus </th>';
      }
 ?>
      
    </thead>
  </table>
  </div> <!--/ responsive-->
</span>


</div> <!--/ container-->



<script type="text/javascript">
// START script untuk pilih kode barang menggunakan chosen     
  $("#kode_barang").change(function(){

    var kode_produk = $(this).val();
    $("#kode_barang").val(kode_produk);
    
    if (kode_produk != '') {
            $.getJSON('lihat_nama_produk_promo.php',{kode_produk:kode_produk}, function(json){
                    
              if (json == null){
                $('#id_produk').val('');
                $('#nama_produk').val('');
              }
              else {
                $('#id_produk').val(json.id);
                $('#nama_produk').val(json.nama_barang);
                $('#satuan').val(json.satuan)
              }
                                                  

                var id_produk = $("#id_produk").val();
                var nama_produk = $("#nama_produk").val();
                var satuan = $("#satuan").val();
                var id_program = $("#id_program").val();

               $.post('periksa_promo_disc_produk.php',{id_produk:id_produk,id_program:id_program}, function(data){
                    if (data == 1) {
                      alert("Anda Tidak Bisa Menambahkan Produk '"+nama_produk+"', Karena Produk Tersebut Sudah Ada Di Promo Diskon Produk !");
                       $("#kode_barang").val('');
                       $("#nama_produk").val('');
                       $("#kode_barang").trigger('chosen:updated');
                       $("#kode_barang").trigger('chosen:open');
                       $("#kode_barang").focus();
                    }
                    else{  

               $.post('cek_kode_bonus_free_produk.php',{id_produk:id_produk}, function(data){
                  if(data == 1){
                    alert("Anda Tidak Bisa Menambahkan Barang Yang Sudah Ada, Silakan Edit atau Pilih Barang Yang Lain !");
                    $("#kode_barang").val('');
                    $("#kode_barang").trigger('chosen:updated');
                    $("#kode_barang").trigger('chosen:open');
                    $("#id_produk").val('');
                  }//penutup if
                          
                });////penutup $.post('cek_kode_produk_program_promo.php',{id_produk:id_produk}, function(data)

             }

            }); // penutup $.getJSON('lihat_nama_produk_promo.php',{kode_produk:kode_produk}, function(json)
      });// END if (kode_produk != '')
  }

});

// end script untuk pilih kode barang menggunakan chosen   
</script>


<!-- untuk memasukan perintah javascript -->
<script type="text/javascript">

// jika dipilih, nim akan masuk ke input dan modal di tutup
  $(document).on('click', '.pilih', function (e) {
  document.getElementById("kode_produk").value = $(this).attr('data-kode');
  document.getElementById("nama_produk").value = $(this).attr('nama-barang');
  document.getElementById("id_produk").value = $(this).attr('id-barang');
  
  $('#myModal').modal('hide');
  });

</script> <!--tag penutup perintah java script-->


<script type="text/javascript">
// MENAMPILKAN FORM
  $(document).ready(function(){
    $("#tambah_free_produk").click(function(){
      $("#tambh_free_produk").show();
        $('#kode_barang').chosen('destroy');
        $("#kode_barang").chosen({no_results_text: "Maaf, Data Tidak Ada!",search_contains:true});    
         $("#tambah_free_produk").hide();
    });
  });
// /MENAMPILKAN FORM
</script>

<script type="text/javascript">
// MENAMPILKAN FORM

    $("#tambah_produk").click(function(){

     var id_program = $("#id_program").val();
     var id_produk = $("#id_produk").val();
     var satuan = $("#satuan").val();
     var qty = $("#qty").val();

                
                var nama_produk = $("#nama_produk").val();
                var satuan = $("#satuan").val();
                var id_program = $("#id_program").val();

        $.post('periksa_promo_disc_produk.php',{id_produk:id_produk,id_program:id_program}, function(data){
                    if (data == 1) {
                      alert("Anda Tidak Bisa Menambahkan Produk '"+nama_produk+"', Karena Produk Tersebut Sudah Ada Di Promo Diskon Produk !");
                       $("#kode_barang").val('');
                       $("#kode_barang").trigger('chosen:updated');
                       $("#kode_barang").trigger('chosen:open');
                       $("#nama_produk").val('');
                       $("#kode_barang").focus();
                    }
         });

        $.post('cek_kode_bonus_free_produk.php',{id_produk:id_produk}, function(data){
                    if(data == 1){
                    alert("Anda Tidak Bisa Menambahkan Barang Yang Sudah Ada, Silakan Edit atau Pilih Barang Yang Lain !");
                    $("#kode_barang").val('');
                    $("#kode_barang").trigger('chosen:updated');
                    $("#kode_barang").trigger('chosen:open');
                    $("#id_produk").val('');
                  }//penutup if
                          
                });////penutup $.post('cek_kode_produk_program_promo.php',{id_produk:id_produk}, function(data)


      if (id_program == '') {
        alert("Silakan isikan program promo terlebih dahulu.");
        $("#nama_program").val('');
      }     
      else if (id_produk == '') {
        alert("Silakan isikan kode produk promo terlebih dahulu.");
        $("#kode_produk").val('');
        $("#kode_produk").focus();
      } 
      else if (qty == '') {
        alert("Silakan isikan qty free terlebih dahulu.");
        $("#qty").focus();
      } 
      else
      {

        $.post("proses_detail_bonus_free_program_promo.php",{id_program:id_program,id_produk:id_produk,qty:qty,satuan:satuan},function(info) {
          $("#tambh_free_produk").hide();
          $("#tambah_free_produk").show();

          var table_free_produk = $('#table_free_produk').DataTable();
            table_free_produk.draw();

              $("#nama_produk").val('');
              $("#qty").val('');
              $("#kode_produk").val('');
       });
      }
      $("#formfreeproduk").submit(function(){
      return false;
      }); 

    });// end $("#tambh_program_promo").click(function()
// /MENAMPILKAN FORM
</script>
<!--====AKHIR TAMBAH =====-->


<!--========AWAL EDIT ====-->
<script type="text/javascript">
// MENAMPILKAN FORM Edit
$(document).on('click', '.edit', function (e) {

      var nama_program_edit = $(this).attr("data-kode_program");
      var id_program_edit = $(this).attr("data-id_program");
      var kode_barang_edit = $(this).attr("data-nama_produk");
      var id_produk_edit = $(this).attr("data-id_produk");
      var satuan_edit = $(this).attr("data-satuan");      
      var id_edit = $(this).attr("data-id");
      var qty_edit = $(this).attr("data-qty");

    $("#nama_program_edit").val(nama_program_edit);
    $("#id_program_edit").val(id_program_edit);
    $("#kode_barang_edit").chosen('destroy');
    $("#kode_barang_edit").val(kode_barang_edit);

    $("#id_produk_edit").val(id_produk_edit);
    $("#satuan_edit").val(satuan_edit);    
    $("#id_edit").val(id_edit);
    $("#qty_edit").val(qty_edit);

      $("#edit_free_produk").show();
      $("#judul_edit_bofree").show();
      $("#judul_bofree").hide();
      $("#tambah_free_produk").hide();
      $("#table_le_kui").hide();
      $("#tambh_free_produk").hide();
      $("#kode_barang_edit").chosen({no_results_text: "Maaf, Data Tidak Ada!",search_contains:true});



    });

    $("#submit_edit").click(function(){

    var id_program = $("#id_program_edit").val();
    var id_produk = $("#id_produk_edit").val();
    var id = $("#id_edit").val();
    var satuan = $("#satuan_edit").val();
    var qty = $("#qty_edit").val();
    var nama_produk = $("#nama_produk_edit").val();
                

               $.post('periksa_promo_disc_produk.php',{id_produk:id_produk,id_program:id_program}, function(data){
                    if (data == 1) {
                      alert("Anda Tidak Bisa Menambahkan Produk '"+nama_produk+"', Karena Produk Tersebut Sudah Ada Di Promo Diskon Produk !");
                       $("#kode_barang_edit").val('');
                       $("#nama_produk_edit").val('');
                       $("#kode_barang_edit").trigger('chosen:updated');
                       $("#kode_barang_edit").trigger('chosen:open');
                       $("#kode_barang_edit").focus();
                    }
                    else{  

               $.post('cek_kode_bonus_free_produk.php',{id_produk:id_produk}, function(data){
                  if(data == 1){
                    alert("Anda Tidak Bisa Menambahkan Barang Yang Sudah Ada, Silakan Edit atau Pilih Barang Yang Lain !");
                    $("#kode_barang_edit").val('');
                    $("#kode_barang_edit").trigger('chosen:updated');
                    $("#kode_barang_edit").trigger('chosen:open');
                    $("#id_produk").val('');
                  }//penutup if
                          
                });////penutup $.post('cek_kode_produk_program_promo.php',{id_produk:id_produk}, function(data)

             }

            }); // penutup $.getJSON('lihat_nama_produk_promo.php',{kode_produk:kode_produk}, function(json)

    if (id_program == '') {
        alert("Silakan isikan program promo terlebih dahulu.");
        $("#id_program_edit").val('');
      }     
      else if (id_produk == '') {
        alert("Silakan isikan kode produk promo terlebih dahulu.");
        $("#id_produk_edit").focus();
      }
      else if (qty == '') {
        alert("Silakan isikan qty terlebih dahulu.");
        $("#harga_free_edit").focus();
      } 
      else
      {


        $.post("edit_detail_bonus_free_program_promo.php",{id:id,id_program:id_program,id_produk:id_produk,qty:qty,satuan:satuan},function(info) {
          $("#edit_free_produk").hide();
          $("#tambah_free_produk").show();
          $("#table_le_kui").show();

            var table_free_produk = $('#table_free_produk').DataTable();
            table_free_produk.draw();

              $("#kode_barang_edit").val('');
              $("#qty_max_edit").val('');
              $("#id_produk_edit").val('');
       });

      }
      $("#formfreeproduk").submit(function(){
      return false;
      }); 
  });

// /MENAMPILKAN FORM Edt
</script>


<script type="text/javascript">
// START script untuk pilih kode barang menggunakan chosen     
  $("#kode_barang_edit").change(function(){
    var kode_produk = $(this).val();
    $("#kode_barang_edit").val(kode_produk);
    
    if (kode_produk != '') {
            $.getJSON('lihat_nama_produk_promo.php',{kode_produk:kode_produk}, function(json){
                    
              if (json == null){
                $('#id_produk_edit').val('');
                $('#nama_produk_edit').val('');
                $('#satuan_edit').val('');
              }
              else {
                $('#id_produk_edit').val(json.id);
                $('#nama_produk_edit').val(json.nama_barang);
                $('#satuan_edit').val(json.satuan);
              }
                                                  

                var id_produk = $("#id_produk_edit").val();
                var nama_produk = $("#nama_produk_edit").val();
                var satuan = $("#satuan_edit").val();
                var id_program = $("#id_program_edit").val();

               $.post('periksa_promo_disc_produk.php',{id_produk:id_produk,id_program:id_program}, function(data){
                    if (data == 1) {
                      alert("Anda Tidak Bisa Menambahkan Produk '"+nama_produk+"', Karena Produk Tersebut Sudah Ada Di Promo Diskon Produk !");
                       $("#kode_barang_edit").val('');
                       $("#nama_produk_edit").val('');
                       $("#kode_barang_edit").trigger('chosen:updated');
                       $("#kode_barang_edit").trigger('chosen:open');
                       $("#kode_barang_edit").focus();
                    }
                    else{  

               $.post('cek_kode_bonus_free_produk.php',{id_produk:id_produk}, function(data){
                  if(data == 1){
                    alert("Anda Tidak Bisa Menambahkan Barang Yang Sudah Ada, Silakan Edit atau Pilih Barang Yang Lain !");
                    $("#kode_barang_edit").val('');
                    $("#kode_barang_edit").trigger('chosen:updated');
                    $("#kode_barang_edit").trigger('chosen:open');
                    $("#id_produk").val('');
                  }//penutup if
                          
                });////penutup $.post('cek_kode_produk_program_promo.php',{id_produk:id_produk}, function(data)

             }

            }); // penutup $.getJSON('lihat_nama_produk_promo.php',{kode_produk:kode_produk}, function(json)

      });// END if (kode_produk != '')
  }

});

// end script untuk pilih kode barang menggunakan chosen   
</script>



<script type="text/javascript">
  //fungsi hapus data 
$(document).on('click', '.delete', function (e) {
    var nama_program = $(this).attr("data-nama_program");
    var nama_produk = $(this).attr("data-nama_produk");
    var id = $(this).attr("data-id");
    $("#nama_program_hapus").val(nama_program);
    $("#nama_produk_hapus").val(nama_produk);
    $("#id_hapus").val(id);
    $("#modal_hapus").modal('show');
    
    $(document).on('click', '#btn_jadi_hapus', function (e) {
    
    var id = $("#id_hapus").val();
    $.post("detail_free_produk_hapus.php",{id:id},function(data){
      if (data != "") {
    
            $("#modal_hapus").modal('hide');
            var table_free_produk = $('#table_free_produk').DataTable();
            table_free_produk.draw();

      }

    });
    
  });
});
</script>



<!--DATA TABLE MENGGUNAKAN AJAX-->
<script type="text/javascript" language="javascript" >
      $(document).ready(function() {

          var dataTable = $('#table_free_produk').DataTable( {
          "processing": true,
          "serverSide": true,
          "ajax":{
            url :"datatable_free_produk.php", // json datasource
           "data": function ( d ) {
                      d.id_nya = $("#id_nya").val();
                    },
            type: "post",  // method  , by default get
            error: function(){  // error handling
              $(".employee-grid-error").html("");
              $("#table_free_produk").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
              $("#employee-grid_processing").css("display","none");
            }
        },
            
            "fnCreatedRow": function( nRow, aData, iDataIndex ) {
                $(nRow).attr('class','tr-id-'+aData[5]+'');
            },
        });

        $("form").submit(function(){
        return false;
        });
        

      } );
</script>


<script type="text/javascript">
  //SELECT CHOSSESN    
  $(".chosen").chosen({no_results_text: "Maaf, Data Tidak Ada!",search_contains:true});    
</script>

<?php include 'footer.php'; ?>