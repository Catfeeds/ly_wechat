<?php
define ( 'RELATIVITY_PATH', '../../' );
define ( 'MODULEID', 100102);
$O_Session = '';
require_once RELATIVITY_PATH . 'include/it_include.inc.php';
require_once RELATIVITY_PATH . 'head.php';

ExportMainTitle(MODULEID,$O_Session->getUid());
//获取子模块菜单

?>
					<div class="panel panel-default sss_sub_table">
                        <div class="panel-heading">
                        <div class="caption">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> 
                            <?php
                            $s_funname='RoleAdd'; 
                            if($_GET['id']>0)
                            {
                            	$o_table=new Base_Role($_GET['id']);
                            	$s_funname='RoleModify'; 
                            	echo(Text::Key('RoleModifyTitle'));
								if($o_table->getName()==null || $o_table->getName()=='')
								{
									echo("<script>location='role_index.php'</script>");
									exit(0);
								}
                            }else{
                            	echo(Text::Key('RoleAddTitle'));
                            }
                            ?>
                            </div>
                            </div>
                    </div>
                    <link rel="stylesheet" type="text/css" href="css/style.css"/>
                    <form action="include/bn_submit.switch.php" id="submit_form" method="post" target="submit_form_frame">
						<input type="hidden" name="Vcl_Url" value="<?php echo(str_replace ( substr( $_SERVER['PHP_SELF'] , strrpos($_SERVER['PHP_SELF'] , '/')+1 ), '', $_SERVER['PHP_SELF']))?>"/>
						<input type="hidden" name="Vcl_BackUrl" value="<?php echo($_SERVER['HTTP_REFERER'])?>"/>
						<input type="hidden" name="Vcl_FunName" value="<?php echo($s_funname)?>"/>
						<input type="hidden" name="Vcl_Id" value="<?php echo($_GET['id'])?>"/>
                    	<input type="hidden" name="Vcl_Check" id="Vcl_Check" value=""/>
                    	<div class="sss_form">
	                     	<div class="item">
	                     		<label>
	                     			<span class="must">*</span> <?php echo(Text::Key('RoleName'))?>：
	                     		</label>
	                     		<input name="Vcl_Name" maxlength="20" id="Vcl_Name" type="text" onchange="role_select(this)" style="width:100%" placeholder="<?php echo(Text::Key('RoleNameAlt'))?>" class="form-control" aria-describedby="basic-addon1" />
	                     	</div>
	                     	<div class="item">
	                     		<label><?php echo(Text::Key('RoleExplain'))?>：</label>
	                     		<input name="Vcl_Explain" id="Vcl_Explain" maxlength="50" type="text" style="width:100%" class="form-control" aria-describedby="basic-addon1" />
	                     	</div>
	                     	<div class="item">
	                     		<label><?php echo(Text::Key('RoleRight'))?>：</label>
	                     		<div style="overflow:hidden">
	                     		<?php 
									$o_module1 = new View_AddRole_Module ();
									$o_module1->PushWhere ( array ('&&', 'ParentModuleId', '=', 0 ) );
									$o_module1->PushOrder ( array ('Module', 'A' ) );
									$n_count1 = $o_module1->getAllCount ();
									$s_html='';
									for($i = 0; $i < $n_count1; $i ++) {
										$s_html .= '<div class="main_role">
														<input type="checkbox" id="module_' . $o_module1->getModuleId ( $i ) . '" value="' . $o_module1->getModuleId ( $i ) . '"/>	                     		
				            							<label class="radio_text"> <span class="glyphicon '.$o_module1->getPath($i).'" aria-hidden="true"></span> ' . $o_module1->getName ( $i ) . '</label>
							                    	';
										$o_module2 = new View_AddRole_Module ();
										$o_module2->PushWhere ( array ('&&', 'ParentModuleId', '=', $o_module1->getModuleId ( $i ) ) );
										$o_module2->PushOrder ( array ('Module', 'A' ) );
										$n_count2 = $o_module2->getAllCount ();
										for($j = 0; $j < $n_count2; $j ++) {
											$s_html .= '<div class="sub_role">
															<input type="checkbox" id="module_' . $o_module2->getModuleId ( $j ) . '"/>	                     		
					            							<label value="' . $o_module2->getModuleId ( $j ) . '" class="radio_text">' . $o_module2->getName ( $j ) . '</label>
													';
											$o_module3 = new View_AddRole_Module ();
											$o_module3->PushWhere ( array ('&&', 'ParentModuleId', '=', $o_module2->getModuleId ( $j ) ) );
											$o_module3->PushOrder ( array ('Module', 'A' ) );
											$n_count3 = $o_module3->getAllCount ();
											for($k = 0; $k < $n_count3; $k ++) {
												$s_html .= '<div class="sub_role">
		                     									<input type="checkbox" id="module_' . $o_module3->getModuleId ( $k ) . '" value="' . $o_module3->getModuleId ( $k ) . '"/>	                     		
	            												<label class="radio_text">' . $o_module3->getName ( $k ) . '</label>
            												</div>';
											}
											$s_html .= '</div>';
										}
										$s_html .= '</div>';
									}
									echo ($s_html);
	                     		?>		
	                     		</div>		
	                     	</div>
							<div class="item">
							<button id="user_add_btn" type="button" class="btn btn-default cancel" aria-hidden="true" style="float: right;outline: medium none" data-placement="left" onclick="location='<?php echo($_SERVER['HTTP_REFERER'])?>'"><?php echo(Text::Key('Cancel'))?></button>
							<button id="user_add_btn" type="button" class="btn btn-success" aria-hidden="true" style="float: right;outline: medium none" data-placement="left" onclick="role_modify()"><?php echo(Text::Key('Submit'))?></button>
							</div>
                     	</div>
                     </form>
<script src="js/control.fun.js" type="text/javascript"></script>
<script type="text/javascript">
<?php 
if($_GET['id']>0)
{
	$o_right = new Base_Right ();
	$o_right->PushWhere ( array ('&&', 'RoleId', '=', $_GET['id'] ) );
	$n_count = $o_right->getAllCount ();
	for($i = 0; $i < $n_count; $i ++) {
		echo('try{$(\'#module_'.$o_right->getModuleId($i).'\').attr(\'checked\',\'true\');}catch(e){}');
	}
?>
$('#Vcl_Name').val('<?php echo($o_table->getName())?>');
$('#Vcl_Explain').val('<?php echo($o_table->getExplain())?>');
<?php 
}
?>
$('#module_100000').attr('checked','true');
$('#module_100000').attr('disabled','true');
</script>
<?php
require_once RELATIVITY_PATH . 'foot.php';
 ?>