@extends('layout.app')

@section('title', 'Cuentas Contables')

@push('styles')
    <style>
        .card {
            border: 1px solid #dee2e6;
        }

        .dataTables_filter {
            display: none !important;
        }
    </style>
@endpush

@section('content')
    <div class="mb-3">
        <h2>Mantenimiento de Cuentas Contables</h2>
    </div>

    <div class="card mb-3">
        <div class="card-body">

            <div class="d-flex justify-content-between mb-3">
                <button class="btn btn-primary" id="btnRegCuenta">
                    <i class="fa-solid fa-plus"></i> Nuevo
                </button>
                <button class="btn btn-secondary" id="reload">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                    Recargar</button>
            </div>

            <table id="cuentasTable" class="table table-striped w-100">
                <thead class="align-middle">
                    <tr>
                        <th>ID</th>
                        <th>Clasificador</th>
                        <th>Tipo de Orden</th>
                        <th>Cuenta Contable</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal registrar cuenta contable --}}
    <div class="modal fade" id="modalRegCuenta" tabindex="-1" role="dialog" aria-labelledby="modalRegCuenta"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Registrar Cuenta Contable</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formRegCuenta">
                        <input name="codcuenta" id="codcuenta" hidden>
                        <div class="form-group">
                            <label for="clasificador" class="font-weight-bold">Clasificador<span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="clasificador" name="clasificador"
                                placeholder="Digite el clasificador" maxlength="3">
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="tipo_orden">Tipo de Orden<span
                                    class="text-danger">*</span></label>
                            <select class="form-control" name="tipo_orden" id="tipo_orden">
                                <option value="" selected>--Seleccionar--</option>
                                <option value="OS">Orden de servicio</option>
                                <option value="OC">Orden de compra</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="cuenta" class="font-weight-bold">Cuenta Contable<span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="cuenta" name="cuenta"
                                placeholder="Digite el Nº de cuenta" maxlength="3">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" form="formRegCuenta">Guardar</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(function() {
            let cuentasMap = {}

            let tabla = $('#cuentasTable').DataTable({
                ajax: "/regcas_ctas_conts/indexWithTrashed",
                columns: [{
                        data: 'codcuenta'
                    },
                    {
                        data: 'clasificador'
                    },
                    {
                        data: 'tipo_orden'
                    },
                    {
                        data: 'cuenta'
                    },
                    {
                        data: 'deleted_at',
                        render: function(data, type, row) {
                            if (data) {
                                return `<span class="badge badge-danger">Eliminado</span>`
                            }
                            return `<span class="badge badge-success">Activo</span>`
                        },
                        className: 'text-center'
                    },
                    {
                        data: "codcuenta",
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        render: function(data, type, row) {
                            cuentasMap[data] = row
                            if(row.deleted_at){
                                return `
                                <button class="btn btn-sm btn-warning btn-restaurar" data-id="${data}">
                                    <i class="fa-solid fa-trash-can-arrow-up"></i>
                                </button>
                            `
                            }
                            return `
                                <button class="btn btn-sm btn-primary btn-editar" data-id="${data}">
                                    <i class="fa-solid fa-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-danger btn-eliminar" data-id="${data}">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            `
                        }
                    }
                ],
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/2.3.3/i18n/es-ES.json'
                },
                responsive: true,
                pageLength: 10,
                order: []
            })

            $('#btnRegCuenta').on('click', function() {
                $('#formRegCuenta')[0].reset()
                let validator = $("#formRegCuenta").validate()
                validator.resetForm()
                $('#modalRegCuenta .modal-title').text('Registrar Cuenta Contable')
                $('#modalRegCuenta').modal('show')
            })

            $("#formRegCuenta").validate({
                errorElement: 'div',
                rules: {
                    clasificador: {
                        required: true,
                        minlength: 3,
                        maxlength: 3
                    },
                    tipo_orden: {
                        required: true
                    },
                    cuenta: {
                        required: true,
                        minlength: 3,
                        maxlength: 3
                    }
                },
                messages: {
                    clasificador: {
                        required: 'Este campo es obligatorio',
                        minlength: 'Debe escribir 3 caracteres',
                        maxlength: 'Debe escribir 3 caracteres'
                    },
                    tipo_orden: {
                        required: 'Este campo es obligatorio'
                    },
                    cuenta: {
                        required: 'Este campo es obligatorio',
                        minlength: 'Debe escribir 3 caracteres',
                        maxlength: 'Debe escribir 3 caracteres'
                    }
                },
                errorPlacement: function(error, element) {
                    error.addClass("invalid-feedback")
                    error.insertAfter(element)
                },
                submitHandler: function(form) {
                    Swal.fire({
                        title: "Confirmación",
                        text: "¿Desea guardar la cuenta contable?",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3b5998",
                        cancelButtonColor: "#d33",
                        cancelButtonText: "Cancelar",
                        confirmButtonText: "Sí, registrar"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            guardarCtaCont()
                        }
                    })
                }
            })

            function guardarCtaCont() {
                let formData = new FormData($('#formRegCuenta')[0])
                let codcuenta = $('#codcuenta').val()
                if (codcuenta) formData.append('codcuenta', codcuenta)

                let url = codcuenta ? '/regcas_ctas_conts/update' : '/regcas_ctas_conts/store'
                console.log(url)
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#modalRegCuenta').modal('hide')
                            tabla.ajax.reload(null, false)
                            Swal.fire({
                                title: "Completado!",
                                text: response.message,
                                icon: "success"
                            })
                        } else {
                            Swal.fire({
                                title: "Error!",
                                text: response.message,
                                icon: "error"
                            })
                        }
                    },
                    error: function() {
                        toastr.error('Error de conexión con el servidor')
                    }
                })
            }

            $('#reload').on('click', function() {
                tabla.ajax.reload()
            })

            $('#cuentasTable').on('click', '.btn-editar', function() {
                $('#formRegCuenta')[0].reset()
                let validator = $("#formRegCuenta").validate()
                validator.resetForm()
                let id = $(this).data('id')
                let fila = cuentasMap[id]

                $('#codcuenta').val(fila.codcuenta)
                $('#clasificador').val(fila.clasificador)
                $('#tipo_orden').val(fila.tipo_orden)
                $('#cuenta').val(fila.cuenta)

                $('#modalRegCuenta .modal-title').text('Editar Cuenta Contable')
                $('#modalRegCuenta').modal('show')
            })

            $('#cuentasTable').on('click', '.btn-eliminar', function() {
                let id = $(this).data('id')
                Swal.fire({
                    title: "Confirmación",
                    text: "Se eliminaran los datos seleccionados",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3b5998",
                    cancelButtonColor: "#d33",
                    cancelButtonText: "Cancelar",
                    confirmButtonText: "Confirmar"
                }).then((result) => {
                    if (result.isConfirmed) {
                        eliminarCtaCont(id)
                    }
                })
            })

            function eliminarCtaCont(id) {
                $.ajax({
                    url: `/regcas_ctas_conts/destroy/${id}`,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            tabla.ajax.reload(null, false);
                            Swal.fire({
                                title: "Completado!",
                                text: response.message,
                                icon: "success"
                            })
                        } else {
                            Swal.fire({
                                title: "Error!",
                                text: response.message,
                                icon: "error"
                            })
                        }
                    },
                    error: function() {
                        toastr.error('Error de conexión con el servidor')
                    }
                })
            }

            $('#cuentasTable').on('click', '.btn-restaurar', function() {
                let id = $(this).data('id')
                Swal.fire({
                    title: "Confirmación",
                    text: "Se va a restaurar el registro seleccionado",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3b5998",
                    cancelButtonColor: "#d33",
                    cancelButtonText: "Cancelar",
                    confirmButtonText: "Confirmar"
                }).then((result) => {
                    if (result.isConfirmed) {
                        restaurarCtaCont(id)
                    }
                })
            })

            function restaurarCtaCont(id) {
                $.ajax({
                    url: `/regcas_ctas_conts/restore/${id}`,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            tabla.ajax.reload(null, false);
                            Swal.fire({
                                title: "Completado!",
                                text: response.message,
                                icon: "success"
                            })
                        } else {
                            Swal.fire({
                                title: "Error!",
                                text: response.message,
                                icon: "error"
                            })
                        }
                    },
                    error: function() {
                        toastr.error('Error de conexión con el servidor')
                    }
                })
            }
        })
    </script>
@endpush
