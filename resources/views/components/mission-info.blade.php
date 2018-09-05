@if(isset($student->ticket))
    <div class="alert alert-success text-center">
        <i class="fas fa-ticket-alt"></i> 抽獎編號 <i class="fas fa-ticket-alt"></i>
        <h3>{{ sprintf("%04d", $student->ticket->id) }}</h3>
        <span class="text-danger">任務已完成，可至服務台兌換遊戲區代幣</span>
    </div>
@else
    @if(!$student->is_freshman)
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i> 抽獎活動限<strong>大學部新生</strong>參加，即使完成任務，也無法參加抽獎
        </div>
    @else
        <div class="alert alert-info">
            <i class="fas fa-exclamation-triangle"></i> 完成以下任務，即可取得抽獎編號
        </div>
    @endif
@endif
<dl class="row" style="font-size: 120%">
    <dt class="col-md-3 col-lg-2">打卡集點</dt>
    <dd class="col-md-7 col-lg-10">
        @if($student->has_enough_counted_records)
            <span class="text-success">
                <i class="far fa-check-square"></i> 已完成
            </span>
        @else
            <span class="text-danger">
                <i class="far fa-square"></i> 未完成
            </span>
            <span>（{{ $student->countedRecords->count() }} / {{ \Setting::get('target') }}）</span>
        @endif
    </dd>
    <dt class="col-md-3 col-lg-2">填寫平台問卷</dt>
    <dd class="col-md-7 col-lg-10">
        <div class="mb-2">
            @if($student->studentSurvey)
                <span class="text-success">
                    <i class="far fa-check-square"></i> 已完成
                </span>
            @else
                <span class="text-danger">
                    <i class="far fa-square"></i> 未完成
                </span>
            @endif
        </div>
    </dd>
</dl>