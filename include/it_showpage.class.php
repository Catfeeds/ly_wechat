<?php
require_once RELATIVITY_PATH . 'include/db_view.class.php';
require_once RELATIVITY_PATH . 'include/db_table.class.php';
require_once RELATIVITY_PATH . 'include/bn_user.class.php';
require_once RELATIVITY_PATH . 'include/it_basic.class.php';
class ShowPage extends It_Basic {
	private $O_SingleUser;
	public function __construct($o_singleUser = NULL) {
		$this->O_SingleUser = $o_singleUser;
	}
	public function getGroupForSelectRole($n_uid) {
		$s_html = '';
		$s_dept = '';
		$s_dept2 = '';
		$s_role = '';
		$s_deptbutton = '';
		$s_deptbutton1 = '';
		$s_deptbutton2 = '';
		$s_deptbutton3 = '';
		$s_rolebutton = '';
		//构造按角色分
		$o_role = new Base_Role (); //构造按部门分
		$n_count = $o_role->getAllCount ();
		$s_rolebutton .= '<div id="module_roleid_' . $o_role->getRoleId ( 0 ) . '">
								<input type="button" onclick="allAddName_Role(\'module_roleid_' . $o_role->getRoleId ( 0 ) . '\')" class="all" value="全部添加" hidefocus="hidefocus" />
                                    <input type="button" onclick="allDeleteName_Role(\'module_roleid_' . $o_role->getRoleId ( 0 ) . '\')" class="delall" value="全部删除" hidefocus="hidefocus" /><br />';
		for($i = 0; $i < $n_count; $i ++) {
			$s_rolebutton .= '<input id="roleid_' . $o_role->getRoleId ( $i ) . '" type="button" class="name" value="' . $o_role->getName ( $i ) . '" hidefocus="hidefocus" onclick="addName_Role(' . $o_role->getRoleId ( $i ) . ')"/>';
		
		}
		$s_rolebutton .= '</div>';
		$s_html = '
            <tr id="group_role" style="display:none">
                <td colspan="2" style="background-color: White; border-bottom: solid 1px #CCCCCC;
                    text-align: center">
                    <table border="0" cellpadding="0" cellspacing="0" class="group">
                        <tr>
                            <td class="right" id="allname_dept_">
                                ' . $s_rolebutton . '                        
                            </td>
                        </tr>
                    </table>
                    <input value="确定" onclick="openGroup_Role()" class="BigButtonA" type="button" style="margin-bottom: 5px" />
                </td>
            </tr>
		';
		return $s_html;
	}
	public function getGroupForSelectDept($n_uid) {
		$s_html = '';
		$s_dept = '';
		$s_dept2 = '';
		$s_role = '';
		$s_deptbutton = '';
		$s_deptbutton1 = '';
		$s_deptbutton2 = '';
		$s_deptbutton3 = '';
		$s_rolebutton = '';
		$o_dept1 = new Base_Dept (); //构造按部门分
		$o_dept1->PushWhere ( array ('&&', 'ParentId', '=', 0 ) );
		$o_dept1->PushOrder ( array ('Number', 'A' ) );
		//$o_dept1->setItem(array ('DeptId', 'Name' ) );
		$n_count1 = $o_dept1->getAllCount ();
		$s_deptbutton4 .= '<div id="module_dept_00" style="display: block">
								<input type="button" onclick="allAddName_Dept(\'module_dept_00\')" class="all" value="全部添加" hidefocus="hidefocus" />
								<input type="button" onclick="allDeleteName_Dept(\'module_dept_00\')" class="delall" value="全部删除" hidefocus="hidefocus" /><br />';
		
		for($i = 0; $i < $n_count1; $i ++) { //找二级	
			//直接显示部门
			$s_deptbutton4 .= '<input id="deptid_' . $o_dept1->getDeptId ( $i ) . '" type="button" class="name" value="' . $o_dept1->getName ( $i ) . '" hidefocus="hidefocus" onclick="addName_Dept(' . $o_dept1->getDeptId ( $i ) . ')"/>';
			$o_dept2 = new Base_Dept ();
			$o_dept2->PushWhere ( array ('&&', 'ParentId', '=', $o_dept1->getDeptId ( $i ) ) );
			$o_dept2->PushOrder ( array ('Number', 'A' ) );
			$o_dept2->setItem ( array ('DeptId', 'Name' ) );
			$n_count2 = $o_dept2->getAllCount ();
			$s_deptbutton1 .= '<div id="module_dept_' . $o_dept1->getDeptId ( $i ) . '" style="display: none">
								<input type="button" onclick="allAddName_Dept(\'module_dept_' . $o_dept1->getDeptId ( $i ) . '\')" class="all" value="全部添加" hidefocus="hidefocus" />
								<input type="button" onclick="allDeleteName_Dept(\'module_dept_' . $o_dept1->getDeptId ( $i ) . '\')" class="delall" value="全部删除" hidefocus="hidefocus" /><br />';
			if ($n_count2 == 0) {
				//如果没有子部门，直接封口
				$s_deptbutton1 .= '</div>';
				$s_deptbutton .= $s_deptbutton1;
				$s_deptbutton1 = '';
			}
			$s_dept .= '<div class="dept1"><a href="javascript:openDeptSub_Dept(\'dept_dept' . ($i + 1) . '\',' . $o_dept1->getDeptId ( $i ) . ');" hidefocus="hidefocus"><img id="dept_icon_dept_' . $o_dept1->getDeptId ( $i ) . '" src="' . RELATIVITY_PATH . 'images/org/tree_off.png" alt=""/> ' . $o_dept1->getName ( $i ) . '</a></div>';
			for($j = 0; $j < $n_count2; $j ++) {
				$s_deptbutton1 .= '<input id="deptid_' . $o_dept2->getDeptId ( $j ) . '" type="button" class="name" value="' . $o_dept2->getName ( $j ) . '" hidefocus="hidefocus" onclick="addName_Dept(' . $o_dept2->getDeptId ( $j ) . ')"/>';
				$s_deptbutton2 .= '<div id="module_dept_' . $o_dept2->getDeptId ( $j ) . '" style="display: none">
										<input type="button" onclick="allAddName_Dept(\'module_dept_' . $o_dept2->getDeptId ( $j ) . '\')" class="all" value="全部添加" hidefocus="hidefocus" />
										<input type="button" onclick="allDeleteName_Dept(\'module_dept_' . $o_dept2->getDeptId ( $j ) . '\')" class="delall" value="全部删除" hidefocus="hidefocus" /><br />';
				$s_dept2 = '';
				$o_dept3 = new Base_Dept ();
				$o_dept3->PushWhere ( array ('&&', 'ParentId', '=', $o_dept2->getDeptId ( $j ) ) );
				$o_dept3->PushOrder ( array ('Number', 'A' ) );
				$o_dept3->setItem ( array ('DeptId', 'Name' ) );
				$n_count3 = $o_dept3->getAllCount ();
				if ($n_count3 == 0) { //如果没有子部门，直接封口
					$s_deptbutton2 .= '</div>';
					$s_deptbutton .= $s_deptbutton2;
					$s_deptbutton2 = '';
				}
				$s_dept2 = '<div id="dept_dept' . ($i + 1) . '_' . ($j + 1) . '" style="display:none"><div class="dept2"><a href="javascript:openDeptSub_Dept(\'dept_dept' . ($i + 1) . '_' . ($j + 1) . '\',' . $o_dept2->getDeptId ( $j ) . ');" hidefocus="hidefocus"><img id="dept_icon_dept_' . $o_dept2->getDeptId ( $j ) . '" src="' . RELATIVITY_PATH . 'images/org/tree_off.png" alt=""/> ' . $o_dept2->getName ( $j ) . '</a></div>';
				for($k = 0; $k < $n_count3; $k ++) {
					$s_deptbutton2 .= '<input id="deptid_' . $o_dept3->getDeptId ( $k ) . '" type="button" class="name" value="' . $o_dept3->getName ( $k ) . '" hidefocus="hidefocus" onclick="addName_Dept(' . $o_dept3->getDeptId ( $k ) . ')"/>';
					$s_deptbutton3 .= '<div id="module_dept_' . $o_dept3->getDeptId ( $k ) . '" style="display: none">
										<input type="button" onclick="allAddName_Dept(\'module_dept_' . $o_dept3->getDeptId ( $k ) . '\')" class="all" value="全部添加" hidefocus="hidefocus" />
										<input type="button" onclick="allDeleteName_Dept(\'module_dept_' . $o_dept3->getDeptId ( $k ) . '\')" class="delall" value="全部删除" hidefocus="hidefocus" /><br />';
					$s_deptbutton3 .= '</div>';
					$s_deptbutton .= $s_deptbutton3;
					$s_deptbutton3 = '';
					if (($k + 1) == $n_count3) { //没有下一子部门，直接封口
						$s_deptbutton2 .= '</div>';
						$s_deptbutton .= $s_deptbutton2;
						$s_deptbutton2 = '';
					}
				}
				if ($s_dept2 != '') {
					$s_dept .= $s_dept2 . '</div>';
				}
				
				if (($j + 1) == $n_count2) {
					$s_deptbutton1 .= '</div>';
					$s_deptbutton .= $s_deptbutton1;
					$s_deptbutton1 = '';
				}
			}
		}
		$s_deptbutton4 .= '</div>';
		$s_deptbutton .= $s_deptbutton4;
		$s_html = '
            <tr id="group_dept" style="display:none">
                <td colspan="2" style="background-color: White; border-bottom: solid 1px #CCCCCC;
                    text-align: center">
                    <table border="0" cellpadding="0" cellspacing="0" class="group">
                        <tr>
                            <td class="left" nowrap="nowrap">
                                <div class="head">
                                    <a href="javascript:openRootDept_Dept(\'00\');" class="header header-active" hidefocus="hidefocus">按部门选择</a></div>
                                <table class="dept" border="0" cellpadding="0" cellspacing="0" style="display:block;border-bottom: solid 1px #5B99CA;">
                                    <tr>
                                        <td style="padding:0px">
                                            ' . $s_dept . '
                                        </td>
                                    </tr>                                    
                                </table>
                            </td>
                            <td class="right" id="allname_dept_">
                                ' . $s_deptbutton . '                        
                            </td>
                        </tr>
                    </table>
                    <input value="确定" onclick="openGroup_Dept()" class="BigButtonA" type="button" style="margin-bottom: 5px" />
                </td>
            </tr>
		';
		return $s_html;
	}
	public function getGroupForAdd($n_uid) {
		$s_html = '';
		$s_dept = '';
		$s_dept2 = '';
		$s_role = '';
		$s_deptbutton = '';
		$s_deptbutton1 = '';
		$s_deptbutton2 = '';
		$s_deptbutton3 = '';
		$s_rolebutton = '';
		$o_dept1 = new Base_Dept (); //构造按部门分
		$o_dept1->PushWhere ( array ('&&', 'ParentId', '=', 0 ) );
		$o_dept1->PushOrder ( array ('Number', 'A' ) );
		//$o_dept1->setItem(array ('DeptId', 'Name' ) );
		$n_count1 = $o_dept1->getAllCount ();
		for($i = 0; $i < $n_count1; $i ++) { //找二级			
			$o_dept2 = new Base_Dept ();
			$o_dept2->PushWhere ( array ('&&', 'ParentId', '=', $o_dept1->getDeptId ( $i ) ) );
			$o_dept2->PushOrder ( array ('Number', 'A' ) );
			$o_dept2->setItem ( array ('DeptId', 'Name' ) );
			$n_count2 = $o_dept2->getAllCount ();
			$s_deptbutton1 .= '<div id="module_' . $o_dept1->getDeptId ( $i ) . '" style="display: none">
								<input type="button" onclick="allAddName(\'module_' . $o_dept1->getDeptId ( $i ) . '\')" class="all" value="全部添加" hidefocus="hidefocus" />
								<input type="button" onclick="allDeleteName(\'module_' . $o_dept1->getDeptId ( $i ) . '\')" class="delall" value="全部删除" hidefocus="hidefocus" /><br />';
			if ($n_count2 == 0) {
				//如果没有子部门，直接封口
				$s_deptbutton1 .= '</div>';
				$s_deptbutton .= $s_deptbutton1;
				$s_deptbutton1 = '';
			}
			$s_dept .= '<div class="dept1"><a href="javascript:openDeptSub(\'dept' . ($i + 1) . '\',' . $o_dept1->getDeptId ( $i ) . ');" hidefocus="hidefocus"><img id="dept_icon_' . $o_dept1->getDeptId ( $i ) . '" src="../../images/org/tree_off.png" alt=""/> ' . $o_dept1->getName ( $i ) . '</a></div>';
			for($j = 0; $j < $n_count2; $j ++) {
				$s_deptbutton1 .= '<input type="button" class="name" value="' . $o_dept2->getName ( $j ) . '" hidefocus="hidefocus" onclick="allModule(this,\'module_' . $o_dept2->getDeptId ( $j ) . '\')"/>';
				$s_deptbutton2 .= '<div id="module_' . $o_dept2->getDeptId ( $j ) . '" style="display: none">
										<input type="button" onclick="allAddName(\'module_' . $o_dept2->getDeptId ( $j ) . '\')" class="all" value="全部添加" hidefocus="hidefocus" />
										<input type="button" onclick="allDeleteName(\'module_' . $o_dept2->getDeptId ( $j ) . '\')" class="delall" value="全部删除" hidefocus="hidefocus" /><br />';
				$s_dept2 = '';
				$o_dept3 = new Base_Dept ();
				$o_dept3->PushWhere ( array ('&&', 'ParentId', '=', $o_dept2->getDeptId ( $j ) ) );
				$o_dept3->PushOrder ( array ('Number', 'A' ) );
				$o_dept3->setItem ( array ('DeptId', 'Name' ) );
				$n_count3 = $o_dept3->getAllCount ();
				if ($n_count3 == 0) { //如果没有子部门，直接封口
					//读取用户列表
					$o_user = new View_User_Dept ();
					//$o_user->PushWhere ( array ('&&', 'Uid', '<>', $n_uid ) );
					$o_user->PushWhere ( array ('&&', 'DeptId', '=', $o_dept2->getDeptId ( $j ) ) );
					$o_user->PushWhere ( array ('&&', 'State', '=', 1 ) );
					$n_user_count = $o_user->getAllCount ();
					for($l = 0; $l < $n_user_count; $l ++) {
						$s_deptbutton2 .= '<input id="uid_' . $o_user->getUid ( $l ) . '" type="button" class="name" value="' . $o_user->getName ( $l ) . '" hidefocus="hidefocus" onclick="addName(' . $o_user->getUid ( $l ) . ')"/>';
					}
					$s_deptbutton2 .= '</div>';
					$s_deptbutton .= $s_deptbutton2;
					$s_deptbutton2 = '';
				}
				$s_dept2 = '<div id="dept' . ($i + 1) . '_' . ($j + 1) . '" style="display:none"><div class="dept2"><a href="javascript:openDeptSub(\'dept' . ($i + 1) . '_' . ($j + 1) . '\',' . $o_dept2->getDeptId ( $j ) . ');" hidefocus="hidefocus"><img id="dept_icon_' . $o_dept2->getDeptId ( $j ) . '" src="../../images/org/tree_off.png" alt=""/> ' . $o_dept2->getName ( $j ) . '</a></div>';
				for($k = 0; $k < $n_count3; $k ++) {
					$s_dept2 .= '<div class="dept3" id="dept' . ($i + 1) . '_' . ($j + 1) . '_' . ($k + 1) . '" style="display:none"><a href="javascript:openDeptSub(\'dept' . ($i + 1) . '_' . ($j + 1) . '_' . ($k + 1) . '\',' . $o_dept3->getDeptId ( $k ) . ');" hidefocus="hidefocus"><img id="dept_icon_' . $o_dept3->getDeptId ( $k ) . '" src="../../images/org/tree_off.png" alt=""/> ' . $o_dept3->getName ( $k ) . '</a></div>';
					$s_deptbutton2 .= '<input type="button" class="name" value="' . $o_dept3->getName ( $k ) . '" hidefocus="hidefocus" onclick="allModule(this,\'module_' . $o_dept3->getDeptId ( $k ) . '\')"/>';
					$s_deptbutton3 .= '<div id="module_' . $o_dept3->getDeptId ( $k ) . '" style="display: none">
										<input type="button" onclick="allAddName(\'module_' . $o_dept3->getDeptId ( $k ) . '\')" class="all" value="全部添加" hidefocus="hidefocus" />
										<input type="button" onclick="allDeleteName(\'module_' . $o_dept3->getDeptId ( $k ) . '\')" class="delall" value="全部删除" hidefocus="hidefocus" /><br />';
					//读取用户列表
					$o_user = new View_User_Dept ();
					//$o_user->PushWhere ( array ('&&', 'Uid', '<>', $n_uid ) );
					$o_user->PushWhere ( array ('&&', 'DeptId', '=', $o_dept3->getDeptId ( $k ) ) );
					$o_user->PushWhere ( array ('&&', 'State', '=', 1 ) );
					$n_user_count = $o_user->getAllCount ();
					for($l = 0; $l < $n_user_count; $l ++) {
						$s_deptbutton3 .= '<input id="uid_' . $o_user->getUid ( $l ) . '" type="button" class="name" value="' . $o_user->getName ( $l ) . '" hidefocus="hidefocus" onclick="addName(' . $o_user->getUid ( $l ) . ')"/>';
					} //
					$s_deptbutton3 .= '</div>';
					$s_deptbutton .= $s_deptbutton3;
					$s_deptbutton3 = '';
					if (($k + 1) == $n_count3) { //没有下一子部门，直接封口
						//读取用户列表
						$o_user = new View_User_Dept ();
						//$o_user->PushWhere ( array ('&&', 'Uid', '<>', $n_uid ) );
						$o_user->PushWhere ( array ('&&', 'DeptId', '=', $o_dept2->getDeptId ( $j ) ) );
						$o_user->PushWhere ( array ('&&', 'State', '=', 1 ) );
						$n_user_count = $o_user->getAllCount ();
						for($l = 0; $l < $n_user_count; $l ++) {
							$s_deptbutton2 .= '<input id="uid_' . $o_user->getUid ( $l ) . '" type="button" class="name" value="' . $o_user->getName ( $l ) . '" hidefocus="hidefocus" onclick="addName(' . $o_user->getUid ( $l ) . ')"/>';
						}
						$s_deptbutton2 .= '</div>';
						$s_deptbutton .= $s_deptbutton2;
						$s_deptbutton2 = '';
					}
				}
				if ($s_dept2 != '') {
					$s_dept .= $s_dept2 . '</div>';
				}
				
				if (($j + 1) == $n_count2) {
					//读取用户列表
					$o_user = new View_User_Dept ();
					//$o_user->PushWhere ( array ('&&', 'Uid', '<>', $n_uid ) );
					$o_user->PushWhere ( array ('&&', 'DeptId', '=', $o_dept1->getDeptId ( $i ) ) );
					$o_user->PushWhere ( array ('&&', 'State', '=', 1 ) );
					$n_user_count = $o_user->getAllCount ();
					for($l = 0; $l < $n_user_count; $l ++) {
						$s_deptbutton1 .= '<input id="uid_' . $o_user->getUid ( $l ) . '" type="button" class="name" value="' . $o_user->getName ( $l ) . '" hidefocus="hidefocus" onclick="addName(' . $o_user->getUid ( $l ) . ')"/>';
					}
					$s_deptbutton1 .= '</div>';
					$s_deptbutton .= $s_deptbutton1;
					$s_deptbutton1 = '';
				}
			}
		
		}
		//构造按角色分
		$o_role = new Base_Role (); //构造按部门分
		$n_count = $o_role->getAllCount ();
		for($i = 0; $i < $n_count; $i ++) {
			$s_role .= '<tr><td style="padding-top:8px"><a href="javascript:openRoleName(' . $o_role->getRoleId ( $i ) . ');"><img src="../../images/org/tree_off.png" alt=""/> ' . $o_role->getName ( $i ) . '</a></td></tr>';
			$s_rolebutton .= '<div id="rolediv_' . $o_role->getRoleId ( $i ) . '" style="display: none">
								<input type="button" onclick="allAddName(\'rolediv_' . $o_role->getRoleId ( $i ) . '\')" class="all" value="全部添加" hidefocus="hidefocus" />
                                    <input type="button" onclick="allDeleteName(\'rolediv_' . $o_role->getRoleId ( $i ) . '\')" class="delall" value="全部删除" hidefocus="hidefocus" /><br />';
			//读取用户列表
			$o_user = new View_User_Dept ();
			//$o_user->PushWhere ( array ('&&', 'Uid', '<>', $n_uid ) );
			$o_user->PushWhere ( array ('&&', 'RoleId', '=', $o_role->getRoleId ( $i ) ) );
			$o_user->PushWhere ( array ('&&', 'State', '=', 1 ) );
			$n_user_count = $o_user->getAllCount ();
			for($l = 0; $l < $n_user_count; $l ++) {
				$s_rolebutton .= '<input id="role' . $o_user->getUid ( $l ) . '" type="button" class="name" value="' . $o_user->getName ( $l ) . '" hidefocus="hidefocus" onclick="addName(' . $o_user->getUid ( $l ) . ')"/>';
			}
			$s_rolebutton .= '</div>';
		}
		$s_html = '
            <tr>
                <td colspan="2" style="padding:0px;height:0px;background-color: White; border-bottom: solid 0px #CCCCCC;border-right: solid 1px #CCCCCC;
                    text-align: center">
                  <div style="display:none;overflow: hidden;" id="group">
                   <div style="height:300px;overflow:auto;overflow-x:hidden">
                    <table border="0" cellpadding="0" cellspacing="0" class="group">
                        <tr>
                            <td class="left" nowrap="nowrap">
                            
                                <div class="head">
                                    <a href="javascript:;" onclick="openDept(this)" class="header" hidefocus="hidefocus">按部门选择</a></div>
                                <div id="dept" style="display:none;">
                                <table class="dept" border="0" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td>
                                             ' . $s_dept . ' 
                                        </td>
                                    </tr>                                    
                                </table>
                                </div>
                                <div class="head" style="border-bottom: solid 1px #5B99CA;">
                                    <a href="javascript:;" onclick="openRole(this)" class="header" hidefocus="hidefocus">按角色选择</a></div>
                                <div id="role" style="display: none">
                                <table border="0" cellpadding="0" cellspacing="0" class="role">
								' . $s_role . '
                                </table>
                                </div>
                            </td>
                            <td class="right" id="allname">
                                ' . $s_deptbutton . $s_rolebutton . '  
                                                
                            </td>
                        </tr>
                    </table>
                    </div>  
                    <input value="确定" onclick="openGroup()" class="BigButtonA" type="button" style="margin-bottom: 5px" />
                   </div>
                </td>
            </tr>
		';
		return $s_html;
	}
	public function getGroupForDesktop() {
		$s_html = '';
		$s_dept1 = '';
		$s_dept2 = '';
		$s_role = '';
		$s_deptbutton = '';
		$s_deptbutton1 = '';
		$s_deptbutton2 = '';
		$s_deptbutton3 = '';
		$s_rolebutton = '';
		$o_dept1 = new Base_Dept (); //构造按部门分
		$o_dept1->PushWhere ( array ('&&', 'ParentId', '=', 0 ) );
		$o_dept1->PushOrder ( array ('Number', 'A' ) );
		//$o_dept1->setItem(array ('DeptId', 'Name' ) );
		$n_count1 = $o_dept1->getAllCount ();
		for($i = 0; $i < $n_count1; $i ++) { //找二级			
			$o_dept2 = new Base_Dept ();
			$o_dept2->PushWhere ( array ('&&', 'ParentId', '=', $o_dept1->getDeptId ( $i ) ) );
			$o_dept2->PushOrder ( array ('Number', 'A' ) );
			$o_dept2->setItem ( array ('DeptId', 'Name' ) );
			$n_count2 = $o_dept2->getAllCount ();
			$s_dept .= '<div class="dept1"><a href="javascript:openDeptSub(\'dept' . ($i + 1) . '\',' . $o_dept1->getDeptId ( $i ) . ');" style="color:black" hidefocus="hidefocus"><img id="dept_icon_' . $o_dept1->getDeptId ( $i ) . '" src="' . RELATIVITY_PATH . 'images/org/tree_off.png" style="width:10px;height:10px" alt=""/>&nbsp;' . $o_dept1->getName ( $i ) . '</a></div>';
			for($j = 0; $j < $n_count2; $j ++) {
				$s_dept2 = '';
				$o_dept3 = new Base_Dept ();
				$o_dept3->PushWhere ( array ('&&', 'ParentId', '=', $o_dept2->getDeptId ( $j ) ) );
				$o_dept3->PushOrder ( array ('Number', 'A' ) );
				$o_dept3->setItem ( array ('DeptId', 'Name' ) );
				$n_count3 = $o_dept3->getAllCount ();
				$s_dept2 = '<div id="dept' . ($i + 1) . '_' . ($j + 1) . '" style="display:none"><div class="dept2"><a href="javascript:openDeptSub(\'dept' . ($i + 1) . '_' . ($j + 1) . '\',' . $o_dept2->getDeptId ( $j ) . ');" hidefocus="hidefocus"><img id="dept_icon_' . $o_dept2->getDeptId ( $j ) . '" src="' . RELATIVITY_PATH . 'images/org/tree_off.png" style="width:10px;height:10px" alt=""/> ' . $o_dept2->getName ( $j ) . '</a></div>';
				for($k = 0; $k < $n_count3; $k ++) {
					$s_dept2 .= '<div id="dept' . ($i + 1) . '_' . ($j + 1) . '_' . ($k + 1) . '" style="display:none"><div class="dept3"><a href="javascript:openDeptSub(\'dept' . ($i + 1) . '_' . ($j + 1) . '_' . ($k + 1) . '\',' . $o_dept3->getDeptId ( $k ) . ');" hidefocus="hidefocus"><img id="dept_icon_' . $o_dept3->getDeptId ( $k ) . '" src="' . RELATIVITY_PATH . 'images/org/tree_off.png" style="width:10px;height:10px" alt=""/> ' . $o_dept3->getName ( $k ) . '</a></div>';
					$o_user = new View_User_Dept ();
					$o_user->PushWhere ( array ('&&', 'DeptId', '=', $o_dept3->getDeptId ( $k ) ) );
					$o_user->PushWhere ( array ('&&', 'State', '=', 1 ) );
					$n_user_count = $o_user->getAllCount ();
					for($l = 0; $l < $n_user_count; $l ++) {
						$s_dept2 .= '<div id="dept' . ($i + 1) . '_' . ($j + 1) . '_' . ($k + 1) . '_' . ($l + 1) . '" style="display: none">
                            	<div class="dept4">
                                   <a href="javascript:buttonGetUserInfo(' . $o_user->getUid ( $l ) . ');" hidefocus="hidefocus">
                                        <img src="' . RELATIVITY_PATH . 'images/org/U01.png" alt="">' . $o_user->getName ( $l ) . '
                                   </a>
                                </div>
                        	</div>';
					}
					$s_dept2 .= '</div>';
				}
				//读取用户列表
				$o_user = new View_User_Dept ();
				$o_user->PushWhere ( array ('&&', 'DeptId', '=', $o_dept2->getDeptId ( $j ) ) );
				$o_user->PushWhere ( array ('&&', 'State', '=', 1 ) );
				$n_user_count = $o_user->getAllCount ();
				for($l = 0; $l < $n_user_count; $l ++) {
					$s_dept2 .= '<div id="dept' . ($i + 1) . '_' . ($j + 1) . '_' . ($n_count3 + $l + 1) . '" style="display: none">
                            	<div class="dept3">
                                   <a href="javascript:buttonGetUserInfo(' . $o_user->getUid ( $l ) . ');" hidefocus="hidefocus">
                                        <img src="' . RELATIVITY_PATH . 'images/org/U01.png" alt="">' . $o_user->getName ( $l ) . '
                                   </a>
                                </div>
                        	</div>';
				}
				if ($s_dept2 != '') {
					$s_dept .= $s_dept2 . '</div>';
				}
			}
			//读取用户列表
			$o_user = new View_User_Dept ();
			$o_user->PushWhere ( array ('&&', 'DeptId', '=', $o_dept1->getDeptId ( $i ) ) );
			$o_user->PushWhere ( array ('&&', 'State', '=', 1 ) );
			$n_user_count = $o_user->getAllCount ();
			for($l = 0; $l < $n_user_count; $l ++) {
				$s_dept .= '<div id="dept' . ($i + 1) . '_' . ($n_count2 + $l + 1) . '" style="display: none">
                            	<div class="dept2">
                                   <a href="javascript:buttonGetUserInfo(' . $o_user->getUid ( $l ) . ');" hidefocus="hidefocus">
                                        <img src="' . RELATIVITY_PATH . 'images/org/U01.png" alt="">&nbsp;' . $o_user->getName ( $l ) . '
                                   </a>
                                </div>
                        	</div>';
			}
		
		}
		//构造按角色分
		$s_html = '<div id="dept" style="display:block"><table border="0" cellpadding="0" cellspacing="0" class="group" style="width: 178px">
                        <tr>
                            <td>
                                <table class="dept" border="0" cellpadding="0" cellspacing="0" style="border:0px">
                                    <tr>
                                        <td>' . $s_dept . '
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                    </div>
		';
		return $s_html;
	}
	public function getGroupForNavigation($n_uid) {
		$s_html = '';
		$s_dept1 = '';
		$s_dept2 = '';
		$s_role = '';
		$s_deptbutton = '';
		$s_deptbutton1 = '';
		$s_deptbutton2 = '';
		$s_deptbutton3 = '';
		$s_rolebutton = '';
		$o_dept1 = new Base_Dept (); //构造按部门分
		$o_dept1->PushWhere ( array ('&&', 'ParentId', '=', 0 ) );
		$o_dept1->PushOrder ( array ('Number', 'A' ) );
		//$o_dept1->setItem(array ('DeptId', 'Name' ) );
		$n_count1 = $o_dept1->getAllCount ();
		for($i = 0; $i < $n_count1; $i ++) { //找二级			
			$o_dept2 = new Base_Dept ();
			$o_dept2->PushWhere ( array ('&&', 'ParentId', '=', $o_dept1->getDeptId ( $i ) ) );
			$o_dept2->PushOrder ( array ('Number', 'A' ) );
			$o_dept2->setItem ( array ('DeptId', 'Name' ) );
			$n_count2 = $o_dept2->getAllCount ();
			$s_dept .= '<div class="dept1"><a href="javascript:openDeptSub(\'dept' . ($i + 1) . '\',' . $o_dept1->getDeptId ( $i ) . ');" hidefocus="hidefocus"><img id="dept_icon_' . $o_dept1->getDeptId ( $i ) . '" src="' . RELATIVITY_PATH . 'images/org/tree_off.png" alt=""/>&nbsp;' . $o_dept1->getName ( $i ) . '</a></div>';
			for($j = 0; $j < $n_count2; $j ++) {
				$s_dept2 = '';
				$o_dept3 = new Base_Dept ();
				$o_dept3->PushWhere ( array ('&&', 'ParentId', '=', $o_dept2->getDeptId ( $j ) ) );
				$o_dept3->PushOrder ( array ('Number', 'A' ) );
				$o_dept3->setItem ( array ('DeptId', 'Name' ) );
				$n_count3 = $o_dept3->getAllCount ();
				$s_dept2 = '<div id="dept' . ($i + 1) . '_' . ($j + 1) . '" style="display:none"><div class="dept2"><a href="javascript:openDeptSub(\'dept' . ($i + 1) . '_' . ($j + 1) . '\',' . $o_dept2->getDeptId ( $j ) . ');" hidefocus="hidefocus"><img id="dept_icon_' . $o_dept2->getDeptId ( $j ) . '" src="' . RELATIVITY_PATH . 'images/org/tree_off.png" alt=""/> ' . $o_dept2->getName ( $j ) . '</a></div>';
				for($k = 0; $k < $n_count3; $k ++) {
					$s_dept2 .= '<div id="dept' . ($i + 1) . '_' . ($j + 1) . '_' . ($k + 1) . '" style="display:none"><div class="dept3"><a href="javascript:openDeptSub(\'dept' . ($i + 1) . '_' . ($j + 1) . '_' . ($k + 1) . '\',' . $o_dept3->getDeptId ( $k ) . ');" hidefocus="hidefocus"><img id="dept_icon_' . $o_dept3->getDeptId ( $k ) . '" src="' . RELATIVITY_PATH . 'images/org/tree_off.png" alt=""/> ' . $o_dept3->getName ( $k ) . '</a></div>';
					$o_user = new View_User_Dept ();
					$o_user->PushWhere ( array ('&&', 'Uid', '<>', $n_uid ) );
					$o_user->PushWhere ( array ('&&', 'DeptId', '=', $o_dept3->getDeptId ( $k ) ) );
					$o_user->PushWhere ( array ('&&', 'State', '=', 1 ) );
					$n_user_count = $o_user->getAllCount ();
					for($l = 0; $l < $n_user_count; $l ++) {
						$s_dept2 .= '<div id="dept' . ($i + 1) . '_' . ($j + 1) . '_' . ($k + 1) . '_' . ($l + 1) . '" style="display: none">
                            	<div class="dept4">
                                   <a href="javascript:goTo(\'msg_show.php?uid=' . $o_user->getUid ( $l ) . '\');" hidefocus="hidefocus">
                                        <img src="../../images/org/U01.png" alt="">' . $o_user->getName ( $l ) . '
                                   </a>
                                </div>
                        	</div>';
					}
					$s_dept2 .= '</div>';
				}
				//读取用户列表
				$o_user = new View_User_Dept ();
				$o_user->PushWhere ( array ('&&', 'Uid', '<>', $n_uid ) );
				$o_user->PushWhere ( array ('&&', 'DeptId', '=', $o_dept2->getDeptId ( $j ) ) );
				$o_user->PushWhere ( array ('&&', 'State', '=', 1 ) );
				$n_user_count = $o_user->getAllCount ();
				for($l = 0; $l < $n_user_count; $l ++) {
					$s_dept2 .= '<div id="dept' . ($i + 1) . '_' . ($j + 1) . '_' . ($n_count3 + $l + 1) . '" style="display: none">
                            	<div class="dept3">
                                   <a href="javascript:goTo(\'msg_show.php?uid=' . $o_user->getUid ( $l ) . '\');" hidefocus="hidefocus">
                                        <img src="../../images/org/U01.png" alt="">' . $o_user->getName ( $l ) . '
                                   </a>
                                </div>
                        	</div>';
				}
				if ($s_dept2 != '') {
					$s_dept .= $s_dept2 . '</div>';
				}
			}
			//读取用户列表
			$o_user = new View_User_Dept ();
			$o_user->PushWhere ( array ('&&', 'Uid', '<>', $n_uid ) );
			$o_user->PushWhere ( array ('&&', 'DeptId', '=', $o_dept1->getDeptId ( $i ) ) );
			$o_user->PushWhere ( array ('&&', 'State', '=', 1 ) );
			$n_user_count = $o_user->getAllCount ();
			for($l = 0; $l < $n_user_count; $l ++) {
				$s_dept .= '<div id="dept' . ($i + 1) . '_' . ($n_count2 + $l + 1) . '" style="display: none">
                            	<div class="dept2">
                                   <a href="javascript:goTo(\'msg_show.php?uid=' . $o_user->getUid ( $l ) . '\');" hidefocus="hidefocus">
                                        <img src="../../images/org/U01.png" alt="">&nbsp;' . $o_user->getName ( $l ) . '
                                   </a>
                                </div>
                        	</div>';
			}
		
		}
		//构造按角色分
		$s_html = '<div id="dept" style="display:none">
					<table border="0" cellpadding="0" cellspacing="0" class="group" style="width: 178px">
                        <tr>
                            <td>
                                <table class="dept" border="0" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td style="padding: 0px">
                                           ' . $s_dept . '
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                    </div>
		';
		return $s_html;
	}
	public function getDesktopModuleSort() {
		$o_user = new Base_User_Desktop ( $this->O_SingleUser->getUid () );
		if ($o_user->getModuleSort () == null) {
			return '';
		} else {
			return $o_user->getModuleSort ();
		}
	}
	public function getAmDesktop() {
		$a_moduleid = array ();
		$o_userModule = new View_User_Right ();
		$o_userModule->PushWhere ( array ('&&', 'Uid', '=', $this->O_SingleUser->getUid () ) );
		$o_userModule->PushWhere ( array ('&&', 'ParentModuleId', '=', 0 ) );
		$n_count = $o_userModule->getAllCount ();
		$s_html = '';
		for($i = 0; $i < $n_count; $i ++) {
			//构建图标提示			
			array_push ( $a_moduleid, $o_userModule->getModuleId ( $i ) );
			$o_wait = '';
			if ($o_userModule->getPath ( $i ) != '') {
				eval ( 'require_once RELATIVITY_PATH . \'' . $o_userModule->getPath ( $i ) . 'include/db_table.class.php\';' );
				eval ( '$o_wait=new ' . $o_userModule->getWaitReadTable ( $i ) . '($this->O_SingleUser->getUid ());' );
				if ($o_wait->getWaitRead () > 0) {
					$s_html .= '
					<div>
                        <img src="../../' . $o_userModule->getMiniIconPath ( $i ) . '" alt="" align="absmiddle" />&nbsp;&nbsp;<a
                            href="javascript:;" onclick="autoLogin(\''.$this->O_SingleUser->getUserName().'\',\''.$this->O_SingleUser->getPassword().'\','.$o_userModule->getModuleId ( $i ) .')">' . $o_userModule->getModuleName ( $i ) . '&nbsp;&nbsp;<span>(' . $o_wait->getWaitRead () . ')</span></a>
                    </div>';
				}
			}
		
		}
		for($k = 1; $k <= 5; $k ++) {
			//附属权限
			eval ( '$o_userModule = new View_User_Right_Sec' . $k . ' ();' );
			$o_userModule->PushWhere ( array ('&&', 'Uid', '=', $this->O_SingleUser->getUid () ) );
			$o_userModule->PushWhere ( array ('&&', 'ParentModuleId', '=', 0 ) );
			$n_count = $o_userModule->getAllCount ();
			for($i = 0; $i < $n_count; $i ++) {
				if (! (array_search ( $o_userModule->getModuleId ( $i ), $a_moduleid ) === FALSE)) {
					continue;
				}
				//构建图标提示
				array_push ( $a_moduleid, $o_userModule->getModuleId ( $i ) );
				$o_wait = '';
				if ($o_userModule->getPath ( $i ) != '') {
					eval ( 'require_once RELATIVITY_PATH . \'' . $o_userModule->getPath ( $i ) . 'include/db_table.class.php\';' );
					eval ( '$o_wait=new ' . $o_userModule->getWaitReadTable ( $i ) . '($this->O_SingleUser->getUid ());' );
					if ($o_wait->getWaitRead () > 0) {
						$s_html .= '
					<div>
                        <img src="../../' . $o_userModule->getMiniIconPath ( $i ) . '" alt="" align="absmiddle" />&nbsp;&nbsp;<a
                            href="javascript:;" onclick="">' . $o_userModule->getModuleName ( $i ) . '&nbsp;&nbsp;<span>(' . $o_wait->getWaitRead () . ')</span></a>
                    </div>';
					}
				}
			}
		}
		if ($s_html=='')
		{
			return '<span style="font-weight:normal;">目前没有未完成的任务</span>';
		}
		return  $s_html ;
	}
	public function getDesktop() {
		$a_moduleid = array ();
		$o_userModule = new View_User_Right ();
		$o_userModule->PushWhere ( array ('&&', 'Uid', '=', $this->O_SingleUser->getUid () ) );
		$o_userModule->PushWhere ( array ('&&', 'ParentModuleId', '=', 0 ) );
		$n_count = $o_userModule->getAllCount ();
		$s_html = '';
		$o_operate='';
		for($i = 0; $i < $n_count; $i ++) {
			//构建图标提示
			array_push ( $a_moduleid, $o_userModule->getModuleId ( $i ) );
			$n_wait=0;
			if ($o_userModule->getPath ( $i ) != '') {

				eval ( 'require_once RELATIVITY_PATH . \'' . $o_userModule->getPath ( $i ) . 'include/ajax_operate.class.php\';' );
				eval ( '$o_operate=new Operating_'.$o_userModule->getWaitReadTable ( $i ).'();' );
				$n_wait=$o_operate->getWaitRead($this->O_SingleUser->getUid ());
				if ($n_wait > 9) {
					$s_classname = 'count count10';
				} else {
					$s_classname = 'count count' . $n_wait;
				}
				if ($n_wait > 0) {
					$s_function = 'addTab';
				} else {
					$s_function = 'addTab';
				}
			} else {
				$s_classname = 'count';
				$s_function = 'addTab';
			}
			$s_html .= '
						<li id="moduleid_' . $o_userModule->getModuleId ( $i ) . '" class="block" title="' . $o_userModule->getModuleName ( $i ) . '" style="margin-left: 19px; margin-right: 19px;" onclick="parent.' . $s_function . '(' . $o_userModule->getModuleId ( $i ) . ',0)">
                        <div class="img">
                            <p>
                                <img src="' . $o_userModule->getIconPathB ( $i ) . '" />
                            </p>
                            <div class="' . $s_classname . '">
                            </div>
                        </div>
                        <a class="icon-text" href="javascript:;"><span>' . $o_userModule->getModuleName ( $i ) . '</span></a></li>';
		}
		for($k = 1; $k <= 5; $k ++) {
			eval ( '$o_userModule = new View_User_Right_Sec' . $k . ' ();' );
			$o_userModule->PushWhere ( array ('&&', 'Uid', '=', $this->O_SingleUser->getUid () ) );
			$o_userModule->PushWhere ( array ('&&', 'ParentModuleId', '=', 0 ) );
			$n_count = $o_userModule->getAllCount ();
			for($i = 0; $i < $n_count; $i ++) {
				if (! (array_search ( $o_userModule->getModuleId ( $i ), $a_moduleid ) === FALSE)) {
					continue;
				}
				//构建图标提示
				array_push ( $a_moduleid, $o_userModule->getModuleId ( $i ) );
				$o_wait = '';
				if ($o_userModule->getPath ( $i ) != '') {
					eval ( 'require_once RELATIVITY_PATH . \'' . $o_userModule->getPath ( $i ) . 'include/ajax_operate.class.php\';' );
					eval ( '$o_operate=new Operating_'.$o_userModule->getWaitReadTable ( $i ).'();' );
					$n_wait=$o_operate->getWaitRead($this->O_SingleUser->getUid ());
					if ($n_wait > 9) {
						$s_classname = 'count count10';
					} else {
						$s_classname = 'count count' . $n_wait;
					}
					if ($n_wait > 0) {
						$s_function = 'addTab';
					} else {
						$s_function = 'addTab';
					}
				} else {
					$s_classname = 'count';
					$s_function = 'addTab';
				}
				$s_html .= '
						<li id="moduleid_' . $o_userModule->getModuleId ( $i ) . '" class="block" title="' . $o_userModule->getModuleName ( $i ) . '" style="margin-left: 19px; margin-right: 19px;" onclick="parent.' . $s_function . '(' . $o_userModule->getModuleId ( $i ) . ',0)">
                        <div class="img">
                            <p>
                                <img src="' . $o_userModule->getIconPathB ( $i ) . '" />
                            </p>
                            <div class="' . $s_classname . '">
                            </div>
                        </div>
                        <a class="icon-text" href="javascript:;"><span>' . $o_userModule->getModuleName ( $i ) . '</span></a></li>';
			}
		}
		return '<ul id="ui-sortable" class="ui-sortable">' . $s_html . '</ul>';
	}
	public function getMenu() {
		$a_moduleid1 = array ();
		$o_userModule1 = new View_Menu_Right ();
		$o_userModule1->PushWhere ( array ('&&', 'Uid', '=', $this->O_SingleUser->getUid () ) );
		$o_userModule1->PushWhere ( array ('&&', 'ParentModuleId', '=', 0 ) );
		$o_userModule1->PushOrder ( array ('Module', 'A' ) );
		$n_count1 = $o_userModule1->getAllCount ();
		for($i = 0; $i < $n_count1; $i ++) {
			array_push ( $a_moduleid1, $o_userModule1->getModuleId ( $i ) );
			$s_rolename = $o_userModule1->getRoleName ( 0 );
			if ($i == 0) {
				$s_class1 = 'active';
			} else {
				$s_class1 = '';
			}
			$s_module1 .= '
                        <li>
                            <a href="javascript:;" hidefocus="hidefocus" class="' . $s_class1 . '" onclick="menuShowNav2(this,' . $o_userModule1->getModuleId ( $i ) . ')">
                                <img src="' . $o_userModule1->getMiniIconPath ( $i ) . '" align="absMiddle" height="20" width="20"/>
								' . $o_userModule1->getModuleName ( $i ) . '
                             </a>
                         </li>
			';
		}
		for($k = 1; $k <= 5; $k ++) {
			eval ( '$o_userModule1 = new View_Menu_Right_Sec' . $k . ' ();' );
			$o_userModule1->PushWhere ( array ('&&', 'Uid', '=', $this->O_SingleUser->getUid () ) );
			$o_userModule1->PushWhere ( array ('&&', 'ParentModuleId', '=', 0 ) );
			$o_userModule1->PushOrder ( array ('Module', 'A' ) );
			$n_count1 = $o_userModule1->getAllCount ();
			for($i = 0; $i < $n_count1; $i ++) {
				if (! (array_search ( $o_userModule1->getModuleId ( $i ), $a_moduleid1 ) === FALSE)) {
					continue;
				}
				array_push ( $a_moduleid1, $o_userModule1->getModuleId ( $i ) );
				$s_rolename = $o_userModule1->getRoleName ( 0 );
				$s_module1 .= '
                        <li>
                            <a href="javascript:;" hidefocus="hidefocus" class="' . $s_class1 . '" onclick="menuShowNav2(this,' . $o_userModule1->getModuleId ( $i ) . ')">
                                <img src="' . $o_userModule1->getMiniIconPath ( $i ) . '" align="absMiddle" height="20" width="20"/>
								' . $o_userModule1->getModuleName ( $i ) . '
                             </a>
                         </li>
			';
			}
		}
		$n_menuheight = count ( $a_moduleid1 ) * 31;
		//
		

		for($i = 0; $i < count ( $a_moduleid1 ); $i ++) {
			$a_moduleid2 = array ();
			//二级菜单
			if ($i == 0) {
				$s_style = "display:block";
			} else {
				$s_style = "display:none";
			}
			$o_userModule2 = new View_Menu_Right ();
			$o_userModule2->PushWhere ( array ('&&', 'Uid', '=', $this->O_SingleUser->getUid () ) );
			$o_userModule2->PushWhere ( array ('&&', 'ParentModuleId', '=', $a_moduleid1 [$i] ) );
			$o_userModule2->PushOrder ( array ('Module', 'A' ) );
			$n_count2 = $o_userModule2->getAllCount ();
			$s_module2 .= '<ul id="menuNav2_' . $a_moduleid1 [$i] . '" style="' . $s_style . '">';
			for($j = 0; $j < $n_count2; $j ++) {
				array_push ( $a_moduleid2, $o_userModule2->getModuleId ( $j ) );
				$s_module2 .= '				           
                                <li>
                                    <a href="javascript:;" onclick="menuShowAndHide();addTabForRefresh(' . $o_userModule2->getParentModuleId ( $j ) . ',' . $o_userModule2->getModuleId ( $j ) . ')" hidefocus="hidefocus">
                                        <span>' . $o_userModule2->getModuleName ( $j ) . '</span>
                                    </a>
                                </li>                           
				';
			}
			for($k = 1; $k <= 5; $k ++) {
				eval ( '$o_userModule2 = new View_Menu_Right_Sec' . $k . ' ();' );
				$o_userModule2->PushWhere ( array ('&&', 'Uid', '=', $this->O_SingleUser->getUid () ) );
				$o_userModule2->PushWhere ( array ('&&', 'ParentModuleId', '=', $a_moduleid1 [$i] ) );
				$o_userModule2->PushOrder ( array ('Module', 'A' ) );
				$n_count2 = $o_userModule2->getAllCount ();
				for($j = 0; $j < $n_count2; $j ++) {
					if (! (array_search ( $o_userModule2->getModuleId ( $j ), $a_moduleid2 ) === FALSE)) {
						continue;
					}
					array_push ( $a_moduleid2, $o_userModule2->getModuleId ( $j ) );
					$s_module2 .= '				           
                                <li>
                                    <a href="javascript:;" onclick="menuShowAndHide();addTabForRefresh(' . $o_userModule2->getParentModuleId ( $j ) . ',' . $o_userModule2->getModuleId ( $j ) . ')" hidefocus="hidefocus">
                                        <span>' . $o_userModule2->getModuleName ( $j ) . '</span>
                                    </a>
                                </li>                           
				';
				}
			}
			$s_module2 .= '</ul>';
			if ($n_menuheight < (count ( $a_moduleid2 ) * 33)) {
				$n_menuheight = count ( $a_moduleid2 ) * 33;
			}
		}
		if ($this->O_SingleUser->getSex () == '男') {
			$s_img = 'images/avatar/0.gif';
		} else {
			$s_img = 'images/avatar/1.gif';
		}
		$s_html = '
					<div style="top: 112px; display: none;" id="start_menu_panel">
			            <div class="panel-head">
			            </div>
			            <!-- 登录用户信息 -->
			            <div class="panel-user">
			                <div class="avatar">
			                    <img src="' . $s_img . '" align="absMiddle"/>
			                </div>
			                <div class="name">
			                    ' . $this->O_SingleUser->getName () . '</div>
			                <div class="tools">
			                    <a class="exit" href="javascript:;" onclick="menuShowAndHide();Dialog_Confirm(\'确定要退出系统吗？\',function (){location=\'index.php?loginout=true\'});" hidefocus="hidefocus" title="退出">
			                    </a>
			                </div>
			            </div>
			            <div class="panel-menu">
			                <!-- 一级菜单 -->
			                <div id="first_panel">
			                    <div style="display: none;" class="scroll-up">
			                    </div>
			                    <ul style="height: ' . $n_menuheight . 'px;" id="first_menu">
			                        ' . $s_module1 . '
			                     </ul>
			                    <div style="display: none;" class="scroll-down">
			                    </div>
			                </div>
			                <!-- 二级级菜单 -->
			                <div style="top: 68px;height:' . $n_menuheight . 'px" id="second_panel">
			                    <div style="height: ' . $n_menuheight . 'px; overflow: hidden; position: relative; padding: 0px; margin: 0px;"
			                        class="second-panel-menu">
			                        <div class="second-panel-head"></div>
			                        <div class="jscroll-c" style="top: 0px; height: ' . ($n_menuheight - 10) . 'px;z-index: 9999; position: relative;">
			                            ' . $s_module2 . '
			                        </div>
			                        <div class="second-panel-foot"></div>
			                    </div>
			                </div>
			            </div>
			            <div class="panel-foot">
			            </div>
			        </div>
		';
		return $s_html;
	}
	public function getGroupForEmail($n_uid) {
		$s_html = '';
		$s_dept = '';
		$s_dept2 = '';
		$s_role = '';
		$s_deptbutton = '';
		$s_deptbutton1 = '';
		$s_deptbutton2 = '';
		$s_deptbutton3 = '';
		$s_rolebutton = '';
		$o_dept1 = new Base_Dept (); //构造按部门分
		$o_dept1->PushWhere ( array ('&&', 'ParentId', '=', 0 ) );
		$o_dept1->PushOrder ( array ('Number', 'A' ) );
		//$o_dept1->setItem(array ('DeptId', 'Name' ) );
		$n_count1 = $o_dept1->getAllCount ();
		for($i = 0; $i < $n_count1; $i ++) { //找二级			
			$o_dept2 = new Base_Dept ();
			$o_dept2->PushWhere ( array ('&&', 'ParentId', '=', $o_dept1->getDeptId ( $i ) ) );
			$o_dept2->PushOrder ( array ('Number', 'A' ) );
			$o_dept2->setItem ( array ('DeptId', 'Name' ) );
			$n_count2 = $o_dept2->getAllCount ();
			$s_deptbutton1 .= '<div id="module_' . $o_dept1->getDeptId ( $i ) . '" style="display: none;width:650px">
								<input type="button" onclick="allAddName(\'module_' . $o_dept1->getDeptId ( $i ) . '\')" class="all" value="全部添加" hidefocus="hidefocus" />
								<input type="button" onclick="allDeleteName(\'module_' . $o_dept1->getDeptId ( $i ) . '\')" class="delall" value="全部删除" hidefocus="hidefocus" /><br />';
			if ($n_count2 == 0) {
				//如果没有子部门，直接封口
				$s_deptbutton1 .= '</div>';
				$s_deptbutton .= $s_deptbutton1;
				$s_deptbutton1 = '';
			}
			$s_dept .= '<div class="dept1"><a href="javascript:openDeptSub(\'dept' . ($i + 1) . '\',' . $o_dept1->getDeptId ( $i ) . ');" hidefocus="hidefocus"><img id="dept_icon_' . $o_dept1->getDeptId ( $i ) . '" src="../../images/org/tree_off.png" alt=""/> ' . $o_dept1->getName ( $i ) . '</a></div>';
			for($j = 0; $j < $n_count2; $j ++) {
				$s_deptbutton1 .= '<input type="button" class="name" value="' . $o_dept2->getName ( $j ) . '" hidefocus="hidefocus" onclick="allModule(this,\'module_' . $o_dept2->getDeptId ( $j ) . '\')"/>';
				$s_deptbutton2 .= '<div id="module_' . $o_dept2->getDeptId ( $j ) . '" style="display: none;width:650px">
										<input type="button" onclick="allAddName(\'module_' . $o_dept2->getDeptId ( $j ) . '\')" class="all" value="全部添加" hidefocus="hidefocus" />
										<input type="button" onclick="allDeleteName(\'module_' . $o_dept2->getDeptId ( $j ) . '\')" class="delall" value="全部删除" hidefocus="hidefocus" /><br />';
				$s_dept2 = '';
				$o_dept3 = new Base_Dept ();
				$o_dept3->PushWhere ( array ('&&', 'ParentId', '=', $o_dept2->getDeptId ( $j ) ) );
				$o_dept3->PushOrder ( array ('Number', 'A' ) );
				$o_dept3->setItem ( array ('DeptId', 'Name' ) );
				$n_count3 = $o_dept3->getAllCount ();
				if ($n_count3 == 0) { //如果没有子部门，直接封口
					//读取用户列表
					$o_user = new View_User_Dept ();
					$o_user->PushWhere ( array ('&&', 'Uid', '<>', $n_uid ) );
					$o_user->PushWhere ( array ('&&', 'DeptId', '=', $o_dept2->getDeptId ( $j ) ) );
					$o_user->PushWhere ( array ('&&', 'State', '=', 1 ) );
					$n_user_count = $o_user->getAllCount ();
					for($l = 0; $l < $n_user_count; $l ++) {
						$s_deptbutton2 .= '<input id="uid_' . $o_user->getUid ( $l ) . '" type="button" class="name" value="' . $o_user->getName ( $l ) . '" hidefocus="hidefocus" onclick="addName(' . $o_user->getUid ( $l ) . ')"/>';
					}
					$s_deptbutton2 .= '</div>';
					$s_deptbutton .= $s_deptbutton2;
					$s_deptbutton2 = '';
				}
				$s_dept2 = '<div id="dept' . ($i + 1) . '_' . ($j + 1) . '" style="display:none;"><div class="dept2"><a href="javascript:openDeptSub(\'dept' . ($i + 1) . '_' . ($j + 1) . '\',' . $o_dept2->getDeptId ( $j ) . ');" hidefocus="hidefocus"><img id="dept_icon_' . $o_dept2->getDeptId ( $j ) . '" src="../../images/org/tree_off.png" alt=""/> ' . $o_dept2->getName ( $j ) . '</a></div>';
				for($k = 0; $k < $n_count3; $k ++) {
					$s_dept2 .= '<div class="dept3" id="dept' . ($i + 1) . '_' . ($j + 1) . '_' . ($k + 1) . '" style="display:none"><a href="javascript:openDeptSub(\'dept' . ($i + 1) . '_' . ($j + 1) . '_' . ($k + 1) . '\',' . $o_dept3->getDeptId ( $k ) . ');" hidefocus="hidefocus"><img id="dept_icon_' . $o_dept3->getDeptId ( $k ) . '" src="../../images/org/tree_off.png" alt=""/> ' . $o_dept3->getName ( $k ) . '</a></div>';
					$s_deptbutton2 .= '<input type="button" class="name" value="' . $o_dept3->getName ( $k ) . '" hidefocus="hidefocus" onclick="allModule(this,\'module_' . $o_dept3->getDeptId ( $k ) . '\')"/>';
					$s_deptbutton3 .= '<div id="module_' . $o_dept3->getDeptId ( $k ) . '" style="display: none;width:600px">
										<input type="button" onclick="allAddName(\'module_' . $o_dept3->getDeptId ( $k ) . '\')" class="all" value="全部添加" hidefocus="hidefocus" />
										<input type="button" onclick="allDeleteName(\'module_' . $o_dept3->getDeptId ( $k ) . '\')" class="delall" value="全部删除" hidefocus="hidefocus" /><br />';
					//读取用户列表
					$o_user = new View_User_Dept ();
					$o_user->PushWhere ( array ('&&', 'Uid', '<>', $n_uid ) );
					$o_user->PushWhere ( array ('&&', 'DeptId', '=', $o_dept3->getDeptId ( $k ) ) );
					$o_user->PushWhere ( array ('&&', 'State', '=', 1 ) );
					$n_user_count = $o_user->getAllCount ();
					for($l = 0; $l < $n_user_count; $l ++) {
						$s_deptbutton3 .= '<input id="uid_' . $o_user->getUid ( $l ) . '" type="button" class="name" value="' . $o_user->getName ( $l ) . '" hidefocus="hidefocus" onclick="addName(' . $o_user->getUid ( $l ) . ')"/>';
					} //
					$s_deptbutton3 .= '</div>';
					$s_deptbutton .= $s_deptbutton3;
					$s_deptbutton3 = '';
					if (($k + 1) == $n_count3) { //没有下一子部门，直接封口
						//读取用户列表
						$o_user = new View_User_Dept ();
						$o_user->PushWhere ( array ('&&', 'Uid', '<>', $n_uid ) );
						$o_user->PushWhere ( array ('&&', 'DeptId', '=', $o_dept2->getDeptId ( $j ) ) );
						$o_user->PushWhere ( array ('&&', 'State', '=', 1 ) );
						$n_user_count = $o_user->getAllCount ();
						for($l = 0; $l < $n_user_count; $l ++) {
							$s_deptbutton2 .= '<input id="uid_' . $o_user->getUid ( $l ) . '" type="button" class="name" value="' . $o_user->getName ( $l ) . '" hidefocus="hidefocus" onclick="addName(' . $o_user->getUid ( $l ) . ')"/>';
						}
						$s_deptbutton2 .= '</div>';
						$s_deptbutton .= $s_deptbutton2;
						$s_deptbutton2 = '';
					}
				}
				if ($s_dept2 != '') {
					$s_dept .= $s_dept2 . '</div>';
				}
				
				if (($j + 1) == $n_count2) {
					//读取用户列表
					$o_user = new View_User_Dept ();
					$o_user->PushWhere ( array ('&&', 'Uid', '<>', $n_uid ) );
					$o_user->PushWhere ( array ('&&', 'DeptId', '=', $o_dept1->getDeptId ( $i ) ) );
					$o_user->PushWhere ( array ('&&', 'State', '=', 1 ) );
					$n_user_count = $o_user->getAllCount ();
					for($l = 0; $l < $n_user_count; $l ++) {
						$s_deptbutton1 .= '<input id="uid_' . $o_user->getUid ( $l ) . '" type="button" class="name" value="' . $o_user->getName ( $l ) . '" hidefocus="hidefocus" onclick="addName(' . $o_user->getUid ( $l ) . ')"/>';
					}
					$s_deptbutton1 .= '</div>';
					$s_deptbutton .= $s_deptbutton1;
					$s_deptbutton1 = '';
				}
			}
		
		}
		//构造按角色分
		$o_role = new Base_Role (); //构造按部门分
		$n_count = $o_role->getAllCount ();
		for($i = 0; $i < $n_count; $i ++) {
			$s_role .= '<tr><td style="padding-top:8px"><a href="javascript:openRoleName(' . $o_role->getRoleId ( $i ) . ');"><img src="../../images/org/tree_off.png" alt=""/> ' . $o_role->getName ( $i ) . '</a></td></tr>';
			$s_rolebutton .= '<div id="rolediv_' . $o_role->getRoleId ( $i ) . '" style="display: none;width:650px">
								<input type="button" onclick="allAddName(\'rolediv_' . $o_role->getRoleId ( $i ) . '\')" class="all" value="全部添加" hidefocus="hidefocus" />
                                    <input type="button" onclick="allDeleteName(\'rolediv_' . $o_role->getRoleId ( $i ) . '\')" class="delall" value="全部删除" hidefocus="hidefocus" /><br />';
			//读取用户列表
			$o_user = new View_User_Dept ();
			$o_user->PushWhere ( array ('&&', 'Uid', '<>', $n_uid ) );
			$o_user->PushWhere ( array ('&&', 'RoleId', '=', $o_role->getRoleId ( $i ) ) );
			$o_user->PushWhere ( array ('&&', 'State', '=', 1 ) );
			$n_user_count = $o_user->getAllCount ();
			for($l = 0; $l < $n_user_count; $l ++) {
				$s_rolebutton .= '<input id="role' . $o_user->getUid ( $l ) . '" type="button" class="name" value="' . $o_user->getName ( $l ) . '" hidefocus="hidefocus" onclick="addName(' . $o_user->getUid ( $l ) . ')"/>';
			}
			$s_rolebutton .= '</div>';
		}
		$s_html = '
            <tr>
                <td colspan="2" style="padding:0px;height:0px;background-color: White; border-bottom: solid 0px #CCCCCC;border-right: solid 1px #CCCCCC;
                    text-align: center">
                  <div style="display:none;overflow: hidden;padding:3px" id="group">
                   <div style="height:300px;overflow:auto;overflow-x:hidden;">
                    <table border="0" cellpadding="0" cellspacing="0" class="group">
                        <tr>
                            <td class="left">                            
                                <div class="head">
                                    <a href="javascript:;" onclick="openDept(this)" class="header" hidefocus="hidefocus">按部门选择</a></div>
                                <div id="dept" style="display:none;">
                                <table class="dept" border="0" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td>
                                             ' . $s_dept . ' 
                                        </td>
                                    </tr>                                    
                                </table>
                                </div>
                                <div class="head" style="border-bottom: solid 1px #5B99CA;">
                                    <a href="javascript:;" onclick="openRole(this)" class="header" hidefocus="hidefocus">按角色选择</a></div>
                                <div id="role" style="display: none">
                                <table border="0" cellpadding="0" cellspacing="0" class="role">
								' . $s_role . '
                                </table>
                                </div>
                            </td>
                            <td class="right" id="allname">
                                ' . $s_deptbutton . $s_rolebutton . '  
                                                
                            </td>
                        </tr>
                    </table>
                    </div>  
                    <input value="确定" onclick="openGroup()" class="BigButtonA" type="button" style="margin-bottom: 5px" />
                   </div>
                </td>
            </tr>
		';
		return $s_html;
	}
	/*public function getMenu() {
		$o_userModule1 = new View_Menu_Right ();
		$o_userModule1->PushWhere ( array ('&&', 'Uid', '=', $this->O_SingleUser->getUid () ) );
		$o_userModule1->PushWhere ( array ('&&', 'ParentModuleId', '=', 0 ) );
		$o_userModule1->PushOrder ( array ('Module', 'A' ) );
		$n_count1 = $o_userModule1->getAllCount ();
		$n_menuheight = $n_count1 * 31;
		for($i = 0; $i < $n_count1; $i ++) {
			$s_rolename = $o_userModule1->getRoleName ( 0 );
			if ($i == 0) {
				$s_class1 = 'active';
				$s_style = "display:block";
			} else {
				$s_class1 = '';
				$s_style = "display:none";
			}
			$s_module1 .= '
                        <li>
                            <a href="javascript:;" hidefocus="hidefocus" class="' . $s_class1 . '" onclick="menuShowNav2(this,' . $o_userModule1->getModuleId ( $i ) . ')">
                                <img src="' . $o_userModule1->getMiniIconPath ( $i ) . '" align="absMiddle" height="20" width="20"/>
								' . $o_userModule1->getModuleName ( $i ) . '
                             </a>
                         </li>
			';
			//二级菜单
			$o_userModule2 = new View_Menu_Right ();
			$o_userModule2->PushWhere ( array ('&&', 'Uid', '=', $this->O_SingleUser->getUid () ) );
			$o_userModule2->PushWhere ( array ('&&', 'ParentModuleId', '=', $o_userModule1->getModuleId ( $i ) ) );
			$o_userModule2->PushOrder ( array ('Module', 'A' ) );
			$n_count2 = $o_userModule2->getAllCount ();
			$s_module2 .= '<ul id="menuNav2_' . $o_userModule1->getModuleId ( $i ) . '" style="' . $s_style . '">';
			for($j = 0; $j < $n_count2; $j ++) {
				if ($n_menuheight < ($n_count2 * 33)) {
					$n_menuheight = $n_count2 * 33;
				}
				$s_module2 .= '				           
                                <li>
                                    <a href="javascript:;" onclick="menuShowAndHide();addTabForRefresh(' . $o_userModule2->getParentModuleId ( $j ) . ',' . $o_userModule2->getModuleId ( $j ) . ')" hidefocus="hidefocus">
                                        <span>' . $o_userModule2->getModuleName ( $j ) . '</span>
                                    </a>
                                </li>                           
				';
			}
			$s_module2 .= '</ul>';
		}
		if ($this->O_SingleUser->getSex () == '男') {
			$s_img = 'images/avatar/0.gif';
		} else {
			$s_img = 'images/avatar/1.gif';
		}
		$s_html = '
					<div style="top: 112px; display: none;" id="start_menu_panel">
			            <div class="panel-head">
			            </div>
			            <!-- 登录用户信息 -->
			            <div class="panel-user">
			                <div class="avatar">
			                    <img src="' . $s_img . '" align="absMiddle"/>
			                </div>
			                <div class="name">
			                    ' . $this->O_SingleUser->getName () . '</div>
			                <div class="tools">
			                    <a class="logout" href="javascript:;" onclick="menuShowAndHide();Dialog_Confirm(\'确定要注销登录吗？\',function (){location=\'login.php\'});" hidefocus="hidefocus" title="注销">
			                    </a><a class="exit" href="javascript:;" onclick="menuShowAndHide();Dialog_Confirm(\'确定要退出系统吗？\',function (){location=\'index.php\'});" hidefocus="hidefocus" title="退出">
			                    </a>
			                </div>
			            </div>
			            <div class="panel-menu">
			                <!-- 一级菜单 -->
			                <div id="first_panel">
			                    <div style="display: none;" class="scroll-up">
			                    </div>
			                    <ul style="height: ' . $n_menuheight . 'px;" id="first_menu">
			                        ' . $s_module1 . '
			                     </ul>
			                    <div style="display: none;" class="scroll-down">
			                    </div>
			                </div>
			                <!-- 二级级菜单 -->
			                <div style="top: 68px;height:' . $n_menuheight . 'px" id="second_panel">
			                    <div style="height: ' . $n_menuheight . 'px; overflow: hidden; position: relative; padding: 0px; margin: 0px;"
			                        class="second-panel-menu">
			                        <div class="second-panel-head"></div>
			                        <div class="jscroll-c" style="top: 0px; height: ' . ($n_menuheight - 10) . 'px;z-index: 9999; position: relative;">
			                            ' . $s_module2 . '
			                        </div>
			                        <div class="second-panel-foot"></div>
			                    </div>
			                </div>
			            </div>
			            <div class="panel-foot">
			            </div>
			        </div>
		';
		return $s_html;
	}*/
}

?>