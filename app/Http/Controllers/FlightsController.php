<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFlightsRequest;
use App\Http\Requests\UpdateFlightsRequest;
use App\Interfaces\FlightsRepositoryInterface;
use App\Repository\AirportsRepository;

class FlightsController extends Controller
{
	public function __construct(
		private FlightsRepositoryInterface $repository
	) {}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      $flights = $this->repository->getAll();
			// return view('flights-page', compact('flights'));
			return response()->json([
				'data' => $flights
			]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFlightsRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Flights $flights)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Flights $flights)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFlightsRequest $request, Flights $flights)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Flights $flights)
    {
        //
    }
}
