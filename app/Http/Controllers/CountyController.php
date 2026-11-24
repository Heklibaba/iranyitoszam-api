<?php

namespace App\Http\Controllers;

use App\Models\County;
use Illuminate\Http\Request;

class CountyController extends Controller
{
/**
 * @api {get} /api/counties Megyek listazasa
 * @apiName GetCounties
 * @apiGroup Megyek
 * @apiQuery {String} [search] Kereses a megye neveben.
 */
    public function index(Request $request)
        {
            $request->validate([
                'search' => 'nullable|string|max:255'
            ]);

            $query = County::query();

            if ($request->filled('search')) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }

            $counties = $query->orderBy('name')->get();

            return response()->json($counties);
        }
/**
 * @api {post} /api/counties Megye letrehozasa
 * @apiName CreateCounty
 * @apiGroup Megyek
 * @apiHeader {String} Authorization Bearer token.
 * @apiBody {String} name Megyenev, legfeljebb 255 karakter.
 */
    public function store(Request $request)
        {
            $data = $request->validate([
                'name' => 'required|string|max:255'
            ]);

            $county = County::create($data);

            return response()->json($county, 201);
        }
/**
 * @api {put} /api/counties/:county Megye modositasa
 * @apiName UpdateCounty
 * @apiGroup Megyek
 * @apiParam {Number} county Megyeazonosito.
 * @apiHeader {String} Authorization Bearer token.
 * @apiBody {String} name Uj megyenev, legfeljebb 255 karakter.
 */
    public function update(Request $request, County $county)
        {
            $data = $request->validate([
                'name' => 'required|string|max:255'
            ]);

            $county->update($data);

            return response()->json($county);
        }
 /**
 * @api {delete} /api/counties/:county Megye torlese
 * @apiName DeleteCounty
 * @apiGroup Megyek
 * @apiParam {Number} county Megyeazonosito.
 * @apiHeader {String} Authorization Bearer token.
 * @apiError (409) Conflict A megyehez telepulesek tartoznak.
 */
    public function destroy(County $county)
        {
            if ($county->cities()->exists()) {
                return response()->json([
                    'message' => 'A megyéhez települések tartoznak, ezért nem törölhető.'
                ], 409);
            }

            $county->delete();

            return response()->json([
                'message' => 'Megye törölve.'
            ]);
        }
/**
 * @api {get} /api/counties/:county Megye reszletei
 * @apiName GetCounty
 * @apiGroup Megyek
 * @apiDescription A megye adatai a telepuleseivel.
 * @apiParam {Number} county Megyeazonosito.
 */
    public function show(County $county)
        {
            return response()->json($county->load('cities'));
        }
}