<?php
error_reporting(0);
require_once RELATIVITY_PATH . 'include/db_connect.class.php';
abstract class CRUD extends DB_Connect {
	protected $Key;
	protected $S_Value;
	protected $S_TableName;
	protected $A_RelationMap;
	protected $B_IsLoaded;
	protected $B_LoadDataSet;
	protected $B_ForDeletion;
	protected $A_ModifiedRelations;
	protected $N_All_Count;
	protected $A_Item;
	protected $A_Where;
	protected $N_Start_Line;
	protected $N_Count_Line;
	protected $A_Order_By;
	abstract protected function DefineTableName();
	abstract protected function DefineRelationMap();
	abstract protected function DefineKey();
	public function __construct($value = null) {
		$this->S_TableName = $this->DefineTableName ();
		$this->A_RelationMap = $this->DefineRelationMap ();
		$this->Key = $this->DefineKey ();
		$this->B_IsLoaded = false;
		$this->B_LoadDataSet = false;
		if (isset ( $value )) {
			$this->S_Value = $value;
		}
		$this->A_ModifiedRelations = array ();
		$this->A_Order_By = array ();
		$this->A_Where = array ();
		$this->A_Item = array ();
		$this->N_All_Count = 0;
		$this->N_Start_Line = - 1;
		$this->N_Count_Line = - 1;
	}
	public function __destruct() {
		if (isset ( $this->S_Value )) {
			if ($this->B_ForDeletion) {
				$this->Execute ( 'DELETE FROM `' . $this->S_TableName . '` WHERE `' . $this->Key . '`=\'' . $this->S_Value . '\'' );
			}
		}
	}
	public function DeletionWhere() {
      //生成条件语句
       $s_tmep = '';
      for($i = 0; $i < count ( $this->A_Where ); $i ++) {
          if ($this->A_Where [$i] [0] == '&&') {
             if ($i == 0) {
                  $s_tmep .= '`' . str_replace ( '.', '`.`', array_search ( $this->A_Where [$i] [1], $this->A_RelationMap ) ) . '`' . $this->A_Where [$i] [2] . '\'' . str_replace ( '\'', '`', $this->A_Where [$i] [3] ) . '\'';
              } else {
                  $a=$this->A_Where [$i] [2];
                  $a=array_search ( $this->A_Where [$i] [1], $this->A_RelationMap ) ;
                  $s_tmep .= ' AND `' . str_replace ( '.', '`.`', array_search ( $this->A_Where [$i] [1], $this->A_RelationMap ) ) . '`' . $this->A_Where [$i] [2] . '\'' . str_replace ( '\'', '`', $this->A_Where [$i] [3] ) . '\'';
              }
           } else if ($this->A_Where [$i] [0] == '||') {
             if ($i == 0) {
                  $s_tmep .= '`' . str_replace ( '.', '`.`', array_search ( $this->A_Where [$i] [1], $this->A_RelationMap ) ) . '`' . $this->A_Where [$i] [2] . '\'' . str_replace ( '\'', '`', $this->A_Where [$i] [3] ) . '\'';
              } else {
                  $s_tmep .= ' OR `' . str_replace ( '.', '`.`', array_search ( $this->A_Where [$i] [1], $this->A_RelationMap ) ) . '`' . $this->A_Where [$i] [2] . '\'' . str_replace ( '\'', '`', $this->A_Where [$i] [3] ) . '\'';
              }
           } else if ($this->A_Where [$i] [0] == '(') {
             if ($i == 0) {
                  $s_tmep .= '(';
              } else {
                  $s_tmep .= ' (';
              }
           } else if ($this->A_Where [$i] [0] == ')') {
              $s_tmep .= ' )';
           } else if ($this->A_Where [$i] [0] == '&') {
              $s_tmep .= ' AND';
           } else if ($this->A_Where [$i] [0] == '|') {
              $s_tmep .= ' OR';
           }     
       }
      if ($s_tmep == '') {
          return;
       }
       $this->Execute ( 'DELETE FROM `' . $this->S_TableName . '` WHERE '. $s_tmep );
    }
	
	public function __call($s_function, $a_arguments) {
		$s_methodtype = substr ( $s_function, 0, 3 );
		$s_methodMember = substr ( $s_function, 3 );
		switch ($s_methodtype) {
			case 'set' :
				return ($this->SetAccessor ( $s_methodMember, $a_arguments [0] ));
				break;
			case 'get' :
				return ($this->GetAccessor ( $s_methodMember, $a_arguments [0] ));
				break;
		}
		return false;
	}
	private function SetAccessor($s_member, $s_newValue) {
		if (property_exists ( $this, $s_member )) {
			//$s_newValue = str_replace ( "\\", "\\\\\\\\", $s_newValue );		
			eval ( '$this->' . $s_member . '=\'' . str_replace ( '\'', '`', $s_newValue ) . '\';' );
			$this->A_ModifiedRelations [$s_member] = '1';
		} else {
			return false;
		}
	}
	private function GetAccessor($s_member, $n_row = null) {
		try {
			if (! $this->B_IsLoaded) {
				$this->Load ();
			}
			if (property_exists ( $this, $s_member )) {
				if (! isset ( $n_row )) {
					eval ( '$s_retVal=$this->' . $s_member . ';' );
					return $s_retVal;
				} else {
					if ($this->N_All_Count > 0 && $n_row < $this->N_All_Count) {
						return mysql_result ( $this->O_Result, $n_row, str_replace ( '`', '', array_search ( $s_member, $this->A_RelationMap ) ) );
					} else {
						return null;
					}
				}
			} else {
				return false;
			}
		} catch ( exception $err ) {
			return false;
		}
	
	}
	protected function Load() {
		if (isset ( $this->S_Value )) {
			$this->Execute ( 'SELECT * FROM `' . $this->S_TableName . '` WHERE `' . str_replace ( '.', '`.`', $this->Key ) . '`=\'' . $this->S_Value . '\'' );
			if ($this->getCount ()) {
				foreach ( $this->A_RelationMap as $key => $s_member ) {
					$value = $this->Item ( 0, $key );
					if (isset ( $value )) {
						if (property_exists ( $this, $s_member )) {
							eval ( '$s_value=$this->' . $s_member . ';' );
							if (! isset ( $s_value )) {
								eval ( '$this->' . $s_member . '=\'' . $value . '\';' );
							}
						}
					}
				}
			} else {
				return false;
			}
			$this->B_IsLoaded = true;
		}
	}
	public function Save() {
		if (count ( $this->A_ModifiedRelations ) == 0) {
			return true;
		}
		if (isset ( $this->S_Value )) {
			$s_query = 'Update `' . $this->S_TableName . '` SET ';
			foreach ( $this->A_RelationMap as $key => $value ) {
				eval ( '$s_retVal=$this->' . str_replace ( '\'', '`', $value ) . ';' );
				if (array_key_exists ( $value, $this->A_ModifiedRelations )) {
					$s_query .= '`' . $key . '`=\'' . $s_retVal . '\', ';
				}
			}
			$s_query = substr ( $s_query, 0, strlen ( $s_query ) - 2 );
			$s_query .= ' WHERE `' . $this->Key . '`=\'' . $this->S_Value . '\'';
			$this->Execute ( $s_query );
			return $this->O_Result;
		} else {
			$s_query = 'INSERT INTO `' . $this->S_TableName . '` (';
			$s_query2 = '';
			foreach ( $this->A_RelationMap as $key => $value ) {
				eval ( '$s_retVal=$this->' . str_replace ( '\'', '`', $value ) . ';' );
				if (array_key_exists ( $value, $this->A_ModifiedRelations )) {
					$s_query .= '`' . $key . '`,';
					$s_query2 .= '\'' . $s_retVal . '\',';
				}
			}
			$s_query = substr ( $s_query, 0, strlen ( $s_query ) - 1 );
			$s_query2 = substr ( $s_query2, 0, strlen ( $s_query2 ) - 1 );
			$s_query = $s_query . ')VALUES(' . $s_query2 . ')';
			$this->Execute ( $s_query );
			if ($this->O_Result) {
				$this->S_Value = $this->S_Id;
				eval ( '$this->' . $this->A_RelationMap [$this->Key] . '=' . $this->S_Id . ';' );
			}
			return $this->O_Result;
		}
	}
	public function Deletion() {
		$this->B_ForDeletion = true;
	}
	public function setItem($a_tiem) {
		$this->B_LoadDataSet = false;
		$this->A_Item = $a_tiem;
	}
	public function PushWhere($a_where) {
		$this->B_LoadDataSet = false;
		array_push ( $this->A_Where, $a_where );
	}
	public function PushOrder($a_order) {
		$this->B_LoadDataSet = false;
		array_push ( $this->A_Order_By, $a_order );
	}
	public function setStartLine($n_number) {
		$this->B_LoadDataSet = false;
		$this->N_Start_Line = $n_number;
	}
	public function setCountLine($n_number) {
		$this->B_LoadDataSet = false;
		$this->N_Count_Line = $n_number;
	}
	public function getAllCount() {
		$this->Select ();
		return $this->N_All_Count;
	}
	public function getCount() {
		if ($this->O_Result) {
			return mysql_num_rows ( $this->O_Result );
		} else {
			return 0;
		}
	}
	private function Select() {
		if ($this->B_LoadDataSet == true) {
			return;
		}
		$s_sql_1 = 'SELECT';
		$s_sql = '';
		$s_sql_count = 'SELECT COUNT(*) AS mycount';
		$s_tmep = '';
		for($i = 0; $i < count ( $this->A_Item ); $i ++) {
			
			$s_tmep .= '`' . str_replace ( '.', '`.`', array_search ( $this->A_Item [$i], $this->A_RelationMap ) ) . '`,';
		}
		if ($s_tmep == '') {
			$s_sql_1 .= ' * ';
		} else {
			$s_sql_1 .= ' ' . substr ( $s_tmep, 0, strlen ( $s_tmep ) - 1 );
		}
		$s_sql .= ' FROM `' . $this->S_TableName . '`';
		//生成条件语句
		$s_tmep = '';
		for($i = 0; $i < count ( $this->A_Where ); $i ++) {
			if ($this->A_Where [$i] [0] == '&&') {
				if ($i == 0) {
					$s_tmep .= '`' . str_replace ( '.', '`.`', array_search ( $this->A_Where [$i] [1], $this->A_RelationMap ) ) . '`' . $this->A_Where [$i] [2] . '\'' . str_replace ( '\'', '`', $this->A_Where [$i] [3] ) . '\'';
				} else {
					$a=$this->A_Where [$i] [2];
					$a=array_search ( $this->A_Where [$i] [1], $this->A_RelationMap ) ;
					$s_tmep .= ' AND `' . str_replace ( '.', '`.`', array_search ( $this->A_Where [$i] [1], $this->A_RelationMap ) ) . '`' . $this->A_Where [$i] [2] . '\'' . str_replace ( '\'', '`', $this->A_Where [$i] [3] ) . '\'';
				}
			} else if ($this->A_Where [$i] [0] == '||') {
				if ($i == 0) {
					$s_tmep .= '`' . str_replace ( '.', '`.`', array_search ( $this->A_Where [$i] [1], $this->A_RelationMap ) ) . '`' . $this->A_Where [$i] [2] . '\'' . str_replace ( '\'', '`', $this->A_Where [$i] [3] ) . '\'';
				} else {
					$s_tmep .= ' OR `' . str_replace ( '.', '`.`', array_search ( $this->A_Where [$i] [1], $this->A_RelationMap ) ) . '`' . $this->A_Where [$i] [2] . '\'' . str_replace ( '\'', '`', $this->A_Where [$i] [3] ) . '\'';
				}
			} else if ($this->A_Where [$i] [0] == '(') {
				if ($i == 0) {
					$s_tmep .= '(';
				} else {
					$s_tmep .= ' (';
				}
			} else if ($this->A_Where [$i] [0] == ')') {
				$s_tmep .= ' )';
			} else if ($this->A_Where [$i] [0] == '&') {
				$s_tmep .= ' AND';
			} else if ($this->A_Where [$i] [0] == '|') {
				$s_tmep .= ' OR';
			}		
		}
		if ($s_tmep != '') {
			$s_sql .= ' WHERE ' . $s_tmep;
		}
		//生成排序
		$s_tmep = '';
		for($i = 0; $i < count ( $this->A_Order_By ); $i ++) {
			if ($this->A_Order_By [$i] [1] == 'A') {
				$s_tmep .= '`' . str_replace ( '.', '`.`', array_search ( $this->A_Order_By [$i] [0], $this->A_RelationMap ) ) . '` ASC,';
			} else if ($this->A_Order_By [$i] [1] == 'D') {
				$s_tmep .= '`' . str_replace ( '.', '`.`', array_search ( $this->A_Order_By [$i] [0], $this->A_RelationMap ) ) . '` DESC,';
			}
		}
		if ($s_tmep != '') {
			$s_sql .= ' ORDER BY ' . substr ( $s_tmep, 0, strlen ( $s_tmep ) - 1 );
		}
		//生成限制
		$s_sql_count .= $s_sql;
		if (($this->N_Start_Line > - 1) && ($this->N_Count_Line > - 1)) {
			$s_sql .= ' LIMIT ' . $this->N_Start_Line . ',' . $this->N_Count_Line;
			$s_sql = $s_sql_1 . $s_sql;
			$this->Execute ( $s_sql_count );
			if ($this->O_Result) {
				$this->N_All_Count = mysql_result ( $this->O_Result, 0, 'mycount' );
				$this->Execute ( $s_sql );
			}
		} else {
			$s_sql = $s_sql_1 . $s_sql;
			$this->Execute ( $s_sql );
			$this->N_All_Count = $this->getCount ();
		}
		$this->B_LoadDataSet = true;
	}
}
?>