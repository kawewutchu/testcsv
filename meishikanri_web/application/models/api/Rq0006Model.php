<?php
require_once (APPPATH . 'models/ApiModel.php');

/**
 * 名刺画像を取得する
 */
class Rq0006Model extends ApiModel
{

    public function has_required_error()
    {
        return ($this->object == NULL 
                || is_null($this->object->main_code)
                || $this->object->main_code === ""
                || is_null($this->object->sides));
    }

    public function has_datatype_error()
    {
        return (!is_bool($this->object->sides));
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
            //返却する画像パスの一部を形成
            $path = (empty($_SERVER["HTTPS"]) ? "http://" : "https://") . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
            $cut = 15;
            $path = substr($path, 0, strlen($path)-$cut).'meishi/';
            
            //表画像か裏画像のパスを取得
            if($this->object->sides == true)
            {
                $image_name = $this->db->select(
                    'img_name_f'
                    )
                ->from('meishi_kanri_main')
                ->where('main_code', $this->object->main_code)
                ->get()
                ->row();
                
                $path .= $image_name->img_name_f;
            }
            elseif($this->object->sides == false)
            {
                $image_name = $this->db->select(
                    'img_name_b'
                    )
                    ->from('meishi_kanri_main')
                    ->where('main_code', $this->object->main_code)
                    ->get()
                    ->row();
                
                $path .= $image_name->img_name_b;
            }
            else
            {
                $path = NULL;
            }
            
            $result->object = new stdClass;
            $result->object->image_path = $path;
            $result->object->main_code = $this->object->main_code;
            $result->object->sides = $this->object->sides;
            $result->code = 0;
        }
        else
        {
            $result->code = 4;
        }
    }
}