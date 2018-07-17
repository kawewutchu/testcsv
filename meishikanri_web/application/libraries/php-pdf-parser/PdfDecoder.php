<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * path指定のバックスラッシュをスラッシュに変更
 */
class PdfDecoder
{

    public function save_images($pdfFilePath, $destDir = null, $baseName = null)
    {
        //呼び出し元で呼び出す直前に１回だけincludeする
        //include APPPATH . 'libraries/php-pdf-parser/pdf.php';

        $fp = fopen($pdfFilePath, 'r');
        if (! $fp) {
            return false;
        }
        
        $pathinfo = pathinfo($pdfFilePath);
        
        if ($baseName == null) {
            $baseName = $pathinfo['filename'];
        }
        if ($destDir == null) {
            $destDir = $pathinfo['dirname'];
        }
        
        $reader = new PdfFileReader($fp);
        
        $pages = array();
        $reader->get_page_count($pages);
        
        $writeCount = 1;
        foreach ($pages['/Kids'] as $page) {
            $page = $page->get_object();
            $resources = $page['/Resources']->get_object();
            foreach ($resources['/XObject'] as $xobject) {
                $stream = $xobject->get_object();
                $data = $stream->get_data();
                $w = fopen($destDir . '/' . $baseName . '_' . $writeCount . '.jpg', 'w');
                fwrite($w, $data);
                fclose($w);
                $writeCount ++;
                break;
            }
        }
        
        fclose($fp);
        
        return true;
    }
}
