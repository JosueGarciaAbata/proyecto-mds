<footer class="footer footer-transparent d-print-none">
  <div class="container-xl">
    <div class="row text-center align-items-center flex-row-reverse">
      <div class="col-lg-auto ms-lg-auto">

      </div>
      <div class="col-12 col-lg-auto mt-3 mt-lg-0">
        <ul class="list-inline list-inline-dots mb-0">
          <li class="list-inline-item">
            Copyright &copy; 2024
            <a href="." class="link-secondary">My Creative Portfolio</a>.
            All rights reserved.
          </li>
          <li class="list-inline-item">
            <a href="../changelog.html" class="link-secondary" rel="noopener">
              v1.0.0-beta
            </a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</footer>
<!-- Libs JS -->
<script src="../dist/libs/apexcharts/dist/apexcharts.min.js?1684106062" defer></script>
<script src="../dist/libs/jsvectormap/dist/js/jsvectormap.min.js?1684106062" defer></script>
<script src="../dist/libs/jsvectormap/dist/maps/world.js?1684106062" defer></script>
<script src="../dist/libs/jsvectormap/dist/maps/world-merc.js?1684106062" defer></script>
<!-- Tabler Core -->
<script src="../dist/js/tabler.min.js?1684106062" defer></script>
<script src="../dist/js/demo.min.js?1684106062" defer></script>

<script src="../dist/libs/jquery_3.2.1/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/additional-methods.min.js"></script>
<script src="../dist/libs/SweetAlert2/SweetAlert2.js"></script>

<script>
  function mostrarModalDeAdvertencia(mensaje) {

    Swal.fire({
      icon: 'warning',
      title: 'Alerta',
      text: mensaje,
      confirmButtonText: 'Aceptar'
    });
  }

  function mostrarModalExito(mensaje) {
    Swal.fire({
      position: "center",
      icon: "success",
      title: mensaje,
      showConfirmButton: false,
      timer: 2500
    });

  }
</script>
</body>

</html>