<?php

class NayoseModel extends CI_Model
{
    public function auth($user_id)
    {
        return true;
    }
    
    /**
     * 名寄せ候補者氏名リストの作成
     */
    public function getNayoseList()
    {
        //セッションにログイン情報があれば
        $this->load->library('session');
        if(@$this->session->userdata['logged_in'] === true)
        {       
            $page = 1;
            //ログインユーザーが所有する全ての同姓同名の名刺の氏名の配列を取得
            $where = array(
                'holder_code' => @$this->session->userdata['user_id'],
                'display' => 1,
                'company_name !=' => '',
                'firstname !=' => '',
                'lastname !=' => ''
            );
            $nayose_list = $this->db->select(array(
                'lastname',
                'firstname'
            ))
            ->from('meishi_kanri_main')
            ->where($where)
            ->group_by(array('lastname', 'firstname'))
            ->having('(COUNT(*) > 1)')
            ->order_by('lastname asc, firstname asc')
            ->get()
            ->result_array();
            //取得した氏名の名刺の中に名寄せ処理が行われていない組み合わせがあるか検索
            foreach($nayose_list as $key => $name)
            {
                //氏名ごとにmain_codeの配列を取得
                $nayose_card = $this->db->select(array(
                    'main_code'
                ))
                ->from('meishi_kanri_main')
                ->where(array(
                    'holder_code' => @$this->session->userdata['user_id'],
                    'lastname' => $name['lastname'], 
                    'firstname' => $name['firstname'], 
                    'display' => 1, 
                    'company_name !=' => '',
                    'firstname !=' => '',
                    'lastname !=' => '',
                    'nayose_code' => NULL
                ))
                ->order_by('exchange_date desc, input_date desc, main_code desc')
                ->get()
                ->result_array();
                //氏名ごとに名刺の組み合わせを名寄せマスタに登録(すでに登録されている組み合わせは無視)
                foreach($nayose_card as $key_A => $card_A)
                {
                    foreach($nayose_card as $key_B => $card_B)
                    {
                        if($key_A < $key_B)
                        {
                            $query = $this->db->insert_string('meishi_kanri_nayose', array(
                                'nayose_name' => $name['lastname'].' '.$name['firstname'],
                                'card_A' => $card_A['main_code'],
                                'card_B' => $card_B['main_code'],
                                'user_id' => @$this->session->userdata['user_id']
                            ));
                            $query = str_replace('INSERT INTO', 'INSERT IGNORE INTO', $query);
                            $this->db->query($query);
                        }
                    }
                }
                //処理されていない組み合わせがあるか検索
                $nayose_set = $this->db->where(array(
                    'nayose_name' => $name['lastname'].' '.$name['firstname'],
                    'status' => NULL,
                    'user_id' => @$this->session->userdata['user_id']
                ))
                ->get('meishi_kanri_nayose');
                if($nayose_set->num_rows() == 0)
                {
                    //処理されていない組み合わせがない氏名を配列から削除
                    unset($nayose_list[$key]);
                }
                else
                {
                    //処理されていない組み合わせの数を取得
                    $set_num = $nayose_set->result_array();
                    $nayose_list[$key]['set_num'] = count($set_num);
                }
            }
            return $nayose_list;
        }
        else
        {
            return NULL;
        }
    }
    
    /**
     * 名寄せ候補者氏名リストのページ数を返す
     */
    public function getNayosePage()
    {
        //セッションにログイン情報があれば
        $this->load->library('session');
        if(@$this->session->userdata['logged_in'] === true)
        {            
            //名寄せ候補の氏名の配列の要素数を取得
            $max_row_num = count(@$this->session->userdata['nayose_list']);
            //取得した要素数を20で除算して端数を切り上げた結果の値を取得
            $max_page_num = ceil($max_row_num/20);            
            $page_result = array(
                'max_row' => $max_row_num,
                'max_page' => $max_page_num
            );
            return $page_result;
        }
        else
        {
            return NULL;
        }
    }
    
