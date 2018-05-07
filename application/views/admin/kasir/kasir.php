<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
  <?php $this->load->view('admin/partials/header'); ?> 
 <!-- Page level plugin CSS-->
  <link href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css" rel="stylesheet">

  <?php $this->load->view('admin/partials/navbar'); ?>

  <div class="content-wrapper">
    <div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="<?=admin_url('user')?>">Kasir</a>
        </li>
        <li class="breadcrumb-item active">List Kasir</li>
      </ol>
      <?php if(isset($_GET['alert'])){ ?>
        <div class="alert alert-success alert-dismissable">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          <?=$_GET['alert']?>
        </div>
      <?php } ?>
      <div id="alert" style="display:none;">
        <div class="alert alert-success">
          <button type="button" class="close" aria-hidden="true">&times;</button>
          Berhasil Menghapus Data
        </div>
      </div>
      <div id="error" style="display:none;">
        <div class="alert alert-danger">
          <button type="button" class="close" aria-hidden="true">&times;</button>
          Mohon Diisi 
        </div>
      </div>
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-table"></i> Data Kasir
        </div>
        <div class="card-body">
          <form action="">
            <div class="form-row">
            <form action="" id="form_tambah">
                <div class="form-group col-md-4">
                  <label for="kode">Kode/ Nama Produk</label>
                  <input type="text" maxlength="255" autocomplete="off" class="form-control" id="kode" placeholder="Kode/ Nama Produk" name="kode" required list="data-kode">
                  <datalist id="data-kode">
                    <span id="val-kode"></span>
                    <option value="">
                  </datalist>
                </div>
                <div class="form-group col-md-3">
                  <label for="jumlah">Jumlah</label>
                  <input type="number" min="1" class="form-control" id="jumlah" placeholder="jumlah" name="jumlah" required>
                </div>
                <div class="form-group col-md-5">
                <label for="tambah">&nbsp;</label>
                <button class="btn btn-primary btn-sm" id="tambah" type="button">Input</button>
                </div>
            </form>
                <table width="100%" id="table" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama Produk</th>
                            <th>Qty</th>
                            <th>Total</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="data-table">
                    </tbody>
                    <tfoot>
                      <th colspan="3">Total</th>
                      <th colspan="2"><span id="total">-</span></th>
                    </tfoot>
                </table>

                <button type="button" class="btn btn-sm btn-success" id="bayar">Bayar</button>

                <div id="order" class="col-md-12" style="display:none;">
                  <span id="frame-order"></span>
                </div>
          </div>
        </div>
        <!-- <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div> -->
      </div>
    </div>
    <!-- /.container-fluid-->
    <!-- /.content-wrapper-->

    <?php $this->load->view('admin/partials/footer'); ?>
    <!-- Page level plugin JavaScript-->
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>

    <!-- Custom scripts for this page-->
    <script>

      $('#bayar').click(function (e) {
        $.ajax({
          type: "GET",
          url: "<?=base_url('api/kasir/order')?>",
          dataType: "JSON",
          success: function (response) {
            getCart();
          }
        });
        var link = "<?=admin_url('kasir/order')?>";
        var html = '<hr><iframe src="'+link+'" frameborder="0" height="500px" width="100%"></iframe>';
        $('#frame-order').html(html);
        $('#order').show();
        $('#kode').val('');
        $('#jumlah').val('');
      });

      $('.close').click(function (e) { 
        $('.alert').hide();
      });
      $(document).ready(function () {
        getCart();
      });
      $('#kode').keyup(function (e) { 
        $.ajax({
          type: "POST",
          url: "<?=base_url('api/kasir/search')?>",
          data: {key : $(this).val()},
          dataType: "json",
          success: function (response) {
            var html = '';
            $.each(response.data, function (key, val) { 
               html += '<option value="'+val.kode+' / '+val.nama_roti+'">';
            });
            $('#val-kode').html(html);
          }
        });
      });

      $("#tambah").click(function (e) {
        var kode = $('#kode').val();
        var qty = $('#jumlah').val();
        if (qty !== '' || kode != '') {
          var myarr = kode.split(" / ");
          $.ajax({
            type: "POST",
            url: "<?=base_url('api/kasir/cart_add')?>",
            data: {kode : myarr[0], qty : qty},
            dataType: "json",
            success: function (response) {
              getCart();
            }
          }); 
        }else{
          $('#error').show();
          $('.alert').show();
        }
      });

    function getCart()
    {
      $.ajax({
          type: "GET",
          url: "<?=base_url('api/kasir/cart')?>",
          dataType: "json",
          success: function (response) {
            var data = response.data.cart;
            var total = response.data.total;
            var html = '';
            // var d = new Date();
            // var n = d.getMinutes() + '' + d.getSeconds() + '' + d.getMilliseconds();
            html += '<tbody id="data-table">';
            $.each(data, function (key, val) {
              html += '<tr id="'+key+'">'+
              '<td>'+val.kode+'</td>'+
              '<td>'+val.nama_roti+'</td>'+
              '<td>'+val.qty+'</td>'+
              '<td>'+val.total+'</td>'+
              '<td><a data-kode="'+val.kode+'" class="btn btn-danger hapus"><i class="fa fa-trash" style="color:white;"></i></a></td></tr>';
            });
            html += '</tbody>';
            $('#data-table').replaceWith(html);
            $('#total').text(total);
          }
        });
    }

    $('#table').on('click', '.hapus', function(){
      var kode = $(this).attr('data-kode');
      
      $.ajax({
        type: "POST",
        url: "<?=base_url('api/kasir/cart_del')?>",
        data: {kode : kode},
        dataType: "JSON",
        success: function (response) {
          getCart();
        }
      });
    });
    </script>
  </div>  
</body>
</html>
