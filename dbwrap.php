<?php

class DBWrap {
	private $dbHost;
	private $dbName;
	private $dbUser;
	private $dbPass;	
	private $gDBLink;
	private $query_result;
	private $last_ins_id;
	
	function DBWrap($host='127.0.0.1',$name='lead_need',$user='root',$pass='cnjkbwz07'){
		$this->dbHost = $host;
		$this->dbName = $name;
		$this->dbUser = $user;
		$this->dbPass = $pass;	
		$this->gDBLink = $this->Init_DB($this->dbHost,$this->dbName,$this->dbUser,$this->dbPass);
	}
			
	function Init_DB($host,$name,$user,$pass) {
		$connection = mysql_pconnect($host,$user,$pass) or die ("Couldn't connect to database on host " . $host);
		mysql_select_db ($name, $connection) or die ("Couldn't select database " . $name);
		return $connection;
	}

// ***************************************************************
// Helper Database Query universal routines (atomic)

	function DoDBQuery($query)
	{
		$this->query_result = mysql_query ($query, $this->gDBLink);		
		return (mysql_fetch_object ($this->query_result));
	}

	function DoDBQueryEx($query)
	{
		$this->query_result = mysql_query ($query, $this->gDBLink);
		return $this->query_result;
	}

	function GetDBQueryRow($index)
	{
		mysql_data_seek ($this->query_result, $index);
		return (mysql_fetch_object ($this->query_result));
	}
	
	function GetDBQueryRowEx($index)
	{
		mysql_data_seek ($this->query_result, $index);
		return (mysql_fetch_array ($this->query_result));		
	}
	
	function GetDBQueryRowCount()
	{
		return (mysql_num_rows($this->query_result)); 
	}
	
	function GetLastInsId(){
		return mysql_insert_id($this->gDBLink);
	}
}

?>