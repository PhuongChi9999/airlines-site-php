<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAirportsRequest;
use App\Http\Requests\UpdateAirportsRequest;
use App\Interfaces\AirportsRepositoryInterface;
use App\Models\Airports;
use Illuminate\Http\JsonResponse;

class AirportsController extends Controller
{
	public function __construct(
		private AirportsRepositoryInterface $repository
	) {}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
			$airports = $this->repository->getAll();

      return view('airports-page', compact('airports'));
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
    public function store(StoreAirportsRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Airports $airports)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Airports $airports)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAirportsRequest $request, Airports $airports)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Airports $airports)
    {
        //
    }
}
