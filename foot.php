</div>
            </div>
            <div class="sss_footer">
                <p id="footer">
                    
                </p>
            </div>
        </div>
    </div>
<div id="loading" class="modal fade in whiteback" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-body loading_body">
      <img id="user_photo" src="<?php echo(RELATIVITY_PATH)?>images/loading.gif" alt="" />
  </div>
</div>
<div class="sss_gotop" data-toggle="tooltip" title="<?php echo(Text::Key('GoTop'))?>" data-placement="left">
	<span class="glyphicon glyphicon-menu-up"></span>
</div>
<iframe id="submit_form_frame" name="submit_form_frame" src="about:blank" style="display:none"></iframe>
<script>
var NavIcon=<?php $o_setup=new Base_User_Info($O_Session->getUid());echo($o_setup->getShowNavIcon())?>
</script>
</body>
</html>