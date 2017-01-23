<?php
define ( 'RELATIVITY_PATH', '../../' );
define ( 'MODULEID', 100101);
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
                            $s_funname='UserAdd'; 
                            if($_GET[id]>0)
                            {
                            	$o_user=new Base_User($_GET['id']);
                            	$o_user_info=new Base_User_Info($_GET['id']);
                            	$o_user_custom=new Base_User_Info_Custom($_GET['id']);
                            	$o_user_role=new Base_User_Role($_GET['id']);
                            	$s_funname='UserModify'; 
                            	echo(Text::Key('UserModifyTitle'));
								if($o_user->getUserName()==null || $o_user->getUserName()=='')
								{
									echo("<script>location='user_index.php'</script>");
									exit(0);
								}
                            }else{
                            	echo(Text::Key('UserAddTitle'));
                            }
                            ?>
                            </div>
                            </div>
                    </div>
                    <form action="include/bn_submit.switch.php" id="submit_form" method="post" target="submit_form_frame">
						<input type="hidden" name="Vcl_Url" value="<?php echo(str_replace ( substr( $_SERVER['PHP_SELF'] , strrpos($_SERVER['PHP_SELF'] , '/')+1 ), '', $_SERVER['PHP_SELF']))?>"/>
						<input type="hidden" name="Vcl_BackUrl" value="<?php echo($_SERVER['HTTP_REFERER'])?>"/>
						<input type="hidden" name="Vcl_FunName" value="<?php echo($s_funname)?>"/>
						<input type="hidden" name="Vcl_Id" value="<?php echo($_GET['id'])?>"/>
                    	<div class="sss_form">
	                     	<div class="item">
	                     		<?php 
	                     		if($_GET[id]>0)
	                     		{
	                     			?>
	                     		<label><?php echo(Text::Key('UserName'))?>：</label>
	                     		<fieldset disabled>
	                     		<input name="Vcl_UserName" value="" maxlength="20" id="Vcl_UserName" type="text" style="width:100%" class="form-control" aria-describedby="basic-addon1" readonly="readonly"/>
	                     		</fieldset>	
	                     			<?php
	                     		}else{
	                     			?>
	                     		<label><span class="must">*</span> <?php echo(Text::Key('UserName'))?>：</label>
	                     		<input name="Vcl_UserName" maxlength="20" id="Vcl_UserName" type="text" style="width:100%" placeholder="<?php echo(Text::Key('UserNameAlt'))?>" class="form-control" aria-describedby="basic-addon1" />
	                     			<?php
	                     		}
	                     		?>
	                     	</div>
	                     	<?php 
	                     	if(!($_GET[id]>0))
	                     	{
	                     	?>
	                     	<div class="item">
	                     		<label><span class="must">*</span> <?php echo(Text::Key('Password'))?>：</label>
	                     		<input name="Vcl_Password" maxlength="20" id="Vcl_Password" type="password" style="width:100%" placeholder="<?php echo(Text::Key('PasswordAlt'))?>" class="form-control" aria-describedby="basic-addon1" />
	                     	</div>
	                     	<div class="item">
	                     		<label><span class="must">*</span> <?php echo(Text::Key('ConfirmPassword'))?>：</label>
	                     		<input name="Vcl_Password2" maxlength="20" id="Vcl_Password2" type="password" style="width:100%" placeholder="<?php echo(Text::Key('ConfirmPasswordAlt'))?>" class="form-control" aria-describedby="basic-addon1" />
	                     	</div>
	                     	<?php }?>
	                     	<div class="item">
	                     		<label><span class="must">*</span> <?php echo(Text::Key('Name'))?>：</label>
	                     		<input name="Vcl_Name" maxlength="20" id="Vcl_Name" type="text" style="width:100%" placeholder="<?php echo(Text::Key('NameAlt'))?>" class="form-control" aria-describedby="basic-addon1" />
	                     	</div>
	                     	<div class="item">
	                     		<label><?php echo(Text::Key('Email'))?>：</label>
	                     		<input name="Vcl_Email" id="Vcl_Email" maxlength="50" type="text" style="width:100%" class="form-control" aria-describedby="basic-addon1" />
	                     	</div>
	                     	<div class="item">
	                     		<label><?php echo(Text::Key('Phone'))?>：</label>
	                     		<input name="Vcl_Phone" id="Vcl_Phone"  maxlength="15" type="text" style="width:100%" class="form-control" aria-describedby="basic-addon1" />
	                     	</div>
	                     	<div class="item">
	                     		<label><?php echo(Text::Key('DiskSpace'))?>：</label><br/>
	                     		<select name="Vcl_DiskSpace" id="Vcl_DiskSpace" class="selectpicker" data-style="btn-default">
	                     			<option value="1">1 G</option>
        							<option value="2">2 G</option>
        							<option value="5">5 G</option>
        							<option value="10">10 G</option>
        							<option value="20">20 G</option>
        							<option value="30">30 G</option>
        							<option value="50">50 G</option>
        							<option value="100">100 G</option>
   								</select>
	                     	</div>
	                     	<div class="item">
	                     		<label><span class="must">*</span> <?php echo(Text::Key('Department'))?>：</label><br/>
	                     		<select name="Vcl_DeptId" id="Vcl_DeptId" class="selectpicker" data-style="btn-default">
	                     			<option value=""> </option>
        							<?php 
									//读取角色
									$o_role=new Base_Dept();
									$n_count = $o_role->getAllCount ();
									$s_select='';
									for($i = 0; $i < $n_count; $i ++) {
										$s_select.='<option value="' . $o_role->getDeptId($i) . '">' . $o_role->getName($i ). '</option>';
									}
									echo($s_select);
									?>
   								</select>
	                     	</div>
	                     	<div class="item">
	                     		<label><span class="must">*</span> <?php echo(Text::Key('PrimaryRole'))?>：</label><br/>
	                     		<select name="Vcl_Role0" id="Vcl_Role0" class="selectpicker" data-style="btn-default">
	                     			<option value="0"> </option>
        							<?php 
									//读取角色
									$o_role=new Base_Role();
									$n_count = $o_role->getAllCount ();
									$s_select='';
									for($i = 0; $i < $n_count; $i ++) {
										$s_select.='<option value="' . $o_role->getRoleId($i) . '">' . $o_role->getName($i ). '</option>';
									}
									echo($s_select);
									?>
   								</select>
	                     	</div>
	                     	<?php 
	                     	for($i=1;$i<6;$i++)
	                     	{
	                     		$s_item=Text::Key('AssistantRole').$i;
	                     		echo('
	                     		<div class="item">
	                     		<label>'.$s_item.'：</label><br/>
		                     		<select name="Vcl_Role'.$i.'" id="Vcl_Role'.$i.'" class="selectpicker" data-style="btn-default">
		                     			<option value="0"> </option>
	        							'.$s_select.'
	   								</select>
	                     		</div>
	                     		');
	                     	}
	                     	?>
							<div class="item">
							<button id="user_add_btn" type="button" class="btn btn-default cancel" aria-hidden="true" style="float: right;outline: medium none" data-placement="left" onclick="location='<?php echo($_SERVER['HTTP_REFERER'])?>'"><?php echo(Text::Key('Cancel'))?></button>
							<button id="user_add_btn" type="button" class="btn btn-success" aria-hidden="true" style="float: right;outline: medium none" data-placement="left" onclick="user_modify()"><?php echo(Text::Key('Submit'))?></button>
							</div>
                     	</div>
                     </form>
<script src="js/control.fun.js" type="text/javascript"></script>
<script type="text/javascript">
<?php 
if($_GET['id']>0)
{
?>
$('#Vcl_UserName').val('<?php echo($o_user->getUserName())?>');
$('#Vcl_Name').val('<?php echo($o_user_info->getName())?>');
$('#Vcl_DiskSpace').val('<?php echo($o_user_info->getDiskSpace()/1073741824)?>');
$('#Vcl_Email').val('<?php echo($o_user_custom->getEmail())?>');
$('#Vcl_Phone').val('<?php echo($o_user_custom->getPhone())?>');
$('#Vcl_DeptId').val('<?php echo($o_user_role->getDeptId())?>');
$('#Vcl_Role0').val('<?php echo($o_user_role->getRoleId())?>');
$('#Vcl_Role1').val('<?php echo($o_user_role->getSecRoleId1())?>');
$('#Vcl_Role2').val('<?php echo($o_user_role->getSecRoleId2())?>');
$('#Vcl_Role3').val('<?php echo($o_user_role->getSecRoleId3())?>');
$('#Vcl_Role4').val('<?php echo($o_user_role->getSecRoleId4())?>');
$('#Vcl_Role5').val('<?php echo($o_user_role->getSecRoleId5())?>');
<?php 
}
?>
</script>
<?php
require_once RELATIVITY_PATH . 'foot.php';
 ?>