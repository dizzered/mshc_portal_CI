<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Fileupload extends MSHC_Controller
{

    protected $path_upload_folder;
    protected $path_thumb_upload_folder;
    protected $path_url_upload_folder;
    protected $path_url_thumb_upload_folder;

    protected $delete_url;

    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));

        //Set relative Path with CI Constant
        $this->setPath_upload_folder("uploads/");
        $this->setPath_thumb_upload_folder("uploads/");

        //Delete img url
        $this->setDelete_url(base_url() . 'fileupload/deleteFile/');

        //Set url img with Base_url()
        $this->setPath_url_upload_folder(base_url() . "uploads/");
        $this->setPath_url_thumb_upload_folder(base_url() . "uploads/");
    }


    public function index()
    {
        //$this->load->view('upload_v/upload_view');
        redirect(base_url());
    }


    public function upload()
    {
        $name = $_FILES['userfile']['name'];
        $name = strtr($name, 'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');

        // remplacer les caracteres autres que lettres, chiffres et point par _
        $name = preg_replace('/([^.a-z0-9]+)/i', '_', $name);

        //Your upload directory, see CI user guide
        $config['upload_path'] = $this->getPath_upload_folder();

        $config['allowed_types'] = 'gif|jpg|png|pdf|doc|docx|xls|xlsx|csv|txt|rtf|rar|zip|gzip|7z|JPG|GIF|PNG|PDF|DOC|DOCX|XLS|CSV|XLSX|TXT|RTF|RAR|ZIP|GZIP|7Z';
        $config['file_name'] = $name;

        //Load the upload library
        $this->load->library('upload', $config);

        if ($this->do_upload()) {
            $data = $this->upload->data();

            //Get info 
            $info = new stdClass();

            $info->name = $name;
            $info->size = $data['file_size'];
            $info->type = $data['file_type'];
            $info->url = $this->getPath_upload_folder() . $name;
            $info->delete_url = $this->getDelete_url() . $name;
            $info->delete_type = 'DELETE';

            //Return JSON data
            if ($this->_is_ajax) {
                //this is why we put this in the constants to pass only json data
                echo json_encode(array($info));
                //this has to be the only the only data returned or you will get an error.
                //if you don't give this a json array it will give you a Empty file upload result error
                //it you set this without the if(IS_AJAX)...else... you get ERROR:TRUE (my experience anyway)
            } else {
                // so that this will still work if javascript is not enabled
                $file_data['upload_data'] = $this->upload->data();
                echo json_encode(array($info));
            }
        } else {

            // the display_errors() function wraps error messages in <p> by default and these html chars don't parse in
            // default view on the forum so either set them to blank, or decide how you want them to display.  null is passed.
            $error = array('error' => $this->upload->display_errors('', ''));

            echo json_encode(array($error));
        }

    }

    /**
     * Function for the upload : return true/false
     * @return bool
     */
    public function do_upload()
    {

        if (!$this->upload->do_upload()) {
            return false;
        } else {
            //$data = array('upload_data' => $this->upload->data());
            return true;
        }
    }

    public function deleteFile()
    {

        //Get the name in the url
        $file = $this->uri->segment(3);

        $success = unlink($this->getPath_upload_folder() . $file);

        //info to see if it is doing what it is supposed to 
        $info = new stdClass();
        $info->sucess = $success;
        $info->path = $this->getPath_url_upload_folder() . $file;
        $info->file = is_file($this->getPath_upload_folder() . $file);

        if ($this->_is_ajax) {
            //I don't think it matters if this is set but good for error checking in the console/firebug
            echo json_encode(array($info));
        } else {
            //here you will need to decide what you want to show for a successful delete
            var_dump($file);
        }
    }

    public function get_files()
    {

        $this->get_scan_files();
    }

    public function get_scan_files()
    {

        $file_name = isset($_REQUEST['file']) ?
            basename(stripslashes($_REQUEST['file'])) : null;
        if ($file_name) {
            $info = $this->get_file_object($file_name);
        } else {
            $info = $this->get_file_objects();
        }
        header('Content-type: application/json');
        echo json_encode($info);
    }

    protected function get_file_object($file_name)
    {
        $file_path = $this->getPath_upload_folder() . $file_name;
        if (is_file($file_path) && $file_name[0] !== '.') {

            $file = new stdClass();
            $file->name = $file_name;
            $file->size = filesize($file_path);
            $file->url = $this->getPath_url_upload_folder() . rawurlencode($file->name);
            //File name in the url to delete 
            $file->delete_url = $this->getDelete_url() . rawurlencode($file->name);
            $file->delete_type = 'DELETE';

            return $file;
        }
        return null;
    }

    protected function get_file_objects()
    {
        return array_values(array_filter(array_map(
            array($this, 'get_file_object'), scandir($this->getPath_upload_folder())
        )));
    }


    public function getPath_upload_folder()
    {
        return $this->path_upload_folder;
    }

    public function setPath_upload_folder($path_upload_folder)
    {
        $this->path_upload_folder = $path_upload_folder;
    }

    public function getPath_thumb_upload_folder()
    {
        return $this->path_thumb_upload_folder;
    }

    public function setPath_thumb_upload_folder($path_thumb_upload_folder)
    {
        $this->path_thumb_upload_folder = $path_thumb_upload_folder;
    }

    public function getPath_url_upload_folder()
    {
        return $this->path_url_upload_folder;
    }

    public function setPath_url_upload_folder($path_url_upload_folder)
    {
        $this->path_url_upload_folder = $path_url_upload_folder;
    }

    public function getPath_url_thumb_upload_folder()
    {
        return $this->path_url_thumb_upload_folder;
    }

    public function setPath_url_thumb_upload_folder($path_url_thumb_upload_folder)
    {
        $this->path_url_thumb_upload_folder = $path_url_thumb_upload_folder;
    }

    public function getDelete_url()
    {
        return $this->delete_url;
    }

    public function setDelete_url($delete_url)
    {
        $this->delete_url = $delete_url;
    }
}