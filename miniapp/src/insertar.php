<?php
    include_once('config.php');
    session_start();
    if ($_SESSION['login']==0){
       header('Location: login.php');
       exit;
    }
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
                <a class="navbar-brand" href="perfil.php">CONFIGURACION</a>
            </div>
            <div class="navbar-header">
                <form role="search" class="navbar-brand">
                    <input name="buscar" class="navbar-header" type="text" placeholder="Buscar">
                    <input name='buscar_button' value="Buscar" class="navbar-header" type="submit" class="btn btn-default"/>
                </form>
            </div>
            <div class="navbar-header">
                <a href="#"><img width="45" height="40" src="images/carrito.png"/></a>
            </div>
        </div>
    </nav>
    <div>
        <form action="insertar.php" method="post" class="form-horizontal">
            <br>
            <div class="form-group">
                <label for="nombre" class="col-sm-2 control-label"><strong>Nombre *</strong></label>
                <div class="col-sm-10">
                    <input type="text" id="text" name="nombre" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label for="descripcion" class="col-sm-2 control-label"><strong>Descripcion *</strong></label>
                <div class="col-sm-10">
                    <input type="text" id="text" name="descripcion" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label for="precio" class="col-sm-2 control-label"><strong>Precio *</strong></label>
                <div class="col-sm-10">
                    <input type="text" id="text" name="precio" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label for="precio" class="col-sm-2 control-label"><strong>Imagen</strong></label>
                <div class="col-sm-10">
                    <input type="text" id="text" name="imagen" class="form-control" default="/images/">
                </div>
            </div>
            <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <input type="submit" class="btn btn-default" value="INSERTAR"/>
            </div>
        </div>
        </form>
    </div>
    <?php
    if (empty($_POST["nombre"]) || empty($_POST['descripcion']) || empty($_POST['precio'])){
      exit;
    } else {
//      CREAR VARIABLES DEL ARRAY POR SEPARADO
        $nombre=mysqli_real_escape_string($mysqli, $_POST['nombre']);
        $descripcion=mysqli_real_escape_string($mysqli, $_POST['descripcion']);
        $img=mysqli_real_escape_string($mysqli, $_POST['imagen']);
        $imagen="/images/".$img.".png";
        $precio=mysqli_real_escape_string($mysqli, $_POST['precio']);
//      INSERTAR DATOS EN LA BASE DE DATOS
//      EN PRIMER LUGAR PREPARAMOS CONSULTA
        $stmt = mysqli_prepare($mysqli, "INSERT INTO productos(nombre, descripcion, img, precio) VALUES(?, ?, ?, ?)");
//      EN SEGUNDO LUGAR AGREGAMOS VARIABLES A LA CONSUTLTA ANTERIOR, DICIENDO QUE TIPO SON s=string i=entero
        mysqli_stmt_bind_param($stmt, "sssi", $nombre, $descripcion, $imagen, $precio);
//      EJECUTAR EL INSERT
        mysqli_stmt_execute($stmt);
        mysqli_stmt_free_result($stmt);
        mysqli_stmt_close($stmt);
    }

    mysqli_close($mysqli);
    ?>
</body>
</html>