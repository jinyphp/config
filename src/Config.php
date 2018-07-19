<?php

namespace Jiny\Config;

use \Jiny\Core\Base\File;

/**
 * jiny 
 * 설정파일을 읽어 처리합니다.
 * 디자인패턴: 싱글톤
 */
class Config
{

    /**
     * 설정값
     * 모든 값이 저장되어 있습니다.
     */
    protected $_config=[];

    /**
     * 설정 파일의 목록을 가지고 있습니다.
     * ./conf/ 디렉토리의 파일들을 읽어 옵니다.
     * Key 값는 설정파일의 이름으로 처리됩니다.
     * 파일명이 '_'로 시작되는 경우 처리되지 않습니다. 
     */
    protected $_load=[];

    /**
     * 설정파일 목록을 지정합니다.
     */
    protected $_file=[];

    private function __construct()
    {
        // 싱글톤
    }

    private function __clone()
    {
        // 싱글톤
    }

    /**
     * 인스턴스 저장 프로퍼티
     */
    private static $_instance;

    /**
     * 드라이버 인스턴스를 관리하는 배열입니다.
     * INI, YMAL, PHP등
     */
    public $Drivers;
    
    /**
     * 설정파일의 인스턴스를 생성합니다.
     * 싱글톤으로 별도의 생성 메서드를 가지고 있습니다.
     */
    public static function instance()
    {
        //echo "인스턴스를 생성합니다.<br>";

        if (!isset(self::$_instance)) {
                   
            // 인스턴스를 생성합니다.
            // 자기 자신의 인스턴스를 생성합니다.                
            self::$_instance = new self();

            // 드라이버를 로드합니다.
            self::$_instance->Drivers['Yaml'] = new \Jiny\Config\Drivers\Yaml(self::$_instance);
            self::$_instance->Drivers['INI'] = new \Jiny\Config\Drivers\INI(self::$_instance);
            self::$_instance->Drivers['PHP'] = new \Jiny\Config\Drivers\PHP(self::$_instance);

            // 시작위치를 지정합니다.
            self::$_instance->_config['ROOT'] = ROOT_PUBLIC;

            //Debug::out("기본 환경 설정값을 읽어 옵니다.");
            self::$_instance->_config['ENV'] = self::$_instance->Drivers['PHP']->loadPHP(".env", ROOT.DS);

            return self::$_instance;

        } else {
            // 인스턴스가 중복
            return self::$_instance; 
        }
    }    


    /**
     * 로딩할 설정파일을 지정합니다.
     */
    public function setFile($filename, $key=NULL)
    {
        if ($key) {
            $this->_file[$key] = $filename;
        } else {
            $parts = pathinfo($filename);
            $this->_file[ $parts['filename'] ] = $filename;
        }
    }

    /**
     * 파일을 로드 합니다.
     */
    public function loadFiles()
    {

        // echo "설정파일을 로드합니다.<br>";

        $path = conf("ENV.path.conf");
        $path = rtrim($path, "/")."/";
        $path = ROOT.File::osPath($path); 

        foreach ($this->_file as $key => $name) {
            // echo "$key $name<br>";
            $parts = pathinfo($name);

            switch ($parts['extension']) {
                case 'ini':
                    //echo "ini 설정파일을 읽어 읽습니다.<br>";
                    $this->_config[$key] = $this->Drivers['INI']->loadINI($parts['filename'], $path);
                    break;

                case 'yml':
                    // Yaml 데이터를 읽어옵니다.
                    $this->_config[$key] = $this->Drivers['Yaml']->loadYaml($parts['filename'], $path);
                    break; 

                case 'php':
                    //echo "php 설정파일을 읽어 읽습니다.<br>";
                    $this->_config[$key] = $this->Drivers['PHP']->loadPHP($parts['filename'], $path);
                    break;    
            } 

        }

        return $this;
    }


