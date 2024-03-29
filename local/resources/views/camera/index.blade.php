@extends('Admin')
@section('title','index')
@section('action')

<center><h1><a href="{{URL::to('Admin/khuVuc/'.$id_nha_may)}}"> Of : {{$name}}</a></h1></center>

<a href="{{URL::to('Admin/camera/insert/'.$id_khu_vuc)}}" class="btn btn-primary">insert</a>
<table class="table table-bordered">
	<tr>
		<th>STT</th>
		<th>name</th>
		<th>rtsp</th>
		<th>Action</th>
	</tr>
@foreach ($data as $key => $value)
	<tr>
		<td>{{$key+1}}</td>
		<td>{{$value->name_camera}}</td>
		<td class="link">{{$value->link_rtsp}}</td>
		<td>
			<a href="{{URL::to('Admin/camera/update/'.$value->id_camera)}}" class="btn btn-primary">update</a>
			<a href="{{URL::to('Admin/camera/delete/'.$value->id_camera)}}" class="btn btn-danger" 
				onclick="return confirm('Bạn có chắc chắn muốn xóa không?')">Delete</a>
		</td>
	</tr>
@endforeach
</table>

@stop()
