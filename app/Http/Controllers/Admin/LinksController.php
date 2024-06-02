<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CheckPermission;
use App\Http\Controllers\Controller;
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
    public function index(Request $request, Site $site): View|Factory|Application|JsonResponse
    {
        CheckPermission::checkAuth('Listar Sites');

        if ($request->ajax()) {
            $links = Link::where('site_id', $site->id)->get();

            $token = csrf_token();

            return Datatables::of($links)
                ->addIndexColumn()
                ->make(true);
        }
    }
}
