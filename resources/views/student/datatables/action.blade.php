<a href="{{ route('student.show', $id) }}" class="btn btn-primary" title="檢視">
    <i class="fa fa-search"></i>
</a>
<a href="{{ route('student.edit', $id) }}" class="btn btn-primary" title="編輯">
    <i class="fa fa-edit"></i>
</a>

{!! Form::open(['route' => ['student.fetch', $id], 'style' => 'display: inline', 'method' => 'PUT']) !!}
<button type="submit" class="btn btn-primary" title="更新學生">
    <i class="fa fa-sync"></i>
</button>
{!! Form::close() !!}
