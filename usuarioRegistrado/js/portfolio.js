/*
Permite añadir habilidades tecnicas y sociales dinamicamente.
Procesar la informacion del portafolio.
*/
$(document).ready(function () {
  var technicalSkillCount = 0;
  var socialSkillCount = 0;

  $("#addTechnicalSkill").click(function () {
    technicalSkillCount++;
    $("#technicalSkillsContainer").append(
      `<input type="text" class="form-control mb-2 technical-skill" placeholder="Enter technical skill" name="technicalSkill${technicalSkillCount}">`
    );
  });

  $("#addSocialSkill").click(function () {
    socialSkillCount++;
    $("#socialSkillsContainer").append(
      `<input type="text" class="form-control mb-2 social-skill" placeholder="Enter social skill" name="socialSkill${socialSkillCount}">`
    );
  });
});

$(document).ready(function () {
  $("#btn_portfolio").click(function () {
    var imagePerfilFile = $("#foto-perfil")[0].files[0];
    var imageFondoFile = $("#foto-fondo")[0].files[0];

    // Valida la información
    if (
      $("#portfolioForm").valid() &&
      $("#state").val() != null &&
      $(".projects input:checked").length > 0
    ) {
      // Guardar información en formData
      var formData = new FormData();
      if (imagePerfilFile) {
        formData.append("imagePerfil", imagePerfilFile);
      }
      if (imageFondoFile) {
        formData.append("imageFondo", imageFondoFile);
      }

      formData.append("titulo", $("#titulo-portafolio").val());
      formData.append("aboutMe", $("#sobre-mi").val());
      formData.append("cv", $("#cv")[0].files[0]);
      formData.append("state", $("#state").val());

      // Habilidades
      var technicalSkills = [];
      var socialSkills = [];
      $("#technicalSkillsContainer input").each(function () {
        technicalSkills.push($(this).val());
      });

      $("#socialSkillsContainer input").each(function () {
        socialSkills.push($(this).val());
      });

      var skillsData = {
        technicalSkills: technicalSkills,
        socialSkills: socialSkills,
      };

      formData.append("skills", JSON.stringify(skillsData));

      // Enviar información del portfolio al backend
      $.ajax({
        url: "procesarInformacion/portfolios/portfolios.php",
        type: "POST",
        data: {
          action: "createPortfolio",
          information: formData,
        },
        contentType: false,
        processData: false,
        success: function (response) {
          console.log(response);
          if (response == "true") {
            mostrarModalExito("Portfolio saved successfully");
            setTimeout(function () {
              window.location.href = "portfolio.php";
            }, 2500);
          } else {
            mostrarModalDeAdvertencia("Could not save the portfolio");
          }
        },
        error: function (xhr, status, error) {
          console.error(error);
          mostrarModalDeAdvertencia("Connection error");
        },
      });
    } else {
      mostrarModalDeAdvertencia("Necessary information is missing");
    }
  });
});

/*
Cargar dinamicamente los proyectos disponibles para seleccionar
*/
$(document).ready(function () {
  $.ajax({
    url: "procesarInformacion/portfolios/portfolios.php",
    method: "POST",
    data: {
      action: "getProjects",
    },
    success: function (response) {
      var labels = JSON.parse(response);

      var formSelectgroup = document.querySelector(".projects");

      formSelectgroup.innerHTML = "";

      labels.forEach(function (label) {
        var labelElement = document.createElement("label");
        labelElement.classList.add("form-selectgroup-item");

        var inputElement = document.createElement("input");
        inputElement.type = "checkbox";
        inputElement.name = "name";
        inputElement.value = label.titulo_proyecto;
        inputElement.dataset.id = label.id_proyecto;

        inputElement.classList.add("form-selectgroup-input");

        var spanElement = document.createElement("span");
        spanElement.classList.add("form-selectgroup-label");
        spanElement.textContent = label.titulo_proyecto;

        labelElement.appendChild(inputElement);
        labelElement.appendChild(spanElement);

        formSelectgroup.appendChild(labelElement);
      });
    },
    error: function (xhr, status, error) {
      console.error(error);
    },
  });
});

/*
Validar la información ingresada
*/
$(document).ready(function () {
  $("#portfolioForm").validate({
    rules: {
      "titulo-portafolio": {
        required: true,
        minlength: 10,
        maxlength: 50,
      },
      "foto-perfil": {
        required: true,
        accept: "image/jpg,image/jpeg,image/png",
      },
      "foto-fondo": {
        required: true,
        accept: "image/jpg,image/jpeg,image/png",
      },
      "sobre-mi": {
        required: true,
        minlength: 10,
      },
      cv: {
        required: true,
        accept: "application/pdf",
      },
      state: {
        required: true,
      },
    },
    messages: {
      "titulo-portafolio": {
        required: "Please enter a title for the portfolio",
        minlength: "Please enter at least 10 characters",
        maxlength: "The maximum number of characters for the title is 50",
      },
      "foto-perfil": {
        required: "Please select a profile picture",
        accept: "Please select a valid image format (jpg, jpeg, png, gif)",
      },
      "foto-fondo": {
        required: "Please select a background photo",
        accept: "Please select a valid image format (jpg, jpeg, png, gif)",
      },
      "sobre-mi": {
        required: "Please enter information about yourself",
        minlength: "Please enter at least 10 characters",
      },
      cv: {
        required: "Please upload your curriculum vitae (PDF)",
        accept: "Please upload a valid PDF file",
      },
      state: {
        required: "Please select visibility status",
      },
    },
    errorElement: "div",
    errorPlacement: function (error, element) {
      error.addClass("error");
      var container = $("<div>").addClass("error-container");
      container.insertAfter(element);
      error.appendTo(container);
    },
    highlight: function (element) {
      $(element).addClass("error");
    },
    unhighlight: function (element) {
      $(element).removeClass("error");
    },
  });
});
