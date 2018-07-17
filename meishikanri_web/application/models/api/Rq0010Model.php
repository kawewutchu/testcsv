<?php
require_once (APPPATH . 'models/ApiModel.php');

/**
 * 端末からの画像アップロードを受けてデータ保存する
 */
class Rq0010Model extends ApiModel
{

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
        //ファイル１を保存
        if (move_uploaded_file($_FILES['file1']['tmp_name'], "meishi/".$_FILES["file1"]["name"])) {
            //echo "File uploaded: ".$_FILES["file1"]["name"];
            
            //ファイル２を保存
            if (move_uploaded_file($_FILES['file2']['tmp_name'], "meishi/".$_FILES["file2"]["name"])) {
                //echo "File uploaded: ".$_FILES["file2"]["name"];
                
                //echo $_POST["holder_code"];
                //echo $_POST["exchange_date"];
                
                //データ登録
                $this->UploadMeishi($_FILES["file1"]["name"], $_FILES["file2"]["name"], $_POST["exchange_date"], $_POST["holder_code"]);
                
            }else{
                $result->code = -1;
            }
        }else{
            $result->code = -1;
        }
    }
    
    /**
     * 新しい名刺レコードをDBに追加
     */
    public function UploadMeishi($img_name_f, $img_name_b, $upload_exchange_date, $upload_user)
    {
        //引数がNULLでなければ
        if($img_name_f != NULL || $img_name_b != NULL)
        {   
            //データ入力用の配列を作る
            date_default_timezone_set('Asia/Tokyo');
            $input_data = array(
                'input_date' => date('Y-m-d H:i:s'),
                'exchange_date' => $upload_exchange_date,
                'holder_code' => $upload_user,
                'img_name_f' => $img_name_f,
                'img_name_b' => $img_name_b
            );
    
            //データ入力
            if($this->db->insert('meishi_kanri_main',$input_data))
            {
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