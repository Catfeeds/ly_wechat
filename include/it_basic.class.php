<?php
require_once RELATIVITY_PATH . 'include/bn_basic.class.php';
class It_Basic extends Bn_Basic {
	protected $N_Page;
	protected $N_PageSize;
	protected $S_FileName;
	protected function setN_Page($n_page)
	{
		if ($n_page<=0)
		{
			$this->N_Page=1;
		}else{
			$this->N_Page=$n_page;
		}
	}
	protected function getFilesize($n_filesize) {
		if ($n_filesize >= ( 1024 * 1024)) {
			$n_filesize = $n_filesize / (1024 * 1024);
			$n_filesize = round ( $n_filesize, 2 );
			return $n_filesize . ' G';
		} else if ($n_filesize > (1024)) {
			$n_filesize = $n_filesize / 1024;
			$n_filesize = round ( $n_filesize, 2 );
			return $n_filesize . ' MB';
		} else {
			return $n_filesize . ' KB';
		}
	
	}
	protected function SearchResultAddRed($s_text, $a_key) {
		$s_text2 = '';
		$n_start = stripos ( $s_text, '<' );
		if ($n_start === false) {
			$s_text = $this->CutStr ( $s_text, 200 );
			for($i = 0; $i < count ( $a_key ); $i ++) {
				$s_text = $this->TextHighLight ( $s_text, $a_key [$i] );
			}
			return $s_text;
		}
		$n_start = 0;
		do {
			$n_count = strlen ( $s_text );
			$n_start = stripos ( $s_text, '<' );
			$n_end = stripos ( $s_text, '>' );
			if (! ($n_start === false)) {
				$s_text2 = $s_text2 . substr ( $s_text, 0, $n_start );
				$n_count = $n_count - $n_end;
				$s_text = substr ( $s_text, $n_end + 1, $n_count );
			}
		} while ( ! ($n_start === false) && strlen ( $s_text2 ) < 200 );
		$s_text = $this->CutStr ( $s_text2, 200 );
		for($i = 0; $i < count ( $a_key ); $i ++) {
			$s_text = $this->TextHighLight ( $s_text, $a_key [$i] );
		}
		return $s_text;
		;
	}
	protected function TextHighLight($s_text, $s_key) {
		$s_text = rawurlencode ( $s_text );
		$s_key = rawurlencode ( $s_key );
		if (isset ( $s_key ) && $s_key != '') {
			$a_text = explode ( $s_key, $s_text );
			$n_start = 0;
			$n_end = 0;
			$s_result = $a_text [0];
			for($i = 1; $i < count ( $a_text ); $i ++) {
				$n_start = stripos ( $a_text [$i], '<' );
				$n_end = stripos ( $a_text [$i], '>' );
				if ($n_end === false || ($n_start < $n_end && $n_start >= 0)) {
					$s_result .= '<span style="color:red">' . $s_key . '</span>' . $a_text [$i];
				} else {
					$s_result .= $s_key . $a_text [$i];
				}
			}
		} else {
			$s_result = $s_text;
		}
		return rawurldecode ( $s_result );
	
		//return rawurldecode($s_result);
	}
}
?>