<ul>
@foreach ($obj->roles as $role)
    <li><a href="javascript:delRole('{{$role->name}}')">[-]</a> {{ $role->name }}</li>
@endforeach
</ul>
