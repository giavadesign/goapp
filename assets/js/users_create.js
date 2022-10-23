function goapp_user_create(data){
  
  $.ajax({
    type: "POST",
    url: 'api/v1/users',
    data: data,
    contentType: 'application/json',
    processData: false,
    dataType:'json',
    beforeSend: function (xhr) {
      goapp_preloader_show();
      xhr.setRequestHeader('Authorization', 'Bearer ' + sessionStorage.getItem("goapp_jwt_token") );
    },
    complete: function(){
      goapp_preloader_hide();
    },
    success: function (out) {
      console.log(out);
      goapp_users_get_all();
      goapp_json_viewer(out.responseJSON);

      let alert_icon =( out.success ) ? 'success' : 'error' ;
      let alert_title = ( out.success ) ? 'Congratulazioni' : 'Attenzione' ;

      Swal.fire({
        icon: alert_icon ,
        title: alert_title ,
        text: out.messages[0],
        footer: 'Status code: <b> ' + out.status_code + ' </b>'
      });
      $('#goapp_modal_insert').find('input[type="text"]').val('');
      $('#goapp_modal_insert').find('input[type="email"]').val('');
      $('#goapp_modal_insert').find('input[type="password"]').val('');
      $('#goapp_modal_insert').modal('hide')
    },
    error: function (e) {    
      goapp_preloader_hide();
      $("#goapp_frm_insert_submit_button").prop("disabled", false);
      goapp_json_viewer(e.responseJSON);
      Swal.fire({
        icon: 'error' ,
        title: 'Attenzione' ,
        text: e.responseJSON.message,
        footer: 'Status code: <b> '+e.responseJSON.status_code+' </b>'
      });
    }
  });
}