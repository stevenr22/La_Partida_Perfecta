// MOSTRAR / OCULTAR CONTRASEÑA
$(document).on("click", ".toggle-password", function () {
  const target = $(this).data("target");
  const $input = $("#" + target);

  if (!$input.length) return;

  const type = $input.attr("type") === "password" ? "text" : "password";
  $input.attr("type", type);

  $(this).find("i").toggleClass("bi-eye bi-eye-slash");
});

// VALIDACIÓN TEST ACTIVO
document.querySelectorAll(".test-btn").forEach((btn) => {
  btn.addEventListener("click", () => {
    const resp = document.getElementById("test-response");
    if (btn.dataset.answer === "correcto") {
      resp.innerHTML = "✅ Correcto. Es un ACTIVO.";
      resp.className = "text-success";
    } else {
      resp.innerHTML = "❌ Incorrecto. Intenta otra vez.";
      resp.className = "text-danger";
    }
  });
});

/* =========================================
   VALIDACIONES EN TIEMPO REAL
   ========================================= */

document.addEventListener("input", function (e) {

  const el = e.target;
  const tipo = el.dataset.validate;
  if (!tipo) return;

  const validadores = {

    // -------------------------------
    // SOLO LETRAS
    // -------------------------------
    letras: {
      regex: /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]*$/,
      limpiar: /[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g
    },

    // -------------------------------
    // CÉDULA (solo números, máx 10)
    // -------------------------------
    cedula: {
      regex: /^\d{0,10}$/,
      limpiar: /\D/g,
      max: 10
    }

  };

  const v = validadores[tipo];
  if (!v) return;

  // Limpiar caracteres inválidos
  if (v.limpiar) {
    el.value = el.value.replace(v.limpiar, "");
  }

  // Limitar longitud
  if (v.max) {
    el.value = el.value.slice(0, v.max);
  }

});
