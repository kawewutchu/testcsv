<?php
require_once (APPPATH . 'models/ApiModel.php');

/**
 * 名刺管理システムのユーザー情報の取得を行う    
 */
class Rq0003Model extends ApiModel
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
        //セッションにログイン情報があれば
        $this->load->library('session');
        if(!($this->session->userdata('user_id') === false))
        {
            //ユーザーの情報の配列を取得
            $db_result = $this->db->select(array(
                'user_id',
                'user_last',
                'user_first',
                'user_position'
            ))
            ->from('meishi_kanri_user')
            ->order_by('sort_code', 'asc')
            ->where_in('display', '1')
            ->get()
            ->result_array();
            foreach($db_result as $key => $row)
            {
                //ログインユーザーの名刺所有数を取得
                $possession = $this->db->from('meishi_kanri_main')
                ->where('holder_code', $row['user_id'])
                ->where('display', 1)
                ->get()
                ->num_rows();
                $result->object[$key]['user_id'] = $row['user_id'];
                $result->object[$key]['user_last'] = $row['user_last'];
                $result->object[$key]['user_first'] = $row['user_first'];
                $result->object[$key]['user_position'] = $row['user_position'];
                $result->object[$key]['possession_num'] = $possession;
            }
            $result->code = 0;
        }
        else 
        {
           $result->code = 4; 
        }
    }
}