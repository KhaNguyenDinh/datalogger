@extends('User')
@section('title','home')
@section('content')
<?php
	$nhaMayGetId = $results['nhaMayGetId'];
	$khuVuc = $results['khuVuc'];
	$result_khuVuc = $results['result_khuVuc'];
	$list_total = $results['list_total'];
	$tab = "__";
	if ($list_total['connect']==0) {
		$hdt = $list_total['total']-$list_total['E']-$list_total['C']-$list_total['error'];
	}else{ $hdt = 0;}
	$color = ['N'=>'green','C'=>'yellow','E'=>'red','bt'=>'green','alert'=>'#f8fb7a','error'=>'#fb7a7a','hdt'=>'#37db00','connect'=>'gray','tong'=>'#0d20ff63'];

?>
@if ($result_khuVuc[0]['newTxt']!==null)
<div class="row"><center><h4 class="card-title">CÔNG TY : {{$nhaMayGetId->name_nha_may}}</h4> </center></div>
<!-- /////////////////// -->
<section class="section">
      <div class="row">
        <div class="col-lg-6">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">THỐNG KÊ TỔNG QUÁT</h5>
              <!-- Default Accordion -->
              <div class="accordion">
                <div class="accordion-item">
                  <div  class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
<center>
	<div id="donut-chart" style="height: 250px;"></div>	
</center>
<div class="row">
	<div class="col-sm-4 tt" style="background: <?=$color['connect']?> "> Mất kết nối <br> {{$list_total['connect']}} </div>
	<div class="col-sm-4 tt" style="background: <?=$color['hdt']?> "> Hoạt động tốt<br>{{$hdt}}</div>
	<div class="col-sm-4 tt" style="background: <?=$color['C']?> "> Hiệu chuẩn<br>{{$list_total['C']}}</div>
</div>
<div class="row">
	<div class="col-sm-4 tt" style="background: <?=$color['error']?> "> Vuợt chuẩn<br>{{$list_total['error']}}</div>
	<div class="col-sm-4 tt" style="background: <?=$color['E']?> "> Lỗi thiết bị<br>{{$list_total['E']}}</div>
	<div class="col-sm-4 tt" style="background: <?=$color['tong']?> "> Tổng<br>{{$list_total['total']}}</div>
</div>
<?php $brd = 'white'; ?>
<style type="text/css">
	.tt{
		/*border: ridge;*/
		border: 1px solid white;
	}
	.dv{
	  border-style: solid;
	  border-color: <?=$brd?> <?=$brd?> <?=$brd?> #efdfb0d9;
	  background: white;
	}
	table td{
		border: 1px solid white;
	}
	.dstt .datatable-top{
		display: none;
	}

</style>
<script type="text/javascript">
	var color =["<?=$color['connect'] ?>","<?=$color['hdt'] ?>","<?=$color['C'] ?>","<?=$color['error'] ?>","<?=$color['E'] ?>"];
	Morris.Donut({
		element: 'donut-chart',
		data: [
			{label: "Mất kết nối ", value: <?=$list_total['connect']?>},
			{label: "Hoạt động tốt", value: <?=$hdt ?>},
			{label: "Hiệu chuẩn", value: <?=$list_total['C'] ?> },
			{label: "Vượt chuẩn", value: <?=$list_total['error'] ?> },
			{label: "Lỗi thiết bị", value: <?=$list_total['E']?>},
			],
		colors: color,
	});
