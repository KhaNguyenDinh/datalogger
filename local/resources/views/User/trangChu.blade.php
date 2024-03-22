@extends('User')
@section('title','Trang chủ')
@section('content')
<?php
	$nhamayGetId = $results['nhamayGetId'];
	$khuvuc = $results['khuvuc'];
	$result_khuvuc = $results['result_khuvuc'];
	$total = $results['total'];
	$total_error = $results['total_error'];
	$total_alert = $results['total_alert'];
	$total_error_connect = $results['total_error_connect'];
	$great = $total - $total_error - $total_alert;
	$color_great='green'; $color_alert='#ff8400';$color_error="red";$color_connect='gray';
	$tab = "__";
?>
<div class="row">
	<div class="col-sm-6">
		<div class="col-sm-6">
			<center>
				<h3>Tổng số trạm</h3>
				<div class="circle" style="font-size: 40px"><?=$total?></div>
			</center>
			
<style type="text/css">
.circle {
    width: 60px;
    height: 60px;
    line-height: 60px;
    text-align: center;
    border-radius: 50%;
    background-color: #ffdf7e;
}
</style>
			<i class="a" style="background: <?=$color_great ?>">---</i><span> {{$great}} Bình thường</span><br>
			<i class="a" style="background: <?=$color_alert ?>">---</i><span> {{$total_alert}} Chuẩn bị vượt ngưỡng</span><br>
			<i class="a" style="background: <?=$color_error ?>">---</i><span> {{$total_error}} Vượt ngưỡng xả thải</span><br>
			<i class="a" style="background: <?=$color_connect ?>">---</i><span> {{$total_error_connect}} Mất tín hiệu</span><br>
		</div>
		<div class="col-sm-6">
			<div id="donut-chart" style="height: 250px;"></div>
			<script type="text/javascript">
			Morris.Donut({
			    element: 'donut-chart',
			    data: [
			        {label: "Great", value: <?=$great?>},
			        {label: "Alert", value: <?=$total_alert ?>},
			        {label: "Error", value: <?=$total_error ?> },
			        {label: "Diconnect", value: <?=$total_error_connect?>}
			    ],
			    colors: ['<?=$color_great ?>', '<?=$color_alert ?>','<?=$color_error ?>','<?=$color_connect ?>'],
			});
			</script>
		</div>
	</div>

	<div class="col-sm-6">
		<table class="table table-bordered">
			<tr>
				<th>STT</th>
				<th>Trạm</th>
				<!-- <th>Nhà Máy</th> -->
				<th>Loại</th>
				<th>Trạng thái</th>
				<th>Tình trạng</th>
				<th>connect</th>
			</tr>
			@foreach ($result_khuvuc as $key => $value)
			<tr>
				<td>{{$key+1}}</td>
				<td>{{$value['khuvucGetId']['name_khu_vuc']}}</td>
<!-- 				<td>{{$nhamayGetId->name_nhaMay}}</td> -->
				<td>{{$value['khuvucGetId']['loai']}}</td>
				<td>
					@if($value['status']=='norm')
					<i class="a" style="background: <?=$color_great ?>">---</i> Đang hoạt động
					@elseif($value['status']=='alert')
					<i class="a" style="background: <?=$color_alert ?>">---</i> Hiệu Chuẩn
					@elseif($value['status']=='error')
					<i class="a" style="background: <?=$color_error ?>">---</i> Thiết bị lỗi
					@endif
				</td>
				<td>
					@if($value['TrangThai']=='norm')
					<i class="a" style="background: <?=$color_great ?>">---</i> Bình thường
					@elseif($value['TrangThai']=='alert')
					<i class="a" style="background: <?=$color_alert ?>">---</i> Chuẩn bị vượt ngưỡng
					@elseif($value['TrangThai']=='error')
					<i class="a" style="background: <?=$color_error ?>">---</i> Vượt ngưỡng xả thải
					@endif
				</td>
				<td>
					@if($value['connect']=='')
						<i class="a" style="background: green">---</i> Hoạt động tốt
					@else
						<i class="a" style="background: <?=$color_connect ?>">---</i> {{$value['connect']}}
					@endif
				</td>
			</tr>
			@endforeach

		</table>
	</div>
</div>  <!-- end row1 -->

<div class="row">
	<ul class="nav nav-tabs" id="myTab" role="tablist" >
		@foreach ($result_khuvuc as $key => $value)
		<a  href="{{URL::to('User/'.$nhamayGetId->id_nha_may.'/'.$key)}}">
			<li class="nav-item" role="presentation">
<<<<<<< HEAD
			  <button class="nav-link <?php if($key==$key_view){echo'active';} ?> " href="{{URL::to('User/'.$nhamayGetId->id_nha_may.'/'.$key)}}" id="home-tab" data-bs-toggle="tab" type="button" role="tab" aria-controls="home" aria-selected="true">
			  	[ {{$value['khuvucGetId']['name_khu_vuc']}} ]
=======
			  <button class="nav-link <?php if($key==$key_view){echo'active';} ?> " href="{{URL::to('User/'.$nhaMayGetId->id_nhaMay.'/'.$key)}}" id="home-tab" data-bs-toggle="tab" type="button" role="tab" aria-controls="home" aria-selected="true">
			  	[ {{$value['khuVucGetId']['name_khuVuc']}} ]
>>>>>>> 055ea115722d8d62f9cb442bf39246714ebc4cd0
			  </button>
			</li>
		</a>
		@endforeach
	</ul>
	<div class="tab-content pt-2">
	  	@foreach ($result_khuvuc as $key => $value)
	  		@if($key==$key_view)
		    	<?php 
		    	$alert = $value['alert'];
		    	$txt = $value['txt'];
		    	 ?>
		    	<div class="row">@include('User.teamPlate.graph')</div>
		    	<div class="row">@include('User.teamPlate.status')</div>
		    	<div class="row">@include('User.teamPlate.alert')</div>
		    @endif
	    @endforeach
	</div><!-- End Default Tabs -->
</div>

<style type="text/css">
.a{
	color: rgba(255, 255, 255, 0);
  border-radius: 50%;
  background: red;
}
</style>
<script type="text/javascript">
setTimeout(function(){
   window.location.reload(0);
}, 300000);
</script>
@stop()
