<?php

class TagModel extends CI_Model
{
    public function auth($user_id)
    {
        return true;
    }
    
    /**
     * 名刺管理システムの共有タグリストを取得
     */
    public function getShareTag()
    {
        //セッションのログイン状態がtrueならば
        $this->load->library('session');
        if(@$this->session->userdata['logged_in'] === true)
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
            ->where('display', 1)
            ->get()
            ->result_array();
        
            return $db_result;
        }
        else
        {
            return NULL;
        }
    }
    
    /**
     * 名刺管理システムのマイタグリストを取得
     */
    public function getMyTag()
    {
        //セッションのログイン状態がtrueならば
        $this->load->library('session');
        if(@$this->session->userdata['logged_in'] === true)
        {
            $db_result = $this->db->select(array(
                'tag_code',
                'tag',
                'parent',
                'attribute'
            ))
            ->from('meishi_kanri_tag')
            ->order_by('tag', 'asc')
            ->where('attribute', @$this->session->userdata['user_id'])
            ->where('display', 1)
            ->get()
            ->result_array();
        
            return $db_result;
        }
        else
        {
            return NULL;
        }
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
     * 編集するタグを取得
     */
    public function getTag($edit_code)
    {
        //セッションのログイン状態がtrueならば
        $this->load->library('session');
        if(@$this->session->userdata['logged_in'] === true)
        {
            $db_result = $this->db->select(array(
                'tag_code',
                'tag',
                'parent',
                'attribute'
            ))
            ->from('meishi_kanri_tag')
            ->where('tag_code', $edit_code)
            ->get()
            ->result_array();
    
            return $db_result;
        }
        else
        {
            return NULL;
        }
    }
    
    /**
     * 編集時に親として指定できるタグを取得
     */
    public function getParentTag($edit_tag)
    {
        //セッションのログイン状態がtrueならば
        $this->load->library('session');
        if(@$this->session->userdata['logged_in'] === true)
        {
            //編集するタグが他のタグの親でなければ、親候補のタグを取得
            $query = $this->db->from('meishi_kanri_tag')->where('parent', $edit_tag['0']['tag_code'])->get();
            if($query->num_rows() < 1)
            {            
                $db_result = $this->db->select(array(
                    'tag_code',
                    'tag',
                    'parent',
                    'attribute'
                ))
                ->from('meishi_kanri_tag')
                ->where('tag_code !=', $edit_tag['0']['tag_code'])
                ->where('tag_code !=', $edit_tag['0']['parent'])
                ->where('attribute', $edit_tag['0']['attribute'])
                ->where('parent', NULL)
                ->where('display', 1)
                ->get()
                ->result_array();
                return $db_result;
            }
            else
            {
                return NULL;
            }
        }
        else
        {
            return NULL;
        }
    }
    
    /**
     * タグ情報を更新
     */
    public function updateTag($update_tag,$update_parent,$update_code)
    {
        //セッションのログイン状態がtrueならば
        $this->load->library('session');
        if(@$this->session->userdata['logged_in'] === true)
        {
            if(empty($update_parent))
            {
                $update_parent = NULL;
            }
            
            //更新用の配列を作成
            $update_data = array(
                'tag' => $update_tag,
                'parent' => $update_parent
            );
            //更新
            $this->db->update('meishi_kanri_tag',$update_data, "tag_code = $update_code");
        }
        else
        {
            return NULL;
        }
    }
    
    /**
     * タグを削除（非表示化、DB上にはデータ残る）
     * 親タグを非表示化した場合は子のparentをNULLにする
     */
    public function hideTag($hide_code)
    {
        //セッションのログイン状態がtrueならば
        $this->load->library('session');
        if(@$this->session->userdata['logged_in'] === true)
        {            
            //非表示用の配列を作成
            $hide_data = array(
                'display' => 0
            );
            //非表示
            $this->db->update('meishi_kanri_tag',$hide_data, "tag_code = $hide_code");
            
            //非表示化したタグの子タグがあればそのparentをNULLにする
            $hide_data = array(
                'parent' => NULL
            );
            $this->db->update('meishi_kanri_tag',$hide_data, "parent = $hide_code");
        }
        else
        {
            return NULL;
        }
    }
}