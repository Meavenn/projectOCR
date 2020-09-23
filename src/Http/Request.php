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

    public static function createFromGlobals()
    {
        return new self(
            $_SERVER,
            $_GET,
            $_POST,
            $_COOKIE,
            $_SESSION,
            $_REQUEST,
            $_FILES
        );
    }
//
//    public function getServer()
//    {
//        return $this->server;
//    }
//
//    public function getGet()
//    {
//        return $this->get;
//    }
//
//    public function getPost()
//    {
//        return $this->post;
//    }
//
//    public function getCookie()
//    {
//        return $this->cookie;
//    }
//
//    public function getSession()
//    {
//        return $this->session;
//    }
//
//    public function getRequest()
//    {
//        return $this->request;
//    }
//
//    public function isMethod(string $method)
//    {
//        return $this->getServer()['REQUEST_METHOD'] === $method;
//    }

}