$(function(){
  /* ===================================================
    cancellazione user
  ===================================================*/
  $(document).delegate('a.goapp_delete_user_trigger' , 'click',function(e){
    e.preventDefault();
    var the_elem = $(this);
    var the_id = the_elem.data('id_item');

    $.ajax({
      type: "DELETE",
      url: "api/v1/users/" + the_id,
      dataType:'json',
      contentType: 'application/json',
      processData: false,
      beforeSend: function (xhr) {
        goapp_preloader_show();
        xhr.setRequestHeader('Authorization', 'Bearer ' + sessionStorage.getItem("goapp_jwt_token") );
      },
      complete: function(){
        goapp_preloader_hide();
      },
      success: function (out) {

        console.log(out)
        
        goapp_json_viewer(out);
       
        let alert_icon =( out.success ) ? 'success' : 'error' ;
        let alert_title = ( out.success ) ? 'Congratulazioni' : 'Attenzione' ;

        Swal.fire({
          icon: alert_icon ,
          title: alert_title ,
          text: out.messages[0],
          footer: 'Status code: <b> ' + out.status_code + ' </b>'
        });

        goapp_users_get_all();
      },
      error: function(e) {
        console.log(e);
        goapp_json_viewer(e.responseJSON);
        
        console.log(e);
        Swal.fire({
          icon: 'error' ,
          title: 'Attenzione' ,
          text: e.responseJSON.message,
          footer: 'Status code: <b> '+e.responseJSON.status_code+' </b>'
        })
      }
    });
    
  });
  /* =================== end cancellazione user =========================== */
});