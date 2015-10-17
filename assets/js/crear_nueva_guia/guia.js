
/*  
    AUTOR       Cardoso Virginia
    AUTOR       Marzullo Matias
    COPYRIGHT   Septiembre 2015 - Departamento de Ciencias e Ingeniería de la Computación - UNIVERSIDAD NACIONAL DEL SUR 
*/

$(document).ready(function() {
    crearDataTable();
    
    event_handlers_window();
    $('#navbar-administracion').parent().addClass('active');

    $('[data-toggle="tooltip"]').tooltip();

    $(window).resize(); // Disparo el evento para que el contenido quede centrado.
});


function crearDataTable() {
    
      // $('#lista_usuarios').DataTable(); //datateble original
    $('#lista_items_guia').dataTable({ //datatable personalizado
        
        "columnDefs": [
            {
                "targets": [ 2,3 ],
                "visible": false,
                "searchable": false
            }
            ]
    });

    $('#lista_items_guia').removeClass('display')
        .addClass('table table-striped table-bordered');

    var table = $('#lista_items_guia').DataTable();
 
    // $('#lista_items_guia tbody tr').click(
    //     function () {
    //         //$( this ).addClass( "active" );   
    //         document.location = table.row(this).data()[8];
    //     } 
    // )
    // .css( 'cursor', 'pointer' )
    // .hover(
    //     function() {
    //         $( this ).addClass( "info" );
    //     },
    //     function() {
    //         $( this ).removeClass( "info" );
    //     }
    // );  
}



/*  EVENT HANDLERS */

function event_handlers_window() {

    $(window).resize(function() {
        calculos_visualizacion();
    });
}

// function guardar_tabla(tableID){


// //EJEMPLO

//                     // // Create an object using an object literal.
//                     // var ourObj = {};

//                     // // Create a string member called "data" and give it a string.
//                     // // Also create an array of simple object literals for our object.
//                     // ourObj.data = "Some Data Points";
//                     // ourObj.arPoints = [{'x':1, 'y': 2},{'x': 2.3, 'y': 3.3},{'x': -1, 'y': -4}];

//                     // $.ajax({
//                     //    url: 'process-data.php',
//                     //   type: 'post',
//                     //   data: {"points" : JSON.stringify(ourObj)},
//                     //   success: function(data) {
//                     //       // Do something with data that came back. 
//                     //   }
//                     // });

//                     // // Test if our data came through
//                     //   if (isset($_POST["points"])) {
//                     //   // Decode our JSON into PHP objects we can use
//                     //   $points = json_decode($_POST["points"]);

//                     //   // Access our object's data and array values.
//                     //   echo "Data is: " . $points->data . "<br>";
//                     //   echo "Point 1: " . $points->arPoints[0]->x . ", " . $points->arPoints[0]->y;
//                     // }

// //----------------------------------------------------------

//   var arreglo_items = [];

//   var table = document.getElementById(tableID);

//   var rowCount = table.rows.length;

//  for (var i = 0; i < rowCount; i++) {
//       arreglo_items[i]= {'nro': table.rows[i].cells[0], 'texto': table.rows[i].cells[1],'id': table.rows[i].cells[2],'new':table.rows[i].cells[3]};

//  }


//   $.ajax({
//       url: $('body').data('site-url')+"/crear_nueva_guia/guardar_guia", // controlador
//        type: 'post'
//       // , contentType: 'application/json'
//       , dataType: 'json'
//       , data: {'arreglo_items': JSON.stringify(arreglo_items)}

//       ,success: function() {
//          alert("correcto");
//        }
//   });
  
 
// }

function addRow(tableID) {

               var table = document.getElementById(tableID);

               var rowCount = table.rows.length;

               var row = table.insertRow(rowCount);

               var text =  document.getElementById("item");

                var cell0 = row.insertCell(0);
               cell0.innerHTML = rowCount;

               var cell1 = row.insertCell(1);
               cell1.innerHTML = text.value;

               var cell2 = row.insertCell(2);
               cell2.innerHTML = 0;

                var cell3 = row.insertCell(3);
                cell3.innerHTML = 1; //nuevo

                var cell4 = row.insertCell(4);
                cell4.innerHTML = "<div class='boton-eliminar'><a class='btn btn-danger btn-xs' data-toggle='tooltip' data-placement='bottom' title='Eliminar'  onclick='delRow("+rowCount+");'><span class='glyphicon glyphicon-trash grande'></span> </a></div>";

                var formulario = document.getElementById("form-crear-guia");

                var x = document.createElement("INPUT");
                x.setAttribute("type", "hidden"); 
                x.name="item-tipo[]";
                x.id ="input-tipo-"+cell0.innerHTML;
                x.value="item-nuevo";
                formulario.appendChild(x);

                var y = document.createElement("INPUT");
                y.setAttribute("type", "hidden"); 
                y.name="item-id[]";
                y.id ="input-id-"+cell0.innerHTML;
                y.value="0";
                formulario.appendChild(y);

                var z = document.createElement("INPUT");
                z.setAttribute("type", "hidden"); 
                z.name="item-texto[]";
                z.id ="input-texto-"+cell0.innerHTML;
                z.value=text.value;
                formulario.appendChild(z);


                text.value= "";
                text.placeholder = "Ingrese texto del item";



}

