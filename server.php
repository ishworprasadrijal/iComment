<?php 
	upload_files($_POST['files']);
	$response['status'] = 'success';
	$response['id'] = $_POST['id'];
	$response['comment'] = $_POST['comment'];
	$response['user'] = $_POST['user'];
	$response['message'] = "<strong>".$_POST['user']."</strong> updated his comment successfully.";
	echo json_encode($response); exit;

	function upload_files($files=null){
		if(is_array($files)){
			foreach($files as $key => $file){
				list($information, $data) = explode(";base64,",$file);
				list(,$extension) = explode("image/",$information);
				$filename = 'uploads/'.$_POST['id'].'_'.$_POST['user'].'_'.time().'_'.rand(000,999).'.'.$extension;
				file_put_contents($filename, base64_decode($data));
			}
		}
	}
?>