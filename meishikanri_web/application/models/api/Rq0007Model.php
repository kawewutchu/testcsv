<?php
require_once (APPPATH . 'models/ApiModel.php');

/**
 * 名刺管理システムの会社名一覧に表示する情報の取得を行う
 */
class Rq0007Model extends ApiModel
{

    public function has_required_error()
    {
        return $this->object == NULL || is_null($this->object->search_word) || is_null($this->object->get_num) || is_null($this->object->scroll_count);
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
            //検索条件を形成
            $where = "(display".' = '.'1)';
            if(!(($this->object->search_word) === ""))
            {
                //検索語句の配列を作成
                $converted_word = mb_convert_encoding($this->object->search_word,'UTF-8','ASCII,JIS,UTF-8,EUC-JP,SJIS');
                $search_word = preg_split('/[ 　]+/u', $converted_word);
                    
                //各検索語句で部分一致検索を行うSQL文を作成
                $count = 0;
                foreach($search_word as $word)
                {
                    $where .= " AND (";
                        if($count == 0)
                        {
                            $where .= " company_name LIKE '%$word%'";
                            $count +=1;
                        }else{
                            $where .= " OR company_name LIKE '%$word%'";
                        }
                    $where .= ")";
                    $count = 0;
                }
            }

            if(!(($this->object->search_user)==="") && !(($this->object->search_user)===NULL))
            {
                //条件にユーザー検索を追加
                $search_user = $this->object->search_user;
                $where .= " AND (holder_code".' = '."'$search_user')";
            }
            
            //会社名一覧の配列を取得
            $db_result = $this->db->select(array(
                'company_name'
                ))
            ->from('meishi_kanri_main')
            ->where($where)
            ->where_not_in('company_name', '')
            ->distinct('company_name')
            ->order_by('company_name asc')
            ->limit($this->object->get_num, ($this->object->get_num)*($this->object->scroll_count))
            ->get()
            ->result_array();
            
            $count = 0;
            foreach($db_result as $row)
            {
                $result->object[$count]['company_name'] = $row['company_name'];
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