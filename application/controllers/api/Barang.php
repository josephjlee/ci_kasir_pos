<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once APPPATH.'core/API_Controller.php';

class Barang extends API_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('barang_m');
        is_login();
    }

    public function show_post()
    {
        $request = $this->_getRequestPost();

        $id = $this->_checkRequest($request, 'id', null, 'required', 1);

        $data = $this->barang_m->getById($id);

        if(!$data) {
            $this->response([
                'status_code'    => 0,
                'status_message' => 'Data tidak ditemukan',
                'data'           => null,
            ], 200);
        }
        $this->response([
            'status_code'    => 1,
            'status_message' => 'Berhasil mengambil data',
            'data'           => $data,
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
