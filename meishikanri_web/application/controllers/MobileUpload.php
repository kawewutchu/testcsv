<?php
/**
 * 名刺画像スキャン時に追加情報（所有者、交換日）を取得する
 */
class MobileUpload extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->library('php-pdf-parser/PdfDecoder', '', 'pdfDecoder');
        $this->load->library('session');
        $this->load->database();
    }

    public function mobileuploadpage()
    {
        $this->load->model('MenuModel');
        //ドロップダウン用ユーザリストを取得
        $this->session->userdata['user_list'] = $this->MenuModel->getUsers();
        //ドロップダウン用タグリストを取得
        $this->session->userdata['tag_list'] = $this->MenuModel->getTags();
        //初期（最新３０件）の名刺データを取得
        $this->session->userdata['meishi_list'] = $this->MenuModel->getMeishi(1, NULL, $this->session->userdata['search_user'], NULL, NULL, NULL);
        //メニュー画面を表示
        $data['meishi_list'] = $this->session->userdata['meishi_list'];
        $this->load->view('MobileUpload', $data);
    }

    public function upload()
    {
        $filename = array();
        
        if($_FILES["files"]["name"] != '')
        {
            // set path for save image in server
            $config["upload_path"] = './meishi/';
            // set type image
            $config["allowed_types"] = 'gif|jpg|png';
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            $is_second = false;
            if($this->session->userdata('last_biz_id')) {
                $is_second = true;
            }
            $this->load->model('MobileUploadModel');
            // upload each image
            for($count = 0; $count<count($_FILES["files"]["name"]); $count++)
            {
                // set file name, type, size, error
                $_FILES["file"]["name"] = $_FILES["files"]["name"][$count];
                $_FILES["file"]["type"] = $_FILES["files"]["type"][$count];
                $_FILES["file"]["tmp_name"] = $_FILES["files"]["tmp_name"][$count];
                $_FILES["file"]["error"] = $_FILES["files"]["error"][$count];
                $_FILES["file"]["size"] = $_FILES["files"]["size"][$count];
                // try to upload to server
                if($this->upload->do_upload('file'))
                {
                    $data = $this->upload->data();
                }

                $datas = array();
                $filename = $_FILES["file"]["name"];
                //upload image name to database
                $this->MobileUploadModel->addNewBizFornt($filename, $count);
                //analyze image to text
                $this->image_to_text($filename, $count);
            }
        } else {
            $count = 0;
        }

        if($is_second) {
            $count = 2;
        }
        echo json_encode(array('uploaded_files' => $count));
    } 
    //  analyze image to text function
    function image_to_text($filename, $count) {
        // set image path
        $target_file = './meishi/'.$filename;
        // use Tesseract OCR library to convert image to text
        $output1 = shell_exec('tesseract '.$target_file.' resultjpn -l jpn');
        $output2 = shell_exec('tesseract '.$target_file.' resulteng -l eng');
        $filesjpn = file("resultjpn.txt", FILE_IGNORE_NEW_LINES);
        $fileseng = file("resulteng.txt", FILE_IGNORE_NEW_LINES);
        //split text line by line
        $informations = array();
        $filesjpn = $this->preprocess($filesjpn);
        $fileseng = $this->preprocess($fileseng);
        for($i = 0; $i < count($filesjpn) ; $i++) {
            //checking English character in sentence 
            $wordlen = mb_strlen($filesjpn[$i]);
            $englishChar = $this->count_english_word(str_split(strtoupper($filesjpn[$i])));
            $th = 10;
            $percentEnglish = ($englishChar * 100)/ $wordlen;
            if($wordlen > 0) {
                // if in sentence have English char more than threshold use sentence form resulteng.txt
                if($percentEnglish > $th) {
                    array_push($informations , $fileseng[$i]);
                } else {
                    array_push($informations , $filesjpn[$i]);
                }
            }
        }
        //send to node red and classifly sentence
        $datas = array();
        foreach($informations as $information) {
            if(strlen($information) > 2){
                $result = $this->post_to_classifly($information);
                $datas  += $this->text_to_analyze($datas, $result); 
            }
        }  
        $this->text_to_database($datas, $count);
    }

    // post the sentence to node RED
    function post_to_classifly($string) {  
        $url = 'https://chatbot-kawewut.mybluemix.net/classifly';
        $fields = array(
            'data' => $string,
        );
        $fields_string = "";
        foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
        rtrim($fields_string, '&');
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
        curl_setopt($ch,CURLOPT_POST, count($fields));
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
    // correcting phone number, Fax, Email and delete prefix word
    function text_to_analyze($datas, $result) {
        $results = json_decode($result);
        if(!is_null($results)) {
                if($results->key == "name") {
                    $name = explode(' ',$results->value);
                    $data = array("lastname" => $name[0]);
                    return $data;
                }else if($results->key == "phone number"){
                    if (strpos($results->value, 'TEL') !== false or strpos($results->value, 'tel') !== false or strpos($results->value, 'Tel') !== false) {
                        $tel = $results->value;
                        $tel = str_replace("TEL","",$tel);
                        $tel = str_replace("Tel","",$tel);
                        $tel = str_replace("tel","",$tel);
                        $tel = str_replace(" ","",$tel);
                        $tel = str_replace(" ","",$tel);
                        $tel = str_replace(":","",$tel);
                        $tel = str_replace("一","",$tel);
                        $data = array("tel" => $tel);
                        return $data;
                    }
                    else if (strpos($results->value, 'FAX') !== false or strpos($results->value, 'Fax') !== false or strpos($results->value, 'fax') !== false) {
                        $tel = $results->value;
                        $tel = str_replace("FAX","",$tel);
                        $tel = str_replace("Fax","",$tel);
                        $tel = str_replace("fax","",$tel);
                        $tel = str_replace(" ","",$tel);
                        $tel = str_replace(" ","",$tel);
                        $tel = str_replace(":","",$tel);
                        $tel = str_replace("一","-",$tel);
                        $data = array("fax" => $tel);
                        return $data;
                    }
                    else if (strpos($results->value, 'PHONE') !== false or strpos($results->value, 'Phone') !== false or strpos($results->value, 'phone') !== false) {
                        $tel = $results->value;
                        $tel = str_replace("PHONE","",$tel);
                        $tel = str_replace("Phone","",$tel);
                        $tel = str_replace("phone","",$tel);
                        $tel = str_replace(" ","",$tel);
                        $tel = str_replace(" ","",$tel);
                        $tel = str_replace(":","",$tel);
                        $tel = str_replace("一","-",$tel);
                        $data = array("tel" => $tel);
                        return $data;
                    }
                    else if($results->key == "Email"){
                        $tel = $results->value;
                        $tel = str_replace("EMAIL","",$tel);
                        $tel = str_replace("Email","",$tel);
                        $tel = str_replace("email","",$tel);
                        $tel = str_replace(" ","",$tel);
                        $tel = str_replace(" ","",$tel);
                        $tel = str_replace(":","",$tel);
                        $tel = str_replace("一","-",$tel);
                        $tel = str_replace("~","-",$tel);
                        $data = array("Email" => $tel);
                        print_r($data);
                        return $data;
                    }
                }
                return array($results->key => $results->value);
        }
    }
    
    function preprocess($filelinebylines) {
        $filelines = array();
        foreach($filelinebylines as $line){
            if(mb_strlen( $line )){
                array_push($filelines, $line);
            }
        }
        return $filelines;
    }

    function count_english_word($strings){
        $countchar = 0;
        foreach($strings as $string){
            if($this->is_EnglishCheck($string)){
                $countchar += 1;
            }
        }
        return $countchar;
    }

    function is_english($str){
        if($str >= "A" and $str <= "Z"){
            return true;
        }
        return false;
    }

    function is_number($str){
        if($str >= "0" and $str <= "9"){
            return true;
        }
        return false;
    }

    function is_EnglishCheck($str) {
        return $this->is_english($str);
    }

    // update detail to database
    function text_to_database($datas, $count) {
        $this->MobileUploadModel->updateDetailBiz($datas, $count);
    }
}