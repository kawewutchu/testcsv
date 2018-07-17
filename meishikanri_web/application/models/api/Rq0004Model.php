<?php
require_once (APPPATH . 'models/ApiModel.php');

/**
 * 名刺管理システムの名刺一覧に表示する情報の取得を行う
 */
class Rq0004Model extends ApiModel
{

    public function has_required_error()
    {
        return ($this->object == NULL 
                || is_null($this->object->get_num) 
                || is_null($this->object->scroll_count));
    }

    public function has_datatype_error()
    {
        return (!is_numeric($this->object->get_num) 
                || !is_numeric($this->object->scroll_count));
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
            $where = "(meishi_kanri_main.display".' = '.'1)';
            if(!is_null($this->object->search_word))
            {
                //検索対象のカラム名の配列を作成
                $search_colum = preg_split("/,/" , "company_name,lastname,firstname,tel");    
                //検索語句の配列を作成
                $converted_word = mb_convert_encoding($this->object->search_word,'UTF-8','ASCII,JIS,UTF-8,EUC-JP,SJIS');
                $search_word = preg_split('/[ 　]+/u', $converted_word);    
                //各カラムに対して各検索語句で部分一致検索のAND検索を行うSQL文を作成
                $count = 0;    
                foreach($search_word as $word)
                {
                    $where .= " AND (";
                    foreach($search_colum as $colum){
                        if($count == 0)
                        {
                            $where .= " $colum LIKE '%$word%'";
                            $count +=1;
                        }else{
                            $where .= " OR $colum LIKE '%$word%'";
                        }
                    }
                    $where .= ")";
                    $count = 0;
                }
            }
            if(!(($this->object->search_user)===""))
            {
                //検索条件を追加
                $search_user = $this->object->search_user;
                $where .= " AND (holder_code".' = '."'$search_user')";
            }
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
            ->where($where)
            ->order_by('exchange_date desc, input_date desc, kana_lastname asc, kana_firstname asc')
            ->limit($this->object->get_num, ($this->object->get_num)*($this->object->scroll_count))
            ->get()
            ->result_array();
            foreach($db_result as $key => $row)
            {
                $result->object[$key]['company_name'] = $row['company_name'];
                $result->object[$key]['position'] = $row['position'];
                $result->object[$key]['post'] = $row['post'];
                $result->object[$key]['lastname'] = $row['lastname'];
                $result->object[$key]['firstname'] = $row['firstname'];
                //日付データを修正
                if(($row['exchange_date'] == "0000-00-00 00:00:00") || ($row['exchange_date'] == NULL))
                {
                    $result->object[$key]['exchange_date'] = '';
                }
                else
                {
                    $result->object[$key]['exchange_date']  = date('Y/m/d', strtotime($row['exchange_date']));
                }
                $result->object[$key]['main_code'] = $row['main_code'];
                $result->object[$key]['user_last'] = $row['user_last'];
                $result->object[$key]['user_first'] = $row['user_first'];
                $result->object[$key]['user_position'] = $row['user_position'];
            }
            $result->code = 0;
        }
        else
        {
            $result->code = 4;
        }
    }
}