/*
Agregar y eliminar habilidades dinamicamente
*/

$(document).ready(function () {
  // Habilidades técnicas
  $("#addTechnicalSkill").click(function () {
    var skillName = $("#technicalSkillInput").val();
    if (skillName) {
      $("#habilidades-Tecnicas").append(
        `<option value="${skillName}" class="added-skill">${skillName}</option>`
      );

      $("#technicalSkillInput").val(""); // Limpiar el input después de agregar la habilidad
    }
  });

  $("#deleteLastTechnicalSkill").click(function () {
    $("#habilidades-Tecnicas option.added-skill:last").remove(); // Eliminar la última opción con la clase 'added-skill' del select
  });

  // Habilidades sociales
  $("#addSocialSkill").click(function () {
    var skillName = $("#socialSkillInput").val();
    if (skillName) {
      $("#habilidades-Sociales").append(
        `<option value="${skillName}" class="added-skill">${skillName}</option>`
      );

      $("#socialSkillInput").val(""); // Limpiar el input después de agregar la habilidad
    }
  });

  $("#deleteLastSocialSkill").click(function () {
    $("#habilidades-Sociales option.added-skill:last").remove(); // Eliminar la última opción con la clase 'added-skill' del select
  });
});
