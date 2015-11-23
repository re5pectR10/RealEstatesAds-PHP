<?php

namespace FW\Route;


class RequestProvider implements IRequestProvider {

    public function getURI()
    {
        return substr($_SERVER["PATH_INFO"], 1);
    }

    public function getPOST()
    {
        return $_POST;
    }

    public function getGET()
    {
        return $_GET;
    }

    public function getFILES()
    {
        return $_FILES;
    }

    public function getHeaders()
    {
        return http_get_request_headers();
    }
}