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
        <h2>Mantenimiento de Tipos de Documentos</h2>
    </div>

    <div class="card mb-3">
        <div class="card-body">

            <div class="d-flex justify-content-between mb-3">
                <button class="btn btn-primary" id="btnRegTipoDoc">
                    <i class="fa-solid fa-plus"></i> Nuevo
                </button>
                <button class="btn btn-secondary" id="reload">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                    Recargar</button>
            </div>

            <table id="tdocumentosTable" class="table table-striped w-100">
                <thead class="align-middle">
                    <tr>
                        <th>ID</th>
                        <th>Código SUNAT</th>
                        <th style="width: 50%">Descripción</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal guardar tipo de documento --}}
    <div class="modal fade" id="modalGuardarTipoDoc" tabindex="-1" role="dialog" aria-labelledby="modalGuardarTipoDoc"
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
                    <form id="formGuardarTipoDoc">
                        <input name="codtipdoc" id="codtipdoc" hidden>
                        <div class="form-group">
                            <label for="coddocsunat" class="font-weight-bold">Código SUNAT<span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="coddocsunat" name="coddocsunat"
                                placeholder="Digite el código SUNAT del documento" maxlength="2">
                        </div>
                        <div class="form-group">
                            <label for="descripcion" class="font-weight-bold">Descripción<span
                                    class="text-danger">*</span></label>
                            <textarea type="text" class="form-control" id="descripcion" name="descripcion"
                                placeholder="Ingrese la descripción del documento"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" form="formGuardarTipoDoc">Guardar</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(function() {
            let tdocumentosMap = {}

            let tabla = $('#tdocumentosTable').DataTable({
                ajax: "/regcas_tipdocs/indexWithTrashed",
                columns: [{
                        data: 'codtipdoc'
                    },
                    {
                        data: 'coddocsunat'
                    },
                    {
                        data: 'descripcion'
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
                        data: "codtipdoc",
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        render: function(data, type, row) {
                            tdocumentosMap[data] = row
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

            $('#btnRegTipoDoc').on('click', function() {
                $('#formGuardarTipoDoc')[0].reset()
                let validator = $("#formGuardarTipoDoc").validate()
                validator.resetForm()
                $('#modalGuardarTipoDoc .modal-title').text('Registrar Tipo de Documento')
                $('#modalGuardarTipoDoc').modal('show')
            })

            $("#formGuardarTipoDoc").validate({
                errorElement: 'div',
                rules: {
                    coddocsunat: {
                        required: true,
                        minlength: 2,
                        maxlength: 2
                    },
                    descripcion: {
                        required: true
                    }
                },
                messages: {
                    coddocsunat: {
                        required: 'Este campo es obligatorio',
                        minlength: 'Debe escribir 2 caracteres',
                        maxlength: 'Debe escribir 2 caracteres'
                    },
                    descripcion: {
                        required: 'Este campo es obligatorio'
                    },
                },
                errorPlacement: function(error, element) {
                    error.addClass("invalid-feedback")
                    error.insertAfter(element)
                },
                submitHandler: function(form) {
                    Swal.fire({
                        title: "Confirmación",
                        text: "¿Desea guardar los datos ingresados?",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3b5998",
                        cancelButtonColor: "#d33",
                        cancelButtonText: "Cancelar",
                        confirmButtonText: "Confirmar"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            guardartipoDoc()
                        }
                    })
                }
            })

            function guardartipoDoc() {
                let formData = new FormData($('#formGuardarTipoDoc')[0])
                let codtipdoc = $('#codtipdoc').val()
                if (codtipdoc) formData.append('codtipdoc', codtipdoc)

                let url = codtipdoc ? '/regcas_tipdocs/update' : '/regcas_tipdocs/store'
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
                            $('#modalGuardarTipoDoc').modal('hide')
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

            $('#tdocumentosTable').on('click', '.btn-editar', function() {
                $('#formGuardarTipoDoc')[0].reset()
                let validator = $("#formGuardarTipoDoc").validate()
                validator.resetForm()
                let id = $(this).data('id')
                let fila = tdocumentosMap[id]

                $('#codtipdoc').val(fila.codtipdoc)
                $('#coddocsunat').val(fila.coddocsunat)
                $('#descripcion').val(fila.descripcion)

                $('#modalGuardarTipoDoc .modal-title').text('Editar Tipo de Documento')
                $('#modalGuardarTipoDoc').modal('show')
            })

            $('#tdocumentosTable').on('click', '.btn-eliminar', function() {
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
                        eliminarTDocumento(id)
                    }
                })
            })

            function eliminarTDocumento(id) {
                $.ajax({
                    url: `/regcas_tipdocs/destroy/${id}`,
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

            $('#tdocumentosTable').on('click', '.btn-restaurar', function() {
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
                        restaurarTDocumento(id)
                    }
                })
            })

            function restaurarTDocumento(id) {
                $.ajax({
                    url: `/regcas_tipdocs/restore/${id}`,
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
