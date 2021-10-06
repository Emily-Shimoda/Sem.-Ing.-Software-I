<?php
session_start();
if($_SESSION['rol'] !=1){
    header("location: inicio.php");
}
  include "conexion.php";
  if(!empty($_POST)){
      if($_POST['idusuario'] ==1){
          header("location: mostrar_usuarios.php");
          mysqli_close($conexion);
          exit;
      }
      $idusuario = $_POST['idusuario'];
      $query_delete = mysqli_query($conexion,"UPDATE usuario SET estatus=0 WHERE idusuario=$idusuario");
      mysqli_close($conexion);
      if($query_delete){
           header("location: mostrar_usuarios.php");
      }
      else{
          $mensaje='<p style="color:red">ERROR, NO SE HA ELIMINADO EL REGISTRO.</p>';
      }
  }
  if(empty($_REQUEST['id']) || $_REQUEST['id'] == 1){
      header("location: mostrar_usuarios.php");
      mysqli_close($conexion);
  }else{
      
      $idusuario = $_REQUEST['id'];
      $query = mysqli_query($conexion,"SELECT u.nombre, u.usuario, r.rol FROM usuario u INNER JOIN rol r ON u.rol = r.idrol WHERE u.idusuario= $idusuario");
      mysqli_close($conexion);
      $result = mysqli_num_rows($query);
          if($result > 0){
              while($datos = mysqli_fetch_array($query)){
                  $nombre= $datos['nombre'];
                  $usuario= $datos['usuario'];
                  $rol= $datos['rol'];
              }
          }else{
             header("location: mostrar_usuarios.php");
          }
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        .data_delete{
            text-align: center;
        }
        .data_delete h2{
            font-size: 26px;
        }
        .data_delete span{
            font-weight: bold;
            color: #4f72d4;
            font-size: 20px;
        }
        .data_delete p{
            font-size: 20px;
        }
        .cancel, .okey{
            width: 130px;
            color: #FFF;
            display:flex;
            align-items: center;
            border-radius: 5px;
            cursor: pointer;
            margin: 10px;
        }
        .data_delete form{
            background: initial;
            margin: auto;
            padding: 30px 50px;
            border: 0;
        }
        .data_delete p{
            color: black;
        }
        .data_delete h2{
            color: black;
        }
        .okey{
            background: #42b343;
        }
        .cancel{
            height: 28px;
            text-align: center;
            background: #546B5D;
            border: none;
        }
    </style>
	<meta charset="UTF-8">
	<?php include "scripts.php";?>
	<title>Eliminar Usuario</title>
</head>
<body>
	<?php include "header.php";?>
	<section id="container">
		<div class="eliminar_U" style="color: #3c93b0;">
		   <h1><i class="fas fa-user-times"></i> ELIMINAR USUARIO</h1>
		    <hr>
       <div class="data_delete">
           <h2 id="delete">¿ESTA SEGURO QUE DESEA ELIMINAR EL SIGUIENTE REGISTRO?</h2>
           <p>Nombre: <span><?php echo $nombre;?></span></p>
           <p>Usuario: <span><?php echo $usuario;?></span></p>
           <p>Tipo de usuario: <span><?php echo $rol;?></span></p>
        <form class="delete" method="post">
            <input type="hidden" name="idusuario" value="<?php echo $idusuario; ?>">
           <center><input type="submit" class="okey" value="ACEPTAR">
            <a class="cancel" href="mostrar_usuarios.php">CANCELAR</a></center>
            <div><?php echo isset($mensaje) ? $mensaje : ''; ?></div>
        </form>
       </div>
        </div>
	</section>
	
	<?php include "footer.php";?>
</body>
</html>