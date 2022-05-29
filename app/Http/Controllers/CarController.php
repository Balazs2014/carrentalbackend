<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Http\Requests\StoreCarRequest;
use App\Http\Requests\UpdateCarRequest;
use App\Models\Rental;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cars = Car::all();
        return response()->json(['data' => $cars]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCarRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCarRequest $request)
    {
        $car = new Car($request->only('license_plate_number', 'brand', 'model', 'daily_cost'));
        $car->save();
        return response()->json($car, 201);
    }

    public function rent(Request $request, Car $car) {
        $find = Car::find($car->id);
        if (is_null($find)) {
            return response()->json([ 'message' => 'Nem található autó' ], 404);
        }
        $count = Rental::where('car_id', $car->id)
            ->where('end_date', '>=', Carbon::now())
            ->count();
        if ($count > 0) {
            return response()->json([ 'message' => 'Az autó már foglalt' ], 409);
        }

        $rent = new Rental();
        $rent->car_id = $car->id;
        $rent->start_date = Carbon::now();
        $rent->end_date = Carbon::now()->addWeek();
        $rent->save();
        return response()->json($rent, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function show(Car $car)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function edit(Car $car)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCarRequest  $request
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCarRequest $request, Car $car)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function destroy(Car $car)
    {
        $find = Car::find($car->id);
        if (is_null($find)) {
            return response()->json([ 'message' => 'Nem található autó'], 404);
        }

        Car::destroy($car->id);
        return response()->noContent();
    }
}
