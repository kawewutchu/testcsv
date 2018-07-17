<?php

class DetailModel extends CI_Model
{
    public function auth($user_id)
    {
        return true;
    }
    
    /**
     * 引数
     * $detail_code：詳細情報を表示する名刺のmain_code
     * $flag：表示先が詳細画面か編集画面かのフラグ
     * 機能
     * 名刺の詳細画面で表示する情報の配列を取得し返す
     */
    public function getDetail($detail_code)
    {
        //セッションのログイン状態がtrueならば
        $this->load->library('session');
        if(@$this->session->userdata['logged_in'] === true)
        {
            //名刺の詳細情報を取得
            $detail_result = $this->db->select(array(
                'company_name',
                'kana_company_name',
                'position',
                'post',
                'lastname',
                'firstname',
                'kana_lastname',
                'kana_firstname',
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
                'GROUP_CONCAT(meishi_kanri_tag.tag SEPARATOR ",") AS tag',
                'DATE_FORMAT(exchange_date, "%Y/%m/%d") AS exchange_date',
                'memo',
                'holder_code',
                'meishi_kanri_main.main_code',
                'img_name_f',
                'img_name_b',
                'user_last',
                'user_first'
            ))
            ->from('meishi_kanri_main')
            ->join('meishi_kanri_user', 'meishi_kanri_main.holder_code = meishi_kanri_user.user_id', 'left')
            ->join('meishi_kanri_bypass','meishi_kanri_main.main_code = meishi_kanri_bypass.main_code', 'left')
            ->join('meishi_kanri_tag','meishi_kanri_bypass.tag_code = meishi_kanri_tag.tag_code','left')
            ->where('meishi_kanri_main.main_code', $detail_code)
            ->group_by('meishi_kanri_main.main_code')
            ->get()
            ->result_array();
            
            //自身の名寄せコードを取得
            $sub_query = $this->db->select(
                'nayose_code'
                )
                ->from('meishi_kanri_main')
                ->where('main_code', $detail_code)
                ->get();
            //自身の名寄せコードがあれば検索条件を追加
            $where_array = 1;
            if($sub_query->num_rows() > 0)
            {
                $row = $sub_query->row();
                if($row->nayose_code != NULL)
                {
                    $sub_code = $row->nayose_code;
                    $where_array = array(
                        'main_code' => "$sub_code",
                        'nayose_code' => "$sub_code"
                    );
                }
            }
            /*
             * 経歴を取得
             * ・自身の名寄せコードを管理コードに持つ名刺
             * ・自身
             * ・自身の管理コードを名寄せコードに持つ名刺
             * ・自身の名寄せコードを名寄せコードに持つ名刺
             */
            $carrer_query = $this->db->select(array(
                'company_name',
                'position',
                'post',
                'DATE_FORMAT(exchange_date, "%Y/%m/%d") AS exchange_date',
            ))
            ->from('meishi_kanri_main')
            ->where('main_code', $detail_code)
            ->or_where('nayose_code', $detail_code)
            ->or_where($where_array)
            ->order_by('exchange_date desc, input_date desc')
            ->get();
            if($carrer_query->num_rows() > 1)
            {
                $carrer_result = $carrer_query->result_array();
                //日付データを修正
                foreach($carrer_result as &$row)
                {
                    if(($row['exchange_date'] == "0000/00/00") || ($row['exchange_date'] == NULL))
                    {
                        $row['exchange_date'] = '（未入力）';
                    }
                }
            }
            else
            {
                $carrer_result = NULL;
            }
            
            //データを表示用に修正
            foreach($detail_result as &$row)
            {
                //日付データを文字列に修正
                if(($row['exchange_date'] == "0000/00/00") || ($row['exchange_date'] == NULL))
                {
                    $row['exchange_date'] = '（未入力）';
                }
                //画像パスを修正
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
                
                //タグを取得
                if(!empty($row['tag']))
                {
                    $row['tag'] = explode(',', $row['tag']);
                }
                
                //電話番号を修正
                if($row['tel'] == NULL){
                    $tel_abc = NULL;
                }else{
                    $tel_abc = explode("-", $row['tel']);
                    if(!(isset($tel_abc[1])))
                    {
                        array_push($tel_abc, NULL);
                    }
                    if(!(isset($tel_abc[2])))
                    {
                        array_push($tel_abc, NULL);
                    }
                }
                $row['tel_abc'] = $tel_abc;
                
                //fax番号を修正
                if($row['fax'] == NULL){
                    $fax_abc = NULL;
                }else{
                    $fax_abc = explode("-", $row['fax']);
                    if(!(isset($fax_abc[1])))
                    {
                        array_push($fax_abc, NULL);
                    }
                    if(!(isset($fax_abc[2])))
                    {
                        array_push($fax_abc, NULL);
                    }
                }
                $row['fax_abc'] = $fax_abc;
                
                //携帯電話番号を修正
                if($row['mobile'] == NULL){
                    $mobile_abc = NULL;
                }else{
                    $mobile_abc = explode("-", $row['mobile']);
                    if(!(isset($mobile_abc[1])))
                    {
                        array_push($mobile_abc, NULL);
                    }
                    if(!(isset($mobile_abc[2])))
                    {
                        array_push($mobile_abc, NULL);
                    }
                }
                $row['mobile_abc'] = $mobile_abc;
                
                //郵便番号を修正
                if($row['postal'] == NULL){
                    $postal_ab = NULL;
                }else{
                    $postal_ab = explode("-", $row['postal']);
                    if(!(isset($postal_ab[1])))
                    {
                        array_push($postal_ab, NULL);
                    }
                }
                $row['postal_ab'] = $postal_ab;
                
                $row['carrer'] = $carrer_result;
            }
            return $detail_result;   
        }
        else
        {
            return NULL;
        }
    }
}