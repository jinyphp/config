<?php
namespace Jiny\Config\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Validator;

/**
 * 입력 데이터를 php 설정파일로 저장합니다.
 */

class WireConfig extends Component
{
    use \Jiny\WireTable\Http\Trait\Hook;
    use \Jiny\WireTable\Http\Trait\Permit;

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
     * 설정 경로 얻기
     */
    private function filename($filename)
    {
        $path = config_path().DIRECTORY_SEPARATOR.$this->filename.".php";
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
            if (file_exists($path)) {
                $this->forms = config( str_replace('/','.',$this->filename) );
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

        $view_layout = "jiny-config::livewire.form";
        return view($view_layout);
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
                $validator = Validator::make(
                    $this->forms,
                    $this->actions['validate'])
                ->validate();
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
                $file = $this->convToPHP($form);

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
            //dd("권환 없음");
            $this->popupPermitOpen();

            // 다시 데이터 로딩...
            $this->configLoading();
        }
    }

    public function convToPHP($form)
    {
        $str = json_encode($form, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );

        // php 배열형태로 변환
        $str = str_replace('{',"[",$str);
        $str = str_replace('}',"]",$str);
        $str = str_replace('":',"\"=>",$str);
        //$str = str_replace(',',",\r\n",$str);

        $file = <<<EOD
        <?php
        return $str;
        EOD;


        return $file;
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
