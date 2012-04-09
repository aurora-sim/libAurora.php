<?php
//!	@file libAurora/DataManager/pdo.php
//!	@brief PDO implementation of Aurora::Framework::IGenericData
//!	@author SignpostMarv

namespace libAurora\DataManager{

	use PDOException;
	use libAurora\RuntimeException;

	use PDOStatement;

	use Aurora\Framework\QueryFilter;

//!	PDO implementation of Aurora::Framework::IGenericData
	class PDO extends DataManagerBase{

//!	object an instance of PDO
		private $PDO;

//!	This will later be turned into a protected method with instantation being driven by a public static method modeled on the c# Aurora::Framework::IGenericData::ConnectToDatabase()
/**
*	@param object an instance of PDO
*/
		public function __construct(\PDO $PDO){
			$this->PDO = $PDO;
		}

//!	Prepares a query
		private static function prepareSth(\PDO $PDO, PDOStatement & $sth=null, $query){
			try{
				$sth = $PDO->prepare($query);
			}catch(PDOException $e){
				throw new RuntimeException('Exception occurred when preparing query.', $e->getCode());
			}		
		}

//!	Binds values to queries
		private static function bindValues(PDOStatement $sth, array $ps){
			try{
				foreach($ps as $k=>$v){
					$type = \PDO::PARAM_STR;
					switch(gettype($v)){
						case 'boolean':
							$type = \PDO::PARAM_BOOL;
						break;
						case 'integer':
							$type = \PDO::PARAM_INT;
						break;
						case 'NULL':
							throw new RuntimeException('NULL is not a supported parameter.');
						break;
						default:
							$v = (string)$v;
						break;
					}
					$sth->bindValue($k, $v, $type);
				}
			}catch(PDOException $e){
				throw new RuntimeException('Exception occurred when binding values to query.', $e->getCode());
			}
		}

//!	Returns and executes
		private static function returnExecute(PDOStatement $sth){
			try{
				$exec = $sth->execute();
				if(!$exec){
					print_r($sth->errorInfo());
				}
				return $exec;
			}catch(PDOException $e){
				throw new RuntimeException('Execution of the query threw an exception.', $e->getCode());
			}
		}

//!	Performs a select query
/**
*	@see Aurora::Framework::IGenericData::Query()
*/
		public function Query(array $wantedValue, $table, QueryFilter $queryFilter=null, array $sort=null, $start=null, $count=null){
			parent::Query($wantedValue, $table, $queryFilter, $sort, $start, $null);

			$query = sprintf('SELECT %s FROM %s', implode(', ', $wantedValue), $table);
			$ps = array();
			$retVal = array();

			if(isset($queryFilter) === true && $queryFilter->count() > 0){
				$query .= ' WHERE ' . $queryFilter->ToSQL($ps);
			}

			if(isset($sort) === true && count($sort) > 0){
				$parts = array();
				foreach($sort as $k=>$v){
					$parts[] = sprintf('`%s` %s', $k, $v ? 'ASC' : 'DESC');
				}
				$query .= ' ORDER BY ' . implode(', ', $parts);
			}

			if(isset($start) === true){
				$query .= ' LIMIT ' . (string)$start;
				if(isset($count) === true){
					$query .= ', ' . (string)$count;
				}
			}

			$sth = null;
			static::prepareSth($this->PDO, $sth, $query);
			static::bindValues($sth, $ps);

			try{
				if($sth->execute() === false){
					throw new RuntimeException('Execution of the query failed.', $sth->errorCode());
				}
			}catch(PDOException $e){
				throw new RuntimeException('Execution of the query threw an exception.', $e->getCode());
			}

			$parts = array();
			try{
				$parts = $sth->fetchAll(\PDO::FETCH_NUM);
			}catch(PDOException $e){
				throw new RuntimeException('Failed to fetch query results.');
			}

			foreach($parts as $v){
				$retVal = array_merge($retVal, $v);
			}

			return $retVal;
		}

//!	Performs an insert query
/**
*	@see Aurora::Framework::IGenericData::Insert()
*/
		public function Insert($table, array $values){
			parent::Insert($table, $values);

			reset($values);
			$specifyKeys = is_string(key($values));
			$fields = '';
			if($specifyKeys === true){
				$fields   = '(' . implode(', ', array_keys($values)) . ')';
			}

			$ps = array();
			foreach($values as $k=>$v){
				$ps[':' . QueryFilter::preparedKey($k)] = $v;
			}
			unset($values);

			$query = sprintf('INSERT INTO %s %s VALUES(%s)', $table, $fields, implode(', ', array_keys($ps)));

			$sth = null;
			static::prepareSth($this->PDO, $sth, $query);
			static::bindValues($sth, $ps);
			return static::returnExecute($sth);
		}

//!	Performs an update query
/**
*	@see Aurora::Framework::IGenericData::Update()
*/
		public function Update($table, array $set, QueryFilter $queryFilter=null){
			parent::Update($table, $set, $queryFilter);

			$parts = array();
			$ps = array();
			foreach($set as $k=>$v){
				$key = ':UPDATE_' . QueryFilter::preparedKey($k);
				$ps[$key] = $v;
				$parts[] = sprintf('%s = %s', $k, $key);
			}
			$_ps = array();

			$query = sprintf('UPDATE %s SET %s', $table, implode(', ', $parts));
			if(isset($queryFilter) && $queryFilter->count() > 0){
				$query .= ' WHERE ' . $queryFilter->toSQL($_ps);
				$ps = array_merge($ps, $_ps);
				unset($_ps);
			}

			$sth = null;
			static::prepareSth($this->PDO, $sth, $query);
			static::bindValues($sth, $ps);
			return static::returnExecute($sth);
		}
	}
}
?>
