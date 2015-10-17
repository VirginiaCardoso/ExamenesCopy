<!--
	AUTOR		Cardoso Virginia
	AUTOR		Marzullo Matias
	COPYRIGHT	Julio, 2015 - Departamento de Ciencias e Ingeniería de la Computación - UNIVERSIDAD NACIONAL DEL SUR 
-->

<link type="text/css" href="<?php echo base_url('assets/css/datepicker/css/bootstrap-datetimepicker.min.css'); ?>" rel="stylesheet" media="screen"/>

<link type="text/css" href="<?php echo base_url('assets/css/examen/generar.css'); ?>" rel="stylesheet" media="screen"/>

<link type="text/css" href="<?php echo base_url('assets/css/select2.css'); ?>" rel="stylesheet" media="screen"/>
<link type="text/css" href="<?php echo base_url('assets/css/select2-bootstrap.css'); ?>" rel="stylesheet" media="screen"/>	
<!--	<link type="text/css" href="<?php echo base_url('assets/css/bootstrap-select.css'); ?>" rel="stylesheet" media="screen"/>	-->
		

<!--<script type="text/javascript"  src="<?php echo base_url('assets/js/bootstrap-select.js'); ?>"></script> -->
<!--<script type="text/javascript"  src="<?php echo base_url('assets/js/bootstrap-select-ES.js'); ?>"></script> -->
<script type="text/javascript"  src="<?php echo base_url('assets/js/select2.js'); ?>"></script>
<script type="text/javascript"  src="<?php echo base_url('assets/js/select2_locale_es.js'); ?>"></script>
	 		
<script type="text/javascript" src="<?php echo base_url('assets/css/datepicker/js/moment.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/css/datepicker/js/bootstrap-datetimepicker.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/css/datepicker/js/bootstrap-datetimepicker.es.js'); ?>"></script>

<script type="text/javascript" src="<?php echo base_url('assets/js/examen/generar.js'); ?>"></script>

<div id="div-form" class="form-container">

	<div class="div-titulo">
		<label>Crear Nueva Guía</label>
	</div>

	<form id="form-crear" class="form-generar" role="form" method="post" action="<?php echo site_url('crear_nueva_guia/crear_guia');?>">
		<div class="form-group-generar">
			<div>
				<div>
					<label for="select-carrera" class="control-label">Carrera</label>
				</div>
				
		<?php

			/* SELECT DE CARRERAS */

			if(!isset($carreras)) // si no existen carreras
			{
				echo 	'<select id="select-carrera" name="carrera" class="select" disabled></select>';
			}
			else
			{ 
				echo '<select id="select-carrera" name="carrera" class="select">';

				foreach ($carreras['list'] as $indice => $carrera): 

					if($indice == $carreras['selected'])
					{
						echo '<option value="'.$carrera['cod_carr'].'" selected = "selected">'.$carrera['cod_carr']." - ".$carrera['nom_carr'].'</option>';
					}
					else
					{
						echo '<option value="'.$carrera['cod_carr'].'">'.$carrera['cod_carr']." - ".$carrera['nom_carr'].'</option>';
					}

				endforeach;

				echo '</select>';
			}
		?>
			</div>
			<label id="error-carrera" class="label-error errores">Carrera inválida</label>
		</div>
		<div class="form-group-generar">
			<div>
				<label for="select-catedra" class="control-label">Cátedra</label>
			</div>
			<div>
		<?php

			/* SELECT DE CATEDRAS */

			if(!isset($catedras)) // si no existen cátedras
			{
				echo 	'<select id="select-catedra" name="catedra" class="select" disabled></select>';
			}
			else
			{ 
				echo "<select id='select-catedra' name='catedra' class='select' data-selected='{$catedras['selected']}'>";

				foreach ($catedras['list'] as $indice => $catedra): 
					if($indice == $catedras['selected'])
					{
						echo '<option value="'.$catedra['cod_cat'].'" selected = "selected">'.$catedra['cod_cat'].' - '.$catedra['nom_cat'].'</option>';
					}
					else
					{
						echo '<option value="'.$catedra['cod_cat'].'">'.$catedra['cod_cat'].' - '.$catedra['nom_cat'].'</option>';
					}

				endforeach; 
				echo '</select>';
			}
		?>
			</div>
			<label id="error-catedra" class="label-error errores">Cátedra inválida</label>
		</div>
		<div class="form-group">
						<div class="row"> 
							<div class="col-xs-12">
			    				<label for="guia">Título de Guía</label>
			    				<input type="text" class="form-control" id="guia" name="guia" placeholder="Ingrese Título de Guía">
			    			</div>
			    		</div>
			  		</div>
			
		<div class="form-group-buttons">
			<a id="btn-cancelar" href="<?php echo site_url('home');?>" class="btn btn-default">Cancelar</a>
			<button id="btn-submit" name="boton" class="btn btn-primary" type="submit">Continuar</button>
		</div>

	</form>

	<?php 
		if(isset($error))
			echo '<label id="error-server" class="label-error">'.$error .'</label> ';
	?>
</div>