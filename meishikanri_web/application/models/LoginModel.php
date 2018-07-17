<?php

class LoginModel extends CI_Model
{

    public function auth($user_id, $password)
    {
        
        //ID,パスワードを照合して、該当する行数を返す
        $query = $this->db->select(array(
                'user_last',
                'user_first'
            ))
        ->where('user_id', $user_id)
        ->where('user_password', $password)
        ->where('display', 1)
        ->get('meishi_kanri_user');

        //取得した行が０でなかったら
        if ($query->num_rows() > 0){
            $row = $query->row_array();
            //セッションにログイン情報を保持
            $newdata = array(
                'user_id' => $user_id,
                'user_last' => $row['user_last'],
                'user_first' => $row['user_first'],
                'user_list' => NULL,
                'tag_list' => NULL,
                'meishi_list' => NULL,
                'max_page' => 1,
                'max_row' => 1,
                'page' => 1,
                'search_user' => $user_id,
                'search_word' => NULL,
                'search_user_name' => $row['user_last'].' '.$row['user_first'],
                'search_tag' => NULL,
                'search_tag_name' => 'タグ指定なし',
                'search_date_from' => NULL,
                'search_date_to' => NULL,
                'nayose_list' => NULL,
                'nayose_page' => 1,
                'max_nayose_page' => 1,
                'max_nayose_row' => 1,
                'nayose_card' => NULL,
                'nayose_name' => NULL,
                'set_num' => 1,
                'max_set_num' => 1,
                'share_tag' => NULL,
                'my_tag' => NULL,
                'logged_in' => true
            );
            $this->session->set_userdata($newdata);
            return true;
        }
        return false;
    }
    
    public function logout()
    {
        $this->session->sess_destroy();
    }
   
}