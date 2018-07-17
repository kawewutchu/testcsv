<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AbstractController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->lang->load('error', 'japanese');
        $this->load->helper('file');
        $this->load->helper('path');
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->helper('download');
        $this->load->database();
        $this->load->library('upload');
    }
}
