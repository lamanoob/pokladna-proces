<?PHP
ini_set('error_reporting',E_ALL); 
ini_set("display_errors","on"); 
ini_set("display_errors",1); 
ini_set('display_startup_errors',1);  
error_reporting(E_ALL);  

//header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
Header("Pragma: no-cache"); 
Header("Expires: ".GMDate("D d m Y H:i:s")." GMT"); 
Header("Cache-control: no-cache"); 
//header("Expires: ".date("D, j M Y H:i:s e")); // Date in the past  
// Před session nastavení
date_default_timezone_set('CET');
session_cache_limiter('private');
/* set the cache expire to 90 minutes */
session_cache_expire(900);
ini_set("session.gc_maxlifetime", "18000");
/*
$cache_limiter = session_cache_limiter();
$cache_expire = session_cache_expire();
echo "The cache limiter is now set to $cache_limiter<br />";
echo "The cached session pages expire after $cache_expire minutes";
  */
set_time_limit(240);
header('Content-Type: text/html; charset=utf-8'); 
?>