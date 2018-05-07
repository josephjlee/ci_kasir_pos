<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Barang extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('barang_m');
        is_login();
    }

    public function index()
    {
        $this->load->view('admin/barang/list_barang');
    }

    public function create()
    {
        $this->load->view('admin/barang/create');
    }

    public function dataTable()
    {
        $this->load->library('datatables');
        $this->datatables->select('id, kode, nama_roti, harga');
        $this->datatables->from('barang');
        $results  = $this->datatables->generate();
        $results  = json_decode($results);
        $dataBarang = [];
        foreach ($results->data as $key => $value) {
            $dataBarang[] = 
            [
                'kode'      => $value->kode,
                'nama_roti' => $value->nama_roti,
                'harga'     => 'Rp ' . format_uang($value->harga) . ',-',
                'opsi'   => 
                    "<button class='btn-edit btn btn-warning btn-xs' data-id='$value->id'>Edit</button> "
            ];
        }
        $results->data = $dataBarang;
        header('Content-Type: application/json');
        echo json_encode($results);
    }
}
