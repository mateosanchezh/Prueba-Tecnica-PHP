<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Productos</h4>
    <button class="btn btn-primary btn-sm" id="btn-nuevo-producto">+ Agregar</button>
</div>

<div id="alerta-producto" class="alert d-none" role="alert"></div>

<div class="table-responsive">
    <table class="table table-bordered table-hover align-middle">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Categoría</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="tbody-productos">
            <tr><td colspan="8" class="text-center">Cargando...</td></tr>
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="modal-producto" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-producto-titulo">Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="alerta-modal-producto" class="alert alert-danger d-none"></div>
                <input type="hidden" id="producto-id">
                <div class="mb-3">
                    <label class="form-label">Nombre <span class="text-danger">*</span></label>
                    <input type="text" id="producto-nombre" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Descripción</label>
                    <textarea id="producto-descripcion" class="form-control" rows="2"></textarea>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label class="form-label">Precio <span class="text-danger">*</span></label>
                        <input type="number" id="producto-precio" class="form-control" min="0" step="0.01">
                    </div>
                    <div class="col mb-3">
                        <label class="form-label">Stock <span class="text-danger">*</span></label>
                        <input type="number" id="producto-stock" class="form-control" min="0">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Categoría <span class="text-danger">*</span></label>
                    <input type="text" id="producto-categoria" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btn-guardar-producto">Guardar</button>
            </div>
        </div>
    </div>
</div>

<script>
$(function () {
    const modal = new bootstrap.Modal('#modal-producto');

    cargar();

    function cargar() {
        fetch('index.php?action=productos.listar')
            .then(r => r.json())
            .then(data => {
                const tbody = $('#tbody-productos').empty();
                if (!data.ok || !data.data.length) {
                    tbody.append('<tr><td colspan="8" class="text-center text-muted">Sin registros</td></tr>');
                    return;
                }
                data.data.forEach(p => {
                    tbody.append(`
                        <tr>
                            <td>${p.id}</td>
                            <td>${p.nombre}</td>
                            <td>${p.descripcion ?? ''}</td>
                            <td>$${parseFloat(p.precio).toFixed(2)}</td>
                            <td>${p.stock}</td>
                            <td>${p.categoria}</td>
                            <td>${p.fecha_creacion ? p.fecha_creacion.substring(0,10) : ''}</td>
                            <td>
                                <button class="btn btn-warning btn-sm btn-editar-producto" data-id="${p.id}">Editar</button>
                                <button class="btn btn-danger btn-sm btn-eliminar-producto" data-id="${p.id}">Eliminar</button>
                            </td>
                        </tr>`);
                });
            });
    }

    $('#btn-nuevo-producto').on('click', function () {
        $('#form-producto-fields input, #form-producto-fields textarea').val('');
        $('#producto-id').val('');
        $('#modal-producto-titulo').text('Nuevo Producto');
        $('#alerta-modal-producto').addClass('d-none');
        modal.show();
    });

    $(document).on('click', '.btn-editar-producto', function () {
        const id = $(this).data('id');
        fetch(`index.php?action=productos.obtener&id=${id}`)
            .then(r => r.json())
            .then(data => {
                if (!data.ok) return;
                const p = data.data;
                $('#producto-id').val(p.id);
                $('#producto-nombre').val(p.nombre);
                $('#producto-descripcion').val(p.descripcion);
                $('#producto-precio').val(p.precio);
                $('#producto-stock').val(p.stock);
                $('#producto-categoria').val(p.categoria);
                $('#modal-producto-titulo').text('Editar Producto');
                $('#alerta-modal-producto').addClass('d-none');
                modal.show();
            });
    });

    $(document).on('click', '.btn-eliminar-producto', function () {
        if (!confirm('¿Eliminar este producto?')) return;
        const id = $(this).data('id');
        fetch('index.php?action=productos.eliminar', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id })
        })
        .then(r => r.json())
        .then(data => {
            if (data.ok) cargar();
            else mostrarAlerta('#alerta-producto', data.mensaje);
        });
    });

    $('#btn-guardar-producto').on('click', function () {
        const id     = $('#producto-id').val();
        const action = id ? 'productos.actualizar' : 'productos.insertar';
        const datos  = {
            nombre:      $('#producto-nombre').val().trim(),
            descripcion: $('#producto-descripcion').val().trim(),
            precio:      parseFloat($('#producto-precio').val()),
            stock:       parseInt($('#producto-stock').val()),
            categoria:   $('#producto-categoria').val().trim(),
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
            else mostrarAlerta('#alerta-modal-producto', data.mensaje);
        });
    });

    function mostrarAlerta(selector, msg) {
        $(selector).text(msg).removeClass('d-none');
    }
});
</script>
