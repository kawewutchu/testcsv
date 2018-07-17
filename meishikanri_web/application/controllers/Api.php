<?php
require_once ('AbstractController.php');
require_once (APPPATH . 'models/ApiResult.php');


/**
 * 名刺管理システムのiOS端末用リクエスト処理
 */
class Api extends AbstractController
{

    protected $prefix;

    public function __construct()
    {
        parent::__construct();
    }

    public function exec($reqid)
    {        
        // Api用モデルのロード
        $modelPath = 'api/';
        if ($this->prefix != null) {
            $modelPath .= ($this->prefix . '/');
        }
        $modelPath .= ($reqid . 'Model');

        $this->load->model($modelPath, 'model');
        
        // APIでは必ずobjectという名前でJSON形式の入力パラメータが渡される
        // Conteny-Type:application/x-www-form-urlencodedで渡される想定
        // object={"key1":"val1", "key2":"val2", ...}
        if (isset($_POST['object'])) {
            $this->model->object = json_decode($this->input->post('object'));
        }
        
        //結果を入れて返却するオブジェクトを作成
        $result = new ApiResult();
        $result->request_id = $reqid;

        // 必須項目チェック
        if ($this->model->has_required_error()) {
            $result->code = 1;           
            $this->output($result);
            return;
        }
        
        // データ型チェック
        if ($this->model->has_datatype_error()) {
            $result->code = 2;
            $this->output($result);
            return;
        }
        
        // 値チェック
        if ($this->model->has_value_error()) {
            $result->code = 3;
            $this->output($result);
            return;
        }
        
        //結果を取得
        $this->model->exec($result);
        //結果をJSON形式に変換
        $this->output($result);
    }

    public function download($reqid)
    {
        // Api用モデルのロード
        $modelPath = 'api/';
        if ($this->prefix != null) {
            $modelPath .= ($this->prefix . '/');
        }
        $modelPath .= ($reqid . 'Model');
        $this->load->model($modelPath, 'model');
        
        // APIでは必ずobjectという名前でJSON形式の入力パラメータが渡される
        if (isset($_POST['object'])) {
            $this->model->object = json_decode($this->input->post('object'));
        }
        
        // 必須項目チェック
        if ($this->model->has_required_error()) {
            return;
        }
        
        // データ型チェック
        if ($this->model->has_datatype_error()) {
            return;
        }
        
        // 値チェック
        if ($this->model->has_value_error()) {
            return;
        }
        
        $data = $this->model->download();
        if ($data != null) {
            $this->output->set_output($data);
        }
    }

    protected function output($result)
    {
        //結果をJSON形式で返却
        $this->output->set_header('Access-Control-Allow-Origin: *')
            ->set_content_type('application/x-www-form-urlencoded', 'utf-8')
            ->set_output(json_encode($result));
    }
}
