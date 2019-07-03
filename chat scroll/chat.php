<?php

if(isset($nickname))
	{
	setcookie('nick',$nickname,time()+60);
	}
	else
	{
	setcookie('nick','noname',time()+60*60*24*14);
	}


//sesia wygasa po 30s
ini_set("session.gc_maxlifetime","15");

//sprawdzanie autoryzacji

session_start();

if(!isset($_SESSION['log']))
{
header('Location: index.php');
exit();
}
	
?>

<html>
<head>

<title>ChatBox</title>

<link rel="stylesheet" type="text/css" href="chat_style.css" />

</head>
<body>

<div id="logo">ChatBox</div>
<div id="chatbox">
	
	<div id="window">


<?php

//zapamiętywanie nicku
if(isset($_POST['nick']))
{
$nickname=$_POST['nick'];
}

//łączenie z MySQL

require_once('db_connect.php');

$connection = new mysqli($host,$db_user,$db_password,$db_name);

if ($connection->connect_errno != 0)
{
echo "error".connect_errno;
}
else
{

//wysyłanie wiadomości

if((isset($_POST['send'])) && ($_POST['nick']!=NULL) && ($_POST['message']!=NULL))
{

$nick=$_POST['nick'];
$message=$_POST['message'];

//wysyłanie kwerendy

$connection -> query("INSERT INTO posts VALUES (NULL,'$nick','$message',NOW())");

header('Location: chat.php');
exit();
}

//widok na posty
//sprawdzanie liczby wiadomości

	$number_of_posts = $connection->query(sprintf("SELECT id_post FROM posts ORDER BY id_post DESC LIMIT 1"));
	$amount = mysqli_fetch_assoc($number_of_posts);
	$number_of_posts->close();
	
//wgrywanie wiadomości

	for($i=1;$i<=$amount['id_post'];$i++)
	{
	$results = $connection->query(sprintf("SELECT nick,message,date FROM posts WHERE id_post='$i'"));

//pokazywanie wiadomości	

	$implode = mysqli_fetch_assoc($results);
	
	if($implode!=NULL)
	{
	echo '<div class="post">';
		echo '<div class="post_header">';
			echo '<div class="post_name">'.$implode['nick'].'</div>';
			echo '<div class="post_date" >'.$implode['date'].'</div>';
			echo '<div style="clear:both;"></div>';
		echo '</div>';
		echo '<div class="post_message">'.$implode['message'].'</div>';
	echo '</div>';
	}
	}
$results->close();
}

?>

	<div id="scrolled"></div>
	</div>
	
	<div id="office">
		<form method="post">

<input type="text" name="nick" id="nick" value="
<?php
if(isset($_COOKIE['nick']))
	{
	echo $_COOKIE['nick'];
	}
?>
" />
		<input type="text" name="message" id="message" />
		<input type="submit" value="send!" name="send"/>
		</form>
	</div>
	
</div>

</body>
</html>