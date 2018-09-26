<?php
/*
 * This file is part of the jinyPHP package.
 *
 * (c) hojinlee <infohojin@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jiny\Config;

use \Jiny\Core\Base\File;

/**
 * > 싱글톤
 * 설정파일을 읽어 처리합니다.
 */
class Config
{

    /**
     * 설정값 저장되어 있습니다.
     */
    protected $_config=[];

    /**
     * 설정파일 목록을 지정합니다.
     */
    protected $_file=[];

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
     * 싱글턴 인스턴스를 생성합니다.
     */
    public static function instance()
    {
        if (!isset(self::$_instance)) {
                   
            // 인스턴스를 생성합니다.
            // 자기 자신의 인스턴스를 생성합니다.                
            self::$_instance = new self();

            // 드라이버를 인스턴스를 로드합니다.
            self::$_instance->Drivers['Yaml'] = new \Jiny\Config\Drivers\Yaml(self::$_instance);
            self::$_instance->Drivers['INI'] = new \Jiny\Config\Drivers\INI(self::$_instance);
            self::$_instance->Drivers['PHP'] = new \Jiny\Config\Drivers\PHP(self::$_instance);


            // 시작위치를 지정합니다.
            self::$_instance->_config['ROOT'] = ROOT_PUBLIC;


            //프레임워크 설정파일을 읽어옵니다.
            // .env.php
            $intFile = ".env";
            if(file_exists(ROOT.DS.$intFile.".php")){
                self::$_instance->_config['ENV'] = self::$_instance->Drivers['PHP']->load($intFile, ROOT.DS);
            } else {
                echo "초기 환경파일 설정을 읽어 올수가 없습니다. <br>";
                echo "시스템을 종료합니다.";
                exit;
            }   

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
            // 키값으로 파일설정
            $this->_file[$key] = $filename;
        } else {
            // 키값이 없는 경우 파일명으로 설정
            $parts = pathinfo($filename);
            $this->_file[ $parts['filename'] ] = $filename;
        }
    }


    /**
     * 파일을 로드 합니다.
     */
    public function loadFiles()
    {
        // 프레임웍 설정에서 환경설정 파일 폴더를 확인합니다.
        $path = $this->path(); 

        // 설정파일을 드라이버를 통하여 읽어들입니다.
        // 향후 전략패턴으로 업그레이드.
        foreach ($this->_file as $key => $name) {
            $parts = pathinfo($name);

            switch ($parts['extension']) {
                case 'ini':
                    //echo "ini 설정파일을 읽어 읽습니다.<br>";
                    $this->_config[$key] = $this->Drivers['INI']->load($parts['filename'], $path);
                    break;

                case 'yml':
                    // Yaml 데이터를 읽어옵니다.
                    $this->_config[$key] = $this->Drivers['Yaml']->load($parts['filename'], $path);
                    break; 

                case 'php':
                    //echo "php 설정파일을 읽어 읽습니다.<br>";
                    $this->_config[$key] = $this->Drivers['PHP']->load($parts['filename'], $path);
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
                if (isset($arr[$name])) {
                    $arr = $arr[$name];
                } else {
                    // 일치하는 값이 없을 경우
                    return null;
                }               
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


    /**
     * 객체를 함수로 호출시 동작
     */
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
     * 설정파일을 자동등록 합니다.
     */
    public function autoSet()
    {   
        // 프레임웍 설정에서 환경설정 파일 폴더를 확인합니다.
        $pathDir = $this->path(); 

        if (is_dir($pathDir)) {
            // echo "OK] $pathDir 디렉터리 작업이 가능합니다.<br>";
            $dirARR = scandir($pathDir);
            
            for ($i=0;$i<count($dirARR);$i++) {
                // _로 시작하는 파일은 제외합니다.
                if ($dirARR[$i][0] == "_" || $dirARR[$i][0] == ".") {
                    // echo "파일을 제외합니다.".$dirARR[$i]."<br>";
                } else {              
                    $this->setFile($dirARR[$i]);
                }                
            }

        } else {
            //echo "Err] $pathDir 디렉터리가 존재하지 않습니다.<br>";
        }
       
        return $this;
    }


    /**
     * 프레임웍 설정에서 환경설정 파일 폴더를 확인합니다.
     */
    public function path()
    {
        $path = conf("ENV.path.conf");
        $path = rtrim($path, "/")."/";
        return ROOT.File::osPath($path); 
    }


    /**
     * 싱글톤 처리
     */
    private function __construct()
    {
        //
    }

    /**
     * 싱글톤 처리
     */
    private function __clone()
    {
        //
    }
    
    /**
     * 
     */

}