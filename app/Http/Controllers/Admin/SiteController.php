<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CheckPermission;
use App\Helpers\TextProcessor;
use App\Http\Controllers\Controller;
use App\Http\Crawler\Crawler;
use App\Http\Requests\Admin\SiteRequest;
use App\Models\Site;
use App\Models\Views\Site as ViewsSite;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use DataTables;
use Illuminate\Http\Response;

class SiteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return View|Factory|Application|JsonResponse
     */
    public function index(Request $request): View|Factory|Application|JsonResponse
    {
        CheckPermission::checkAuth('Listar Sites');

        if ($request->ajax()) {
            $sites = ViewsSite::get();

            $token = csrf_token();

            return Datatables::of($sites)
                ->addIndexColumn()
                ->addColumn('description', function ($row) {
                    return Str::limit($row->description);
                })
                ->addColumn('action', function ($row) use ($token) {
                    return
                        '<a class="btn btn-xs btn-warning mx-1 shadow" title="Crawlet" href="sites/' . $row->id . '/crawler"><i class="fa fa-lg fa-fw fa-spider"></i></a>'
                        . '<a class="btn btn-xs btn-success mx-1 shadow" title="Visualizar" href="sites/' . $row->id . '"><i class="fa fa-lg fa-fw fa-eye"></i></a>'
                        . '<a class="btn btn-xs btn-primary mx-1 shadow" title="Editar" href="sites/' . $row->id . '/edit"><i class="fa fa-lg fa-fw fa-pen"></i></a>'
                        . '<form method="POST" action="sites/' . $row->id . '" class="btn btn-xs px-0"><input type="hidden" name="_method" value="DELETE"><input type="hidden" name="_token" value="' . $token . '"><button class="btn btn-xs btn-danger mx-1 shadow" title="Excluir" onclick="return confirm(\'Confirma a exclusão deste site?\')"><i class="fa fa-lg fa-fw fa-trash"></i></button></form>';
                })
                ->rawColumns(['description', 'action'])
                ->make(true);
        }

        return view('admin.sites.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|\Illuminate\Foundation\Application|View
     */
    public function create(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        CheckPermission::checkAuth('Criar Sites');

        return view('admin.sites.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param SiteRequest $request
     * @return RedirectResponse
     */
    public function store(SiteRequest $request): RedirectResponse
    {
        CheckPermission::checkAuth('Criar Sites');

        $data = $request->all();

        if ($request->description) {
            $data['observations'] = TextProcessor::store($request->url, 'sites', $request->observations);
        }

        $data['user_id'] = Auth::user()->id;
        $data['status'] = 'Pendente';
        $site = Site::create($data);

        if ($site->save()) {
            return redirect()
                ->route('admin.sites.index')
                ->with('success', 'Cadastro realizado!');
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao cadastrar!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        CheckPermission::checkAuth('Listar Sites');

        $site = Site::find($id);

        if (!$site) {
            abort(403, 'Acesso não autorizado');
        }

        return view('admin.sites.show', compact('site'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|Factory|View|Response
     */
    public function edit(int $id): Application|Factory|View|Response
    {
        CheckPermission::checkAuth('Editar Sites');

        $site = Site::find($id);

        if (!$site) {
            abort(403, 'Acesso não autorizado');
        }

        return view('admin.sites.edit', compact('site'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param SiteRequest $request
     * @param int $id
     * @return RedirectResponse|Response
     */
    public function update(SiteRequest $request, int $id): Response|RedirectResponse
    {
        CheckPermission::checkAuth('Editar Sites');

        $site = Site::find($id);

        if (!$site) {
            abort(403, 'Acesso não autorizado');
        }

        $data = $request->all();

        if ($request->description) {
            $data['observations'] = TextProcessor::store($request->url, 'sites', $request->observations);
        }

        if ($site->update($data)) {
            return redirect()
                ->route('admin.sites.index')
                ->with('success', 'Atualização realizada!');
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao atualizar!');
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        CheckPermission::checkAuth('Excluir Sites');

        $site = Site::find($id);

        if (!$site) {
            abort(403, 'Acesso não autorizado');
        }

        if ($site->delete()) {

            return redirect()
                ->route('admin.sites.index')
                ->with('success', 'Exclusão realizada!');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Erro ao excluir!');
        }
    }

    public function crawler(int $id)
    {
        $site = Site::find($id);

        if (!$site) {
            abort(403, 'Acesso não autorizado');
        }

        $crawler = Crawler::crawler($site->url);

        $html = '<div>';

        if (count($crawler['headers']) > 0) {
            foreach ($crawler['headers'] as $k => $v) {
                $html .= '<p>' . $k . ': ' . implode(', ', $v) . '</p>';
            }
        }
        $html .= '</div>';

        $site->technologies = $html;
        $site->last_check = date('Y-m-d H:i:s');
        $site->status = 'Processando';
        $site->update();

        return redirect()
            ->route('admin.sites.index')
            ->with('success', 'Crawler em andamento!');
    }
}
