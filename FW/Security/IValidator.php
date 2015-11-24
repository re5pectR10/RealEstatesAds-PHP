<?php

namespace FW\Security;


interface IValidator {

    public function setRule($rule, $value, $params, $name);
    public function validate();
    public function getErrors();
} 