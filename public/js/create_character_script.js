document.addEventListener("DOMContentLoaded", function () {
  // Funcionalidad para mostrar el nombre del archivo seleccionado
  const fileInput = document.getElementById("image");
  const fileNameSpan = document.getElementById("file-name");

  // Función para actualizar el nombre del archivo en el DOM
  fileInput.addEventListener("change", function () {
    const fileName = fileInput.files[0]
      ? fileInput.files[0].name
      : "No file selected"; // Obtener el nombre del archivo
    fileNameSpan.textContent = fileName; // Actualizar el contenido del span
  });

  // Funcionalidad para manejar la selección de raza
  const raceSelect = document.getElementById("race");
  const customRaceInput = document.getElementById("custom_race");

  raceSelect.addEventListener("change", function () {
    if (this.value === "Other") {
      customRaceInput.style.display = "block";
      customRaceInput.required = true; // Hacer que el campo sea obligatorio
    } else {
      customRaceInput.style.display = "none";
      customRaceInput.required = false;
      customRaceInput.value = ""; // Limpiar el campo de texto
    }
  });

  // Funcionalidad para manejar la selección de clase
  const classSelect = document.getElementById("class");
  const customClassInput = document.getElementById("custom_class");

  classSelect.addEventListener("change", function () {
    if (this.value === "Other") {
      customClassInput.style.display = "block";
      customClassInput.required = true; // Hacer que el campo sea obligatorio
    } else {
      customClassInput.style.display = "none";
      customClassInput.required = false;
      customClassInput.value = ""; // Limpiar el campo de texto
    }
  });
});
document.addEventListener("DOMContentLoaded", function () {
  const imageInput = document.getElementById("image");
  const fileNameSpan = document.getElementById("file-name");

  imageInput.addEventListener("change", function () {
    if (this.files && this.files[0]) {
      fileNameSpan.textContent = this.files[0].name;
    }
  });
});
