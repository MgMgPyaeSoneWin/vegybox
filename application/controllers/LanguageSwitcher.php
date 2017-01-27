<?php

/**
 * Created by PhpStorm.
 * User: pyaesone
 * Date: 1/26/17
 * Time: 11:17 PM
 */
class LanguageSwitcher extends CI_Controller
{
    public function __construct() {
        parent::__construct();
    }

    function switchLang($language = "") {

        $language = ($language != "") ? $language : "english";
        $this->session->set_userdata('site_lang', $language);

        redirect($_SERVER['HTTP_REFERER']);

    }
}