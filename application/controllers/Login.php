<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function __construct() 
    {
        parent::__construct();
    }

	public function index()
	{
        check_already_login();
        $data['username'] = $this->session->userdata('username');
		$this->load->view('login', $data);
	}

    public function generate_password_testing2() {
    $this->load->helper('crypto'); // pastikan helper ini adalah tempat fungsi encrypt_password()
    $plaintext = '1234';
    $encrypted = encrypt_password($plaintext);
    echo "Encrypted password: <br>" . $encrypted;
}


// cek error password

    // public function test_encrypt() {
    //     $this->load->helper('crypto');
    //     $encrypted = encrypt_password('1234');
    //     echo "Encrypted: " . $encrypted . "<br>";
    //     echo "Decrypted: " . decrypt_password($encrypted);
    // }
    

	public function process() {
        $username = $this->input->post('username');
        $password_input = $this->input->post('password');

        
        $user = $this->User_model->get_user($username);
        
        if ($user) {
            $decrypted_password = decrypt_password($user->password_hash);
            
            if ($password_input === $decrypted_password) {
                $this->session->set_userdata('userid', $user->id);
                $this->session->set_userdata('username', $user->username);
                $this->session->set_flashdata('success', 'Login berhasil, selamat datang ' . $user->username . '!');
                redirect('dashboard');
            } else {
                $this->session->set_flashdata('error', 'Password salah');
            }
        } else {
            $this->session->set_flashdata('error', 'Username tidak ditemukan');
        }

        redirect('login');
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect('login');
    }
}
