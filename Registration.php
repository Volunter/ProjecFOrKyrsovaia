<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

	<title>Банк</title>
	<link rel="stylesheet" href="style2.css" media="screen" type="text/css" />
</head>
<body>
    <div id="login">
        <form action="Registration.php" method="post" enctype="multipart/form-data" >
            <fieldset class="clearfix">
		    <p><input type="text" name = "name"  placeholder="Имя" required></p>
		     <p><input type="text" name = "secondName"  placeholder="Фамилия" required></p>
                <p><input type="text" name = "login"  placeholder="Логин" required></p>
                <p><input type="password" name = "password1"   placeholder="Пароль" required></p>
                <p><input type="password" name = "password2"   placeholder="Повторить пароль" required></p>
                <p><input type="text" name = "phone"   placeholder="Номер телефона" required  pattern="[78][0-9]{10}"></p>
                <p><input type="submit" name = "submit" value="Зарегистрироваться"></p>
		    	<p><a href="autorization.php"> Вход в аккаунт </a></p>
            </fieldset>
        </form>
    </div>
</body>
</html>

<?php
session_start();
try {
     $conn = new PDO("sqlsrv:server = tcp:vol2.database.windows.net,1433; Database = BD", "Volun", "Simpsons1");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
	
}
catch (PDOException $e) {
	print("Error connecting to SQL Server.");
	die(print_r($e));
}

if(isset($_POST["submit"])) {
try {
    $name = $_POST['name'];
    $secondName = $_POST['secondName'];
    $login = $_POST['login'];
    $pas1 = $_POST['password1'];
    $pas2 = $_POST['password2'];
    $phone = $_POST['phone'];
	
	
	
$sql_select = "SELECT * FROM Enter WHERE Login LIKE '%".$login."%'";
$stmt = $conn->query($sql_select);
$reg = $stmt->fetchAll(); 
	
$sql_select0 = "SELECT * FROM Klient WHERE Phone LIKE '%".$phone."%'";
$st = $conn->query($sql_select0);
$ph = $st->fetchAll(); 	
	
if(count($reg) > 0) {
    echo "<h2>This login already exist</h2>";
    }
else{		
if($pas1!=$pas2)
	echo "<h3>Passwords isn't equal</h3>";
	else{
		if(count($ph) > 0)
			echo "<h2>This phone already exist</h2>";
		else{
		$sql_insert1 = 
"INSERT INTO Klient (Name, SecondName,Phone) 
                   VALUES (?,?,?)";
    $stmt = $conn->prepare($sql_insert1);
    $stmt->bindValue(1, $name);
    $stmt->bindValue(2, $secondName);
	$stmt->bindValue(3, $phone);
    $stmt->execute();
		
		
    $sql_insert2 = 
"INSERT INTO Enter (Login, Password) 
                   VALUES (?,?)";
    $stmt = $conn->prepare($sql_insert2);
    $stmt->bindValue(1, $login);
    $stmt->bindValue(2, $pas1);

    $stmt->execute();
		
  	header('location: autorization.php');	
		
}
}
}
}
catch(Exception $e) {
    die(var_dump($e));
}
}




?>
