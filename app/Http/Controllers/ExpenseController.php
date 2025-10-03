<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Str;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public static function index()
    {
        return Response('success', 200)->content(json_encode(Expense::all()));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        return $request;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'expense_type_id' => 'required|exists:expense_types,id',
            'schedule_id' => 'required|exists:schedules,id',
            'amount' => 'required|numeric|min:0',
            'observation' => 'nullable|string|max:255',
            'attachment' => 'nullable|file|mimes:pdf,jpeg,png,jpg,docx|max:10240',
        ]);

        try {
            $filePath = null;

            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $fileName = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('expenses', $fileName, "public");

                if (!Storage::disk('public')->exists($filePath)) {
                    return response()->json(['error' => 'Falha ao salvar o arquivo'], 500);
                }
            }

            Expense::create([
                'user_id' => Auth::user()->id,
                'expense_type_id' => $validated['expense_type_id'],
                'schedule_id' => $validated['schedule_id'],
                'amount' => $validated['amount'],
                'observation' => $validated['observation'],
                'attachment' => $filePath,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Despesa criada com sucesso!'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar despesa',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        return response()->json(["expense" => Expense::findOrFail($request->id)]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Expense $expense)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|integer|exists:expenses,id',
            'expense_type_id' => 'required|integer|exists:expense_types,id',
            'user_id' => 'required|integer|exists:users,id',
            'amount' => 'required|numeric|min:0',
            'observation' => 'nullable|string|max:3000',
            'attachment' => 'nullable|file|mimes:pdf,jpeg,png,jpg,docx|max:10240',
        ]);

        try {
            $expense = Expense::findOrFail($validated['id']);

            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $fileName = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('expenses', $fileName, "public");

                if (!Storage::disk('public')->exists($filePath)) {
                    return response()->json(['error' => 'Falha ao salvar o arquivo.'], 500);
                }

                if ($expense->attachment && !empty($expense->attachment)) {
                    Storage::disk('public')->delete($expense->attachment);
                    if (Storage::disk('public')->exists($expense->attachment)) {
                        return response()->json(['error' => 'Falha ao remover o arquivo.'], 500);
                    }
                }

                $expense->attachment = $filePath;
            }

            $expense->expense_type_id = $validated['expense_type_id'];
            $expense->amount = $validated['amount'];
            $expense->observation = $validated['observation'];
            $expense->user_id = $validated['user_id'];
            $expense->updated_at = new DateTime("now");
            $expense->save();

            return response()->json([
                'success' => true,
                'message' => 'Despesa editada com sucesso!'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao editar despesa!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $expense = Expense::findOrFail($id);

            if ($expense->attachment && !empty($expense->attachment)) {
                Storage::disk('public')->delete($expense->attachment);
                if (Storage::disk('public')->exists($expense->attachment)) {
                    return response()->json(['error' => 'Falha ao remover o arquivo'], 500);
                }
            }

            $expense->forceDelete();

            return response()->json([
                'success' => true,
                'message' => 'Despesa deletada com sucesso!'
            ], 200);
        } catch (\Exception $e) {
            // Log do erro
            \Log::error('Erro ao deletar despesa.', [
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
}
