<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CheckPermission;
use App\Helpers\TextProcessor;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LinkRequest;
use App\Models\Link;
use App\Models\Site;;

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

class LinksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return View|Factory|Application|JsonResponse
     */
    public function index(Request $request, Site $site): JsonResponse
    {

        CheckPermission::checkAuth('Listar Sites');

        if ($request->ajax()) {
            $links = Link::where('site_id', $site->id)->get();

            $token = csrf_token();

            return Datatables::of($links)
                ->addIndexColumn()
                ->addColumn('action', function ($row) use ($token) {
                    return
                        '<a class="btn btn-xs btn-info mx-1 shadow" title="Link" href="' . $row->url . '" target="_blank"><i class="fa fa-lg fa-fw fa-link"></i></a>'
                        . ($row->observations ? '<a class="btn btn-xs btn-light mx-1 shadow" title="Informações" href="' . route('admin.link.show', ['site' => $row->site_id, 'link' => $row->id]) . '" target="_blank"><i class="fa fa-lg fa-fw fa-info"></i></a>' : '')
                        . '<a class="btn btn-xs btn-primary mx-1 shadow" title="Editar Link" href="' . route('admin.link.edit', ['site' => $row->site_id, 'link' => $row->id]) . '"><i class="fa fa-lg fa-fw fa-pen"></i></a>'
                        . '<form method="POST" action="' . route('admin.link.destroy', ['site' => $row->site_id, 'link' => $row->id]) . '" class="btn btn-xs px-0"><input type="hidden" name="_method" value="DELETE"><input type="hidden" name="_token" value="' . $token . '"><button class="btn btn-xs btn-danger mx-1 shadow" title="Excluir" onclick="return confirm(\'Confirma a exclusão deste link?\')"><i class="fa fa-lg fa-fw fa-trash"></i></button></form>';
                })
                ->addColumn('url', function ($row) {
                    if ($row->signal) {
                        $row->url = '<span><i class="fa fa-md fa-fw fa-exclamation-triangle text-warning mr-2"></i> ' . $row->url . '</span>';
                    }
                    return $row->url;
                })
                ->rawColumns(['url', 'action'])
                ->make(true);
        }
    }


    public function show(int $site, int $link)
    {
        CheckPermission::checkAuth('Editar Sites');

        $link = Link::where('site_id', $site)->find($link);

        if (!$link) {
            abort(403, 'Acesso não autorizado');
        }

        return view('admin.sites.links.show', compact('link'));
    }

    public function edit(int $site, int $link)
    {
        CheckPermission::checkAuth('Editar Sites');

        $link = Link::where('site_id', $site)->find($link);

        if (!$link) {
            abort(403, 'Acesso não autorizado');
        }

        return view('admin.sites.links.edit', compact('link'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param SiteRequest $request
     * @param int $id
     * @return RedirectResponse|Response
     */
    public function update(LinkRequest $request, int $site, int $link): Response|RedirectResponse
    {
        CheckPermission::checkAuth('Editar Sites');

        $link = Link::where('site_id', $site)->find($link);

        if (!$link) {
            abort(403, 'Acesso não autorizado');
        }

        if ($request->observations) {
            $data['observations'] = TextProcessor::store($request->url, 'links', $request->observations);
        }

        $data = $request->all();

        if ($link->update($data)) {
            return redirect()
                ->route('admin.sites.show', ['site' => $site])
                ->with('success', 'Atualização realizada!');
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao atualizar!');
        }
    }


    public function destroy(int $site, int $link)
    {
        CheckPermission::checkAuth('Excluir Sites');

        $link = Link::where('site_id', $site)->find($link);

        if (!$link) {
            abort(403, 'Acesso não autorizado');
        }

        if ($link->delete()) {

            return redirect()
                ->route('admin.sites.show', ['site' => $site])
                ->with('success', 'Exclusão realizada!');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Erro ao excluir!');
        }
    }
}
