<?php

namespace App\Http\Controllers;

use App\Models\Cooperative;
use App\Models\ExpenseType;
use App\Models\Locality;
use App\Models\Location;
use App\Models\User;
use Illuminate\Http\Request;

class SelectController extends Controller
{
    /**
     * Display a listing select options of the cooperative.
     */
    public function cooperatives()
    {
        return Cooperative::query()
            ->whereNull('deleted_at')
            ->orderBy('name')
            ->get(['id as value', 'name as label']);
    }

    /**
     * Display a listing select options of the states (UF).
     */
    public function states()
    {
        return Locality::query()
            ->groupBy('UF')
            ->orderBy('UF')
            ->get(['UF as value', 'UF as label']);
    }

    /**
     * Display a listing select options of the cities by state (UF).
     */
    public function cities($state)
    {
        return Locality::query()
            ->where('UF', $state)
            ->orderBy('city')
            ->get(['id as value', 'city as label']);
    }

    /**
     * Display a listing select options of the location.
     */
    public function location()
    {
        return Location::query()
            ->orderBy('name')
            ->get(['id as value', 'name as label']);
    }

    /**
     * Display a listing select options of the location.
     */
    public function persons()
    {
        return User::query()
            ->orderBy('name')
            ->get(['id as value', 'name as label']);
    }

    /**
     * Display a listing select options of the expense type.
     */
    public function expenseTypes()
    {
        return ExpenseType::query()
            ->whereNull('deleted_at')
            ->orderBy('name')
            ->orderBy('unity')
            ->selectRaw('id as value, CONCAT(name, " - ", unity) as label')
            ->get();
    }
}
