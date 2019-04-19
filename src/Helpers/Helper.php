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
use \Jiny\Config\Config;

use Jiny\Filesystem\File;

if (! function_exists('conf')) {
    /**
     * 설정값을 읽어옵니다.
     */
    function conf(string $key=NULL, $value=NULL) {       
        //if ($conf = Registry::get("CONFIG")) {
        if ($conf = Config::instance()) {
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

// namespace Jiny;
use \Jiny\Config\Drivers\Yaml;
use \Jiny\Config\Drivers\INI;
use \Jiny\Config\Drivers\JSON;

if (! function_exists('yaml')) {
    function yaml($filename) {
        $yaml = Yaml::instance();
        
        $path = File::pathDir($filename);
        $name = File::pathFileName($filename);
     
        return $yaml->load($name, $path);

    }
}

if (! function_exists('ini')) {
    function ini($filename) {
        $ini = INI::instance();
        
        $path = File::pathDir($filename);
        $name = File::pathFileName($filename);
     
        return $ini->load($name, $path);

    }
}

if (! function_exists('json')) {
    function json($filename) {
        $json = JSON::instance();
        
        $path = File::pathDir($filename);
        $name = File::pathFileName($filename);
     
        return $json->load($name, $path);

    }
}