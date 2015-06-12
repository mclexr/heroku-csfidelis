<?php
function getConnection() {
$dbopts = parse_url(getenv('DATABASE_URL'));
$dbhost= $dbopts["host"];
$dbuser= $dbopts["user"];
$dbpass= $dbopts["pass"];
$dbname=ltrim($dbopts["path"],'/');
$dbConnection = new PDO("pgsql:dbname=$dbhost;dbname=$dbname", $dbuser, $dbpass); 
$dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
return $dbConnection;
}
?> 