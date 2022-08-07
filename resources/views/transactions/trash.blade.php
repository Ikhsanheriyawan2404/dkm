@extends('layouts.app', compact('title'))

@section('content')
@include('sweetalert::alert')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
        <h1 class="m-0">{{ $title ?? '' }}</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            {{-- <li class="breadcrumb-item active">{{ Breadcrumbs::render('book_trash') }}</li> --}}
        </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<div class="container-fluid mb-3 d-flex justify-content-end">
    <div class="row">
        <div class="col-12">
            <form action="{{ route('deleteAll.transactions') }}" method="post">
                @csrf
                @method('DELETE')
                <button type="submit" onclick="return confirm('Apakah yakin ingin menghapus semua data secara permanen?')" class="btn btn-sm btn-danger">Hapus semua <i class="fa fa-trash"></i></button>
            </form>
        </div>
    </div>
</div>

<div class="container">
    @include('components.alerts')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Data Sampah Keuangan Transaksi</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body table-responsive">
            <table id="data-table" class="table table-sm table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 1%">No.</th>
                        <th>Keterangan</th>
                        <th>Masuk</th>
                        <th>Keluar</th>
                        <th class="text-center" style="width: 3%"><i class="fas fa-cogs"></i></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transactions as $transaction)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $transaction->description }}</td>
                        <td>{{ number_format($transaction->debit) }}</td>
                        <td>{{ number_format($transaction->credit) }}</td>
                        <td class="d-flex justify-content-center">
                            <div class="btn-group">
                                <a class="badge badge-primary dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                </a>
                                <div class="dropdown-menu">
                                    <a href="{{ route('restore.transactions', $transaction->id) }}" class="dropdown-item" class="btn btn-sm btn-primary">Restore</a>
                                    <form action="{{ route('deletePermanent.transactions', $transaction->id) }}" method="POST">
                                        <button type="submit" class="dropdown-item" onclick="return confirm('Apakah yakin ingin menghapus ini?')">Hapus</button>
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
</div>

@endsection
