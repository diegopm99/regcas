@extends('layout.app')

@section('title', 'Documentos')

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
        <h2>Mantenimiento de Retenciones</h2>
    </div>

    <div class="card mb-3">
        <div class="card-body">

            <div class="d-flex justify-content-between mb-3">
                <button class="btn btn-primary" id="btnRegRetenc">
                    <i class="fa-solid fa-plus"></i> Nuevo
                </button>
                <button class="btn btn-secondary" id="reload">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                    Recargar</button>
            </div>

            <table id="retencionesTable" class="table table-striped w-100">
                <thead class="align-middle">
                    <tr>
                        <th>ID</th>
                        <th>Descripción</th>
                        <th>Tipo</th>
                        <th>Porcentaje</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal registrar retencion --}}
    <div class="modal fade" id="modalGuardarRetenc" tabindex="-1" role="dialog" aria-labelledby="modalGuardarRetenc"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Registrar Retención</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formGuardarRetenc">
                        <input name="codretenc" id="codretenc" hidden>
                        <div class="form-group">
                            <label for="descripcion" class="font-weight-bold">Descripción<span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="descripcion" name="descripcion"
                                placeholder="Ingrese la descripción">
                        </div>
                        <div class="form-group">
                            <label for="tipo" class="font-weight-bold">Tipo</label>
                            <select class="form-control" id="tipo" name="tipo">
                                <option value="" selected>Ninguno</option>
                                <option value="DETRACCION">Detracción</option>
                                <option value="RETENCION">Retención</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="porcentaje" class="font-weight-bold">Porcentaje</label>
                            <input type="text" class="form-control" id="porcentaje" name="porcentaje"
                                placeholder="Ingrese el porcentaje">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" form="formGuardarRetenc">Guardar</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(function() {
            let retencionesMap = {}

            let tabla = $('#retencionesTable').DataTable({
                ajax: "/regcas_retenciones/indexWithTrashed",
                columns: [{
                        data: 'codretenc'
                    },
                    {
                        data: 'descripcion'
                    },
                    {
                        data: 'tipo',
                        render: function(data, type, row) {
                            if (data === 'DETRACCION') {
                                return 'Detracción'
                            } else if (data === 'RETENCION') {
                                return 'Retención'
                            }
                            return '-'
                        }
                    },
                    {
                        data: 'porcentaje',
                        render: function(data, type, row) {
                            if(data){
                                return data + '%'
                            }
                            return '-'
                        }
                    },
                    {
                        data: 'deleted_at',
                        render: function(data, type, row) {
                            if (data) {
                                return `<span class="badge badge-danger">Eliminado</span>`
                            }
                            return `<span class="badge badge-success">Activo</span>`
                        }
                    },
                    {
                        data: "codretenc",
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        render: function(data, type, row) {
                            retencionesMap[data] = row
                            if (row.deleted_at) {
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

            $('#btnRegRetenc').on('click', function() {
                $('#formGuardarRetenc')[0].reset()
                let validator = $("#formGuardarRetenc").validate()
                validator.resetForm()
                $('#modalGuardarRetenc .modal-title').text('Registrar Retención')
                $('#modalGuardarRetenc').modal('show')
            })

            $("#formGuardarRetenc").validate({
                errorElement: 'div',
                rules: {
                    descripcion: {
                        required: true,
                    }
                },
                messages: {
                    descripcion: {
                        required: 'Este campo es obligatorio',
                    }
                },
                errorPlacement: function(error, element) {
                    error.addClass("invalid-feedback")
                    error.insertAfter(element)
                },
                submitHandler: function(form) {
                    Swal.fire({
                        title: "Confirmación",
                        text: "Se guardarán los datos ingresados",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3b5998",
                        cancelButtonColor: "#d33",
                        cancelButtonText: "Cancelar",
                        confirmButtonText: "Confirmar"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            guardarRetenc()
                        }
                    })
                }
            })

            function guardarRetenc() {
                let formData = new FormData($('#formGuardarRetenc')[0])
                let codretenc = $('#codretenc').val()
                if (codretenc) formData.append('codretenc', codretenc)

                let url = codretenc ? '/regcas_retenciones/update' : '/regcas_retenciones/store'
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
                            $('#modalGuardarRetenc').modal('hide')
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

            $('#retencionesTable').on('click', '.btn-editar', function() {
                $('#formGuardarRetenc')[0].reset()
                let validator = $("#formGuardarRetenc").validate()
                validator.resetForm()
                let id = $(this).data('id')
                let fila = retencionesMap[id]

                $('#codretenc').val(fila.codretenc)
                $('#descripcion').val(fila.descripcion)
                $('#tipo').val(fila.tipo)
                $('#porcentaje').val(fila.porcentaje)

                $('#modalGuardarRetenc .modal-title').text('Editar Retención')
                $('#modalGuardarRetenc').modal('show')
            })

            $('#retencionesTable').on('click', '.btn-eliminar', function() {
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
                        eliminarRetencion(id)
                    }
                })
            })

            function eliminarRetencion(id) {
                $.ajax({
                    url: `/regcas_retenciones/destroy/${id}`,
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

            $('#retencionesTable').on('click', '.btn-restaurar', function() {
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
                        restaurarRetencion(id)
                    }
                })
            })

            function restaurarRetencion(id) {
                $.ajax({
                    url: `/regcas_retenciones/restore/${id}`,
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
