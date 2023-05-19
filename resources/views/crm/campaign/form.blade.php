<div class="row">
    <div class="mb-3 col-12 col-md-6">
        {{ Form::label('Nombre de la campaña') }}
        {{ Form::text('nombre_campana', $campaign->nombre_campana, ['class' => 'form-control' . ($errors->has('nombre_campana') ? ' is-invalid' : ''), 'placeholder' => 'Nombre de la campaña']) }}
        {!! $errors->first('nombre_campana', '<div class="invalid-feedback">:message</div>') !!}
    </div>
    <div class="mb-3 col-12 col-md-6">
        {{ Form::label('Descripción de la campaña') }}
        {{ Form::text('descripcion_campana', $campaign->descripcion_campana, ['class' => 'form-control' . ($errors->has('descripcion_campana') ? ' is-invalid' : ''), 'placeholder' => 'Descripción de la campaña']) }}
        {!! $errors->first('descripcion_campana', '<div class="invalid-feedback">:message</div>') !!}
    </div>
    <div class="mb-3 col-12 col-md-6">
        {{ Form::label('Estatus') }}
        {{ Form::select('status', $statuses, $campaign->status, ['class' => 'form-select' . ($errors->has('status') ? ' is-invalid' : '')]) }}
    </div>
</div>

<button type="submit" class="btn btn-primary">{{ __('Guardar') }}</button>