  <?php require_once('header_menu_sectionLogo.php'); ?> 
        <?php require_once('header_menu.phpV1.php'); ?>

  <div
          class="page-wrapper d-flex justify-content-center align-items-center"
        >
          <div class="container container-tight py-4">
          
            <div class="card card-md">
              <div class="card-body">
                <h2 class="h2 text-center mb-4">Login to your account</h2>
                <form id="login-form" action="./" method="get" autocomplete="off" novalidate="">
                  <div class="mb-3">
                    <label class="form-label">Email address</label>
                    <input
                      type="email"
                      class="form-control"
                      placeholder="Your email"
                      autocomplete="off"
                      name="login_email"
                      id="login_email"
                    />
                  </div>
                  <div class="mb-3 ">
    <label class="form-label">Password</label>
    <input type="password" class="form-control" placeholder="Password" autocomplete="off" name="login_password" id="login_password">
</div>
                  <div class="mb-3">
                    <label class="form-check">
                      <input type="checkbox" class="form-check-input" />
                      <span class="form-check-label"
                        >Mostrar contraseña</span
                      >
                    </label>
                  </div>
                  <div class="form-footer">
                    <button  id="login_button"type="button" class="btn btn-primary w-100">
                      Sign in
                    </button>
                  </div>
                </form>
              </div>
              <div class="hr-text">or</div>
            
            </div>
            <div class="text-center text-muted mt-3">
              Don't have account yet?
              <a href="./register.php" tabindex="-1">Sign up</a>
            </div>
          </div>
        </div>
        <?php require_once('footerV1.php'); ?> 

        
        <script>




  $(document).ready(function() {
    
      
      $("#login-form").validate({
        rules: {
            login_email: {
                required: true,
                email: true,
            },
            login_password: {
                required: true,
            },
        },
        messages: {
            login_email: {
                required: "Por favor ingrese un email",
                email: "Por favor ingrese un email válido",
            },
            login_password: {
                required: "Por favor ingrese su contraseña",
            },
        },
          errorElement: "div",
          errorPlacement: function(error, element) {
              error.addClass("error");
              var container = $("<div>").addClass("error-container");
              container.insertAfter(element);
              error.appendTo(container);
          },
          highlight: function(element) {
              $(element).addClass("error");
          },
          unhighlight: function(element) {
              $(element).removeClass("error");
          },
      });
  });

  $('#login_button').click(function() {

if (
  $("#login-form").valid()
 
) {
console.log("Paso");


}else{

console.log("No paso");  
}
});


  // // function validarCorreoElectronico(correo) {
      
  // //     var regCorreo= /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    
  // //     return regCorreo.test(correo);
  // // }
  // function enviarMensaje(){

  // $mail_setFromEmail=$('#customer_email').val();
  // $mail_setFromName=$('#customer_name').val();
  // $txt_message=$('#message').val();
  // $mail_subject='Cliente Nuevo Pagina  Vaz:';	
  // $telefono=$("#telefono").val();
  // //var expresionRegularCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;




  // if($("#modalForm form").valid()
  // ){
  //   $.ajax({
  //     url:'sendemail.php',
  //       data:{mail_setFromEmail:$mail_setFromEmail,
  //         mail_setFromName:$mail_setFromName,
  //           txt_message:$txt_message,
  //           mail_subject:$mail_subject,
  //           telefono:$telefono,
  //         },
  //     type:'POST',
  //     dataType: 'html',
  //     beforeSend: function() {
  //       var inputNombre = document.getElementById("send");
  //       inputNombre.value = "Enviando...";
  //     //$('#send').text('Enviando...');
  //     $('#send').addClass('btn-info');
  //     },
  //     success: function( resp ) {
      
  //       $('#send').text('Enviar Mesaje');
  //       $('#send').removeClass('btn-info');
  //       $('#send').addClass('btn-success');
  //       $('#modalForm').modal('hide');


  //   mostrarModalExito("Email enviado con éxito");
  //     }
  //   });
  // }

    
  // }
  </script>
