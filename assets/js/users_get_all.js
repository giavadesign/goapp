function goapp_users_get_all(){
  if( sessionStorage.getItem("goapp_jwt_token") !== null){
    $.ajax({
      type: "GET",
      url: 'api/v1/users',
      dataType:'json',
      contentType: 'application/json',
      beforeSend: function (xhr) {
        xhr.setRequestHeader('Authorization', 'Bearer ' + sessionStorage.getItem("goapp_jwt_token") );
      },
      success: function( out ){
        console.log( out );
        goapp_json_viewer(out);
        goapp_refresh_tabulator( out.data.result );
      },
      error: function(e){
        Swal.fire({
          icon: 'error' ,
          title: 'Attenzione' ,
          text: e.responseJSON.message,
          footer: 'Status code: <b>' + e.responseJSON.status_code + '</b>'
        }).then( (result) => {
          sessionStorage.removeItem("goapp_jwt_token");
          $('#goapp').load('Views/login.html');
        });
      }
    });
  }
}