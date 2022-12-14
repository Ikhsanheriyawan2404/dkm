<?php

namespace App\Http\Controllers;

use PDF;
use Carbon\Carbon;
use App\Models\Transaction;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\TransactionRequest;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:transaction-module', ['only' => ['index', 'store', 'edit', 'deleteSelected', 'destroy', 'trash', 'restore', 'deletePermanent', 'deleteAll']]);
    }

    public function index()
    {
        if (request()->ajax()) {
            $transactions = Transaction::orderBy('date', 'desc')->get();
            return DataTables::of($transactions)
                    ->addIndexColumn()
                    ->editColumn('debit', function (Transaction $transaction) {
                        return $transaction->debit != NULL ? number_format($transaction->debit) : '-';
                    })
                    ->editColumn('credit', function (Transaction $transaction) {
                        return $transaction->credit != NULL ? number_format($transaction->credit) : '-';
                    })
                    ->editColumn('date', function (Transaction $transaction) {
                        return date('d-m-Y', strtotime($transaction->date));
                    })
                    ->addColumn('checkbox', function ($row) {
                        return '<input type="checkbox" name="checkbox" id="check" class="checkbox" data-id="' . $row->id . '">';
                    })
                    ->addColumn('action', function($row){
                        $btn =
                        '<div class="btn-group">
                            <a class="badge bg-navy dropdown-toggle dropdown-icon" data-toggle="dropdown">
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="javascript:void(0)" data-id="'.$row->id.'" class="btn btn-primary btn-sm" id="editTransaction">Edit</a>
                                <form action=" ' . route('transactions.destroy', $row->id) . '" method="POST">
                                    <button type="submit" class="dropdown-item" onclick="return confirm(\'Apakah yakin ingin menghapus ini?\')">Hapus</button>
                                ' . csrf_field() . '
                                ' . method_field('DELETE') . '
                                </form>
                            </div>
                        </div>';
                        return $btn;
                    })
                    ->rawColumns(['checkbox', 'action'])
                    ->make(true);
        }

        return view('transactions.index',[
            'title' => 'Data Transaksi',
        ]);
    }

    public function store(TransactionRequest $request)
    {
        $request->validated();

        Transaction::updateOrCreate(
            ['id' => request('transaction_id')],
            [
                'date' => request('date'),
                'description' => request('description'),
                'debit' => request('transactionType') == 'debit' ? (int)strtok(str_replace(".", "", request('nominal')), "Rp ") : null,
                'credit' => request('transactionType') == 'credit' ? (int)preg_replace("/[^0-9]/", "", request('nominal')) : null,
            ]);
    }

    public function edit(Transaction $transaction)
    {
        return response()->json($transaction);
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();
        toast('Data transaksi berhasil dihapus!','success');
        return back();
    }

    public function deleteSelected()
    {
        $id = request('id');
        Transaction::whereIn('id', $id)->delete();
        return response()->json(['code'=> 1, 'msg' => 'Data transaksi berhasil dihapus']);
    }

    public function trash()
    {
        $transactions = Transaction::onlyTrashed()->latest()->get();
        return view('transactions.trash', [
            'title' => 'Data Sampah Transaksi',
            'transactions' => $transactions,
        ]);
    }

    public function restore($id)
    {
        $transaction = Transaction::onlyTrashed()->where('id', $id);
        $transaction->restore();
        toast('Data transaksi berhasil dipulihkan!', 'success');
        return redirect()->back();
    }

    public function deletePermanent($id)
    {
        $transaction = Transaction::onlyTrashed()->where('id', $id);
        $transaction->forceDelete();

        toast('Data transaksi berhasil dihapus permanen!', 'success');
        return redirect()->back();
    }

    public function deleteAll()
    {
        $transactions = Transaction::onlyTrashed();
        $transactions->forceDelete();

        toast('Semua data transaksi berhasil dihapus permanen!', 'success');
        return redirect()->back();
    }

    public function printPdf()
    {
        if (request('startDate') && request('endDate')) {
            $startDate = Carbon::parse(request('startDate'))->format('Y-m-d');
            $endDate = Carbon::parse(request('endDate'))->format('Y-m-d');
            $transactions = Transaction::whereBetween('date', [$startDate, $endDate])->orderBy('date', 'desc')->get();
            $period = [$startDate, $endDate];
        } else {
            $period = [];
            $transactions = Transaction::orderBy('date', 'desc')->get();
        }

        // $pdf = app('dompdf.wrapper');
        $pdf = PDF::loadView('transactions.pdf', compact(['transactions', 'period']))->setPaper('a4', 'landscape');
        return $pdf->stream();
    }
}
