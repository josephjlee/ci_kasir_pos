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
          <a href="<?=admin_url('Barang')?>">Barang</a>
        </li>
        <li class="breadcrumb-item active">List Barang</li>
      </ol>
      <div id="alert" style="display:none;">
        <div class="alert alert-success">
          <button type="button" class="close" aria-hidden="true">&times;</button>
          Berhasil
        </div>
      </div>
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-table"></i> Data Barang <button class="btn btn-primary btn-xs pull-right" id="tambah">Tambah</button>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Kode</th>
                  <th>Nama Roti</th>
                  <th>Harga</th>
                  <th>Opsi</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
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
      $('.close').click(function (e) { 
        $('.alert').show();
      });
        var table;

        $(document).ready(function() {
            //datatables
            table = $('#dataTable').DataTable({ 
                "processing": true, //Feature control the processing indicator.
                "serverSide": true, //Feature control DataTables' server-side processing mode.
                "order": [], //Initial no order.
                // Load data for the table's content from an Ajax source
                "ajax": {
                    "url": '<?php echo admin_url('barang/dataTable') ?>',
                    "type": "POST"
                },
                //Set column definition initialisation properties.
                "columns": [
                    {"data": "kode"},
                    {"data": "nama_roti"},
                    {"data": "harga"},
                    {"data": "opsi", "searchable": false}
                ],

            });
        });

        $('#tambah').click(function (e) { 
            $('#modal-add').modal('show');
        });

        $("#dataTable").on( "click", ".btn-edit", function() {
            var sendData = {
              id : $(this).attr('data-id')
            };
            $.ajax({
              type: "POST",
              url: "<?=base_url('api/barang/show')?>",
              data: sendData,
              dataType: "json",
              success: function (response) {
                modal_edit(response.data);
                console.log(response);
                
              }
            });
            $('#modal-edit').modal('show');
        });

        function modal_edit(data){
            $('#modal-edit #id_edit').val(data.id);
            $('#modal-edit #kode_edit').val(data.kode);
            $('#modal-edit #nama_roti_edit').val(data.nama_roti);
            $('#modal-edit #harga_edit').val(data.harga);
        }
    </script>
  </div>

    <!-- Modal add-->
    <div class="modal fade" id="modal-add" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add Barang</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
              <form method="post">
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="kode">Kode</label>
                  <input type="text" maxlength="255" class="form-control" id="kode" placeholder="kode" name="kode" required>
                  <span class="error_kode badge badge-danger"></span>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="nama_roti">Nama Roti</label>
                  <input type="text" maxlength="255" class="form-control" id="nama_roti" placeholder="Nama Roti" name="nama_roti" required>
                  <span class="error_nama_roti badge badge-danger"></span>
                </div>
                <div class="form-group col-md-6">
                  <label for="harga">Harga</label>
                  <input type="number" min="1" class="form-control" id="harga" placeholder="Harga" name="harga" required>
                  <span class="error_harga badge badge-danger"></span>
                </div>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
            <button id="simpan" class="btn btn-primary" type="button">Simpan</button>
            </form>
            <script>

        $("#modal-add").on( "click", "#simpan", function() {
            var data = {
                kode      : $('#modal-add #kode').val(),
                nama_roti : $('#modal-add #nama_roti').val(),
                harga     : $('#modal-add #harga').val()
            }
            if (data.kode == '' || data.nama_roti == '' || data.harga == '') {
                if (data.kode == '') {
                  $('.error_kode').text("Harap Isi");
                }else{
                  $('.error_kode').hide();
                }
                if (data.nama_roti == '') {
                  $('.error_nama_roti').text("Harap Isi");
                }else{
                  $('.error_nama_roti').hide();
                }
                if (data.harga == '') {
                  $('.error_harga').text("Harap Isi");
                }else{
                  $('.error_harga').hide();
                }
            }else{
              $('.error_harga').hide();
              $('.error_nama_roti').hide();
              $('.error_kode').hide();
              
              $.ajax({
                  type: "POST",
                  url: "<?=base_url('api/barang/store')?>",
                  data: data,
                  dataType: "JSON",
                  success: function (response) {
                    if(response.status_code == 0){
                      console.log('error');
                      $('.error_kode').show();
                      $('.error_kode').text(response.status_message);
                    }else{
                      $('#modal-add #kode').val('');
                      $('#modal-add #nama_roti').val('');
                      $('#modal-add #harga').val('');
                      table.ajax.reload();
                      $('#modal-add').modal('hide');
                      $('.alert').show();
                      $('#alert').show();
                    }
                  }
              });
            }
        });
            </script>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal edit-->
    <div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Edit Barang</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
              <form action="">
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="kode">Kode</label>
                  <input type="text" maxlength="255" class="form-control" id="kode_edit" placeholder="kode" name="kode" required>
                  <input type="hidden" required id="id_edit">
                  <span class="error_kode_edit badge badge-danger"></span>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="nama_roti">Nama Roti</label>
                  <input type="text" maxlength="255" class="form-control" id="nama_roti_edit" placeholder="Nama Roti" name="nama_roti" required>
                  <span class="error_nama_roti_edit badge badge-danger"></span>
                </div>
                <div class="form-group col-md-6">
                  <label for="harga">Harga</label>
                  <input type="number" min="1" class="form-control" id="harga_edit" placeholder="Harga" name="harga" required>
                  <span class="error_harga_edit badge badge-danger"></span>
                </div>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
            <button id="update" class="btn btn-primary" type="button">Update</button>
          </div>
          <script>
            $("#modal-edit").on( "click", "#update", function() {
                var data = {
                    id        : $('#modal-edit #id_edit').val(),
                    kode      : $('#modal-edit #kode_edit').val(),
                    nama_roti : $('#modal-edit #nama_roti_edit').val(),
                    harga     : $('#modal-edit #harga_edit').val()
                }
                if (data.kode == '' || data.nama_roti == '' || data.harga == '') {
                    if (data.kode == '') {
                      $('.error_kode_edit').text("Harap Isi");
                    }else{
                      $('.error_kode_edit').hide();
                    }
                    if (data.nama_roti == '') {
                      $('.error_nama_roti_edit').text("Harap Isi");
                    }else{
                      $('.error_nama_roti_edit').hide();
                    }
                    if (data.harga == '') {
                      $('.error_harga_edit').text("Harap Isi");
                    }else{
                      $('.error_harga_edit').hide();
                    }
                }else{
                  $('.error_harga_edit').hide();
                  $('.error_nama_roti_edit').hide();
                  $('.error_kode_edit').hide();
                  
                  $.ajax({
                      type: "POST",
                      url: "<?=base_url('api/barang/update')?>",
                      data: data,
                      dataType: "JSON",
                      success: function (response) {
                        table.ajax.reload();
                        $('#modal-edit').modal('hide');
                        $('.alert').show();
                        $('#alert').show();
                      }
                  });
                }
            });
          </script>
        </div>
      </div>
    </div>
  
</body>
</html>
