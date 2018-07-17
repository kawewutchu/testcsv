<?php

class MenuModel extends CI_Model
{
    public function auth($user_id)
    {
        return true;
    }
    
    /**
     * ユーザのリストを取得
     */
    public function getUsers()
    {     
        //セッションのログイン状態がtrueならば
        $this->load->library('session');
        if(@$this->session->userdata['logged_in'] === true)
        {    
            $db_result = $this->db->select(array(
                    'user_id',
                    'user_last',
                    'user_first'
                ))
                ->from('meishi_kanri_user')
                ->order_by('sort_code', 'asc')
                ->where_in('display', '1')
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
     * タグのリストを取得
     */
    public function getTags()
    {
    
        //セッションのログイン状態がtrueならば
        $this->load->library('session');
        if(@$this->session->userdata['logged_in'] === true)
        {
            $db_result = $this->db->select(array(
                'tag_code',
                'tag',
                'parent',
                'attribute',
                'user_last',
                'user_first'
            ))
            ->from('meishi_kanri_tag')
            ->join('meishi_kanri_user', 'meishi_kanri_tag.attribute = meishi_kanri_user.user_id' , 'left')
            ->where('meishi_kanri_tag.display', 1)
            ->order_by('sort_code asc, tag asc')
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
     * 引数
     * $page：値：何ぺージ目を表示するか
     * $search_word：配列：表示する名刺リストの検索条件
     * $search_user：文字列：表示する名刺リストの検索条件
     * 機能
     * 30件分の名刺リストを取得
     */
    public function getMeishi($page, $search_word, $search_user, $search_tag, $search_date_from, $search_date_to)
    {   
        //セッションのログイン状態がtrueならば
        $this->load->library('session');
        if(@$this->session->userdata['logged_in'] === true)
        {
            //取得する名刺の検索条件を作成
            $where = "(meishi_kanri_main.display".' = '.'1)';
            //$search_wordが空でなければ
            if(!($search_word === NULL || $search_word ===''))
            {
                //検索対象のカラム名の配列を作成
                $search_colum_array = preg_split("/,/" , "company_name,lastname,firstname,tel");                
                //検索語句の配列を作成
                $converted_word = mb_convert_encoding($search_word,'UTF-8','ASCII,JIS,UTF-8,EUC-JP,SJIS');
                $search_word_array = preg_split('/[ 　]+/u', $converted_word);            
                //検索条件を追加
                foreach($search_word_array as $word)
                {
                    $where .= " AND (";
                    foreach($search_colum_array as $key => $colum){
                        if($key == 0)
                        {
                            $where .= " $colum LIKE '%$word%'";
                        }else{
                            $where .= " OR $colum LIKE '%$word%'";
                        }
                    }
                    $where .= ")";
                }
            }            
            //$search_userが空でなければ
            if(!($search_user === NULL || $search_user ===''))
            {
                //検索条件を追加
                $where .= " AND (holder_code".' = '."'$search_user')";
            }            
            //$search_tagが空でなければ
            if(!($search_tag === NULL || $search_tag ===''))
            {
                //子タグを取得
                $offspring = $this->db->select(array(
                    'tag_code'
                ))
                ->from('meishi_kanri_tag')
                ->where('parent', $search_tag)
                ->where('display', 1)
                ->order_by('tag', 'asc')
                ->get()
                ->result_array();            
                //タグ検索条件を作成
                $where_tag = "(tag_code".' = '."'$search_tag')";
                if(!empty($offspring))
                {
                    foreach($offspring as $off)
                    {
                        $off_code = $off['tag_code'];
                        $where_tag .= " OR (tag_code".' = '."'$off_code')";
                    }
                }            
                //検索タグが付けられている名刺コードを取得
                $bypass_result = $this->db->select(array(
                    'main_code'
                ))
                ->from('meishi_kanri_bypass')
                ->where($where_tag)
                ->get()
                ->result_array();
                //検索条件を追加
                foreach($bypass_result as $key => $row)
                {
                    $temp = $row['main_code'];
                    if($key == 0)
                    {
                        $where .= " AND ((meishi_kanri_main.main_code".' = '."'$temp')";
                    }
                    else
                    {
                        $where .= " OR (meishi_kanri_main.main_code".' = '."'$temp')";
                    }
                }
                $where .= ")";
            }            
            //$search_date_fromが空でなければ
            if(!($search_date_from === NULL || $search_date_from ===''))
            {
                //検索条件を追加
                $where .= " AND (exchange_date".' >= '."'$search_date_from')";
            }
            //$search_date_fromが空でなければ
            if(!($search_date_to === NULL || $search_date_to ===''))
            {
                //検索条件を追加
                $where .= " AND (exchange_date".' <= '."'$search_date_to')";
            }
            
            //$pageぺージ目に表示する30件分の名刺データを取得       
            $meishi_result = $this->db->select(array(
                'company_name',
                //'kana_company_name',
                'position',
                'post',
                'lastname',
                'firstname',
                //'kana_lastname',
                //'kana_firstname',
                //'postal',
                'address_a',
                'address_b',
                'address_c',
                'address_d',
                'tel',
                'fax',
                'mobile',
                'mail',
                //'url',
                'GROUP_CONCAT(meishi_kanri_tag.tag SEPARATOR ",") AS tag',
                //'input_date',
                'DATE_FORMAT(exchange_date, "%Y/%m/%d") AS exchange_date',
                //'memo',
                //'contact_date',
                //'personal_code',
                //'card_code',
                //'company_code',
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
            ->where($where)
            ->where('company_name IS NOT NULL', NULL)
            ->where('lastname IS NOT NULL', NULL)
            ->where('firstname IS NOT NULL', NULL)
            ->group_by('meishi_kanri_main.main_code')
            ->order_by('exchange_date desc, input_date desc, kana_lastname asc, kana_firstname asc')
            ->limit(30, ($page-1)*30)
            ->get()
            ->result_array();
            
            //日付データと画像データとタグデータを表示用に修正
            foreach($meishi_result as &$row)
            {
                if(($row['exchange_date'] == "0000/00/00") || ($row['exchange_date'] == NULL))
                {
                    $row['exchange_date'] = '（未入力）';
                }
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
            return NULL;
        }
    }

    /**
     * 引数
     * $search_word：配列：表示する名刺リストの検索条件
     * $search_user：文字列：表示するリストの検索条件
     * 機能
     * 検索条件を満たす名刺一覧を30件ずつ表示した時の最大ページ数を返す
     */
    public function getMaxPage($search_word, $search_user, $search_tag, $search_date_from, $search_date_to)
    {
        //セッションのログイン状態がtrueならば
        $this->load->library('session');
        if(@$this->session->userdata['logged_in'] === true)
        {
            $where = "(display".' = '.'1)';
            
            //$search_wordが空でなければ
            if(!($search_word === NULL || $search_word ===''))
            {
                //検索対象のカラム名の配列を作成
                $search_colum_array = preg_split("/,/" , "company_name,lastname,firstname,tel");
            
                //検索語句の配列を作成
                $converted_word = mb_convert_encoding($search_word,'UTF-8','ASCII,JIS,UTF-8,EUC-JP,SJIS');
                $search_word_array = preg_split('/[ 　]+/u', $converted_word);
            
                //各カラムに対して各検索語句で部分一致検索のAND検索を行うSQL文を作成
                $count = 0;
                foreach($search_word_array as $word)
                {
                    $where .= " AND (";
                    foreach($search_colum_array as $colum){
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
            //$search_userが空でなければ
            if(!($search_user === NULL || $search_user ===''))
            {
                //検索条件を追加
                $where .= " AND (holder_code".' = '."'$search_user')";
            }
            //$search_tagが空でなければ
            if(!($search_tag === NULL || $search_tag ===''))
            {
                //子タグを取得
                $offspring = $this->db->select(array(
                    'tag_code'
                ))
                ->from('meishi_kanri_tag')
                ->where('parent', $search_tag)
                ->where('display', 1)
                ->order_by('tag', 'asc')
                ->get()
                ->result_array();
            
                //タグ検索条件を作成
                $where_tag = "(tag_code".' = '."'$search_tag')";
                if(!empty($offspring))
                {
                    foreach($offspring as $off)
                    {
                        $off_code = $off['tag_code'];
                        $where_tag .= " OR (tag_code".' = '."'$off_code')";
                    }
                }
            
                //検索タグが付けられている名刺コードを取得
                $tag_result = $this->db->select(array(
                    'main_code'
                ))
                ->from('meishi_kanri_bypass')
                ->where($where_tag)
                ->get()
                ->result_array();
            
                foreach($tag_result as $key => $row)
                {
                    $temp = $row['main_code'];
                    if($key == 0)
                    {
                        $where .= " AND ((main_code".' = '."'$temp')";
                    }
                    else
                    {
                        $where .= " OR (main_code".' = '."'$temp')";
                    }
                }
                $where .= ")";
            }
            //$search_date_fromが空でなければ
            if(!($search_date_from === NULL || $search_date_from ===''))
            {
                //検索条件を追加
                $where .= " AND (exchange_date".' >= '."'$search_date_from')";
            }
            //$search_date_fromが空でなければ
            if(!($search_date_to === NULL || $search_date_to ===''))
            {
                //検索条件を追加
                $where .= " AND (exchange_date".' <= '."'$search_date_to')";
            }
            
            $query = $this->db->where($where)
            ->where('company_name IS NOT NULL', NULL)
            ->where('lastname IS NOT NULL', NULL)
            ->where('firstname IS NOT NULL', NULL)
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
            return NULL;
        }
    }
    
    /**
     * 後で管理者のみ実行可能に変更
     * 引数で与えられた条件にヒットする名刺のCSVデータを出力する
     *
    public function outputMeishi($search_word, $search_user, $search_tag, $search_date_from, $search_date_to)
    {
        //セッションのログイン状態がtrueならば
        $this->load->library('session');
        if(@$this->session->userdata['logged_in'] === true)
        {
            //取得する名刺の検索条件を作成
            $where = "(meishi_kanri_main.display".' = '.'1)';
            //$search_wordが空でなければ
            if(!($search_word === NULL || $search_word ===''))
            {
                //検索対象のカラム名の配列を作成
                $search_colum_array = preg_split("/,/" , "company_name,lastname,firstname");
                //検索語句の配列を作成
                $converted_word = mb_convert_encoding($search_word,'UTF-8','ASCII,JIS,UTF-8,EUC-JP,SJIS');
                $search_word_array = preg_split('/[ 　]+/u', $converted_word);
                //検索条件を追加
                foreach($search_word_array as $word)
                {
                    $where .= " AND (";
                    foreach($search_colum_array as $key => $colum){
                        if($key == 0)
                        {
                            $where .= " $colum LIKE '%$word%'";
                        }else{
                            $where .= " OR $colum LIKE '%$word%'";
                        }
                    }
                    $where .= ")";
                }
            }
            //$search_userが空でなければ
            if(!($search_user === NULL || $search_user ===''))
            {
                //検索条件を追加
                $where .= " AND (holder_code".' = '."'$search_user')";
            }
            //$search_tagが空でなければ
            if(!($search_tag === NULL || $search_tag ===''))
            {
                //子タグを取得
                $offspring = $this->db->select(array(
                    'tag_code'
                ))
                ->from('meishi_kanri_tag')
                ->where('parent', $search_tag)
                ->where('display', 1)
                ->order_by('tag', 'asc')
                ->get()
                ->result_array();
                //タグ検索条件を作成
                $where_tag = "(tag_code".' = '."'$search_tag')";
                if(!empty($offspring))
                {
                    foreach($offspring as $off)
                    {
                        $off_code = $off['tag_code'];
                        $where_tag .= " OR (tag_code".' = '."'$off_code')";
                    }
                }
                //検索タグが付けられている名刺コードを取得
                $bypass_result = $this->db->select(array(
                    'main_code'
                ))
                ->from('meishi_kanri_bypass')
                ->where($where_tag)
                ->get()
                ->result_array();
                //検索条件を追加
                foreach($bypass_result as $key => $row)
                {
                    $temp = $row['main_code'];
                    if($key == 0)
                    {
                        $where .= " AND ((meishi_kanri_main.main_code".' = '."'$temp')";
                    }
                    else
                    {
                        $where .= " OR (meishi_kanri_main.main_code".' = '."'$temp')";
                    }
                }
                $where .= ")";
            }
            //$search_date_fromが空でなければ
            if(!($search_date_from === NULL || $search_date_from ===''))
            {
                //検索条件を追加
                $where .= " AND (exchange_date".' >= '."'$search_date_from')";
            }
            //$search_date_fromが空でなければ
            if(!($search_date_to === NULL || $search_date_to ===''))
            {
                //検索条件を追加
                $where .= " AND (exchange_date".' <= '."'$search_date_to')";
            }
    
            //$CSV出力する名刺データを取得
            $csv_query = $this->db->select(array(
                'company_name AS 会社名',
                'kana_company_name AS 会社名（カナ）',
                'position AS 部署',
                'post AS 役職',
                'lastname AS 姓',
                'firstname AS 名',
                'kana_lastname AS 姓（カナ）',
                'kana_firstname AS 名（カナ）',
                'postal AS 郵便番号',
                'address_a AS 都道府県',
                'address_b AS 市区町村',
                'address_c AS 番地',
                'address_d AS 建物名',
                'tel AS 電話番号',
                'fax AS FAX',
                'mobile AS 携帯電話番号',
                'mail AS メールアドレス',
                'GROUP_CONCAT(meishi_kanri_tag.tag SEPARATOR " ") AS タグ',
                'url AS URL',
                'DATE_FORMAT(input_date, "%Y/%m/%d") AS 入力日',
                'DATE_FORMAT(exchange_date, "%Y/%m/%d") AS 交換日',
                'memo AS メモ',
                //'contact_date',
                //'personal_code',
                //'card_code',
                //'company_code',
                'CONCAT(meishi_kanri_user.user_last," ",meishi_kanri_user.user_first) AS 所有者',
                'holder_code AS 所有者ID',
                'meishi_kanri_main.main_code AS 管理コード',
            ))
            ->from('meishi_kanri_main')
            ->join('meishi_kanri_user', 'meishi_kanri_main.holder_code = meishi_kanri_user.user_id', 'left')
            ->join('meishi_kanri_bypass','meishi_kanri_main.main_code = meishi_kanri_bypass.main_code', 'left')
            ->join('meishi_kanri_tag','meishi_kanri_bypass.tag_code = meishi_kanri_tag.tag_code','left')
            ->where($where)
            ->where('company_name IS NOT NULL', NULL)
            ->where('lastname IS NOT NULL', NULL)
            ->where('firstname IS NOT NULL', NULL)
            ->group_by('meishi_kanri_main.main_code')
            ->order_by('exchange_date desc, input_date desc, company_name asc, kana_lastname asc, kana_firstname asc')
            ->get();
    
            //クエリ結果からCSVを生成
            $this->load->dbutil();
            $csv_data = mb_convert_encoding($this->dbutil->csv_from_result($csv_query),'SJIS',  'UTF-8');
            //CSVをファイルに書き出す
            $this->load->helper('file');
            $path = './csv/';
            date_default_timezone_set('Asia/Tokyo');
            $filename = date('YmdHis').'.csv';
            if (!write_file($path.$filename, $csv_data))
            {
                return false;
            }
            else
            {
                return $path.$filename;
            }
        }
        else
        {
            return NULL;
        }
    }*/
}