<?php
define ( 'RELATIVITY_PATH', '../../' );
define ( 'MODULEID', 100101);
$O_Session = '';
require_once RELATIVITY_PATH . 'include/it_include.inc.php';
require_once RELATIVITY_PATH . 'head.php';
$o_user=new Base_User($_GET[id]);
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
                        <span class="glyphicon glyphicon-repeat" aria-hidden="true"></span> 
                            <?php echo(Text::Key('UserResetpasswordTitle'))?>
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
	                     		<label><?php echo(Text::Key('UserName'))?>：</label>
	                     		<fieldset disabled>
	                     		<input name="Vcl_UserName" value="" maxlength="20" id="Vcl_UserName" type="text" style="width:100%" class="form-control" aria-describedby="basic-addon1" />
	                     		</fieldset>	
	                     	</div>
	                     	<div class="item">
	                     		<label><span class="must">*</span> <?php echo(Text::Key('ResetPassword'))?>：</label>
	                     		<input name="Vcl_Password" maxlength="20" id="Vcl_Password" type="password" style="width:100%" placeholder="<?php echo(Text::Key('PasswordAlt'))?>" class="form-control" aria-describedby="basic-addon1" />
	                     	</div>
	                     	<div class="item">
	                     		<label><span class="must">*</span> <?php echo(Text::Key('ConfirmPassword'))?>：</label>
	                     		<input name="Vcl_Password2" maxlength="20" id="Vcl_Password2" type="password" style="width:100%" placeholder="<?php echo(Text::Key('ConfirmPasswordAlt'))?>" class="form-control" aria-describedby="basic-addon1" />
	                     	</div>
							<div class="item">
							<button id="user_add_btn" type="button" class="btn btn-default cancel" aria-hidden="true" style="float: right;outline: medium none" data-placement="left" onclick="location='<?php echo($_SERVER['HTTP_REFERER'])?>'"><?php echo(Text::Key('Cancel'))?></button>
							<button id="user_add_btn" type="button" class="btn btn-success" aria-hidden="true" style="float: right;outline: medium none" data-placement="left" onclick="user_resetpasswd()"><?php echo(Text::Key('Submit'))?></button>
							</div>
                     	</div>
                     </form>
<script src="js/control.fun.js" type="text/javascript"></script>
<script type="text/javascript">
$('#Vcl_UserName').val('<?php echo($o_user->getUserName())?>');
</script>
<?php
require_once RELATIVITY_PATH . 'foot.php';
 ?>