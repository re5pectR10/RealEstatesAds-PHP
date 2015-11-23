<?php

namespace FW\Route;


interface IRequestProvider {

    public function getURI();
    public function getPOST();
    public function getGET();
    public function getFILES();
    public function getHeaders();
} 