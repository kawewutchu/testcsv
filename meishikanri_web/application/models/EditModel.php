<?php

class EditModel extends CI_Model
{
    public function auth($user_id)
    {
        return true;
    }
    
    /**
     * 編集する名刺が持つタグ情報を取得
     */
    public function getChecked($edit_code)
    {
        //セッションのログイン状態がtrueならば
        $this->load->library('session');
        if($this->session->userdata['logged_in'] === true)
        {
            //名刺が持つタグのタグコードを取得
            $bypass_result = $this->db->select(array(
                'tag_code'
            ))
            ->from('meishi_kanri_bypass')
            ->where('main_code', $edit_code)
            ->get()
            ->result_array();
            if(!empty($bypass_result))
            {
                $checked_result = array();
                foreach($bypass_result as $row)
                {
                    array_push($checked_result, $row['tag_code']);
                }                
                return $checked_result;
            }
            else
            {
                return array();
            }
        }
        else
        {
            return NULL;
        }
    }
    
    /**
     * DB上の名刺データを更新する
     **/
    public function updateMeishi($update_company, $update_kana_company, 
            $update_position, $update_post, 
            $update_lastname, $update_firstname, 
            $update_kana_lastname, $update_kana_firstname, 
            $update_postal, 
            $update_address_a, $update_address_b, $update_address_c, $update_address_d, 
            $update_tel, $update_fax, $update_mobile, $update_mail, $update_url, $update_tag, 
            $update_exchange_date, $update_memo,  
            $update_holder_code, $update_sides, $update_today, $update_code)
    {
        //セッションのログイン状態がtrueならば
        $this->load->library('session');
        if($this->session->userdata['logged_in'] === true)
        {
        
            //更新用の配列を作成
            $update_data = array(
                'company_name' => $update_company,
                'kana_company_name' => $update_kana_company,
                'position' => $update_position,
                'post' => $update_post,
                'lastname' => $update_lastname,
                'firstname' => $update_firstname,
                'kana_lastname' => $update_kana_lastname,
                'kana_firstname' => $update_kana_firstname,
                'postal' => $update_postal,
                'address_a' => $update_address_a,
                'address_b' => $update_address_b,
                'address_c' => $update_address_c,
                'address_d' => $update_address_d,
                'tel' => $update_tel,
                'fax' => $update_fax,
                'mobile' => $update_mobile,
                'mail' => $update_mail,
                'url' => $update_url,
                'exchange_date' => $update_exchange_date,
                'memo' => $update_memo,
                'holder_code' => $update_holder_code,
                'update_date' => $update_today,
                'update_user' => $this->session->userdata['user_id']
            );
            
            //データ更新
            $this->db->update('meishi_kanri_main',$update_data, "main_code = $update_code");
            
            //画像の裏表入れ替え
            if($update_sides == 1)
            {
                $temp = $this->db->select(array(
                    'img_name_f',
                    'img_name_b'
                    ))
                    ->from('meishi_kanri_main')
                    ->where('main_code', $update_code)
                    ->get()
                    ->result_array();
                
                 foreach($temp as $row)
                 {
                     $temp_f = $row['img_name_f'];
                     $temp_b = $row['img_name_b'];
                 }
                
                $update_img = array(
                    'img_name_f' => $temp_b,
                    'img_name_b' => $temp_f
                );
                $this->db->update('meishi_kanri_main',$update_img,"main_code = $update_code");
            }
            
            //タグ更新
            //編集する名刺に関する中間テーブルのレコードを一旦削除
            $this->db->where('main_code', $update_code)->delete('meishi_kanri_bypass');
            //タグ入力に応じて新たに中間テーブルにレコードを作成
            if(!empty($update_tag))
            {
                foreach($update_tag as $row)
                {
                    //作成用の配列を作成
                    $insert_data = array(
                        'main_code' => $update_code,
                        'tag_code' => $row
                    );
                    //中間テーブルにレコードを作成
                    $this->db->insert('meishi_kanri_bypass',$insert_data);
                }
            }
            
            return true;
        }
        else
        {
            return NULL;
        }
    }
    
    /**
     * 引数
     * $hide_code：文字列：非表示にする名刺のmain_codeカラムの値
     * 機能
     * $hide_codeをmain_codeカラムに持つ名刺データをメニュー画面の一覧から削除
     */
    public function hideMeishi($hide_code, $hide_today)
    {
        //セッションのログイン状態がtrueならば
        $this->load->library('session');
        if($this->session->userdata['logged_in'] === TRUE)
        {
            //非表示用の配列を作成
            $hide_data = array(
                'display' => 0,
                'update_date' => $hide_today,
                'update_user' => $this->session->userdata['user_id']  
            );
            
            if($this->db->update('meishi_kanri_main', $hide_data, "main_code = $hide_code"))
            {
                return true;
            }else{
                return false;
            }
        }
        else
        {
            return NULL;
        }
    }
}
