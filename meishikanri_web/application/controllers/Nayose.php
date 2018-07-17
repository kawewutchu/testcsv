<?php

class Nayose extends CI_Controller
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
     * 名寄せページを表示する
     */
    public function get_name()
    {
        //セッションのログイン状態がtrueならば
        if(@$this->session->userdata['logged_in'] === true)
        {
           $this->load->model('NayoseModel');
           //名寄せ候補の氏名を取得
           $this->session->userdata['nayose_list'] = $this->NayoseModel->getNayoseList();
           //名寄せ候補一覧のページ数を取得
           $max_num = $this->NayoseModel->getNayosePage();
           $this->session->userdata['max_nayose_page'] = $max_num['max_page'];
           $this->session->userdata['max_nayose_row'] = $max_num['max_row'];
           $this->session->userdata['nayose_page'] = 1;
           $this->session->userdata['nayose_card'] = NULL;
           //名寄せ画面を表示
           $data['nayose_list'] = array_slice($this->session->userdata['nayose_list'],(($this->session->userdata['nayose_page']-1)*20),20);
           $data['max_nayose_page'] = $this->session->userdata['max_nayose_page'];
           $data['max_nayose_row'] = $this->session->userdata['max_nayose_row'];
           $data['nayose_page'] = $this->session->userdata['nayose_page'];
           $data['nayose_card'] = $this->session->userdata['nayose_card'];
           $this->load->view('Nayose', $data);
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
     * 名寄せ候補氏名一覧を遷移
     */
    public function replace_name()
    {
        //セッションのログイン状態がtrueならば
        if(@$this->session->userdata['logged_in'] === true)
        {
            $this->session->userdata['nayose_page'] = $this->input->post('replace_name');
            //名寄せ候補名刺一覧を初期化
            $this->session->userdata['nayose_card'] = NULL;
            //遷移先のページを表示
            $data['nayose_list'] = array_slice($this->session->userdata['nayose_list'],(($this->session->userdata['nayose_page']-1)*20),20);
            $data['max_nayose_page'] = $this->session->userdata['max_nayose_page'];
            $data['max_nayose_row'] = $this->session->userdata['max_nayose_row'];
            $data['nayose_page'] = $this->session->userdata['nayose_page'];
            $data['nayose_card'] = $this->session->userdata['nayose_card'];
            $this->load->view('Nayose', $data);
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
     * 名寄せ候補の組み合わせを取得
     */
    public function get_card()
    {
        //セッションのログイン状態がtrueならば
        if(@$this->session->userdata['logged_in'] === true)
        {
            $this->session->userdata['nayose_name'] = $this->input->post('nayose_name');
            $this->session->userdata['max_set_num'] = $this->input->post('set_num');
            
            //名寄せ候補の組み合わせを取得
            $this->load->model('NayoseModel');
            $this->session->userdata['nayose_card'] = $this->NayoseModel->getNayoseCard($this->session->userdata['nayose_name'], 1);
            //名寄せ候補の組み合わせを表示
            $data['nayose_list'] = array_slice($this->session->userdata['nayose_list'],(($this->session->userdata['nayose_page']-1)*20),20);
            $data['max_nayose_page'] = $this->session->userdata['max_nayose_page'];
            $data['max_nayose_row'] = $this->session->userdata['max_nayose_row'];
            $data['nayose_page'] = $this->session->userdata['nayose_page'];
            $data['nayose_card'] = $this->session->userdata['nayose_card'];
            $data['set_num'] = 1;
            $data['max_set_num'] = $this->session->userdata['max_set_num'];
            $this->load->view('Nayose', $data);
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
     * 名寄せ候補の組み合わせを遷移
     */
    public function replace_set()
    {
        //セッションのログイン状態がtrueならば
        if(@$this->session->userdata['logged_in'] === true)
        {
            $this->session->userdata['set_num'] = $this->input->post('replace_set');
            
            //遷移先の組み合わせを取得
            $this->load->model('NayoseModel');
            $this->session->userdata['nayose_card'] = $this->NayoseModel->getNayoseCard($this->session->userdata['nayose_name'], $this->session->userdata['set_num']);
            //名寄せ候補の組み合わせを表示
            $data['nayose_list'] = array_slice($this->session->userdata['nayose_list'],(($this->session->userdata['nayose_page']-1)*20),20);
            $data['max_nayose_page'] = $this->session->userdata['max_nayose_page'];
            $data['max_nayose_row'] = $this->session->userdata['max_nayose_row'];
            $data['nayose_page'] = $this->session->userdata['nayose_page'];
            $data['nayose_card'] = $this->session->userdata['nayose_card'];
            $data['set_num'] = $this->session->userdata['set_num'];
            $data['max_set_num'] = $this->session->userdata['max_set_num'];
            $this->load->view('Nayose', $data);
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
     * 名刺の名寄せ処理を行う
     */
    public function Nayose()
    {
        //セッションのログイン状態がtrueならば
        if(@$this->session->userdata['logged_in'] === true)
        {
            $nayose_set = $this->input->post('nayose_set');
            $do = $this->input->post('nayose_btn');
            $this->load->model('NayoseModel');
            //名寄せ処理を実行
            $this->NayoseModel->doNayose($nayose_set, $do);
            //名寄せマスタのチェック
            $result = $this->check_nayose();
            if($result === true)
            {
                //名寄せ候補の組み合わせを初期化
                $this->session->userdata['nayose_card'] = NULL;
                $this->session->userdata['max_set_num'] = 1;
                //名寄せ候補一覧を取得
                $this->session->userdata['nayose_list'] = $this->NayoseModel->getNayoseList();
                //名寄せ候補一覧のページ数を取得
                $max_num = $this->NayoseModel->getNayosePage();
                $this->session->userdata['max_nayose_page'] = $max_num['max_page'];
                $this->session->userdata['max_nayose_row'] = $max_num['max_row'];
                //直前に表示していた名寄せ候補一覧のページがなくなっていれば
                if($max_num['max_page'] < $this->session->userdata['nayose_page'])
                {
                    //ページを1ページ前に戻す
                    $this->session->userdata['nayose_page']--;
                }
                else
                {
                    //直前に処理した名刺と同姓同名の未処理の組み合わせがあれば取得
                    foreach($this->session->userdata['nayose_list'] as $row)
                    {
                        if($this->session->userdata['nayose_name'] == $row['lastname'].' '.$row['firstname'])
                        {
                            //名寄せ候補の組み合わせを取得
                            $this->session->userdata['max_set_num'] = $row['set_num'];
                            $this->session->userdata['nayose_card'] = $this->NayoseModel->getNayoseCard($this->session->userdata['nayose_name'], 1);
                            break;
                        }
                    }
                }
                //名寄せ画面を表示
                $data['nayose_list'] = array_slice($this->session->userdata['nayose_list'],(($this->session->userdata['nayose_page']-1)*20),20);
                $data['max_nayose_page'] = $this->session->userdata['max_nayose_page'];
                $data['max_nayose_row'] = $this->session->userdata['max_nayose_row'];
                $data['nayose_page'] = $this->session->userdata['nayose_page'];
                $data['nayose_card'] = $this->session->userdata['nayose_card'];
                $data['set_num'] = 1;
                $data['max_set_num'] = $this->session->userdata['max_set_num'];
                $this->load->view('Nayose', $data);
            }
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
     * 名寄せマスタをチェックする
     */
    public function check_nayose()
    {
        //セッションのログイン状態がtrueならば
        if(@$this->session->userdata['logged_in'] === true)
        {
            $this->load->model('NayoseModel');
            $result = $this->NayoseModel->checkNayose();
            if($result === 'finished')
            {
                return true;
            }
            else if($result === 'checking')
            {
                return $this->check_nayose();
            }
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
