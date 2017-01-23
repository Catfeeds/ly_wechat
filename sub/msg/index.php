<?php
define ( 'RELATIVITY_PATH', '../../' );
define ( 'MODULEID', 0);
$O_Session = '';
require_once RELATIVITY_PATH . 'include/it_include.inc.php';
require_once RELATIVITY_PATH . 'head.php';

//获取子模块菜单
?>
					<div class="sss_main_sub_top">
                        <div class="title">
                            <span class="glyphicon glyphicon-envelope"></span>&nbsp;&nbsp;系统消息
                            
                            </div>
                            
                        <div class="text">
                            <?php echo(Text::Key('SystemMessageExplain'))?>
                        </div>
                    </div>
                    <div class="panel panel-default sss_sub_table">
                        	<div class="panel-heading">
                        	<div class="caption">
                            <?php echo(Text::Key('SystemMessageList'))?>
                            </div>
                            <button id="user_add_btn" type="button" title="<?php echo(Text::Key('ClearAllRecord'))?>" class="btn btn-danger" aria-hidden="true" style="float: right;
                                outline: medium none" data-placement="left" data-toggle="tooltip" onclick="sys_msg_delete_all()">
                                <span  class="glyphicon glyphicon-trash"></span>&nbsp;<?php echo(Text::Key('Clear'))?></button>
                            </div>
	                       <div id="msg_list">
	
	                       </div>
                   </div>
                    <link rel="stylesheet" type="text/css" href="css/style.css"/>
					<script src="js/control.fun.js" type="text/javascript"></script>
<script>
</script>
<?php
require_once RELATIVITY_PATH . 'foot.php';
 ?>