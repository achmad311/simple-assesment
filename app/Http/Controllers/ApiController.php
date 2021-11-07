<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ApiController extends Controller
{
    public function chart(Request $request)
    {
        // dd($request->filter);
        $filter = 'jumlah_meninggal';
        if ($request->has('filter')) {
            $filter = $request->filter;
            // dd($request->filter);
        }
        // dd($filter);
        $time = time() * 1000;
        // dd($time);
        $response = Http::get('https://data.covid19.go.id/public/api/update.json?_=' . $time);

        $collection = $response->json();
        $collect = collect($collection['update']['harian']);
        // $today = Carbon::now();
        // $subday = $today->subDays(7);
        $dataLast = $collect->take(-7);
        $labels = [];
        $data_chart = [];
        foreach ($dataLast as $value) {
            $parse_date = Carbon::parse($value['key_as_string'])->format('Y-m-d');
            array_push($labels, $parse_date);

            array_push($data_chart, $value[$filter]['value']);
        }
        // dd($labels, $data_chart);

        $data = [
            'labels' => $labels,
            'data' => $data_chart,
        ];
        return response()->json($data);
    }

    public function table()
    {
        $time = time() * 1000;
        // dd($time);
        $response = Http::get('https://data.covid19.go.id/public/api/update.json?_=' . $time);

        $data = $response->json();
        // dd($data['update']['harian']);
        $datatable = DataTables::of($data['update']['harian'])->tojson();

        return $datatable;
    }

    public function pieChart()
    {
        $time = time() * 1000;
        // dd($time);
        $response = Http::get('https://data.covid19.go.id/public/api/update.json?_=' . $time);

        return response()->json($response->json());
    }

    public function barChart()
    {
        $time = time() * 1000;
        // dd($time);
        $response = Http::get('https://data.covid19.go.id/public/api/update.json?_=' . $time);

        return response()->json($response->json());
    }
}
