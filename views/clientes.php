<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Clientes</h4>
    <button class="btn btn-primary btn-sm" id="btn-nuevo-cliente">+ Agregar</button>
</div>

<div id="alerta-cliente" class="alert d-none" role="alert"></div>

<div class="table-responsive">
    <table class="table table-bordered table-hover align-middle">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Dirección</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="tbody-clientes">
            <tr><td colspan="8" class="text-center">Cargando...</td></tr>
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="modal-cliente" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-cliente-titulo">Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="alerta-modal-cliente" class="alert alert-danger d-none"></div>
                <input type="hidden" id="cliente-id">
                <div class="row">
                    <div class="col mb-3">
                        <label class="form-label">Nombre <span class="text-danger">*</span></label>
                        <input type="text" id="cliente-nombre" class="form-control">
                    </div>
                    <div class="col mb-3">
                        <label class="form-label">Apellido <span class="text-danger">*</span></label>
                        <input type="text" id="cliente-apellido" class="form-control">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" id="cliente-email" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Teléfono</label>
                    <input type="text" id="cliente-telefono" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Dirección</label>
                    <input type="text" id="cliente-direccion" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btn-guardar-cliente">Guardar</button>
            </div>
        </div>
    </div>
</div>

<script>
$(function () {
    const modal = new bootstrap.Modal('#modal-cliente');

    cargar();

    function cargar() {
        fetch('index.php?action=clientes.listar')
            .then(r => r.json())
            .then(data => {
                const tbody = $('#tbody-clientes').empty();
                if (!data.ok || !data.data.length) {
                    tbody.append('<tr><td colspan="8" class="text-center text-muted">Sin registros</td></tr>');
                    return;
                }
                data.data.forEach(c => {
                    tbody.append(`
                        <tr>
                            <td>${c.id}</td>
                            <td>${c.nombre}</td>
                            <td>${c.apellido}</td>
                            <td>${c.email}</td>
                            <td>${c.telefono ?? ''}</td>
                            <td>${c.direccion ?? ''}</td>
                            <td>${c.fecha_creacion ? c.fecha_creacion.substring(0,10) : ''}</td>
                            <td>
                                <button class="btn btn-warning btn-sm btn-editar-cliente" data-id="${c.id}">Editar</button>
                                <button class="btn btn-danger btn-sm btn-eliminar-cliente" data-id="${c.id}">Eliminar</button>
                            </td>
                        </tr>`);
                });
            });
    }

    $('#btn-nuevo-cliente').on('click', function () {
        $('#cliente-id, #cliente-nombre, #cliente-apellido, #cliente-email, #cliente-telefono, #cliente-direccion').val('');
        $('#modal-cliente-titulo').text('Nuevo Cliente');
        $('#alerta-modal-cliente').addClass('d-none');
        modal.show();
    });

    $(document).on('click', '.btn-editar-cliente', function () {
        const id = $(this).data('id');
        fetch(`index.php?action=clientes.obtener&id=${id}`)
            .then(r => r.json())
            .then(data => {
                if (!data.ok) return;
                const c = data.data;
                $('#cliente-id').val(c.id);
                $('#cliente-nombre').val(c.nombre);
                $('#cliente-apellido').val(c.apellido);
                $('#cliente-email').val(c.email);
                $('#cliente-telefono').val(c.telefono);
                $('#cliente-direccion').val(c.direccion);
                $('#modal-cliente-titulo').text('Editar Cliente');
                $('#alerta-modal-cliente').addClass('d-none');
                modal.show();
            });
    });

    $(document).on('click', '.btn-eliminar-cliente', function () {
        if (!confirm('¿Eliminar este cliente?')) return;
        const id = $(this).data('id');
        fetch('index.php?action=clientes.eliminar', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id })
        })
        .then(r => r.json())
        .then(data => {
            if (data.ok) cargar();
            else mostrarAlerta('#alerta-cliente', data.mensaje);
        });
    });

    $('#btn-guardar-cliente').on('click', function () {
        const id     = $('#cliente-id').val();
        const action = id ? 'clientes.actualizar' : 'clientes.insertar';
        const datos  = {
            nombre:    $('#cliente-nombre').val().trim(),
            apellido:  $('#cliente-apellido').val().trim(),
            email:     $('#cliente-email').val().trim(),
            telefono:  $('#cliente-telefono').val().trim(),
            direccion: $('#cliente-direccion').val().trim(),
        };
        if (id) datos.id = parseInt(id);

        fetch(`index.php?action=${action}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(datos)
        })
        .then(r => r.json())
        .then(data => {
            if (data.ok) { modal.hide(); cargar(); }
            else mostrarAlerta('#alerta-modal-cliente', data.mensaje);
        });
    });

    function mostrarAlerta(selector, msg) {
        $(selector).text(msg).removeClass('d-none');
    }
});
</script>
