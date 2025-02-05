<?php
namespace Jiny\Config\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

use Jiny\WireTable\Http\Controllers\BaseController;
class AdminConfig extends BaseController
{
    // use \Jiny\Config\Http\Trait\Permit;       // 권환
    // use \Jiny\Config\Http\Trait\SetMenu;   // 메뉴

    public function __construct()
    {
        parent::__construct();  // setting Rule 초기화
        //$this->setVisit($this); // Livewire와 양방향 의존성 주입
    }

    // public function index(Request $request)
    // {
    //     // 사용자 인증정보 체크
    //     $user = Auth::user();
    //     if ($user) {
    //         // 메뉴 설정
    //         $this->setUserMenu($user);
    //     }


    //     // 권한
    //     $this->permitCheck();
    //     if($this->permit['read']) {
    //         $view = $this->checkEditView();

    //         return view($view,[
    //             'actions' => $this->actions,
    //             'request' => $request
    //         ]);
    //     }

    //     // 권한 접속 실패
    //     return view("jiny-config::error.permit",[
    //         'actions' => $this->actions,
    //         'request' => $request
    //     ]);
    // }


    // private function checkEditView()
    // {
    //     // 메인뷰 페이지...
    //     if (isset($this->actions['view']['main'])) {
    //         if (view()->exists($this->actions['view']['main'])) {
    //             return $this->actions['view']['main'];
    //         }
    //     }

    //     if (isset($this->actions['config']['type'])) {
    //         switch($this->actions['config']['type']) {
    //             case 'ini':
    //                 return "jiny-config::admin.layout_ini";
    //             case 'ymal':
    //                 return "jiny-config::admin.layout_ymal";
    //             case 'json':
    //                 return "jiny-config::admin.layout_json";
    //             default:
    //         }
    //     }

    //     // config 수정
    //     return "jiny-config::admin.layout_php";
    // }

}
