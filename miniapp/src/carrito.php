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
                <a class="navbar-brand" href="perfil.php">PERFIL</a>
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
	<table width='100%' border=0 class="table">
	<tr bgcolor="#7fc0ed">
		<td>Nomrbe</td>
		<td>Descripcion</td>
		<td>Precio</td>
		<td>Eliminar</td>
    </tr>
    <?php
//  LISTADO DE PRODUCTOS
//    $result = mysqli_query($mysqli, "SELECT * FROM carrito ORDER BY id DESC";
	while($res = mysqli_fetch_array($result)) {
		echo "<tr>\n";
		echo "<td>".$res['nombre']."</td>\n";
		echo "<td>".$res['descripcion']."</td>\n";
		echo "<td>".$res['precio']."$</td>\n";
		echo "<td><a href=\"eliminar.php?id=$res[id]\" onClick=\"return confirm('Are you sure you want to delete?')\">Eliminar</a></td>\n";
		echo "</tr>\n";
    }
	mysqli_close($mysqli);
    ?>
    </table>
</body>
</html>