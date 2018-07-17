<?php
require_once (APPPATH . 'models/ApiModel.php');

/**
 * 名刺管理システムの名刺詳細に表示する情報の取得を行う
 */
class Rq0005Model extends ApiModel
{

    public function has_required_error()
    {
        return $this->object == NULL || is_null($this->object->main_code);
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
            //詳細情報を取得
            $db_result = $this->db->select(array(
                'company_name',
                'kana_company_name',
                'position',
                'post',
                'lastname',
                'firstname',
                'kana_firstname',
                'kana_lastname',
                'postal',
                'address_a',
                'address_b',
                'address_c',
                'address_d',
                'tel',
                'fax',
                'mobile',
                'mail',
                'url',
                //'tag',
                'exchange_date',
                'memo',
                'main_code',
                'user_last',
                'user_first',
                'user_position'
                ))
            ->from('meishi_kanri_main')
            ->join('meishi_kanri_user', 'meishi_kanri_main.holder_code = meishi_kanri_user.user_id', 'left')
            ->where('meishi_kanri_main.main_code', $this->object->main_code)
            ->get()
            ->row();
            
            if(!($db_result == NULL))
            {
                $result->object = new stdClass;
                
                $result->object->company_name = $db_result->company_name;
                $result->object->kana_company_name = $db_result->kana_company_name;
                $result->object->position = $db_result->position;
                $result->object->post = $db_result->post;
                $result->object->lastname = $db_result->lastname;
                $result->object->firstname = $db_result->firstname;
                $result->object->kana_lastname = $db_result->kana_lastname;
                $result->object->kana_firstname = $db_result->kana_firstname;
                $result->object->postal = $db_result->postal;
                $result->object->address_a = $db_result->address_a;
                $result->object->address_b = $db_result->address_b;
                $result->object->address_c = $db_result->address_c;
                $result->object->address_d = $db_result->address_d;
                $result->object->tel = $db_result->tel;
                $result->object->fax = $db_result->fax;
                $result->object->mobile = $db_result->mobile;
                $result->object->mail = $db_result->mail;
                $result->object->url = $db_result->url;
                
                //タグを取得、表示用に修正
                $bypass_result = $this->db->select(array(
                    'tag_code'
                ))
                ->from('meishi_kanri_bypass')
                ->where('main_code', $this->object->main_code)
                ->get()
                ->result_array();
                if(!empty($bypass_result))
                {
                    $count = 0;
                    foreach($bypass_result as $tag)
                    {
                        $temp = $tag['tag_code'];
                        if($count == 0){
                            $where = '(tag_code'." = $temp)";
                            $count = 1;
                        }
                        else
                        {
                            $where .=" OR (".'tag_code'." = $temp)";
                        }
                    }
                    $tag_result = $this->db->select(array(
                        'tag'
                    ))
                    ->from('meishi_kanri_tag')
                    ->where($where)
                    ->get()
                    ->result_array();
                    foreach($tag_result as $key => $temp)
                    {
                        if($key == 0)
                        {
                            $result->object->tag = $temp['tag'];
                        }
                        else
                        {
                            $result->object->tag .= ','.$temp['tag'];
                        }
                    }
                }
                else
                {
                    $result->object->tag = '';
                }
                
                //日付データを表示用に修正
                if(($db_result->exchange_date == "0000-00-00 00:00:00") || ($db_result->exchange_date == NULL))
                {
                    $result->object->exchange_date = '';
                }
                else
                {
                    $result->object->exchange_date  = date('Y/m/d', strtotime($db_result->exchange_date));
                }
                $result->object->memo = $db_result->memo;
                $result->object->main_code = $db_result->main_code;
                $result->object->user_last = $db_result->user_last;
                $result->object->user_first = $db_result->user_first;
                $result->object->user_position = $db_result->user_position;
                
                //自身の名寄せコードを取得
                $sub_query = $this->db->select(
                    'nayose_code'
                    )
                    ->from('meishi_kanri_main')
                    ->where('main_code', $this->object->main_code)
                    ->get();
                $where_array = 1;
                //名寄せコードがあれば検索条件を変更
                if($sub_query->num_rows() > 0)
                {
                    $row = $sub_query->row();
                    $sub_code = $row->nayose_code;
                    $where_array = array(
                        'main_code' => "$sub_code",
                        'nayose_code' => "$sub_code"
                    );
                }
                //経歴を取得
                $carrer_query = $this->db->select(array(
                    'company_name',
                    'position',
                    'post',
                    'exchange_date',
                ))
                ->from('meishi_kanri_main')
                ->where('main_code', $this->object->main_code)
                ->or_where('nayose_code', $this->object->main_code)
                ->or_where($where_array)
                ->order_by('exchange_date desc, input_date desc')
                ->get();
                
                if($carrer_query->num_rows() > 1)
                {
                    $result->object->carrer = $carrer_query->result_array();
                    //日付データを修正
                    foreach($result->object->carrer as &$row)
                    {
                        if(($row['exchange_date'] == "0000-00-00 00:00:00") || ($row['exchange_date'] == NULL))
                        {
                            $row['exchange_date'] = '';
                        }
                        else
                        {
                            $row['exchange_date'] = date('Y/m/d', strtotime($row['exchange_date']));
                        }
                    }
                }
                else
                {
                    $result->object->carrer = array();
                }
            }
            $result->code = 0;
        }
        else
        {
            $result->code = 4;
        }
    }
}