var f_goapp_frm_login_name = 'goapp_frm_login';
var f_goapp_frm_login = $('form[name="' + f_goapp_frm_login_name + '"]');

$(document).delegate('#goapp_frm_login' , 'submit', function(e){
  e.preventDefault();

  var the_form = $(this);
  var the_form_data = the_form.serialize();
  var the_url = 'api/v1/login';

  $.ajax({
    type: "POST",
    url: the_url,
    dataType:'json',
    data: the_form_data,
    success: function (out) {
      console.log(out);
      if( out.success === true ){
        sessionStorage.setItem("goapp_jwt_token", out.data.goapp_jwt_token );

        goapp_check_logged_user( 1000 );
      }
      else{ sessionStorage.removeItem("goapp_jwt_token"); }
    },
    error: function (e) {        
      console.log(e)
      $("#goapp_frm_login").find('button[type="submit"]').prop("disabled", false);
      
      Swal.fire({
        icon: 'error' ,
        title: 'Attenzione' ,
        text: e.responseJSON.message,
        footer: 'Status code: <b>' + e.responseJSON.status_code + '</b>'
      });
    }
  });

});