<!-- MODAL PARA INGRESAR CÓDIGO (Estilo Contable + Bootstrap Icons) -->
<div class="modal fade" id="modalCodigo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header" style="background:#0d47a1; color:white;">
                <h5 class="modal-title">
                    <i class="bi bi-calculator me-2"></i> Acceso al Registro Contable
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <p class="mb-3 text-secondary">
                    Ingresa el <strong>código de autorización</strong> proporcionado por el contador o administrador
                    para acceder al módulo contable.
                </p>

                <label class="form-label fw-bold">Código de Autorización:</label>
                <input
                    type="text"
                    id="inputCodigo"
                    class="form-control form-control-lg"
                    placeholder="Ej: CNT-2025">
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Cancelar
                </button>

                <button type="button" id="btnValidarCodigo" class="btn btn-primary">
                    <i class="bi bi-check2-circle me-1"></i> Validar Código
                </button>
            </div>

        </div>
    </div>
</div>



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
                            <i class="bi bi-envelope-at me-1"></i> Correo electrónico:
                        </label>
                        <input
                            type="email"
                            class="form-control"
                            name="email_usu"
                            id="email_usu"
                            value="<?= htmlspecialchars($usuario['email_usu']) ?>">
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

                    <div class="mb-3">
                        <label class="form-label">
                            <i class="bi bi-shield-lock-fill me-1"></i> Nueva contraseña:
                        </label>
                        <input
                            type="password"
                            class="form-control"
                            name="contrasena_usu"
                            id="contrasena_usu"
                            placeholder="Dejar vacío si no deseas cambiarla">
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








<!-- MODAL 1: INGRESAR CÉDULA -->
<div class="modal fade" id="modalCedula" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content rounded-4">

            <div class="modal-header">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-person-vcard"></i> Verificación de identidad
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="formCedula">
                <div class="modal-body">

                    <p class="text-muted">Ingresa tu número de cédula para continuar.</p>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Número de cédula</label>
                        <input type="text" placeholder="Ingrese su cedula" id="cedula" name="cedula" class="form-control">
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        Continuar
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
<!-- MODAL 2: CAMBIO DE CONTRASEÑA -->
<div class="modal fade" id="modalNuevaClave" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content rounded-4">

            <div class="modal-header">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-shield-lock"></i> Cambiar contraseña
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="formCambiarClave">
                <div class="modal-body">

                    <p class="fw-bold mb-1">Usuario identificado:</p>
                    <p id="nombreUsuario" class="text-primary fw-semibold">—</p>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nueva contraseña</label>
                        <input type="password" id="nueva_clave" placeholder="Ingrese la nueva contraseña" class="form-control" required>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-success">
                        Guardar cambios
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>