    /**
     * 名寄せ候補の名刺の組み合わせを返す
     */
    public function getNayoseCard($nayose_name, $set_num)
    {
        //セッションにログイン情報があれば
        $this->load->library('session');
        if(@$this->session->userdata['logged_in'] === true)
        {            
            //氏名が$nayose_last.' '.$nayose_firstの名刺の$set_num件目の組み合わせを取得
            $nayose_set = $this->db->select(array(
                'card_A',
                'card_B'
            ))
            ->from('meishi_kanri_nayose')
            ->where('nayose_name', $nayose_name)
            ->where('status', NULL)
            ->where('user_id', @$this->session->userdata['user_id'])
            ->order_by('card_A desc, card_B desc')
            ->limit(1, $set_num-1)
            ->get()
            ->result_array();
            //取得した組み合わせの名刺を取得
            $nayose_card = $this->db->select(array(
                'img_name_f',
                'company_name',
                'post',
                'position',
                'exchange_date',
                'main_code'
            ))
            ->from('meishi_kanri_main')
            ->where('main_code', $nayose_set['0']['card_A'])
            ->or_where('main_code', $nayose_set['0']['card_B'])
            ->order_by('exchange_date desc, input_date desc, main_code desc')
            ->get()
            ->result_array();
            //データを表示用に修正
            foreach($nayose_card as $key => &$row)
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
            }
            return $nayose_card;
        }
        else
        {
            return NULL;
        }
    }
    
    /**
     * 名寄せ処理を行う
     */
    public function doNayose($nayose_set, $do)
    {
        //セッションにログイン情報があれば
        $this->load->library('session');
        if(@$this->session->userdata['logged_in'] === true)
        {   
            foreach($nayose_set as $key => $row)
            {
                if($key == 0)
                {
                    //1枚目の名刺の管理コードを取得
                    $card_A = $row;
                }
                if($key == 1)
                {
                    //2枚目の名刺の管理コードを取得
                    $card_B = $row;
                    //名寄せ処理
                    if($do == 'nayose')
                    {
                        //名刺レコードを更新
                        $this->db->where('main_code', $card_B)
                        ->or_where('nayose_code', $card_B)
                        ->update('meishi_kanri_main',array(
                            'nayose_code' => $card_A,
                            'display' => 0
                        ));
                        //名寄せした組み合わせのstatusを名nayoseに更新
                        $this->db->where(array(
                            'card_A' => $card_A,
                            'card_B' => $card_B
                        ))->update('meishi_kanri_nayose',array(
                            'status' => 'nayose'
                        ));
                        //名寄せで非表示になった名刺の名刺コードを持つ組み合わせのstatusをhideに更新
                        $where = "((`card_A` = $card_B) OR (`card_B` = $card_B)) AND (`status` IS NULL)";
                        $this->db->where($where)
                        ->update('meishi_kanri_nayose',array(
                            'status' => 'hide'
                        ));
                    }
                    //兼務指定処理
                    else if($do == 'kenmu')
                    {
                        //兼務指定した組み合わせのstatusをkenmuに更新
                        $this->db->where(array(
                            'card_A' => $card_A,
                            'card_B' => $card_B
                        ))->update('meishi_kanri_nayose',array(
                            'status' => 'kenmu'
                        ));
                    }
                    //別人指定処理
                    else if($do == 'betsujin')
                    {
                        //別人指定した組み合わせのstatusをbetsujinに更新
                        $this->db->where(array(
                            'card_A' => $card_A,
                            'card_B' => $card_B
                        ))
                        ->update('meishi_kanri_nayose',array(
                            'status' => 'betsujin'
                        ));
                    }
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
     * 未処理の組み合わせの中に処理済みにできる組み合わせが無いか名寄せマスタをチェックする
     */
    public function checkNayose()
    {
        //セッションにログイン情報があれば
        $this->load->library('session');
        if(@$this->session->userdata['logged_in'] === true)
        {
            //名寄せマスタにある、直前に名寄せ処理した氏名と同姓同名かつ所有者がログインユーザーの名刺コードを全て取得
            $list_A = $this->db->select(array(
              'card_A'  
            ))
            ->from('meishi_kanri_nayose')
            ->where(array(
                'nayose_name' => $this->session->userdata['nayose_name'],
                'user_id' => $this->session->userdata['user_id']                
            ))
            ->group_by('card_A')
            ->order_by('card_A asc')
            ->get()
            ->result_array();
            foreach($list_A as $key => $A)
            {
                $check_list_A[$key] = $A['card_A'];
            }
            $list_B = $this->db->select(array(
                'card_B'
            ))
            ->from('meishi_kanri_nayose')
            ->where(array(
                'user_id' => $this->session->userdata['user_id']
            ))
            ->where_not_in('card_B', $check_list_A)
            ->group_by('card_B')
            ->order_by('card_B asc')
            ->get()
            ->result_array();
            foreach($list_B as $key => $B)
            {
                $check_list_B[$key] = $B['card_B'];
            }
            $check_list = array_merge($check_list_A, $check_list_B);            
            //名寄せマスタにある、直前に名寄せ処理した氏名と同姓同名かつ所有者がログインユーザーの未処理の組み合わせを全て取得
            $check_set = $this->db->select(array(
                'card_A',
                'card_B'
            ))
            ->from('meishi_kanri_nayose')
            ->where(array(
                'nayose_name' => $this->session->userdata['nayose_name'],
                'user_id' => $this->session->userdata['user_id'],
                'status' => NULL
            ))
            ->order_by('card_A desc, card_B desc')
            ->get()
            ->result_array();
            //取得した未処理の組み合わせごとに、処理済みにできるかを判定
            foreach($check_set as $set)
            {
                /* 例えば、
                 *  ------------------------------------------
                 * |     card_A     |     card_B     | status |
                 *  ------------------------------------------
                 * | $set['card_A'] | $set['card_B'] |  NULL  |
                 *  ------------------------------------------
                 *  という未処理のレコードは、
                 *  ----------------------------------     ----------------------------------
                 * |     card_A     | card_B | status |   |     card_A     | card_B | status |
                 *  ----------------------------------     ----------------------------------
                 * | $set['card_A'] |   $X   | kenmu  |   | $set['card_B'] |   $X   | kenmu  |
                 *  ----------------------------------  ,  ----------------------------------
                 *  というレコードの組み合わせが存在するならば、
                 *  ------------------------------------------
                 * |     card_A     |     card_B     | status |
                 *  ------------------------------------------
                 * | $set['card_A'] | $set['card_B'] | kenmu  |
                 *  ------------------------------------------
                 *  として処理済みにできる。
                 *  このようなレコードの組み合わせが名寄せマスタに存在するか検索する。
                 */
                foreach($check_list as $X)
                {
                    /* (i)
                     *  -------------------------------------------- 
                     * |     card_A     | card_B |      status      |
                     *  -------------------------------------------- 
                     * | $set['card_A'] |   $X   | NOT(NULL, hide)  |
                     *  -------------------------------------------- 
                     *  または
                     *  -------------------------------------------- 
                     * | card_A |     card_B     |      status      |
                     *  -------------------------------------------- 
                     * |   $X   | $set['card_A'] | NOT(NULL, hide)  |
                     *  -------------------------------------------- 
                     *  という組み合わせを検索
                     */
                    $where = "(((`card_A` = ".$set['card_A'].")AND(`card_B` = ".$X."))OR((`card_A` = ".$X.")AND(`card_B` = ".$set['card_A'].")))";
                    $flag_A = $this->db->select(array(
                        'card_A',
                        'card_B',
                        'status'
                    ))
                    ->from('meishi_kanri_nayose')
                    ->where($where)
                    ->where_not_in('status', array(
                        'IS NULL',
                        'hide'
                    ))
                    ->get();
                    /* (ii)
                     *  -------------------------------------------- 
                     * |     card_A     | card_B |      status      |
                     *  -------------------------------------------- 
                     * | $set['card_B'] |   $X   | NOT(NULL, hide)  |
                     *  -------------------------------------------- 
                     *  または
                     *  -------------------------------------------- 
                     * | card_A |     card_B     |      status      |
                     *  -------------------------------------------- 
                     * |   $X   | $set['card_B'] | NOT(NULL, hide)  |
                     *  -------------------------------------------- 
                     *  という組み合わせを検索
                     */
                    $where = "(((`card_A` = ".$set['card_B'].")AND(`card_B` = ".$X."))OR((`card_A` = ".$X.")AND(`card_B` = ".$set['card_B'].")))";
                    $flag_B = $this->db->select(array(
                        'card_A',
                        'card_B',
                        'status'
                    ))
                    ->from('meishi_kanri_nayose')
                    ->where($where)
                    ->where_not_in('status', array(
                        'IS NULL',
                        'hide'
                    ))
                    ->get();
                    //(i)と(ii)の両方で検索結果があれば
                    if(($flag_A->num_rows() > 0) && ($flag_B->num_rows() > 0))
                    {
                        //(i)と(ii)の検索結果のstatusカラムの組み合わせによって処理を分岐
                        $check_status = array_merge($flag_A->result_array(), $flag_B->result_array());
                        //{(i)がkenmuまたはnayose、(ii)がkenmuまたはnayose}なら、
                        if((($check_status['0']['status'] == 'kenmu')||($check_status['0']['status'] == 'nayose'))&&(($check_status['1']['status'] == 'kenmu')||($check_status['1']['status'] == 'nayose')))
                        {
                            //{$set['card_A'], $set['card_B']}の組み合わせのstatusをkenmuに更新
                            $this->db->where(array(
                                'card_A' => $set['card_A'],
                                'card_B' => $set['card_B']
                            ))->update('meishi_kanri_nayose',array(
                                'status' => 'kenmu'
                            ));
                            return 'checking';
                        }
                        //{{(i)がbetsujin、(ii)がkenmuまたはnayose}または{(i)がkenmuまたはnayose、(ii)がbetsujin}}なら、
                        else if((($check_status['0']['status'] == 'betsujin')&&(($check_status['1']['status'] == 'kenmu')||($check_status['1']['status'] == 'nayose')))||((($check_status['0']['status'] == 'kenmu')||($check_status['0']['status'] == 'nayose'))&&($check_status['1']['status'] == 'betsujin')))
                        {
                            //{$set['card_A'], $set['card_B']}の組み合わせのstatusをbetsujinに更新
                            $this->db->where(array(
                                'card_A' => $set['card_A'],
                                'card_B' => $set['card_B']
                            ))->update('meishi_kanri_nayose',array(
                                'status' => 'betsujin'
                            ));
                            return 'checking';
                        }
                        //(i)がbetsujin、(ii)がbetsujinなら、
                        else if(($check_status['0']['status'] == 'betsujin')&&($check_status['1']['status'] == 'betsujin'))
                        {
                            //{$set['card_A'], $set['card_B']}の組み合わせのstatusはNULLのまま
                        }
                    }
                }
            }
            return 'finished';
        }
        else
        {
            return NULL;
        }
    }
}