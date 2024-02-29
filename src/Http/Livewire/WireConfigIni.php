<?php
namespace Jiny\Config\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Validator;

class WireConfigIni extends Component
{
    use \Jiny\Config\Http\Livewire\Hook;
    use \Jiny\Config\Http\Livewire\Permit;

    public $actions;
    public $filename;
    public $redirect;
    public $forms=[];

    public function mount()
    {
        $this->permitCheck();
        $this->configLoading();
    }

    /**
     * 설정 파일명 얻기
     */
    private function filename($filename)
    {
        $path = config_path().DIRECTORY_SEPARATOR.$this->filename.".ini";
        return $path;
    }

    /**
     * 데이터 읽기
     */
    private function configLoading()
    {
        if(isset($this->actions['filename'])) {
            $this->filename = $this->actions['filename'];
        }

        if ($this->filename) {
            $path = $this->filename($this->filename);
            $path = str_replace(['/','\\'],DIRECTORY_SEPARATOR,$path);
            //dd($path);

            if (file_exists($path)) {
                $str = file_get_contents($path);
                $this->forms = \parse_ini_string($str);
                //$this->forms = config( str_replace('/','.',$this->filename) );
                //dd($this->forms);
            }
        }
    }

    /**
     * UI 출력
     */
    public function render()
    {
        if ($controller = $this->isHook("hookCreating")) {
            $controller->hookCreating($this);
        }

        return view("jiny-config::livewire.form");
    }


    public function submit()
    {
        $this->store();
        $this->goToIndex();
    }

    /**
     * 신규저장
     */
    public function store()
    {
        if($this->permit['create'] || $this->permit['update']) {
            //유효성 검사
            if (isset($this->actions['validate'])) {
                $validator = Validator::make($this->forms, $this->actions['validate'])->validate();
            }

            #// $this->forms['created_at'] = date("Y-m-d H:i:s");
            $this->forms['updated_at'] = date("Y-m-d H:i:s");

            // Before Hook
            if ($controller = $this->isHook("hookStoring")) {
                $form = $controller->hookStoring($this, $this->forms);
            } else {
                $form = $this->forms;
            }


            // 설정값을 파일로 저장
            if ($this->filename) {
                //$file = $this->convToPHP($form);
                $file = $this->array_to_ini_string($form);

                // PHP 설정파일명
                $path = $this->filename($this->filename);

                // 설정 디렉터리 검사
                $info = pathinfo($path);
                if(!is_dir($info['dirname'])) mkdir($info['dirname'],0755, true);

                file_put_contents($path, $file);
            }

            // After Hook
            if ($controller = $this->isHook("hookStored")) {
                $controller->hookStored($this, $form);
            }

        } else {
            $this->popupPermitOpen();

            // 다시 데이터 로딩...
            $this->configLoading();
        }
    }

    /*
    public function convToPHP($form)
    {
        $str = json_encode($form, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
        return $str;
    }
    */

    private function array_to_ini_string($array, $parent = '')
    {
        $ini_string = '';
        //dd($array);
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                // 재귀적으로 배열 내부를 처리합니다.
                $section = empty($parent) ? $key : "$parent.$key";
                $ini_string .= $this->array_to_ini_string($value, $section);
            } else {
                // 키와 값을 INI 형식에 맞게 추가합니다.
                if($parent) {
                    $ini_string .= "$parent.$key = $value\n";
                } else {
                    $ini_string .= "$key = $value\n";
                }

            }
        }

        return $ini_string;
    }

    public function clear()
    {
        $this->forms = [];
    }

    private function goToIndex()
    {
        if ($this->redirect) {
            return redirect()->to($this->redirect);
        }
    }

}
