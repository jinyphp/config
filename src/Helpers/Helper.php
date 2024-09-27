<?php

/**
 * json 파일을 읽어 옵니다.
 */
if(!function_exists("json_file_decode")) {
    function json_file_decode($file) {
        $json = []; //

        if(file_exists($file)) {
            if($body = file_get_contents($file)) {
                $json = json_decode($body,true);
            }
        }

        return $json;
    }
};

/**
 * 배열을 json 파일로 저장합니다.
 */
if(!function_exists("json_file_encode")) {
    function json_file_encode($file, $json) {
        $str = json_encode($json,
            JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        file_put_contents($file, $str);

        return $str;
    }
}




//namespace Jiny;

use \Jiny\Core\Registry\Registry;
use \Jiny\Config\Config;

use Jiny\Filesystem\File;


/*
if (! function_exists('conf')) {
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
*/

/**
 * 설정 객체를 반환합니다.
 */
/*
if (! function_exists('config_init')) {
    function config_init()
    {
        if ($conf = Registry::get("CONFIG")) {
            return $conf;
        }
    }
}
*/

/**
 * 설정 객체를 반환합니다.
 */
/*
if (! function_exists('config_set')) {
    function config_set($key, $value)
    {
        $conf = config_init();
        $conf->set($key, $value);
    }
}
*/

/*
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

*/

/*
 * version 2.0
 */
if (! function_exists('config_json')) {
    function config_json($filename): ?array {
        $path = config_path().DIRECTORY_SEPARATOR.$filename.".json";
        $path = str_replace(['/','\\'],DIRECTORY_SEPARATOR,$path);

        if (file_exists($path)) {
            $str = file_get_contents($path);
            $arr = json_decode($str,true); // 배열로 가지고 오기
            return $arr;
        }

        return null;
    }
}

if (! function_exists('config_ini')) {
    function config_ini($filename): ?array {
        $path = config_path().DIRECTORY_SEPARATOR.$filename.".ini";
        $path = str_replace(['/','\\'],DIRECTORY_SEPARATOR,$path);

        if (file_exists($path)) {
            $str = file_get_contents($path);
            $arr = \parse_ini_string($str);
            return $arr;
        }

        return null;
    }
}

use \Symfony\Component\Yaml\Yaml as Yaml2;
if (! function_exists('config_ymal')) {
    function config_yaml($filename): ?array {
        $path = config_path().DIRECTORY_SEPARATOR.$filename.".ymal";
        $path = str_replace(['/','\\'],DIRECTORY_SEPARATOR,$path);

        if (file_exists($path)) {
            $str = file_get_contents($path);
            $arr = Yaml2::parse($str);
            return $arr;
        }

        return null;
    }
}

