/* ===================================================
function load data into category select 
===================================================*/
function goapp_json_viewer( json_obj ){
  var jsonViewer = new JSONViewer();
  if( $("#goapp_json").length) {
    $("#goapp_json").empty();
    $("#goapp_json").append(jsonViewer.getContainer());
    jsonViewer.showJSON(json_obj);
  }
}
/* =================== end function =========================== */

/* ===================================================
function preloader 
===================================================*/
function goapp_preloader_show(){
  if( $('#goapp_overlay').length ){ $('#goapp_overlay').fadeIn(10); } 
  if( $('.goapp_spinner_wrapper').length ){ $('.goapp_spinner_wrapper').fadeIn(10); }
}
function goapp_preloader_hide( loader_time = 0 ){

  if( ( typeof loader_time == 'number' && loader_time != 0 ) || loader_time != '' ){
    setTimeout( function(){
      if( $('#goapp_overlay').length ){ $('#goapp_overlay').fadeOut('slow'); } 
      if( $('.goapp_spinner_wrapper').length ){ $('.goapp_spinner_wrapper').fadeOut('fast'); }
    }, loader_time );
  }
  else{
    if( $('#goapp_overlay').length ){ $('#goapp_overlay').fadeOut('slow'); } 
    if( $('.goapp_spinner_wrapper').length ){ $('.goapp_spinner_wrapper').fadeOut('fast'); }
  }

}
/* =================== end function =========================== */

/* ===================================================
function check_logged_user 
===================================================*/
function goapp_check_logged_user( loader_time = 0){

  goapp_preloader_show();

  if( sessionStorage.getItem("goapp_jwt_token") === null){ $('#goapp').load('Views/login.html'); }
  else{ 
    $('#goapp').load('Views/goapp.html', function(){ 
      
      goapp_users_get_all();
      


      /* ===================================================
      logout 
      ====================================================*/
      if( $('#goapp_logout_trigger').length ){
        $('#goapp_logout_trigger').on('click',function(e){
          e.preventDefault();
          sessionStorage.removeItem("goapp_jwt_token");
          location.reload();
        });
      }
      /* =================== logout ===================== */
      
      /* ===================================================
      form inserimento 
      ====================================================*/
      if( $('#goapp_frm_insert_user').length ){
        $('#goapp_frm_insert_user').parsley();

        $('form[name="goapp_frm_insert_user"]').on( 'submit' , function(e){
          e.preventDefault();
          var the_elem = $(this);
          //var data = the_elem.serialize();
          var data = JSON.stringify({
            "username" : $('input[name="user_username"]').val(),
            "password" : $('input[name="user_password"]').val(),
            "name" : $('input[name="user_name"]').val(),
            "surname" : $('input[name="user_surname"]').val(),
            "company" : $('input[name="user_company"]').val(),
            "role" : $('input[name="user_role"]').val(),
            "email" : $('input[name="user_email"]').val()
          });
          goapp_user_create(data)
        });

      }
      /* =================== json viewer ================ */


      /* ===================================================
      json viewer 
      ====================================================*/
      if( $('#goapp_json_panel_trigger').length ){
        $('#goapp_json_panel_trigger').on( 'click' , function(e){
          e.stopPropagation();
          e.preventDefault();
          if( $('#goapp_json_wrapper' ).length ){
            $('#goapp_json_wrapper').toggleClass('active');
            $('#goapp_json_panel_trigger').toggleClass('rotate');
          }
        });
      } 
      /* =================== json viewer ================ */
    });
  }

  goapp_preloader_hide( loader_time );
}
/* =================== end function =========================== */


$(window).on('load', function(){
  
  goapp_check_logged_user();

  
}); 


$(document).ajaxComplete(function() {
  
  //parsley form
  if( $( '#goapp_frm_login' ).length ){
    $('#goapp_frm_login').parsley();
  } 

});

$(function(){

  

});
