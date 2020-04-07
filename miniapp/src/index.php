<?php
    include_once('config.php');
    session_start();
    if ($_SESSION['login']==0){
       header('Location: login.php');
       exit;
    }
    $result = mysqli_query($mysqli, "SELECT * FROM productos ORDER BY id ASC");
?>
<html>
<head>
    <title>Pagina principal</title>
    <link rel='stylesheet' type='text/css' media='screen' href='css/bootstrap.css'>
</head>
<body>
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="index.php">INICIO</a>
                <a class="navbar-brand" href="perfil.php">PERFIL</a>
            </div>
            <div class="navbar-header">
                <form action="buscar.php" method="post" role="search" class="navbar-brand">
                    <input name="buscar" class="navbar-header" type="text" placeholder="Buscar">
                    <input name='buscar_button' value="Buscar" class="navbar-header" type="submit" class="btn btn-default"/>
                </form>
            </div>
            <div class="navbar-header">
                <a href="carrito.php"><img width="45" height="40" src="images/carrito.png"/></a>
            </div>
        </div>
    </nav>  
    <div align="center">
        <?php
        echo "<div class="."row>"."\n";
        while($res = mysqli_fetch_array($result)) {
            echo "<div class="."col-3>"."\n";
            echo "<h3 align='center'>".$res['nombre']."</h3>"."\n";
            echo "<p align='center' class="."lead".">".$res['descripcion']."</p>"."\n";
            echo "<img src=\"".$res['img']."\"width='250' height='200'/>"."\n";
            echo "<h5 align='center'>".$res['precio']."€"."</h5>"."\n";
            echo "<form action='carrito.php' method='post'>";
            echo "<input name='carrito' type='submit' value='array($res["nombre"]) '>";
            echo "</form>";
            echo "</div>"."\n";
        }
        echo "</div>"."\n";
        mysqli_close($mysqli);
        ?>
    </div>
</body>
</html>