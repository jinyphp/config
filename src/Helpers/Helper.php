<?php
/*
 * This file is part of the jinyPHP package.
 *
 * (c) hojinlee <infohojin@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
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

/**
 * 설정 객체를 반환합니다.
 */
if (! function_exists('config_init')) {
    function config_init()
    {
        if ($conf = Registry::get("CONFIG")) {
            return $conf;
        }
    }
}

/**
 * 설정 객체를 반환합니다.
 */
if (! function_exists('config_set')) {
    function config_set($key, $value)
    {
        $conf = config_init();
        $conf->set($key, $value);
    }
}