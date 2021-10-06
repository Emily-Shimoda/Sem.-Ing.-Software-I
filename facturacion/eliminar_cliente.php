<?php
session_start();
  include "conexion.php";
  if(!empty($_POST)){
      $codcliente= $_POST['codcliente'];
      $query_delete = mysqli_query($conexion,"UPDATE cliente SET estatus=0 WHERE codcliente=$codcliente");
      mysqli_close($conexion);
      if($query_delete){
           header("location: mostrar_clientes.php");
      }
      else{
          $mensaje='<p style="color:red">ERROR, NO SE HA ELIMINADO EL REGISTRO.</p>';
      }
  }
  if(empty($_REQUEST['id'])){
      header("location: mostrar_clientes.php");
      mysqli_close($conexion);
  }else{
      
      $codcliente = $_REQUEST['id'];
      $query = mysqli_query($conexion,"SELECT c.codcliente, c.nit, c.nombre, c.telefono, c.direccion FROM cliente c WHERE c.codcliente= $codcliente");
      mysqli_close($conexion);
      $result = mysqli_num_rows($query);
          if($result > 0){
              while($datos = mysqli_fetch_array($query)){
                  $codcliente= $datos['codcliente'];
                  $nit= $datos['nit'];
                  $nombre= $datos['nombre'];
                  $tel= $datos['telefono'];
                  $dir= $datos['direccion'];
              }
          }else{
             header("location: mostrar_clientes.php");
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
	<title>Eliminar Cliente</title>
</head>
<body>
	<?php include "header.php";?>
	<section id="container">
		<div class="eliminar_U" style="color: #3c93b0;">
		   <h1><i class="fas fa-user-times"></i> ELIMINAR CLIENTE</h1>
		    <hr>
       <div class="data_delete">
           <h2 id="delete">¿ESTA SEGURO QUE DESEA ELIMINAR EL SIGUIENTE CLIENTE?</h2>
           <p>N° Cliente: <span><?php echo $codcliente;?></span></p>
           <p>NIT/RFC: <span><?php echo $nit;?></span></p>
           <p>Nombre: <span><?php echo $nombre;?></span></p>
           <p>Teléfono: <span><?php echo $tel;?></span></p>
           <p>Dirección: <span><?php echo $dir;?></span></p>
        <form class="delete" method="post">
            <input type="hidden" name="codcliente" value="<?php echo $codcliente; ?>">
           <center><input type="submit" class="okey" value="ACEPTAR">
            <a class="cancel" href="mostrar_clientes.php">CANCELAR</a></center>
            <div><?php echo isset($mensaje) ? $mensaje : ''; ?></div>
        </form>
       </div>
        </div>
	</section>
	
	<?php include "footer.php";?>
</body>
</html>