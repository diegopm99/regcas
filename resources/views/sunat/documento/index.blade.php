@extends('layout.app')

@section('title', 'Documentos')

@push('styles')
    <style>
        .card {
            border: 1px solid #dee2e6;
        }

        .bootstrap-select .btn {
            border: 1px solid #ced4da;
            background-color: white;
            color: #777 !important;
        }

        .bootstrap-select .dropdown-menu li a span.text {
            display: inline-block;
            max-width: 300px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            vertical-align: middle;
            font-size: .8rem;
        }

        .bootstrap-select .filter-option-inner-inner {
            display: inline-block;
            max-width: 300px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            vertical-align: middle;
            font-size: .8rem;
        }

        .dataTables_filter {
            display: none !important;
        }
    </style>
@endpush

@section('content')
    <div class="mb-3">
        <h2>Reportes</h2>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <h5>Filtro de Comprobante</h5>
            <div class="row">
                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="mb-3">
                        <label for="filtro-anno" class="form-label">Año</label>
                        <select name="filtro-anno" id="filtro-anno" class="selectpicker w-100" data-live-search="true"
                            data-size="8">
                            <option value="" selected>--Seleccionar--</option>
                            @foreach ($anios as $anio)
                                <option value="{{ $anio->anoproc }}">{{ $anio->anoproc }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="mb-3">
                        <label for="filtro-fuente" class="form-label">Fuente de Financiamiento</label>
                        <select name="filtro-fuente" id="filtro-fuente" class="form-control" disabled>
                            <option value="" disabled selected>--Seleccionar--</option>
                        </select>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="mb-3">
                        <label for="filtro-numcp" class="form-label">Número</label>
                        <input type="text" class="form-control" placeholder="Nº de comprobante" name="filtro-numcp"
                            id="filtro-numcp">
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="mb-3">
                        <label for="filtro-retenc" class="form-label">Tipo de Retención</label>
                        <select name="filtro-retenc" id="filtro-retenc" class="selectpicker w-100" data-live-search="true"
                            data-size="8">
                            <option value="" selected>--Seleccionar--</option>
                            @foreach ($retenciones as $tipo)
                                <option value="{{ $tipo->codretenc }}">{{ $tipo->descripcion }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="mb-3">
                        <label for="filtro-numruc" class="form-label">Nº de RUC del Proveedor</label>
                        <input type="text" class="form-control" placeholder="Nº de ruc" name="filtro-numruc"
                            id="filtro-numruc">
                    </div>
                </div>
            </div>
            <h5>Filtro de Tributación</h5>
            <div class="row">
                <div class="col-sm-4 col-lg-3">
                    <div class="mb-3">
                        <label for="filtro-anoproc" class="form-label">Periodo Tributario</label>
                        <select name="filtro-anoproc" id="filtro-anoproc" class="selectpicker w-100" data-live-search="true"
                            data-size="8">
                            <option value="" selected>--Seleccionar--</option>
                            @foreach ($anios as $anio)
                                <option value="{{ $anio->anoproc }}">{{ $anio->anoproc }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-sm-8 col-lg-5">
                    <p class="mb-2">Rango de fechas</p>
                    <div class="d-flex align-items-center">
                        <input class="form-control mr-1" type="date" name="fecha-inicio" id="fecha-inicio">
                        <span>a</span>
                        <input class="form-control ml-1" type="date" name="fecha-fin" id="fecha-fin">
                    </div>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-4 d-flex align-items-end">
                    <div class="mb-3 w-100 d-flex">
                        <button class="btn btn-primary w-50 mr-1" type="button" id="btnFiltrar">
                            <i class="fa-solid fa-filter"></i> Filtrar
                        </button>
                        <button class="btn btn-secondary w-50" type="button" id="btnLimpiar">
                            <i class="fa-solid fa-eraser"></i> Limpiar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <div class="d-flex justify-content-between mb-3">
                <button class="btn btn-secondary me-1" id="btnExcel">
                    <i class="fa-solid fa-file-excel"></i> Excel
                </button>
                <button class="btn btn-primary me-1" id="reload">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                    Recargar</button>
            </div>

            <table id="documentosTable" class="table table-striped w-100">
                <thead class="align-middle">
                    <tr>
                        <th>Comprobante</th>
                        <th>Año Comp.</th>
                        <th>Mes Comp.</th>
                        <th>Fuente Comp.</th>
                        <th>Codigo Fuente</th>
                        <th>N° Comp.</th>
                        <th>Fecha Comp.</th>
                        <th>N° SIAF</th>
                        <th>Orden</th>
                        <th>Tipo Ord.</th>
                        <th>Año Ord.</th>
                        <th>N° Ord.</th>
                        <th>Fecha Ord.</th>
                        <th>Cuenta Cont.</th>
                        <th>Serie Doc.</th>
                        <th>N° Doc.</th>
                        <th>Fecha Doc.</th>
                        <th>Fecha Venc. Doc.</th>
                        <th>RUC</th>
                        <th>Proveedor</th>
                        <th>Gravable</th>
                        <th>No Gravable</th>
                        <th>IGV</th>
                        <th>Penalidad</th>
                        <th>Garantía</th>
                        <th>Total</th>
                        <th>ID Retenc.</th>
                        <th>Tipo Retenc.</th>
                        <th>Serie Retenc.</th>
                        <th>Nº Retenc.</th>
                        <th>Fecha Retenc.</th>
                        <th>Retención</th>
                        <th>Coactivo</th>
                        <th>Detracción</th>
                        <th>Neto</th>
                        <th>Observación</th>
                        <th>Año tributación</th>
                        <th>Mes tributación</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(function() {
            $('#filtro-anno').change(function() {
                let anoproc = $(this).val()
                let $select = $('#filtro-fuente')
                if (anoproc) {
                    $select.prop('disabled', true)
                    cargarFuentes(anoproc, $select)
                } else {
                    $select.empty()
                    $select.append(
                        `<option value="" disabled selected>--Seleccionar--</option>`
                    )
                    $select.prop('disabled', true)
                }
            })

            function cargarFuentes(anoproc, $select) {
                $.get(`/regcas_fuente/filter/${anoproc}`).then(response => {
                    if (response.success) {
                        $select.empty().append(
                            `<option value="" selected>--Seleccionar--</option>`
                        )
                        response.data.forEach(fuente => {
                            $select.append(
                                `<option value="${fuente.ftocodigo}">${fuente.ftocodigo} - ${fuente.ftodesc}</option>`
                            )
                        })
                        $select.prop('disabled', false)
                    } else {
                        toastr.error(response.message)
                    }
                }).catch(() => {
                    toastr.error('Error de conexión con el servidor')
                })
            }

            let tabla = $('#documentosTable').DataTable({
                ajax: "/regcas_cpdocs/index",
                autoWidth: false,
                responsive: true,
                columnDefs: [{
                    className: 'text-nowrap align-middle',
                    targets: '_all'
                }],
                columns: [{
                        data: 'codcpdoc',
                        render: function(data, type, row) {
                            return `${row.num_comp}/${obtenerMes(row.fecha_comp)}/${row.fuente_comp}`
                        }
                    },
                    {
                        data: 'fecha_comp',
                        render: function(data) {
                            return data ? obtenerAnno(data) : ""
                        },
                        visible: false,
                        searchable: true
                    },
                    {
                        data: 'fecha_comp',
                        render: function(data, type, row) {
                            return data ? obtenerMes(data) : ""
                        },
                        visible: false,
                        searchable: true
                    },
                    {
                        data: 'fuente_comp'
                    },
                    {
                        data: 'fuente.ftocodigo',
                        visible: false,
                        searchable: true
                    },
                    {
                        data: 'num_comp'
                    },
                    {
                        data: 'fecha_comp',
                    },
                    {
                        data: 'num_siaf',
                    },
                    {
                        data: 'num_orden',
                        render: function(data, type, row) {
                            return `${data}/${row.torden}`
                        }
                    },
                    {
                        data: 'torden'
                    },
                    {
                        data: 'fecha_orden',
                        render: function(data, type, row) {
                            return data ? obtenerAnno(data) : ""
                        },
                        visible: false,
                        searchable: true
                    },
                    {
                        data: 'num_orden'
                    },
                    {
                        data: 'fecha_orden'
                    },
                    {
                        data: 'cuenta_contable.cuenta'
                    },
                    {
                        data: "serie_doc"
                    },
                    {
                        data: "num_doc"
                    },
                    {
                        data: "fecha_doc"
                    },
                    {
                        data: "fecha_venc",
                        render: function(data, type, row) {
                            return data ? data : "s.f."
                        }
                    },
                    {
                        data: "ruc"
                    },
                    {
                        data: "proveedor"
                    },
                    {
                        data: "v_grav",
                        visible: false,
                        searchable: true
                    },
                    {
                        data: "v_nograv",
                        visible: false,
                        searchable: true
                    },
                    {
                        data: "igv",
                        visible: false,
                        searchable: true
                    },
                    {
                        data: "penalidad",
                        visible: false,
                        searchable: true
                    },
                    {
                        data: "garantia",
                        visible: false,
                        searchable: true
                    },
                    {
                        data: "total"
                    },
                    {
                        data: "tretenc",
                        visible: false,
                        searchable: true
                    },
                    {
                        data: "tipo_retencion",
                        render: function(data, type, row) {
                            return data && data.descripcion ? data.descripcion : "-"
                        }
                    },
                    {
                        data: "serie_retenc",
                        render: function(data, type, row) {
                            return data ? data : "-"
                        }
                    },
                    {
                        data: "num_retenc",
                        render: function(data, type, row) {
                            return data ? data : "-"
                        }
                    },
                    {
                        data: "fecha_retenc",
                        render: function(data, type, row) {
                            return data ? data : "-"
                        }
                    },
                    {
                        data: "retencion",
                        visible: false,
                        searchable: true
                    },
                    {
                        data: "coactivo",
                        visible: false,
                        searchable: true
                    },
                    {
                        data: "detraccion",
                        visible: false,
                        searchable: true
                    },
                    {
                        data: "neto"
                    },
                    {
                        data: "obs",
                        render: function(data, type, row) {
                            return data ? data : "-"
                        },
                        visible: false,
                        searchable: true
                    },
                    {
                        data: "anno"
                    },
                    {
                        data: "mes"
                    }
                ],
                buttons: [{
                    extend: 'excelHtml5',
                    title: 'Reporte de Documentos',
                    exportOptions: {
                        columns: function(idx, data, node) {
                            return idx !== 4 && idx !== 26
                        }
                    }
                }],
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/2.3.3/i18n/es-ES.json'
                },
                pageLength: 10,
                order: []
            })

            function obtenerMes(fecha) {
                let [dia, mes, anno] = fecha.split("-");
                return String(new Date(`${anno}-${mes}-${dia}`).getMonth() + 1).padStart(2, '0')
            }

            function obtenerAnno(fecha) {
                let [dia, mes, anno] = fecha.split("-");
                return anno
            }

            $('#btnExcel').on('click', function() {
                tabla.button('.buttons-excel').trigger()
            })

            $('#reload').on('click', function() {
                tabla.ajax.reload()
            })

            $('#filtro-numruc').on('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '')
                if (this.value.length > 11) {
                    this.value = this.value.slice(0, 11)
                }
            })

            $('#filtro-numcp').on('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '')
                if (this.value.length > 6) {
                    this.value = this.value.slice(0, 6)
                }
            })

            $('#btnFiltrar').on('click', function() {
                let annocomp = $('#filtro-anno').val()
                let ffcomp = $('#filtro-fuente').val()
                let numcomp = $('#filtro-numcp').val()
                let tretencId = $('#filtro-retenc').val()
                let numruc = $('#filtro-numruc').val()

                let annoproc = $('#filtro-anoproc').val()
                let fechaInicio = $('#fecha-inicio').val()
                let fechaFin = $('#fecha-fin').val()

                tabla.columns().search('')
                $.fn.dataTable.ext.search = []

                if (annocomp) tabla.column(1).search('^' + annocomp + '$', true, false)
                if (ffcomp) tabla.column(4).search('^' + ffcomp + '$', true, false)
                if (numcomp) tabla.column(5).search('^' + numcomp + '$', true, false)
                if (tretencId) tabla.column(26).search('^' + tretencId + '$', true, false)
                if (numruc) tabla.column(18).search('^' + numruc + '$', true, false)

                if (annoproc) tabla.column(36).search('^' + annoproc + '$', true, false)
                if (fechaInicio || fechaFin) {
                    $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {

                        let anno = data[36]
                        let mes = data[37]

                        if (!anno || !mes) return false
                        mes = mes.padStart(2, '0')

                        let fechaRegistro = new Date(`${anno}-${mes}-01`)
                        let inicio = fechaInicio ? new Date(fechaInicio) : null
                        let fin = fechaFin ? new Date(fechaFin) : null

                        if (
                            (!inicio || fechaRegistro >= inicio) &&
                            (!fin || fechaRegistro <= fin)
                        ) {
                            return true
                        }
                        return false
                    })
                }
                tabla.draw()
            })

            $('#btnLimpiar').on('click', function() {
                $('#filtro-anno').val(null).trigger('change')
                $('#filtro-fuente').val(null).trigger('change')
                $('#filtro-numcp').val(null).trigger('change')
                $('#filtro-retenc').val(null).trigger('change')
                $('#filtro-numruc').val(null).trigger('change')

                $('#filtro-anoproc').val(null).trigger('change')
                $('#fecha-inicio').val(null).trigger('change')
                $('#fecha-fin').val(null).trigger('change')

                $.fn.dataTable.ext.search = []
                tabla.columns().search('')
                tabla.draw()
            })
        })
    </script>
@endpush
