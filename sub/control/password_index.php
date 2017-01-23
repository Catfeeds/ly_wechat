<?php
define ( 'RELATIVITY_PATH', '../../' );
define ( 'MODULEID', 100303);
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
                            echo(Text::Key('UserPasswordTitle'));
                            ?>
                            </div>
                        </div>
                    </div>
                    <form action="include/bn_submit.switch.php" id="submit_form" method="post" target="submit_form_frame">
						<input type="hidden" name="Vcl_Url" value="<?php echo(str_replace ( substr( $_SERVER['PHP_SELF'] , strrpos($_SERVER['PHP_SELF'] , '/')+1 ), '', $_SERVER['PHP_SELF']))?>"/>
						<input type="hidden" name="Vcl_BackUrl" value="<?php echo($_SERVER['HTTP_REFERER'])?>"/>
						<input type="hidden" name="Vcl_FunName" value="PasswordModify"/>
                    	<div class="sss_form">
                    		<div class="item">
	                     		<label><span class="must">*</span> <?php echo(Text::Key('OldPassword'))?>：</label>
	                     		<input name="Vcl_OldPassword" maxlength="20" id="Vcl_OldPassword" type="password" style="width:100%" placeholder="<?php echo(Text::Key('OldPasswordAlt'))?>" class="form-control" aria-describedby="basic-addon1" />
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
							<button id="user_add_btn" type="button" class="btn btn-success" aria-hidden="true" style="float: right;outline: medium none" data-placement="left" onclick="password_modify()"><?php echo(Text::Key('Submit'))?></button>
							</div>
                     	</div>
                     	 
                     </form>
<script src="js/control.fun.js" type="text/javascript"></script>
<script type="text/javascript">

</script>
<?php
require_once RELATIVITY_PATH . 'foot.php';
 ?>