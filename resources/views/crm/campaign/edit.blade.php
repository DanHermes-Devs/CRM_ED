@extends('crm.layouts.app')

@section('template_title')
    {{ __('Update') }} Campaign
@endsection

@section('content')
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">EDITAR CAMPAÑA</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">DASHBOARD</a></li>
                        <li class="breadcrumb-item active">EDITAR CAMPAÑA</li>
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
                        <h4 class="text-left mb-3">EDITAR CAMPAÑA</h4>
                        <a href="{{ route('campaigns.index') }}" class="btn btn-info mb-3">
                            <div class="d-flex align-items-center gap-1">
                                <i class="ri-arrow-left-line"></i>
                                Regresar
                            </div>
                        </a>
                    </div>
                    <form method="POST" action="{{ route('campaigns.update', $campaign->id) }}"  role="form" enctype="multipart/form-data">
                        {{ method_field('PATCH') }}
                        @csrf

                        @include('crm.campaign.form')

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
