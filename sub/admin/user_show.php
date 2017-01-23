<?php
define ( 'RELATIVITY_PATH', '../../' );
define ( 'MODULEID', 100101);
$O_Session = '';
require_once RELATIVITY_PATH . 'include/it_include.inc.php';
require_once RELATIVITY_PATH . 'head.php';
$o_user=new Base_User($_GET['id']);
$o_user_info=new Base_User_Info($_GET['id']);
$o_user_custom=new Base_User_Info_Custom($_GET['id']);
$o_user_role=new Base_User_Role($_GET['id']);
$o_user_photo=new Base_User_Photo($_GET['id']);
if($o_user->getUserName()==null || $o_user->getUserName()=='')
{
	echo("<script>location='user_index.php'</script>");
	exit(0);
}
ExportMainTitle(MODULEID,$O_Session->getUid());
//获取子模块菜单

?>
					<div class="panel panel-default sss_sub_table">
                        <div class="panel-heading">
                        <div class="caption">
                        <span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> 
                            <?php echo(Text::Key('UserInfo'))?>
                            </div>
                        </div>
                    </div>
                    <form action="include/bn_submit.switch.php" id="submit_form" method="post" target="submit_form_frame">
						<input type="hidden" name="Vcl_Url" value="<?php echo(str_replace ( substr( $_SERVER['PHP_SELF'] , strrpos($_SERVER['PHP_SELF'] , '/')+1 ), '', $_SERVER['PHP_SELF']))?>"/>
						<input type="hidden" name="Vcl_BackUrl" value="<?php echo($_SERVER['HTTP_REFERER'])?>"/>
						<input type="hidden" name="Vcl_FunName" value="UserResetPasswd"/>
						<input type="hidden" name="Vcl_Id" value="<?php echo($_GET['id'])?>"/>
                    	<div class="sss_form">
                    		<div class="item">
	                     		<label><?php echo(Text::Key('Photo'))?>：</label><br/>
	                     		<img class="photo" src="<?php echo(RELATIVITY_PATH.$o_user_photo->getPath())?>"/>	
	                     	</div>
	                     	<div class="item">
	                     		<label><?php echo(Text::Key('UserName'))?>：</label>
	                     		<fieldset disabled>
	                     		<input value="" id="Vcl_UserName" type="text" style="width:100%" class="form-control" aria-describedby="basic-addon1" />
	                     		</fieldset>	
	                     	</div>
	                     	<div class="item">
	                     		<label><?php echo(Text::Key('Name'))?>：</label>
	                     		<fieldset disabled>
	                     		<input value="" id="Vcl_Name" type="text" style="width:100%" class="form-control" aria-describedby="basic-addon1" />
	                     		</fieldset>	
	                     	</div>
	                     	<div class="item">
	                     		<label><?php echo(Text::Key('Sex'))?>：</label>
	                     		<fieldset disabled>
	                     		<input value="" id="Vcl_Sex" type="text" style="width:100%" class="form-control" aria-describedby="basic-addon1" />
	                     		</fieldset>	
	                     	</div>
	                     	<div class="item">
	                     		<label><?php echo(Text::Key('Birthday'))?>：</label>
	                     		<fieldset disabled>
	                     		<input value="" id="Vcl_Birthday" type="text" style="width:100%" class="form-control" aria-describedby="basic-addon1" />
	                     		</fieldset>	
	                     	</div>
	                     	<div class="item">
	                     		<label><?php echo(Text::Key('Phone'))?>：</label>
	                     		<fieldset disabled>
	                     		<input value="" id="Vcl_Phone" type="text" style="width:100%" class="form-control" aria-describedby="basic-addon1" />
	                     		</fieldset>	
	                     	</div>
	                     	<div class="item">
	                     		<label><?php echo(Text::Key('Email'))?>：</label>
	                     		<fieldset disabled>
	                     		<input value="" id="Vcl_Email" type="text" style="width:100%" class="form-control" aria-describedby="basic-addon1" />
	                     		</fieldset>	
	                     	</div>
	                     	<div class="item">
	                     		<label><?php echo(Text::Key('DiskSpace'))?>：</label><br/>
	                     		<fieldset disabled>
	                     		<input value="" id="Vcl_DiskSpace" type="text" style="width:100%" class="form-control" aria-describedby="basic-addon1" />
   								</fieldset>	
	                     	</div>
							<div class="item">
							<button id="user_add_btn" type="button" class="btn btn-default cancel" aria-hidden="true" style="float: right;outline: medium none" data-placement="left" onclick="location='<?php echo($_SERVER['HTTP_REFERER'])?>'"><?php echo(Text::Key('Back'))?></button>
							</div>
                     	</div>
                     </form>
<script src="js/control.fun.js" type="text/javascript"></script>
<script type="text/javascript">
$('#Vcl_UserName').val('<?php echo($o_user->getUserName())?>');
$('#Vcl_Name').val('<?php echo($o_user_info->getName())?>');
$('#Vcl_Sex').val('<?php echo($o_user_info->getSex())?>');
$('#Vcl_Birthday').val('<?php if($o_user_custom->getBirthday()!='0000-00-00')echo($o_user_custom->getBirthday())?>');
$('#Vcl_Email').val('<?php echo($o_user_info->getEmail())?>');
$('#Vcl_Phone').val('<?php echo($o_user_info->getPhone())?>');
$('#Vcl_DiskSpace').val('<?php echo(($o_user_info->getDiskSpace()/1073741824).' G')?>');
</script>
<?php
require_once RELATIVITY_PATH . 'foot.php';
 ?>