<?php

if(isset($nickname))
	{
	setcookie('nick',$nickname,time()+60);
	}
/*	else
	{
	setcookie('nick','noname',time()+60*60*24*14);
	}
*/
?>

<html>
<head>

<title>ChatBox</title>

<link rel="stylesheet" type="text/css" href="chat_style.css" />

</head>
<body>


<div id="chatbox">
	
	<div id="logo">ChatBox</div>
	
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
	echo '<div class="post_name">';
	echo $implode['nick']."      ".$implode['date'];
	echo '</div>';
	echo '<div class="post_message">';
	echo $implode['message'];
	echo '</div>';
	echo '</div>';
	}
	}
$results->close();
}

?>


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
" /></br>
		<input type="text" name="message" id="message" /></br>
		<input type="submit" value="send!" name="send"/></br>
		</form>
	</div>
	
</div>

</body>
</html>