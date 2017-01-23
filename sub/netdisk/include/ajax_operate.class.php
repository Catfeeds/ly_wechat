<?php
//error_reporting ( 0 );
require_once RELATIVITY_PATH . 'include/db_table.class.php';
require_once RELATIVITY_PATH . 'include/db_view.class.php';
require_once RELATIVITY_PATH . 'include/bn_basic.class.php';
require_once RELATIVITY_PATH . 'include/bn_user.class.php';
class Operate extends Bn_Basic {
	public function Refresh($n_uid)
	{
		if (! ($n_uid > 0)) {
			$this->setReturn('parent.goto_login()');
		}
		$o_user = new Single_User ( $n_uid );
		if (!$o_user->ValidModule ( 100200 ))return;//如果没有权限，不返回任何值
		date_default_timezone_set('Asia/Chongqing');
		$a_files=array();
		mkdir ( RELATIVITY_PATH . 'userdata/netdisk', 0777 );
		mkdir ( RELATIVITY_PATH . 'userdata/netdisk/thumb', 0777 );
		mkdir ( RELATIVITY_PATH . 'userdata/netdisk/'.md5($n_uid.'ctisss'), 0777 );
		$s_refreshpath=str_replace('..', '', rawurldecode($this->getPost('path')));
		$s_path=$this->FilterPath(rawurldecode($this->getPost('path')),$n_uid);
		$s_refreshpath=iconv ( 'utf-8', $this->getEncode(), $s_refreshpath );
		$s_path=iconv ( 'utf-8',$this->getEncode(), $s_path );
		if (is_dir ( $s_path )) {
			if ($dh = opendir($s_path)) {
				while ( ($file = readdir ( $dh )) != false ) {
					//文件名的全路径 包含文件名
					$filePath = $s_path . $file;
					//获取文件修改时间
					$fmt = filemtime ( $filePath );
					$size =@filesize( $filePath );
					$b_isfile=1;
					if (is_dir ( $filePath ))
					{
						$b_isfile=0;
					}
					if($file=='.' || $file=='..')
					{
						continue;
					}
					$a_temp=array(
						'name'=>iconv ($this->getEncode(), 'utf-8', $file ),
						'size'=>$size,
						'path'=>iconv ($this->getEncode(), 'utf-8','userdata/netdisk/'.md5($n_uid.'ctisss').$s_refreshpath.$file),
						'date'=>date ( "Y-m-d H:i:s", $fmt ),
						'isfile'=>$b_isfile
					);
					
					array_push($a_files, $a_temp);
				}
				closedir ( $dh );
			}
			$a_general = array (
				'path' => iconv ($this->getEncode(), 'utf-8', $s_refreshpath ),
				'success' => 1,
				'text' =>Text::Key('OperationSuccess'),
				'files' => $a_files
			);
		}else{
			$a_general = array (
				'path' => iconv ($this->getEncode(), 'utf-8', $s_refreshpath ),
				'success' => 0,
				'text' =>Text::Key('OperationError01'),
				'files' => ''
			);
		}
		
		echo (json_encode ( $a_general ));
	}
	public function GetImg($n_uid)
	{
		$s_path=$this->FilterPath(rawurldecode($this->getPost('path')),$n_uid);
		$s_path=iconv ( 'utf-8', $this->getEncode(), $s_path );
		$this->image($s_path);
	}
    public function image($path){
    	require_once RELATIVITY_PATH . 'include/imageThumb.class.php';
    	require_once RELATIVITY_PATH . 'include/file.function.php';
    	require_once RELATIVITY_PATH . 'include/common.function.php';
    	require_once RELATIVITY_PATH . 'include/web.function.php';
        if (filesize($path) <= 1024*10) {//小于10k 不再生成缩略图
            file_put_out($path);
        }
        $image=$path;
        $image_md5  = md5_file($image);//文件md5
        if (strlen($image_md5)<5) {
            $image_md5 = md5($image);
        }
        define('DATA_THUMB',RELATIVITY_PATH.'userdata/netdisk/thumb'); 
        $image_thum = DATA_THUMB.'/'.$image_md5.'.png';
        if (!is_dir(DATA_THUMB)){
            mkdir(DATA_THUMB,"0777");
        }
        if (!file_exists($image_thum)){//如果拼装成的url不存在则没有生成过
                $cm=new CreatMiniature();
                $cm->SetVar($image,'file');
                //$cm->Prorate($image_thum,72,64);//生成等比例缩略图
                $cm->BackFill($image_thum,72,64,true);//等比例缩略图，空白处填填充透明色
        }
        if (!file_exists($image_thum) || filesize($image_thum)<100){//缩略图生成失败则用默认图标
            $image_thum=STATIC_PATH.'images/image.png';
        }
        //输出
        file_put_out($image_thum);
    }
	public function Download($n_uid){
		$s_path=$this->FilterPath(rawurldecode($this->getPost('path')),$n_uid);
		$s_path=iconv ( 'utf-8', $this->getEncode(), $s_path );
		require_once RELATIVITY_PATH . 'include/imageThumb.class.php';
    	require_once RELATIVITY_PATH . 'include/file.function.php';
    	require_once RELATIVITY_PATH . 'include/common.function.php';
    	require_once RELATIVITY_PATH . 'include/web.function.php';
        file_put_out($s_path,true);
    }
	public function UploadFiles($n_uid)
	{
		if (! ($n_uid > 0)) {
			$this->setReturn('parent.goto_login()');
		}
		$o_user = new Single_User ( $n_uid );
		if (!$o_user->ValidModule ( 100200 ))return;//如果没有权限，不返回任何值
		$this->VerifyDiskSpace($n_uid, $_FILES["file"]["size"]);
		$a_files=array();
		$targetDir=$this->FilterPath(rawurldecode($this->getPost('path')),$n_uid);
		$targetDir=iconv ( 'utf-8', $this->getEncode(), $targetDir );
		@set_time_limit(5 * 60);
		
		// Uncomment this one to fake upload time
		// usleep(5000);
		
		// Settings
		//$targetDir = 'uploads';
		$cleanupTargetDir = true; // Remove old files
		$maxFileAge = 5 * 3600; // Temp file age in seconds
		// Create target dir
		if (!file_exists($targetDir)) {
			@mkdir($targetDir);
		}
		// Get a file name
		if (isset($_REQUEST["name"])) {
			$fileName = $_REQUEST["name"];
		} elseif (!empty($_FILES)) {
			$fileName = $_FILES["file"]["name"];
		} else {
			$fileName = uniqid("file_");
		}
		$fileName=iconv ( 'utf-8', $this->getEncode(), rawurldecode($fileName));
		$filePath = $targetDir.$fileName;
		//判读是否有相同文件，如果有，改名为(1)
		$n_sum=1;
		while (file_exists($filePath))
		{
			$a_ext=explode('.', $fileName);
			$fileName='';
			for($i=0;$i<count($a_ext);$i++)
			{
				if($i==0)
				{
					$fileName=$a_ext[$i].'('.$n_sum.')';
				}else{
					$fileName=$fileName.'.'.$a_ext[$i];
				}
			}
			$filePath=$targetDir.$fileName;
			$n_sum++;
		}
		// Chunking might be enabled
		$chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
		$chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;
		// Remove old temp files	
		if ($cleanupTargetDir) {
			if (!is_dir($targetDir) || !$dir = opendir($targetDir)) {
				//die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
			}
		
			while (($file = readdir($dir)) !== false) {
				$tmpfilePath = $targetDir . $file;
		
				// If temp file is current file proceed to the next
				if ($tmpfilePath == "{$filePath}.part") {
					continue;
				}
		
				// Remove temp file if it is older than the max age and is not the current file
				if (preg_match('/\.part$/', $file) && (filemtime($tmpfilePath) < time() - $maxFileAge)) {
					@unlink($tmpfilePath);
				}
			}
			closedir($dir);
		}	
		// Open temp file
		if (!$out = @fopen("{$filePath}.part", $chunks ? "ab" : "wb")) {
			//die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
		}
		if (!empty($_FILES)) {
			if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
				//die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
			}
		
			// Read binary input stream and append it to temp file
			if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
				//die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
			}
		} else {	
			if (!$in = @fopen("php://input", "rb")) {
				//die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
			}
		}
		while ($buff = fread($in, 4096)) {
			fwrite($out, $buff);
		}
		@fclose($out);
		@fclose($in);
		// Check if file has been uploaded
		if (!$chunks || $chunk == $chunks - 1) {
			// Strip the temp .part suffix off 
			rename("{$filePath}.part",$filePath);
		}
		// Return Success JSON-RPC response
		$a_general = array (
				'success' => 1,
				'text' =>'',
			);
		echo (json_encode ( $a_general ));
	}
	public function getEncode()
	{
		if (strtoupper(substr(PHP_OS, 0,3)) === 'WIN') {
			$config['system_os']='windows';
			//return $this->getEncode();//user set your server system charset
		} else {
			//$config['system_os']='linux';
			return 'utf-8';
		}
	}
	public function CreateFolder($n_uid)
	{
		if (! ($n_uid > 0)) {
			$this->setReturn('parent.goto_login()');
		}
		$o_user = new Single_User ( $n_uid );
		if (!$o_user->ValidModule ( 100200 ))return;//如果没有权限，不返回任何值
		$a_files=array();
		$s_path=$this->FilterPath(rawurldecode($this->getPost('path')),$n_uid);
		
		$s_path=iconv ( 'utf-8',$this->getEncode(), $s_path );
		if (mkdir($s_path,0777))
		{
			$a_general = array (
				'success' => 1,
				'text' =>Text::Key('OperationSuccess'),
			);
		}else{
			$a_general = array (
				'success' => 0,
				'text' =>Text::Key('CreateFolderError'),
			);
		}
		echo (json_encode ( $a_general ));
	}
	public function DeleteFilesFolders($n_uid)
	{
		if (! ($n_uid > 0)) {
			$this->setReturn('parent.goto_login()');
		}
		$o_user = new Single_User ( $n_uid );
		if (!$o_user->ValidModule ( 100200 ))return;//如果没有权限，不返回任何值
		$a_files=array();
		$a_files=json_decode(str_replace('\\"', '"', $this->getPost('item')));
		for($i=0;$i<count($a_files);$i++)
		{
			$path=$this->FilterPath(rawurldecode($a_files[$i]),$n_uid);
			$path=iconv ( 'utf-8', $this->getEncode(), $path );
			if (! is_dir ( $path )) {
				unlink ( $path );
			}else{
				$this->DeleteDir($path);
			}
		}
		$a_general = array (
				'success' =>1,
				'text' =>Text::Key('OperationSuccess')
			);
		echo (json_encode ( $a_general ));
	}
	public function PasteFiles($n_uid)
	{
		if (! ($n_uid > 0)) {
			$this->setReturn('parent.goto_login()');
		}
		$o_user = new Single_User ( $n_uid );
		if (!$o_user->ValidModule ( 100200 ))return;//如果没有权限，不返回任何值
		$type=$this->getPost('type');		
		$to_path=$this->FilterPath(rawurldecode($this->getPost('path')),$n_uid);
		$to_path=iconv ( 'utf-8', $this->getEncode(), $to_path );//目标路径
		
		$a_files=array();
		$a_files=json_decode(str_replace('\\"', '"', $this->getPost('item')));
		
		//验证磁盘空间
		require_once RELATIVITY_PATH . 'include/file.function.php';
		//计算要压缩的文件总尺寸
		$n_sum_size=0;
    	for ($i=0; $i < count($a_files); $i++) {
    		$path=$this->FilterPath(rawurldecode($a_files[$i]),$n_uid);
			$path=iconv ( 'utf-8', $this->getEncode(), $path );
    		if (is_dir ($path))
    		{
    			//计算文件夹大小
    			$a_size=_path_info_more($path);
    			$n_sum_size=$n_sum_size+$a_size['size'];
    		}else{
    			//计算文件大小
    			$n_sum_size=$n_sum_size+get_filesize($path);
    		}
        }
		$this->VerifyDiskSpace($n_uid,$n_sum_size);

		for($i=0;$i<count($a_files);$i++)
		{
			$path=$this->FilterPath(rawurldecode($a_files[$i]),$n_uid);
			$path=iconv ( 'utf-8', $this->getEncode(), $path );
			if (! is_dir ( $path )) {
				copy($path, $to_path.basename($path));
				if($type=='cut')
				{
					if ($path==$to_path.basename($path))
					{
						continue;
					}
					unlink ( $path );
				}
			}else{
				$a_temp=explode('/', $path);
				//如果目标和要移动的目录一样，并且是剪切操作，那么就跳过，防止误删除
				$this->copydir($path, $to_path.$a_temp[count($a_temp)-1]);
				if($type=='cut')
				{
					if ($path.'/'==$to_path)
					{
						continue;
					}
					$this->DeleteDir($path);
				}
			}
		}
		$a_general = array (
				'success' =>1,
				'text' =>Text::Key('OperationSuccess'),
			);
		echo (json_encode ( $a_general ));
	}
	function copydir($strSrcDir, $strDstDir)
	{
		$dir = opendir($strSrcDir);
		if (!$dir) {
			return false;
		}
		if (!is_dir($strDstDir)) {
			if (!mkdir($strDstDir)) {
				return false;
			}
		}
		while (false !== ($file = readdir($dir))) {
			if (($file!='.') && ($file!='..')) {
				if (is_dir($strSrcDir.'/'.$file) ) {
					if (!$this->copydir($strSrcDir.'/'.$file, $strDstDir.'/'.$file)) {
						return false;
					}
				} else {
					if (!copy($strSrcDir.'/'.$file, $strDstDir.'/'.$file)) {
						return false;
					}
				}
			}
		}
		closedir($dir);
		return true;
	}
	public function RenameFiles($n_uid)
	{
		if (! ($n_uid > 0)) {
			$this->setReturn('parent.goto_login()');
		}
		$o_user = new Single_User ( $n_uid );
		if (!$o_user->ValidModule ( 100200 ))return;//如果没有权限，不返回任何值
		$a_files=array();
		$s_path=$this->FilterPath(rawurldecode($this->getPost('path')),$n_uid);
		$s_path=iconv ( 'utf-8', $this->getEncode(), $s_path);
		
		$s_oldpath=$this->FilterPath(rawurldecode($this->getPost('old_path')),$n_uid);
		$s_oldpath=iconv ( 'utf-8', $this->getEncode(), $s_oldpath);
		
		if (rename($s_oldpath, $s_path))
		{
			$a_general = array (
				'success' => 1,
				'text' =>Text::Key('OperationSuccess'),
			);
		}else{
			$a_general = array (
				'success' => 0,
				'text' =>Text::Key('CreateFolderError'),
			);
		}
		echo (json_encode ( $a_general ));
	}	
	public function FilterPath($s_path,$n_uid)
	{
		$s_path=str_replace('../', '', $s_path);
		$s_path=str_replace('..', '', $s_path);
		$s_path=str_replace('userdata/netdisk/'.md5($n_uid.'ctisss'), '', $s_path);
		return RELATIVITY_PATH.'userdata/netdisk/'.md5($n_uid.'ctisss').$s_path;

	}
    public function Zip($n_uid){
    	//sleep(1);
    	if (! ($n_uid > 0)) {
			$this->setReturn('parent.goto_login()');
		}
		$o_user = new Single_User ( $n_uid );
		if (!$o_user->ValidModule ( 100200 ))return;//如果没有权限，不返回任何值
		$list=array();
		$list=json_decode($this->getPost('path'));
		$zip_list=array();
		
		for($i=0;$i<count($list);$i++)
		{
			$temp=$this->FilterPath(rawurldecode($list[$i]),$n_uid);
			$temp=iconv ( 'utf-8', $this->getEncode(), $temp );
			array_push($zip_list, array('path'=>$temp));
		}
		//验证磁盘空间
		require_once RELATIVITY_PATH . 'include/file.function.php';
		//计算要压缩的文件总尺寸
		$n_sum_size=0;
    	for ($i=0; $i < count($zip_list); $i++) {
    		if (is_dir ( $zip_list[$i]['path'] ))
    		{
    			//计算文件夹大小
    			$a_size=_path_info_more($zip_list[$i]['path']);
    			$n_sum_size=$n_sum_size+$a_size['size'];
    		}else{
    			//计算文件大小
    			$n_sum_size=$n_sum_size+get_filesize($zip_list[$i]['path']);
    		}
        }
		$this->VerifyDiskSpace($n_uid,$n_sum_size);
    	require_once RELATIVITY_PATH . 'include/pclzip.class.php';
    	require_once RELATIVITY_PATH . 'include/util.php';
    	
    	require_once RELATIVITY_PATH . 'include/common.function.php';
    	require_once RELATIVITY_PATH . 'include/web.function.php';
		
        ini_set('memory_limit', '2028M');//2G;

        
        $list_num = count($zip_list);
        for ($i=0; $i < $list_num; $i++) { 
            $zip_list[$i]['path'] = rtrim($zip_list[$i]['path'],'/');
        }
        
        //指定目录
        $basic_path = $zip_path;
        if (!isset($zip_path)){
            $basic_path =get_path_father($zip_list[0]['path']);    
        }
       
        if ($list_num == 1){
            $path_this_name=get_path_this($zip_list[0]['path']);
        }else{
            $path_this_name=get_path_this(get_path_father($zip_list[0]['path']));
        }
        //去掉源文件后缀名
        $a_filename=array();
       	$a_filename=explode('.', $path_this_name);
       	//设置压缩文件名
        $zipname = $basic_path.$a_filename[0].'.zip';
        $zipname = get_filename_auto($zipname);
        
        if (!is_writeable($basic_path)) {
            $a_general = array (
				'success' => 0,
				'text' =>Text::Key('ZipError'),
			);
        }else{
            $files = array();
            for ($i=0; $i < $list_num; $i++) {
                $files[] = $zip_list[$i]['path'];
            }
            $remove_path_pre = get_path_father($zip_list[0]['path']);
            $archive = new PclZip($zipname);
            $v_list = $archive->create(implode(',',$files),PCLZIP_OPT_REMOVE_PATH,$remove_path_pre);
            if ($v_list == 0) {
                show_json("Error:".$archive->errorInfo(true),false);
            }
            $info = $this->L['zip_success'].$this->L['size'].":".size_format(filesize($zipname));
            if (!isset($zip_path)) {
                show_json($info,true,iconv_app(get_path_this($zipname)));
            }else{
                return iconv_app($zipname);
            }
            $a_general = array (
				'success' => 1,
				'text' =>'',
			);
        }        
        echo (json_encode ( $a_general ));
    }
    public function Unzip($n_uid){
    	require_once RELATIVITY_PATH . 'include/file.function.php';
        require_once RELATIVITY_PATH . 'include/pclzip.class.php';
    	require_once RELATIVITY_PATH . 'include/util.php';
    	require_once RELATIVITY_PATH . 'include/common.function.php';
    	require_once RELATIVITY_PATH . 'include/web.function.php';
        ini_set('memory_limit', '2028M');//2G;

        $path=iconv ( 'utf-8', $this->getEncode(),$this->FilterPath($this->getPost('path'),$n_uid));
        $name = get_path_this($path);
        $this->VerifyDiskSpace($n_uid, get_filesize($path));
        $name = substr($name,0,strrpos($name,'.'));
        $unzip_to=get_path_father($path).$name;
        //echo($unzip_to);
       // exit(0);
        //所在目录不可写
        if (!is_writeable(get_path_father($path))){
            show_json($this->L['no_permission_write'],false);
        }
        $zip = new PclZip($path);//
        $result = $zip->extract(PCLZIP_OPT_PATH,$unzip_to,
                                PCLZIP_OPT_SET_CHMOD,0777,
                                PCLZIP_OPT_REPLACE_NEWER);//解压到某个地方,覆盖方式      
        
        if ($result == 0) {
            show_json("Error : ".$zip->errorInfo(true),fasle);
        }else{
            show_json($this->L['unzip_success']);
        }
    }
    public function VerifyDiskSpace($n_uid,$n_size)
    {
    	//1.获取当前根目录下文件总大小
    	require_once RELATIVITY_PATH . 'include/file.function.php';
    	$a_used=_path_info_more(RELATIVITY_PATH. 'userdata/netdisk/'.md5($n_uid.'ctisss'));
    	$n_used=$a_used['size'];
    	//2.计算剩余空间
    	$o_user_info=new Base_User_Info($n_uid);
    	$n_free=$o_user_info->getDiskSpace()-$n_used;
    	//3.如果剩余空间大于$n_size，返回true
    	if ($n_size==null)
    	{
    		$n_size=0;
    	}
    	if ($n_free>=$n_size)
    	{
    		return true;
    	}else{
    		$a_general = array (
				'success' => 0,
				'text' =>Text::Key('DiskSpaceFull'),
			);
			echo (json_encode ( $a_general ));
			exit(0);
    	}   
    } 
	public function GetDiskSpace($n_uid)
	{
		if (! ($n_uid > 0)) {
			$this->setReturn('parent.goto_login()');
		}
		$o_user = new Single_User ( $n_uid );
		if (!$o_user->ValidModule ( 100200 ))return;//如果没有权限，不返回任何值
		require_once RELATIVITY_PATH . 'include/file.function.php';
		//获取总空间
		$o_user_info=new Base_User_Info($n_uid);
		$a_result=_path_info_more(RELATIVITY_PATH. 'userdata/netdisk/'.md5($n_uid.'ctisss'));
		$a_general = array (
				'success' =>1,
				'total' =>$o_user_info->getDiskSpace(),
				'used' =>$a_result['size'],
			);
		echo (json_encode ( $a_general ));
	}
    public function PropertyFiles($n_uid){
    	//sleep(1);
    	if (! ($n_uid > 0)) {
			$this->setReturn('parent.goto_login()');
		}
		$o_user = new Single_User ( $n_uid );
		if (!$o_user->ValidModule ( 100200 ))return;//如果没有权限，不返回任何值
		$list=array();
		$list=json_decode($this->getPost('path'));
		$file_list=array();
		
		for($i=0;$i<count($list);$i++)
		{
			$temp=$this->FilterPath(rawurldecode($list[$i]),$n_uid);
			$temp=iconv ( 'utf-8', $this->getEncode(), $temp );
			array_push($file_list,$temp);
		}
		$s_type='single';
		$s_filetype='folder';
		require_once RELATIVITY_PATH . 'include/file.function.php';
		if (count($file_list)>1)
		{
			//多文件属性
			$s_type='muti';
			$a_data=path_info_muti($file_list);//获取多个文件夹信息
		}else{
			//单文件属性
			if(is_dir($file_list[0]))
			{
				$a_data=folder_info($file_list[0]);//获取文件夹信息
			}else{
				$a_data=file_info($file_list[0]);//获取文件信息
				$s_filetype='file';
			}
			//去掉真实的路径，只取用户网盘目录后的路径
			$a_data['path']=str_replace(RELATIVITY_PATH.'userdata/netdisk/'.md5($n_uid.'ctisss'), '', $a_data['path']);
			$a_data['path']=str_replace(RELATIVITY_PATH.'userdata/netdisk', '', $a_data['path']);
			$a_data['name']=str_replace(md5($n_uid.'ctisss'),'/', $a_data['name']);
		}
		//构造结果
		$a_general = array (
				'success' =>1,
				'type' =>$s_type,
				'filetype' =>$s_filetype,
				'data' =>$a_data,
		);
        echo (json_encode ($a_general));
    }
}

?>