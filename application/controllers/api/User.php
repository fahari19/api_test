<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class User extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model', 'user');
    }

    public function index_get()
    {
        $id = $this->get('id');

        if ($id === null) {
            $user = $this->user->getUser();
        } else {
            $user = $this->user->getuser($id);
        }
        
                
        if($user){
            $this->response([
                'status'=> true,
                'data' => $user
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status'=> false,
                'message' => 'Id tidak ditemukan'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function index_delete()
    {
        $id = $this->delete('id');

        if ($id === null) {
            $this->response([
                'status'=> false,
                'message' => 'Id tidak valid'
            ], REST_Controller::HTTP_BAD_REQUEST);
        } else {
            if ($this->user->deleteUser($id) > 0) {
                // Ok
                $this->response([
                    'status'=> true,
                    'id' => $id,
                    'message' => 'User telah dihapus'
                ], REST_Controller::HTTP_NO_CONTENT);
            } else {
                // Error
                $this->response([
                    'status'=> false,
                    'message' => 'Id tidak ditemukan'
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        }
    }

    public function index_post()
    {
        $data =  [
            'nama' => $this->post('nama'),
            'username' => $this->post('username'),
            'email' => $this->post('email'),
            'nomor_hp' => $this->post('nomor_hp')
        ];

        if ($this->user->createUser($data) > 0)
        {
            $this->response([
                'status'=> true,
                'message' => 'User telah ditambah'
            ], REST_Controller::HTTP_CREATED);
        } else {
            $this->response([
                'status'=> false,
                'message' => 'Gagal menambahkan data'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }
}