
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
                cell3.innerHTML = 0; //nuevo

                var cell4 = row.insertCell(4);
                cell4.innerHTML = "<div class='boton-eliminar'><a class='btn btn-danger btn-xs' data-toggle='tooltip' data-placement='bottom' title='Eliminar'  onclick='delRow("+rowCount+");'><span class='glyphicon glyphicon-trash grande'></span> </a></div>";

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
                cell3.innerHTML = 1; //no nuevo
                
               var cell4 = row.insertCell(4);
                cell4.innerHTML = "<div class='boton-eliminar'><a class='btn btn-danger btn-xs' data-toggle='tooltip' data-placement='bottom' title='Eliminar'  onclick='delRow("+rowCount+");'><span class='glyphicon glyphicon-trash grande'></span> </a></div>";


}

function delRow(nroFila) {

     var table = document.getElementById('lista_items_guia');
     table.deleteRow(nroFila);

     //actualizar nro item
     var rowCount = table.rows.length;
    for (i = 0; i < rowCount; i++) {
        // document.getElementById("myTable").rows[0].cells[0].innerHTML ="hi";
        if(i!=0)
            table.rows[i].cells[0].innerHTML = i;
            table.rows[i].cells[4].innerHTML = "<div class='boton-eliminar'><a class='btn btn-danger btn-xs' data-toggle='tooltip' data-placement='bottom' title='Eliminar'  onclick='delRow("+i+");'><span class='glyphicon glyphicon-trash grande'></span> </a></div>";
    }
     

}