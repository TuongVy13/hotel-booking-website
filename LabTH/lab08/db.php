<?php
try{
$pdh = new PDO("mysql:host=localhost; dbname=bookstore"  , "root"  , ""  );
$pdh->query("  set names 'utf8'"  );
}
catch(Exception $e){
		echo $e->getMessage(); exit;
}
