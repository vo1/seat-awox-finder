@can('awox.update')
<a href="{{ route('awox.form.read', ['id' => $row->id ])}}">
    <span class="fas fa-edit"></span>
</a>
@endcan
@can('awox.delete')
<a href="{{ route('awox.delete', ['id' => $row->id ])}}">
    <span class="fas fa-trash"></span>
</a>
@endcan