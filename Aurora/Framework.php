<?php


namespace Aurora\Framework{
	
	
	interface Exception extends \Aurora\Exception{
	}
	
	
	class RuntimeException extends \Aurora\RuntimeException implements Exception{
	}
	
	
	class InvalidArgumentException extends \Aurora\InvalidArgumentException implements Exception{
	}
	
	
	class BadMethodCallException extends \Aurora\BadMethodCallException implements Exception{
	}
}


namespace{
	require_once('Framework/Services/IRegionData.php');
	require_once('Framework/GroupRecord.php');
	require_once('Framework/GroupNoticeData.php');
	require_once('Framework/LandData.php');
	require_once('Framework/EstateSettings.php');
	require_once('Framework/EventData.php');
	require_once('Framework/QueryFilter.php');
	require_once('Framework/IGenericData.php');
	require_once('Framework/IDataConnector.php');
	require_once('Framework/ColumnDefinition.php');
	require_once('Framework/IndexDefinition.php');
}
?>
