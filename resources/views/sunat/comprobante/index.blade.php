@extends('layout.app')

@section('title', 'Comprobante de pago')

@push('styles')
    <style>
        .card {
            border: 1px solid #dee2e6;
        }

        .detail-item {
            margin-bottom: .5rem;
        }

        .detail-label {
            display: block;
        }

        .detail-value {
            font-weight: bold;
            font-size: 14px;
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

        .is-invalid,
        .is-valid {
            background-image: none !important;
            padding-right: 0.75rem !important;
        }

        .texto-corto {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        @media (max-width: 991px) {
            .th-acciones {
                width: 25% !important;
            }
        }
    </style>
@endpush

@section('content')
    <div class="mb-3">
        <h1>Consultar comprobante</h1>
    </div>

    <div id="buscador">
        <div class="card">
            <div class="card-body">
                <form id="formBuscarComprobante">
                    <div class="row">
                        <div class="col-sm-6 col-lg-3 mb-3">
                            <label for="anoproc" class="form-label">Año</label>
                            <select name="anoproc" id="anoproc" class="selectpicker w-100" data-live-search="true"
                                data-size="8" title="Seleccionar año">
                                @foreach ($anios as $anio)
                                    <option value="{{ $anio->anoproc }}">{{ $anio->anoproc }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6 col-lg-3 mb-3">
                            <label for="cpcfuente" class="form-label">Fuente</label>
                            <select name="cpcfuente" id="cpcfuente" class="form-control" disabled>
                                <option value="" disabled selected>Seleccionar fuente</option>
                            </select>
                        </div>
                        <div class="col-sm-6 col-lg-3 mb-3">
                            <label for="cpcnumero" class="form-label">Número</label>
                            <input name="cpcnumero" id="cpcnumero" class="form-control" type="text"
                                placeholder="Ingresar el nº del comprobante">
                        </div>
                        <div class="col-sm-6 col-lg-3 align-self-end d-flex mb-3">
                            <button class="btn btn-primary w-50 mr-1" type="submit" id="btnBuscar"><i class="fa fa-search"
                                    aria-hidden="true"></i></button>
                            <button id="btnLimpiar" class="btn btn-secondary w-50" type="button"><i class="fa fa-eraser"
                                    aria-hidden="true"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="detalleComprobante" style="display: none;">
        <!-- Información Principal del Comprobante -->
        <div class="row" id="infoGeneral">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-light">
                        <h6 class="mb-0 ">INFORMACIÓN DEL COMPROBANTE</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 detail-item">
                                <span class="detail-label">Número de Comprobante:</span>
                                <span class="detail-value" id="comprobanteNumero"></span>
                            </div>
                            <div class="col-md-4 detail-item">
                                <span class="detail-label">Fecha de Emisión:</span>
                                <span class="detail-value" id="comprobanteFecha"></span>
                            </div>
                            <div class="col-md-4 detail-item">
                                <span class="detail-label">Registro SIAF:</span>
                                <span class="detail-value" id="comprobanteSiaf"></span>
                            </div>
                            <div class="col-md-4 detail-item">
                                <span class="detail-label">Fuente de Financiamiento:</span>
                                <span class="detail-value" id="comprobanteFuente"></span>
                            </div>
                            <div class="col-md-4 detail-item">
                                <span class="detail-label">Referencia:</span>
                                <span class="detail-value" id="comprobanteReferencia"></span>
                            </div>
                            <div class="col-md-4 detail-item">
                                <span class="detail-label">Monto Total:</span>
                                <span class="detail-value" id="comprobanteMonto"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-light">
                        <h6 class="mb-0 ">INFORMACIÓN DEL PROVEEDOR</h6>
                    </div>
                    <div class="card-body">
                        <div class="detail-item">
                            <span class="detail-label">Razón Social:</span>
                            <span class="detail-value" id="comprobanteRazonSocial"></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">RUC:</span>
                            <span class="detail-value" id="comprobanteRUC"></span>
                            <div class="text-center" id="ruc-prov">
                                <button type="button" class="btn btn-sm btn-primary" id="btnRegRuc">Registrar RUC</button>
                                <button type="button" class="btn btn-sm btn-primary" id="btnRegProv">Registrar
                                    Proveedor</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Facturas Asociadas -->
        <div class="">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0 ">DOCUMENTOS ASOCIADOS</h6>
                </div>
                <div class="card-body">
                    <button class="btn btn-warning mb-3 text-dark" id="btnAgregarDocumento"><i class="fa fa-plus-circle"
                            aria-hidden="true"></i> Agregar</button>
                    <table class="table table-striped nowrap w-100" id="documentosTable">
                        <thead>
                            <tr>
                                <th>Fecha Doc.</th>
                                <th style="width: 20%">Tipo Documento</th>
                                <th>N° Documento</th>
                                <th>Fecha Emisión</th>
                                <th>Total</th>
                                <th>Retención</th>
                                <th>Estado</th>
                                <th class="th-acciones" style="width: 15%">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Retenciones -->
        <div>
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0 ">RETENCIONES APLICADAS</h6>
                </div>
                <div class="card-body">
                    <div class="row" id="retencionesContainer">
                        <!--Dinamico-->
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal registrar documento --}}
        <div class="modal fade" id="modalRegDocumento" tabindex="-1" role="dialog"
            aria-labelledby="modalRegDocumento" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalRegDocumentoTitle">Registrar Documento</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="formRegDocumento">
                            <input type="text" id="codcpdoc" name="codcpdoc" hidden>
                            <!-- Datos de la Orden -->
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-shopping-cart mr-2"></i>Datos de la Orden
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 col-xl-3">
                                            <div class="form-group">
                                                <label for="tipo-orden" class="font-weight-bold">Tipo de Orden <span
                                                        class="text-danger">*</span></label>
                                                <select class="form-control required" id="tipo-orden" name="tipo-orden">
                                                    <option value="" disabled selected>--Seleccionar--</option>
                                                    <option value="OC">Orden de Compra (C)</option>
                                                    <option value="OS">Orden de Servicio (S)</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xl-3">
                                            <div class="form-group">
                                                <label for="nro-orden" class="font-weight-bold">Número de Orden <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control required" id="nro-orden"
                                                    name="nro-orden" placeholder="Ingrese el nº de orden">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xl-3">
                                            <div class="form-group">
                                                <label for="fecha_orden" class="font-weight-bold">Fecha de Orden <span
                                                        class="text-danger">*</span></label>
                                                <input type="date" class="form-control required" id="fecha-orden"
                                                    name="fecha-orden">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xl-3">
                                            <div class="form-group">
                                                <label for="cuenta" class="font-weight-bold">Cuenta <span
                                                        class="text-danger">*</span></label>
                                                <select class="form-control required" id="cuenta" name="cuenta"
                                                    disabled>
                                                    <option value="" selected disabled>--Seleccionar--</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Datos del Documento -->
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-file-alt mr-2"></i>Datos del Documento
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 col-xl-3">
                                            <div class="form-group">
                                                <label for="tipo-documento" class="font-weight-bold d-block">Tipo de
                                                    Documento
                                                    <span class="text-danger">*</span></label>
                                                <select name="tipo-documento" id="tipo-documento"
                                                    class="selectpicker w-100 required" data-live-search="true"
                                                    data-size="8" title="--Seleccionar--">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xl-3">
                                            <div class="form-group">
                                                <label for="nro-serie" class="font-weight-bold">Número de Serie
                                                    <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control required" id="nro-serie"
                                                    name="nro-serie" placeholder="Ingrese el nº de serie">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xl-3">
                                            <div class="form-group">
                                                <label for="nro-documento" class="font-weight-bold">Número de Documento
                                                    <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control required" id="nro-documento"
                                                    name="nro-documento" placeholder="Ingrese el nº de documento">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xl-3">
                                            <div class="form-group">
                                                <label for="fecha-emision" class="font-weight-bold">Fecha de Emisión <span
                                                        class="text-danger">*</span></label>
                                                <input type="date" class="form-control required" id="fecha-emision"
                                                    name="fecha-emision">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xl-3" id='fecha-venc-container'>
                                            <div class="form-group">
                                                <label for="fecha-venc" class="font-weight-bold">Fecha de Vencimiento
                                                    <span class="text-danger">*</span></label>
                                                <input type="date" class="form-control required" id="fecha-venc"
                                                    name="fecha-venc">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row justify-content-center">
                                        <div class="col-md-6">
                                            <div class="card border-0 bg-light">
                                                <div class="card-body">
                                                    <h6 class="card-title text-center font-weight-bold mb-2">Montos del
                                                        Documento</h6>
                                                    <div class="row">
                                                        <div class="col-sm-6 col-md-12 col-lg-6">
                                                            <div class="form-group">
                                                                <label for="gravable" class="font-weight-bold">Gravable
                                                                    <span class="text-danger">*</span></label>
                                                                <div class="input-group input-group-sm">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text">S/</span>
                                                                    </div>
                                                                    <input type="text"
                                                                        class="form-control text-right monto required"
                                                                        id="gravable" name="gravable"
                                                                        placeholder="0.00">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6 col-md-12 col-lg-6">
                                                            <div class="form-group">
                                                                <label for="no-gravable" class="font-weight-bold">No
                                                                    Gravable</label>
                                                                <div class="input-group input-group-sm">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text">S/</span>
                                                                    </div>
                                                                    <input type="text"
                                                                        class="form-control text-right monto no-required"
                                                                        id="no-gravable" name="no-gravable"
                                                                        placeholder="0.00">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card border-0 bg-light">
                                                <div class="card-body">
                                                    <h6 class="card-title text-center font-weight-bold mb-2">Impuestos</h6>
                                                    <div class="row">
                                                        <div class="col-sm-6 col-md-12 col-lg-6">
                                                            <div class="form-group">
                                                                <label for="igv" class="font-weight-bold">IGV</label>
                                                                <div class="input-group input-group-sm">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text">S/</span>
                                                                    </div>
                                                                    <input type="text"
                                                                        class="form-control text-right monto no-required"
                                                                        id="igv" name="igv" placeholder="0.00">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6 col-md-12 col-lg-6">
                                                            <div class="form-group">
                                                                <label for="total" class="font-weight-bold">Total <span
                                                                        class="text-danger">*</span></label>
                                                                <div class="input-group input-group-sm">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text">S/</span>
                                                                    </div>
                                                                    <input type="text"
                                                                        class="form-control text-right font-weight-bold monto required"
                                                                        id="total" name="total" placeholder="0.00">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card border-0 bg-light">
                                                <div class="card-body">
                                                    <h6 class="card-title text-center font-weight-bold mb-2">Descuentos
                                                    </h6>
                                                    <div class="row">
                                                        <div class="col-sm-6 col-md-12 col-lg-6">
                                                            <div class="form-group">
                                                                <label for="penalidad"
                                                                    class="font-weight-bold">Penalidad</label>
                                                                <div class="input-group input-group-sm">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text">S/</span>
                                                                    </div>
                                                                    <input type="text"
                                                                        class="form-control text-right monto no-required"
                                                                        id="penalidad" name="penalidad"
                                                                        placeholder="0.00">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6 col-md-12 col-lg-6">
                                                            <div class="form-group">
                                                                <label for="garantia"
                                                                    class="font-weight-bold">Garantía</label>
                                                                <div class="input-group input-group-sm">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text">S/</span>
                                                                    </div>
                                                                    <input type="text"
                                                                        class="form-control text-right monto no-required"
                                                                        id="garantia" name="garantia"
                                                                        placeholder="0.00">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Datos del Documento de Retención -->
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-percentage mr-2"></i>Datos de Retención
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 col-xl-3">
                                            <div class="form-group">
                                                <label for="tipo-retenc" class="font-weight-bold">Tipo de
                                                    Retención</label>
                                                <select class="form-control " id="tipo-retenc" name="tipo-retenc">
                                                    <option value="" disabled selected>--Seleccionar--</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xl-3">
                                            <div class="form-group">
                                                <label for="serie-retenc" class="font-weight-bold">Número de Serie</label>
                                                <input type="text" class="form-control" id="serie-retenc"
                                                    name="serie-retenc" placeholder="Ingrese el nº de serie">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xl-3">
                                            <div class="form-group">
                                                <label for="nro-retenc" class="font-weight-bold">Número de
                                                    Documento</label>
                                                <input type="text" class="form-control" id="nro-retenc"
                                                    name="nro-retenc" placeholder="Ingrese el nº de documento">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xl-3">
                                            <div class="form-group">
                                                <label for="fecha-retenc" class="font-weight-bold">Fecha de
                                                    Emisión</label>
                                                <input type="date" class="form-control" id="fecha-retenc"
                                                    name="fecha-retenc">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card border-0 bg-light mb-0">
                                        <div class="card-body">
                                            <h6 class="card-title text-center font-weight-bold mb-2">Montos de Detracción
                                            </h6>
                                            <div class="row">
                                                <div class="col-sm-6 col-lg-3">
                                                    <div class="form-group">
                                                        <label for="retencion" class="font-weight-bold">Retención</label>
                                                        <div class="input-group input-group-sm">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">S/</span>
                                                            </div>
                                                            <input type="text"
                                                                class="form-control text-right monto no-required"
                                                                id="retencion" name="retencion" placeholder="0">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 col-lg-3">
                                                    <div class="form-group">
                                                        <label for="coactivo" class="font-weight-bold">Coactivo</label>
                                                        <div class="input-group input-group-sm">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">S/</span>
                                                            </div>
                                                            <input type="text"
                                                                class="form-control text-right monto no-required"
                                                                id="coactivo" name="coactivo" placeholder="0.00">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 col-lg-3">
                                                    <div class="form-group">
                                                        <label for="detraccion"
                                                            class="font-weight-bold">Detracción</label>
                                                        <div class="input-group input-group-sm">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">S/</span>
                                                            </div>
                                                            <input type="text"
                                                                class="form-control text-right monto no-required"
                                                                id="detraccion" name="detraccion" placeholder="0">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 col-lg-3">
                                                    <div class="form-group">
                                                        <label for="neto" class="font-weight-bold">Neto</label>
                                                        <div class="input-group input-group-sm">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">S/</span>
                                                            </div>
                                                            <input type="text"
                                                                class="form-control text-right monto no-required"
                                                                id="neto" name="neto" placeholder="0.00">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Observaciones -->
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="card-title mb-0">
                                        <i class="fa fa-comments mr-2"></i>Observaciones
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <textarea type="text" class="form-control" rows="3" id="observaciones" name="observaciones"
                                        placeholder="Ingresar observaciones"></textarea>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success" form="formRegDocumento">Guardar</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal registrar proveedor --}}
        <div class="modal fade" id="modalGuardarProveedor" tabindex="-1" role="dialog"
            aria-labelledby="modalGuardarProveedor" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Registrar Proveedor</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="formGuardarProveedor">
                            <input name="entcodigo" id="entcodigo" hidden>
                            <input name="uejcodigo" id="uejcodigo" hidden>
                            <input name="proveedor" id="proveedor" hidden>
                            <div class="form-group">
                                <label for="pronombres" class="font-weight-bold">Razon Social<span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="pronombres" name="pronombres"
                                    placeholder="Ingrese el nombre o razon social">
                            </div>
                            <div class="form-group">
                                <label for="pronumruc" class="font-weight-bold">Nº de RUC<span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="pronumruc" name="pronumruc"
                                    placeholder="Ingrese el nº de ruc">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" form="formGuardarProveedor">Guardar</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal detalle documento --}}
        <div class="modal fade" id="modalDetalleDoc" tabindex="-1" role="dialog" aria-labelledby="modalDetalleDoc"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detalle del documento</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card" id="infoOrden">
                            <div class="card-header bg-light">
                                <h5 class="card-title m-0"><i class="fas fa-shopping-cart mr-2"></i>Información de la
                                    Orden</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-6 col-lg-3">
                                        <div class="detail-item">
                                            <span class="detail-label">Tipo de Orden</span>
                                            <span class="detail-value" id="detalleTipoOrden"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-3">
                                        <div class="detail-item">
                                            <span class="detail-label">Número de Orden</span>
                                            <span class="detail-value" id="detalleNumeroOrden"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-3">
                                        <div class="detail-item">
                                            <span class="detail-label">Fecha de Orden</span>
                                            <span class="detail-value" id="detalleFechaOrden"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-3">
                                        <div class="detail-item">
                                            <span class="detail-label">Cuenta Contable</span>
                                            <span class="detail-value" id="detalleCuentaContable"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card" id="infoDocumento">
                            <div class="card-header bg-light">
                                <h5 class="card-title m-0"><i class="fas fa-file-alt mr-2"></i>Información del Documento
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-sm-6 col-lg-4">
                                        <div class="detail-item">
                                            <span class="detail-label">Tipo de Documento</span>
                                            <span class="detail-value texto-corto" id="detalleTipoDocumento"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-4">
                                        <div class="detail-item">
                                            <span class="detail-label">Número de Documento</span>
                                            <span class="detail-value" id="detalleNumeroDocumento"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-4">
                                        <div class="detail-item">
                                            <span class="detail-label">Fecha de Emision</span>
                                            <span class="detail-value" id="detalleFechaDocumento"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-4">
                                        <div class="detail-item">
                                            <span class="detail-label">Fecha de Vencimiento</span>
                                            <span class="detail-value" id="detalleFechaVencimiento"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row justify-content-center">
                                    <div class="col-sm-6">
                                        <div class="card border-0 bg-light">
                                            <div class="card-body">
                                                <h6 class="card-title text-center font-weight-bold mb-2">BASE IMPONIBLE
                                                </h6>
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="detail-item text-center">
                                                            <span class="detail-label">Gravable</span>
                                                            <span class="detail-value" id="detalleGravable"></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="detail-item text-center">
                                                            <span class="detail-label">No Gravable</span>
                                                            <span class="detail-value" id="detalleNoGravable"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="card border-0 bg-light">
                                            <div class="card-body">
                                                <h6 class="card-title text-center font-weight-bold mb-2">IMPUESTOS Y TOTAL
                                                </h6>
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="detail-item text-center">
                                                            <span class="detail-label">IGV</span>
                                                            <span class="detail-value" id="detalleIGV"></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="detail-item text-center">
                                                            <span class="detail-label">Total</span>
                                                            <span class="detail-value text-dark" id="detalleTotal"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="card border-0 bg-light">
                                            <div class="card-body">
                                                <h6 class="card-title text-center font-weight-bold mb-2">DESCUENTOS
                                                </h6>
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="detail-item text-center">
                                                            <span class="detail-label">Garantía</span>
                                                            <span class="detail-value text-danger" id="detalleGarantia"></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="detail-item text-center">
                                                            <span class="detail-label">Penalidad</span>
                                                            <span class="detail-value text-danger" id="detallePenalidad"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card" id="infoRetencion">
                            <div class="card-header bg-light">
                                <h5 class="card-title m-0"><i class="fas fa-percentage mr-2"></i>Información de Retención
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-sm-6 col-lg-4">
                                        <div class="detail-item">
                                            <span class="detail-label">Tipo de Retención</span>
                                            <span class="detail-value texto-corto" id="detalleTipoRetenc"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-4">
                                        <div class="detail-item">
                                            <span class="detail-label">Número de Documento</span>
                                            <span class="detail-value" id="detalleNumeroRetenc"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-4">
                                        <div class="detail-item">
                                            <span class="detail-label">Fecha de Emisión</span>
                                            <span class="detail-value" id="detalleFechaRetenc"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row text-center">
                                    <div class="col-6 col-lg-3">
                                        <div class="card border-0 bg-light">
                                            <div class="card-body py-2">
                                                <div class="detail-item">
                                                    <span class="detail-label">Retención</span>
                                                    <span class="detail-value text-danger" id="detalleRetencion"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6 col-lg-3">
                                        <div class="card border-0 bg-light">
                                            <div class="card-body py-2">
                                                <div class="detail-item">
                                                    <span class="detail-label">Coactivo</span>
                                                    <span class="detail-value text-danger" id="detalleCoactivo"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6 col-lg-3">
                                        <div class="card border-0 bg-light">
                                            <div class="card-body py-2">
                                                <div class="detail-item">
                                                    <span class="detail-label">Detracción</span>
                                                    <span class="detail-value text-danger" id="detalleDetraccion"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6 col-lg-3">
                                        <div class="card border-0 bg-light">
                                            <div class="card-body py-2">
                                                <div class="detail-item">
                                                    <span class="detail-label">Neto</span>
                                                    <span class="detail-value text-dark" id="detalleNeto"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card" id="infoObservaciones">
                            <div class="card-header bg-light">
                                <h5 class="card-title m-0"><i class="fa fa-comments mr-2"></i>Observaciones
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="detail-item">
                                            <span class="detail-value" id="detalleObservaciones"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @push('scripts')
        <script>
            $(function() {
                //temporal
                //$('#detalleComprobante').show()

                $('#anoproc').change(function() {
                    let anoproc = $(this).val()
                    let $select = $('#cpcfuente')
                    if (anoproc) {
                        $select.prop('disabled', true)
                        cargarFuentes(anoproc, $select)
                    } else {
                        $select.empty()
                        $select.append(
                            `<option value="" disabled selected>Seleccionar fuente</option>`
                        )
                        $select.prop('disabled', true)
                    }
                })

                function cargarFuentes(anoproc, $select) {
                    $.get(`/regcas_fuente/filter/${anoproc}`).then(response => {
                        if (response.success) {
                            $select.empty()
                            $select.append(
                                `<option value="" disabled selected>Seleccionar fuente</option>`
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

                $('#cpcnumero').on('input', function() {
                    this.value = this.value.replace(/[^0-9]/g, '')

                    if (this.value.length > 6) {
                        this.value = this.value.slice(0, 6)
                    }
                })

                $("#btnLimpiar").on('click', function() {
                    limpiarBuscador()
                })

                function limpiarBuscador() {
                    $('#formBuscarComprobante').find('select, textarea, input').val('').trigger('change')
                }

                function limpiarDetalle() {
                    $('#infoGeneral').find('.detail-value').text('')
                }

                $("#formBuscarComprobante").validate({
                    rules: {
                        anoproc: {
                            required: true,
                            maxlength: 4
                        },
                        cpcfuente: {
                            required: true,
                        },
                        cpcnumero: {
                            required: true,
                            maxlength: 6
                        }
                    },
                    messages: {
                        anoproc: {
                            required: "El año de procesamiento es obligatorio",
                            maxlength: "Máximo 4 caracteres"
                        },
                        cpcfuente: {
                            required: "La fuente es obligatoria",
                        },
                        cpcnumero: {
                            required: "El número de comprobante es obligatorio",
                            maxlength: "Máximo 6 caracteres"
                        }
                    },
                    errorPlacement: function(error, element) {
                        return false;
                    },
                    invalidHandler: function(event, validator) {
                        if (validator.errorList.length) {
                            let errorMessage = validator.errorList[0].message;
                            toastr.error(errorMessage);
                        }
                    },
                    submitHandler: function(form) {
                        buscarComprobante()
                    }
                })

                let comprobante = null

                function buscarComprobante() {
                    let params = $('#formBuscarComprobante').serialize()
                    $.ajax({
                        url: '/regcas_comprobante/find',
                        type: 'GET',
                        data: params,
                        success: function(response) {
                            console.log(response)
                            if (response.success && response.data) {
                                comprobante = response.data
                                cargarDetalleComprobante(comprobante)
                                toastr.success('Comprobante encontrado')
                            } else {
                                toastr.warning('No se encontraron resultados')
                            }
                        },
                        error: function() {
                            toastr.error('Error de conexión con el servidor')
                        }
                    })
                }

                function cargarDetalleComprobante(data) {
                    limpiarDetalle()
                    $('#comprobanteNumero').text(data.cpcnumero)
                    $('#comprobanteFecha').text(data.cpcfecha)
                    $('#comprobanteSiaf').text(data.ordnumsiaf)
                    $('#comprobanteFuente').text(data.fuente.ftodesc)
                    $('#comprobanteReferencia').text(data.cpcreferencia)
                    $('#comprobanteMonto').text(`S/ ${parseFloat(data.cpctotal).toFixed(2)}`)

                    if (data.proveedor) {
                        let nroRuc = data.proveedor.pronumruc
                        $('#comprobanteRazonSocial').text(data.proveedor.pronombres)
                        if (nroRuc) {
                            $('#comprobanteRUC').text(nroRuc)
                            $('#btnRegRuc').hide()
                            $('#btnRegProv').hide()
                        } else {
                            $('#btnRegRuc').show()
                            $('#btnRegProv').hide()
                        }
                    } else {
                        let nombreCompleto = [
                            data.ente.entpaterno,
                            data.ente.entmaterno,
                            data.ente.entnombres
                        ].filter(Boolean).join(' ')
                        $('#comprobanteRazonSocial').text(nombreCompleto)
                        $('#btnRegRuc').hide()
                        $('#btnRegProv').show()
                    }

                    documentosTable.ajax.reload()
                    renderRetenciones(data.retenciones || [])

                    $('#detalleComprobante').show()
                    $('html, body').animate({
                        scrollTop: $("#detalleComprobante").offset().top
                    }, 800)
                }

                function renderRetenciones(retenciones) {
                    let container = $('#retencionesContainer')
                    container.empty()

                    if (retenciones.length === 0) {
                        container.append(`
                        <div class="col-12 text-center">
                            <p class="text-muted">No hay retenciones aplicadas.</p>
                        </div>
                        `)
                        return
                    }

                    retenciones.forEach(retencion => {
                        let card = `
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-body py-3">
                                    <div class="row">
                                        <div class="col-7 col-xl-8">
                                            <h6>${retencion.conceptoPlla.condescripcion ? retencion.conceptoPlla.condescripcion : 'No especifíca'}</h6>
                                            <p class="mb-0">${retencion.conceptoPlla.tipoConcepto.tcodesc}</p>
                                            <p class="mb-0">${retencion.conceptoPlla.ente ? retencion.conceptoPlla.ente.entnombres : 'No especifíca'}</p>
                                        </div>
                                        <div class="col-5 col-xl-4 text-right">
                                            <span class="h5 text-danger">S/ ${parseFloat(retencion.cprmonttot).toFixed(2)}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `
                        container.append(card)
                    })
                }

                let documentosMap = {}
                let documentosTable = $('#documentosTable').DataTable({
                    ajax: {
                        url: '/regcas_cpdocs/filter',
                        type: 'GET',
                        data: function(data) {
                            if (comprobante) {
                                data.anoproc = comprobante.anoproc,
                                    data.cpcfuente = comprobante.cpcfuente,
                                    data.cpcnumero = comprobante.cpcnumero,
                                    data.uejcodigo = comprobante.uejcodigo
                            } else {
                                return false
                            }
                        }
                    },
                    columns: [{
                            data: 'fecha_doc',
                            visible: false,
                        },
                        {
                            data: 'tipo_documento.descripcion',
                            render: function(data, type, row) {
                                if (!data) return ''
                                const maxLength = 50
                                data = `${row.tipo_documento.coddocsunat} - ${data}`
                                return data.length > maxLength ?
                                    data.substring(0, maxLength) + '...' :
                                    data
                            }
                        },
                        {
                            data: 'num_doc',
                            className: 'text-center',
                            render: function(data, type, row) {
                                return `${row.serie_doc}-${row.num_doc}`
                            }
                        },
                        {
                            data: 'fecha_doc',
                            className: 'text-center',
                        },
                        {
                            data: 'total',
                            className: 'text-center',
                        },
                        {
                            data: 'tipo_retencion',
                            className: 'text-center',
                            render: function(data, type, row) {
                                if (data) {
                                    return data.descripcion
                                } else {
                                    return `<span class="badge badge-danger">No presenta</span>`
                                }
                            }
                        },
                        {
                            data: 'deleted_at',
                            className: 'text-center',
                            render: function(data, type, row) {
                                if (!data) {
                                    return `<span class="badge badge-success">Activo</span>`
                                } else {
                                    return `<span class="badge badge-danger">Eliminado</span>`
                                }
                            }
                        },
                        {
                            data: "codcpdoc",
                            orderable: false,
                            searchable: false,
                            className: 'text-center',
                            render: function(data, type, row) {
                                documentosMap[data] = row
                                if (row.deleted_at) {
                                    return `
                                        <button class="btn btn-warning btn-sm btn-restaurar" data-id="${data}">
                                            <i class="fa-solid fa-trash-restore"></i>
                                        </button>
                                    `
                                }
                                return `
                                    <button class="btn btn-info btn-sm btn-detalle" data-id="${data}">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                    <button class="btn btn-primary btn-sm btn-editar" data-id="${data}">
                                        <i class="fa-solid fa-pencil"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm btn-eliminar" data-id="${data}">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                `
                            }
                        }
                    ],
                    searching: false,
                    lengthChange: false,
                    language: {
                        url: 'https://cdn.datatables.net/plug-ins/2.3.3/i18n/es-ES.json'
                    },
                    responsive: true,
                    autoWidth: false,
                    pageLength: 10,
                    order: [
                        [0, 'desc']
                    ]
                })

                $('#btnAgregarDocumento').on('click', function() {
                    if (!comprobante.proveedor) {
                        Swal.fire({
                            title: "Adventencia",
                            text: "Primero debe registrar el proveedor",
                            icon: "warning"
                        });
                        return
                    } else if (!comprobante.proveedor.pronumruc) {
                        Swal.fire({
                            title: "Adventencia",
                            text: "Primero debe registrar el RUC del proveedor",
                            icon: "warning"
                        });
                        return
                    }
                    $('#formRegDocumento')[0].reset()
                    var validator = $('#formRegDocumento').validate()
                    validator.resetForm()
                    Promise.all([
                        cargarTiposRetencion($('#tipo-retenc')),
                        cargarTiposDocumento($('#tipo-documento'))
                    ]).then(() => {
                        $('.selectpicker').selectpicker('refresh')
                        $('#tipo-orden').trigger('change')
                        $('#tipo-documento').trigger('change')
                        $('#tipo-retencion').trigger('change')
                        $('#cuenta').trigger('change')
                        $('#modalRegDocumento .modal-title').text('Registrar documento')
                        $('#modalRegDocumento').modal('show')
                    })
                })

                function limpiarYLimitar(valor) {
                    valor = valor.replace(',', '.')
                    valor = valor.replace(/[^0-9.]/g, '')
                    if (valor.startsWith('.')) {
                        valor = '0' + valor;
                    }
                    if ((valor.match(/\./g) || []).length > 1) {
                        let partes = valor.split('.')
                        valor = partes[0] + '.' + partes.slice(1).join('')
                    }
                    if (valor.includes('.')) {
                        let partes = valor.split('.')
                        partes[1] = partes[1].substring(0, 2)
                        valor = partes[0] + '.' + partes[1]
                    }
                    return valor
                }

                $('.monto').on('input', function() {
                    let valor = limpiarYLimitar($(this).val())
                    $(this).val(valor)
                })

                $('#gravable, #no-gravable, #igv, #penalidad, #garantia').on('input', function() {
                    calcularTotal()
                })

                function calcularTotal() {
                    let gravable = parseFloat($('#gravable').val()) || 0
                    let noGravable = parseFloat($('#no-gravable').val()) || 0
                    let igv = parseFloat($('#igv').val()) || 0
                    let penalidad = parseFloat($('#penalidad').val()) || 0
                    let garantia = parseFloat($('#garantia').val()) || 0
                    let total = (gravable + noGravable + igv) - (penalidad + garantia)
                    $('#total').val(total.toFixed(2))
                    $('#tipo-retenc').trigger('change')
                }

                $('.monto').on('paste', function(e) {
                    e.preventDefault()
                })

                $('#tipo-retenc').on('change', function() {
                    console.log('change')
                    let tipo = $(this).find(':selected').data('tipo')
                    let porcentaje = $(this).find(':selected').data('porcentaje')

                    let gravable = parseFloat($('#gravable').val()) || 0
                    let noGravable = parseFloat($('#no-gravable').val()) || 0
                    let igv = parseFloat($('#igv').val()) || 0
                    
                    let totalSinDescuentos = gravable + noGravable + igv

                    if (tipo && porcentaje) {
                        let calculado = Math.round(totalSinDescuentos * (porcentaje / 100))
                        if (tipo.toLowerCase() == 'retencion') {
                            $('#retencion').val(calculado)
                            $('#detraccion').val('')
                            $('#observaciones').val('Retención')
                        }
                        if (tipo.toLowerCase() == 'detraccion') {
                            $('#detraccion').val(calculado)
                            $('#retencion').val('')
                            $('#observaciones').val('Detracción')
                        }
                        calcularNeto()
                    } else {
                        $('#detraccion').val('')
                        $('#retencion').val('')
                        $('#neto').val('')
                    }
                })

                function calcularNeto() {
                    let retencion = parseFloat($('#retencion').val()) || 0
                    let detraccion = parseFloat($('#detraccion').val()) || 0
                    let gravable = parseFloat($('#gravable').val()) || 0
                    let noGravable = parseFloat($('#no-gravable').val()) || 0
                    let igv = parseFloat($('#igv').val()) || 0
                    let totalSinDescuentos = gravable + noGravable + igv
                    let neto = totalSinDescuentos - retencion - detraccion
                    $('#neto').val(neto.toFixed(2))
                }

                $('#tipo-orden').on('change', function(e, callback) {
                    let tipo = $(this).val()
                    let $select = $('#cuenta')

                    if (tipo) {
                        $select.prop('disabled', true)
                        cargarCuentas(tipo, $select, callback)
                    } else {
                        $select.empty().append(`<option value="" disabled selected>--Seleccionar--</option>`)
                        $select.prop('disabled', true)
                        if (callback) callback()
                    }
                })

                function cargarCuentas(tipo, $select, callback) {
                    $.get(`/regcas_ctas_conts/filter/${tipo}`).then(response => {
                        if (response.success) {
                            $select.empty().append(
                                `<option value="" disabled selected>--Seleccionar--</option>`)
                            response.data.forEach(cuenta => {
                                $select.append(
                                    `<option value="${cuenta.codcuenta}">${cuenta.cuenta}</option>`)
                            })
                            $select.prop('disabled', false)
                            if (callback) callback()
                        } else {
                            toastr.error(response.message)
                            if (callback) callback()
                        }
                    }).catch(() => {
                        toastr.error('Error de conexión con el servidor')
                        if (callback) callback()
                    })
                }

                function cargarTiposRetencion($select) {
                    return $.get(`/regcas_retenciones/index`).then(response => {
                        if (response.success) {
                            $select.empty().append(
                                `<option value="" selected>--Seleccionar--</option>`
                            )
                            response.data.forEach(retencion => {
                                let tipo = retencion.tipo ?? ''
                                let porcentaje = retencion.porcentaje ?? ''
                                $select.append(
                                    `<option value="${retencion.codretenc}"
                                        data-tipo="${tipo}"
                                        data-porcentaje="${porcentaje}">
                                        ${retencion.descripcion}
                                    </option>`
                                )
                            })
                        } else {
                            toastr.error(response.message)
                        }
                    })
                }

                function cargarTiposDocumento($select) {
                    return $.get(`/regcas_tipdocs/index`).then(response => {
                        if (response.success) {
                            $select.empty()
                            response.data.forEach(tipdoc => {
                                $select.append(
                                    `<option value="${tipdoc.codtipdoc}" data-coddocsunat="${tipdoc.coddocsunat}">${tipdoc.coddocsunat} - ${tipdoc.descripcion}</option>`
                                )
                            })
                            $select.selectpicker('refresh')
                        } else {
                            toastr.error(response.message)
                        }
                    })
                }

                $('#nro-orden').on('input', function() {
                    this.value = this.value.replace(/[^0-9]/g, '')
                    if (this.value.length > 5) {
                        this.value = this.value.slice(0, 5)
                    }
                })

                $('#nro-documento, #nro-serie, #serie-retenc, #nro-retenc').on('input', function() {
                    if (this.value.length > 11) {
                        this.value = this.value.slice(0, 11)
                    }
                })

                $('#tipo-documento').on('change', function() {
                    let coddocsunat = $(this).find(':selected').data('coddocsunat')
                    if (coddocsunat && coddocsunat == '14') {
                        $('#fecha-venc-container').show()
                    } else {
                        $('#fecha-venc-container').hide()
                        $('#fecha-venc').val('')
                    }
                })

                $("#formRegDocumento").validate({
                    rules: {
                        'serie-retenc': {
                            required: function() {
                                const tipo = $('#tipo-retenc').find(':selected').data('tipo')
                                if (!tipo) return false
                                return tipo.toLowerCase() === 'retencion'
                            }
                        },
                        'nro-retenc': {
                            required: function() {
                                const tipo = $('#tipo-retenc').find(':selected').data('tipo')
                                if (!tipo) return false
                                return tipo.toLowerCase() === 'retencion'
                            }
                        },
                        'fecha-retenc': {
                            required: function() {
                                const tipo = $('#tipo-retenc').find(':selected').data('tipo')
                                if (!tipo) return false
                                return tipo.toLowerCase() === 'retencion'
                            }
                        },
                        'fecha-venc': {
                            required: function() {
                                const coddocsunat = $('#tipo-documento').find(':selected').data(
                                    'coddocsunat')
                                if (!coddocsunat) return false
                                return coddocsunat == '14'
                            }
                        }
                    },
                    messages: {

                    },
                    errorElement: 'div',
                    highlight: function(element) {},
                    unhighlight: function(element) {},
                    errorPlacement: function(error, element) {
                        error.addClass("invalid-feedback")
                        if (element.hasClass('selectpicker')) {
                            error.insertAfter(element.parent());
                        } else {
                            error.insertAfter(element);
                        }
                    },
                    submitHandler: function(form) {
                        $('.no-required').each(function() {
                            if ($(this).val().trim() === '') {
                                $(this).val('0.00')
                            }
                        })
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
                                guardarDocumento()
                            }
                        })
                    }
                })

                $(".required").each(function() {
                    $(this).rules("add", {
                        required: true,
                        messages: {
                            required: "Este campo es obligatorio"
                        }
                    })
                })

                function guardarDocumento() {
                    let formData = new FormData($('#formRegDocumento')[0])
                    formData.append('uejcodigo', comprobante.uejcodigo)
                    formData.append('anno-comp', comprobante.anoproc)
                    formData.append('num-comp', comprobante.cpcnumero)
                    formData.append('ff-comp', comprobante.cpcfuente)
                    formData.append('fuente-comp', comprobante.fuente.ftodesc)
                    formData.append('fecha-comp', comprobante.cpcfecha)
                    formData.append('num-siaf', comprobante.ordnumsiaf)
                    formData.append('proveedor', comprobante.proveedor.pronombres)
                    formData.append('ruc', comprobante.proveedor.pronumruc)

                    let codcpdoc = $('#codcpdoc').val()
                    if (codcpdoc) formData.append('codcpdoc', codcpdoc)

                    let url = codcpdoc ? '/regcas_cpdocs/update' : '/regcas_cpdocs/store'
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
                                $('#modalRegDocumento').modal('hide')
                                documentosTable.ajax.reload()
                                Swal.fire({
                                    title: "Completado",
                                    text: response.message,
                                    icon: "success"
                                })
                            } else {
                                Swal.fire({
                                    title: response.message,
                                    text: response.data || '',
                                    icon: "warning"
                                })
                            }
                        },
                        error: function() {
                            toastr.error('Error de conexión con el servidor')
                        }
                    })
                }

                $('#documentosTable').on('click', '.btn-editar', function() {
                    Promise.all([
                        cargarTiposRetencion($('#tipo-retenc')),
                        cargarTiposDocumento($('#tipo-documento'))
                    ]).then(() => {
                        $('#formRegDocumento')[0].reset()
                        let id = $(this).data('id')
                        let fila = documentosMap[id]

                        $('#codcpdoc').val(fila.codcpdoc)
                        $('#tipo-orden').val(fila.torden).trigger('change', [function() {
                            $('#cuenta').val(fila.ctacont)
                        }])
                        $('#nro-orden').val(fila.num_orden)
                        $('#fecha-orden').val(fila.fecha_orden.split('-').reverse().join('-'))

                        $('#tipo-documento').val(fila.tdoc).trigger('change')
                        $('#nro-serie').val(fila.serie_doc)
                        $('#nro-documento').val(fila.num_doc)
                        $('#fecha-emision').val(fila.fecha_doc.split('-').reverse().join('-'))
                        if (fila.fecha_venc) {
                            $('#fecha-venc').val(fila.fecha_venc.split('-').reverse().join('-'))
                        }
                        $('#gravable').val(fila.v_grav)
                        $('#no-gravable').val(fila.v_nograv)
                        $('#igv').val(fila.igv)
                        $('#total').val(fila.total)
                        $('#penalidad').val(fila.penalidad)
                        $('#garantia').val(fila.garantia)

                        $('#tipo-retenc').val(fila.tretenc).trigger('change')
                        $('#serie-retenc').val(fila.serie_retenc)
                        $('#nro-retenc').val(fila.num_retenc)
                        $('#fecha-retenc').val(
                            fila.fecha_retenc ? fila.fecha_retenc.split('-').reverse().join('-') :
                            ''
                        )
                        $('#retencion').val(fila.retencion)
                        $('#coactivo').val(fila.coactivo)
                        $('#detraccion').val(fila.detraccion)
                        $('#neto').val(fila.neto)

                        $('#observaciones').val(fila.obs)
                        $('#modalRegDocumento .modal-title').text('Editar documento')
                        $('#modalRegDocumento').modal('show')
                    })
                })

                $('#btnRegProv').on('click', function() {
                    $('#formGuardarProveedor')[0].reset()
                    let nombreCompleto = [
                        comprobante.ente.entpaterno,
                        comprobante.ente.entmaterno,
                        comprobante.ente.entnombres
                    ].filter(Boolean).join(' ')
                    $('#entcodigo').val(comprobante.ente.entcodigo)
                    $('#uejcodigo').val(comprobante.ente.uejcodigo)
                    $('#pronombres').val(nombreCompleto)
                    $('#proveedor').val('false')
                    $('#modalGuardarProveedor').modal('show')
                })

                $('#btnRegRuc').on('click', function() {
                    $('#formGuardarProveedor')[0].reset()
                    $('#entcodigo').val(comprobante.proveedor.entcodigo)
                    $('#uejcodigo').val(comprobante.proveedor.uejcodigo)
                    $('#pronombres').val(comprobante.proveedor.pronombres)
                    $('#proveedor').val('true')
                    $('#modalGuardarProveedor').modal('show')
                })

                $('#pronumruc').on('input', function() {
                    this.value = this.value.replace(/[^0-9]/g, '')
                    if (this.value.length > 11) {
                        this.value = this.value.slice(0, 11)
                    }
                })

                $("#formGuardarProveedor").validate({
                    errorElement: "div",
                    rules: {
                        pronombres: {
                            required: true
                        },
                        pronumruc: {
                            required: true
                        }
                    },
                    messages: {
                        pronombres: {
                            required: "Este campo es obligatorio"
                        },
                        pronumruc: {
                            required: "Este campo es obligatorio"
                        }
                    },
                    errorPlacement: function(error, element) {
                        error.addClass("invalid-feedback")
                        error.insertAfter(element);
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
                                guardarProveedor()
                            }
                        })
                    }
                })

                function guardarProveedor() {
                    let formData = new FormData($('#formGuardarProveedor')[0])
                    let url
                    if ($('#proveedor').val() === 'true') {
                        url = '/regcas_proveedor/update'
                    } else {
                        url = '/regcas_proveedor/store'
                    }
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
                                $('#modalGuardarProveedor').modal('hide')
                                Swal.fire({
                                    title: "Completado!",
                                    text: response.message,
                                    icon: "success"
                                })
                                $('#btnBuscar').trigger('click')
                            } else {
                                Swal.fire({
                                    title: "Error!",
                                    text: response.message,
                                    icon: "error"
                                })
                            }
                        },
                        error: function(xhr) {
                            toastr.error('Error de conexión con el servidor')
                        }
                    })
                }

                $('#documentosTable').on('click', '.btn-eliminar', function() {
                    let id = $(this).data('id')
                    Swal.fire({
                        title: "Alerta",
                        text: 'Los datos se eliminarán permanentemente',
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3b5998",
                        cancelButtonColor: "#d33",
                        cancelButtonText: "Cancelar",
                        confirmButtonText: "Confirmar"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: `/regcas_cpdocs/destroy/${id}`,
                                type: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function(response) {
                                    if (response.success) {
                                        documentosTable.ajax.reload(null, false);
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
                })

                $('#documentosTable').on('click', '.btn-restaurar', function() {
                    let id = $(this).data('id')
                    Swal.fire({
                        title: "Alerta",
                        text: 'Los restaurará el documento seleccionado',
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3b5998",
                        cancelButtonColor: "#d33",
                        cancelButtonText: "Cancelar",
                        confirmButtonText: "Confirmar"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: `/regcas_cpdocs/restore/${id}`,
                                type: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function(response) {
                                    if (response.success) {
                                        documentosTable.ajax.reload(null, false);
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
                })

                $('#documentosTable').on('click', '.btn-detalle', function() {
                    $('#infoRetencion').hide()
                    let id = $(this).data('id')
                    let fila = documentosMap[id]
                    $('#detalleTipoOrden').text(fila.torden)
                    $('#detalleNumeroOrden').text(fila.num_orden)
                    $('#detalleFechaOrden').text(fila.fecha_orden)
                    $('#detalleCuentaContable').text(fila.cuenta_contable.cuenta)
                    $('#detalleTipoDocumento').text(fila.tipo_documento.descripcion).attr('title',
                        fila.tipo_documento.descripcion)
                    $('#detalleNumeroDocumento').text(`${fila.serie_doc}-${fila.num_doc}`)
                    $('#detalleFechaDocumento').text(fila.fecha_doc)
                    if (fila.fecha_venc) {
                        $('#detalleFechaVencimiento').text(fila.fecha_venc)
                    } else {
                        $('#detalleFechaVencimiento').text('s.f.')
                    }
                    $('#detalleGravable').text(`S/${fila.v_grav}`)
                    $('#detalleNoGravable').text(`S/${fila.v_nograv}`)
                    $('#detalleIGV').text(`S/${fila.igv}`)
                    $('#detallePenalidad').text(`S/${fila.penalidad}`)
                    $('#detalleGarantia').text(`S/${fila.garantia}`)
                    $('#detalleTotal').text(`S/${fila.total}`)
                    if (fila.tretenc) {
                        $('#detalleTipoRetenc').text(fila.tipo_retencion.descripcion).attr('title',
                            fila.tipo_retencion.descripcion)
                        $('#detalleNumeroRetenc').text(`${fila.serie_retenc}-${fila.num_retenc}`)
                        $('#detalleFechaRetenc').text(fila.fecha_retenc)
                        $('#detalleRetencion').text(`S/${fila.retencion}`)
                        $('#detalleCoactivo').text(`S/${fila.coactivo}`)
                        $('#detalleDetraccion').text(`S/${fila.detraccion}`)
                        $('#detalleNeto').text(`S/${fila.neto}`)
                        $('#infoRetencion').show()
                    }
                    $('#detalleObservaciones').text(fila.obs)
                    $('#modalDetalleDoc').modal('show')
                })
            })
        </script>
    @endpush
