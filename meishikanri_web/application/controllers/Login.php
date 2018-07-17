<?php

class Login extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->library('session');
        $this->load->database();
    }

    public function index()
    {
        $this->load->view('Login');
    }

    /**
     * ID、パスワード認証処理をして、画面遷移
     */
    public function auth()
    {           
            $user_id = $this->input->post('user_id');
            $password = $this->input->post('password');
            //認証に成功したら
            $this->load->model('LoginModel');
            if($this->LoginModel->auth($user_id, $password)){
                $this->load->model('MenuModel');
                //ドロップダウン用ユーザリストを取得
                $this->session->userdata['user_list'] = $this->MenuModel->getUsers();
                //ドロップダウン用タグリストを取得
                $this->session->userdata['tag_list'] = $this->MenuModel->getTags();
                //初期（最新３０件）の名刺データを取得
                $this->session->userdata['meishi_list'] = $this->MenuModel->getMeishi(1, NULL, $this->session->userdata['search_user'], NULL, NULL, NULL);
                //全ての名刺データを30件ずつ表示するのに必要なページ数を取得
                $max_num = $this->MenuModel->getMaxPage(NULL, $this->session->userdata['search_user'], NULL, NULL, NULL);
                $this->session->userdata['max_page'] = $max_num['max_page'];
                $this->session->userdata['max_row'] = $max_num['max_row'];
                //メニュー画面を表示
                $data['meishi_list'] = $this->session->userdata['meishi_list'];
                $data['max_page'] = $this->session->userdata['max_page'];
                $data['max_row'] = $this->session->userdata['max_row'];
                $data['page'] = $this->session->userdata['page'];
                $this->load->view('Menu', $data);
            }
            else
            {
                //ログイン画面を表示
                $data['logged_in'] = 1;
                $this->load->view('Login',$data);
            }
    }

    /**
     * ログアウト画面遷移（ログイン画面へ戻る）
     */
    public function logout()
    {
        $this->load->model('LoginModel');
        $this->LoginModel->logout();
        $this->index();
    }
}
