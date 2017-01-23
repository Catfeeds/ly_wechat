<?php
define ( 'RELATIVITY_PATH', '../../' );
define ( 'MODULEID', 100301);
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
                            echo(Text::Key('UserInfoTitle'));
                            $o_user=new Base_User($O_Session->getUid());
                            $o_user_info=new Base_User_Info($O_Session->getUid());
                            $o_user_custom=new Base_User_Info_Custom($O_Session->getUid());
                            $o_user_role=new Base_User_Role($O_Session->getUid());
                            ?>
                            </div>
                            </div>
                    </div>
                    <form action="include/bn_submit.switch.php" id="submit_form" method="post" target="submit_form_frame">
						<input type="hidden" name="Vcl_Url" value="<?php echo(str_replace ( substr( $_SERVER['PHP_SELF'] , strrpos($_SERVER['PHP_SELF'] , '/')+1 ), '', $_SERVER['PHP_SELF']))?>"/>
						<input type="hidden" name="Vcl_BackUrl" value="<?php echo($_SERVER['HTTP_REFERER'])?>"/>
						<input type="hidden" name="Vcl_FunName" value="InfoModify"/>
                    	<div class="sss_form editor">
	                     	<div class="item">
	                     		<label><?php echo(Text::Key('UserName'))?>：</label>
	                     		<fieldset disabled>
	                     		<input name="Vcl_UserName" value="" maxlength="20" id="Vcl_UserName" type="text" style="width:100%" class="form-control" aria-describedby="basic-addon1" readonly="readonly"/>
	                     		</fieldset>	
	                     	</div>
	                     	<div class="item">
	                     		<label><span class="must">*</span> <?php echo(Text::Key('Name'))?>：</label>
	                     		<script id="editor" type="text/plain"></script>
	                     	</div>
							<div class="item">
							<button id="user_add_btn" type="button" class="btn btn-success" aria-hidden="true" style="float: right;outline: medium none" data-placement="left" onclick="info_modify()"><?php echo(Text::Key('Save'))?></button>
							</div>
                     	</div>
                     	 
                     </form>
                     
<script src="js/control.fun.js" type="text/javascript"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo(RELATIVITY_PATH)?>sub/editor/editor_config.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo(RELATIVITY_PATH)?>sub/editor/editor_api.js"></script>
<script type="text/javascript">
var editor = new UE.ui.Editor();
editor.render("editor");
</script>
<?php
require_once RELATIVITY_PATH . 'foot.php';
 ?>