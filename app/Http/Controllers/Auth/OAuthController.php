<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Qrcode;
use App\Services\FcuApiService;
use App\Student;
use App\User;
use Carbon\Carbon;

class OAuthController extends Controller
{
    /**
     * @var FcuApiService
     */
    private $fcuApiService;

    /**
     * OAuthController constructor.
     * @param FcuApiService $fcuApiService
     */
    public function __construct(FcuApiService $fcuApiService)
    {
        $this->fcuApiService = $fcuApiService;
        $this->middleware('guest');
    }

    public function index()
    {
        //檢查設定
        $OAuthUrl = config('services.fcu-api.url-oauth');
        $clientId = config('services.fcu-api.client-id');
        $clientUrl = config('services.fcu-api.client-url');
        if (!$OAuthUrl || !$clientId || !$clientUrl) {
            return back()->with('warning', '目前未開放');
        }
        $data = [
            'client_id'  => $clientId,
            'client_url' => $clientUrl,
        ];
        //重導向到OAuth登入頁面
        $redirectUrl = $OAuthUrl . '?' . http_build_query($data);

        return redirect($redirectUrl);
    }

    public function login()
    {
        $userCode = \Request::get('user_code');
        if (!$userCode) {
            return redirect()->route('index')->with('warning', '登入失敗(c)');
        }

        //利用User Code取得學號
        $userInfo = $this->fcuApiService->getLoginUser($userCode);
        //檢查登入結果
        if (!is_array($userInfo) || !isset($userInfo['stu_id']) || empty($userInfo['stu_id'])) {
            return redirect()->route('index')->with('warning', '登入失敗(u)');
        }
        $nid = $userInfo['stu_id'];

        //嘗試找出使用者
        $email = $userInfo['stu_id'] . '@fcu.edu.tw';
        $user = User::where('email', $email)->first();
        //若使用者不存在
        if (!$user) {
            //先建立使用者
            //使用updateOrCreate而非create防同時重送
            $user = User::updateOrCreate([
                'email' => $email,
            ], [
                'name'        => $nid,
                'email'       => $email,
                'password'    => '',
                'confirm_at'  => Carbon::now(),
                'register_at' => Carbon::now(),
                'register_ip' => \Request::getClientIp(),
            ]);
        }
        //登入使用者
        auth()->login($user, true);

        //取得學生資料
        $stuInfo = $this->fcuApiService->getStuInfo($nid);
        if (!is_array($stuInfo) || !isset($stuInfo['status']) || $stuInfo['status'] != 1) {
            //無學生資料，直接結束流程
            return redirect()->route('index');
        }
        //嘗試取得學生
        $student = $user->student;
        //若學生不存在
        if (!$student) {
            //找出或建立學生
            $student = Student::query()->firstOrCreate([
                'nid' => $stuInfo['stu_id'],
            ], [
                'name'      => $stuInfo['stu_name'],
                'class'     => $stuInfo['stu_class'],
                'unit_name' => $stuInfo['unit_name'],
                'dept_name' => $stuInfo['dept_name'],
                'in_year'   => $stuInfo['in_year'],
                'gender'    => $stuInfo['stu_sex'],
            ]);
            //綁定學生
            $user->student()->save($student);
        }
        //若學生沒有QR Code
        if (!$student->qrcode) {
            //綁定QRCode
            $student->qrcode()->save(Qrcode::create());
        }
        //更新名稱
        $user->update(['name' => $stuInfo['stu_name']]);

        return redirect()->intended();
    }
}
