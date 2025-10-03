<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Locality;
use App\Models\Schedule;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;


class ScheduleController extends Controller
{
    /**
     * Display a listing all of the resource.
     */
    public function allSchedules(Request $request)
    {
        $month = $request->has('month') && !empty($request->month) ? $request->month : now()->month;
        $year = $request->has('year') && !empty($request->year) ? $request->year : now()->year;
        $perPage = $request->get('rowsPerPage', 10);
        $page = $request->get('page', 0);

        $schedules = Schedule::query();

        $schedules->whereYear('date', '=', $year)
            ->whereMonth('date', '=', $month);

        $data = $schedules->with([
            'users' => function ($query) {
                $query->select('users.id', 'users.name');
            }
        ])
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        $paginator = new LengthAwarePaginator(
            $data->forPage($page, 10), // Itens da página atual
            $data->count(), // Total de itens
            $perPage,
            $page,
        );

        return response()->json([
            "data" => array_values($paginator->items()),
            "totalRecords" => $paginator->total()
        ]);
    }

    /**
     * Display a listing user of the resource.
     */
    public function index()
    {
        return Response()->json(Auth::user()->schedulesOpen);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $schedule = new Schedule();
        $schedule->fill($request->all())->save();

        return $schedule;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return DB::transaction(function () use ($request) {
            try {
                $request->validate([
                    'start_date' => 'required|date',
                    'end_date' => 'required|date|after_or_equal:start_date',
                    // outras validações necessárias
                ]);

                $startDate = Carbon::parse($request->start_date);
                $endDate = Carbon::parse($request->end_date);
                $schedules = [];

                // Loop através de cada dia no range
                for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
                    $schedule = Schedule::create([
                        'advice_type' => $request->advice_type,
                        'cooperative_id' => $request->cooperative_id,
                        'date' => $date->format('Y-m-d'), // Nova coluna date
                        'end_time' => $request->end_time, // Novo campo end_time (time)
                        'finished' => 0,
                        'locality_id' => $request->locality_id,
                        'location_id' => $request->location_id,
                        'observations' => $request->observations,
                        'start_time' => $request->start_time, // Novo campo start_time (time)
                    ]);

                    // Relacionar usuários se existirem
                    if (!empty($request->userIds)) {
                        $schedule->users()->attach($request->userIds);
                    }

                    $schedules[] = $schedule;
                }

                DB::commit();

                return response()->json([
                    'message' => count($schedules) . ' agendas criadas com sucesso!',
                    'count' => count($schedules),
                    'schedules' => $schedules
                ], 201);

            } catch (\Exception $e) {
                DB::rollBack();

                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao criar agenda(s)',
                    'error' => $e->getMessage()
                ], 500);
            }
        });
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return Schedule::findOrFail($id);
    }

    public function scheduleExpenses($id)
    {
        $schedule = Schedule::findOrFail($id);
        $expenses = Expense::
            with('user')
            ->where('schedule_id', $schedule->id)
            ->orderBy('created_at')
            ->get();
        return response()->json([
            'schedule' => $schedule,
            'expenses' => $expenses,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $schedule = Schedule::with(['users'])->findOrFail($id);
        $locality = Locality::findOrFail($schedule->locality_id);
        $users_name = $schedule->users->pluck('name')->toArray();

        return response()->json([
            'data' => $schedule->makeHidden(['users']),
            'locality' => $locality,
            'users_name' => $users_name
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|integer|exists:schedules,id',
            'advice_type' => 'required|string|max:100',
            'cooperative_id' => 'required|integer|exists:cooperatives,id',
            "end_date" => 'required',
            "end_time" => 'required',
            "finished" => 'required',
            'locality_id' => 'required|integer|exists:localities,id',
            'location_id' => 'required|integer|exists:locations,id',
            'observations' => 'nullable|string|max:3000',
            "start_date" => 'required',
            "start_time" => 'required'
        ]);

        try {
            $schedule = Schedule::findOrFail($validated['id']);

            $schedule->observations = $validated['observations'];
            $schedule->advice_type = $validated['advice_type'];
            $schedule->cooperative_id = $validated['cooperative_id'];
            $schedule->end_time = $validated['end_time'];
            $schedule->locality_id = $validated['locality_id'];
            $schedule->location_id = $validated['location_id'];
            $schedule->finished = $validated['finished'] ? (boolean) $validated['finished'] : $schedule->finished;
            $schedule->observations = $validated['observations'];
            $schedule->date = $validated['start_date'];
            $schedule->start_time = $validated['start_time'];

            if (!empty($request->userIds)) {
                $schedule->users()->detach();
                $schedule->users()->attach($request->userIds);
            }

            $schedule->save();

            return response()->json([
                'success' => true,
                'message' => 'Despesa editada com sucesso!'
            ], 200);

        } catch (\Exception $e) {
            \Log::error('Erro ao editar agenda.', [
                'schedule_id' => $request->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao editar despesa!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function finalizationSchedule(Request $request)
    {
        try {
            $schedule = Schedule::findOrFail($request->id);
            $schedule->finished = !$schedule->finished;
            $schedule->save();

            return response()->json(['message' => $schedule->finished ? 'Agenda finalizada com sucesso!' : 'Agenda reaberta com sucesso!']);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Erro ao tentar alterar o status da agenda!']);
            // throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $schedule = Schedule::findOrFail($id);

            $schedule->users()->detach();

            $schedule->forceDelete();

            return response()->json([
                'success' => true,
                'message' => 'Agenda deletada com sucesso!'
            ], 200);
        } catch (\Exception $e) {
            // Log do erro
            \Log::error('Erro ao deletar agenda.', [
                'expense_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function generateCSVReport($year, $month)
    {
        // Validar os parâmetros
        if (!is_numeric($year) || !is_numeric($month) || $month < 1 || $month > 12) {
            return response()->json(['error' => 'Parâmetros inválidos. Use year (ex: 2024) e month (1-12)'], 400);
        }

        // Query otimizada com joins e agregações no banco
        $schedules = DB::table('schedules')
            ->select([
                'schedules.id',
                'schedules.date',
                'schedules.locality',
                'schedules.advice_type',
                'schedules.observations',
                'schedules.location',
                'schedules.hours',
                'schedules.km',
                'cooperatives.hour_value',
                'cooperatives.km_value',
                DB::raw("GROUP_CONCAT(users.name SEPARATOR ', ') as user_names"),
                // Somatórios das despesas por tipo
                DB::raw("COALESCE(SUM(CASE WHEN expenses.type = 'displacement' THEN expenses.value ELSE 0 END), 0) as displacement_total"),
                DB::raw("COALESCE(SUM(CASE WHEN expenses.type = 'feeding' THEN expenses.value ELSE 0 END), 0) as feeding_total"),
                DB::raw("COALESCE(SUM(CASE WHEN expenses.type = 'materials' THEN expenses.value ELSE 0 END), 0) as materials_total"),
                DB::raw("COALESCE(SUM(CASE WHEN expenses.type = 'hosting' THEN expenses.value ELSE 0 END), 0) as hosting_total"),
                DB::raw("COALESCE(SUM(CASE WHEN expenses.type = 'taxi' THEN expenses.value ELSE 0 END), 0) as taxi_total"),
                DB::raw("COALESCE(SUM(CASE WHEN expenses.type = 'ticket' THEN expenses.value ELSE 0 END), 0) as ticket_total")
            ])
            ->leftJoin('schedule_user', 'schedules.id', '=', 'schedule_user.schedule_id')
            ->leftJoin('users', 'schedule_user.user_id', '=', 'users.id')
            ->leftJoin('expenses', 'schedules.id', '=', 'expenses.schedule_id')
            ->leftJoin('cooperatives', 'schedules.cooperative_id', '=', 'cooperatives.id')
            ->whereYear('schedules.date', $year)
            ->whereMonth('schedules.date', $month)
            ->groupBy([
                'schedules.id',
                'schedules.date',
                'schedules.locality',
                'schedules.advice_type',
                'schedules.observations',
                'schedules.location',
                'schedules.hours',
                'schedules.km'
            ])
            ->orderBy('schedules.date')
            ->get();

        if ($schedules->isEmpty()) {
            return response()->json(['message' => 'Nenhuma schedule encontrada para o período especificado'], 404);
        }

        // Nome do arquivo
        $filename = "schedules_report_{$month}_{$year}.csv";

        // Cabeçalhos do CSV
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        // Criar o CSV
        $callback = function () use ($schedules) {
            $file = fopen('php://output', 'w');

            // BOM para UTF-8 (evita problemas com acentos)
            fwrite($file, "\xEF\xBB\xBF");

            // Cabeçalhos das colunas
            fputcsv($file, [
                'Data',
                'Municipio/UF',
                'Acessor',
                'Nº Assessoria',
                'Observações',
                'Local/Escola',
                'Horas',
                'Horas/R$',
                'KM',
                'KM/R$',
                'Deslocamento',
                'Alimentação',
                'Materiais',
                'Hospedagem',
                'Taxi/Uber',
                'Passagem'
            ], ';');

            // Dados
            foreach ($schedules as $schedule) {
                fputcsv($file, [
                    Carbon::parse($schedule->date)->format('d/m/Y'), // Data formatada
                    $schedule->locality,
                    $schedule->user_names,
                    $schedule->advice_type,
                    $schedule->observations,
                    $schedule->location,
                    $schedule->hours,
                    $schedule->hour_value,
                    $schedule->km,
                    $schedule->km_value,
                    $schedule->displacement_total,
                    $schedule->feeding_total,
                    $schedule->materials_total,
                    $schedule->hosting_total,
                    $schedule->taxi_total,
                    $schedule->ticket_total
                ], ';');
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
