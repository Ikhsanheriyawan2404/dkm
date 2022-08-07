<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\TransactionRequest;

class TransactionController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $transactions = Transaction::latest()->get();
            return DataTables::of($transactions)
                    ->addIndexColumn()
                    ->editColumn('debit', function (Transaction $transaction) {
                        return number_format($transaction->debit);
                    })
                    ->editColumn('credit', function (Transaction $transaction) {
                        return number_format($transaction->credit);
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
                'description' => request('description'),
                'debit' => request('transactionType') == 'debit' ? (int)strtok(str_replace(".", "", request('nominal')), "Rp ") : 0,
                'credit' => request('transactionType') == 'credit' ? (int)preg_replace("/[^0-9]/", "", request('nominal')) : 0,
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
}
