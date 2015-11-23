<?php

namespace FW\HTML;

use FW\Helpers\Common;
use FW\Security\CSRF;

class Form {

    public static function open(array $options = array()) {
        if (!array_key_exists('method', $options)) {
            $options['method'] = 'POST';
        }

        return '<form' . self::getAttributesAsString($options) . '>' . self::csrf();
    }

    public static function close() {
        return '</form>';
    }

    public static function text(array $options = array()) {
        return self::getInputFormElement('text', $options);
    }

    public static function radio(array $options = array()) {
        return self::getInputFormElement('radio', $options);
    }

    public static function check(array $options = array()) {
        return self::getInputFormElement('checkbox', $options);
    }

    public static function password(array $options = array()) {
        return self::getInputFormElement('password', $options);
    }

    public static function hidden(array $options = array()) {
        return self::getInputFormElement('hidden', $options);
    }

    public static function datetime(array $options = array()) {
        return self::getInputFormElement('datetime', $options);
    }

    public static function file(array $options = array()) {
        return self::getInputFormElement('file', $options);
    }

    public static function color(array $options = array()) {
        return self::getInputFormElement('color', $options);
    }

    public static function email(array $options = array()) {
        return self::getInputFormElement('email', $options);
    }

    public static function number(array $options = array()) {
        return self::getInputFormElement('number', $options);
    }

    public static function range(array $options = array()) {
        return self::getInputFormElement('range', $options);
    }

    public static function reset(array $options = array()) {
        return self::getInputFormElement('reset', $options);
    }

    public static function submit(array $options = array()) {
        return self::getInputFormElement('submit', $options);
    }

    public static function textarea($text = '', array $options = array()) {
        return self::getElementWithClosingTad('textarea', $text, $options);
    }

    public static function label($text = '', array $options = array()) {
        return self::getElementWithClosingTad('label', $text, $options);
    }

    public static function script($src = '', array $options = array()) {
        if (Common::startsWith($src, 'http')) {
            $options['src'] = $src;
        } else {
            $options['src'] = Common::getBaseDir() . $src;
        }

        return self::getElementWithClosingTad('script', '', $options);
    }

    public static function style($src = '', array $options = array()) {
        if (!Common::startsWith($src, 'http')) {
            $src = Common::getBaseDir() . $src;
        }
        $options['rel'] = 'stylesheet';
        return '<link href="' . $src . '"' . self::getAttributesAsString($options) . '>';
    }

    public static function select(array $options = array(), array $elements = array()) {
        $output = '<select' . self::getAttributesAsString($options) . '>';
        foreach ($elements as $el) {
            $output .= '<option';
            $output .= isset($el['options']) ? self::getAttributesAsString($el['options']) : '';
            $output .= '>';
            $output .= isset($el['text']) ? $el['text'] : '';
            $output .= '</option>';
        }
        $output .= '</select>';
        return $output;
    }

    public static function csrf() {
        return self::hidden(array(
            'name' => '_token',
            'value' => CSRF::generateToken()
        ));
    }

    public static function ajaxScript($button, $url, $method, $loadOn, $params = array())
    {
        $ajax = '<script>';
        $ajax .= '$("' . $button . '").click(function (e) {' . 'e.preventDefault();';
        $ajax .= '$.ajax({';
        $ajax .= 'url: "' . $url . '",';
        $ajax .= 'method: "' . $method . '",';
        $ajax .= 'data: {';

        foreach ($params as $key => $value) {
            $ajax .= $key . ': $("' . $value . '").val(),';
        }

        $ajax .= '}';
        $ajax .= '}).done(function (data) {';
        $ajax .= '$("' . $loadOn . '").text(data);';
        $ajax .= '})});</script>';

        return $ajax;
    }

    private static function getElementWithClosingTad($tag, $text = '', array $options = array()) {
        return '<' . $tag . self::getAttributesAsString($options) . '>' . $text . '</' . $tag . '>';
    }

    private static function getInputFormElement($type, array $options = array()) {
        return '<input type="' . $type . '"' . self::getAttributesAsString($options) . '>';
    }

    private static function getAttributesAsString(array $attr = array()) {
        $attributes = '';
        foreach($attr as $key => $value) {
            $attributes .= ' ' . $key . '="' . $value . '"';
        }

        return $attributes;
    }
} 