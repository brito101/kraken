@extends('adminlte::page')

@section('title', '- Edição de Link')
@section('plugins.Summernote', true)
@section('plugins.BootstrapSwitch', true)

@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-fw fa-link"></i> Editar Link</h1>
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

                    @include('components.alert')

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Dados Cadastrais do Link</h3>
                        </div>

                        <form method="POST"
                            action="{{ route('admin.link.update', ['site' => $link->site_id, 'link' => $link->id]) }}">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="id" value="{{ $link->id }}">
                            <div class="card-body">

                                <div class="d-flex flex-wrap justify-content-between">
                                    <div class="col-12 form-group px-0">
                                        <label for="url">URL</label>
                                        <input type="text" class="form-control" id="url"
                                            placeholder="URL do site para busca" name="url"
                                            value="{{ old('url') ?? $link->url }}" required>
                                    </div>

                                    <div class="col-12 form-group px-0">
                                        <label for="page">Página</label>
                                        <input type="text" class="form-control" id="page"
                                            placeholder="Título da Página" name="page"
                                            value="{{ old('page') ?? $link->page }}">
                                    </div>

                                    <div class="d-flex flex-wrap justify-content-start">
                                        <label class="align-self-center mr-2">Sinalizar?</label>
                                        @if ($link->signal == 1)
                                            <x-adminlte-input-switch name="signal" data-on-color="success"
                                                data-off-color="danger" data-on-text="Sim" data-off-text="Não"
                                                enable-old-support checked />
                                        @else
                                            <x-adminlte-input-switch name="signal" data-on-color="success"
                                                data-off-color="danger" data-on-text="Sim" data-off-text="Não"
                                                enable-old-support />
                                        @endif
                                    </div>
                                </div>

                                @php
                                    $config = [
                                        'height' => '100',
                                        'toolbar' => [
                                            // [groupName, [list of button]]
                                            ['style', ['style']],
                                            ['font', ['bold', 'underline', 'clear']],
                                            ['fontsize', ['fontsize']],
                                            ['fontname', ['fontname']],
                                            ['color', ['color']],
                                            ['para', ['ul', 'ol', 'paragraph']],
                                            ['height', ['height']],
                                            ['table', ['table']],
                                            ['insert', ['link', 'picture', 'video']],
                                            ['view', ['fullscreen', 'codeview', 'help']],
                                        ],
                                        'inheritPlaceholder' => true,
                                    ];
                                @endphp

                                <div class="d-flex flex-wrap justify-content-between">
                                    <div class="col-12 form-group px-0 mb-0">
                                        <x-adminlte-text-editor name="observations" label="Observações" id="observations"
                                            label-class="text-black" igroup-size="md" placeholder="Texto descritivo..."
                                            :config="$config">
                                            {!! old('observations') ?? $link->observations !!}
                                        </x-adminlte-text-editor>
                                    </div>
                                </div>


                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Enviar</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
