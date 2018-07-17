<?php
require_once (APPPATH . 'models/ApiModel.php');

/**
 * 名刺管理システムユーザーのログイン認証を行う 
 */
class Rq0001Model extends ApiModel
{

    public function has_required_error()
    {
        return $this->object == null || is_null($this->object->user_id) || is_null($this->object->password);
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
        //IDとパスワードが一致するデータを取得
        $db_result = $this->db->where('user_id', $this->object->user_id)
            ->where('user_password', $this->object->password)
            ->where('display', 1)
            ->get('meishi_kanri_user');
        //データがなければfalseを返却
        if ($db_result->num_rows() == 0) {
            $result->code = 0;
            $result->object['status'] = false;
            return;
        }
        
        // セッションへ認証情報を格納
        $this->load->library('session');
        $newdata = array('user_id' => $this->object->user_id);
        $this->session->set_userdata($newdata);
        
        $result->code = 0;
        $result->object['status'] = true;
    }
}