<?php
define ( 'RELATIVITY_PATH', '../../' );
define ( 'MODULEID', 100200);
$O_Session = '';
require_once RELATIVITY_PATH . 'include/it_include.inc.php';
require_once RELATIVITY_PATH . 'head.php';
ExportMainTitle(MODULEID,$O_Session->getUid());
//获取子模块菜单
?>
<link rel="stylesheet" type="text/css" href="css/style.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo(RELATIVITY_PATH)?>js/bootstrap/js/picasa/picasa.css"/>
<ol class="breadcrumb">
</ol>
                    <div class="panel panel-default sss_sub_table">
                        <div class="panel-heading">
                        	<button id="up" data-toggle="tooltip" title="<?php echo(Text::Key('Up'))?>" type="button" class="btn btn-primary" aria-hidden="true" style="outline: medium none;font-size:12px;padding:3px 5px 3px 5px;" onclick="open_up()">
	                                <span class="glyphicon glyphicon-arrow-left" style="font-size:12px">
	                                </span>
	                       	</button>
                        	<button data-toggle="tooltip" title="<?php echo(Text::Key('NewFolder'))?>" type="button" class="btn btn-success" aria-hidden="true" style="outline: medium none;font-size:12px;padding:3px 5px 3px 5px;" onclick="new_folder()">
	                                <span class="glyphicon glyphicon-folder-close" style="font-size:12px">
	                                </span>
	                       	</button>
	                        <button data-toggle="tooltip" title="<?php echo(Text::Key('Upload'))?>" type="button" class="btn btn-success" aria-hidden="true" style="outline: medium none;font-size:12px;padding:3px 5px 3px 5px;" onclick="show_upload()">
	                                <span class="glyphicon glyphicon-cloud-upload" style="font-size:12px">
	                                </span>
	                       	</button>
	                       	<button data-toggle="tooltip" title="<?php echo(Text::Key('Refresh'))?>" type="button" class="btn btn-success" aria-hidden="true" style="outline: medium none;font-size:12px;padding:3px 5px 3px 5px;" onclick="refresh('')">
	                                <span class="glyphicon glyphicon-refresh" style="font-size:12px">
	                                </span>
	                       	</button>
	                       	<button data-toggle="tooltip" title="<?php echo(Text::Key('List'))?>" type="button" class="btn btn-default" aria-hidden="true" style="outline: medium none;font-size:12px;padding:3px 5px 3px 5px;float:right" onclick="change_layout(this,'list')">
	                                <span class="glyphicon glyphicon-th-list" style="font-size:12px">
	                                </span>
	                       	</button>
	                       	<button data-toggle="tooltip" title="<?php echo(Text::Key('Icon'))?>" type="button" class="btn btn-default list_on" aria-hidden="true" style="outline: medium none;font-size:12px;padding:3px 5px 3px 5px;float:right;margin-right:5px;" onclick="change_layout(this,'icon')">
	                                <span class="glyphicon glyphicon-th" style="font-size:12px">
	                                </span>
	                       	</button>
	                       	<div class="diskspace">
		                       	<div class="progress">
	  								<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100">
	  								</div>
								</div>
								<div class="diskspace_text"></div>
							</div>
                        </div>
                    <div class="alert" role="alert"><span class="glyphicon glyphicon-ok-sign"></span>&nbsp;&nbsp;<span class="text"></span></div>
                    <div id="file_list" class="sss_filelist" data-toggle="context" data-target="#all_menu">
						
                    </div>                   
                    </div>
                    <div class="sss_page"><div class="explain"></div></div>
