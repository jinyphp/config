<?php
use \Jiny\Core\Registry\Registry;

if (! function_exists('conf')) {
    /**
     * 설정값을 읽어옵니다.
     */
    function conf(string $key=NULL, $value=NULL) {        
        if ($conf = Registry::get("CONFIG")) {
            if (func_num_args()) {
                if ($value) {
                    return $conf->set($key, $value);
                } else {
                    return $conf->data($key);
                } 
            } else {
                // return $conf;
                return $conf->data();
            }                       
        } else {
            echo "인스턴스를 확인할 수 없습니다.";
        }
    }
}