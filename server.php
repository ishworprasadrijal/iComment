<?php 
	upload_files($_POST['files']);
	$response['status'] = 'success';
	$response['id'] = $_POST['id'];
	$response['comment'] = $_POST['comment'];
	$response['user'] = $_POST['user'];
	$response['message'] = "<strong>".$_POST['user']."</strong> updated his comment successfully.";
    $response['files'] = show_file_previews($_POST['user']);
	echo json_encode($response); exit;

	function upload_files($files=null){
		if(is_array($files)){
			foreach($files as $key => $file){
				list($information, $data) = explode(";base64,",$file);
				$mime_type = explode(":",$information);
				$extension = extension_from_mime($mime_type[1]);
				// list(,$extension) = explode(":",$information);
				$filename = 'uploads/'.$_POST['id'].'_'.$_POST['user'].'_'.time().'_'.rand(000,999).'.'.$extension;
				file_put_contents($filename, base64_decode($data));
			}
		}
	}


    function extension_from_mime($g_mime) {
        $mime_types = array(
            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',

            // images
            'png' => 'image/png',
            'jpe' => 'image/jpe',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',

            // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',

            // audio/video
            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',

            // adobe
            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',

            // ms office
            'doc' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',

            // open office
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        );

        foreach($mime_types as $key => $mime){
        	if($mime == $g_mime) return $key;
        }
        return $g_mime;
    }

    function show_file_previews($name=''){
        $files = '';
        $results=glob("uploads/*_".$name."_*",GLOB_BRACE);
        foreach($results as $key => $result){
          $extension = pathinfo($result, PATHINFO_EXTENSION);
          if(in_array($extension,array('jpg', 'jpeg', 'png', 'bmp', 'gif', 'tiff'))){
            $files .= '<img src="'.$result.'">';              
          }elseif(in_array($extension,array('doc', 'msword', 'docx', 'txt', 'rtf', 'xls', 'xlsx', 'ppt', 'pptx', 'csv'))){
            $files .= '<img src="knownfile.png">';
          }elseif(in_array($extension,array('pdf'))){
            $files .= '<img src="pdf.png">';
          }elseif(!in_array($extension,array('jpg', 'jpeg', 'png', 'bmp', 'gif', 'tiff', 'pdf', 'doc', 'docx', 'txt', 'rtf', 'xls', 'xlsx', 'ppt', 'pptx', 'csv'))){
            $files .= '<img src="unknown.png">';
          }
        }
        return $files;
    }
?>