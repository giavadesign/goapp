/* ===================================================
function load data into table
===================================================*/
function goapp_refresh_tabulator( obj ){
  var fieldEl = document.getElementById("filter-field");
  var typeEl = document.getElementById("filter-type");
  var valueEl = document.getElementById("filter-value");

  //Custom filter example
  function customFilter(data){
      return data.car && data.rating < 3;
  }


  

  //Trigger setFilter function with correct parameters
  function updateFilter(){
    
    var filterVal = fieldEl.options[fieldEl.selectedIndex].value;
    var typeVal = typeEl.options[typeEl.selectedIndex].value;

    var filter = filterVal == "function" ? customFilter : filterVal;

    if(filterVal == "function" ){
      typeEl.disabled = true;
      valueEl.disabled = true;
    }else{
      typeEl.disabled = false;
      valueEl.disabled = false;
    }




    if(filterVal != '' ){
      $('#filter-value').on('keyup',function(e){
        
        if( valueEl.value.trim() != '' || ( valueEl.value == '' && ( e.which == 8 || e.which == 46 ) ) ){
            table.setFilter(filter,typeVal, valueEl.value.trim());
        }

      });
    }
  }

  //Update filters on value change
  document.getElementById("filter-field").addEventListener("change", updateFilter);
  document.getElementById("filter-type").addEventListener("change", updateFilter);
  document.getElementById("filter-value").addEventListener("keyup", updateFilter);

  //Clear filters on "Clear Filters" button click
  document.getElementById("filter-clear").addEventListener("click", function(){
    fieldEl.value = "";
    typeEl.value = "=";
    valueEl.value = "";

    table.clearFilter();
  });

  var table = new Tabulator("#goapp_main_table", {

    data:obj, 
    layout:"fitColumns",
    rowHeight: 50,
    responsiveLayout:true,
    columns:[
      { title:"Cognome", field:"Surname", responsive:0 },
      { title:"Nome", field:"Name", responsive:0 },
      { title:"Azienda", field:"Azienda", responsive:2 },
      { title:"Ruolo", field:"Role", responsive:2 },
      { title:"Recapiti", field:"category",responsive:3 },
      { title: "Azioni",  width:100, responsive:0, field: "Id",
       formatter: function(cell, formatterParams, onRendered){
        console.log(cell)
        /*
        return '<div class="goapp_tabulator_actions"><a href="#" class="goapp_view_user_detail_trigger" data-id_item="' + cell.getValue() + '"><i class="icon-search"></i></a><a class="goapp_update_user_trigger" href="#" data-id_item="' + cell.getValue() + '"><i class="icon-pencil2"></i></a><a class="goapp_delete_user_trigger" data-id_item="' + cell.getValue() +'" href="#"><i class="icon-bin"></i></a></div>';
        */
        return '<div class="goapp_tabulator_actions"><a class="goapp_delete_user_trigger" data-id_item="' + cell.getValue() +'" href="#"><i class="icon-bin"></i></a></div>';
       }
      }
    ],
    pagination:"local",
    paginationSize:30,
    paginationSizeSelector:[3, 6, 8, 10,20,30],
    movableColumns:true,
    paginationCounter:"rows",
  });
}
/* =================== end function =========================== */