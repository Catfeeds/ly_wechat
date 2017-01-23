<?php
/**
 * Created by JetBrains PhpStorm.
 * User: taoqili
 * Date: 12-7-18
 * Time: 上午11: 32
 * UEditor编辑器通用上传类
 */
class Uploader {
	private $fileField; //文件域名
	private $file; //文件上传对象
	private $config; //配置信息
	private $oriName; //原始文件名
	private $fileName; //新文件名
	private $fullName; //完整文件名,即从当前配置目录开始的URL
	private $fileSize; //文件大小
	private $fileType; //文件类型
	private $stateInfo; //上传状态信息,
	public $AddSum=0;
	private $stateMap = array (//上传状态映射表，国际化用户需考虑此处数据的国际化
"SUCCESS", //上传成功标记，在UEditor中内不可改变，否则flash判断会出错
"文件大小超出 upload_max_filesize 限制", "文件大小超出 MAX_FILE_SIZE 限制", "文件未被完整上传", "没有文件被上传", "上传文件为空", "POST" => "文件大小超出 post_max_size 限制", "SIZE" => "文件大小超出网站限制", "TYPE" => "不允许的文件类型", "DIR" => "目录创建失败", "IO" => "输入输出错误", "UNKNOWN" => "未知错误", "MOVE" => "文件保存时出错" );
	
	/**
	 * 构造函数
	 * @param string $fileField 表单名称
	 * @param array $config  配置项
	 * @param bool $base64  是否解析base64编码，可省略。若开启，则$fileField代表的是base64编码的字符串表单名
	 */
	public function __construct($fileField, $config, $base64 = false) {
		$this->fileField = $fileField;
		$this->config = $config;
		$this->stateInfo = $this->stateMap [0];
		$this->upFile ( $base64 );
	}
	
	/**
	 * 上传文件的主处理方法
	 * @param $base64
	 * @return mixed
	 */
	private function upFile($base64) {
		//处理base64上传
		if ("base64" == $base64) {
			$content = $_POST [$this->fileField];
			$this->base64ToImage ( $content );
			return;
		}
		
		//处理普通上传
		$file = $this->file = $_FILES [$this->fileField];
		if (! $file) {
			$this->stateInfo = $this->getStateInfo ( 'POST' );
			return;
		}
		if ($this->file ['error']) {
			$this->stateInfo = $this->getStateInfo ( $file ['error'] );
			return;
		}
		if (! is_uploaded_file ( $file ['tmp_name'] )) {
			$this->stateInfo = $this->getStateInfo ( "UNKNOWN" );
			return;
		}
		
		$this->oriName = $file ['name'];
		$this->fileSize = $file ['size'];
		$this->fileType = $this->getFileExt ();
		
		if (! $this->checkSize ()) {
			$this->stateInfo = $this->getStateInfo ( "SIZE" );
			return;
		}
		if (! $this->checkType ()) {
			$this->stateInfo = $this->getStateInfo ( "TYPE" );
			return;
		}
		//$this->fullName = $this->getFolder() . '/' . $this->getName();
		if ($this->stateInfo == $this->stateMap [0]) {
			define ( 'RELATIVITY_PATH', '../../../' );
			require_once RELATIVITY_PATH . 'include/it_include.inc.php';
			$o_user = new Single_User ( $O_Session->getUid ());
			//获取用户编号
			//保存上传文件并建立数据库
			
			require_once RELATIVITY_PATH . 'include/db_table.class.php';
			$o_date = new DateTime ( 'Asia/Chongqing' );
			mkdir ( RELATIVITY_PATH . 'userdata/files', 0777 );
			mkdir ( RELATIVITY_PATH . 'userdata/files/' . md5 ( $o_user->getUserName () ), 0777 );
			$o_filename = md5 ( $this->oriName . rand ( 0, 9999 ) . $o_date->format ( 'U' ) ); //生成随机文件夹

			$this->fullName = RELATIVITY_PATH . 'userdata/files/' . md5 ( $o_user->getUserName () ) .'/' . $o_filename . $this->fileType;
			if (! move_uploaded_file ( $file ["tmp_name"], $this->fullName )) {
				$this->stateInfo = $this->getStateInfo ( "MOVE" );
			} else {
				//在数据库中保存文件信息
				$o_file = new Base_User_Files ();
				$o_file->setFilename ($this->oriName);
				$o_file->setOriginalFilename ( $o_filename . $this->fileType );
				$o_file->setFilesize ( $filesize );
				$o_file->setUid ( $O_Session->getUid ()  );
				$o_file->setPath ( 'userdata/files/' . md5 ( $o_user->getUserName () ) . '/' );
				$o_file->setDate ( $o_date->format ( 'Y' ) . '-' . $o_date->format ( 'm' ) . '-' . $o_date->format ( 'd' ) . ' ' . $o_date->format ( 'H' ) . ':' . $o_date->format ( 'i' ) . ':' . $o_date->format ( 's' ) );
				$o_file->setFolderId ( 0 ); 
				//设置文件后缀信息
				$o_suffix = new Base_User_File_Type ( strtolower ( trim ( substr ( strrchr ( $file ['name'], '.' ), 1 ) ) ) );
				if ($o_suffix->getClassName () == false) {
					$o_file->setSuffix ( 'other' );
				} else {
					$o_file->setSuffix ( strtolower ( trim ( substr ( strrchr ( $file ['name'], '.' ), 1 ) ) ) );
				}
				$o_file->Save ();
			}
		}
		
	}
	
