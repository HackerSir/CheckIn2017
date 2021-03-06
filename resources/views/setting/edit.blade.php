@extends('layouts.base')

@section('title', '活動設定')

@section('main_content')
    <div class="card">
        <div class="card-body">
            {{ bs()->openForm('post', route('setting.update')) }}
            {{ bs()->formGroup(bs()->text('start_at', Setting::get('start_at'))->required())->class('required')->label('開始打卡時間')->showAsRow() }}
            {{ bs()->formGroup(bs()->text('end_at', Setting::get('end_at'))->required())->class('required')->label('結束打卡時間')->showAsRow() }}
            {{ bs()->formGroup(bs()->input('number', 'target', Setting::get('target'))->required()->attributes(['min' => 0]))->class('required')->label('打卡目標數量')->showAsRow() }}
            {{ bs()->formGroup(bs()->input('number', 'zone_target', Setting::get('zone_target'))->required()->attributes(['min' => 0]))->class('required')->label('區域收集目標數量')->showAsRow() }}
            {{ bs()->formGroup(bs()->text('feedback_create_expired_at', Setting::get('feedback_create_expired_at'))->required())->class('required')->label('回饋資料填寫期限')->showAsRow() }}
            {{ bs()->formGroup(bs()->text('feedback_download_expired_at', Setting::get('feedback_download_expired_at'))->required())->class('required')->label('回饋資料檢視與下載期限')->showAsRow() }}
            {{ bs()->formGroup(bs()->text('club_edit_deadline', Setting::get('club_edit_deadline'))->required())->class('required')->label('社團資料編輯期限')->showAsRow() }}

            <div class="row">
                <div class="mx-auto">
                    {{ bs()->submit('更新設定', 'primary')->prependChildren(fa()->icon('check')->addClass('mr-2')) }}
                </div>
            </div>
            {{ bs()->closeForm() }}
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(function () {
            $('input[name=start_at]').datetimepicker();
            $('input[name=end_at]').datetimepicker();
            $('input[name=feedback_create_expired_at]').datetimepicker();
            $('input[name=feedback_download_expired_at]').datetimepicker();
            $('input[name=club_edit_deadline]').datetimepicker();
        });
    </script>
@endsection
