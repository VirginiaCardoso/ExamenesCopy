<?php 

/**
 * Controlador Crear_Nuevo
 *
 *@package      controllers
 *@author       Cardoso Virginia
 *@author       Marzullo Matias
 *@copyright    Julio, 2015 - Departamento de Ciencias e Ingeniería de la Computación - UNIVERSIDAD NACIONAL DEL SUR 
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Crear_nueva_guia extends CI_Controller {

    private $view_data;
    private $legajo;
    private $privilegio;
    private $redirected = false;  //si redirecciona via AJAX, no hace nada en los metodos
    // private $tabla;
    
	public function __construct()
    {
        parent::__construct();

    
        if($this->usuario->acceso_permitido(PRIVILEGIO_DOCENTE)) 
   		{
            $this->view_data['navbar']['nombre'] = $this->usuario->get_info_sesion_usuario('nom_doc'); 
            $this->view_data['navbar']['apellido'] = $this->usuario->get_info_sesion_usuario('apellido_doc');
            $this->view_data['activo'] = $this->usuario->activo(); 

            $this->legajo = $this->usuario->get_info_sesion_usuario('leg_doc');
            $this->privilegio = $this->usuario->get_info_sesion_usuario('privilegio'); 

            $this->load->model(array('carreras_model','catedras_model','guias_model', 'alumnos_model'));

            // $this->$tabla = $this->crear_tabla_items();

                
        }
        else if($this->usuario->logueado()) //no tiene privilegio, pero esta logueado
        { 
            if($this->input->is_ajax_request())
            {
                $error['error_msj'] = "No tiene permiso para realizar esta acción";
                $this->load->helper('url');
                $error['redirect'] = site_url('home/index/error_privilegio');
                $this->util->json_response(FALSE,STATUS_REDIRECT,$error);
                $this->redirected = true;
            }   
            else
            {
                $this->session->set_flashdata('error', 'No tiene permiso para realizar esta acción');
                redirect('home/index/error_privilegio');
            }

        }
        else
        {
            if($this->input->is_ajax_request())
            {
                $error['error_msj'] = "Sesión caducada. Vuelva a iniciar sesión";
                $this->load->helper('url');
                $error['redirect'] = site_url('login');
                $this->util->json_response(FALSE,STATUS_SESSION_EXPIRED,$error);
                $this->redirected = true;
            }
            else
            {
                $this->session->set_flashdata('error', 'Sesión caducada. Vuelva a iniciar sesión');
                redirect('login');
            }
        }
    }


	/**
     * Redirecciona al controlador correspondiente a la actividad actual del usuario
     *
     * @access  public
     */
    public function index()
    {
        $actividad_actual = $this->usuario->get_actividad_actual();
        //if($actividad_actual == FALSE || $actividad_actual=='generar_examen' )
            redirect('crear_nueva_guia/crear');
        //else
        //    redirect(....)
    }

    /**
     * Controlador de la pagina de seleccion de catedra-guia-alumno
     *  
     * En POST se pueden mandar selecciones default: carrera (codigo), catedra (codigo), guia (id), alumno (lu)
     * 
     * @param $selects array - Arreglo con las opciones seleccionadas por defecto (ej: $selects['carrera'] = codigo)
     * @access  public
     */
    public function crear($selects = NULL)
    {
       
        $this->usuario->set_actividad_actual('crear_examen');

        //Mensaje de error: flashdata en la sesion
        $error = $this->session->flashdata('error');
        if($error)
            $this->view_data['error'] = $error;
      
        
        //FECHA ACTUAL
        $this->view_data['fecha'] = date('d/m/Y'); 
        
        //LISTA CARRERAS
        $carreras = $this->_carreras();
        //DEBUG
        //echo 'Carreras del docente:<br/>';
        //foreach ($carreras as $fila)
        //    var_dump($fila); echo '<br/>';

        if(count($carreras)>0)  //si no hay carreras no manda datos a la view
        {
            $this->view_data['carreras']['list'] = $carreras;  //en la view: $carreras['list'][indice]['cod_carr'].
            $index_carrera = 0; //Por defecto, primer carrera

            //Busco en post si hay carrera seleccionada por default, y actualiza el index seleccionado de la lista
            $cod_carr_default = $this->input->post('carrera') ;
            
            if($cod_carr_default)
            {
                $index_carrera = $this->util->buscar_indice($carreras,'cod_carr',$cod_carr_default);
                //Si el codigo es erroneo, queda no_selected
            }
                
            $this->view_data['carreras']['selected'] = $index_carrera;

            //Si no hay carrera seleccionada, las demas listas están vacías.
            if($index_carrera >= 0 && isset($carreras[$index_carrera]))
            {
                $carrera = $carreras[$index_carrera];   //Carrera seleccionada

                //LISTA CATEDRAS DE LA CARRERA
                $catedras = $this->_catedras($carrera['cod_carr']); 
                //DEBUG
                //echo 'Catedras del docente de '.$carrera['nom_carr'].':<br/>';
                //foreach ($catedras as $fila)
                //    var_dump($fila); echo '<br/>';
                
        
                if(count($catedras)>0)  //si no hay catedras no manda datos a la view
                {
                    $this->view_data['catedras']['list'] = $catedras;  //en la view: $catedras['list'][indice]['cod_cat'].
                    $index_catedra = 0; //Por defecto, primera catedra

                    //Busco en post si hay catedra seleccionada por default, y actualiza el index seleccionado de la lista
                    $cod_cat_default = $this->input->post('catedra') ;
                    if($cod_cat_default)
                    {
                        $index_catedra = $this->util->buscar_indice($catedras,'cod_cat',$cod_cat_default);
                    }
                    
                    $this->view_data['catedras']['selected'] = $index_catedra;

                    //Si no hay catedra seleccionada, las demas listas están vacías.
                    if($index_catedra >= 0 && isset($catedras[$index_carrera]))
                    {
                        $catedra= $catedras[$index_catedra];   //Catedra seleccionada
                                             
                    } //hay catedra seleccionada
                }//hay catedras
            }// hay carrera seleccionada
        }//hay carreras
        
        $this->view_data['title'] = "Crear Guia - Departamento de Ciencias de la Salud";          
        $this->load->view('template/header', $this->view_data);

        $this->load->view('content/crear_nueva_guia/crear', $this->view_data);

        $this->load->view('template/footer');  
    }


    /**
     * Controlador de la pagina de muestra para creacion de guias
     * En POST se reciben las opciones seleccionadas:
     * carrera (codigo), catedra (codigo), 
     * 
     *  @access  public
     */
    public function crear_guia()
    {
        if(!$this->input->post()) 
        {
            $this->session->set_flashdata('error', 'Acceso inválido a la creación de una guia');
            redirect('crear_nueva_guia/crear');
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('carrera', 'Carrera', 'required|integer');
        $this->form_validation->set_rules('catedra', 'Cátedra', 'required|integer');
        $this->form_validation->set_rules('guia', 'Nombre de Guía', 'trim|required');

       // $this->form_validation->set_rules('guia', 'guia', 'required|integer');
        //$this->form_validation->set_rules('alumno', 'alumno', 'required|integer');
       // $this->form_validation->set_rules('fecha', 'fecha', 'required');

        if (!$this->form_validation->run())  //si no verifica inputs
        {
            $this->session->set_flashdata('error', validation_errors());
            redirect('crear_nueva_guia/crear');
        }

        // $items = $this->_items();
         $cod_carr = $this->input->post('carrera'); 
         $cod_cat = $this->input->post('catedra'); 
         $tit_guia = $this->input->post('guia');; 
        // $this->view_data['items']['list']=$items; //en la view: $items['list'][indice]['id_item'].

        //         if ($this->input->post('privilegio')=="superadmin")
        // {
        //     $priv = PRIVILEGIO_SUPERADMIN;
        // }
        // else{
        //     if ($this->input->post('privilegio')=="admin"){
        //         $priv = PRIVILEGIO_ADMIN;
        //     }
        //     else{
        //         $priv = PRIVILEGIO_DOCENTE;
        //      }
        // }

        $id_guia = $this->guias_model->insert_guia_catedra($cod_cat, $tit_guia);
        $this->util->json_response(TRUE,STATUS_OK,$id_guia); 
        
        // $this->view_data['id_guia']=$id_guia;
        // $this->view_data['tit_guia']=$tit_guia;
        // $this->view_data['title'] = "Nueva Guía - Departamento de Ciencias de la Salud";          
        // $this->load->view('template/header', $this->view_data);

        // $this->view_data['evaluar'] = TRUE;
        // $this->load->view('content/crear_nueva_guia/guia', $this->view_data);

        // $this->load->view('template/footer'); 
      // crear_item_guia($id_guia,$tit_guia);
       
        $this->session->set_flashdata('tit',$tit_guia);
        $this->session->set_flashdata('id',$id_guia);
        redirect('crear_nueva_guia/agregar_items_guia');
        
    }

    public function agregar_items_guia(){

        // $tit = $this->session->flashdata('tit');
        // $this->view_data['title'] = "Agregar items a guia Departamento de Ciencias de la Salud"; 

        //Mensaje de error: flashdata en la sesion
        $error = $this->session->flashdata('error');
        

        $this->session->keep_flashdata('tit');
        $this->session->keep_flashdata('id');

        if($error)
            $this->view_data['error'] = $error;  

        $this->usuario->set_actividad_actual('agregar_items_guia'); 
        
        // $grupositems= $this->_grupositems(); 
        // $this->view_data['grupositems']['list']=$grupositems; //en la view: $grupositems['list'][indice]['id_grupoitem'].

         $items = $this->_itemsOrderByNom();  
         $this->view_data['items']['list']=$items; //en la view: $items['list'][indice]['id_item'].
         $this->view_data['items']['selected'] = 0;
 
        $this->view_data['title'] = "Agregar items a guia -  Departamento de Ciencias de la Salud";    
        $this->load->view('template/header', $this->view_data);

        $this->view_data['evaluar'] = TRUE;

        $tabla= $this->crear_tabla_items();
        $this->view_data['tabla'] = $tabla;

        $this->load->view('content/crear_nueva_guia/guia', $this->view_data);

        $this->load->view('template/footer'); 

    }





    

    private function crear_tabla_items(){
       
        $this->load->library('table');
        
        $this->table->set_heading('Nro', 'Texto', 'id', 'New','Eliminar');
        
        $template= array ('table_open'  => '<table id="lista_items_guia" class="table table-striped table-bordered  table-condensed" cellspacing="5" width="100%">');
        $this->table->set_template($template);
        $tabla= $this->table->generate();
        return $tabla;

    }

    function nuevo_item($tabla){
        if(!$this->input->post()) 
        {
            $this->session->set_flashdata('error', 'Acceso inválido a la creación de una guia');
            redirect('crear_nueva_guia/agregar_items_guia');
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('item', 'item', 'required|alpha');

        $texto_item = $this->input->post('item');



       // echo $texto_item;

    }

    /**
     * Devuelve arreglo con los items
     *
     * @access  private
     * @return  array  - lista de items
     */
    function _items() 
    {
        if($this->privilegio>=PRIVILEGIO_ADMIN)  //si es admin muestra todas las carreras
            $items = $this->guias_model->get_items_2();
        
        return $items;
    } 

/**
     * Devuelve arreglo con los items
     *
     * @access  private
     * @return  array  - lista de items
     */
    function _itemsOrderByNom() 
    {
        if($this->privilegio>=PRIVILEGIO_ADMIN)  //si es admin muestra todas las carreras
            $items = $this->guias_model->get_items_3();
        
        return $items;
    } 
    /**
     * Devuelve arreglo con los grupositems
     *
     * @access  private
     * @return  array  - lista de items
     */
    function _grupositems() 
    {
        if($this->privilegio>=PRIVILEGIO_ADMIN)  //si es admin muestra todas las carreras
            $grupositems = $this->guias_model->get_grupositems();
        
        return $grupositems;
    } 

    /**
     * Devuelve arreglo con las carreras correspondientes al usuario
     *
     * @access  private
     * @return  array  - lista de carreras del usuario
     */
    function _carreras() 
    {
        if($this->privilegio>=PRIVILEGIO_ADMIN)  //si es admin muestra todas las carreras
            $carreras = $this->carreras_model->get_carreras();
        else
            $carreras = $this->carreras_model->get_carreras_docente($this->legajo);
        return $carreras;
    }

    /**
     * Devuelve arreglo con las catedras correspondientes al usuario y la carrera elegida
     *
     * @param   $cod_carr int codigo carrera 
     * @access  private
     * @return  array  - lista de catedras del usuario y la carrera
     */
    function _catedras($cod_carr) 
    {
        if($this->privilegio>=PRIVILEGIO_ADMIN)  //si es admin muestra todas las catedras de la carrera
            $catedras = $this->catedras_model->get_catedras_carrera($cod_carr);
        else
           // $catedras = $this->catedras_model->get_catedras_docente_carrera($this->legajo,$cod_carr);
            $catedras = $this->catedras_model->get_catedras_docente_carrera_permiso_modif($this->legajo,$cod_carr);
        return $catedras;

    }

    /**
     * Devuelve arreglo con las guias correspondientes a la catedra elegida
     *
     * @param   $cod_cat int codigo catedra
     * @access  private
     * @return  array  - lista de guias de la catedra elegida
     */
  
    /*  function _guias($cod_cat) 
    {
        return $this->guias_model->get_guias_catedra($cod_cat);
    }*/

    /**
     * Devuelve arreglo con los alumnos correspondientes a la catedra elegida
     *
     * @param   $cod_cat int codigo catedra
     * @access  private
     * @return  array  - lista de alumnos de la catedra elegida
     */
    /*   function _alumnos($cod_cat) 
    {
        return $this->alumnos_model->get_alumnos_catedra($cod_cat);
    }
    */

    /**
     * Controlador de la lista de catedras (accedido mediante AJAX). Retorna JSON
     *  
     * En POST se envia parametro: carrera (codigo)
     * 
     * @access  public
     */
    public function get_catedras()
    {
        if(!$this->redirected)
        {
            $cod_carr = $this->input->post('carrera') ;
            if($cod_carr)
            {
                $catedras = $this->_catedras($cod_carr); 
                if(count($catedras)>0)
                    $this->util->json_response(TRUE,STATUS_OK,$catedras);    
                else
                    $this->util->json_response(FALSE,STATUS_INVALID_PARAM,""); 
            }
            else
                $this->util->json_response(FALSE,STATUS_EMPTY_POST,"");
        }
    }

    /**
     * Controlador de la lista de guias y alumnos (accedido mediante AJAX). Retorna JSON
     *  
     * En POST se envia parametro: catedra (codigo)
     * 
     * @access  public
     */
  /*  public function get_guias_alumnos()
    {
        if(!$this->redirected)
        {
            $cod_cat = $this->input->post('catedra') ;
            if($cod_cat)
            {
                $guias = $this->_guias($cod_cat); 
                $alumnos = $this->_alumnos($cod_cat); 
                
                $this->util->json_response(TRUE,STATUS_OK,array('guias' => $guias,'alumnos' => $alumnos));    
                
            }
            else
                $this->util->json_response(FALSE,STATUS_EMPTY_POST,"");
        }
    }*/

    /**
     * Devuelve arreglo con los items de la guia especificada (y el estado si es examen), con la siguiente estructura:
     *
     * ITEMS DE LA GUIA (ARREGLO, AGRUPADOS POR SECCION Y GROUP_ITEM). 
     * Pide al modelo, en base al id.
     * Arma arreglo para pasar a la en la vista en $guia['items'], agrupados en subarreglos así:
     * $guia( [[datos]], [items] => 
     *                  ([pos] =>([tipo]=> seccion-grupoitem-item, [nro], [nom],
     *                              (si tipo es item):[id],[solo_texto],
     *                              (si tipo es seccion o grupo):[items] => 
     *                    ([pos] => ([tipo]=>grupoitem-item, [nro], [nom],
     *                                  (si tipo es item):[id],[solo_texto],
     *                                  (si tipo es grupo):[items] => 
     *                      ([pos] => ([tipo]=>item,[nro],[nom],[id],[solo_texto])) )) )) )
     * @param   $id_guia int id guia
     * @param   $examen bool agregar datos examen 
     * @access  private
     * @return  array 
     */
  /*  function _itemsguia($id,$examen) 
    {
        if($examen)
            $lista_items = $this->examenes_model->get_items($id);
        else
            $lista_items = $this->guias_model->get_items($id);
        
        $lista = array();
        $k = 0;
        for ($i=0; $i < count($lista_items); $i++)
        { 
            $item_completo = $lista_items[$i];
            if($item_completo['nom_sec'])  // si el item está dentro de una sección
            {
                //inserto seccion
                $nro_seccion = $item_completo['nro_sec'];
                $lista[$k] = array('tipo' => 'seccion',
                                    'nro' => $nro_seccion,
                                    'nom' => $item_completo['nom_sec']);
                                    //'items' => array(...) se agrega desp de recorrer los items del grupo
                //lista de items dentro de la seccion. 
                $j = 0; $items = array(); 
                $avanzo = true;
                while ($avanzo)
                {
                    if ($item_completo['nom_grupoitem']) //si el item esta dentro de un grupoitem dentro de una seccion
                    {
                        //inserto grupoitem dentro de la seccion
                        $nro_grupo = $item_completo['nro_grupoitem'];
                        $items[$j] = array('tipo' => 'grupoitem',
                                    'nro' => $nro_seccion.'.'.$nro_grupo,
                                    'nom' => $item_completo['nom_grupoitem']);
                                    //'items' => array(...) se agrega desp de recorrer los items del grupo

                        //lista de items dentro del grupo dentro de la seccion. 
                        $j2 = 0; $items2 = array(); 
                        $avanzo2 = true;
                        while ($avanzo2)
                        {
                            $items2[$j2] = array('tipo' => 'item',
                                            'nro' => $nro_seccion.'.'.$nro_grupo.'.'.$item_completo['nro_item'],
                                            'nom' => $item_completo['nom_item'],
                                            'id' => $item_completo['id_item'],
                                            'solo_texto' => $item_completo['solo_texto']);
                            if($examen) 
                            {    
                                $items2[$j2]['estado'] = $item_completo['estado_item'];
                                $items2[$j2]['obs'] = $item_completo['obs_item'];
                            }
                            $avanzo2 = $i+1 < count($lista_items) && $lista_items[$i+1]['nro_grupoitem'] == $nro_grupo && $lista_items[$i+1]['nro_sec'] == $nro_seccion;
                            if($avanzo2)
                            {
                                $i++;
                                $item_completo = $lista_items[$i];
                                $j2++;
                            }                        
                        }
                        $items[$j]['items'] = $items2;
                        $j++;
                    }
                    else //item suelto dentro de la seccion (sin grupo item)
                    {
                        $items[$j] = array('tipo' => 'item',
                                    'nro' => $nro_seccion.'.'.$item_completo['nro_item'],
                                    'nom' => $item_completo['nom_item'],
                                    'id' => $item_completo['id_item'],
                                    'solo_texto' => $item_completo['solo_texto']);  
                        if($examen) 
                        {    
                            $items[$j]['estado'] = $item_completo['estado_item'];
                            $items[$j]['obs'] = $item_completo['obs_item'];
                        }       
                    }
                    $avanzo = $i+1 < count($lista_items) && $lista_items[$i+1]['nro_sec'] == $nro_seccion;
                    if($avanzo)
                    {
                        $i++;
                        $item_completo = $lista_items[$i];
                        $j++;
                    }     

                            
                }
                                
                $lista[$k]['items'] = $items;
                $k++;
            }
            elseif ($item_completo['nom_grupoitem']) //si el item esta dentro de un grupoitem
            {
                //inserto grupoitem
                $nro_grupo = $item_completo['nro_grupoitem'];
                $lista[$k] = array('tipo' => 'grupoitem',
                                    'nro' => $nro_grupo,
                                    'nom' => $item_completo['nom_grupoitem']);
                                    //'items' => array(...) se agrega desp de recorrer los items del grupo
                //lista de items dentro del grupo. 
                $j = 0; $items = array(); 
                $avanzo = true;
                while ($avanzo)
                {
                    $items[$j] = array('tipo' => 'item',
                                    'nro' => $nro_grupo.'.'.$item_completo['nro_item'],
                                    'nom' => $item_completo['nom_item'],
                                    'id' => $item_completo['id_item'],
                                    'solo_texto' => $item_completo['solo_texto']);
                    if($examen) 
                    {    
                        $items[$j]['estado'] = $item_completo['estado_item'];
                        $items[$j]['obs'] = $item_completo['obs_item'];
                    }
                    $avanzo = $i+1 < count($lista_items) && $lista_items[$i+1]['nro_grupoitem'] == $nro_grupo;
                    if($avanzo)
                    {
                        $i++;
                        $item_completo = $lista_items[$i];
                        $j++;
                    }                        
                }
                                
                $lista[$k]['items'] = $items;
                $k++;
            }
            else //si el ítem está suelto (sin sección ni grupo)
            {
                //inserto item directamente
                $lista[$k] = array('tipo' => 'item',
                                    'nro' => $item_completo['nro_item'],
                                    'nom' => $item_completo['nom_item'],
                                    'id' => $item_completo['id_item'],
                                    'solo_texto' => $item_completo['solo_texto']);
                if($examen) 
                {    
                    $lista[$k]['estado'] = $item_completo['estado_item'];
                    $lista[$k]['obs'] = $item_completo['obs_item'];
                }
                $k++;
            }
        }

        return $lista;

    }
*/

    

    // /**
    //  * Controlador de la pagina de muestra de la guia para evaluar
    //  *  
    //  * En POST se reciben las opciones seleccionadas:
    //  * carrera (codigo), catedra (codigo), guia (id), alumno (lu), fecha (timestamp)
    //  * 
    //  * @param $seleccion array - Arreglo con las opciones seleccionadas (ej: $seleccion['carrera'] = codigo)
    //  * @access  public
    //  */
    // public function crear_guia()
    // {
    //     if(!$this->input->post()) 
    //     {
    //         $this->session->set_flashdata('error', 'Acceso inválido a la creación de una guia');
    //         redirect('crear_nueva_guia/crear');
    //     }
    //     $this->load->library('form_validation');
    //     $this->form_validation->set_rules('carrera', 'Carrera', 'required|integer');
    //     $this->form_validation->set_rules('catedra', 'Cátedra', 'required|integer');
    //     $this->form_validation->set_rules('guia', 'Nombre de Guía', 'trim|required');

    //    // $this->form_validation->set_rules('guia', 'guia', 'required|integer');
    //     //$this->form_validation->set_rules('alumno', 'alumno', 'required|integer');
    //    // $this->form_validation->set_rules('fecha', 'fecha', 'required');

    //     if (!$this->form_validation->run())  //si no verifica inputs
    //     {
    //         $this->session->set_flashdata('error', validation_errors());
    //         redirect('crear_nueva_guia/crear');
    //     }

    //     $items = $this->_items();
    //      $cod_carr = $this->input->post('carrera'); 
    //      $cod_cat = $this->input->post('catedra'); 
    //      $tit_guia = $this->input->post('guia');; 
    //     $this->view_data['items']['list']=$items; //en la view: $items['list'][indice]['id_item'].

    //             if ($this->input->post('privilegio')=="superadmin")
    //     {
    //         $priv = PRIVILEGIO_SUPERADMIN;
    //     }
    //     else{
    //         if ($this->input->post('privilegio')=="admin"){
    //             $priv = PRIVILEGIO_ADMIN;
    //         }
    //         else{
    //             $priv = PRIVILEGIO_DOCENTE;
    //          }
    //     }

    //     $id_guia = $this->guias_model->set_guia_catedra($cod_cat, $tit_guia);
    //     $this->util->json_response(TRUE,STATUS_OK,$id_guia); 
        
    //     $this->view_data['id_guia']=$id_guia;
    //     $this->view_data['tit_guia']=$tit_guia;
    //     $this->view_data['title'] = "Nueva Guía - Departamento de Ciencias de la Salud";          
    //     $this->load->view('template/header', $this->view_data);

    //     $this->view_data['evaluar'] = TRUE;
    //     $this->load->view('content/crear_nueva_guia/guia', $this->view_data);

    //     $this->load->view('template/footer'); 
    //   // crear_item_guia($id_guia,$tit_guia);
        
    // }

    // /**
    //  * Controlador de la accion ver un examen guardado
    //  *  
    //  * En GET (parametro) se recibe solo el id del examen
    //  *
    //  * Carga vista del examen
    //  * 
    //  * @access  private
    //  * @param   $id int id del examen deseado
    //  */
    // private function crear_item_guia($id_guia,$tit_guia)
    // {
    //     $this->view_data['id_guia']=$guia_nueva;
    //     $this->view_data['tit_guia']=$tit_guia;
    //     $this->view_data['title'] = "Nuevos Items Guía - Departamento de Ciencias de la Salud";          
    //     $this->load->view('template/header', $this->view_data);

    //     $this->view_data['evaluar'] = TRUE;
    //     $this->load->view('content/crear_nueva_guia/guia', $this->view_data);

    //     $this->load->view('template/footer');
    // }

    


    /**
     * Controlador de la accion ver un examen guardado
     *  
     * En GET (parametro) se recibe solo el id del examen
     *
     * Carga vista del examen
     * 
     * @access  public
     * @param   $id int id del examen deseado
     */
    public function ver($id)
    {
        $this->_cargar_datos_examen($id);    

        $this->load->view('template/header', $this->view_data);

        $this->load->view('content/examen/examen', $this->view_data);

        $this->load->view('template/footer');
    }

    /**
     * Controlador de la accion generar un archivo PDF a partir un examen guardado
     *  
     * En GET (parametro) se recibe solo el id del examen
     *
     * Genera el archivo PDF
     * 
     * @access  public
     * @param   $id int id del examen deseado
     */
    public function pdf($id)
    {
        $this->load->helper(array('dompdf', 'file'));

        $this->_cargar_datos_examen($id);
    
        $html = $this->load->view('content/examen/examen_pdf', $this->view_data, true);


        $file_name = 'ex_'.$id.'_'.$this->view_data['catedra']['cod_cat'].'_'.$this->view_data['docente']['leg_doc'].'_'.$this->view_data['alumno']['lu_alu'];

        pdf_create($html, $file_name);

    }

   }

/* Fin del archivo crear_nueva_guia.php */
/* Ubicación: ./application/controllers/crear_nueva_guia.php */