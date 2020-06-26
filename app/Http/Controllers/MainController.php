<?php

namespace App\Http\Controllers;

use App\Model\Corona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MainController extends Controller
{
    private $model;

    public function __construct()
    {
        $this->model = app(Corona::class);
    }

    public function helloWorld()
    {
        return response('Hello World');
    }

    public function countries()
    {
        $response = Http::get('https://api.kawalcorona.com/');
        $data = $response->object();

        return response([
            'data' => $data
        ]);
    }

    public function store()
    {
        $response = Http::get('https://api.kawalcorona.com/');
        $data = $response->object();
        $data = collect($data)->map(function ($country) {
            return [
                'country' => $country->attributes->Country_Region,
                'latitude' => $country->attributes->Lat,
                'longitude' => $country->attributes->Long_,
                'confirmed' => $country->attributes->Confirmed,
                'active' => $country->attributes->Active,
                'death' => $country->attributes->Deaths,
                'recover' => $country->attributes->Recovered
            ];
        })->toArray();

        $this->model->insert($data);

        return response([
            'data' => 'success'
        ]);
    }

    public function index()
    {
        $cases = $this->model->all();

        return response([
            'data' => $cases
        ]);
    }

    public function show($country)
    {
        $case = $this->model->where('country', $country)->get();

        return response([
            'data' => $case
        ]);
    }
}
