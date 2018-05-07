<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once APPPATH.'core/API_Controller.php';

class Kasir extends API_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->library('cart');
        $this->load->model('barang_m');
        is_login();
    }

    public function search_post()
    {
        $request = $this->_getRequestPost();

        $key = $this->_checkRequest($request, 'key', null, 'required', 1);

        $data = $this->barang_m->getSearch($key);

        $this->response([
            'status_code'    => 1,
            'status_message' => 'Berhasil mengambil data',
            'data'           => $data,
        ], 200);
    }

    public function get_by_code_post()
    {
        $request = $this->_getRequestPost();

        $kode = $this->_checkRequest($request, 'kode', null, 'required', 1);

        $data = $this->barang_m->getByKode($kode);

        $this->response([
            'status_code'    => 1,
            'status_message' => 'Berhasil mengambil data',
            'data'           => $data,
        ], 200);
    }

    public function get_order_post()
    {
        $request = $this->_getRequestPost();
        $id = $this->_checkRequest($request, 'id', null, 'required', 1);
        $this->load->model('orders_m');
        $this->load->model('order_detail_m');
        $data['order'] = $this->orders_m->getById($id);
        $data['detail_order'] = $this->order_detail_m->getByOrderId($id);
        $this->response([
            'status_code'    => 1,
            'status_message' => 'Berhasil mengambil data',
            'data'           => $data,
        ], 200);
    }

    public function cart_get(){
        $total = [];
        $dataCart = $this->session->userdata('cart');
        if(count($dataCart)){
            foreach ($dataCart as $key => $value) {
                $total[]        = $value['total'];
                $dataCart[$key]['total'] = 'Rp ' . number_format($value['total']) . ',-' ;
            }
        }
        $data['cart'] = $dataCart;
        $this->session->set_userdata('total', array_sum($total));
        $data['total'] = $total ? 'Rp ' . number_format(array_sum($total)) . ',-' : 0 ;
        $this->response([
            'status_code'    => 1,
            'status_message' => 'Berhasil',
            'data'           => $data,
        ], 200);
    }

    public function cart_del_post()
    {
        $request = $this->_getRequestPost();
        $kode = $this->_checkRequest($request, 'kode', null, 'required', 1);
        $dataCart = $this->session->userdata('cart');
        foreach ($dataCart as $key => $value) {
            $cekKode = $value['kode'] === $kode ? 1 : 0;
            if($cekKode){
                unset($dataCart[$key]);
            }
        }
        $this->session->unset_userdata('cart');
        $this->session->set_userdata('cart', $dataCart);

        $this->response([
            'status_code'    => 1,
            'status_message' => 'Berhasil',
            'data'           => null,
        ], 200);
    }

    public function order_get()
    {
        $dataCart = $this->session->userdata('cart');
        $this->load->model('orders_m');
        $this->load->model('order_detail_m');
        $input = [
            'total'   => $this->session->userdata('total'),
            'created' => date('Y-m-d H:i:s'),
        ];

        $id_order = $this->orders_m->insert($input);
        foreach ($dataCart as $key => $value) {
            $inputDetail = [
                'order_id'    => $id_order,
                'kode'        => $value['kode'],
                'nama_barang' => $value['nama_roti'],
                'harga      ' => $value['harga'],
                'qty'         => $value['qty'],
                'total'       => $value['total'],
            ];
           $this->order_detail_m->insert($inputDetail);
        }
        $this->session->unset_userdata('cart');
        $this->session->unset_userdata('total');
        $data = [
            'id_order' => $id_order,
            'total'    => $input['total'],
            'item'     => $dataCart
        ];

        $this->session->set_userdata('order', $data);

        $this->response([
            'status_code'    => 1,
            'status_message' => 'Berhasil',
            'data'           => $data,
        ], 200);
    }

    public function cart_add_post()
    {
        $request = $this->_getRequestPost();

        $kode = $this->_checkRequest($request, 'kode', null, 'required', 1);
        $qty = $this->_checkRequest($request, 'qty', null, 'required', 1);

        $data = $this->barang_m->getByKode($kode);
        $input = [
            "kode"      => $kode,
            "nama_roti" => $data->nama_roti,
            "harga"     => $data->harga,
            "qty"       => $qty,
            "total"     => $qty * $data->harga,
        ];
        $cekKode = 0;
        $dataCart = $this->session->userdata('cart');

        if(count($dataCart) > 0){
            foreach ($dataCart as $key => $value) {
                $cekKode = $value['kode'] == $kode ? 1 : 0;
                if($cekKode){
                    $qty = $value['qty'] + $qty;
                    $update = [
                        "kode"      => $kode,
                        "nama_roti" => $value['nama_roti'],
                        "harga"     => $value['harga'],
                        "qty"       => $qty,
                        "total"     => $qty * $value['harga'],
                    ];
                    $dataCart[$key] = $update;
                }
            }
            $this->session->unset_userdata('cart');
            $this->session->set_userdata('cart', $dataCart);
        }

        if(!$cekKode){
            $count = count($dataCart);
            $dataCart[$count] = $input;
            $this->session->set_userdata('cart', $dataCart);
        }

        $this->response([
            'status_code'    => 1,
            'status_message' => 'Berhasil',
            'data'           => $dataCart,
        ], 200);
    }

    public function store_post()
    {
        $request = $this->_getRequestPost();

        $kode = $this->_checkRequest($request, 'kode', null, 'required', 1);
        $nama_roti = $this->_checkRequest($request, 'nama_roti', null, 'required', 1);
        $harga = $this->_checkRequest($request, 'harga', null, 'required', 1);

        $data = $this->barang_m->getByKode($kode);

        $input = [
            'kode'      => $kode,
            'nama_roti' => $nama_roti,
            'harga'     => $harga
        ];

        if($data) {
            $this->response([
                'status_code'    => 0,
                'status_message' => 'Kode Duplicate',
                'data'           => $input,
            ], 200);
        }
        $this->barang_m->insert($input);
        $this->response([
            'status_code'    => 1,
            'status_message' => 'Berhasil Menambah Barang',
            'data'           => $input,
        ], 200);
    }


    public function update_post()
    {
        $request = $this->_getRequestPost();

        $id = $this->_checkRequest($request, 'id', null, 'required', 1);
        $kode = $this->_checkRequest($request, 'kode', null, 'required', 1);
        $nama_roti = $this->_checkRequest($request, 'nama_roti', null, 'required', 1);
        $harga = $this->_checkRequest($request, 'harga', null, 'required', 1);


        $input = [
            'kode'      => $kode,
            'nama_roti' => $nama_roti,
            'harga'     => $harga
        ];

        $this->barang_m->update($id, $input);

        $this->response([
            'status_code'    => 1,
            'status_message' => 'Berhasil Mengedit Barang',
            'data'           => $input,
        ], 200);
    }

    public function delete_post()
    {
        $request = $this->_getRequestPost();

        $id = $this->_checkRequest($request, 'id', null, 'required', 1);        

        $data = $this->barang_m->delete($id);

        $this->response([
            'status_code'    => 1,
            'status_message' => 'Berhasil menghapus data',
            'data'           => null,
        ], 200);
    }
}
