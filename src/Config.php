<?php

namespace Jiny\Config;

/**
 * 싱글톤 방식으로 동작합니다.
 */
class Config 
{
    private function __construct()
    {
        // 싱글톤
    }

    private function __clone()
    {
        // 싱글톤
    }


    // 인스턴스 저장 프로퍼티
    private static $_instance;

    // 설정값을 저장하는 프로퍼티
    private $_config=[];
    private $_load=[];

    // 싱글톤 인스턴스를 생성
    // 메소드 처리입니다.
    public static function instance()
    {
        if (!isset(self::$_instance)) {
                   
            // 인스턴스를 생성합니다.
            // 자기 자신의 인스턴스를 생성합니다.
            //echo "인스턴스를 생성합니다.<br>";     
            self::$_instance = new self();

            //Debug::out("기본 환경 설정값을 읽어 옵니다.");
            self::$_instance->_config['ENV'] = self::loadPHPReturn(".env", "..".DS);

            return self::$_instance;

        } else {
            // 인스턴스가 중복
            //echo "인스턴스 중복.<br>";     
        }
    }    

    public function __invoke($key=NULL)
    {
        return $this->data($key);
    }

    public function data($key=NULL)
    {
        if ($key) {
            $a = \explode(".",$key);
            $v = $this->_config;
            foreach ($a as $value){
                $v = $v[$value];
            }
            return $v;

        } else {
            return $this->_config;
        }        
    }

    public function set($key, $value)
    {
        $this->_config[$key] = $value;
    }



    /**
     * 설정파일을 자동등록 합니다.
     * 설정파일은 기본 BASE.conf 설정 디렉토리 입니다.
     */
    public function autoUpFiles()
    {
        //echo "환경설정 파일을 자동 로드합니다.<br>";
        $pathDir = $this->_config['ENV']['conf'];

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


    // 전체 설정값을 읽어 옵니다.
    public function load()
    {
        //Debug::out("전체 설정파일을 읽어옵니다.");
        $path = rtrim($this->_config['ENV']['conf'], "/")."/";
        foreach ($this->_load as $key => $value) {
            switch ($value) {
                case 'ini':
                    //echo "ini 설정파일을 읽어 읽습니다.<br>";
                    $this->_config[$key] = $this->loadINI($key, $path);
                    break;
                case 'yml':
                    break;    
                case 'php':
                    //echo "php 설정파일을 읽어 읽습니다.<br>";
                    $this->_config[$key] = $this->loadPHPReturn($key, $path);
                    break;    
            }
        }
        
        return $this;
    }

    /**
     * 읽어올 환경설정 파일을 설정합니다.
     */
    public function setLoad($filename){
        //Debug::out($filename." 설정파일을 등록합니다.");

        $parts = pathinfo($filename);
        $this->_load[ $parts['filename'] ] = $parts['extension'];
        return $this;
    }





    /**
     * PHP Return 배열로 된 설정값을 읽어 옵니다.
     * [
     *  "name"=>"aaa"
     * ]
     */
    public function loadPHPReturn($name, $path=NULL)
    {
        if ($name) {
            if ($path) {
                $filename = $path.$name.".php";
            } else {
                $filename = $name.".php";
            }

            if (file_exists($filename)) {
                //Debug::out(">>>".$filename." 설정값을 읽어 옵니다.");
                return include ($filename);
            } else {
                // 파일이 존재하지 않습니다.
                //Debug::out(">>>파일이 존재하지 않습니다.");
            }            
        } else {
            // 파일 이름이 없습니다.
        }
    }

    // ini 설정파일을 로드합니다.
    public function loadINI($name, $path=NULL)
    {
        if ($name) {
            if ($path) {
                $filename = $path.$name.".ini";
            } else {
                $filename = $name."ini";
            }
            //echo "ini 파일 = ".$filename."<br>";

            if (\file_exists($filename)) {
                //Debug::out(">>>".$name." 설정값을 읽어 옵니다.");
                $str = file_get_contents($filename);           
                return \parse_ini_string($str);       
            } else {
                // 파일이 없습니다.
                Debug::out(">>>파일이 존재하지 않습니다.");
            }            
        } else {
            // 이름이 없습니다.
        }

    }

}