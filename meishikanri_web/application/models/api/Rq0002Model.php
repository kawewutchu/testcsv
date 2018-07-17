<?php
require_once (APPPATH . 'models/ApiModel.php');

/**
 * 名刺管理システムユーザーのログアウトを行う
 */
class Rq0002Model extends ApiModel
{

    public function has_required_error()
    {
        return false;
    }

    public function has_datatype_error()
    {
        return false;
    }

    public function has_value_error()
    {
        return false;
    }

    public function exec(&$result)
    {
        //セッション情報を破棄
        $result->code = 0;
        $this->load->library('session');
        $this->session->sess_destroy();
    }
}