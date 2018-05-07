<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Orders_m extends CI_Model {

    public $table = 'orders';

    function getById($id)
    {
        $query = $this->db->select('*')
                    ->where('id', $id)
                    ->get($this->table)->row();
        return $query;
    }

    // insert data
    function insert($data)
    {
        $this->db->insert($this->table, $data);
        $insert_id = $this->db->insert_id();

        return  $insert_id;
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
