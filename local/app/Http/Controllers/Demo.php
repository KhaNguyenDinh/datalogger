<!-- ///////////// Load TXT
	//  insert & move TXT
	// public function loadTxtAll(){
 //    	$khuVucAll = $this->khuVucAll();
 //    	foreach ($khuVucAll as $key => $khuVuc) {
	// 		$type = $khuVuc->type;
	// 		$id_khuVuc = $khuVuc->id_khuVuc;
	// 		$folder_TXT = $khuVuc->folder_TXT;

	// 		if ($type=='ymd') {
	// 			$directoryPath = 'public/TXT/'.$folder_TXT;
	// 			$subDirectories = Storage::directories($directoryPath);

	// 			if (!empty($subDirectories)) { // check thu muc

	// 			    foreach ($subDirectories as $subDirectory) {
	// 			    	$folderName = basename($subDirectory);
	// 			    	if (strlen($folderName)!=4) {
	// 						$files = Storage::files($subDirectory);

	// 						foreach ($files as $file) {
	// 							$time_file = substr($file,strlen($file)-18,14);
	// 							$key =date("Y-m-d H:i",strtotime($time_file));
	// 						    $fileContents = Storage::disk('local')->get($file);

	// 			    			$existingRecord = DB::table($folder_TXT)
	// 							    ->where('time', date("Y-m-d H:i:s",strtotime($time_file)))
	// 							    ->select('time')->first();
	// 							if ($existingRecord) {	$available = "YES";
	// 							}else{ $available="NO";}
	// 						// Tách nội dung thành các dòng
	// 						    $lines = explode("\n", $fileContents);
	// 						    // Xử lý từng dòng
	// 						    $mov=''; $time_txt = '';
	// 						    $array = [];
	// 						    foreach ($lines as $line) {
	// 						    	$elements = explode("\t", $line);
	// 						    	if (isset($elements[4])) {
	// 						    		if ($mov=='') {
	// 						    			if (date("Y-m-d H:i",strtotime($elements[3])) !== $key) {
	// 								    		$mov = "NO";
	// 								    	}else{
	// 								    		$mov = "YES";
	// 								    	}
	// 						    		}
	// 						    		if ($available=="NO") {
	// 						    			$array[] = [
	// 											'id_khuVuc' => $id_khuVuc,
	// 											'name' => $elements[0],
	// 											'number' => $elements[1],
	// 											'unit' => $elements[2],
	// 											'time' => date("Y-m-d H:i:s", strtotime($elements[3])),
	// 											'status' => $elements[4],
	// 										];
	// 										if ($time_txt=='') {
	// 											$time_txt = date("Y-m-d H:i:s", strtotime($elements[3]) );
	// 										}
	// 						    		}

	// 						    	}
	// 						    }


	// 						    if ($mov =='YES') {
	// 						    	if ($available=="NO") {
	// 						    		$dataToInsert= [
	// 										'id_khuVuc' => $id_khuVuc,
	// 										'time'=>$time_txt,
	// 										'data'=>json_encode($array)
	// 									];
	// 								    DB::table($folder_TXT)->insert($dataToInsert);
	// 						    	}
	// 								// Tách đoạn đường dẫn dựa trên dấu /
	// 								$parts = explode('/', $file);
	// 								// Lấy tên file
	// 								$fileName = end($parts);
	// 								// Loại bỏ $directoryPath khỏi đường dẫn để lấy thư mục và tên file mới
	// 								$relativePath = str_replace($directoryPath . '/', '', $file);


	// 								$sourcePath = storage_path('app/public/TXT/'.$folder_TXT.'/'.$relativePath);

	// 								$destinationPath = storage_path('app/public/TXT_mov/'.$folder_TXT.'/'.$relativePath);

	// 								// Tạo thư mục B nếu nó chưa tồn tại
	// 								if (!File::isDirectory(dirname($destinationPath))) {
	// 								    File::makeDirectory(dirname($destinationPath), 0755, true, true);
	// 								}

	// 								// Di chuyển file từ thư mục A sang thư mục B
	// 								File::move($sourcePath, $destinationPath);

	// 								// Xóa file ở thư mục A nếu di chuyển thành công
	// 								if (File::exists($destinationPath)) {
	// 								    File::delete($sourcePath);
	// 								}
	// 							}
	// 						}

	// 						$files = Storage::files($subDirectory);
	// 						if (empty($files)) {
	// 						    Storage::deleteDirectory($subDirectory);
	// 						    // echo "Đã xóa thư mục $subDirectory vì không còn file trong đó.";
	// 						}
	// 					}

	// 			    }
	// 			}
	// 		}
	// 		if ($type='y/m/d') {
	// 			$directoryPath = 'public/TXT/'.$folder_TXT;
	// 			$subDirectories = Storage::directories($directoryPath);

	// 			if (!empty($subDirectories)) { // check thu muc nam
	// 				foreach ($subDirectories as $subDirectory) {
	// 					$folderName = basename($subDirectory);
	// 			    	if (strlen($folderName)==4) {
	// 						$subDirectories1 = Storage::directories($subDirectory);

	// 						if (!empty($subDirectories1)) {
	// 							foreach ($subDirectories1 as  $subDirectory1) {
	// 								$subDirectories2 = Storage::directories($subDirectory1);
	// 								foreach ($subDirectories2 as $subDirectory2) {

	// 									$files = Storage::files($subDirectory2);
	// 									foreach ($files as $file) {
	// 										$time_file = substr($file,strlen($file)-18,14);
	// 										$key =date("Y-m-d H:i",strtotime($time_file));

	// 										$existingRecord = DB::table($folder_TXT)
	// 										    ->where('time', date("Y-m-d H:i:s",strtotime($time_file)))
	// 										    ->select('time')->first();
	// 										if ($existingRecord) {	$available = "YES";
	// 										}else{ $available="NO";}

	// 									    $fileContents = Storage::disk('local')->get($file);


	// 									 	    // Tách nội dung thành các dòng
	// 									    $lines = explode("\n", $fileContents);
	// 									    // Xử lý từng dòng
	// 									    $mov=''; ;$time_txt = '';
	// 									    $array = [];
	// 									    foreach ($lines as $line) {
	// 									    	$elements = explode("\t", $line);
	// 									    	if (isset($elements[4])) {
	// 									    		if ($mov=='') {
	// 									    			if (date("Y-m-d H:i",strtotime($elements[3])) !== $key) {
	// 											    		$mov = "NO";
	// 											    	}else{
	// 											    		$mov = "YES";
	// 											    	}
	// 									    		}
	// 									    		if ($available=="NO") {
	// 									    			$array[] = [
	// 														'id_khuVuc' => $id_khuVuc,
	// 														'name' => $elements[0],
	// 														'number' => $elements[1],
	// 														'unit' => $elements[2],
	// 														'time' => date("Y-m-d H:i:s", strtotime($elements[3])),
	// 														'status' => $elements[4],
	// 													];
	// 													if ($time_txt=='') {
	// 														$time_txt = date("Y-m-d H:i:s", strtotime($elements[3]) );
	// 													}
	// 									    		}

	// 									    	}
	// 									    }
	// 									    if ($mov =='YES') {
	// 									    	if ($available=="NO") {
	// 									    		$dataToInsert= [
	// 													'id_khuVuc' => $id_khuVuc,
	// 													'time'=>$time_txt,
	// 													'data'=>json_encode($array)
	// 												];
	// 											    DB::table($folder_TXT)->insert($dataToInsert);
	// 									    	}
	// 											// Tách đoạn đường dẫn dựa trên dấu /
	// 											$parts = explode('/', $file);
	// 											// Lấy tên file
	// 											$fileName = end($parts);
	// 											// Loại bỏ $directoryPath khỏi đường dẫn để lấy thư mục và tên file mới
	// 											$relativePath = str_replace($directoryPath . '/', '', $file);


	// 											$sourcePath = storage_path('app/public/TXT/'.$folder_TXT.'/'.$relativePath);

	// 											$destinationPath = storage_path('app/public/TXT_mov/'.$folder_TXT.'/'.$relativePath);

	// 											// Tạo thư mục B nếu nó chưa tồn tại
	// 											if (!File::isDirectory(dirname($destinationPath))) {
	// 											    File::makeDirectory(dirname($destinationPath), 0755, true, true);
	// 											}

	// 											// Di chuyển file từ thư mục A sang thư mục B
	// 											File::move($sourcePath, $destinationPath);

	// 											// Xóa file ở thư mục A nếu di chuyển thành công
	// 											if (File::exists($destinationPath)) {
	// 											    File::delete($sourcePath);
	// 											}
	// 										}

	// 									}
	// 									$files = Storage::files($subDirectory2);
	// 									if (empty($files)) {
	// 									    Storage::deleteDirectory($subDirectory2);
	// 									    // echo "Đã xóa thư mục $subDirectory2 vì không còn file trong đó.";
	// 									}
	// 								}
	// 								$directories = Storage::directories($subDirectory1);
	// 								if (empty($directories)) {
	// 								    Storage::deleteDirectory($subDirectory1);
	// 								    // echo "Đã xóa thư mục $subDirectory1 vì không còn thư mục con trong đó.";
	// 								}
	// 							}
	// 						}
	// 						$directories = Storage::directories($subDirectory);
	// 						if (empty($directories)) {
	// 						    Storage::deleteDirectory($subDirectory);
	// 						    // echo "Đã xóa thư mục $subDirectory1 vì không còn thư mục con trong đó.";
	// 						}
	// 			    	}
	// 				}
	// 			}
	// 		}
 //    	}
 //    	return Redirect()->back()->withInput();
 //    } // ok

 //    public function loadTxtNhaMay($id_nhaMay){
 //    	$khuVucAll = $this->khuVuc($id_nhaMay);
 //    	foreach ($khuVucAll as $key => $khuVuc) {
	// 		$folder_TXT = $khuVuc->folder_TXT;
	// 		$type = $khuVuc->type;
	// 		$id_khuVuc = $khuVuc->id_khuVuc;

	// 		if ($type=='ymd') {
	// 			$directoryPath = 'public/TXT/'.$folder_TXT;
	// 			$subDirectories = Storage::directories($directoryPath);

	// 			if (!empty($subDirectories)) { // check thu muc

	// 			    foreach ($subDirectories as $subDirectory) {
	// 			    	$folderName = basename($subDirectory);
	// 			    	if (strlen($folderName)!=4) {
	// 						$files = Storage::files($subDirectory);

	// 						foreach ($files as $file) {
	// 							$time_file = substr($file,strlen($file)-18,14);
	// 							$key =date("Y-m-d H:i",strtotime($time_file));
	// 						    $fileContents = Storage::disk('local')->get($file);

	// 			    			$existingRecord = DB::table($folder_TXT)
	// 							    ->where('time', date("Y-m-d H:i:s",strtotime($time_file)))
	// 							    ->select('time')->first();
	// 							if ($existingRecord) {	$available = "YES";
	// 							}else{ $available="NO";}
	// 						// Tách nội dung thành các dòng
	// 						    $lines = explode("\n", $fileContents);
	// 						    // Xử lý từng dòng
	// 						    $mov=''; $time_txt = '';
	// 						    $array = [];
	// 						    foreach ($lines as $line) {
	// 						    	$elements = explode("\t", $line);
	// 						    	if (isset($elements[4])) {
	// 						    		if ($mov=='') {
	// 						    			if (date("Y-m-d H:i",strtotime($elements[3])) !== $key) {
	// 								    		$mov = "NO";
	// 								    	}else{
	// 								    		$mov = "YES";
	// 								    	}
	// 						    		}
	// 						    		if ($available=="NO") {
	// 						    			$array[] = [
	// 											'id_khuVuc' => $id_khuVuc,
	// 											'name' => $elements[0],
	// 											'number' => $elements[1],
	// 											'unit' => $elements[2],
	// 											'time' => date("Y-m-d H:i:s", strtotime($elements[3])),
	// 											'status' => $elements[4],
	// 										];
	// 										if ($time_txt=='') {
	// 											$time_txt = date("Y-m-d H:i:s", strtotime($elements[3]) );
	// 										}
	// 						    		}

	// 						    	}
	// 						    }


	// 						    if ($mov =='YES') {
	// 						    	if ($available=="NO") {
	// 						    		$dataToInsert= [
	// 										'id_khuVuc' => $id_khuVuc,
	// 										'time'=>$time_txt,
	// 										'data'=>json_encode($array)
	// 									];
	// 								    DB::table($folder_TXT)->insert($dataToInsert);
	// 						    	}
	// 								// Tách đoạn đường dẫn dựa trên dấu /
	// 								$parts = explode('/', $file);
	// 								// Lấy tên file
	// 								$fileName = end($parts);
	// 								// Loại bỏ $directoryPath khỏi đường dẫn để lấy thư mục và tên file mới
	// 								$relativePath = str_replace($directoryPath . '/', '', $file);


	// 								$sourcePath = storage_path('app/public/TXT/'.$folder_TXT.'/'.$relativePath);

	// 								$destinationPath = storage_path('app/public/TXT_mov/'.$folder_TXT.'/'.$relativePath);

	// 								// Tạo thư mục B nếu nó chưa tồn tại
	// 								if (!File::isDirectory(dirname($destinationPath))) {
	// 								    File::makeDirectory(dirname($destinationPath), 0755, true, true);
	// 								}

	// 								// Di chuyển file từ thư mục A sang thư mục B
	// 								File::move($sourcePath, $destinationPath);

	// 								// Xóa file ở thư mục A nếu di chuyển thành công
	// 								if (File::exists($destinationPath)) {
	// 								    File::delete($sourcePath);
	// 								}
	// 							}
	// 						}

	// 						$files = Storage::files($subDirectory);
	// 						if (empty($files)) {
	// 						    Storage::deleteDirectory($subDirectory);
	// 						    // echo "Đã xóa thư mục $subDirectory vì không còn file trong đó.";
	// 						}
	// 					}

	// 			    }
	// 			}
	// 		}
	// 		if ($type='y/m/d') {
	// 			$directoryPath = 'public/TXT/'.$folder_TXT;
	// 			$subDirectories = Storage::directories($directoryPath);

	// 			if (!empty($subDirectories)) { // check thu muc nam
	// 				foreach ($subDirectories as $subDirectory) {
	// 					$folderName = basename($subDirectory);
	// 			    	if (strlen($folderName)==4) {
	// 						$subDirectories1 = Storage::directories($subDirectory);

	// 						if (!empty($subDirectories1)) {
	// 							foreach ($subDirectories1 as  $subDirectory1) {
	// 								$subDirectories2 = Storage::directories($subDirectory1);
	// 								foreach ($subDirectories2 as $subDirectory2) {

	// 									$files = Storage::files($subDirectory2);
	// 									foreach ($files as $file) {
	// 										$time_file = substr($file,strlen($file)-18,14);
	// 										$key =date("Y-m-d H:i",strtotime($time_file));

	// 										$existingRecord = DB::table($folder_TXT)
	// 										    ->where('time', date("Y-m-d H:i:s",strtotime($time_file)))
	// 										    ->select('time')->first();
	// 										if ($existingRecord) {	$available = "YES";
	// 										}else{ $available="NO";}

	// 									    $fileContents = Storage::disk('local')->get($file);


	// 									 	    // Tách nội dung thành các dòng
	// 									    $lines = explode("\n", $fileContents);
	// 									    // Xử lý từng dòng
	// 									    $mov=''; ;$time_txt = '';
	// 									    $array = [];
	// 									    foreach ($lines as $line) {
	// 									    	$elements = explode("\t", $line);
	// 									    	if (isset($elements[4])) {
	// 									    		if ($mov=='') {
	// 									    			if (date("Y-m-d H:i",strtotime($elements[3])) !== $key) {
	// 											    		$mov = "NO";
	// 											    	}else{
	// 											    		$mov = "YES";
	// 											    	}
	// 									    		}
	// 									    		if ($available=="NO") {
	// 									    			$array[] = [
	// 														'id_khuVuc' => $id_khuVuc,
	// 														'name' => $elements[0],
	// 														'number' => $elements[1],
	// 														'unit' => $elements[2],
	// 														'time' => date("Y-m-d H:i:s", strtotime($elements[3])),
	// 														'status' => $elements[4],
	// 													];
	// 													if ($time_txt=='') {
	// 														$time_txt = date("Y-m-d H:i:s", strtotime($elements[3]) );
	// 													}
	// 									    		}

	// 									    	}
	// 									    }
	// 									    if ($mov =='YES') {
	// 									    	if ($available=="NO") {
	// 									    		$dataToInsert= [
	// 													'id_khuVuc' => $id_khuVuc,
	// 													'time'=>$time_txt,
	// 													'data'=>json_encode($array)
	// 												];
	// 											    DB::table($folder_TXT)->insert($dataToInsert);
	// 									    	}
	// 											// Tách đoạn đường dẫn dựa trên dấu /
	// 											$parts = explode('/', $file);
	// 											// Lấy tên file
	// 											$fileName = end($parts);
	// 											// Loại bỏ $directoryPath khỏi đường dẫn để lấy thư mục và tên file mới
	// 											$relativePath = str_replace($directoryPath . '/', '', $file);


	// 											$sourcePath = storage_path('app/public/TXT/'.$folder_TXT.'/'.$relativePath);

	// 											$destinationPath = storage_path('app/public/TXT_mov/'.$folder_TXT.'/'.$relativePath);

	// 											// Tạo thư mục B nếu nó chưa tồn tại
	// 											if (!File::isDirectory(dirname($destinationPath))) {
	// 											    File::makeDirectory(dirname($destinationPath), 0755, true, true);
	// 											}

	// 											// Di chuyển file từ thư mục A sang thư mục B
	// 											File::move($sourcePath, $destinationPath);

	// 											// Xóa file ở thư mục A nếu di chuyển thành công
	// 											if (File::exists($destinationPath)) {
	// 											    File::delete($sourcePath);
	// 											}
	// 										}

	// 									}
	// 									$files = Storage::files($subDirectory2);
	// 									if (empty($files)) {
	// 									    Storage::deleteDirectory($subDirectory2);
	// 									    // echo "Đã xóa thư mục $subDirectory2 vì không còn file trong đó.";
	// 									}
	// 								}
	// 								$directories = Storage::directories($subDirectory1);
	// 								if (empty($directories)) {
	// 								    Storage::deleteDirectory($subDirectory1);
	// 								    // echo "Đã xóa thư mục $subDirectory1 vì không còn thư mục con trong đó.";
	// 								}
	// 							}
	// 						}
	// 						$directories = Storage::directories($subDirectory);
	// 						if (empty($directories)) {
	// 						    Storage::deleteDirectory($subDirectory);
	// 						    // echo "Đã xóa thư mục $subDirectory1 vì không còn thư mục con trong đó.";
	// 						}
	// 			    	}
	// 				}
	// 			}
	// 		}
 //    	}
 //    	return Redirect()->back()->withInput();
 //    } // ok -->