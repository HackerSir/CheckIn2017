@extends('layouts.base')

@section('title', $teaParty->name . ' - 編輯迎新茶會')

@section('buttons')
    <a href="{{ route('tea-party.show', $teaParty) }}" class="btn btn-secondary">
        <i class="fa fa-arrow-left" aria-hidden="true"></i> 檢視迎新茶會
    </a>
@endsection

@section('main_content')
    <div class="card">
        <div class="card-body">
            {{ bs()->openForm('patch', route('tea-party.update', $teaParty), ['model' => $teaParty]) }}
            @include('tea-party.form')

            <div class="row">
                <div class="mx-auto">
                    {{ bs()->submit('確認', 'primary')->prependChildren(fa()->icon('check')->addClass('mr-2')) }}
                </div>
            </div>
            {{ bs()->closeForm() }}
        </div>
    </div>
@endsection
