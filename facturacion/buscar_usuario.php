<?php 
 session_start();
if($_SESSION['rol'] !=1){
    header("location: inicio.php");
}
 include "conexion.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="css/stilo_usuarios.css">
	<?php include "scripts.php";?>
	<style>
    .delete_user{
    color: red;
}
    .paginador ul{
    padding: 1px;
    margin-top: 10px;
    background: #FFF;
    list-style: none;
	display: flex;
    justify-content: flex-end;
}
.paginador a, .selectedpage{
    color: #428bca;
    border: 1px solid #ddd;
    padding: 5px;
    display: inline-block;
    font-size:  14px;
    text-align: center;
    width: 35px;
}
.paginador a:hover{
    background: black;
}
.selectedpage{
    color: #FFF;
    background: #428bca;
    border: 1px solid #48bca;
}
.buscar_user{
    display: flex;
    float: right;
    padding: 10px;
    border-radius: 5px solid;
    background: initial;
    width: 500px;
}
.buscauser{
    color: #FFF;
    padding:0 5px;
    background: #1faac8;
    border: 10;
    height: 28.5px;
    cursor: pointer;
    margin: 2px;
}
    </style>
	<title>LISTA DE USUARIOS</title>
</head>
<body>
	<?php include "header.php";?>
	<section id="container">
	<?php 
        $busqueda=strtolower($_REQUEST['busqueda']);
        if(empty($busqueda)){
            header("location: mostrar_usuarios.php");
            mysqli_close($conexion);
        }
    ?>
		<div class="lista_usuario">
		   <h1><i class="fas fa-users"></i> LISTA DE USUARIOS</h1>
		    <hr>
		<center><a href="regis_usuario.php" class="new_user">REGISTRAR USUARIO <i class="fas fa-user-plus"></i></a></center>
		<form action="buscar_usuario.php" method="get" class="buscar_user">
		    <input type="text" name="busqueda" id="busquedau" placeholder="Buscar" value="<?php echo $busqueda; ?>">
		    <button type="submit" class="buscauser"><i class="fas fa-search fa-lg"></i></button>
		    
		</form>
		<table>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Usuario</th>
                <th>Rol</th>
                <th>Acciones</th>
		    </tr>
        <?php 
            //paginador
            $rol='';
            if($busqueda == 'admin' || $busqueda =='administrador'){
                $rol= "OR rol LIKE '%1%'";
            }else if ($busqueda == 'super' || $busqueda=='supervisor'){
                 $rol= "OR rol LIKE '%2%'";
            }else if ($busqueda == 'vende' || $busqueda=='vendedor'){
                 $rol= "OR rol LIKE '%3%'";
            }
            $registros= mysqli_query($conexion,"SELECT COUNT(*) as total_registros FROM usuario                                                   WHERE ( idusuario LIKE '%$busqueda%'                                                      OR nombre LIKE '%$busqueda%' OR 
                                                                      usuario LIKE '%$busqueda%' 
                                                                      $rol ) 
                                                                      AND estatus=1");
            $result_regis = mysqli_fetch_array($registros);
            $total_regis= $result_regis['total_registros'];
            $por_pagina = 5;
            if(empty($_GET['pagina'])){
                $pagina=1;
            }else{
                $pagina=$_GET['pagina'];
            }
            $desde = ($pagina-1) * $por_pagina;
            $total_paginas = ceil($total_regis / $por_pagina);
            
        $query=mysqli_query($conexion,"SELECT u.idusuario, u.nombre, u.correo, u.usuario, r.rol FROM usuario u INNER JOIN rol r ON u.rol = r.idrol 
            WHERE ( u.nombre LIKE '%$busqueda%' OR 
                    u.idusuario LIKE '%$busqueda%' OR 
                    u.usuario LIKE '%$busqueda%' OR
                    r.rol LIKE '%$busqueda%' ) AND
        estatus=1 ORDER BY u.idusuario ASC LIMIT $desde,$por_pagina ");
            mysqli_close($conexion);
        $result = mysqli_num_rows($query);
            if($result > 0){
                while($datos = mysqli_fetch_array($query)){
        ?>
		    <tr>
		        <td><?php echo $datos["idusuario"] ?></td>
		        <td><?php echo $datos["nombre"] ?></td>
		        <td><?php echo $datos["correo"] ?></td>
		        <td><?php echo $datos["usuario"] ?></td>
		        <td><?php echo $datos["rol"] ?></td>
		        <td>
		            <a href="modificar_usuario.php?id=<?php echo $datos["idusuario"] ?>" class="modificar_user"><i class="fas fa-edit"></i> Modificar</a>
		          <?php if($datos["idusuario"] !=1 ){ ?>
		            |
		            <a href="eliminar_user.php?id=<?php echo $datos["idusuario"] ?>" class="delete_user"><i class="fas fa-trash-alt"></i> Eliminar</a>
		            <?php }?>
		        </td>
		    </tr>
        <?php
		          }
            }
        ?>
		</table>
		</div>
		<?php if ($total_regis >0){ ?>
		<div class="paginador">
		  <ul>
        <?php 
              if($pagina!= 1){
              ?>
		    <li><a href="?pagina=<?php echo 1;?>&busqueda=<?php echo $busqueda; ?>"><i class="fas fa-step-backward"></i></a></li>
		    <li><a href="?pagina=<?php echo $pagina-1;?>&busqueda=<?php echo $busqueda; ?>"><i class="fas fa-caret-left"></i></a></li
            <?php
                }
                for ($i=1; $i<=$total_paginas; $i++){
                    if($i == $pagina){ 
                        echo '<li class="selectedpage">'.$i.'</li>';
                }  
                else{
                     echo '<li><a href="?pagina='.$i.'&busqueda='.$busqueda.'">'.$i.'</a></li>';
                }
                    }
                if($pagina !=$total_paginas){
                ?>
            <li><a href="?pagina=<?php echo $pagina+1;?>&busqueda=<?php echo $busqueda; ?>"><i class="fas fa-caret-right "></i></a></li>
            <li><a href="?pagina=<?php echo $total_paginas;?>&busqueda=<?php echo $busqueda; ?>"><i class="fas fa-step-forward"></i></a></li>
            <?php }?>
		  </ul>
		</div>
		<?php }else{ echo '<hr>';
                     echo'<center><p style="color:#01F70C">BUSQUEDA SIN RESULTADOS</p></center>';}
        ?>
	</section>
	
	<?php include "footer.php";?>
</body>
</html>