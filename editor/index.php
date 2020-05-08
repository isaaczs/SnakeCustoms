<!DOCTYPE html>
<html lang="es">
	<head>
 
		<title>Editor de posts de Instagram</title>

		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

		<!-- Optional theme -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

		<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

		<!-- Latest compiled and minified JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

		<meta name="theme-color" content="#000000">

		<script>
			function eliminar(id) {
				$('#delete_id').val(id);
		    }
			function editar(id,post) {
				$('#edit_id').val(id);
				$('#edit_post').val(post);
				alert(post);
		    }
			function ultimo(id) {
				$('#add_id').val(id);
		    }

		</script>

		<style>
			.col-md-3 {
				margin-bottom: 30px;
			}
			.img-responsive {
				min-width: 100%;
				margin-bottom: 2px;
			}
		</style>

	</head>

	<?php

		$msg_delete = '';
		$msg_edit = '';

		// Ruta del JSON existente
		$file = '../posts.json';
		// Asignamos el JSON existente
		$data = file_get_contents($file);
		$posts = json_decode($data, true);

		if (!empty($_POST['add_idpost']) and $_POST['add_idpost']!='') {

			// Se agrega al inicio
			array_unshift($posts,array('idpost' => $_POST['add_idpost']));

			/*
			$posts[] = array(
				'idpost' => $_POST['add_idpost']
			);
			*/

			// Reordenamos indices
			$posts = array_values($posts);

			// Invertimos orden
			//$posts = array_reverse($posts);			

		    // Creamos un JSON nuevo
		    $json_string = json_encode($posts);
		    file_put_contents($file, $json_string);	

		    unset($_POST);
		}

		if (!empty($_POST['edit_post'])) {
			$id_a_editar = $_POST['edit_id'];
			$posts[$id_a_editar] = array(
				'idpost' => $_POST['edit_post']
			);

			// Reordenamos indices
			$posts = array_values($posts);

			// Invertimos orden
			//$posts = array_reverse($posts);

		    // Creamos un JSON nuevo
		    $json_string = json_encode($posts);
		    file_put_contents($file, $json_string);

		    unset($_POST);

		    $msg_edit = 'Se editó';
		}

		if (!empty($_POST['delete_id'])) {
			$id_a_eliminar = $_POST['delete_id'];
			unset($posts[$id_a_eliminar]);

			// Reordenamos indices
			$posts = array_values($posts);

			// Invertimos orden
			//$posts = array_reverse($posts);

		    // Creamos un JSON nuevo
		    $json_string = json_encode($posts);
		    file_put_contents($file, $json_string);

		    unset($_POST);

		    $msg_delete = 'Se eliminó';

		}

		unset($_POST);

		//print_r($posts);

	?>

    <body>

        <div class="container">
            <div class="row">
                <div class="col-md-12">
	                <br>
					<h3><span class="pull-left">Editor de posts</span>
					<a href=<?php echo $file ?> style="margin-top: -5px;" target="_blank" class="btn btn-warning pull-right">Descargar un backup</a></h3>
					<br>
					<hr>
					<br>

					<div class="well text-left">
						<form class="form-inline" name="addlocal" id="addlocal" action="" method="post">
							<div class="form-group">
								<label for="add_id"><h4 style="margin-top: 16px;">Agregar nuevo post </h4></label>
								<input type="hidden" id="add_id" name="add_id" value="">
							</div>
							<input class="form-control" type="text" id="add_idpost" name="add_idpost" required="" placeholder="Ingrese id de post">
							<button type="submit" class="btn btn-success">Agregar</button>
							<p class="text-muted">Ej: Si el link es: https://www.instagram.com/p/<strong>B9njgJDB2MZ</strong>/ lo que tienes que ingresar es lo que está en negrita</p>
						</form>
					</div>

					<?php if (isset($msg_delete) and $msg_delete != '') {
						echo '<div class="alert alert-danger">'.$msg_delete.'</div>';
					} ?>
					<?php if (isset($msg_edit) and $msg_edit != '') {
						echo '<div class="alert alert-success">'.$msg_edit.'</div>';
					} ?>

					<br>

					<?php 

						// Imprimimos el contenido
						echo "<div class='row'>";

						$id_array = 0;

						foreach ($posts as $post) {
						    $post_id = $id_array;
						    $idpost = $post['idpost'];
							echo "		<div class='col-md-3 text-center'>";
							echo "			<img src='https://instagram.com/p/".$idpost."/media/?size=t' alt='thumbnail' class='img-responsive thumbnail' />";
							echo "			<button type='button' class='btn btn-primary btn-sm' data-toggle='modal' data-target='#edit' onclick='editar(".$post_id.",".$idpost.")'>Editar</button>";	
							echo "			<button type='button' class='btn btn-danger btn-sm' data-toggle='modal' data-target='#delete' onclick='eliminar(".$post_id.")'>Eliminar</button>";	
							echo "		</div>";
							$id_array++;
						}
						echo "</div>";

					?>

				</div>
			</div>

	        <br>
	        <br>
	        <hr>
	        <p class="text-muted small text-center">Editor de posts. 0.1.</p>
	        <br>

        </div>


		<!-- Modal Editar -->
		<div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="editLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="editLabel">Editar post</h4>
					</div>
					<form name="editpost" id="editpost" action="" method="post">
						<div class="modal-body">
							<p>Ingresa el id de la publicación:</p>
							<input type="hidden" id="edit_id" name="edit_id" value="" class="form-control">
							<input type="text" id="edit_post" name="edit_post" required="" class="form-control" style="margin-bottom: 10px;">
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
							<button type="submit" class="btn btn-success">Modificar</button>
						</div>
					</form>
				</div>
			</div>
		</div>

		<!-- Modal Eliminar -->
		<div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="deleteLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="deleteLabel">Eliminar post</h4>
					</div>
					<div class="modal-body">
						<p>¿Desea eliminar definitivamente el post?</p>
					</div>
					<div class="modal-footer">
						<form name="deletepost" id="deletepost" action="" method="post">
							<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
							<input type="hidden" id="delete_id" name="delete_id" value="">
							<button type="submit" class="btn btn-danger">Eliminar</button>
						</form>
					</div>
				</div>
			</div>
		</div>

    </body>
</html>