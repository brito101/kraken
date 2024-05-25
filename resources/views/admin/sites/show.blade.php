@extends('adminlte::page')

@section('title', '- Edição de Site')
@section('plugins.Summernote', true)

@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-fw fa-globe"></i> Editar Site</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        @can('Editar Sites')
                            <li class="breadcrumb-item"><a href="{{ route('admin.sites.index') }}">Sites</a></li>
                        @endcan
                        <li class="breadcrumb-item active">Editar Site</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Dados Cadastrais do Site</h3>
                        </div>

                        <div class="card-body">

                            <div class="d-flex flex-wrap justify-content-between">
                                <div class="col-12 form-group px-0">
                                    <label for="url">URL</label>
                                    <div class="col-12 form-group px-0 d-flex flex-wrap justify-content-center"
                                        id="url">
                                        <div class="col-12 px-0 border border-secondary border-2 mx-1 rounded p-2">
                                            {{ $site->url }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if ($site->description)
                                <div class="d-flex flex-wrap justify-content-between">
                                    <div class="col-12 form-group px-0 mb-0">
                                        <label for="description">Descrição</label>
                                        <div class="col-12 form-group px-0 d-flex flex-wrap justify-content-center"
                                            id="description">
                                            <div class="col-12 px-0 border border-secondary border-2 mx-1 rounded p-2">
                                                {{ $site->description }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($site->technologies)
                                <div class="d-flex flex-wrap justify-content-between">
                                    <div class="col-12 form-group px-0 mb-0">
                                        <div class="col-12 form-group px-0 mb-0">
                                            <label for="technologies">Tecnologias</label>
                                            <div class="col-12 form-group px-0 d-flex flex-wrap justify-content-center"
                                                id="technologies">
                                                <div class="col-12 px-0 border border-secondary border-2 mx-1 rounded p-2">
                                                    {!! $site->technologies !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($site->observations)
                                <div class="d-flex flex-wrap justify-content-between">
                                    <div class="col-12 form-group px-0 mb-0">
                                        <div class="col-12 form-group px-0 mb-0">
                                            <label for="technologies">Observações</label>
                                            <div class="col-12 form-group px-0 d-flex flex-wrap justify-content-center"
                                                id="observations">
                                                <div class="col-12 px-0 border border-secondary border-2 mx-1 rounded p-2">
                                                    {!! $site->observations !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
