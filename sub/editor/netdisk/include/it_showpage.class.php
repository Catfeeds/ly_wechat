<?php
require_once RELATIVITY_PATH . 'include/it_basic.class.php';
require_once RELATIVITY_PATH . 'include/bn_user.class.php';
class ShowPage extends It_Basic {
	protected $O_SingleUser;
	protected $A_FolderId = array ();	
	public function __construct($o_singleUser) {
		$this->O_SingleUser = $o_singleUser;
	}	
	public function getNetDiskFileList($n_folderid) {

		$o_file = new View_Base_User_File();
		$o_file->PushWhere ( array ('&&', 'Delete', '=', 0 ) );
		$o_file->PushWhere ( array ('&&', 'FolderId', '=', $n_folderid ) );
		$o_file->PushWhere ( array ('&&', 'ClassName', '=', 'image' ) );
		$o_file->PushWhere ( array ('&&', 'Uid', '=', $this->O_SingleUser->getUid () ) );
		$o_file->PushOrder ( array ('Filename', 'A' ) );
		$n_count = $o_file->getAllCount ();
		for($i = 0; $i < $n_count; $i ++) {
			//如果是图片，双击后打开。
			$html .= '
			<div title="' . $o_file->getFilename ( $i ) . '" onclick="selected(this,\''.$this->S_Root.$o_file->getPath ( $i ) . $o_file->getOriginalFilename ( $i ) . '\')" class="off">
	            <div>
	                <div class="icon" style="width:100px;height:68px">
	                    <div><img src="'.$this->S_Root.$o_file->getPath ( $i ) . $o_file->getOriginalFilename ( $i ) . '" alt="" align="absmiddle" style="width:100px; height:68px"/></div>
	                    <div>
	                    </div>
	                </div>
	            </div>
	            <div class="file_name">
	                ' . $this->AilterFileName ( $o_file->getFilename ( $i ) ) . '
	            </div>

	        </div>';
		
		}
		return $html;
	}	
	private function AilterFileName($s_text) {
		$a_key = explode ( ".", $s_text );
		if (count ( $a_key ) > 1) {
			return $this->CutStr ( $a_key [0], 13 ) . '.' . $a_key [1];
		} else {
			return $this->CutStr ( $a_key [0], 13);
		}
	}
}

?>