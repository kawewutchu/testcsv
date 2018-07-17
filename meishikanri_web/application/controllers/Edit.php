<?php

class Edit extends CI_Controller
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
     * 編集前の名刺情報を入力フォームに持った編集ページを表示
     */
    public function edit()
    {
        //セッションのログイン状態がtrueならば
        if(@$this->session->userdata['logged_in'] === true)
        {
            $edit_code = $this->input->post('edit_code');
            //入力フォームに表示する名刺データを取得
            $this->load->model('DetailModel');
            $data['meishi_detail'] = $this->DetailModel->getDetail($edit_code);
            $this->load->model('EditModel');
            $data['checked_tag'] = $this->EditModel->getChecked($edit_code);
            //利用者リストを取得
            $this->load->model('MenuModel');
            $data['user_list'] = $this->session->userdata['user_list'];
            //共有タグリスト、マイタグリストを取得
            $this->load->model('TagModel');
            $this->session->userdata['share_tag'] = $this->TagModel->getShareTag();
            $this->session->userdata['my_tag'] = $this->TagModel->getMyTag();
            $data['share_tag'] = $this->session->userdata['share_tag'];
            $data['my_tag'] = $this->session->userdata['my_tag'];
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
     * 名刺情報を更新する関数
     */
    public function update()
    {
        //セッションのログイン状態がtrueならば
        if(@$this->session->userdata['logged_in'] === true)
        {
            //pdf画像がある名刺（未入力名刺）が更新されたらそのpdfを削除
            $pdf = $this->input->post('pdf');
            if(isset($pdf))
            {
                $pdf = substr_replace($pdf, '', 0, 1);
                unlink($pdf);
            }
            //郵便番号、電話番号、FAX番号、携帯電話番号をハイフン繋ぎの文字列に結合
            $postal_ab = $this->input->post('update_postal');
            $update_postal = implode("-", array_filter($postal_ab));
            $tel_abc = $this->input->post('update_tel');
            $update_tel = implode("-", array_filter($tel_abc));
            $fax_abc = $this->input->post('update_fax');
            $update_fax = implode("-", array_filter($fax_abc));
            $mobile_abc = $this->input->post('update_mobile');
            $update_mobile = implode("-", array_filter($mobile_abc));
            //その他の入力情報を取得
            $update_company = $this->input->post('update_company');
            $update_kana_company = $this->input->post('update_kana_company');
            $update_position = $this->input->post('update_position');
            $update_post = $this->input->post('update_post');
            $update_lastname = $this->input->post('update_lastname');
            $update_firstname = $this->input->post('update_firstname');
            $update_kana_lastname = $this->input->post('update_kana_lastname');
            $update_kana_firstname = $this->input->post('update_kana_firstname');
            $update_address_a = $this->input->post('update_address_a');
            $update_address_b = $this->input->post('update_address_b');
            $update_address_c = $this->input->post('update_address_c');
            $update_address_d = $this->input->post('update_address_d');
            $update_mail = $this->input->post('update_mail');
            $update_url = $this->input->post('update_url');
            $update_tag = $this->input->post('update_tag');
            $update_exchange_data = $this->input->post('update_exchange_date');
            $update_memo = $this->input->post('update_memo');
            $update_holder_code = $this->input->post('update_holder');
            $update_sides = $this->input->post('sides');
            //更新日時(日本時間)を取得
            date_default_timezone_set('Asia/Tokyo');
            $update_today = date('Y/m/d H:i:s');
            //更新する名刺のmain_codeを取得
            $update_code = $this->input->post('update_code');
            //名刺情報を更新
            $this->load->model('EditModel');
            $this->EditModel->updateMeishi($update_company, $update_kana_company,
                $update_position, $update_post,
                $update_lastname, $update_firstname,
                $update_kana_lastname, $update_kana_firstname,
                $update_postal, $update_address_a, $update_address_b, $update_address_c, $update_address_d,
                $update_tel, $update_fax, $update_mobile, $update_mail, $update_url, $update_tag,
                $update_exchange_data, $update_memo, $update_holder_code,
                $update_sides, $update_today, $update_code);
            //詳細画面を表示
            $this->load->model('DetailModel');
            $data['meishi_detail'] = $this->DetailModel->getDetail($update_code);
            $this->load->view('Detail', $data);
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
     * 選択された名刺をメニュー画面の一覧から非表示にする
     */
    public function hide()
    {
        //セッションのログイン状態がtrueならば
        if(@$this->session->userdata['logged_in'] === true)
        {
            //非表示にする名刺のmain_codeを取得
            $hide_code = $this->input->post('hide_code');
            //非表示にした日時を取得
            $hide_today = date('Y/m/d H:i:s');
            //非表示
            $this->load->model('EditModel');
            $this->EditModel->hideMeishi($hide_code, $hide_today);
            //メニュー画面を表示
            $this->load->model('MenuModel');
            $data['meishi_list'] = $this->MenuModel->getMeishi(1, NULL, NULL, NULL, NULL, NULL);
            $max_num = $this->MenuModel->getMaxPage(NULL,NULL,NULL,NULL,NULL);
            $data['max_page'] = $max_num['max_page'];
            $data['max_row'] = $max_num['max_row'];$data['user_id'] = $this->input->post('hide_user');
            $data['search_word'] = NULL;
            $data['search_user'] = NULL;
            $data['page'] = 1;
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
}