<div id="file_menu">
	<ul id="file_menu_sub" class="dropdown-menu" role="menu">
		<li><a href="javascript:;" onclick="open_click()"><span class="glyphicon glyphicon-folder-open"></span>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo(Text::Key('Open'))?></a></li>
		<li><a href="javascript:;" onclick="share_file()"><span class="glyphicon glyphicon glyphicon-link"></span>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo(Text::Key('Share'))?></a></li>
		<li><a href="javascript:;" onclick="download_file()"><span class="glyphicon glyphicon-download-alt"></span>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo(Text::Key('Download'))?></a></li>
		<li><a  href="javascript:;" onclick="zip_files()"><span class="glyphicon glyphicon-compressed"></span>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo(Text::Key('Zip'))?></a></li>
		<li><a  href="javascript:;" onclick="unzip_files()"><span class="glyphicon glyphicon glyphicon-import"></span>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo(Text::Key('Unzip'))?></a></li>
		<li class="divider"></li>
		<li><a  href="javascript:;" onclick="copy_files('copy')"><span class="glyphicon glyphicon-duplicate"></span>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo(Text::Key('Copy'))?></a></li>
		<li><a  href="javascript:;" onclick="copy_files('cut')"><span class="glyphicon glyphicon-scissors"></span>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo(Text::Key('Cut'))?></a></li>
		<li><a  href="javascript:;" onclick="delete_files_folders()"><span class="glyphicon glyphicon glyphicon-trash"></span>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo(Text::Key('Delete'))?></a></li>
		<li><a  href="javascript:;" onclick="rename_files()"><span class="glyphicon glyphicon-pencil"></span>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo(Text::Key('Rename'))?></a></li>
		<li class="divider"></li>
		<li><a  href="javascript:;" onclick="property_files()"><span class="glyphicon glyphicon-info-sign"></span>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo(Text::Key('Property'))?></a></li>
	</ul>
</div>
<div id="all_menu">
	<ul class="dropdown-menu" role="menu">
		<li><a href="javascript:;" onclick="refresh('')"><span class="glyphicon glyphicon-refresh"></span>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo(Text::Key('Refresh'))?></a></li>
		<li><a href="javascript:;" onclick="show_upload()"><span class="glyphicon  glyphicon-cloud-upload"></span>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo(Text::Key('Upload'))?></a></li>
		<li><a href="javascript:;" onclick="paste_files()"><span class="glyphicon glyphicon-paste"></span>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo(Text::Key('Paste'))?></a></li>
		<li><a href="javascript:;" onclick="new_folder()"><span class="glyphicon glyphicon-folder-close"></span>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo(Text::Key('NewFolder'))?></a></li>
		<li class="divider"></li>
		<li><a  href="javascript:;" onclick="property_folder()"><span class="glyphicon glyphicon-info-sign"></span>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo(Text::Key('Property'))?></a></li>
	</ul>
</div>
<div id="upload_form">
	<button id="pick_files" type="button" class="btn btn-primary" aria-hidden="true" style="outline: medium none;float:left">
		<span class="glyphicon glyphicon-plus"></span>
		<?php echo(Text::Key('SelectFiles'))?>
	</button>
	<button id="upload_start" type="button" class="btn btn-success" aria-hidden="true" style="outline: medium none;float:left;margin-left:10px;">
		<span class="glyphicon glyphicon glyphicon-play">
		</span>
		<?php echo(Text::Key('Start'))?>
	</button>
	<button type="button" class="btn btn-danger" aria-hidden="true" style="outline: medium none;float:right" onclick="upload_build()">
		<?php echo(Text::Key('Clear'))?>
	</button>
	<div style="width:100%;height:10px;overflow:hidden;"></div>
	<div id="upload_list" class="upload_list">
	</div>
</div>	

	
	
					<script src="js/bootstrap-contextmenu.js" type="text/javascript"></script>
					<script src="js/plupload.full.min.js" type="text/javascript"></script>
					<script src="js/control.fun.js" type="text/javascript"></script>
					<script src="<?php echo(RELATIVITY_PATH)?>js/bootstrap/js/picasa/picasa.js" type="text/javascript"></script>
<?php
require_once RELATIVITY_PATH . 'foot.php';
 ?>