<?php

class Menu extends CI_Controller
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
        $this->load->view('Menu');
    }
    
    /**
     * メニュー画面を再読み込み
     */
    public function reload_menu()
    {
        //セッションのログイン状態がtrueならば
        if(@$this->session->userdata['logged_in'] === true)
        {
            $this->load->model('MenuModel');
            //メニュー画面の先頭の表示に必要なデータを取得
            $this->session->userdata['search_user'] = $this->session->userdata['user_id'];
            $this->session->userdata['search_tag'] = NULL;
            $this->session->userdata['search_word'] = NULL;
            $this->session->userdata['search_user_name'] = $this->session->userdata['user_last'].' '.$this->session->userdata['user_first'];
            $this->session->userdata['search_tag_name'] = 'タグ指定なし';
            $this->session->userdata['search_date_from'] = NULL;
            $this->session->userdata['search_date_to'] = NULL;
            $this->session->userdata['user_list'] = $this->MenuModel->getUsers();
            $this->session->userdata['tag_list'] = $this->MenuModel->getTags();
            $this->session->userdata['meishi_list'] = $this->MenuModel->getMeishi(1, NULL, $this->session->userdata['search_user'], NULL, NULL, NULL);
            $max_num = $this->MenuModel->getMaxPage(NULL, $this->session->userdata['user_id'], NULL, NULL, NULL);
            $this->session->userdata['max_page'] = $max_num['max_page'];
            $this->session->userdata['max_row'] = $max_num['max_row'];
            $this->session->userdata['page'] = 1;
            
            $data['meishi_list'] = $this->session->userdata['meishi_list'];
            $data['max_page'] = $this->session->userdata['max_page'];
            $data['max_row'] = $this->session->userdata['max_row'];
            $data['page'] = $this->session->userdata['page'];
            
            $this->load->view('Menu',$data);
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
     * 検索ボタンが押された時、データを取得して再度メニュー画面を構成
     */
    public function search()
    {
        //セッションのログイン状態がtrueならば
        if(@$this->session->userdata['logged_in'] === true)
        {
            //押されたボタンを取得
            $btn = $this->input->post('search_btn');
            //検索窓に入力された文字列を取得
            $this->session->userdata['search_word'] = $this->input->post('search_word');
            //セレクトボックスの値を取得
            $this->session->userdata['search_user'] = $this->input->post('search_user');
            $this->session->userdata['search_tag'] = $this->input->post('search_tag');
            //datepickerの値を取得
            $this->session->userdata['search_date_from'] = $this->input->post('search_date_from');
            $this->session->userdata['search_date_to'] = $this->input->post('search_date_to');
            $this->session->userdata['page'] = 1;
            //検索条件にヒットする名刺データの配列を取得
            $this->load->model('MenuModel');
            $this->session->userdata['meishi_list'] = $this->MenuModel->getMeishi($this->session->userdata['page'], $this->session->userdata['search_word'], $this->session->userdata['search_user'], $this->session->userdata['search_tag'], $this->session->userdata['search_date_from'], $this->session->userdata['search_date_to']);
            //検索結果の表示に必要な最大ページ数を取得
            $max_num = $this->MenuModel->getMaxPage($this->session->userdata['search_word'], $this->session->userdata['search_user'], $this->session->userdata['search_tag'], $this->session->userdata['search_date_from'], $this->session->userdata['search_date_to']);
            $this->session->userdata['max_page'] = $max_num['max_page'];
            $this->session->userdata['max_row'] = $max_num['max_row'];
            //セレクトボックスで選択された値の表示を取得
            foreach($this->session->userdata['user_list'] as $row)
            {
                if($row['user_id'] === $this->session->userdata['search_user'])
                {
                    $this->session->userdata['search_user_name'] = $row['user_last'].' '.$row['user_first'];
                    break;
                }
                else
                {
                    $this->session->userdata['search_user_name'] = '所有者';
                }
            }
            foreach($this->session->userdata['tag_list'] as $row)
            {
                if($row['tag_code'] === $this->session->userdata['search_tag'])
                {
                    $this->session->userdata['search_tag_name'] = $row['tag'];
                    break;
                }
                else
                {
                    $this->session->userdata['search_tag_name'] = 'タグ';
                }
            }
            /* 後で管理者のみ実行可能に変更
             * CSV出力ボタンが押されていたらCSV出力
            if($btn == 'output')
            {
                //検索条件にヒットする全ての名刺のCSVデータを出力
                $output = $this->MenuModel->outputMeishi($this->session->userdata['search_word'], $this->session->userdata['search_user'], $this->session->userdata['search_tag'], $this->session->userdata['search_date_from'], $this->session->userdata['search_date_to']);
                if(!($output == false))
                {
                    $data['output'] = $output;
                }
            }*/
            //ページ数を初期化
            //結果をメニュー画面に渡して画面表示
            $data['meishi_list'] = $this->session->userdata['meishi_list'];
            $data['max_page'] = $this->session->userdata['max_page'];
            $data['max_row'] = $this->session->userdata['max_row'];
            $data['page'] = $this->session->userdata['page'];
            $this->load->view('Menu',$data);
        }
        else
        {
            $this->load->model('LoginModel');
            $this->LoginModel->logout();
            $this->load->view('Login');
        }
    }
    
    /**
     * メニューで表示する名刺一覧を30件ずつ切り替える関数
     */
    public function replace()
    {        
        //セッションのログイン状態がtrueならば
        if(@$this->session->userdata['logged_in'] === true)
        {
            //切り替え先のページ数を取得
            $this->session->userdata['page'] = $this->input->post('prev_next');
            
            $this->load->model('MenuModel');
            $this->session->userdata['meishi_list'] = $this->MenuModel->getMeishi($this->session->userdata['page'], $this->session->userdata['search_word'], $this->session->userdata['search_user'], $this->session->userdata['search_tag'], $this->session->userdata['search_date_from'], $this->session->userdata['search_date_to']);
            
            //セレクトボックスで選択された氏名を取得
            foreach($this->session->userdata['user_list'] as $row)
            {
                if($row['user_id'] === $this->session->userdata['search_user'])
                {
                    $this->session->userdata['search_user_name'] = $row['user_last'].' '.$row['user_first'];
                    break;
                }
                else
                {
                    $this->session->userdata['search_user_name'] = '所有者';
                }
            }
            foreach($this->session->userdata['tag_list'] as $row)
            {
                if($row['tag_code'] === $this->session->userdata['search_tag'])
                {
                    $this->session->userdata['search_tag_name'] = $row['tag'];
                    break;
                }
                else
                {
                    $this->session->userdata['search_tag_name'] = 'タグ';
                }
            }
            //メニュー画面を表示
            $data['meishi_list'] = $this->session->userdata['meishi_list'];
            $data['max_page'] = $this->session->userdata['max_page'];
            $data['max_row'] = $this->session->userdata['max_row'];
            $data['page'] = $this->session->userdata['page'];
            $this->load->view('Menu', $data);
        }
        else
        {
            $this->load->model('LoginModel');
            $this->LoginModel->logout();
            $this->load->view('Login');
        }
    }
    
    /**
     * 名刺の詳細画面に移動する関数
     */
    public function to_detail()
    {
        //セッションのログイン状態がtrueならば
        if(@$this->session->userdata['logged_in'] === true)
        {
            $detail_code = $this->input->post('detail_code');
            //詳細画面に表示する名刺データを取得
            $this->load->model('DetailModel');
            $data['meishi_detail'] = $this->DetailModel->getDetail($detail_code);
            //詳細画面を表示
            $this->load->view('Detail', $data);
        }
        else
        {
            //ログアウトしてログイン画面を表示
            $this->load->model('LoginModel');
            $this->LoginModel->logout();
            $this->load->view('Login');
        }
    }
}
