<?php
error_reporting ( 0 );
require_once RELATIVITY_PATH . 'include/db_table.class.php';
require_once RELATIVITY_PATH . 'include/db_view.class.php';
require_once RELATIVITY_PATH . 'include/bn_basic.class.php';
class Operate extends Bn_Basic {
	public function isMobile()
	{
		$http_user_agent = isset ( $_SERVER ['HTTP_USER_AGENT'] ) ? strtolower ( $_SERVER ['HTTP_USER_AGENT'] ) : '';
		$http_accept = isset ( $_SERVER ['HTTP_ACCEPT'] ) ? strtolower ( $_SERVER ['HTTP_ACCEPT'] ) : '';
		$pos_hua = strpos ( $http_user_agent, 'mobi' );
		$pos_a_wap = strpos ( $http_accept, 'wap' );
		$pos_a_wml = strpos ( $http_accept, 'wml' );
		
		if ($pos_hua !== FALSE) {
			return true;
		} elseif ($pos_a_wap !== FALSE) {
			return true;
		} elseif ($pos_a_wml !== FALSE) {
			return true;
		} else {
			return false;
		}
		
	}
	public function __construct() {
		$this->Result = TRUE;
	}
	public function ShowTitleList() {
		//读取文章
		$s_html = '';
		$o_post = new View_Wp_Posts ();
		$o_post->PushWhere ( array ('&&', 'TermId', '=', $_POST ['Ajax_TermId'] ) );
		$o_post->PushWhere ( array ('&&', 'PostStatus', '=', 'publish' ) );
		$o_post->setStartLine ( 0 );
		$o_post->setCountLine ( 10 );
		$o_post->PushOrder ( array ('PostDate', 'D' ) );
		$n_total = $o_post->getAllCount ();
		$n_count = $o_post->getCount ();
		for($i = 0; $i < $n_count; $i ++) {
			$s_html .= '<li><a id="list_'.$o_post->getId($i).'" href="javascript:;" class="subpage-news-item" onclick="content_list_li(this,' . $o_post->getId ( $i ) . ')" style="outline-width:0px;" hidefocus="true"><img src="./images/subpage-next-disable.jpg" />' . $o_post->getTitle ( $i ) . '</a></li>';
		}
		//验证上一页和下一页的按钮是否激活
		$b_pre='false';
		//判断下一篇
		if ($n_total> $n_count) {
			$b_next='true';
		} else {
			$b_next='false';
		}
		echo ('<script type="text/javascript" language="javascript">parent.nav_li_callback(\'' . rawurlencode ( $s_html ) . '\','.$b_pre.','.$b_next.');</script>');
		return;
	}
	public function GetPostContent() {
		//读取文章
		$b_pre='';
		$b_next='';
		$n_postid=$_POST ['Ajax_PostId'];
		$s_html = '';
		$o_post = new Wp_Posts ( $_POST ['Ajax_PostId'] );
		$s_title = $o_post->getTitle ();
		$s_date = $o_post->getPostDate ();
		$s_content = $o_post->getContent ();
		
			//验证上一页和下一页的按钮是否激活
		$o_post=new View_Wp_Posts($_POST ['Ajax_PostId'] );
		$o_temp = new View_Wp_Posts ();
		$o_temp->PushWhere ( array ('&&', 'TermId', '=', $o_post->getTermId () ) );
		$o_temp->PushWhere ( array ('&&', 'PostDate', '>', $o_post->getPostDate () ) );
		$o_temp->PushWhere ( array ('&&', 'PostStatus', '=', 'publish' ) );
		if ($o_temp->getAllCount () > 0) {
			//说明有上一篇
			$b_pre='true';
		} else {
			$b_pre='false';
		}
		//判断下一篇
		$o_temp = new View_Wp_Posts ();
		$o_temp->PushWhere ( array ('&&', 'TermId', '=', $o_post->getTermId () ) );
		$o_temp->PushWhere ( array ('&&', 'PostDate', '<', $o_post->getPostDate () ) );
		$o_temp->PushWhere ( array ('&&', 'PostStatus', '=', 'publish' ) );
		if ($o_temp->getAllCount () > 0) {
			//说明有上一篇
			$b_next='true';
		} else {
			$b_next='false';
		}
		echo ('<script type="text/javascript" language="javascript">parent.content_list_li_callback('.$n_postid.','.$b_pre.','.$b_next.',\'' . rawurlencode ( $s_title ) . '\',\'' . rawurlencode ( $s_date ) . '\',\'' . rawurlencode ( $s_content ) . '\');</script>');
		return;
	}
	public function GetNewsList() {
		//读取文章
		$o_term = new View_Se_Terms ();
		$o_term->PushWhere ( array ('&&', 'Name', '=', '部门新闻' ) );
		$o_term->PushWhere ( array ('&&', 'Parent', '=', $_POST ['Ajax_TermId']  ) );
		$o_term->getAllCount ();
		$o_list = new View_Wp_Posts ();
		$o_list->PushWhere ( array ('&&', 'TermId', '=', $o_term->getTermId ( 0 ) ) );
		$o_list->PushWhere ( array ('&&', 'PostStatus', '=', 'publish' ) );
		$o_list->PushOrder ( array ('PostDate', 'D' ) );
		$n_count = $o_list->getAllCount ();
		for($i = 0; $i < $n_count; $i ++) {
			$s_html.='<div class="subpage-article-title">
									<h3 class="subpage-news-title"><a href="post.php?id=' . $o_list->getId ( $i ) . '">' . $o_list->getTitle ( $i ) . '</a></h3>
									<p class="readmore subpage-info">' . $o_list->getPostDate ( $i ) . '</p></div>
									';
		}
		echo ('<script type="text/javascript" language="javascript">parent.content_list_li_news_callback(\'' . rawurlencode ( $s_html ) . '\');</script>');
		return;
	}
	public function GetNpiList() {
		//读取文章
		$o_term = new View_Se_Terms ();
		$o_term->PushWhere ( array ('&&', 'Name', '=', 'NPI信息' ) );
		$o_term->PushWhere ( array ('&&', 'Parent', '=', $_POST ['Ajax_TermId']  ) );
		$o_term->getAllCount ();
		$o_list = new View_Wp_Posts ();
		$o_list->PushWhere ( array ('&&', 'TermId', '=', $o_term->getTermId ( 0 ) ) );
		$o_list->PushWhere ( array ('&&', 'PostStatus', '=', 'publish' ) );
		$o_list->PushOrder ( array ('PostDate', 'D' ) );
		$n_count = $o_list->getAllCount ();
		for($i = 0; $i < $n_count; $i ++) {
			if ($this->isMobile ()) {
				$s_html .= '
												<div class="gehcs-news-item">
													<div class="npi-item-pic"><img src="' . $o_list->getImage ( $i ) . '" style="width: 100%;"/></div>
													<a href="post.php?id=' . $o_list->getId ( $i ) . '" target="_blank"><h3 class="news-title">' . $o_list->getTitle ( $i ) . '</h3></a>
													<p class="news-text">' . $o_list->getPostDate ( $i ) . '</p>
												</div>
												';
			} else {
				$s_html .= '<div class="gehcs-news-item" style="float:left;width:360px;margin-right:15px;">
										<div class="npi-item-pic" style="width: 100%;max-width:180px;max-height:100px"><img src="' . $o_list->getImage ( $i ) . '" style="width: 100%;"/></div>
										<a href="post.php?id=' . $o_list->getId ( $i ) . '" target="_blank"><h3 class="news-title">' . $o_list->getTitle ( $i ) . '</h3></a>
										<p class="news-text">' . $o_list->getPostDate ( $i ) . '</p>
									</div>';
			}
		}
		echo ('<script type="text/javascript" language="javascript">parent.content_list_li_news_callback(\'' . rawurlencode ( $s_html ) . '\');</script>');
		return;
	}
	public function GetProList() {
		//读取文章
		$o_term = new View_Se_Terms ();
		$o_term->PushWhere ( array ('&&', 'Name', '=', '产品园地' ) );
		$o_term->PushWhere ( array ('&&', 'Parent', '=', $_POST ['Ajax_TermId']  ) );
		$o_term->getAllCount ();
		$o_list = new View_Wp_Posts ();
		$o_list->PushWhere ( array ('&&', 'TermId', '=', $o_term->getTermId ( 0 ) ) );
		$o_list->PushWhere ( array ('&&', 'PostStatus', '=', 'publish' ) );
		$o_list->PushOrder ( array ('PostDate', 'D' ) );
		$n_count = $o_list->getAllCount ();
		for($i = 0; $i < $n_count; $i ++) {
			if ($this->isMobile ()) {
				$s_html .= '
												<div class="gehcs-news-item">
													<div class="npi-item-pic"><img src="' . $o_list->getImage ( $i ) . '" style="width: 100%;"/></div>
													<a href="post.php?id=' . $o_list->getId ( $i ) . '" target="_blank"><h3 class="news-title">' . $o_list->getTitle ( $i ) . '</h3></a>
													<p class="news-text">' . $o_list->getPostDate ( $i ) . '</p>
												</div>
												';
			} else {
			$s_html.='<div class="gehcs-news-item" style="float:left;width:360px;margin-right:15px;">
										<div class="npi-item-pic"  style="width: 100%;max-width:180px;max-height:100px"><img src="'.$o_list->getImage($i).'" style="width: 100%;"/></div>
										<a href="post.php?id='.$o_list->getId($i).'" target="_blank"><h3 class="news-title">'.$o_list->getTitle($i).'</h3></a>
										<p class="news-text">'.$o_list->getPostDate($i).'</p>
									</div>';
			}
		}
		echo ('<script type="text/javascript" language="javascript">parent.content_list_li_news_callback(\'' . rawurlencode ( $s_html ) . '\');</script>');
		return;
	}
	public function GetPostPre() {
		$b_pre='';
		$b_next='';
		$n_postid='';
		$o_post=new View_Wp_Posts($_POST['Ajax_PostId']);
		//读取文章
		$o_temp = new View_Wp_Posts ();
		$o_temp->PushWhere ( array ('&&', 'TermId', '=', $o_post->getTermId () ) );
		$o_temp->PushWhere ( array ('&&', 'PostDate', '>', $o_post->getPostDate () ) );
		$o_temp->PushWhere ( array ('&&', 'PostStatus', '=', 'publish' ) );
		$o_temp->PushOrder ( array ('PostDate', 'A' ) );
		if ($o_temp->getAllCount () > 0) {
			//说明有上一篇
			$n_postid = $o_temp->getId ( 0 );
			$s_title=rawurlencode($o_temp->getTitle ( 0 ));
			$s_date=rawurlencode($o_temp->getPostDate ( 0 ));
			$s_content=rawurlencode($o_temp->getContent ( 0 ));
		}else{
			echo ('<script type="text/javascript" language="javascript">parent.window.alert(\'对不起，已经没有文章！！\');</script>');
			return;
		}
		
		//验证上一页和下一页的按钮是否激活
		$o_post=new View_Wp_Posts($n_postid);
		$o_temp = new View_Wp_Posts ();
		$o_temp->PushWhere ( array ('&&', 'TermId', '=', $o_post->getTermId () ) );
		$o_temp->PushWhere ( array ('&&', 'PostDate', '>', $o_post->getPostDate () ) );
		$o_temp->PushWhere ( array ('&&', 'PostStatus', '=', 'publish' ) );
		if ($o_temp->getAllCount () > 0) {
			//说明有上一篇
			$b_pre='true';
		} else {
			$b_pre='false';
		}
		//判断下一篇
		$o_temp = new View_Wp_Posts ();
		$o_temp->PushWhere ( array ('&&', 'TermId', '=', $o_post->getTermId () ) );
		$o_temp->PushWhere ( array ('&&', 'PostDate', '<', $o_post->getPostDate () ) );
		$o_temp->PushWhere ( array ('&&', 'PostStatus', '=', 'publish' ) );
		if ($o_temp->getAllCount () > 0) {
			//说明有上一篇
			$b_next='true';
		} else {
			$b_next='false';
		}
		echo ('<script type="text/javascript" language="javascript">parent.get_post_pre_callback('.$n_postid.','.$b_pre.','.$b_next.',\''.$s_title.'\',\''.$s_date.'\',\''.$s_content.'\');</script>');
		return;
	}
	public function GetPostNext() {
		$b_pre='';
		$b_next='';
		$n_postid='';
		$o_post=new View_Wp_Posts($_POST['Ajax_PostId']);
		//读取文章
		$o_temp = new View_Wp_Posts ();
		$o_temp->PushWhere ( array ('&&', 'TermId', '=', $o_post->getTermId () ) );
		$o_temp->PushWhere ( array ('&&', 'PostDate', '<', $o_post->getPostDate () ) );
		$o_temp->PushWhere ( array ('&&', 'PostStatus', '=', 'publish' ) );
		$o_temp->PushOrder ( array ('PostDate', 'D' ) );
		if ($o_temp->getAllCount () > 0) {
			//说明有上一篇
			$n_postid = $o_temp->getId ( 0 );
			$s_title=rawurlencode($o_temp->getTitle ( 0 ));
			$s_date=rawurlencode($o_temp->getPostDate ( 0 ));
			$s_content=rawurlencode($o_temp->getContent ( 0 ));
		}else{
			echo ('<script type="text/javascript" language="javascript">parent.window.alert(\'对不起，已经没有文章！！\');</script>');
			return;
		}
		
		//验证上一页和下一页的按钮是否激活
		$o_post=new View_Wp_Posts($n_postid);
		$o_temp = new View_Wp_Posts ();
		$o_temp->PushWhere ( array ('&&', 'TermId', '=', $o_post->getTermId () ) );
		$o_temp->PushWhere ( array ('&&', 'PostDate', '>', $o_post->getPostDate () ) );
		$o_temp->PushWhere ( array ('&&', 'PostStatus', '=', 'publish' ) );
		if ($o_temp->getAllCount () > 0) {
			//说明有上一篇
			$b_pre='true';
		} else {
			$b_pre='false';
		}
		//判断下一篇
		$o_temp = new View_Wp_Posts ();
		$o_temp->PushWhere ( array ('&&', 'TermId', '=', $o_post->getTermId () ) );
		$o_temp->PushWhere ( array ('&&', 'PostDate', '<', $o_post->getPostDate () ) );
		$o_temp->PushWhere ( array ('&&', 'PostStatus', '=', 'publish' ) );
		if ($o_temp->getAllCount () > 0) {
			//说明有上一篇
			$b_next='true';
		} else {
			$b_next='false';
		}
		echo ('<script type="text/javascript" language="javascript">parent.get_post_pre_callback('.$n_postid.','.$b_pre.','.$b_next.',\''.$s_title.'\',\''.$s_date.'\',\''.$s_content.'\');</script>');
		return;
	}
	public function GetDiscuzTerm() {
		//读取文章
		//获取列表
		$o_discuz = new View_Wp_Discuz_Posts ();
		$o_discuz->PushWhere ( array ('&&', 'TermId', '=', $_POST['Ajax_TermId'] ) );
		$o_discuz->PushWhere ( array ('&&', 'State', '=', 1 ) );
		$o_discuz->setStartLine ( 0 );
		$o_discuz->setCountLine ( 4 );
		$o_discuz->PushOrder ( array ('TopDate', 'D' ) );
		$o_discuz->PushOrder ( array ('Date', 'D' ) );
		$n_count_total = $o_discuz->getAllCount ();
		$n_count = $o_discuz->getCount ();
		//echo ('<script type="text/javascript" language="javascript">parent.window.alert(\''.$_POST['Ajax_TermId'] .'\');</script>');
		//return;
		for($i = 0; $i < $n_count; $i ++) {
			//获取文章内图片
			//统计评论数
			$o_temp = new Wp_Discuz_Comment ();
			$o_temp->PushWhere ( array ('&&', 'PostId', '=', $o_discuz->getPostId ( $i ) ) );
			$o_temp->setStartLine ( 0 );
			$o_temp->setCountLine ( 1 );
			$n_count_comment = $o_temp->getAllCount ();
			$s_html.='
									<div class="gehcs-content-wrap-divbox col-xs-13">
				                        <div class="divbox-left col-xs-13"><img alt="" src="' . $this->getPhotoUrl ( $o_discuz->getContent ( $i ) ) . '" style="max-width:165px"/></div>
				                        <div class="divbox-right col-xs-13">
				                            <div class="divbox-right-title">
				                                <a class="subpage-news-title" href="javascript:;" onclick="get_discuz_post_show('.$o_discuz->getPostId($i).')">' . $o_discuz->getTitle ( $i ) . '</a>
				                            </div>
				                            <div class="divbox-right-content">
				                                <p>' . $this->SearchResultAddRed ( $o_discuz->getContent ( $i ), 220 ) . '</p>
				                            </div>
				                            <div class="divbox-right-bottom">
				                                <p class="divbox-right-bottom-left">' . $o_discuz->getName ( $i ) . ' 发表于' . $o_discuz->getDate ( $i ) . '</p>
				                                <p class="divbox-right-bottom-right">' . $n_count_comment . ' 条评论</p>
				                            </div>
				                        </div>
				                    </div>
								';
		}
		//验证上一页和下一页的按钮是否激活
		$b_pre='false';
		//判断下一篇
		if ($n_count_total> $n_count) {
			$b_next='true';
		} else {
			$b_next='false';
		}
		echo ('<script type="text/javascript" language="javascript">parent.get_discuz_term_callback(\''.$_POST['Ajax_TermId'].'\','.$b_pre.','.$b_next.',\''.rawurlencode($s_html).'\');</script>');
		return;
	}
	public function GetDiscuzListNext() {
		//读取文章
		//获取列表
		$size=4;
		$page=$_POST['Ajax_Page'];
		$o_discuz = new Wp_Discuz_Posts ();
		$o_discuz->PushWhere ( array ('&&', 'TermId', '=', $_POST['Ajax_TermId'] ) );
		$o_discuz->PushWhere ( array ('&&', 'State', '=', 1) );
		$o_discuz->setStartLine ( ($page-1)*$size );
		$o_discuz->setCountLine ( $size );
		$o_discuz->PushOrder ( array ('TopDate', 'D' ) );
		$o_discuz->PushOrder ( array ('Date', 'D' ) );
		$n_total = $o_discuz->getAllCount ();
		$n_count = $o_discuz->getCount ();
		for($i = 0; $i < $n_count; $i ++) {
			$o_temp = new Wp_Discuz_Comment ();
			$o_temp->PushWhere ( array ('&&', 'PostId', '=', $o_discuz->getPostId ( $i ) ) );
			$o_temp->setStartLine ( 0 );
			$o_temp->setCountLine ( 1 );
			$n_count_comment = $o_temp->getAllCount ();
			$s_html.='
									<div class="gehcs-content-wrap-divbox col-xs-13">
				                        <div class="divbox-left col-xs-13"><img alt="" src="' . $this->getPhotoUrl ( $o_discuz->getContent ( $i ) ) . '" style="max-width:165px"/></div>
				                        <div class="divbox-right col-xs-13">
				                            <div class="divbox-right-title">
				                                <a class="subpage-news-title" href="javascript:;" onclick="get_discuz_post_show('.$o_discuz->getPostId($i).')">' . $o_discuz->getTitle ( $i ) . '</a>
				                            </div>
				                            <div class="divbox-right-content">
				                                <p>' . $this->SearchResultAddRed ( $o_discuz->getContent ( $i ), 220 ) . '</p>
				                            </div>
				                            <div class="divbox-right-bottom">
				                                <p class="divbox-right-bottom-left">' . $o_discuz->getName ( $i ) . ' 发表于' . $o_discuz->getDate ( $i ) . '</p>
				                                <p class="divbox-right-bottom-right">' . $n_count_comment . ' 条评论</p>
				                            </div>
				                        </div>
				                    </div>
								';
		}
		//验证上一页和下一页的按钮是否激活
		if ($page>1)
		{
			$b_pre = 'true';
		}else{
			$b_pre = 'false';
		}
		//判断下一篇
		if ($n_total > ($page*$size)) {
			$b_next = 'true';
		} else {
			$b_next = 'false';
		}
		echo ('<script type="text/javascript" language="javascript">parent.discuz_list_callback(\'' . $page . '\',' . $b_pre . ',' . $b_next . ',\'' . rawurlencode ( $s_html ) . '\');</script>');
		return;
	}
	public function GetPostListNext() {
		//读取文章
		//获取列表
		$size=10;
		$page=$_POST['Ajax_Page'];
		$o_post = new View_Wp_Posts ();
		$o_post->PushWhere ( array ('&&', 'TermId', '=', $_POST['Ajax_TermId'] ) );
		$o_post->PushWhere ( array ('&&', 'PostStatus', '=', 'publish' ) );
		$o_post->setStartLine ( ($page-1)*$size );
		$o_post->setCountLine ( $size );
		$o_post->PushOrder ( array ('PostDate', 'D' ) );
		$n_total = $o_post->getAllCount ();
		$n_count = $o_post->getCount ();
		for($i = 0; $i < $n_count; $i ++) {
			$s_html='<li><a id="list_' . $o_post->getId ( $i ) . '" href="javascript:;" class="subpage-news-item" onclick="content_list_li(this,' . $o_post->getId ( $i ) . ')" style="outline-width:0px;" hidefocus="true"><img src="./images/subpage-next-disable.jpg" />' . $o_post->getTitle ( $i ) . '</a></li>';
		}
		//验证上一页和下一页的按钮是否激活
		if ($page>1)
		{
			$b_pre = 'true';
		}else{
			$b_pre = 'false';
		}
		//判断下一篇
		if ($n_total > ($page*$size)) {
			$b_next = 'true';
		} else {
			$b_next = 'false';
		}
		echo ('<script type="text/javascript" language="javascript">parent.post_list_callback(\'' . $page . '\',' . $b_pre . ',' . $b_next . ',\'' . rawurlencode ( $s_html ) . '\');</script>');
		return;
	}
	public function GetDiscuzPostNext() {
		$b_pre = '';
		$b_next = '';
		$n_postid = '';
		$o_post = new View_Wp_Discuz_Posts ( $_POST ['Ajax_PostId'] );
		
		//读取文章
		$o_temp = new View_Wp_Discuz_Posts ();
		$o_temp->PushWhere ( array ('&&', 'TermId', '=', $o_post->getTermId () ) );
		$o_temp->PushWhere ( array ('&&', 'Date', '<', $o_post->getDate () ) );
		$o_temp->PushWhere ( array ('&&', 'State', '=', 1 ) );
		$o_temp->PushOrder ( array ('Date', 'D' ) );
		if ($o_temp->getAllCount () > 0) {
			//说明有上一篇
			$n_postid = $o_temp->getPostId ( 0 );
		} else {
			echo ('<script type="text/javascript" language="javascript">parent.window.alert(\'对不起，' . $_POST ['Ajax_PostId'] . '已经没有文章！！\');</script>');
			return;
		}
		//判断上一篇和下一篇是否为激活
		$o_temp2 = new Wp_Discuz_Posts ();
		$o_temp2->PushWhere ( array ('&&', 'TermId', '=', $o_temp->getTermId (0) ) );
		$o_temp2->PushWhere ( array ('&&', 'Date', '>', $o_temp->getDate (0) ) );
		$o_temp2->PushWhere ( array ('&&', 'State', '=', 1 ) );
		if ($o_temp2->getAllCount () > 0) {
			//说明有上一篇
			$b_pre='true';
		} else {
			$b_pre='false';
		}
		//判断下一篇
		$o_temp2 = new Wp_Discuz_Posts ();
		$o_temp2->PushWhere ( array ('&&', 'TermId', '=', $o_temp->getTermId (0) ) );
		$o_temp2->PushWhere ( array ('&&', 'Date', '<', $o_temp->getDate (0) ) );
		$o_temp2->PushWhere ( array ('&&', 'State', '=', 1 ) );
		if ($o_temp2->getAllCount () > 0) {
			//说明有下一篇
			$b_next='true';
		} else {
			$b_next='false';
		}
		echo ('<script type="text/javascript" language="javascript">parent.get_discuz_post_pre_callback(' . $n_postid . ',' . $b_pre . ',' . $b_next . ',\'' . rawurlencode ( $this->GetPostInfo ( $n_postid ) ) . '\');</script>');
		return;
	}
	public function GetDiscuzPostPre() {
		$b_pre = '';
		$b_next = '';
		$n_postid = '';
		$o_post = new View_Wp_Discuz_Posts ( $_POST ['Ajax_PostId'] );
		
		//读取文章
		$o_temp = new View_Wp_Discuz_Posts ();
		$o_temp->PushWhere ( array ('&&', 'TermId', '=', $o_post->getTermId () ) );
		$o_temp->PushWhere ( array ('&&', 'Date', '>', $o_post->getDate () ) );
		$o_temp->PushWhere ( array ('&&', 'State', '=', 1 ) );
		$o_temp->PushOrder ( array ('Date', 'A' ) );
		if ($o_temp->getAllCount () > 0) {
			//说明有上一篇
			$n_postid = $o_temp->getPostId ( 0 );
		} else {
			echo ('<script type="text/javascript" language="javascript">parent.window.alert(\'对不起，' . $_POST ['Ajax_PostId'] . '已经没有文章！！\');</script>');
			return;
		}
		//判断上一篇和下一篇是否为激活
		$o_temp2 = new Wp_Discuz_Posts ();
		$o_temp2->PushWhere ( array ('&&', 'TermId', '=', $o_temp->getTermId (0) ) );
		$o_temp2->PushWhere ( array ('&&', 'Date', '>', $o_temp->getDate (0) ) );
		$o_temp2->PushWhere ( array ('&&', 'State', '=', 1 ) );
		if ($o_temp2->getAllCount () > 0) {
			//说明有上一篇
			$b_pre='true';
		} else {
			$b_pre='false';
		}
		//判断下一篇
		$o_temp2 = new Wp_Discuz_Posts ();
		$o_temp2->PushWhere ( array ('&&', 'TermId', '=', $o_temp->getTermId (0) ) );
		$o_temp2->PushWhere ( array ('&&', 'Date', '<', $o_temp->getDate (0) ) );
		$o_temp2->PushWhere ( array ('&&', 'State', '=', 1 ) );
		if ($o_temp2->getAllCount () > 0) {
			//说明有下一篇
			$b_next='true';
		} else {
			$b_next='false';
		}
		echo ('<script type="text/javascript" language="javascript">parent.get_discuz_post_pre_callback(' . $n_postid . ',' . $b_pre . ',' . $b_next . ',\'' . rawurlencode ( $this->GetPostInfo ( $n_postid ) ) . '\');</script>');
		return;
	}
	public function GetDiscuzPostShow() {
		$b_pre = '';
		$b_next = '';
		$n_postid = '';
		$o_post = new View_Wp_Discuz_Posts ( $_POST ['Ajax_PostId'] );
		if ($o_post->getState()==0)
		{
			//echo ('<script type="text/javascript" language="javascript">parent.discuz_post_del_callback();</script>');
			//return;
		}
		//判断上一篇和下一篇是否为激活
		$o_temp2 = new Wp_Discuz_Posts ();
		$o_temp2->PushWhere ( array ('&&', 'TermId', '=', $o_post->getTermId () ) );
		$o_temp2->PushWhere ( array ('&&', 'Date', '>', $o_post->getDate () ) );
		$o_temp2->PushWhere ( array ('&&', 'State', '=', 1 ) );
		if ($o_temp2->getAllCount () > 0) {
			//说明有上一篇
			$b_pre='true';
		} else {
			$b_pre='false';
		}
		//判断下一篇
		$o_temp2 = new Wp_Discuz_Posts ();
		$o_temp2->PushWhere ( array ('&&', 'TermId', '=', $o_post->getTermId () ) );
		$o_temp2->PushWhere ( array ('&&', 'Date', '<', $o_post->getDate () ) );
		$o_temp2->PushWhere ( array ('&&', 'State', '=', 1 ) );
		if ($o_temp2->getAllCount () > 0) {
			//说明有下一篇
			$b_next='true';
		} else {
			$b_next='false';
		}
		echo ('<script type="text/javascript" language="javascript">parent.get_discuz_post_show_callback(' . $o_post->getPostId() . ',' . $b_pre . ',' . $b_next . ',\'' . rawurlencode ( $this->GetPostInfo ( $o_post->getPostId()) ) . '\');</script>');
		return;
	}
	public function DiscuzPostAdd() {
		//验证用户是否登陆
		require_once RELATIVITY_PATH . 'include/bn_session.class.php';
		$O_Session = new Session ();
		if ($O_Session->Login () == false)
		{
			//echo ('<script type="text/javascript">parent.location=\'discuz.php\'</script>');
			return;
		}
		$n_uid=$O_Session->getUid();
		$o_post=new Wp_Discuz_Posts();
		$o_post->setTitle($this->AilterTextArea($_POST['Ajax_Title']));
		$o_post->setOwner($n_uid);
		$o_post->setTermId($_POST['Ajax_TermId']);
		$o_post->setDate($this->GetDateNow());
		$o_post->setState(1);
		$o_post->setContent($_POST['Ajax_Content']);
		$o_post->Save();
		echo ('<script type="text/javascript" language="javascript">parent.discuz_post_add_callback();</script>');
		return;
	}
	public function DiscuzPostModify() {
		//验证用户是否登陆
		require_once RELATIVITY_PATH . 'include/bn_session.class.php';
		$O_Session = new Session ();
		if ($O_Session->Login () == false)
		{
			//echo ('<script type="text/javascript">parent.location=\'discuz.php\'</script>');
			return;
		}
		$n_uid=$O_Session->getUid();
		$o_post=new Wp_Discuz_Posts($_POST['Ajax_PostId']);
		if($o_post->getTermId()>0 && $n_uid==$o_post->getOwner())
		{
			$o_post->setTitle($this->AilterTextArea($_POST['Ajax_Title']));
			$o_post->setTermId($_POST['Ajax_TermId']);
			$o_post->setContent($_POST['Ajax_Content']);
			$o_post->Save();
			echo ('<script type="text/javascript" language="javascript">parent.discuz_post_modify_callback();</script>');
			return;
		}
	}
	public function DiscuzCommentAdd() {
		//验证用户是否登陆
		require_once RELATIVITY_PATH . 'include/bn_session.class.php';
		$O_Session = new Session ();
		if ($O_Session->Login () == false)
		{
			//echo ('<script type="text/javascript">parent.location=\'discuz.php\'</script>');
			return;
		}
		$n_uid=$O_Session->getUid();
		$o_post=new Wp_Discuz_Comment();
		$o_post->setContent($this->AilterTextArea($_POST['Ajax_Content']));
		$o_post->setOwner($n_uid);
		$o_post->setPostId($_POST['Ajax_PostId']);
		$o_post->setDate($this->GetDateNow());
		$o_post->Save();
		echo ('<script type="text/javascript" language="javascript">parent.get_discuz_post_show('.$_POST['Ajax_PostId'].');</script>');
		return;
	}
	public function DiscuzCommentDel() {
		//验证用户是否登陆
		require_once RELATIVITY_PATH . 'include/bn_session.class.php';
		$O_Session = new Session ();
		if ($O_Session->Login () == false)
		{
			//echo ('<script type="text/javascript">parent.location=\'discuz.php\'</script>');
			return;
		}
		$n_uid=$O_Session->getUid();
		$o_post=new Wp_Discuz_Comment($_POST['Ajax_CommentId']);
		$n_postid=$o_post->getPostId();
		if ($o_post->getOwner()==$n_uid)
		{
			$o_post->Deletion();
		}
		echo ('<script type="text/javascript" language="javascript">parent.get_discuz_post_show('.$n_postid.');</script>');
		return;
	}
	public function DiscuzPostDel() {
		//验证用户是否登陆
		require_once RELATIVITY_PATH . 'include/bn_session.class.php';
		$O_Session = new Session ();
		if ($O_Session->Login () == false)
		{
			//echo ('<script type="text/javascript">parent.location=\'discuz.php\'</script>');
			return;
		}
		$n_uid=$O_Session->getUid();
		$o_post=new Wp_Discuz_Posts($_POST['Ajax_PostId']);
		$n_postid=$o_post->getPostId();
		if ($o_post->getOwner()==$n_uid)
		{
			$o_post->setState(0);
			$o_post->Save();
		}
		echo ('<script type="text/javascript" language="javascript">parent.discuz_post_del_callback();</script>');
		return;
	}
}

?>