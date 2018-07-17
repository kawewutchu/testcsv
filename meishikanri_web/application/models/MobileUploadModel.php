<?php
/**
 * 名刺画像スキャン時に追加情報（所有者、交換日）を取得する
 */

class MobileUploadModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database(); 
        $this->load->library('session');
    }

    public function auth()
    {
        return true;
    }

    public function addNewBizFornt($imageName, $count)
    {
        // insert first image name
        if($count == 0) {
            $add_image = array(
                'holder_code' => "admin",
                'img_name_f' => $imageName
            );
            $this->db->insert('meishi_kanri_main', $add_image);
            $insert_id = $this->db->insert_id();
            $this->session->set_userdata('last_biz_id', $insert_id);
        // upadate second image name
        } elseif ($count == 1) {
            $last_biz_id = $this->session->userdata['last_biz_id'];
            $this->db->set('img_name_b', $imageName);
            $this->db->where('main_code', $last_biz_id);
            $this->db->update('meishi_kanri_main');
        }
    }

    public function updateDetailBiz($datas, $count)
    {
        if($count == 0) {
            checkdata_and_update($datas);
        } elseif ($count == 1) {
            checkdata_and_update($datas);
        }
    }

    public function checkdata_and_update($datas) {
        $update_detail = array();
            // checking null data
            if(isset($datas["position"])) {
                $update_detail['position'] = $datas["position"];
            }
            if(isset($datas["company_name"])) {
                $update_detail['company_name'] = $datas["company_name"];
            }
            if(isset($datas["lastname"])) {
                $update_detail['lastname'] = $datas["lastname"];
            }
            if(isset($datas["fax"])) {
                $update_detail['fax'] = $datas["fax"];
            }
            if(isset($datas["mail"])) {
                $update_detail['mail'] = $datas["Email"];
            }
            if(isset($datas["postal"])) {
                $update_detail['postal'] = $datas["postal code"];
            }
            if(isset($datas["address_c"])) {
                $update_detail['address_c'] = $datas["address"];
            }
             // update detail to database 
            $last_biz_id = $this->session->userdata['last_biz_id'];
            $this->db->set($update_detail);
            $this->db->where('main_code', $last_biz_id);
            $this->db->update('meishi_kanri_main');
    }

}