function addRow2(tableID) {

               var table = document.getElementById(tableID);

               var rowCount = table.rows.length;

               var row = table.insertRow(rowCount);

                var cell0 = row.insertCell(0);
               cell0.innerHTML = rowCount;

                var select = document.getElementById("select-item");
               var cell1 = row.insertCell(1);
               cell1.innerHTML = select.options[select.selectedIndex].text;

               var cell2 = row.insertCell(2);
                cell2.innerHTML = select.value;

               var cell3 = row.insertCell(3);
                cell3.innerHTML = 0; //no nuevo
                
               var cell4 = row.insertCell(4);
                cell4.innerHTML = "<div class='boton-eliminar'><a class='btn btn-danger btn-xs' data-toggle='tooltip' data-placement='bottom' title='Eliminar'  onclick='delRow("+rowCount+");'><span class='glyphicon glyphicon-trash grande'></span> </a></div>";

                var formulario = document.getElementById("form-crear-guia");

                // var x = document.createElement("INPUT");
                // x.setAttribute("type", "hidden"); 
                // x.name="item-id[]";
                // x.id ="input-item-"+select.value;
                // x.value=select.value;
                // formulario.appendChild(x);

                var x = document.createElement("INPUT");
                x.setAttribute("type", "hidden"); 
                x.name="item-tipo[]";
                x.id ="input-tipo-"+cell0.innerHTML;
                x.value="item-bd";
                formulario.appendChild(x);

                var y = document.createElement("INPUT");
                y.setAttribute("type", "hidden"); 
                y.name="item-id[]";
                y.id ="input-id-"+cell0.innerHTML;
                y.value=select.value;
                formulario.appendChild(y);

                var z = document.createElement("INPUT");
                z.setAttribute("type", "hidden"); 
                z.name="item-texto[]";
                z.id ="input-texto-"+cell0.innerHTML;
                z.value=select.options[select.selectedIndex].text;
                formulario.appendChild(z);

                // <input type='hidden' name='item-id[]' id='input-item-{$item['id']}' value='{$item['id']}'/>

}

function delRow(nroFila) {

     var table = document.getElementById('lista_items_guia');
     table.deleteRow(nroFila); //elimino la fila de la tabla

     //elimino los input de esa fila
     var inputtipo = document.getElementById("input-tipo-"+nroFila);
     inputtipo.parentNode.removeChild(inputtipo);
     var inputid = document.getElementById("input-id-"+nroFila);
     inputid.parentNode.removeChild(inputid);
     var inputtexto = document.getElementById("input-texto-"+nroFila);
     inputtexto.parentNode.removeChild(inputtexto);


     //actualizar nro item
     var rowCount = table.rows.length;
    for (i = nroFila; i < rowCount; i++) {
        // document.getElementById("myTable").rows[0].cells[0].innerHTML ="hi";
        // if(i!=0){
            table.rows[i].cells[0].innerHTML = i;
            table.rows[i].cells[4].innerHTML = "<div class='boton-eliminar'><a class='btn btn-danger btn-xs' data-toggle='tooltip' data-placement='bottom' title='Eliminar'  onclick='delRow("+i+");'><span class='glyphicon glyphicon-trash grande'></span> </a></div>";
          // }
    }

    //actualizar nombre de los input hiden
    for (j=nroFila+1; j<=rowCount; j++){
      k=j-1;
      var inputtipo2 = document.getElementById("input-tipo-"+j);
      inputtipo2.id="input-tipo-"+k;
     
      var inputid2 = document.getElementById("input-id-"+j);
      inputid2.id="input-id-"+k;
    
      var inputtexto2 = document.getElementById("input-texto-"+j);
      inputtexto2.id="input-texto-"+k;
     

    }





  }
