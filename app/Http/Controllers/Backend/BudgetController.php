<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\VillageBudget;
use App\Models\BudgetTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BudgetController extends Controller
{
    public function index(Request $request)
    {
        $query = VillageBudget::query();
        
        if ($request->filled('year')) {
            $query->where('fiscal_year', $request->year);
        }
        
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        
        $budgets = $query->orderBy('fiscal_year', 'desc')
                         ->orderBy('category')
                         ->paginate(20);
        
        // Get summary statistics
        $currentYear = date('Y');
        $totalBudget = VillageBudget::where('fiscal_year', $currentYear)->sum('planned_amount');
        $totalRealized = BudgetTransaction::whereHas('budget', function($q) use ($currentYear) {
            $q->where('fiscal_year', $currentYear);
        })->sum('amount');
        
        $summary = [
            'total_budget' => $totalBudget,
            'total_realized' => $totalRealized,
            'remaining' => $totalBudget - $totalRealized,
            'realization_percentage' => $totalBudget > 0 ? ($totalRealized / $totalBudget) * 100 : 0
        ];
        
        return view('backend.budget.index', compact('budgets', 'summary'));
    }
    
    public function create()
    {
        return view('backend.budget.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'year' => 'required|integer|min:2020|max:2030',
            'category' => 'required|in:pendapatan,belanja_pegawai,belanja_barang,belanja_modal,belanja_sosial',
            'sub_category' => 'required|string|max:255',
            'description' => 'required|string',
            'planned_amount' => 'required|numeric|min:0',
            'source' => 'nullable|string|max:255',
            'notes' => 'nullable|string'
        ]);
        
        VillageBudget::create([
            'fiscal_year' => $request->fiscal_year,
            'budget_type' => $request->budget_type,
            'category' => $request->category,
            'sub_category' => $request->sub_category,
            'description' => $request->description,
            'planned_amount' => $request->planned_amount,
            'created_by' => auth()->id()
        ]);
        
        return redirect()->route('admin.budget.index')
                         ->with('success', 'Anggaran berhasil ditambahkan.');
    }
    
    public function show(VillageBudget $budget)
    {
        $transactions = $budget->transactions()
                              ->orderBy('transaction_date', 'desc')
                              ->paginate(10);
        
        $summary = [
            'total_transactions' => $budget->transactions()->count(),
            'total_spent' => $budget->transactions()->sum('amount'),
            'remaining_budget' => $budget->planned_amount - $budget->transactions()->sum('amount')
        ];
        
        return view('backend.budget.show', compact('budget', 'transactions', 'summary'));
    }
    
    public function edit(VillageBudget $budget)
    {
        return view('backend.budget.edit', compact('budget'));
    }
    
    public function update(Request $request, VillageBudget $budget)
    {
        $request->validate([
            'fiscal_year' => 'required|integer|min:2020|max:2030',
            'budget_type' => 'required|in:pendapatan,belanja',
            'category' => 'required|string|max:255',
            'sub_category' => 'nullable|string|max:255',
            'description' => 'required|string',
            'planned_amount' => 'required|numeric|min:0'
        ]);
        
        $budget->update([
            'fiscal_year' => $request->fiscal_year,
            'budget_type' => $request->budget_type,
            'category' => $request->category,
            'sub_category' => $request->sub_category,
            'description' => $request->description,
            'planned_amount' => $request->planned_amount
        ]);
        
        return redirect()->route('admin.budget.index')
                         ->with('success', 'Anggaran berhasil diperbarui.');
    }
    
    public function destroy(VillageBudget $budget)
    {
        // Check if budget has transactions
        if ($budget->transactions()->count() > 0) {
            return redirect()->route('admin.budget.index')
                           ->with('error', 'Tidak dapat menghapus anggaran yang sudah memiliki transaksi.');
        }
        
        $budget->delete();
        
        return redirect()->route('admin.budget.index')
                         ->with('success', 'Anggaran berhasil dihapus.');
    }
    
    public function transactions(VillageBudget $budget)
    {
        $transactions = $budget->transactions()
                              ->orderBy('transaction_date', 'desc')
                              ->paginate(20);
        
        return view('backend.budget.transactions', compact('budget', 'transactions'));
    }
    
    public function addTransaction(Request $request, VillageBudget $budget)
    {
        $request->validate([
            'transaction_date' => 'required|date',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'transaction_type' => 'required|in:income,expense',
            'reference_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string'
        ]);
        
        // Check if transaction exceeds remaining budget (for expenses)
        if ($request->transaction_type === 'expense') {
            $remainingBudget = $budget->planned_amount - $budget->transactions()->where('transaction_type', 'expense')->sum('amount');
            if ($request->amount > $remainingBudget) {
                return back()->with('error', 'Jumlah transaksi melebihi sisa anggaran.');
            }
        }
        
        BudgetTransaction::create([
            'village_budget_id' => $budget->id,
            'transaction_date' => $request->transaction_date,
            'description' => $request->description,
            'amount' => $request->amount,
            'transaction_type' => $request->transaction_type,
            'reference_number' => $request->reference_number,
            'notes' => $request->notes,
            'user_id' => auth()->id()
        ]);
        
        return redirect()->route('admin.budget.transactions', $budget)
                         ->with('success', 'Transaksi berhasil ditambahkan.');
    }
    
    public function deleteTransaction(BudgetTransaction $transaction)
    {
        $budgetId = $transaction->village_budget_id;
        $transaction->delete();
        
        return redirect()->route('admin.budget.transactions', $budgetId)
                         ->with('success', 'Transaksi berhasil dihapus.');
    }
    
    public function reportSummary(Request $request)
    {
        $year = $request->get('year', date('Y'));
        
        $budgets = VillageBudget::where('fiscal_year', $year)->get();
        
        $summary = $budgets->groupBy('category')->map(function($categoryBudgets) {
            $allocated = $categoryBudgets->sum('planned_amount');
            $spent = 0;
            
            foreach ($categoryBudgets as $budget) {
                $spent += $budget->transactions()->where('transaction_type', 'expense')->sum('amount');
            }
            
            return [
                'allocated' => $allocated,
                'spent' => $spent,
                'remaining' => $allocated - $spent,
                'percentage' => $allocated > 0 ? ($spent / $allocated) * 100 : 0
            ];
        });
        
        return view('backend.budget.report-summary', compact('summary', 'year'));
    }
    
    public function export(VillageBudget $budget)
    {
        // In a real application, you would generate Excel/PDF export here
        // For now, return JSON data
        
        $data = [
            'budget' => $budget,
            'transactions' => $budget->transactions()->get(),
            'summary' => [
                'allocated' => $budget->planned_amount,
                'spent' => $budget->transactions()->where('transaction_type', 'expense')->sum('amount'),
                'income' => $budget->transactions()->where('transaction_type', 'income')->sum('amount')
            ]
        ];
        
        return response()->json($data);
    }
}