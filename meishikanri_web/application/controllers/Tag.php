<?php

class Tag extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->library('session');
        $this->load->database();
    }
    
    /**
     * タグ一覧を表示
     */
    public function get_tag()
    {
        //セッションのログイン状態がtrueならば
        if(@$this->session->userdata['logged_in'] === true)
        {
            $this->load->model('TagModel');
            $this->session->userdata['share_tag'] = $this->TagModel->getShareTag();
            $this->session->userdata['my_tag'] = $this->TagModel->getMyTag();
            
            $data['share_tag'] = $this->session->userdata['share_tag'];
            $data['my_tag'] = $this->session->userdata['my_tag'];
            $this->load->view('Tag', $data);
        }
        else
        {
            //ログアウトしてログイン画面へ
            $this->load->model('LoginModel');
            $this->LoginModel->logout();
            $this->load->view('Login');
        }
    }
    
    /**
     * 新しいタグを作成するページへ移動
     */
    public function new_tag()
    {
        //セッションのログイン状態がtrueならば
        if(@$this->session->userdata['logged_in'] === true)
        {
            //作成画面表示
            $data['share_tag'] = $this->session->userdata['share_tag'];
            $data['my_tag'] = $this->session->userdata['my_tag'];
            $data['user_id'] = $this->session->userdata['user_id'];
            $this->load->view('Tag_add', $data);
        }
        else
        {
            //ログアウトしてログイン画面へ
            $this->load->model('LoginModel');
            $this->LoginModel->logout();
            $this->load->view('Login');
        }
    }
    
    /**
     * 新しいタグを作成するページへ移動
     */
    public function add_tag()
    {
        //セッションのログイン状態がtrueならば
        if(@$this->session->userdata['logged_in'] === true)
        {
            $add_tag = $this->input->post('add_tag');
            $add_tag_attribute = $this->input->post('add_tag_attribute');
            $add_tag_parent = $this->input->post('add_tag_parent');
            
            $this->load->model('TagModel');
            $this->TagModel->addNewTag($add_tag, $add_tag_attribute, $add_tag_parent);
            
            $this->load->model('MenuModel');
            $this->session->userdata['tag_list'] = $this->MenuModel->getTags();
            
            $this->session->userdata['share_tag'] = $this->TagModel->getShareTag();
            $this->session->userdata['my_tag'] = $this->TagModel->getMyTag();
            
            $data['share_tag'] = $this->session->userdata['share_tag'];
            $data['my_tag'] = $this->session->userdata['my_tag'];
            $data['reset'] = true;
            $this->load->view('Tag', $data);
        }
        else
        {
            //ログアウトしてログイン画面へ
            $this->load->model('LoginModel');
            $this->LoginModel->logout();
            $this->load->view('Login');
        }
    }
    
    /**
     * タグを編集するページへ移動
     */
    public function edit_tag()
    {
        //セッションのログイン状態がtrueならば
        if(@$this->session->userdata['logged_in'] === true)
        {
            $edit_code = $this->input->post('edit_code');
            //編集画面表示
            $this->load->model('TagModel');
            $data['edit_tag'] = $this->TagModel->getTag($edit_code);
            $data['parent_tag'] = $this->TagModel->getParentTag($data['edit_tag']);
            $data['user_id'] = $this->session->userdata['user_id'];
            $this->load->view('Tag_edit', $data);
        }
        else
        {
            //ログアウトしてログイン画面へ
            $this->load->model('LoginModel');
            $this->LoginModel->logout();
            $this->load->view('Login');
        }
    }
    
    /**
     * タグを更新する
     */
    public function update_tag()
    {
        //セッションのログイン状態がtrueならば
        if(@$this->session->userdata['logged_in'] === true)
        {
            //タグの更新情報を取得
            $update_tag = $this->input->post('update_tag');
            $update_parent = $this->input->post('update_parent');
            $update_code = $this->input->post('update_code');
            //タグを更新
            $this->load->model('TagModel');
            $this->TagModel->updateTag($update_tag,$update_parent,$update_code);           
            //タグ一覧を表示
            $this->load->model('MenuModel');
            $this->session->userdata['tag_list'] = $this->MenuModel->getTags();
            $this->get_tag();
        }
        else
        {
            //ログアウトしてログイン画面へ
            $this->load->model('LoginModel');
            $this->LoginModel->logout();
            $this->load->view('Login');
        }
    }
    
    /**
     * タグを削除する
     */
    public function hide_tag()
    {
        //セッションのログイン状態がtrueならば
        if(@$this->session->userdata['logged_in'] === true)
        {
            $hide_code = $this->input->post('hide_code');
            //タグを削除
            $this->load->model('TagModel');
            $this->TagModel->hideTag($hide_code);
            //タグ一覧を表示
            $this->load->model('MenuModel');
            $this->session->userdata['tag_list'] = $this->MenuModel->getTags();
            $this->get_tag();
        }
        else
        {
            //ログアウトしてログイン画面へ
            $this->load->model('LoginModel');
            $this->LoginModel->logout();
            $this->load->view('Login');
        }
    }
}
