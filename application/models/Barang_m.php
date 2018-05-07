<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Barang_m extends CI_Model {

    public $table = 'barang';

    public function getById($id)
    {
        $query = $this->db->select(
                        'id, 
                        kode,
                        nama_roti,
                        harga'
                    )
                    ->where('id', $id)
                    ->get($this->table);
        return $query->row();
    }

    public function getByKode($kode)
    {
        $query = $this->db->select(
                        'id, 
                        kode,
                        nama_roti,
                        harga'
                    )
                    ->where('kode', $kode)
                    ->get($this->table);
        return $query->row();
    }

    public function getSearch($q)
    {
            $this->db->select('kode, nama_roti');
            $this->db->or_like('kode', $q);
            $this->db->or_like('nama_roti', $q);
            $data =  $this->db->get($this->table)->result();;
             
        return $data;
    }
    // insert data
    function insert($data)
    {
        $this->db->insert($this->table, $data);
    }

    // update data
    function update($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update($this->table, $data);
    }

    // delete data
    function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete($this->table);
    }

    // get total rows
    function total_rows() {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

}
