<?php
define ( 'RELATIVITY_PATH', '../../' );
define ( 'MODULEID', 100300);
require_once RELATIVITY_PATH . 'include/bn_session.class.php';
require_once RELATIVITY_PATH . 'include/ajax_operate.class.php';
$O_Session = new Session ();
$o_operate = new Operate ();
$s_url=$o_operate->getSubPage($O_Session->getUid (),MODULEID);
?>
<script>
location='<?php echo(RELATIVITY_PATH.$s_url)?>';
</script>