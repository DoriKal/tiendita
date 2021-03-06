<?php 
	session_start();
	include_once "../php_conexion.php";
	include_once "class/class.php";
	include_once "../funciones.php";
	include_once "../class_buscar.php";
	
	if($_SESSION['tipo_user']=='a' or $_SESSION['tipo_user']=='c'){
	}else{
		header('Location: ../../php_cerrar.php');
	}
	
	$usu=$_SESSION['idUser'];
	
	$oPersona=new Consultar_Cajero($usu);
	$cajero_nombre=$oPersona->consultar('nom').' '.$oPersona->consultar('ape');

	$pa=mysql_query("SELECT * from (empleado e INNER JOIN cajero c on e.idUsuario=c.usuario_idUsuario3) INNER JOIN sucursal s ON s.idDeposito=c.deposito_idDeposito3 WHERE e.idUsuario='$usu'");				
	while($row=mysql_fetch_array($pa)){
		$id_bodega=$row['deposito_idDeposito3'];
		$oDeposito=new Consultar_Deposito($id_bodega);
		$nombre_deposito=$oDeposito->consultar('nombre');
	}
	
	if(!empty($_GET['del'])){
		$id=limpiar($_GET['del']);
		mysql_query("DELETE FROM detalle  WHERE detalle.usuario_idUsuario1=$usu and id='$id'");
		header("Location: index.php");
	}
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <title>Ventas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="../../css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
      table {
        width:90%;
        }
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
        }
        th, td {
            padding: 5px;
            text-align: left;
        }
        table#t01 tr:nth-child(even) {
            background-color: #eee;
        }
        table#t01 tr:nth-child(odd) {
           background-color:#fff;
        }
        table#t01 th  {
            background-color: black;
            color: white;
        }
    </style>
    <link href="../../css/bootstrap-responsive.css" rel="stylesheet">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="../../ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="../../ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../../ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="../../ico/apple-touch-icon-57-precomposed.png">
	<link rel="shortcut icon" href="../../ico/favicon.png">
  </head>
  <!-- -->
  <body>

    <?php include_once "../../menu/m_venta.php"; ?>
	<div align="center">
    	<table width="90%">
          <tr>
            <td>
            	<table class="table table-bordered">
                  <tr class="well">
                    <td>
                   	    <div class="row-fluid">
	                        <div class="span6">
                            	<form name="form2" action="" method="post">
                                    <strong>Codigo o Nombre del Producto</strong><br>
                                    <input type="text" autofocus list="browsers" name="buscar" autocomplete="off" class="input-xxlarge" required>
                                    
                                    <datalist id="browsers">
                                        <?php
                                            $pa=mysql_query("SELECT a.nombre  FROM (pedido p INNER JOIN articulo a ON a.codigo=p.articulo_codigo) INNER JOIN sucursal s ON s.idDeposito=$id_bodega");				
                                            while($row=mysql_fetch_array($pa)){
                                                echo '<option value="'.$row['nombre'].'">';
                                            }
                                        ?> 
                                    </datalist>
                                </form>
                            </div>
    	                    <div class="span6">
                            	<div class="row-fluid">
			                        <div class="span6">
                                    	<strong>Cajero: </strong> <?php echo $cajero_nombre; ?><br>
                                        <i class="icon-ok"></i> <strong>Sucursal: </strong> <?php echo $nombre_deposito; ?><br>
                                        <i class="icon-ok"></i> <strong>Fecha: </strong> <?php echo fecha(date('Y-m-d')); ?>
                                    </div>
                                    <div class="span6" align="right">
                                    	<?php
											if (file_exists("../../usuarios/".$_SESSION['cod_user'].".jpg")){
												echo '<img src="../../usuarios/'.$_SESSION['cod_user'].'.jpg" width="50" height="50" class="img-polaroid img-polaroid">';
											}else{
												echo '<img src="../../usuarios/defecto.png" width="80" height="80">';
											}
										?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                  </tr>
                </table>
                <?php
					if(!empty($_POST['new_cant'])){
						$new_cant=limpiar($_POST['new_cant']);
						$ncodigo=limpiar($_POST['ncodigo']);
                        echo $usu.$ncodigo;
						mysql_query("UPDATE detalle SET cant='$new_cant' WHERE articulo_codigo1='$ncodigo' and usuario_idUsuario1=$usu");
					}
					
					if(!empty($_POST['ncodigo_ref'])){
						$referencia=limpiar($_POST['referencia']);
						$ref_ant=limpiar($_POST['ref_ant']);
						$ncodigo=limpiar($_POST['ncodigo_ref']);
						
						if($referencia==''){
							mysql_query("UPDATE detalle SET ref='' WHERE articulo_codigo1='$ncodigo' and usuario_idUsuario1=$usu and ref='$ref_ant'");
						}else{
							$pa=mysql_query("SELECT * FROM detalle WHERE detalle.ref='$referencia'");				
							if($row=mysql_fetch_array($pa)){
								echo mensajes('El Numero de Referencia "'.$referencia.'" Esta siendo usada','rojo');
							}else{
								mysql_query("UPDATE detalle SET ref='$referencia' WHERE articulo_codigo1='$ncodigo' and usuario_idUsuario1=$usu and ref='$ref_ant'");
							}
						}
						
					}	
				
                	if(!empty($_POST['buscar'])){
						$buscar=limpiar($_POST['buscar']);
						$poa=mysql_query("SELECT a.codigo 
                            FROM (articulo a INNER JOIN pedido p ON p.articulo_codigo=a.codigo) 
                            INNER JOIN sucursal s ON p.deposito_idDeposito=s.idDeposito
                            WHERE s.idDeposito=$id_bodega and (a.codigo='$buscar' or a.nombre='$buscar') 
                            GROUP BY a.nombre");	
						if($roow=mysql_fetch_array($poa)){
							$codigo=$roow['codigo'];
                            
							$pa=mysql_query("SELECT * FROM detalle WHERE articulo_codigo1='$codigo' and usuario_idUsuario1=$usu and ref=''");	
							if($row=mysql_fetch_array($pa)){
								$cant=$row['cant']+1;
								mysql_query("UPDATE detalle SET cant='$cant' WHERE articulo_codigo1='$codigo' and usuario_idUsuario1=$usu");
							}else{
								mysql_query("INSERT INTO detalle (articulo_codigo1,ref, cant, usuario_idUsuario1) VALUES ('$codigo','N/A','1',$usu)");	
							}
						}else{
							echo mensajes('El Producto que Busca no se encuentra Registrado en la Base de Datos','rojo');	
						}
					}
                ?>
                <div class="row-fluid">
	                <div class="span8">
                    	<div style="width:100%; height:300px; overflow: auto;">
                        <table id="t01" class="table table-bordered">
                            <tr class="well">
                            	<td><strong>Código</strong></td>
                                <td><strong>R. Venta</strong></td>
                                <td><strong>Descripción del Producto</strong></td>
                                <td><strong><center>Cant.</center></strong></td>
                                <td><strong><div align="right">Precio</div></strong></td>
                                <td><strong><div align="right">Importe</div></strong></td>
                                <td></td>
                            </tr>
                            <?php 
								$neto=0;$item=0;
                                $pa=mysql_query("SELECT * FROM ((detalle d 
                                    INNER JOIN articulo a on d.articulo_codigo1=a.codigo) 
                                    INNER JOIN empleado e ON d.usuario_idUsuario1=e.idUsuario) 
                                    INNER JOIN iva i ON a.iva_ivaventa=i.idIva WHERE e.idUsuario=$usu");				
                                    while($row=mysql_fetch_array($pa)){
									$item=$item+$row['cant'];
									##### CONSULTAR IVA ###################
									$oIVA=new Consultar_IVA($row['idIva']);
									$iva=$oIVA->consultar('valor');
                                    ##### Calcular el valor e importe ######
                                    $defecto=strtolower($row['defecto']);
                                    $valor=$row[$defecto.'_venta']*(($iva/100)+1);
                                    $importe=$row['cant']*$valor;
									$neto=$neto+$importe;
                                    ########################################
									if($row['ref']==NULL){
										$referencia='Sin Referencia';
									}else{
										$referencia=$row['ref'];
									}
                            ?>
                            <tr>
                            	<td><?php echo $row['codigo']; ?></td>
                                <td>
                                	<a href="#r<?php echo $row['id']; ?>" role="button" class="btn btn-mini" data-toggle="modal">
										<strong><?php echo $referencia; ?></strong>
                                    </a>
                                </td>
                                <td><?php echo $row['nombre']; ?></td>
                                <td>
                                	<center>
                                    	<a href="#m<?php echo $row['id']; ?>" role="button" class="btn btn-mini" data-toggle="modal">
											<strong><?php echo $row['cant']; ?></strong>
                                        </a>
                                    </center>
                                </td>
                                <td><div align="right">$ <?php echo formato($valor); ?></div></td>
                                <td><div align="right">$ <?php echo formato($importe); ?></div></td>
                                <td>
                                    <center>
                                        <a href="index.php?del=<?php echo $row['id']; ?>" class="btn btn-mini" title="Remover de la Lista de Compra">
                                            <i class="icon-remove"></i>
                                        </a>
                                    </center>
                                </td>
                            </tr>
                            
                             <div id="r<?php echo $row['id']; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <form name="forme" action="" method="post">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h3 id="myModalLabel" align="center">Referencia del Producto<br>[<?php echo $row['nombre']; ?>]</h3>
                                </div>
                                <div class="modal-body" align="center">
                                	<input type="hidden" name="ncodigo_ref" value="<?php echo $row['codigo']; ?>">
                               		<strong>Referencia del Producto</strong><br>
                                    <input type="text" name="referencia" value="<?php echo $row['ref']; ?>" class="input-xlarge" autocomplete="off">
                                    <input type="hidden" name="ref_ant" value="<?php echo $row['ref']; ?>">
                                </div>
                                <div class="modal-footer">
                                    <button class="btn" data-dismiss="modal" aria-hidden="true"><strong>Cerrar</strong></button>
                                    <button type="submit" class="btn btn-primary"><strong>Registrar Referencia</strong></button>
                                </div>
                                </form>
                            </div>
                            
                            
                            <div id="m<?php echo $row['id']; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <form name="forme" action="" method="post">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h3 id="myModalLabel" align="center">Actualizar Cantidad<br>[<?php echo $row['nombre']; ?>]</h3>
                                </div>
                                <div class="modal-body" align="center">
                                	<input type="hidden" name="ncodigo" value="<?php echo $row['codigo']; ?>">
                               		<strong>Nueva Cantidad</strong><br>
                                    <input type="number" name="new_cant" min="1" value="<?php echo $row['cant'] ?>" autocomplete="off" required>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn" data-dismiss="modal" aria-hidden="true"><strong>Cerrar</strong></button>
                                    <button type="submit" class="btn btn-primary"><strong>Actualizar Cantidad</strong></button>
                                </div>
                                </form>
                            </div>
                            
                            <?php } ?>
                        </table>
                        </div>
                    </div>
    	            <div class="span4">
                    	<table class="table table-bordered">
                            <tr>
                                <td>
                                	<center><strong>Total a Pagar</strong>
                                	<pre><h2 class="text-success" align="center">$ <?php echo formato($neto); ?></h2></pre>
                                    <strong>Cantidad Productos: <br><span class="badge badge-success"><?php echo $item; ?></span></strong></center>
                                </td>
                            </tr>
                    	</table>
                        <?php if($neto<>0){ ?>
                        <table class="table table-bordered">
                            <tr>
                                <td>
                                	<div align="center">
                                        <a href="#contado" role="button" class="btn" data-toggle="modal">
                                            <i class="icon-shopping-cart"></i> <strong>Cobrar</strong>
                                        </a>
                                	</div>
                                </td>
                            </tr>
                    	</table>
                        <?php } ?>
                        <table class="table table-bordered">
                            <tr>
                                <td>
                                    <div align="center">
                                        <a href="venta_dia.php" role="button" class="btn">
                                            <i class="icon-shopping-cart"></i> <strong>Corte Total</strong>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                
            </td>
          </tr>
        </table>
    </div>
    
    <div id="contado" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    	<form name="contado" action="pro_contado.php" method="get">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="myModalLabel" align="center">Cobrar</h3>
        </div>
        <div class="modal-body" align="center">
        	<strong>Hola! <?php echo $cajero_nombre; ?></strong><br>
			<strong>Total a Pagar</strong>
           	<pre><h2 class="text-success" align="center">$ <?php echo formato($neto); ?></h2></pre>
            <strong>Efectivo</strong><br>
            <div class="input-prepend input-append">
				<span class="add-on"><strong><?php echo $s; ?></strong></span>
            	<input type="number" name="valor_recibido" min="<?php echo $neto; ?>" step="0.01" autocomplete="off" required>
                <span class="add-on"><strong></strong></span>
        	</div>
            <input type="hidden" value="<?php echo $neto; ?>" name="neto">
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true"><strong>Cerrar</strong></button>
            <button type="submit" class="btn btn-primary"><strong>Registrar Compra</strong></button>
        </div>
        </form>
    </div>
    
    
    <!-- Le javascript ../../js/jquery.js
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="../../js/jquery.js"></script>
    <script src="../../js/bootstrap-transition.js"></script>
    <script src="../../js/bootstrap-alert.js"></script>
    <script src="../../js/bootstrap-modal.js"></script>
    <script src="../../js/bootstrap-dropdown.js"></script>
    <script src="../../js/bootstrap-scrollspy.js"></script>
    <script src="../../js/bootstrap-tab.js"></script>
    <script src="../../js/bootstrap-tooltip.js"></script>
    <script src="../../js/bootstrap-popover.js"></script>
    <script src="../../js/bootstrap-button.js"></script>
    <script src="../../js/bootstrap-collapse.js"></script>
    <script src="../../js/bootstrap-carousel.js"></script>
    <script src="../../js/bootstrap-typeahead.js"></script>

  </body>
</html>
