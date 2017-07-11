@extends('layouts.app')

@section('title', '兩步驟認證')

@section('content')
    <div class="card">
        <div class="card-header">
            兩步驟認證
        </div>
        <div class="card-block">
            <form role="form" method="POST" action="{{ route('profile.2fa.toggle') }}" onsubmit="return confirm('確定嗎？')">
                {{ csrf_field() }}

                <div class="form-group row">
                    <label class="col-md-4 col-form-label">目前狀態</label>

                    <div class="col-md-6">
                        @if($user->google2fa_secret)
                            <span class="form-text text-success">已啟用</span>
                        @else
                            <span class="form-text text-danger">未啟用</span>
                        @endif
                    </div>
                </div>
                @if(isset($google2faQRCodeUrl))
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label">QR Code</label>

                        <div class="col-md-6">
                            <img src="{{ $google2faQRCodeUrl }}"><br/>
                            請以 <a
                                href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2"
                                target="_blank">Google Authenticator</a> 掃描此二維條碼，並於下方輸入驗證碼
                        </div>
                    </div>
                @endif

                <div class="form-group row{{ $errors->has('one_time_password') ? ' has-danger' : '' }}">
                    <label for="one_time_password" class="col-md-4 col-form-label">驗證碼</label>

                    <div class="col-md-6">
                        <input id="one_time_password" type="text"
                               class="form-control{{ $errors->has('one_time_password') ? ' form-control-danger' : '' }}"
                               name="one_time_password" required autofocus>
                        <span class="form-text">請輸入 <a
                            href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2"
                            target="_blank">Google Authenticator</a> 顯示之驗證碼</span>

                        @if ($errors->has('one_time_password'))
                            <span class="form-control-feedback">
                                <strong>{{ $errors->first('one_time_password') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-8 offset-md-4">
                        @if($user->google2fa_secret)
                            <button type="submit" class="btn btn-danger">停用</button>
                        @else
                            {{ Form::hidden('toggle', 'on') }}
                            <button type="submit" class="btn btn-primary">啟用</button>
                        @endif

                        <a href="{{ route('profile') }}" class="btn btn-secondary">返回個人資料</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection