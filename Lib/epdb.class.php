<?php
/**
 * epdb - EnterPrise Database Class
 * Transplanted from Egg Pain Database
 * Copyright (c) 2013 Bokjan Chan
 * For more information, visit bokjan.com
 * @author Bokjan Chan
 * @copyright Copyright (c) 2013, Bokjan Chan
 * @link https://bokjan.com
 * @version 0.2.0
 */
class epdb{
	function __construct($table){
		if (C('DB_USEPREFIX')) {
			$this->table=C('DB_PREFIX').$table;
		} else {
			$this->table=$table;
		}
		$conn_host=C('DB_HOST').':'.C('DB_PORT');
		$this->conn=mysql_connect($conn_host,C('DB_USER'),C('DB_PW'));
		mysql_query('SET NAMES UTF8');
	}

	public function table($table){
		if($this->useprefix){
			$this->table=C('DB_PREFIX').$table;
		}
		else{
			$this->table=$table;
		}
	}

	function __destruct(){
		mysql_close($this->conn);
	}

	public function getPk(){
		$query="SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `table_name`='{$this->table}' AND `COLUMN_KEY`='PRI'";
		$res=$this->execute($query);
		if($res!=NULL){
			$res=mysql_fetch_assoc($res);
			return $res['COLUMN_NAME'];
		}
		else{
			return false;
		}
	}

	public function execute($query){
		mysql_select_db(C('DB_NAME'),$this->conn);
		$res=mysql_query($query);
		return $res;
	}

	public function data($data){
		$this->data=$data;
		$key=array();
		$value=array();
		foreach ($data as $k => $v) {
			$key[]=$k;
			$value[]=$v;
		}
		$this->key=$key;
		$this->value=$value;
		return $this;
	}

	public function where($data){
		$this->data=$data;
		if (is_array($data)) {
			if(!isset($data['_logic'])){
				$data['_logic']='AND';
			}else{
				$data['_logic']='OR';
			}
			$dc=count($data);
			$where='';
			$i=1;
			foreach ($data as $k => $v) {
				$i++;
				if($k!='_logic'){
				$where.='`'.$k.'`=\''.$v.'\'';}
				if($i<$dc){
					$where.=' '.$data['_logic'].' ';
				}
				$this->where=$where;
			}

		} else {
			$this->where=$data;
		}
		return $this;
	}

	public function order($order,$type='ASC'){
		$this->order=$order.'|'.$type;
		return $this;
	}

	public function limit($limit=1){
		$this->limit=$limit;
		return $this;
	}

	public function add(){
		$i=0;
		$j=0;
		$kc=count($this->key);
		$vc=count($this->value);
		$query='INSERT INTO `'.$this->table.'` (';
		foreach ($this->key as $k) {
			$query.='`'.$k.'`';
			$i++;
			if ($i<$kc) {
				$query.=',';
			}
		}
		$query.=') VALUES (';
		foreach($this->value as $v){
			if($v=='NULL'){
				$query.='NULL';
			}else{
				$query.='\''.$v.'\'';
			}
			$j++;
			if ($j<$vc) {
				$query.=',';
			}
		}
		$query.=')';
		$this->query=$query;
		return $this->execute($query);
	}

	public function select(){
		$query='SELECT * FROM '.$this->table;
		if(isset($this->where)){
			$query.=' WHERE '.$this->where;
			unset($this->where);
		}
		if(isset($this->order)){
			$order=explode('|', $this->order);
			$query.=' ORDER BY `'.$order[0].'` '.$order[1];
			unset($this->order);
		}
		if(isset($this->limit)){
			$query.=' LIMIT '.$this->limit;
			unset($this->limit);
		}
		$this->query=$query;
		$res=$this->execute($query);
		if(mysql_num_rows($res)>0){
			$result=array();
			while($row=mysql_fetch_assoc($res)){
				$result[]=$row;
			}
			return $result;	
		}
		else{
			return NULL;
		}

	}

	public function find(){
		$query='SELECT * FROM '.$this->table;
		if(isset($this->where)){
			$query.=' WHERE '.$this->where;
			unset($this->where);
		}
		if(isset($this->order)){
			$order=explode('|', $this->order);
			$query.=' ORDER BY `'.$order[0].'` '.$order[1];
			unset($this->order);
		}
			$query.=' LIMIT 1';
		$this->query=$query;
		$res=$this->execute($query);
		if (mysql_num_rows($res)>0) {
			$result=mysql_fetch_object($res);
		} else {
			$result=NULL;
		}
		$this->res=$result;
		return $result;
	}

	public function getField($columns){
		if($columns==''){
			return NULL;
		}
		$query='SELECT '.$columns.' FROM '.$this->table;
		if(isset($this->where)){
			$query.=' WHERE '.$this->where;
			unset($this->where);
		}
		if(isset($this->order)){
			$order=explode('|', $this->order);
			$query.=' ORDER BY `'.$order[0].'` '.$order[1];
			unset($this->order);
		}
		if(isset($this->limit)){
			$query.=' LIMIT '.$this->limit;
			unset($this->limit);
		}
		$this->query=$query;
		$res=$this->execute($query);
		if(mysql_num_rows($res)>0){
			$result=array();
			while ($row=mysql_fetch_assoc($res)) {
				$result[]=$row;
			}
		} else{
			$result=NULL;
		}
		$this->res=$result;
		return $result;
	}

	public function save(){
		$pk=$this->getPk();
		$this->pk=$pk;
		$data=$this->data;
		if ($pk==false) {
			$i=0;
			$dc=count($data);
			$query='UPDATE '.$this->table.' SET ';
			foreach ($data as $k => $v) {
				$i++;
				$query.='`'.$k.'`=\''.$v.'\'';
				if($i<$dc){
					$query.=',';
				}
			}
			if(!isset($this->where)){
				return false;
			}
			else{
				$query.=' WHERE '.$this->where;
				unset($this->where);
			}
		} else {
			$i=0;
			$dc=count($data);
			$query='UPDATE '.$this->table.' SET ';
			foreach ($data as $k => $v) {
				$i++;
				$query.='`'.$k.'`=\''.$v.'\'';
				if($i<$dc){
					$query.=',';
				}
			}
			if(!isset($data[$pk])){
				if(!isset($this->where)){
					return false;
				}
				else{
					$query.=' WHERE '.$this->where;
					unset($this->where);
				}
			}
			else{
				$query.=' WHERE `'.$pk.'`=\''.$data[$pk].'\'';
			}
		}
		$this->query=$query;
		$res=$this->execute($query);
		return $res;
	}

	public function setInc($field,$step=1,$math='+'){
		$query='UPDATE '.$this->table.' SET '.$field.'='.$field;
		$query.=$math.$step;
		if(isset($this->where)){
			$query.=' WHERE '.$this->where;
			unset($this->where);
		}
		if(isset($this->limit)){
			$query.=' LIMIT '.$this->limit;
			unset($this->limit);
		}
		$this->query=$query;
		$res=$this->execute($query);
		return $res;
	}

	public function setDec($field,$step=1){
		return $this->setInc($field,$step,'-');
	}

	public function delete(){
		if(!isset($this->where)){
			return false;
		}
		$query='DELETE FROM '.$this->table.' WHERE ';
		unset($this->where);
		$query.=$this->where;
		if(isset($this->limit)){
			$query.=' LIMIT '.$this->limit;
			unset($this->limit);
		}
		$this->query=$query;
		$res=$this->execute($query);
		return $res;
	}
}
?>