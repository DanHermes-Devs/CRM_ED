@extends('crm.layouts.app')

@section('content')
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">EDITAR USUARIO</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">DASHBOARD</a></li>
                        <li class="breadcrumb-item active">EDITAR USUARIO</li>
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
                        <h4 class="text-left mb-3">EDITAR USUARIO</h4>
                        <a href="{{ route('usuarios') }}" class="btn btn-info mb-3">
                            <div class="d-flex align-items-center gap-1">
                                <i class="ri-arrow-left-line"></i>
                                Regresar
                            </div>
                        </a>
                    </div>

                    {{-- Formulario para editar usuario --}}
                    <form action="{{ route('actualizar-usuario', $usuario->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-12 col-md-3">
                                <div class="mb-3">
                                    <label for="usuario" class="form-label">Usuario</label>
                                    <input type="text" class="form-control" id="usuario" name="usuario" value="{{ $usuario->usuario }}">
                                </div>
                            </div>
                            <div class="col-12 col-md-3">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nombre</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ $usuario->name }}">
                                </div>
                            </div>
                            <div class="col-12 col-md-3">
                                <div class="mb-3">
                                    <label for="apellido_paterno" class="form-label">Apellido Paterno</label>
                                    <input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno" value="{{ $usuario->apellido_paterno }}">
                                </div>
                            </div>
                            <div class="col-12 col-md-3">
                                <div class="mb-3">
                                    <label for="apellido_materno" class="form-label">Apellido Materno</label>
                                    <input type="text" class="form-control" id="apellido_materno" name="apellido_materno" value="{{ $usuario->apellido_materno }}">
                                </div>
                            </div>
                            <div class="col-12 col-md-3">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Correo Electrónico</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ $usuario->email }}">
                                </div>
                            </div>
                            <div class="col-12 col-md-3">
                                <div class="mb-3">
                                    <label for="estatus" class="form-label">Estatus</label>
                                    <select class="form-select" id="estatus" name="estatus">
                                        <option value="1" {{ $usuario->estatus == 1 ? 'selected' : '' }}>Activo</option>
                                        <option value="2" {{ $usuario->estatus == 2 ? 'selected' : '' }}>Desactivado</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-md-3">
                                <div class="mb-3">
                                    <label for="roles" class="form-label">Rol:</label>
                                    <select class="form-select @error('roles') is-invalid @enderror" id="roles" name="roles">
                                        <option value="">Selecciona una opción</option>
                                        @foreach ($roles as $rol)
                                            {{-- Si el valor es igual al que tiene en la base de datos mantener seleccionado --}}
                                            <option value="{{ $rol }}" {{ $rol == $usuario->roles[0]->name ? 'selected' : '' }}>{{ $rol }}</option>
                                        @endforeach
                                    </select>

    
                                    @error('roles')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }} </strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Campo para la contraseña --}}
                            <div class="col-12 col-md-3">
                                <div class="mb-3">
                                    <label for="password" class="form-label">Contraseña</label>
                                    <input type="password" class="form-control" id="password" name="password">
                                </div>
                            </div>

                            <div class="col-12 col-md-3">
                                <div class="mb-3">
                                    <label for="group_id" class="form-label">Grupo Asignado:</label>
                                    <select class="form-select @error('group_id') is-invalid @enderror" id="group_id" name="group_id">
                                        <option value="">Selecciona una opción</option>
                                        @foreach($grupos as $grupo)
                                            <option value="{{ $grupo->id }}">{{ $grupo->nombre_grupo }}</option>
                                        @endforeach
                                    </select>
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
                                    <input type="date" class="form-control @error('fecha_nacimiento') is-invalid @enderror" id="fecha_nacimiento" name="fecha_nacimiento" value="{{ $usuario->fecha_nacimiento }}">
    
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
                                    <input type="text" class="form-control @error('rfc') is-invalid @enderror" id="rfc" name="rfc" value="{{ $usuario->rfc }}">
    
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
                                    <input type="text" class="form-control @error('curp') is-invalid @enderror" id="curp" name="curp" value="{{ $usuario->curp }}">
    
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
                                    <input type="text" class="form-control @error('no_imss') is-invalid @enderror" id="no_imss" name="no_imss" value="{{ $usuario->no_imss }}">
    
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
                                    <input type="text" class="form-control @error('cr_infonavit') is-invalid @enderror" id="cr_infonavit" name="{{ $usuario->cr_infonavit }}" value="Crédito Infonavit">
    
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
                                    <input type="text" class="form-control @error('cr_fonacot') is-invalid @enderror" id="cr_fonacot" name="cr_fonacot" value="{{ $usuario->cr_fonacot }}">
    
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
                                    <input type="text" class="form-control @error('tipo_sangre') is-invalid @enderror" id="tipo_sangre" name="tipo_sangre" value="{{ $usuario->tipo_sangre }}">
    
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
                                    <input type="text" class="form-control @error('ba_nomina') is-invalid @enderror" id="ba_nomina" name="ba_nomina" value="{{ $usuario->ba_nomina }}">
    
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
                                    <input type="text" class="form-control @error('cta_clabe') is-invalid @enderror" id="cta_clabe" name="cta_clabe" value="{{ $usuario->cta_clabe }}">
    
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
                                    <input type="text" class="form-control @error('alergias') is-invalid @enderror" id="alergias" name="alergias" value="{{ $usuario->alergias }}">
    
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
                                    <input type="text" class="form-control @error('padecimientos') is-invalid @enderror" id="padecimientos" name="padecimientos" value="{{ $usuario->padecimientos }}">
    
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
                                    <input type="text" class="form-control @error('tel_casa') is-invalid @enderror" id="tel_casa" name="tel_casa" value="{{ $usuario->tel_casa }}">
    
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
                                    <input type="text" class="form-control @error('tel_celular') is-invalid @enderror" id="tel_celular" name="tel_celular" value="{{ $usuario->tel_celular }}">
    
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
                                    <input type="text" class="form-control @error('persona_emergencia') is-invalid @enderror" id="persona_emergencia" name="persona_emergencia" value="{{ $usuario->persona_emergencia }}">
    
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
                                    <input type="text" class="form-control @error('tel_emergencia') is-invalid @enderror" id="tel_emergencia" name="tel_emergencia" value="{{ $usuario->tel_emergencia }}">
    
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
                                    <input type="text" class="form-control @error('esquema_laboral') is-invalid @enderror" id="esquema_laboral" name="esquema_laboral" value="{{ $usuario->esquema_laboral }}">
    
                                    @error('esquema_laboral')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }} </strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12 col-md-3">
                                <div class="mb-3">
                                    <label for="turno" class="form-label">Turno:</label>
                                    <input type="text" class="form-control @error('turno') is-invalid @enderror" id="turno" name="turno" value="{{ $usuario->turno }}">
    
                                    @error('turno')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }} </strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12 col-md-3">
                                <div class="mb-3">
                                    <label for="hora_entrada" class="form-label">Hora Entrada:</label>
                                    <input type="time" class="form-control @error('hora_entrada') is-invalid @enderror" id="hora_entrada" name="hora_entrada" value="{{ $usuario->hora_entrada }}">
    
                                    @error('hora_entrada')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }} </strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-12 col-md-3">
                                <div class="mb-3">
                                    <label for="hora_salida" class="form-label">Hora Salida:</label>
                                    <input type="time" class="form-control @error('hora_salida') is-invalid @enderror" id="hora_salida" name="hora_salida" value="{{ $usuario->hora_salida }}">
    
                                    @error('hora_salida')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }} </strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-12 col-md-3">
                                <div class="mb-3">
                                    <label for="fecha_ingreso" class="form-label">Fecha de Ingreso:</label>
                                    <input type="date" class="form-control @error('fecha_ingreso') is-invalid @enderror" id="fecha_ingreso" name="fecha_ingreso" value="{{ $usuario->fecha_ingreso }}">
    
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
                                    <input type="date" class="form-control @error('fecha_baja') is-invalid @enderror" id="fecha_baja" name="fecha_baja" value="{{ $usuario->fecha_baja }}">
    
                                    @error('fecha_baja')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }} </strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>                        
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="motivo_baja" class="form-label">Motivo de la Baja:</label>
                                    <textarea name="motivo_baja" id="motivo_baja" class="form-control" rows="5">
                                        {{ $usuario->motivo_baja }}
                                    </textarea>
    
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
                                    <textarea name="observaciones" id="observaciones" class="form-control" rows="5">
                                        {{ $usuario->observaciones }}
                                    </textarea>
    
                                    @error('observaciones')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }} </strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">Actualizar usuario</button>
                            </div>
                        </div>
                    </form>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->
    </div><!-- end row -->
</div>
<!-- container-fluid -->
@endsection