</script>
                    </div>
                  </div>
                </div>
              </div><!-- End Default Accordion Example -->

            </div>
          </div>

        </div>

        <div class="col-lg-6">

          <div class="card dstt">
            <div class="card-body">
              <h5 class="card-title">DANH SÁCH TRẠM THEO TRẠNG THÁI</h5>

              <!-- Default Accordion -->
              <div class="accordion">
                <div class="accordion-item">
                  <div  class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                    <div class="accordion-body">

						<table class="table datatable" style="width: 100%">
							<thead>
							<tr>
								<th>STT</th>
								<th>Trạm</th>
								<th>Loại</th>
								<th>Trạng thái</th>
								<th>Tình trạng</th>
								<th>Tín hiệu</th>
							</tr>
							</thead>
							<tbody>
							<?php 
							foreach ($result_khuVuc as $key => $value) {
								$list_check =$value['list_check'];
							echo "<tr>";
								echo "<td>".$key."</td>";
								echo "<td>".$value['khuVucGetId']['name_khu_vuc']."</td>";
								echo "<td >".$value['khuVucGetId']['loai']."</td>";
								if ($list_check['connect']==0) {
									if ($list_check['E']==0) {
										if ($list_check['C']==0) {
											$status = 'Bình thường';
											$background = $color['hdt'];
										}else{ 
											$status = 'Hiêu chuẩn';
											$background = $color['C'];
										}
									}else{ 
										$status = 'Lỗi thiết bị';
										$background = $color['E'];
									}

									echo "<td style='background:".$background."'>".$status."</td>";
								}else{ echo "<td>---</td>"; }
									/////////
								if ($list_check['connect']==0) {
									if ($list_check['error']==0) {
										if ($list_check['alert']==0) {
											$tt = 'Trong ngưỡng';
											$background = $color['hdt'];
										}else{ 
											$tt = 'Chuẩn bị vượt';
											$background = $color['alert'];
										}
									}else{ 
										$tt = 'Vượt ngưỡng';
										$background = $color['error'];
									}
									echo "<td style='background:".$background."'>".$tt."</td>";
								}else{ echo "<td>---</td>"; }
								if ($list_check['connect']==0) {
									$connect = 'Đang kết nối';
									$background = $color['hdt'];
								}else{ 
									$connect = 'Lỗi kết nối';
									$background = $color['connect'];
								}
								echo "<td style='background:".$background."'>".$connect."</td>";
							echo "</tr>";
							}
							 ?>
							</tbody>
						</table>
                    </div>
                  </div>
                </div>
              </div><!-- End Default Accordion Example -->

            </div>
          </div>

        </div>
      </div>
    </section>
    <!-- ///////// -->


<div class="row">

	<h5 class="card-title">TRẠM THEO THÀNH PHẦN MÔI TRƯỜNG</h5>
	<ul class="nav nav-tabs" id="myTab" role="tablist" >
		@foreach ($result_khuVuc as $key => $value)
		<a  href="{{URL::to('User/'.$nhaMayGetId->id_nha_may.'/'.$key)}}">
			<li class="nav-item" role="presentation">
			  <button class="nav-link <?php if($key==$key_view){echo'active';} ?> " href="{{URL::to('User/'.$nhaMayGetId->id_nha_may.'/'.$key)}}" id="home-tab" data-bs-toggle="tab" type="button" role="tab" aria-controls="home" aria-selected="true">
			  	{{$value['khuVucGetId']['name_khu_vuc']}}
			  </button>
			</li>
		</a>
		@endforeach
	</ul>
	<div class="tab-content pt-2">
	<!-- /////////// -->
	@if ($value = Arr::first($result_khuVuc))
		<div class="col-sm-12">
			<div class="row dv">
				@include('teamPlate.status')
				<div style="display: flex;">
					<h4>Dữ liệu mới nhất : </h4>
					<h4>{{$value['newTxt']->time}}</h4>
					@if ($value['list_check']['connect']==1)
						<h4 style="color: red">  Lỗi kết nối  </h4>
					@endif
				</div>
				@include('teamPlate.view_newtxt')
			</div>
	<!-- /////////////////////////		 --><br>
		  	@foreach ($result_khuVuc as $key => $value)
		  		@if($key==$key_view)
			    	<?php 
			    	$alert = $value['alert'];
			    	$txt = $value['txt'];
			    	 ?>
			    	<div class="row dv">
			    		<h5 class="card-title">DỮ LIỆU 1 GIỜ MỚI NHẤT</h5>
			    		@include('teamPlate.graph')</div><br>
			    	<div class="row dv">
			    		@include('teamPlate.alert')
			    	</div>
			    	
			    @endif
		    @endforeach
		</div><!-- End Default Tabs -->
	@endif
	</div>
</div>
@endif
<style type="text/css">
.a{
color: rgba(255, 255, 255, 0);
  border-radius: 50%;
  background: red;
}
/*.scoll{
  overflow-x: scroll;
  display: block;
}*/
.export{
    display: none;
}

</style>

<script>
$(document).ready(function() {
	var i=0; var reload = <?=$list_total['reload']?>;
    setInterval(function() {
		$.ajax({
		    url: "{{ route('checkData', ['id' => $nhaMayGetId->id_nha_may]) }}",  // id_nha_may
		    success: function(data) {
		    	if (data==1) {
		    		window.location.reload(0);
		    	}else{
		    		if (i%10==0 && i!=0 && reload==1) { window.location.reload(0); }
		    		i = i+1;
		    	}
		    },
		});
    }, 60000); // Thời gian kiểm tra sự thay đổi 60s
});
</script>

@stop()
<script type="text/javascript">
setTimeout(function(){
   window.location.reload(0);
}, 300000);
</script>