<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller
{

    public function index()
    {
        if($this->session->userdata('username')){
            redirect('secretadmin/kasir');
        }
        $this->load->view('login');
    }
    
    public function cek_login(){
        $this->load->library('form_validation');
        $this->form_validation->set_rules(
            'username', 
            'Username', 
            'required'
        );
        $this->form_validation->set_rules(
            'password', 
            'Password', 
            'required|min_length[6]'
        );

        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');

        if ($this->form_validation->run() == false) {
            $this->index();
        } else {
            $this->db->where('username', $this->input->post('username'));
            $users = $this->db->get('users');
            if($users->num_rows()>0){
                $user = $users->row_array();
                if(password_verify($this->input->post('password'), $user['password'])){
                    $this->session->set_userdata($user);
                        redirect('secretadmin/kasir');
                }else{
                    redirect('auth' . "?alert=Password salah");
                }
            }else{
                redirect('auth' . "?alert=Username salah");
            }
        }
    }

  

    public function logout(){
        $this->session->sess_destroy();
        redirect('auth' . '?alert=Anda sudah berhasil keluar dari aplikasi');
    }
}
