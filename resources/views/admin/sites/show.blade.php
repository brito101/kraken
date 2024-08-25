@extends('adminlte::page')

@section('title', '- Edição de Site')
@section('plugins.Summernote', true)
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)

@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-fw fa-globe"></i> Site {{ $site->url }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        @can('Listar Sites')
                            <li class="breadcrumb-item"><a href="{{ route('admin.sites.index') }}">Sites</a></li>
                        @endcan
                        <li class="breadcrumb-item active">Site</li>
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

                            @if ($site->technologies && $site->technologies != '<div></div>')
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

                            @php
                                $heads = [['label' => 'ID', 'width' => 10], 'Página', 'URL', 'Checagem', 'Status'];
                                $config = [
                                    'ajax' => url('/admin/links/' . $site->id),
                                    'columns' => [
                                        ['data' => 'id', 'name' => 'id'],
                                        ['data' => 'page', 'name' => 'page'],
                                        ['data' => 'url', 'name' => 'url'],
                                        ['data' => 'last_check', 'name' => 'last_check'],
                                        ['data' => 'status', 'name' => 'status'],
                                    ],
                                    'language' => ['url' => asset('vendor/datatables/js/pt-BR.json')],
                                    'autoFill' => true,
                                    'processing' => true,
                                    'serverSide' => true,
                                    'responsive' => true,
                                    'pageLength' => 50,
                                    'lengthMenu' => [[10, 50, 100, 500, 1000, -1], [10, 50, 100, 500, 1000, 'Tudo']],
                                    'refresh' => 30000,
                                    'dom' => '<"d-flex flex-wrap col-12 justify-content-between"Bf>rtip',
                                    'buttons' => [
                                        ['extend' => 'pageLength', 'className' => 'btn-default'],
                                        [
                                            'extend' => 'copy',
                                            'className' => 'btn-default',
                                            'text' => '<i class="fas fa-fw fa-lg fa-copy text-secondary"></i>',
                                            'titleAttr' => 'Copiar',
                                            'exportOptions' => ['columns' => ':not([dt-no-export])'],
                                        ],
                                        [
                                            'extend' => 'print',
                                            'className' => 'btn-default',
                                            'text' => '<i class="fas fa-fw fa-lg fa-print text-info"></i>',
                                            'titleAttr' => 'Imprimir',
                                            'exportOptions' => ['columns' => ':not([dt-no-export])'],
                                        ],
                                        [
                                            'extend' => 'csv',
                                            'className' => 'btn-default',
                                            'text' => '<i class="fas fa-fw fa-lg fa-file-csv text-primary"></i>',
                                            'titleAttr' => 'Exportar para CSV',
                                            'exportOptions' => ['columns' => ':not([dt-no-export])'],
                                        ],
                                        [
                                            'extend' => 'excel',
                                            'className' => 'btn-default',
                                            'text' => '<i class="fas fa-fw fa-lg fa-file-excel text-success"></i>',
                                            'titleAttr' => 'Exportar para Excel',
                                            'exportOptions' => ['columns' => ':not([dt-no-export])'],
                                        ],
                                        [
                                            'extend' => 'pdf',
                                            'className' => 'btn-default',
                                            'text' => '<i class="fas fa-fw fa-lg fa-file-pdf text-danger"></i>',
                                            'titleAttr' => 'Exportar para PDF',
                                            'exportOptions' => ['columns' => ':not([dt-no-export])'],
                                        ],
                                    ],
                                ];
                            @endphp
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Links</h3>
                                </div>
                                <div class="card-body">
                                    <x-adminlte-datatable id="table1" :heads="$heads" :heads="$heads"
                                        :config="$config" striped hoverable beautify />
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
