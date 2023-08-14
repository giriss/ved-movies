<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Log;
use App\Models\Movie;

class MoviesController extends Controller
{
    private function incrementApiUsage($api_key)
    {
        if (!User::where('api_key', $api_key)->exists()) {
            response()->exit(['error' => 'Invalid API Key'], 422);
        }

        $log = Log::firstWhere(['api_key' => $api_key, 'date' => date("Y-m-d")]);
        if ($log == null) {
            $log = new Log();
            $log->api_key = $api_key;
            $log->date = date("Y-m-d");
            $log->count = 1;
            $log->save();
        } else {
            $log->count += 1;
            $log->save();
        }
    }

    public function search()
    {
        $api_key = request()->get("api_key");
        $this->incrementApiUsage($api_key);

        $res = db()->table("movies")->search("title", request()->get("title"));

        response()->json($res);
    }

    public function searchByYear()
    {
        $api_key = request()->get("api_key");
        $this->incrementApiUsage($api_key);

        $res = db()->table("movies")->search("year", request()->get("year"));

        response()->json($res);
    }
}
