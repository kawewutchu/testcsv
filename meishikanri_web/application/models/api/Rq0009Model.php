<?php
require_once (APPPATH . 'models/ApiModel.php');

/**
 * スキャンされた名刺画像を取得してテーブルにレコードを追加する
 */
class Rq0009Model extends ApiModel
{
    
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->library('php-pdf-parser/PdfDecoder', '', 'pdfDecoder');
    }

    public function has_required_error()
    {
        return false;
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
        if(!($this->session->userdata('user_id') === false))
        {
            //名刺画像PDFが一時保存されているディレクトリを開く
            $dir = './scan/';
            //ディレクトリを開けたか確認
            if(is_dir($dir))
            {
                //ディレクトリ内の全ファイルを配列で取得
                if($files = scandir($dir))
                {
                    include APPPATH . 'libraries/php-pdf-parser/pdf.php';
                    foreach($files as $row)
                    {
                        $info = new SplFileInfo($row);
                        //PDFファイルがあれば
                        if($info->getExtension() === 'pdf')
                        {
                            //pdf形式のファイルに含まれる画像オブジェクトをjpg形式のファイルに変換
                            if($this->pdfDecoder->save_images($dir.$row, './meishi', $info->getBasename('.pdf')))
                            {
                                $img_name_f = $info->getBasename('.pdf').'_1'.'.jpg';
                                $img_name_b = $info->getBasename('.pdf').'_2'.'.jpg';
                    
                                if($img_name_f != NULL || $img_name_b != NULL)
                                {
                                    //データ入力用の配列を作る
                                    date_default_timezone_set('Asia/Tokyo');
                                    $input_data = array(
                                        'input_date' => date('Y-m-d H:i:s'),
                                        'exchange_date' => "0000-00-00 00:00:00",
                                        'img_name_f' => $img_name_f,
                                        'img_name_b' => $img_name_b
                                    );
                                    //データ入力
                                    if($this->db->insert('meishi_kanri_main',$input_data))
                                    {
                                        unlink($dir.$row);
                                    }
                                }
                            }
                        }
                    }
                    $result->code = 0;
                }
                else
                {
                    $result->code = -1;
                }
            }
            else
            {
                $result->code = -1;
            }
        }
        else
        {
            $result->code = 4;
        }
    }
}