<?php
require_once (APPPATH . 'models/ApiModel.php');

/**
 * 特定の会社の名刺一覧に表示する情報の取得を行う
 */
class Rq0008Model extends ApiModel
{

    public function has_required_error()
    {
        return ($this->object == NULL 
                || is_null($this->object->search_word) 
                || is_null($this->object->user_id)
                || $this->object->search_word === ""
                || is_null($this->object->get_num)
                || is_null($this->object->scroll_count));
    }

    public function has_datatype_error()
    {
        return (!is_numeric($this->object->get_num)) || (!is_numeric($this->object->scroll_count));
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
            //if($this->object->user_id == "") {
               if(!(($this->object->user_id)==="") && !(($this->object->user_id)===NULL)){
            //名刺一覧の表示に用いるデータを取得
            $db_result = $this->db->select(array(
                'company_name',
                'position',
                'post',
                'lastname',
                'firstname',
                'exchange_date',
                'main_code',
                'user_last',
                'user_first',
                'user_position'
                ))
            ->from('meishi_kanri_main')
            ->join('meishi_kanri_user', 'meishi_kanri_main.holder_code = meishi_kanri_user.user_id', 'left')
            ->where('meishi_kanri_main.display', 1)
            ->where('meishi_kanri_main.holder_code', $this->object->user_id)
            ->where('meishi_kanri_main.company_name', $this->object->search_word)
            ->order_by('exchange_date desc')
            ->limit($this->object->get_num, ($this->object->get_num)*($this->object->scroll_count))
            ->get()
            ->result_array();
            } else {
                //名刺一覧の表示に用いるデータを取得
                $db_result = $this->db->select(array(
                    'company_name',
                    'position',
                    'post',
                    'lastname',
                    'firstname',
                    'exchange_date',
                    'main_code',
                    'user_last',
                    'user_first',
                    'user_position'
                ))
                ->from('meishi_kanri_main')
                ->join('meishi_kanri_user', 'meishi_kanri_main.holder_code = meishi_kanri_user.user_id', 'left')
                ->where('meishi_kanri_main.display', 1)
                //->where('meishi_kanri_main.holder_code', $this->object->user_id)
                ->where('meishi_kanri_main.company_name', $this->object->search_word)
                ->order_by('exchange_date desc')
                ->limit($this->object->get_num, ($this->object->get_num)*($this->object->scroll_count))
                ->get()
                ->result_array();
            }
            
            $count = 0;
            
            foreach($db_result as $row)
            {
                $result->object[$count]['company_name'] = $row['company_name'];
                $result->object[$count]['position'] = $row['position'];
                $result->object[$count]['post'] = $row['post'];
                $result->object[$count]['lastname'] = $row['lastname'];
                $result->object[$count]['firstname'] = $row['firstname'];
                //日付データを修正
                if(($row['exchange_date'] == "0000-00-00 00:00:00") || ($row['exchange_date'] == NULL))
                {
                    $result->object[$count]['exchange_date'] = '';
                }
                else
                {
                    $result->object[$count]['exchange_date']  = date('Y/m/d', strtotime($row['exchange_date']));
                }
                $result->object[$count]['main_code'] = $row['main_code'];
                $result->object[$count]['user_last'] = $row['user_last'];
                $result->object[$count]['user_first'] = $row['user_first'];
                $result->object[$count]['user_position'] = $row['user_position'];
                
                $count++;
            }        
            $result->code = 0;
        }
        else
        {
            $result->code = 4;
        }
    }
}