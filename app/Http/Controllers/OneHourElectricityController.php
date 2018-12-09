<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

use App\Models\OneHourElectricity;
use App\Models\Panel;

class OneHourElectricityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $panel = Panel::where('serial', $request->panel_serial)->firstOrFail();
        return OneHourElectricity::where('panel_id', $panel->id)->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $params = $request->all();
        $validator = Validator::make($params, OneHourElectricity::$fieldValidations);
        if ($validator->fails()) {
            return new Response($validator->errors()->all(), 422);
        }

        $panel = Panel::where('serial', $request->panel_serial)->first();
        if($panel === null) {
            return new Response($validator->errors()->add('0','Panel not found'), 422);
        }
        $params['panel_id'] = $panel->id;

        return $panel->oneHourElectricities()->create($params);
    }
}
