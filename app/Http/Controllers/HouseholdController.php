<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHouseholdRequest;
use App\Http\Requests\UpdateHouseholdRequest;
use App\Models\Household;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HouseholdController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Household::with(['residents' => function ($query) {
            $query->where('status', 'active');
        }])->latest();

        // Filter by RT/RW if provided
        if ($request->filled('rt_number')) {
            $query->where('rt_number', $request->rt_number);
        }
        
        if ($request->filled('rw_number')) {
            $query->where('rw_number', $request->rw_number);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by house number or head name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('house_number', 'like', "%{$search}%")
                  ->orWhere('head_name', 'like', "%{$search}%");
            });
        }

        $households = $query->paginate(15)->withQueryString();
        
        return Inertia::render('households/index', [
            'households' => $households,
            'filters' => $request->only(['rt_number', 'rw_number', 'status', 'search']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('households/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreHouseholdRequest $request)
    {
        $household = Household::create($request->validated());

        return redirect()->route('households.show', $household)
            ->with('success', 'Household created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Household $household)
    {
        $household->load([
            'residents' => function ($query) {
                $query->orderBy('relationship');
            },
            'administrativeLetters' => function ($query) {
                $query->latest()->limit(10);
            },
            'financialRecords' => function ($query) {
                $query->latest()->limit(10);
            },
        ]);

        return Inertia::render('households/show', [
            'household' => $household,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Household $household)
    {
        return Inertia::render('households/edit', [
            'household' => $household,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHouseholdRequest $request, Household $household)
    {
        $household->update($request->validated());

        return redirect()->route('households.show', $household)
            ->with('success', 'Household updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Household $household)
    {
        $household->delete();

        return redirect()->route('households.index')
            ->with('success', 'Household deleted successfully.');
    }
}