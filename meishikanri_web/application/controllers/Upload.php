<?php
/**
 * 名刺画像スキャン時に追加情報（所有者、交換日）を取得する
 */
class Upload extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->library('php-pdf-parser/PdfDecoder', '', 'pdfDecoder');
        $this->load->database();
    }
    
    /**
     * ユーザーリストを取得
     */
    public function get_user()
    {
        //ユーザーリストを取得
        $this->load->model('UploadModel');
        $data['user_list'] = $this->UploadModel->getUserList();
        //入力画面表示
        $this->load->view('Upload_user', $data);
    }
    
    /**
     * タグリストを取得
     */
    public function get_tag()
    {
        $user_id = $this->input->post('upload_user');
        //タグリストを取得
        $this->load->model('UploadModel');
        $data['share_tag'] = $this->UploadModel->getShareTag();
        $data['my_tag'] = $this->UploadModel->getMyTag($user_id);
        $data['user_id'] = $user_id;
        //入力画面表示
        $this->load->view('Upload', $data);
    }
    
    /**
     * 新しいタグを作成するページへ移動
     */
    public function new_tag()
    {
        $user_id = $this->input->post('add_tag_user');
        //タグ作成画面を表示
        $this->load->model('UploadModel');
        $data['share_tag'] = $this->UploadModel->getShareTag();
        $data['my_tag'] = $this->UploadModel->getMyTag($user_id);
        $data['user_id'] = $user_id;
        $this->load->view('Upload_tag', $data);
    }
    
    /**
     * 新しいタグを追加
     */
    public function add_tag()
    {
        //追加するタグ情報を取得
        $add_tag = $this->input->post('add_tag');
        $add_tag_attribute = $this->input->post('add_tag_attribute');
        $add_tag_parent = $this->input->post('add_tag_parent');
        $user_id = $this->input->post('add_tag_user');
        //タグを追加
        $this->load->model('UploadModel');
        $this->UploadModel->addNewTag($add_tag, $add_tag_attribute, $add_tag_parent);
        //入力画面を表示
        $data['share_tag'] = $this->UploadModel->getShareTag();
        $data['my_tag'] = $this->UploadModel->getMyTag($user_id);
        $data['user_id'] = $user_id;
        $this->load->view('Upload', $data);
    }
    
    /**
     * 新しい名刺レコードをDBに追加
     */
    public function upload()
    {
        $upload_exchange_date = $this->input->post('upload_exchange_date');
        $upload_user = $this->input->post('upload_user');
        $upload_tag = $this->input->post('upload_tag');
        //名刺画像PDFが一時保存されているディレクトリパスを指定
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
                            $this->load->model('UploadModel');
                            //名刺をアップロード
                            if($this->UploadModel->UploadMeishi($img_name_f, $img_name_b, $upload_exchange_date, $upload_user, $upload_tag))
                            {
                                //変換元のpdfファイルを移動
                                rename($dir.$row, './pdf/'.$info->getBasename('.pdf').'.pdf');
                                //入力画面を表示
                                $data['close'] = true;
                                $this->load->view('Upload', $data);
                            }
                            else
                            {
                                echo 'upload error';
                            }
                        }
                    }
                }
            }
        }
    }
}