    /**
     * 지정한 key의 데이터를 읽어옵니다.
     */
    public function data($key=NULL)
    {
        if ($key) {
            // 닷(.)을 이용하여 배열값을 분리합니다.
            $value = \explode(".", $key);

            $arr = $this->_config;
            foreach ($value as $name){
                if (isset($arr[$name])) $arr = $arr[$name];               
            }           
            return $arr;

        } else {
            return $this->_config;
        }
    }



    /**
     * 지정한 값을 저장합니다.
     */
    public function set(string $key, $value)
    {
        if($key){
            // 닷(.)을 이용하여 배열값을 분리합니다.
            $k = \explode(".", $key);

            switch(count($k)) {
                case 5:
                    $this->_config[ $k[0] ][ $k[1] ][ $k[2] ][ $k[3] ][ $k[4] ] = $value;
                    break;

                case 4:
                    $this->_config[ $k[0] ][ $k[1] ][ $k[2] ][ $k[3] ] = $value;
                    break;

                case 3:
                    $this->_config[ $k[0] ][ $k[1] ][ $k[2] ] = $value;
                    break;

                case 2:
                    $this->_config[ $k[0] ][ $k[1] ] = $value;
                    break;
                case 1:
                    $this->_config[ $k[0] ] = $value;
                    break;
            }

        }
        
        return $this;        
    }








    public function __invoke($key=NULL)
    {
        return $this->data($key);
    }

    

    /**
     * 값을 추가합니다.
     * @value = array
     */
    public function append(string $key, array $value)
    {
        if($key){
            foreach ($value as $k => $v) {
                $this->_config[$key][$k] = $v;
            }
        }
        return $this;
    }

    /**
     * 설정파일을 파싱 로드합니다.
     */
    public function parser()
    {
        $path = rtrim($this->_config['ENV']['path']['conf'], "/")."/";
        $path = ROOT.str_replace("/",DS,$path);

        foreach ($this->_load as $key => $value) {
            switch ($value) {
                case 'ini':
                    //echo "ini 설정파일을 읽어 읽습니다.<br>";
                    $this->_config[$key] = $this->Drivers['INI']->loadINI($key, $path);
                    break;
                case 'yml':
                    // Yaml 데이터를 읽어옵니다.
                    $this->_config[$key] = $this->Drivers['Yaml']->loadYaml($key, $path);
                    break;    
                case 'php':
                    //echo "php 설정파일을 읽어 읽습니다.<br>";
                    $this->_config[$key] = $this->Drivers['PHP']->loadPHP($key, $path);
                    break;    
            }
        }
        
        return $this;
    }

    /**
     * 설정파일을 자동등록 합니다.
     * 설정파일은 기본 BASE.conf 설정 디렉토리 입니다.
     */
    public function autoUpFiles()
    {
        //echo __METHOD__."<br>";
        //echo "환경설정 파일을 자동 로드합니다.<br>";
     
        $pathDir = ROOT.$this->_config['ENV']['path']['conf'];
        //echo $pathDir."<br>";

        if (is_dir($pathDir)) {
            //echo "OK] $pathDir 디렉터리 작업이 가능합니다.<br>";
            $dirARR = scandir($pathDir);
            
            for ($i=0;$i<count($dirARR);$i++) {
                // _로 시작하는 파일은 제외합니다.
                if ($dirARR[$i][0] == "_" || $dirARR[$i][0] == ".") {
                    //echo "파일을 제외합니다.".$dirARR[$i]."<br>";
                } else {
                    //echo $dirARR[$i]."<br>";
                    $this->setLoad($dirARR[$i]);
                }
                
            }

        } else {
            //echo "Err] $pathDir 디렉터리가 존재하지 않습니다.<br>";
        }
       
        return $this;
    }


    /**
     * 읽어올 환경설정 파일을 설정합니다.
     */
    public function setLoad($filename){
        // 파일명과 확장자를 확인합니다.
        $parts = pathinfo($filename);
        $this->_load[ $parts['filename'] ] = $parts['extension'];
        return $this;
    }

    

}