<?php
class Input extends CI_Controller
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
        $this->load->view('Input');
    }

    /**
     * 未入力名刺一覧を表示する
     */
    public function no_entered()
    {
        //セッションのログイン状態がtrueならば
        if(@$this->session->userdata['logged_in'] === true)
        {
            //未入力名刺を取得
            $this->load->model('InputModel');
            $data['meishi_list'] = $this->InputModel->getNoEntered(1);
            $max_num = $this->InputModel->getMaxNoEntered();
            $data['max_page'] = $max_num['max_page'];
            $data['max_row'] = $max_num['max_row'];
            $data['page'] = 1;
            //未入力名刺一覧を表示
            $this->load->view('Input', $data);
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
     * 未入力名刺一覧画面の画面遷移
     */
    public function replace()
    {      
        //セッションのログイン状態がtrueならば
        if(@$this->session->userdata['logged_in'] === true)
        {
            $page = $this->input->post('prev_next');
            $max_page = $this->input->post('max_page');
            //画面遷移先に必要なデータを取得
            $this->load->model('InputModel');
            $data['meishi_list'] = $this->InputModel->getNoEntered($page);
            $data['page'] = $page;
            $max_num = $this->InputModel->getMaxNoEntered();
            $data['max_page'] = $max_num['max_page'];
            $data['max_row'] = $max_num['max_row'];
            //画面遷移
            $this->load->view('Input', $data);
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
     * 未入力名刺の情報入力ページを表示
     */
    public function input()
    {
        //セッションのログイン状態がtrueならば
        if(@$this->session->userdata['logged_in'] === true)
        {
            $edit_code = $this->input->post('input_code');

            if(is_null($edit_code) || empty($edit_code)) {
                $edit_code = $this->session->userdata['last_biz_id'];
                $this->session->unset_userdata('last_biz_id');
            }

            //入力フォームに表示する名刺データを取得
            $this->load->model('DetailModel');
            $data['meishi_detail'] = $this->DetailModel->getDetail($edit_code);
            $this->load->model('EditModel');
            $data['checked_tag'] = $this->EditModel->getChecked($edit_code);
            //ユーザーリストを取得
            $data['user_list'] = $this->session->userdata['user_list'];
            //共有タグリスト、マイタグリストを取得
            $this->load->model('TagModel');
            $this->session->userdata['share_tag'] = $this->TagModel->getShareTag();
            $this->session->userdata['my_tag'] = $this->TagModel->getMyTag();
            $data['share_tag'] = $this->session->userdata['share_tag'];
            $data['my_tag'] = $this->session->userdata['my_tag'];
            $pdf = substr_replace($data['meishi_detail']['0']['img_name_f'], '.pdf', -6);
            $data['pdf'] = substr_replace($pdf, '../pdf/', 0, 10);
            //編集画面を表示
            $this->load->view('Edit', $data);
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
     * 未入力名刺の削除
     */
    public function delete()
    {
        //セッションのログイン状態がtrueならば
        if(@$this->session->userdata['logged_in'] === true)
        {
            //非表示にする名刺のmain_codeを取得
            $hide_code = $this->input->post('input_code');            
            //非表示にした日時を取得
            $hide_today = date('Y/m/d H:i:s');
            //非表示
            $this->load->model('EditModel');
            $this->EditModel->hideMeishi($hide_code, $hide_today);
            
            //一覧画面を更新
            redirect('Input/no_entered');
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
