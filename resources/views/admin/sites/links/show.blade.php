@extends('adminlte::page')

@section('title', '- Link')

@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-fw fa-link"></i> Link {{ Str::limit($link->url, 30, '...') }}
                        {!! $link->signal ? ' <i class="fa fa-md fa-fw fa-exclamation-triangle text-warning ml-2"></i>' : '' !!}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        @can('Listar Sites')
                            <li class="breadcrumb-item"><a href="{{ route('admin.sites.index') }}">Sites</a></li>
                        @endcan
                        <li class="breadcrumb-item active">Link</li>
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
                            <h3 class="card-title">Dados Cadastrais do Link</h3>
                        </div>

                        <div class="card-body">

                            <div class="d-flex flex-wrap justify-content-between">
                                <div class="col-12 form-group px-0">
                                    <label for="url">URL</label>
                                    <input type="text" class="form-control" id="url"
                                        placeholder="URL do site para busca" name="url"
                                        value="{{ old('url') ?? $link->url }}" disabled>
                                </div>

                                <div class="col-12 form-group px-0">
                                    <label for="page">Página</label>
                                    <input type="text" class="form-control" id="page" placeholder="Título da Página"
                                        name="page" value="{{ old('page') ?? $link->page }}" disabled>
                                </div>
                            </div>

                            @if ($link->observations)
                                <div class="d-flex flex-wrap justify-content-between">
                                    <div class="col-12 form-group px-0 mb-0">
                                        <div class="col-12 form-group px-0 mb-0">
                                            <label for="technologies">Observações</label>
                                            <div class="col-12 form-group px-0 d-flex flex-wrap justify-content-center"
                                                id="observations">
                                                <div class="col-12 px-0 border border-secondary border-2 mx-1 rounded p-2">
                                                    {!! $link->observations !!}
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