	/**
	 * 处理base64编码的图片上传
	 * @param $base64Data
	 * @return mixed
	 */
	/*private function getOriginalFileName($s_name,$n_folder_id)
	{
		$o_file = new Netdisk_File ();
		$o_file->PushWhere ( array ('&&', 'Delete', '=', 0 ) );
		$o_file->PushWhere ( array ('&&', 'Filename', '=', $s_name ) );
		$o_file->PushWhere ( array ('&&', 'FolderId', '=', $n_folder_id ) );
		if ($o_file->getAllCount()>0)
		{
			$this->AddSum=$this->AddSum+1;
			$a_filename = explode ( ".", $this->oriName);
			$s_name=$a_filename[0].'('.$this->AddSum.')'.'.'.$a_filename[1];
			return $this->getOriginalFileName($s_name,$n_folder_id);
		}else{
			return $s_name;
		}
	}*/
	private function base64ToImage($base64Data) {
		$img = base64_decode ( $base64Data );
		$this->fileName = time () . rand ( 1, 10000 ) . ".png";
		$this->fullName = $this->getFolder () . '/' . $this->fileName;
		if (! file_put_contents ( $this->fullName, $img )) {
			$this->stateInfo = $this->getStateInfo ( "IO" );
			return;
		}
		$this->oriName = "";
		$this->fileSize = strlen ( $img );
		$this->fileType = ".png";
	}
	
	/**
	 * 获取当前上传成功文件的各项信息
	 * @return array
	 */
	public function getFileInfo() {
		return array ("originalName" => $this->oriName, "name" => $this->fileName, "url" => $this->fullName, "size" => $this->fileSize, "type" => $this->fileType, "state" => $this->stateInfo );
	}
	
	/**
	 * 上传错误检查
	 * @param $errCode
	 * @return string
	 */
	private function getStateInfo($errCode) {
		return ! $this->stateMap [$errCode] ? $this->stateMap ["UNKNOWN"] : $this->stateMap [$errCode];
	}
	
	/**
	 * 重命名文件
	 * @return string
	 */
	private function getName() {
		return $this->fileName = time () . rand ( 1, 10000 ) . $this->getFileExt ();
	}
	
	/**
	 * 文件类型检测
	 * @return bool
	 */
	private function checkType() {
		return in_array ( $this->getFileExt (), $this->config ["allowFiles"] );
	}
	
	/**
	 * 文件大小检测
	 * @return bool
	 */
	private function checkSize() {
		return $this->fileSize <= ($this->config ["maxSize"] * 1024);
	}
	
	/**
	 * 获取文件扩展名
	 * @return string
	 */
	private function getFileExt() {
		return strtolower ( strrchr ( $this->file ["name"], '.' ) );
	}
	
	/**
	 * 按照日期自动创建存储文件夹
	 * @return string
	 */
	private function getFolder() {
		$pathStr = $this->config ["savePath"];
		if (strrchr ( $pathStr, "/" ) != "/") {
			$pathStr .= "/";
		}
		$pathStr .= date ( "Ymd" );
		if (! file_exists ( $pathStr )) {
			if (! mkdir ( $pathStr, 0777, true )) {
				return false;
			}
		}
		return $pathStr;
	}
}