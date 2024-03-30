<?php
namespace Jiny\Config\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Validator;

class WireConfigPHP extends Component
{
    use \Jiny\Config\Http\Trait\Hook;
    use \Jiny\Config\Http\Trait\Permit;

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

        //dd($this->forms);

        $form_layout = "jiny-config::livewire.form-layout";
        if(isset($this->actions['form_layout'])) {
            $form_layout = $this->actions['form_layout'];
        }
        return view($form_layout);
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

                $str = $this->convToPHP($form);
$file = <<<EOD
<?php
return $str;
EOD;
//dd($file);
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

    public function convToPHP($form, $level=1)
    {
        /*
        $str = json_encode($form, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );

        // php 배열형태로 변환
        $str = str_replace('{',"[",$str);
        $str = str_replace('}',"]",$str);
        $str = str_replace('":',"\"=>",$str);
        //$str = str_replace(',',",\r\n",$str);
        */
        $str = "[\n"; //초기화
        $lastKey = array_key_last($form);
        //dump($lastKey);
        foreach($form as $key => $value) {
            for($i=0;$i<$level;$i++) $str .= "\t";

            if(is_array($value)) {
                $str .= "'$key'=>".''.$this->convToPHP($value,$level+1).'';
            } else {
                //$str .= "'$key'=>".'"'.$value.'"';
                $str .= "'$key'=>".'"'.addslashes($value).'"';
            }

            //dd($str);

            if($key != $lastKey) $str .= ",\n";
        }

        $str .= "\n";

        if($level>1) {
            for($i=0;$i<$level-1;$i++) $str .= "\t";
        }

        $str .= "]";


        return $str;
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
