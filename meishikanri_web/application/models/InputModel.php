<?php
class InputModel extends CI_Model
{
    public function auth()
    {
        return true;
    }
    
    /**
     * company_name, lastname, firstnameがNULLの名刺のデータを30件分取得する
     */
    public function getNoEntered($page)
    {
        //セッションのログイン状態がtrueならば
        $this->load->library('session');
        if($this->session->userdata['logged_in'] == TRUE)
        {
            //$pageぺージ目に表示する30件分の名刺データを取得
            $meishi_result = $this->db->select(array(
                'meishi_kanri_main.main_code',
                'GROUP_CONCAT(meishi_kanri_tag.tag SEPARATOR ",") AS tag',
                'img_name_f',
                'img_name_b',
                'exchange_date',
                'user_last',
                'user_first'
            ))
            ->from('meishi_kanri_main')
            ->join('meishi_kanri_user', 'meishi_kanri_main.holder_code = meishi_kanri_user.user_id', 'left')
            ->join('meishi_kanri_bypass','meishi_kanri_main.main_code = meishi_kanri_bypass.main_code', 'left')
            ->join('meishi_kanri_tag','meishi_kanri_bypass.tag_code = meishi_kanri_tag.tag_code','left')
            ->where('meishi_kanri_main.display', 1)
            ->group_start()
            ->where('company_name', NULL)
            ->or_where('lastname', NULL)
            ->or_where('firstname', NULL)
            ->group_end()
            ->group_by('meishi_kanri_main.main_code')
            ->order_by('exchange_date desc, input_date desc')
            ->limit(30, ($page-1)*30)
            ->get()
            ->result_array();
            
            //データを表示用に修正
            foreach($meishi_result as &$row)
            {
                //日付データを修正
                if(($row['exchange_date'] == "0000-00-00 00:00:00") || ($row['exchange_date'] == NULL))
                {
                    $row['exchange_date'] = '（未入力）';
                }
                else
                {
                    $row['exchange_date'] = date('Y/m/d', strtotime($row['exchange_date']));
                }
                //画像データを修正
                $file_name = $row['img_name_f'];
                $file_path = "./meishi/".$file_name;
                if(is_file($file_path))
                {
                    $row['img_name_f'] = "../meishi/".$file_name;
                }
                else
                {
                    $row['img_name_f'] = NULL;
                }
                $file_name = $row['img_name_b'];
                $file_path = "./meishi/".$file_name;
                if(is_file($file_path))
                {
                    $row['img_name_b'] = "../meishi/".$file_name;
                }
                else
                {
                    $row['img_name_b'] = NULL;
                }
                //タグの合計が130文字以上なら省略
                if(!empty($row['tag']))
                {
                    $tag_array = explode(',',$row['tag']);
                    foreach($tag_array as $key => $tag)
                    {
                        if($key == 0)
                        {
                            $temp = $tag;
                        }
                        else
                        {
                            $temp .= ', '.$tag;
                        }
                        if(mb_strlen($temp) > 130)
                        {
                            $row['tag'] .=', ... ';
                            break;
                        }
                        $row['tag'] = $temp;
                    }
                }
            }
            return $meishi_result;
        }
        else
        {
            return false;
        }
    }
    
    /**
     * company_name, lastname, firstnameがNULLの名刺の一覧を30件ずつ表示する時に必要な最大ページ数を返す
     */
    public function getMaxNoEntered()
    {
        //セッションのログイン状態がtrueならば
        $this->load->library('session');
        if($this->session->userdata['logged_in'] == TRUE)
        {
            $query = $this->db->where('display', 1)
            ->where('company_name', NULL)
            ->where('lastname', NULL)
            ->where('firstname', NULL)
            ->get('meishi_kanri_main');
            
            //取得した行数を30で除算して端数を切り上げた結果の値を取得
            $max_row_num = $query->num_rows();
            $max_page_num = ceil($max_row_num/30);
            
            $max_result = array(
                'max_row' => $max_row_num,
                'max_page' => $max_page_num
            );
            
            return $max_result;
        }
        else
        {
            return false;
        }
    }    
}