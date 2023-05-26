@extends('crm.layouts.app')

@section('content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">CREAR USUARIO</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">DASHBOARD</a></li>
                            <li class="breadcrumb-item active">CREAR USUARIO</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-xl-12">
                <div class="card crm-widget py-4 px-3">
                    <div class="card-body">

                        <div class="d-flex justify-content-between">
                            <h4 class="text-left mb-3">AGREGAR NUEVO USUARIO</h4>
                            <a href="{{ route('usuarios') }}" class="btn btn-info mb-3">
                                <div class="d-flex align-items-center gap-1">
                                    <i class="ri-arrow-left-line"></i>
                                    Regresar
                                </div>
                            </a>
                        </div>

                        {{-- Formulario para agregar nuevo usuario --}}
                        <form action="{{ route('store-usuario') }}" method="POST" novalidate>
                            @csrf
                            <div class="row">
                                <div class="col-12 col-md-3">
                                    <div class="mb-3">
                                        <label for="usuario" class="form-label">Usuario:</label>
                                        <input type="text" class="form-control @error('usuario') is-invalid @enderror" id="usuario" name="usuario" placeholder="Usuario" value="{{ old('usuario') }}">
                                        
                                        @error('usuario')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }} </strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <div class="mb-3">
                                        <label for="nombre" class="form-label">Nombre:</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="nombre" name="name" placeholder="Nombre" value="{{ old('name') }}">
        
                                        @error('name')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }} </strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <div class="mb-3">
                                        <label for="apellido_paterno" class="form-label">Apellido Paterno:</label>
                                        <input type="text" class="form-control @error('apellido_paterno') is-invalid @enderror" id="apellido_paterno" name="apellido_paterno" placeholder="Apellido Paterno" value="{{ old('apellido_paterno') }}">
        
                                        @error('apellido_paterno')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }} </strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <div class="mb-3">
                                        <label for="apellido_materno" class="form-label">Apellido Materno:</label>
                                        <input type="text" class="form-control @error('apellido_materno') is-invalid @enderror" id="apellido_materno" name="apellido_materno" placeholder="Apellido Materno" value="{{ old('apellido_materno') }}">
        
                                        @error('apellido_materno')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }} </strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Correo Electrónico:</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="Correo Electrónico" value="{{ old('email') }}">
        
                                        @error('email')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }} </strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <div class="mb-3">
                                        <label for="estatus" class="form-label">Estatus:</label>
                                        <select class="form-select @error('estatus') is-invalid @enderror" id="estatus" name="estatus">
                                            <option value="">Selecciona una opción</option>
                                            <option value="1" {{ old('estatus') == 1 ? 'selected' : '' }}>Activo</option>
                                            <option value="0" {{ old('estatus') == 0 ? 'selected' : '' }}>Inactivo</option>
                                        </select>
        
                                        @error('estatus')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }} </strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-12 col-md-3">
                                    <div class="mb-3">
                                        <label for="roles" class="form-label">Rol:</label>
                                        <select class="form-select @error('roles') is-invalid @enderror" id="roles" name="roles">
                                            <option value="">Selecciona una opción</option>
                                            @foreach ($roles as $rol)
                                                <option value="{{ $rol }}">{{ $rol }}</option>
                                            @endforeach
                                        </select>

        
                                        @error('roles')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }} </strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Contraseña:</label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Contraseña">
        
                                        @error('password')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }} </strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <div class="mb-3">
                                        <label for="id_campana" class="form-label">Campaña:</label>
                                        <select class="form-select @error('id_campana') is-invalid @enderror" id="id_campana" name="id_campana">
                                            <option value="">Selecciona una opción</option>
                                            @foreach ($campanas as $campana)
                                                <option value="{{ $campana->id }}">{{ $campana->nombre_campana }}</option>
                                            @endforeach
                                        </select>

        
                                        @error('id_campana')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }} </strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <div class="mb-3">
                                        <label for="sexo" class="form-label">Sexo:</label>
                                        <select class="form-select @error('sexo') is-invalid @enderror" id="sexo" name="sexo">
                                            <option value="">Selecciona una opción</option>
                                            <option value="H" {{ old('sexo') == 1 ? 'selected' : '' }}>Hombre</option>
                                            <option value="M" {{ old('sexo') == 0 ? 'selected' : '' }}>Mujer</option>
                                        </select>
        
                                        @error('sexo')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }} </strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <div class="mb-3">
                                        <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento:</label>
                                        <input type="date" class="form-control @error('fecha_nacimiento') is-invalid @enderror" id="fecha_nacimiento" name="fecha_nacimiento" placeholder="Fecha de Nacimiento">
        
                                        @error('fecha_nacimiento')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }} </strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <div class="mb-3">
                                        <label for="rfc" class="form-label">RFC:</label>
                                        <input type="text" class="form-control @error('rfc') is-invalid @enderror" id="rfc" name="rfc" placeholder="RFC">
        
                                        @error('rfc')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }} </strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-12 col-md-3">
                                    <div class="mb-3">
                                        <label for="curp" class="form-label">CURP:</label>
                                        <input type="text" class="form-control @error('curp') is-invalid @enderror" id="curp" name="curp" placeholder="CURP">
        
                                        @error('curp')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }} </strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <div class="mb-3">
                                        <label for="estado_civil" class="form-label">Estado Civil:</label>
                                        <select class="form-select @error('sexo') is-invalid @enderror" id="sexo" name="sexo">
                                            <option value="">Selecciona una opción</option>
                                            <option value="Soltero" {{ old('sexo') == 1 ? 'selected' : '' }}>Soltero</option>
                                            <option value="Casado" {{ old('sexo') == 0 ? 'selected' : '' }}>Casado</option>
                                        </select>
        
                                        @error('estado_civil')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }} </strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <div class="mb-3">
                                        <label for="no_imss" class="form-label">No. IMSS:</label>
                                        <input type="text" class="form-control @error('no_imss') is-invalid @enderror" id="no_imss" name="no_imss" placeholder="No. IMSS">
        
                                        @error('no_imss')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }} </strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <div class="mb-3">
                                        <label for="cr_infonavit" class="form-label">Crédito Infonavit:</label>
                                        <input type="text" class="form-control @error('cr_infonavit') is-invalid @enderror" id="cr_infonavit" name="cr_infonavit" placeholder="Crédito Infonavit">
        
                                        @error('cr_infonavit')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }} </strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <div class="mb-3">
                                        <label for="cr_fonacot" class="form-label">Crédito Fonacot:</label>
                                        <input type="text" class="form-control @error('cr_fonacot') is-invalid @enderror" id="cr_fonacot" name="cr_fonacot" placeholder="Crédito Fonacot">
        
                                        @error('cr_fonacot')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }} </strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <div class="mb-3">
                                        <label for="tipo_sangre" class="form-label">Tipo de Sangre:</label>
                                        <input type="text" class="form-control @error('tipo_sangre') is-invalid @enderror" id="tipo_sangre" name="tipo_sangre" placeholder="Tipo de Sangre">
        
                                        @error('tipo_sangre')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }} </strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-12 col-md-3">
                                    <div class="mb-3">
                                        <label for="ba_nomina" class="form-label">Banco Nómina:</label>
                                        <input type="text" class="form-control @error('ba_nomina') is-invalid @enderror" id="ba_nomina" name="ba_nomina" placeholder="Banco Nómina">
        
                                        @error('ba_nomina')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }} </strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <div class="mb-3">
                                        <label for="cta_clabe" class="form-label">Cuenta CLABE:</label>
                                        <input type="text" class="form-control @error('cta_clabe') is-invalid @enderror" id="cta_clabe" name="cta_clabe" placeholder="Cuenta CLABE">
        
                                        @error('cta_clabe')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }} </strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-12 col-md-3">
                                    <div class="mb-3">
                                        <label for="alergias" class="form-label">Alergias:</label>
                                        <input type="text" class="form-control @error('alergias') is-invalid @enderror" id="alergias" name="alergias" placeholder="Alergias">
        
                                        @error('alergias')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }} </strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-12 col-md-3">
                                    <div class="mb-3">
                                        <label for="padecimientos" class="form-label">Padecimientos:</label>
                                        <input type="text" class="form-control @error('padecimientos') is-invalid @enderror" id="padecimientos" name="padecimientos" placeholder="Padecimientos">
        
                                        @error('padecimientos')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }} </strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-12 col-md-3">
                                    <div class="mb-3">
                                        <label for="tel_casa" class="form-label">Teléfono de Casa:</label>
                                        <input type="text" class="form-control @error('tel_casa') is-invalid @enderror" id="tel_casa" name="tel_casa" placeholder="Teléfono de Casa">
        
                                        @error('tel_casa')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }} </strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-12 col-md-3">
                                    <div class="mb-3">
                                        <label for="tel_celular" class="form-label">Teléfono de Celular:</label>
                                        <input type="text" class="form-control @error('tel_celular') is-invalid @enderror" id="tel_celular" name="tel_celular" placeholder="Teléfono de Celular">
        
                                        @error('tel_celular')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }} </strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-12 col-md-3">
                                    <div class="mb-3">
                                        <label for="persona_emergencia" class="form-label">Persona de Emergencia:</label>
                                        <input type="text" class="form-control @error('persona_emergencia') is-invalid @enderror" id="persona_emergencia" name="persona_emergencia" placeholder="Teléfono de Casa">
        
                                        @error('persona_emergencia')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }} </strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <div class="mb-3">
                                        <label for="tel_emergencia" class="form-label">Teléfono de Emergencia:</label>
                                        <input type="text" class="form-control @error('tel_emergencia') is-invalid @enderror" id="tel_emergencia" name="tel_emergencia" placeholder="Teléfono de Emergencia">
        
                                        @error('tel_emergencia')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }} </strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-12 col-md-3">
                                    <div class="mb-3">
                                        <label for="esquema_laboral" class="form-label">Esquema Laboral:</label>
                                        <input type="text" class="form-control @error('esquema_laboral') is-invalid @enderror" id="esquema_laboral" name="esquema_laboral" placeholder="Esquema Laboral">
        
                                        @error('esquema_laboral')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }} </strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-12 col-md-3">
                                    <div class="mb-3">
                                        <label for="proyecto_asignado" class="form-label">Proyecto Asignado:</label>
                                        <input type="text" class="form-control" id="proyecto_asignado" name="proyecto_asignado" value="Poryecto 1" readonly>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <div class="mb-3">
                                        <label for="turno" class="form-label">Turno:</label>
                                        <input type="text" class="form-control @error('turno') is-invalid @enderror" id="turno" name="turno" placeholder="Turno">
        
                                        @error('turno')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }} </strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-12 col-md-3">
                                    <div class="mb-3">
                                        <label for="horario" class="form-label">Horario:</label>
                                        <input type="text" class="form-control @error('horario') is-invalid @enderror" id="horario" name="horario" placeholder="Horario">
        
                                        @error('horario')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }} </strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-12 col-md-3">
                                    <div class="mb-3">
                                        <label for="fecha_ingreso" class="form-label">Fecha de Ingreso:</label>
                                        <input type="date" class="form-control @error('fecha_ingreso') is-invalid @enderror" id="fecha_ingreso" name="fecha_ingreso" placeholder="Fecha de Ingreso">
        
                                        @error('fecha_ingreso')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }} </strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-12 col-md-3">
                                    <div class="mb-3">
                                        <label for="fecha_baja" class="form-label">Fecha de Baja:</label>
                                        <input type="date" class="form-control @error('fecha_baja') is-invalid @enderror" id="fecha_baja" name="fecha_baja" placeholder="Fecha de Ingreso">
        
                                        @error('fecha_baja')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }} </strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label for="motivo_baja" class="form-label">Motivo de la Baja:</label>
                                        <textarea name="motivo_baja" id="motivo_baja" class="form-control" rows="5"></textarea>
        
                                        @error('motivo_baja')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }} </strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label for="observaciones" class="form-label">Observaciones:</label>
                                        <textarea name="observaciones" id="observaciones" class="form-control" rows="5"></textarea>
        
                                        @error('observaciones')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }} </strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary waves-effect waves-light mb-3">Guardar usuario</button>
                        </form>
                    </div><!-- end card body -->
                </div><!-- end card -->
            </div><!-- end col -->
        </div><!-- end row -->
    </div>
    <!-- container-fluid -->
@endsection