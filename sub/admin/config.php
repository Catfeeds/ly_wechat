<?php
define ( 'RELATIVITY_PATH', '../../' );
define ( 'MODULEID', 100104);
$O_Session = '';
require_once RELATIVITY_PATH . 'include/it_include.inc.php';
require_once RELATIVITY_PATH . 'head.php';

ExportMainTitle(MODULEID,$O_Session->getUid());
//获取子模块菜单
$o_sys=new Base_Setup(1);
?>
					<div class="panel panel-default sss_sub_table">
                        <div class="panel-heading">
                        <div class="caption">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> 
                            <?php
                            echo(Text::Key('SystemConfigTitle'));
                            ?>
                            </div>
                    	</div>
                    </div>
                    <form action="include/bn_submit.switch.php" id="submit_form" method="post" target="submit_form_frame" enctype="multipart/form-data">
						<input type="hidden" name="Vcl_Url" value="<?php echo(str_replace ( substr( $_SERVER['PHP_SELF'] , strrpos($_SERVER['PHP_SELF'] , '/')+1 ), '', $_SERVER['PHP_SELF']))?>"/>
						<input type="hidden" name="Vcl_BackUrl" value="<?php echo($_SERVER['HTTP_REFERER'])?>"/>
						<input type="hidden" name="Vcl_FunName" value="ConfigModify"/>
                    	<div class="sss_form">
                    		<div class="item">
	                     		<label><?php echo(Text::Key('CurrentLogo'))?>：</label>
	                     		<div style="background-image:url('<?php echo(RELATIVITY_PATH)?>images/logo_bj.png');width:100%">
	                     		<img style="height:50px;width:250px;border: 0px" src="<?php echo(RELATIVITY_PATH.$o_sys->getLogo())?>" alt="" />
	                     		</div>
	                     	</div>
	                     	<div class="item">
	                     		<label><?php echo(Text::Key('ModifyLogo'))?>：</label><br/>
	                     		<div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
	                     		<fieldset disabled>
                    					<input class="form-control" placeholder="<?php echo(Text::Key('LogoSizeExplain'))?>" size="16" type="text" id="Vcl_Upload" readonly="readonly"/>
                    					</fieldset>
										<span class="input-group-addon" style="cursor:pointer;" onclick="$('#Vcl_File').click()"><span class="glyphicon glyphicon-folder-open"></span></span>
                					</div>
                					<input id="Vcl_File" name="Vcl_File" type="file" accept=".png,.jpg" style="display:none" onchange="$('#Vcl_Upload').val($('#Vcl_File').val())"/>
	                     	</div>
	                     	<div class="item">
	                     		<label><span class="must">*</span> <?php echo(Text::Key('SystemName'))?>：</label>
	                     		<input name="Vcl_SystemName" maxlength="50" id="Vcl_SystemName" type="text" style="width:100%" class="form-control"/>
	                     	</div>
	                     	<div class="item">
	                     		<label><span class="must">*</span> <?php echo(Text::Key('HomeUrl'))?>：</label>
	                     		<div class="input-group">
	                     		<div class="input-group-addon">http://</div>
	                     		<input name="Vcl_HomeUrl" maxlength="255" id="Vcl_HomeUrl" type="text" style="width:100%" class="form-control"/>
	                     		</div>
	                     	</div>		
	                     	<div class="item">
	                     		<label><span class="must">*</span> <?php echo(Text::Key('Footer'))?>：</label>
	                     		<input name="Vcl_Footer" maxlength="255" id="Vcl_Footer" type="text" style="width:100%" class="form-control"/>
	                     	</div>	
	                     	<div class="item">
	                     		<label><?php echo(Text::Key('Version'))?>：</label>
	                     		<fieldset disabled>
	                     		<input maxlength="255" id="Vcl_Version" type="text" style="width:100%" class="form-control" readonly="readonly"/>
	                     		</fieldset>
	                     	</div>                     	
							<div class="item">
							<button id="user_add_btn" type="button" class="btn btn-default cancel" aria-hidden="true" style="float: right;outline: medium none" data-placement="left" onclick="location='<?php echo($_SERVER['HTTP_REFERER'])?>'"><?php echo(Text::Key('Cancel'))?></button>
							<button id="user_add_btn" type="button" class="btn btn-success" aria-hidden="true" style="float: right;outline: medium none" data-placement="left" onclick="config_modify()"><?php echo(Text::Key('Submit'))?></button>
							</div>
                     	</div>
                     </form>
						<script src="js/control.fun.js" type="text/javascript"></script>
						<script type="text/javascript">
						$('#Vcl_SystemName').val('<?php echo($o_sys->getSystemName())?>');
						$('#Vcl_HomeUrl').val('<?php 
													$a_url=parse_url($o_sys->getHomeUrl());
													echo($a_url['host'].$a_url['path'])?>');
						$('#Vcl_Footer').val('<?php echo($o_sys->getFooter())?>');
						$('#Vcl_Version').val('<?php echo($o_sys->getVersion())?>');
						</script>
<?php
require_once RELATIVITY_PATH . 'foot.php';
 ?>