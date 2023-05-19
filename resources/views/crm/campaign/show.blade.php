@extends('layouts.app')

@section('template_title')
    {{ $campaign->name ?? "{{ __('Show') Campaign" }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Campaign</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('campaigns.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Nombre Campana:</strong>
                            {{ $campaign->nombre_campana }}
                        </div>
                        <div class="form-group">
                            <strong>Descripcion Campana:</strong>
                            {{ $campaign->descripcion_campana }}
                        </div>
                        <div class="form-group">
                            <strong>Status:</strong>
                            {{ $campaign->status }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
