<?php
namespace App\Http\Controllers;
use Validator;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Eloquent\Collection;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;
use App\nhaMay;
use App\khuVuc;
use App\alert;
use App\camera;
use App\account;
use App\viTri;
use DateTime;
use Illuminate\Pagination\LengthAwarePaginator;


class dulieuTXTController extends Controller
{
	public function checkData($id_nha_may) {
		$currentDateTime = new DateTime('now', new \DateTimeZone('Asia/Ho_Chi_Minh'));
		$formattedDateTime = $currentDateTime->format('Y-m-d H:i:s');
		$date1 = DateTime::createFromFormat('Y-m-d H:i:s',$formattedDateTime);
		$nhaMayGetId = nhaMay::find($id_nha_may);
		$khuVuc = khuVuc::where('id_nha_may', $id_nha_may)->orderBy('id_khu_vuc')->get();
		$returm = 0;
		foreach ($khuVuc as $key => $value) {
			$newTxt = DB::table($value->folder_txt)
	            ->orderByDesc('time')->first();

	        if (!empty($newTxt)) {
	            $time =  $newTxt->time;
	            $date2 = DateTime::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s",strtotime($time)));
				$interval = $date1->diff($date2);
				if ($interval->y== 0 && $interval->m == 0 && $interval->d == 0 && $interval->h == 0 && $interval->i<=1) {
					$returm = 1;
				}
			}
		}
	    return $returm;
	}

