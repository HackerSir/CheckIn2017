<ul class="list-group">
    @forelse($student->records as $record)
        <li class="list-group-item">
            <div>
                <h4>{{ link_to_route('clubs.show', $record->club->name, $record->club) }}
                    {!! $record->club->clubType->tag ?? '' !!}
                    @if(!$record->club->is_counted)
                        <span class='badge badge-secondary'>不列入集點</span>
                    @endif
                </h4>
                <small>
                    {{ $record->created_at }}
                    （{{ (new \Carbon\Carbon($record->created_at))->diffForHumans() }}）
                </small>
                @if($showFeedbackButton ?? false)
                    @if($student->feedback->contains('club_id', $record->club->id))
                        @php($feedback = $student->feedback->filter(function($item)use($record){return $item->club_id == $record->club->id;})->first())
                        <div class="float-md-right">
                            <a href="{{ route('feedback.show', $feedback) }}" class="btn btn-success">
                                <i class="fa fa-search"></i> 檢視回饋資料
                            </a>
                        </div>
                    @else
                        <div class="float-md-right">
                            <a href="{{ route('feedback.create', $record->club) }}" class="btn btn-primary">
                                <i class="fa fa-edit"></i> 填寫回饋資料
                            </a>
                        </div>
                    @endif
                @endif
            </div>
        </li>
    @empty
        <li class="list-group-item">
            <div>
                尚無打卡紀錄，快去打卡吧
            </div>
        </li>
    @endforelse
</ul>
