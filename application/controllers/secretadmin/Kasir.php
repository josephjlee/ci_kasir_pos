<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kasir extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // $this->load->model('kasir_m');
        is_login();
    }

    public function index()
    {
        $this->load->view('admin/kasir/kasir');
    }

    public function order_history()
    {
        $this->load->view('admin/kasir/order_history');
    }

    public function order_history_json()
    {
        $this->load->library('datatables');
        $this->datatables->select('orders.*');
        // $this->datatables->select('orders.*, 
        //     order_detail.nama_barang, 
        //     order_detail.harga,
        //     order_detail.qty,
        //     order_detail.jumlah,
        //     order_detail.total as sub_total,
        // ');
        $this->datatables->from('orders');
        // $this->datatables->join('order_detail', 'order_detail.order_id = order.id');
        $results  = $this->datatables->generate();
        $results  = json_decode($results);
        $dataOrder = [];
        foreach ($results->data as $key => $value) {
            $dataOrder[] = 
            [
                'id'      => $value->id,
                'total'   => 'Rp ' . format_uang($value->total),
                'created' => $value->created,
                'opsi'   => 
                    "<button class='btn-detail btn btn-success btn-xs' data-id='$value->id'>Detail</button> "
            ];
        }
        $results->data = $dataOrder;
        header('Content-Type: application/json');
        echo json_encode($results);
    }

    public function order()
    {
        $this->session->unset_userdata('cart');
        $this->load->view('admin/kasir/order');
    }
}
