<?php

if (! function_exists('conf')) {
    /**
     * 설정값을 읽어옵니다.
     */
    function conf($key, $value=NULL) {
        $conf = Registry::get("CONFIG");
        return $conf->data($key);
    }
}