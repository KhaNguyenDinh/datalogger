@extends('Admin')
@section('title','update')
@section('action')

<div class="row">
	<div class="col-sm-4"></div>
	<div class="col-sm-4" style="background: #eff7d9">
		<center><h1>Update</h1></center>
		<form method="post" action="{{URL::to('Admin/camera/postupdate/'.$data->id_camera)}}" enctype="multipart/form-data">
			@csrf
			<input type="hidden" name="id_khu_vuc" value="{{$data->id_khu_vuc}}" required class="form-control">
			<label>camera</label>
			<input type="text" name="name_camera" value="{{$data->name_camera}}" required class="form-control">
			<label>rtsp</label>
			<input type="text" name="link_rtsp" value="{{$data->link_rtsp}}" required class="form-control">
			<input type="submit" value="update" class="btn btn-primary">
		</form>
		<a href="{{URL::to('Admin/camera/'.$data->id_khu_vuc)}}" >back</a>
	</div>
	<div class="col-sm-4"></div>
</div>


@stop()
