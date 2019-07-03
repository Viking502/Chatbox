<?php
ini_set("session.gc_maxlifetime","15");
session_start();
?>

<!DOCTYPE HTML>
<html lang="pl">
<head>

<title>ChatBox</title>

<meta charset="utf-8"/>

<link type="text/css" rel="stylesheet" href="log_style.css"/>

</head>
<body>

<form method="post"/>
<div id="logo">ChatBox</div>
<input type="password" name="pass">
<input type="submit" value="log in"/>
</form>

<?php
if(isset($_POST['pass']))
{
$password=$_POST['pass'];

if($password==1111)
	{
	$_SESSION['log']=true;
	header('Location: chat.php#scrolled');
	}
else
	{
	echo 'wrong password!';
	}
}

?>

</body>
</html>