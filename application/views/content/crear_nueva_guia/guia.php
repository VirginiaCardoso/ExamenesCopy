
<!-- 	AUTOR		
	AUTOR		
		COPYRIGHT	Septiembre, 2015 - Departamento de Ciencias e Ingeniería de la Computación - UNIVERSIDAD NACIONAL DEL SUR 
-- -->


	<link type="text/css" href="<?php echo base_url('assets/css/examen/examen.css'); ?>" rel="stylesheet" media="screen"/> 
	<link type="text/css" href="<?php echo base_url('assets/css/crear_nueva_guia/crear_nueva_guia.css'); ?>" rel="stylesheet" media="screen"/>
	<script type="text/javascript"  src="<?php echo base_url('assets/js/examen/examen.js'); ?>"></script>
	
	<script type="text/javascript"  src="<?php echo base_url('assets/js/crear_nueva_guia/guia.js'); ?>"></script>


<!-- 

	<link type="text/css" href="<?php echo base_url('assets/css/examen/generar.css'); ?>" rel="stylesheet" media="screen"/>
 -->
	<link type="text/css" href="<?php echo base_url('assets/css/select2.css'); ?>" rel="stylesheet" media="screen"/>
	<link type="text/css" href="<?php echo base_url('assets/css/select2-bootstrap.css'); ?>" rel="stylesheet" media="screen"/>	
	<link type="text/css" href="<?php echo base_url('assets/css/bootstrap-select.css'); ?>" rel="stylesheet" media="screen"/>	


	<script type="text/javascript"  src="<?php echo base_url('assets/js/bootstrap-select.js'); ?>"></script> 
	<script type="text/javascript"  src="<?php echo base_url('assets/js/bootstrap-select-ES.js'); ?>"></script> 
	<script type="text/javascript"  src="<?php echo base_url('assets/js/select2.js'); ?>"></script>
	<script type="text/javascript"  src="<?php echo base_url('assets/js/select2_locale_es.js'); ?>"></script>

	 <script type="text/javascript" src="<?php echo base_url('assets/css/datepicker/js/moment.min.js'); ?>"></script>

	<script type="text/javascript" src="<?php echo base_url('assets/js/examen/generar.js'); ?>"></script>
	
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>

	<link type="text/css" href="<?php echo base_url('assets/css/crear_nueva_guia/crear_nueva_guia.css'); ?>" rel="stylesheet" media="screen"/>


	<div id="div" class="container">

		<div class="div-titulo">
			<label>Agregar items a guía </label>
		</div>
		<!-- obtengo los datos de la guía creada resientemente por medio de la session 	
		<?php
			$id = $this->session->flashdata('id');
			$tit = $this->session->flashdata('tit');
		?>-->
		<div class="tabla">
			<div class="fila">	
				<div class="columna field-name">
				Título guía:
				</div>
				<div class="columna">
					<?php echo $tit_guia; ?> 					
				</div>
		</div>

			<!-- <div class="fila">	
				<div class="columna field-name">
					Número guía:
				</div>
				<div class="columna">
					<?php echo $id; ?>
				</div>
			</div> -->
			  			 		
		</div>


	<div class="contenedor_guia container">
		
			<!-- AQUI VA LA TABLA DE ITEMS   -->
				<div class="items_guia">
					<div class="lista col-xs-12">
						<?php echo $tabla; ?>
					</div>   <!--  -->
					 <form id="form-crear-guia" class="form-crear-guia" role="form" method="post" action="<?php echo site_url('crear_nueva_guia/guardar_guia');?>" >

						<!-- <input type="hidden" name="input-cod-carr" id="input-cod-carr" value="<?php echo $cod_carr; ?>"/> -->
						<input type="hidden" name="input-cod-cat" id="input-cod-cat" value="<?php echo $cod_cat; ?>"/>
						<input type="hidden" name="input-tit-guia" id="input-tit-guia" value="<?php echo $tit_guia; ?>"/>
					 	<div class="guardar-guia form-group-buttons botonera">

							<a id="btn-cancelar" href="<?php echo site_url('crear_nueva_guia/crear_guia');?>" class="btn btn-default ">Cancelar</a>
						<!-- 	<a id="btn-guardar" data-target="#" class="btn btn-primary btn-lg">Guardar</a> -->
							<button id="btn-guardar" name="boton" class="btn btn-primary " type="submit" >Guardar</button>
						</div>

					 </form> 
				</div>
						 

		<div class="forms_items ">

		<div class="form-group form-radio">
	    	<div>
			    <label class="radio-inline">
			        <input type="radio" name="tipoitem" value="itemsimple"  checked="checked" /> Item simple
			    </label>
			    <label class="radio-inline">
			        <input type="radio" name="tipoitem" value="grupoitems"  disabled="disabled" /> Grupo items
			    </label>
			</div>
		</div>
		<!-- Nuevo Item -->
			
					<div class="row row_items">  
						<div class="col-xs-10">
								<!-- <label for="item" class="control-label">Nombre</label> -->
								<input type="text" class="form-control " id="item" name="item" value="" placeholder="Ingrese texto del item" />
						</div>
						<div class="col-xs-2 botonadd">
								<button id="btn-submit" name="boton" class="btn btn-primary" type="submit" onclick="addRow('lista_items_guia');"> + </button>
						</div>
					<!-- </div>-->
				</div> 


			<div class=" row row_items">
				
				<div class="col-xs-10 ">

					<?php

					/* SELECT DE items */

					if(!isset($items)) // si no existen items
					{
						echo 	'<select id="select-item" name="select-item" class="select form-control" disabled></select>';
					}
					else
					{ 
						echo '<select id="select-item" name="select-item" class="select form-control select-i">';

						foreach ($items['list'] as $indice => $item): 

							if($indice == $items['selected'])
							{
								echo '<option value="'.$item['id_item'].'" selected = "selected">'.$item['nom_item'].'</option>';
							}
							else
							{
								echo '<option value="'.$item['id_item'].'">'.$item['nom_item'].'</option>';
							}

						endforeach;

						echo '</select>';
					}
					?>
				</div>	
				<div class="col-xs-2 botonadd">
					<button id="btn-submit" name="boton" class="btn btn-primary" type="submit" onclick="addRow2('lista_items_guia');"> + </button>
				</div>
			</div>
		</div>
	</div>

	

</div>