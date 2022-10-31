<?php // authenticate3.php
  require_once 'SQL_login.php';

  try
  {
    $pdo = new PDO($attr, $user, $pass, $opts);
  }
  catch (\PDOException $e)
  {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
  }

if(isset($_POST['email']) && isset($_POST['pwd'])){
	
    $email_temp = sanitise($pdo,$_POST['email']);
    $pw_temp = sanitise($pdo,$_POST['pwd']);
    $query   = "SELECT * FROM userdb WHERE email=$email_temp";
    $result  = $pdo->query($query);

    if (!$result->rowCount()) die("User not found");

    $row = $result->fetch();
    $fn  = $row['email'];
    $pw  = $row['pass'];

    //if (password_verify(str_replace("'", "", $pw_temp), $pw))
    if (password_verify( $pw_temp, $pw))
    {
      session_start();

      $_SESSION['email'] = $fn;

      echo htmlspecialchars(" Hi $fn,
        you are now logged in as '$fn'");
      die ("<p><a href='continue.php'>Click here to continue</a></p>
            <br><p><a href='session_logout.php'>Click here to logout</a></p>");
    }
    else echo("Invalid username/password combination");
    echo ("'$fn', '$pw', '$pw_temp'");
  }
  else
  {

    echo ("Please enter your username and password");
  }

  function sanitise($pdo, $str)
  {
    $str = htmlentities($str);
    return $pdo->quote($str);
  }
?>
