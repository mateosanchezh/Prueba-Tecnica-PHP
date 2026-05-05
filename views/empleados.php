<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Empleados</h4>
    <button class="btn btn-primary btn-sm" id="btn-nuevo-empleado">+ Agregar</button>
</div>

<div id="alerta-empleado" class="alert d-none" role="alert"></div>

<div class="table-responsive">
    <table class="table table-bordered table-hover align-middle">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Cargo</th>
                <th>Salario</th>
                <th>Fecha Ingreso</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="tbody-empleados">
            <tr><td colspan="7" class="text-center">Cargando...</td></tr>
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="modal-empleado" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-empleado-titulo">Empleado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="alerta-modal-empleado" class="alert alert-danger d-none"></div>
                <input type="hidden" id="empleado-id">
                <div class="row">
                    <div class="col mb-3">
                        <label class="form-label">Nombre <span class="text-danger">*</span></label>
                        <input type="text" id="empleado-nombre" class="form-control">
                    </div>
                    <div class="col mb-3">
                        <label class="form-label">Apellido <span class="text-danger">*</span></label>
                        <input type="text" id="empleado-apellido" class="form-control">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Cargo <span class="text-danger">*</span></label>
                    <input type="text" id="empleado-cargo" class="form-control">
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label class="form-label">Salario <span class="text-danger">*</span></label>
                        <input type="number" id="empleado-salario" class="form-control" min="0" step="0.01">
                    </div>
                    <div class="col mb-3">
                        <label class="form-label">Fecha de ingreso <span class="text-danger">*</span></label>
                        <input type="date" id="empleado-fecha-ingreso" class="form-control">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btn-guardar-empleado">Guardar</button>
            </div>
        </div>
    </div>
</div>

<script>
$(function () {
    const modal = new bootstrap.Modal('#modal-empleado');

    cargar();

    function cargar() {
        fetch('index.php?action=empleados.listar')
            .then(r => r.json())
            .then(data => {
                const tbody = $('#tbody-empleados').empty();
                if (!data.ok || !data.data.length) {
                    tbody.append('<tr><td colspan="7" class="text-center text-muted">Sin registros</td></tr>');
                    return;
                }
                data.data.forEach(e => {
                    tbody.append(`
                        <tr>
                            <td>${e.id}</td>
                            <td>${e.nombre}</td>
                            <td>${e.apellido}</td>
                            <td>${e.cargo}</td>
                            <td>$${parseFloat(e.salario).toFixed(2)}</td>
                            <td>${e.fecha_ingreso ? e.fecha_ingreso.substring(0,10) : ''}</td>
                            <td>
                                <button class="btn btn-warning btn-sm btn-editar-empleado" data-id="${e.id}">Editar</button>
                                <button class="btn btn-danger btn-sm btn-eliminar-empleado" data-id="${e.id}">Eliminar</button>
                            </td>
                        </tr>`);
                });
            });
    }

    $('#btn-nuevo-empleado').on('click', function () {
        $('#empleado-id, #empleado-nombre, #empleado-apellido, #empleado-cargo, #empleado-salario, #empleado-fecha-ingreso').val('');
        $('#modal-empleado-titulo').text('Nuevo Empleado');
        $('#alerta-modal-empleado').addClass('d-none');
        modal.show();
    });

    $(document).on('click', '.btn-editar-empleado', function () {
        const id = $(this).data('id');
        fetch(`index.php?action=empleados.obtener&id=${id}`)
            .then(r => r.json())
            .then(data => {
                if (!data.ok) return;
                const e = data.data;
                $('#empleado-id').val(e.id);
                $('#empleado-nombre').val(e.nombre);
                $('#empleado-apellido').val(e.apellido);
                $('#empleado-cargo').val(e.cargo);
                $('#empleado-salario').val(e.salario);
                $('#empleado-fecha-ingreso').val(e.fecha_ingreso ? e.fecha_ingreso.substring(0,10) : '');
                $('#modal-empleado-titulo').text('Editar Empleado');
                $('#alerta-modal-empleado').addClass('d-none');
                modal.show();
            });
    });

    $(document).on('click', '.btn-eliminar-empleado', function () {
        if (!confirm('¿Eliminar este empleado?')) return;
        const id = $(this).data('id');
        fetch('index.php?action=empleados.eliminar', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id })
        })
        .then(r => r.json())
        .then(data => {
            if (data.ok) cargar();
            else mostrarAlerta('#alerta-empleado', data.mensaje);
        });
    });

    $('#btn-guardar-empleado').on('click', function () {
        const id     = $('#empleado-id').val();
        const action = id ? 'empleados.actualizar' : 'empleados.insertar';
        const datos  = {
            nombre:        $('#empleado-nombre').val().trim(),
            apellido:      $('#empleado-apellido').val().trim(),
            cargo:         $('#empleado-cargo').val().trim(),
            salario:       parseFloat($('#empleado-salario').val()),
            fecha_ingreso: $('#empleado-fecha-ingreso').val(),
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
            else mostrarAlerta('#alerta-modal-empleado', data.mensaje);
        });
    });

    function mostrarAlerta(selector, msg) {
        $(selector).text(msg).removeClass('d-none');
    }
});
</script>
