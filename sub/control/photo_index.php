<?php
define ( 'RELATIVITY_PATH', '../../' );
define ( 'MODULEID', 100302);
$O_Session = '';
require_once RELATIVITY_PATH . 'include/it_include.inc.php';
require_once RELATIVITY_PATH . 'head.php';

ExportMainTitle(MODULEID,$O_Session->getUid());
//获取子模块菜单

?>
<link type="text/css" rel="stylesheet" href="css/style.css" />
					<div class="panel panel-default sss_sub_table">
                        <div class="panel-heading">
                        <div class="caption">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> 
                            <?php 
                            echo(Text::Key('UserPhotoTitle'));
                            $o_user=new Base_User_Photo($O_Session->getUid());
                            ?>
                            </div>
                        </div>
                    </div>
                    <form action="include/bn_submit.switch.php" id="submit_form" method="post" target="submit_form_frame" enctype="multipart/form-data">
						<input type="hidden" name="Vcl_Url" value="<?php echo(str_replace ( substr( $_SERVER['PHP_SELF'] , strrpos($_SERVER['PHP_SELF'] , '/')+1 ), '', $_SERVER['PHP_SELF']))?>"/>
						<input type="hidden" name="Vcl_BackUrl" value="<?php echo($_SERVER['HTTP_REFERER'])?>"/>
						<input type="hidden" name="Vcl_FunName" value="PhotoModify"/>
                    	<div class="sss_form">
                    		<div class="item">
	                     		<label><?php echo(Text::Key('Photo'))?>：</label><br/>
	                     		<img class="photo" src="<?php echo(RELATIVITY_PATH.$o_user->getPath())?>"/>	
	                     	</div>
	                     	<div class="item">
	                     		<label><?php echo(Text::Key('UploadNewPhoto'))?>：</label><br/>
	                     		<div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
	                     		<fieldset disabled>
                    					<input class="form-control" placeholder="<?php echo(Text::Key('UploadPhotoSize'))?>" size="16" type="text" id="Vcl_Upload" readonly="readonly"/>
                    					</fieldset>
										<span class="input-group-addon" style="cursor:pointer;" onclick="$('#Vcl_File').click()"><span class="glyphicon glyphicon-folder-open"></span></span>
                					</div>
                					<input id="Vcl_File" name="Vcl_File" type="file" accept=".png,.jpg" style="display:none" onchange="$('#Vcl_Upload').val($('#Vcl_File').val())"/>
	                     	</div>
							<div class="item">
							<button id="user_add_btn" type="button" class="btn btn-success" aria-hidden="true" style="float: right;outline: medium none" data-placement="left" onclick="loading_show();$('#submit_form').submit()"><?php echo(Text::Key('Save'))?></button>
							</div>
                     	</div>
                     	 
                     </form>
<script src="js/control.fun.js" type="text/javascript"></script>
<script type="text/javascript">

</script>
<?php
require_once RELATIVITY_PATH . 'foot.php';
 ?>