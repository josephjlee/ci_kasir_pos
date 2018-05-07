<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Users_m extends CI_Model {

    public $table = 'users';

    public function getById($id)
    {
        $query = $this->db->select(
                        'id, 
                        username'
                    )
                    ->where('id', $id)
                    ->get($this->table);
        return $query->row();
    }

    public function getPassword($id)
    {
        $query = $this->db->select(
                        'password'
                    )
                    ->where('id', $id)
                    ->get($this->table);
        $q = $query->row();
        return $q->password;
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

}