	public function relay($id_nha_may){
		$currentDateTime = new DateTime('now', new \DateTimeZone('Asia/Ho_Chi_Minh'));
		$formattedDateTime = $currentDateTime->format('Y-m-d H:i:s');
		$date1 = DateTime::createFromFormat('Y-m-d H:i:s',$formattedDateTime);
		////////////////////////////
		$nhaMayGetId = nhaMay::find($id_nha_may);
		$khuVuc = khuVuc::where('id_nha_may', $id_nha_may)->orderBy('id_khu_vuc')->get();
		$list_total=['N'=>0,'C'=>0,'E'=>0,'bt'=>0,'alert'=>0,'error'=>0,'load'=>0,'connect'=>0,'total'=>count($khuVuc),'reload'=>0];
		$list_error=[];
		$name_status = ['N','C','E','bt','alert','error','load','connect'];

		foreach ($khuVuc as $key => $value) {
			$alert =alert::where('id_khu_vuc', $value->id_khu_vuc)->get();
			$newTxt = DB::table($value->folder_txt)
                ->orderByDesc('time')->first();
            $list_check = ['N'=>0,'C'=>0,'E'=>0,'bt'=>0,'alert'=>0,'error'=>0,'load'=>0,'connect'=>0];
            if (!empty($newTxt)) {
	            $time =  $newTxt->time;
	            $date2 = DateTime::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s",strtotime($time)));
				$interval = $date1->diff($date2);
				$connect = "";
				if ($interval->y > 0) {$connect = "Mất tín hiệu";
				} elseif($interval->m > 0) {$connect = "Mất tín hiệu";
				} elseif($interval->d > 0) {$connect = "Mất tín hiệu";
				} elseif($interval->h > 0) {$connect = "Mất tín hiệu";
				} elseif($interval->i > 10) {$connect = "Mất tín hiệu";}
				if ($connect!=='') {
					$list_check['load'] = $list_check['connect'] = 1;
				}
				/////////////////////////////////////////////////////
				if ($list_check['connect']==0) {
					$arrayData = json_decode($newTxt->data, true);
					$list_alert = [];
					if ($alert) {
						foreach ($alert as $key => $value_alert) {
							if ($value_alert['enable']=="YES") {
								$list_alert[$value_alert['name_alert']]=$value_alert;
							}
						}
					}
					foreach ($arrayData as $key1 => $value1) {
						if ($list_check['E'] == 0 ) {
							if ($list_check['C'] == 0 ) {
								switch ($value1['status']) {
									case 0: $list_check['N'] = 1;
										if (array_key_exists($value1['name'], $list_alert)) {
											$value2 = $list_alert[$value1['name']];
											if ($list_check['error']==0) {
												if ($list_check['alert']==0) {
													if ($value1['number']<$value2['minmin']||$value1['number']>$value2['maxmax']) {
														$list_check['error']=1;
													}elseif ($value1['number']<$value2['min']||$value1['number']>$value2['max']) {
														$list_check['alert']=1;
													}
												}
											}
										}							
										break;
									case 1:$list_check['C'] = 1; break;
									case 2:$list_check['E'] = 1; break;
								}
							}
						}
					}

				}
			}	
			$name_check = ['E','error','connect'];
			$error='';
			foreach ($name_check as $key => $name) {
				if ($list_check[$name]>0) {
					if ($name=='connect') {
						$error = 'Mất kết nối <br>';
					}elseif($name=='error'){
						$error = 'Vượt ngưỡng <br>';
					}elseif($name=='E'){
						$error = 'Lỗi thiết bị <br>';
					}
				}
			}
			$list_error[$value->name_khu_vuc]= $error;
		}
		foreach ($list_error as $key => $value) {
			echo $value;
		}
	}
    public function resetTxt(){
    	$khuVucAll = khuVuc::all();
    	foreach ($khuVucAll as $key => $khuVuc) {
    		$count = DB::table($khuVuc->folder_txt)->count();
    		if ($count>25920) {
    			DB::table($khuVuc->folder_txt)
			    ->orderByDesc('time')
			    ->skip(25920) // Bỏ qua 25920 bản ghi đầu tiên (3 tháng)
			    ->pluck('time')
			    ->each(function ($time) use ($khuVuc) {
			        DB::table($khuVuc->folder_txt)
			            ->where('time', $time)
			            ->delete();
			    });
    		}
    	}
    	return Redirect()->back()->withInput();
    }
    // alert
    public function deleteAlert($id){
    	$data = alert::find($id)->delete();
        return Redirect()->back()->withInput();
    }
    public function postinsert(Request $request, $id_khu_vuc){
		$validator = Validator::make(
		    $request->all(),
		    [
		        'name_alert' => 'required|min:1|max:100', // Đặt tên của trường và cung cấp quy tắc kiểm tra
		        'minmin' => 'required',
		        'min' => 'required',
		        'max' => 'required',
		        'maxmax' => 'required',
		        'enable' => 'required'
		    ]
		);
        if ($validator->fails()) {
        	return response()->json(['error'=>$validator->errors()->all()]);
        }else{
	    	$count = alert::where('name_alert',$request->name_alert)->where('id_khu_vuc',$id_khu_vuc)->count();
	    	if ($count==0) {
		    	$insert = new alert();
		    	$insert->id_khu_vuc = $id_khu_vuc;
		    	$insert->name_alert = $request->name_alert;
		    	$insert->minmin = $request->minmin;
		    	$insert->min = $request->min;
		    	$insert->max = $request->max;
		    	$insert->maxmax = $request->maxmax;
		    	$insert->enable = $request->enable;
		    	$insert->save();
		    	return Redirect()->back()->withInput();
	    	}else{
	    		return response()->json(['success'=>$request->name.' dang ton tai']);
	    	}
        }
    }
    public function postupdate(Request $request, $id_khu_vuc){
		$validator = Validator::make(
		    $request->all(),
		    [
		        'name_alert' => 'required|min:1|max:100', // Đặt tên của trường và cung cấp quy tắc kiểm tra
		        'minmin' => 'required',
		        'min' => 'required',
		        'max' => 'required',
		        'maxmax' => 'required',
		        'enable' => 'required'
		    ]
		);
        if ($validator->fails()) {
        	return response()->json(['error'=>$validator->errors()->all()]);
        }else{
	    	for ($i=0; $i < count($request->id_alert) ; $i++) { 
				$count = alert::where('name_alert',$request->name_alert[$i])->where('id_khu_vuc',$id_khu_vuc)->where('id_alert','!=',$request->id_alert[$i])->count();
				$count=0;
		    	if ($count==0) {
			    	$update = alert::find($request->id_alert[$i]);
			    	$update->name_alert = $request->name_alert[$i];
			    	$update->minmin = $request->minmin[$i];
			    	$update->min = $request->min[$i];
			    	$update->max = $request->max[$i];
			    	$update->maxmax = $request->maxmax[$i];
			    	$update->enable = $request->enable[$i];
			    	$update->save();
		    	}else{
		    		return response()->json(['success'=>$request->name.' dang ton tai']);
		    	}
	    	}
	    	return Redirect()->back()->withInput();
	        }
    }
    // end alert
	public function showTrangChu($id_nha_may,$key_view){
		$currentDateTime = new DateTime('now', new \DateTimeZone('Asia/Ho_Chi_Minh'));
		$formattedDateTime = $currentDateTime->format('Y-m-d H:i:s');
		$date1 = DateTime::createFromFormat('Y-m-d H:i:s',$formattedDateTime);
		////////////////////////////
		$nhaMayGetId = nhaMay::find($id_nha_may);
		$khuVuc = khuVuc::where('id_nha_may', $id_nha_may)->orderBy('id_khu_vuc')->get();

		$results = [];
		$result_khuVuc=[];
		$list_total=['N'=>0,'C'=>0,'E'=>0,'bt'=>0,'alert'=>0,'error'=>0,'load'=>0,'connect'=>0,'total'=>count($khuVuc),'reload'=>0];
		$name_status = ['N','C','E','bt','alert','error','load','connect'];

		foreach ($khuVuc as $key => $value) {
			$alert =alert::where('id_khu_vuc', $value->id_khu_vuc)->get();
			$viTri = viTri::where('id_khu_vuc', $value->id_khu_vuc)->orderBy('vitri', 'asc')->get();
			$newTxt = DB::table($value->folder_txt)
                ->orderByDesc('time')->first();
            $txt = DB::table($value->folder_txt)
                ->orderByDesc('time')->limit(12)->get();
            $list_check = ['N'=>0,'C'=>0,'E'=>0,'bt'=>0,'alert'=>0,'error'=>0,'load'=>0,'connect'=>0];
            if (!empty($newTxt)) {
	            $time =  $newTxt->time;
	            $date2 = DateTime::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s",strtotime($time)));
				$interval = $date1->diff($date2);
				$connect = "";
				if ($interval->y > 0) {$connect = "Mất tín hiệu";
				} elseif($interval->m > 0) {$connect = "Mất tín hiệu";
				} elseif($interval->d > 0) {$connect = "Mất tín hiệu";
				} elseif($interval->h > 0) {$connect = "Mất tín hiệu";
				} elseif($interval->i > 10) {$connect = "Mất tín hiệu";}
				if ($connect!=='') {
					$list_check['load'] = $list_check['connect'] = 1;
				}
				/////////////////////////////////////////////////////
				if ($list_check['connect']==0) {
					$arrayData = json_decode($newTxt->data, true);
					$list_alert = [];
					if ($alert) {
						foreach ($alert as $key => $value_alert) {
							if ($value_alert['enable']=="YES") {
								$list_alert[$value_alert['name_alert']]=$value_alert;
							}
						}
					}
					foreach ($arrayData as $key1 => $value1) {
						if ($list_check['E'] == 0 ) {
							if ($list_check['C'] == 0 ) {
								switch ($value1['status']) {
									case 0: $list_check['N'] = 1;
										if (array_key_exists($value1['name'], $list_alert)) {
											$value2 = $list_alert[$value1['name']];
											if ($list_check['error']==0) {
												if ($list_check['alert']==0) {
													if ($value1['number']<$value2['minmin']||$value1['number']>$value2['maxmax']) {
														$list_check['error']=1;
													}elseif ($value1['number']<$value2['min']||$value1['number']>$value2['max']) {
														$list_check['alert']=1;
													}
												}
											}
										}							
										break;
									case 1:$list_check['C'] = 1; break;
									case 2:$list_check['E'] = 1; break;
								}
							}
						}
					}
				}
			}	
			array_push($result_khuVuc,['khuVucGetId'=>$value,'alert'=>$alert,'viTri'=>$viTri,'newTxt'=>$newTxt,'txt'=>$txt,'list_check'=>$list_check]);
			foreach ($name_status as $key => $value) {
				$list_total[$value] += $list_check[$value];
			}
		}
		if ($list_total['load']>0 && $list_total['load'] < $list_total['total']) {
			$list_total['reload']=1;
		}
		$results = array_merge($results,['nhaMayGetId'=>$nhaMayGetId,'khuVuc'=>$khuVuc,'result_khuVuc'=>$result_khuVuc,'list_total'=>$list_total]);
			////////////////////////////////
		return view('User.home', compact('results','key_view'));
	}

