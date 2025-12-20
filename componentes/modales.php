<!-- MODAL EDITAR PERFIL (Estilo Contable + Bootstrap Icons) -->
<div class="modal fade" id="modalEditarPerfil" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow-lg">

            <!-- Encabezado -->
            <div class="modal-header" style="background:#1565c0; color:white;">
                <h5 class="modal-title">
                    <i class="bi bi-person-vcard me-2"></i> Actualizar Datos del Usuario
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <!-- Cuerpo -->
            <div class="modal-body">
                <p class="text-secondary mb-3">
                    Modifica tu información asociada a tu perfil dentro del sistema contable.
                </p>

                <form id="formEditarPerfil">
                    <input type="hidden" name="id_usu" id="id_usu" value="<?= $usuario["id_usu"] ?>">

                    <div class="mb-3">
                        <label class="form-label">
                            <i class="bi bi-person-lines-fill me-1"></i> Nombre:
                        </label>
                        <input
                            type="text"
                            class="form-control"
                            name="nombre_usu"
                            id="nombre_usu"
                            value="<?= htmlspecialchars($usuario['nombre_usu']) ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            <i class="bi bi-person-lines-fill me-1"></i> Apellido:
                        </label>
                        <input
                            type="text"
                            class="form-control"
                            name="apellido_usu"
                            id="apellido_usu"
                            value="<?= htmlspecialchars($usuario['apellido_usu']) ?>">
                    </div>



                    <div class="mb-3">
                        <label class="form-label">
                            <i class="bi bi-person-fill-gear me-1"></i> Nombre de usuario:
                        </label>
                        <input
                            type="text"
                            class="form-control"
                            name="usuario_usu"
                            id="usuario_usu"
                            value="<?= htmlspecialchars($usuario['usuario_usu']) ?>">
                    </div>



                </form>
            </div>

            <!-- Pie del modal -->
            <div class="modal-footer">
                <button class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Cancelar
                </button>

                <button class="btn btn-primary" form="formEditarPerfil">
                    <i class="bi bi-save2 me-1"></i> Guardar cambios
                </button>
            </div>

        </div>
    </div>
</div>







<!-- MODAL JUEGO: CÉDULA -->
<div class="modal fade" id="modalCedulaJuego" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4">
      <div class="modal-header">
        <h5 class="modal-title fw-bold"><i class="bi bi-person-vcard"></i> Identificación</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form id="formCedulaJuego">
        <div class="modal-body">
          <p class="text-muted">Ingresa tu número de cédula para jugar</p>
          <input type="text" id="cedulaJuego" placeholder="10 digitos para empezar" data-validate="cedula" maxlength="10" inputmode="numeric" pattern="[0-9]{10}" class="form-control">
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button class="btn btn-primary" type="submit">Continuar</button>
        </div>
      </form>
    </div>
  </div>
</div>


<!-- MODAL RESET: CÉDULA -->
<div class="modal fade" id="modalCedulaReset" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4">
      <div class="modal-header">
        <h5 class="modal-title fw-bold"><i class="bi bi-shield-lock"></i> Restablecer contraseña</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form id="formCedulaReset">
        <div class="modal-body">
          <p class="text-muted">Ingresa tu número de cédula</p>
          <input type="text" id="cedulaReset" class="form-control">
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button class="btn btn-primary" type="submit">Continuar</button>
        </div>
      </form>
    </div>
  </div>
</div>



<!-- MODAL 1B: REGISTRO RÁPIDO (si no existe) -->
<div class="modal fade" id="modalRegistroRapido" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4">
      <div class="modal-header">
        <h5 class="modal-title fw-bold">
          <i class="bi bi-person-plus"></i> Registro rápido
        </h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form id="formRegistroRapido">
        <div class="modal-body">

          <div class="mb-2">
            <label class="fw-bold">Cédula</label>
            <input type="text" id="cedulaRR" class="form-control" readonly>
          </div>

          <div class="mb-2">
            <label class="fw-bold">Nombre</label>
            <input type="text"
                   id="nombreRR"
                   placeholder="Ingrese su nombre"
                   data-validate="letras"
                   class="form-control"
                   required>
          </div>

          <div class="mb-2">
            <label class="fw-bold">Apellido</label>
            <input type="text"
                   id="apellidoRR"
                   placeholder="Ingrese su apellido"
                   data-validate="letras"
                   class="form-control"
                   required>
          </div>

          <!-- 🔹 NUEVO: NIVEL / ROL -->
          <div class="mb-2">
            <label class="fw-bold">Nivel</label>
            <select id="rolRR" class="form-select" required>
              <option value="">-- Seleccionar nivel --</option>
              <option value="1">Estudiante Básico</option>
              <option value="2">Estudiante Universitario</option>
              <option value="3">Maestro / Profesional</option>
            </select>
          </div>

          <small class="text-secondary">
            Usuario y contraseña temporal: tu cédula.
          </small>

        </div>

        <div class="modal-footer">
          <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button class="btn btn-primary" type="submit">
            Registrar y continuar
          </button>
        </div>
      </form>
    </div>
  </div>
</div>



<!-- MODAL 2: CÓDIGO -->
<div class="modal fade" id="modalCodigo" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">

            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="bi bi-check-circle"></i> Confirmación
                </h5>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <p><strong>Nombre:</strong></p>
                <p id="nombreUsuarioCodigo" class="text-primary"></p>

                <p><strong>Cédula:</strong></p>
                <p id="cedulaUsuarioCodigo" class="text-secondary"></p>

                <hr>

                <label class="fw-bold">Código de autorización</label>
                <input type="text" id="codigo" class="form-control form-control-lg" placeholder="Ej: 1234">

              
            </div>

            <div class="modal-footer">
                <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button id="btnIngresarJuego" class="btn btn-success">
                    <i class="bi bi-play-fill"></i> Ingresar
                </button>

            </div>

        </div>
    </div>
</div>


<!-- MODAL 3: CAMBIO CLAVE -->
<div class="modal fade" id="modalNuevaClave" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4">

      <div class="modal-header">
        <h5 class="modal-title fw-bold">
          <i class="bi bi-shield-lock"></i> Cambiar contraseña
        </h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form id="formCambiarClave" autocomplete="off">
        <div class="modal-body">

          <p class="fw-bold mb-1">Usuario:</p>
          <p id="nombreUsuarioClave" class="text-primary mb-3"></p>

          <!-- Cédula escondida (se llena desde consultar cédula) -->
          <input type="hidden" id="cedulaUsuarioClave">

          <label class="fw-bold">Nueva contraseña</label>
          <div class="input-group mb-2">
            <input type="password" id="nueva_clave" class="form-control" placeholder="********">
            <button class="btn btn-outline-secondary" type="button" id="btnToggleNueva">
              <i class="bi bi-eye"></i>
            </button>
          </div>

          <label class="fw-bold">Confirmar contraseña</label>
          <div class="input-group">
            <input type="password" id="confirmar_clave" class="form-control" placeholder="********">
            <button class="btn btn-outline-secondary" type="button" id="btnToggleConfirmar">
              <i class="bi bi-eye"></i>
            </button>
          </div>

          <small class="text-muted d-block mt-2">
            Recomendado: mínimo 6 caracteres.
          </small>

        </div>

        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button class="btn btn-success" type="submit">
            Guardar
          </button>
        </div>
      </form>

    </div>
  </div>
</div>

