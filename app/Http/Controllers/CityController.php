<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    /**
 * @api {get} /api/cities Telepulesek listazasa
 * @apiName GetCities
 * @apiGroup Telepulesek
 * @apiQuery {String} [search] Kereses nev alapjan.
 * @apiQuery {Number} [county_id] Szures megyeazonosito alapjan.
 */
    public function index(Request $request)
            {
                $request->validate([
                    'search' => 'nullable|string|max:255',
                    'county_id' => 'nullable|integer|exists:counties,id'
                ]);

                $query = City::with('county');

                if ($request->filled('search')) {
                    $query->where('name', 'like', '%' . $request->search . '%');
                }

                if ($request->filled('county_id')) {
                    $query->where('county_id', $request->county_id);
                }

                $cities = $query->orderBy('name')->get();

                return response()->json($cities);
            }
            /**
 * @api {post} /api/cities Telepules letrehozasa
 * @apiName CreateCity
 * @apiGroup Telepulesek
 * @apiHeader {String} Authorization Bearer token.
 * @apiBody {String} name Telepulesnev, legfeljebb 255 karakter.
 * @apiBody {Number} county_id Letezo megye azonositoja.
 */
        public function store(Request $request)
            {
                $data = $request->validate([
                    'name' => 'required|string|max:255',
                    'county_id' => 'required|integer|exists:counties,id'
                ]);

                $city = City::create($data);

                return response()->json($city->load('county'), 201);
            }
            /**
 * @api {put} /api/cities/:city Telepules modositasa
 * @apiName UpdateCity
 * @apiGroup Telepulesek
 * @apiParam {Number} city Telepulesazonosito.
 * @apiHeader {String} Authorization Bearer token.
 * @apiBody {String} name Telepulesnev, legfeljebb 255 karakter.
 * @apiBody {Number} county_id Letezo megye azonositoja.
 */
        public function update(Request $request, City $city)
            {
                $data = $request->validate([
                    'name' => 'required|string|max:255',
                    'county_id' => 'required|integer|exists:counties,id'
                ]);

                $city->update($data);

                return response()->json($city->load('county'));
            }   
            /**
 * @api {delete} /api/cities/:city Telepules torlese
 * @apiName DeleteCity
 * @apiGroup Telepulesek
 * @apiParam {Number} city Telepulesazonosito.
 * @apiHeader {String} Authorization Bearer token.
 */
        public function destroy(City $city)
            {
                $city->delete();

                return response()->json([
                    'message' => 'Település törölve.'
                ]);
            }
            /**
 * @api {get} /api/cities/:city Telepules reszletei
 * @apiName GetCity
 * @apiGroup Telepulesek
 * @apiDescription A telepules adatai a megyejevel.
 * @apiParam {Number} city Telepulesazonosito.
 */
        public function show(City $city)
            {
                return response()->json($city->load('county'));
            }
}