	public function dataKhuVuc($id_khu_vuc, $startTime, $endTime){
		$khuVucGetId = khuVuc::find($id_khu_vuc);
		$nhaMayGetId = nhaMay::find($khuVucGetId['id_nha_may']);
		$khuVuc = khuVuc::where('id_nha_may',$khuVucGetId['id_nha_may'])->get();
		$alert = alert::where('id_khu_vuc',$id_khu_vuc)->get();
		$camera = camera::where('id_khu_vuc',$id_khu_vuc)->get();
		$results = []; $result_khuVuc = [];

		if ($startTime=="NO" || $endTime=="NO") {
			$txt = DB::table($khuVucGetId['folder_txt'])
                ->orderByDesc('time')
                ->limit(12)
                ->get();
		}else{
			$txt = DB::table($khuVucGetId['folder_txt'])
                ->orderByDesc('time')
                ->whereBetween('time', [$startTime, $endTime])
                ->get();
        }
        array_push($result_khuVuc, ['khuVucGetId'=>$khuVucGetId,'alert'=>$alert,'txt'=>$txt]);
        $results = array_merge($results,['nhaMayGetId'=>$nhaMayGetId,'khuVuc'=>$khuVuc,'camera'=>$camera,'result_khuVuc'=>$result_khuVuc]);

	    return $results;
	}
	public function showKhuVuc(Request $request,$id_khu_vuc,$action){
		if ($action=='updatealert') {
			$data = alert::where('id_khu_vuc',$id_khu_vuc)->get();
			return view('User.alert', compact('data','action','id_khu_vuc'));
		}
		$show_Alert='';
		$results = $this->dataKhuVuc($id_khu_vuc,'NO','NO');
		if ($action=='Alert') {
			$result_txt = [];
			$results['result_khuVuc'][0]['txt']=new Collection($result_txt);
		}
		return view('User.khuVuc', compact('results','action','show_Alert'));
	}
	public function postShowKhuVuc(Request $request, $id_khu_vuc,$action){
		$validator = Validator::make(
		    $request->all(),
		    [
		        'startTime' => 'required',
		        'endTime' => 'required|after:startTime',
		    ],
		    [
		        'endTime.after' => 'Thời gian kết thúc phải lớn hơn thời gian bắt đầu.',
		    ]
		);
        if ($validator->fails()) {
        	return response()->json(['error'=>$validator->errors()->all()]);
        }else{
        	$startTime = $request->startTime;
        	$endTime = $request->endTime;
        	$results = $this->dataKhuVuc($id_khu_vuc,$startTime,$endTime);
        	

        	if ($action=='Alert') {
        		$alert = $results['result_khuVuc'][0]['alert'];
        		$list_alert = [];
				if ($alert) {
					foreach ($alert as $key => $value) {
						if ($value['enable']=="YES") {
							$list_alert[$value['name_alert']]=$value;
						}
					}
				}
        		$txt = $results['result_khuVuc'][0]['txt'];
        		if ($alert==null) {
        			$results['result_khuVuc'][0]['txt']=null;
        		}else{
        			$result_txt=[];
					foreach ($txt as $key => $value) {
						$arrayData = json_decode($value->data, true);
						$result_alert=false; $result_error=false;

						foreach ($arrayData as $key1 => $value1) {
							if (array_key_exists($value1['name'], $list_alert)) {
								$value2 = $list_alert[$value1['name']];

								if ($value1['number']<$value2['minmin']||$value1['number']>$value2['maxmax']) {
									$result_error=true;
								}elseif ($value1['number']<$value2['min']||$value1['number']>$value2['max']) {
									$result_alert=true;
								}

							}							
						}
						
						if ($request->Alert=='Alert' && ( $result_error==true || $result_alert==true ) ) {
							array_push($result_txt,$value);
						}elseif ($request->Alert=='Error' && $result_error==true) {
							array_push($result_txt,$txt[$key]);
						}
					}	
        		}
        		$result_txt = new Collection($result_txt);
        		$results['result_khuVuc'][0]['txt'] = $result_txt;
        	}

        	// dd($results);
        	if (isset($request->action) && $request->action=='execel') {
				$data=[];$ykeys=[];$name=['Time'];
				$txt = $results['result_khuVuc'][0]['txt'];
				$th = json_decode($txt[0]->data, true);
				foreach ($th as $key => $value) {
					$name=array_merge($name,array($value['name']));
				}
				array_push($data, $name);
				foreach ($txt as $key => $value) {
					$time = $value->time;
					$arrayData = json_decode($value->data, true);
					foreach ($arrayData as $key => $value) {
					  $ykeys = array_merge($ykeys,array('year' => $time, $value['name']=>number_format($value['number'],2)) );
					}
					array_push($data, $ykeys);
				}
				return Excel::download(new UsersExport($data), 'data.xlsx');
        	}else{
        		// dd($results);
        		$show_Alert='';
        		if ($action=='Alert') {
        			$show_Alert=$request->Alert;
        		}
        		return view('User.khuVuc', compact('results','startTime','endTime','action','show_Alert'));
        	}
        }
    }
}



