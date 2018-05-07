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
          <a href="<?=admin_url('Barang')?>">Order</a>
        </li>
        <li class="breadcrumb-item active">List Order</li>
      </ol>
      <div id="alert" style="display:none;">
        <div class="alert alert-success">
          <button type="button" class="close" aria-hidden="true">&times;</button>
          Berhasil
        </div>
      </div>
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-table"></i> Order History
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table" id="dataTable">
              <thead>
                <tr>
                  <th>Order Id</th>
                  <th>Total</th>
                  <th>Created</th>
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
                    "url": '<?php echo admin_url('kasir/order_history_json') ?>',
                    "type": "POST"
                },
                //Set column definition initialisation properties.
                "columns": [
                    {"data": "id", orderable: false, searchable: false},
                    {"data": "total", orderable: false, searchable: false},
                    {"data": "created", orderable: false, searchable: false},
                    {"data": "opsi", orderable: false, searchable: false}
                ],

            });

    $('#dataTable').on('click', '.btn-detail', function(){
      var id = $(this).attr('data-id');
      $('#modal-id').modal('show');
      $.ajax({
        type: "POST",
        url: "<?=base_url('api/kasir/get_order')?>",
        data: {id : id},
        dataType: "JSON",
        success: function (response) {
          var data = response.data;
          $('#modal-id #id_order').html(data.order.id);
          $('#modal-id #created').html(data.order.created);
          $('#modal-id #total').html(data.order.total);
          var html = '';
          $.each(data.detail_order, function (key, val) { 
               html += '<tr><td>' + val.kode + ' / ' + val.nama_barang +'</td>';
               html += '<td>' + val.qty + '</td>';
               html += '<td>' + val.harga + '</td>';
               html += '<td>' + val.total + '</td></tr>';
          });
console.log(data.detail_order[0]);

          $('#order_item').append(html);
        }
      });
    });
        });

    </script>
  </div>

<div class="modal fade" id="modal-id"  tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Detail Transaksi</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>

          <div class="modal-body">
            <div class="jumbotron" style="background:whitesmoke;">
                <div class="container">
                    <p>Id Order : <span id="id_order"></span></p> 
                    <p>Tanggal Order : <span id="created"></span></p>
                    <table class="table" style="width:100%">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Qty</th>
                            <th>Harga</th>
                            <th>Sub total</th>
                        </tr>
                    </thead>
                    <tbody id="order_item">
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="2">&nbsp;
                            </th>
                            <th>Total</th>
                            <th><span id="total"></span></th>
                        </tr>
                    </tfoot>
                    </table>
                </div>
            </div>
            </div>

          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
          </div>
          </div>
      </div>
    </div>

</body>
</html>
