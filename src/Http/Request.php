<?php


namespace App\Http;


class Request
{

    /**
     * @var array
     */
    public $server;
    /**
     * @var array
     */
    public $get;
    /**
     * @var array
     */
    public $post;
    /**
     * @var array
     */
    public $cookie;
    /**
     * @var array
     */
    public $session;
    /**
     * @var array
     */
    public $request;
    /**
     * @var array
     */
    public $files;


    public function __construct()
    {
        $this->server = $_SERVER;
        $this->get = $_GET;
        $this->post = $_POST;
        $this->cookie = $_COOKIE;
        $this->session = $_SESSION;
        $this->request = $_REQUEST;
        $this->files = $_FILES;

    }

    static public function getServer(){
        return $_SERVER;
    }


}