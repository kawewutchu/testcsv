<?php
/**
 * 名刺画像スキャン時に追加情報（所有者、交換日）を取得する
 */

class UploadModel extends CI_Model
{
    public function auth()
    {
        return true;
    }
    
    /**
     * 名刺管理システムのユーザー情報を取得
     */
    public function getUserList()
    {
        $db_result = $this->db->select(array(
            'user_id',
            'user_last',
            'user_first'
        ))
        ->from('meishi_kanri_user')
        ->order_by('sort_code', 'asc')
        ->where('display', '1')
        ->get()
        ->result_array();
        
        return $db_result;
    }
    
    /**
     * 名刺管理システムの共有タグリストを取得
     */
    public function getShareTag()
    {
        $db_result = $this->db->select(array(
            'tag_code',
            'tag',
            'parent',
            'attribute'
        ))
        ->from('meishi_kanri_tag')
        ->order_by('tag', 'asc')
        ->where('attribute', 1)
        ->where('display', '1')
        ->get()
        ->result_array();
    
        return $db_result;
    }
    
    /**
     * 名刺管理システムのマイタグリストを取得
     */
    public function getMyTag($user_id)
    {
        $db_result = $this->db->select(array(
            'tag_code',
            'tag',
            'parent',
            'attribute'
        ))
        ->from('meishi_kanri_tag')
        ->order_by('tag', 'asc')
        ->where('attribute', $user_id)
        ->where('display', '1')
        ->get()
        ->result_array();
    
        return $db_result;
    }   
    
    /**
     * 新しいタグをタグマスタに追加
     */
    public function addNewTag($add_tag, $add_tag_attribute, $add_tag_parent)
    {
        $add_tag_data = array(
            'tag' => $add_tag,
            'attribute' => $add_tag_attribute,
            'parent' => $add_tag_parent
        );
        
       $this->db->insert('meishi_kanri_tag', $add_tag_data);
    }
    
    /**
     * 新しい名刺レコードをDBに追加
     */
    public function UploadMeishi($img_name_f, $img_name_b, $upload_exchange_date, $upload_user, $upload_tag)
    {
        //引数がNULLでなければ
        if($img_name_f != NULL || $img_name_b != NULL)
        {            
            //名刺データ入力用の配列を作成
            date_default_timezone_set('Asia/Tokyo');
            $input_main = array(
                'input_date' => date('Y-m-d H:i:s'),
                'exchange_date' => $upload_exchange_date,
                'holder_code' => $upload_user,
                'img_name_f' => $img_name_f,
                'img_name_b' => $img_name_b
            );
            
            //名刺データ入力
            if($this->db->insert('meishi_kanri_main',$input_main))
            {
                //タグ入力があればタグ登録
                if(!empty($upload_tag))
                {
                    //タグ入力用の配列を作成
                    $last_id = $this->db->insert_id();
                    $input_bypass = array(
                        'main_code' => $last_id
                    );
                    //タグ入力
                    foreach($upload_tag as $row)
                    {
                        $input_bypass['tag_code'] = $row;
                        $this->db->insert('meishi_kanri_bypass',$input_bypass);
                    }
                }
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }
}