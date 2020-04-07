<html>
<head>
    <title>Login</title>
    <link rel='stylesheet' type='text/css' media='screen' href='css/bootstrap.css'>
</head>
<body>
    <form class="form-horizontal" action="login.php" method="post" >
        <div class="form-group">
            <label for="user" class="col-sm-2 control-label">User</label>
            <div class="col-sm-10">
                <input type="text" id="text" name="user" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label for="password" class="col-sm-2 control-label">Password</label>
            <div class="col-sm-10">
                <input type="password" class="form-control" id="password" name="password"/>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <input type="submit" class="btn btn-default" value="ENTRAR"/>
            </div>
        </div>
    </form>
<?php
include_once("config.php");
$user=$_POST["user"];
$pass=$_POST["password"];
if (empty($_POST["user"]) || empty($_POST['password'])){
    exit;
} else {
    $result = mysqli_query($mysqli, "SELECT COUNT(id) AS id FROM users WHERE name = '$user' AND password = '$pass' ORDER BY id DESC");
    $res = mysqli_fetch_array($result);
    if ($res['id'] == 1){
        session_start();
        $_SESSION["login"]=1;
        header('Location: index.php');
    } else {
        echo "<h2>INCORRECTO</h2>";
        exit;
    }
}
?>
</body>
</html>