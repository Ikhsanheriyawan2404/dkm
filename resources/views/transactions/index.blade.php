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
                        {{-- <li class="breadcrumb-item active">{{ Breadcrumbs::render('books') }}</li> --}}
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <div class="container-fluid mb-3 d-flex justify-content-end">
        <div class="row">
            <div class="col-12">
                {{-- @can('book-module') --}}
                    <button class="btn btn-sm bg-navy" id="createNewItem">Tambah <i class="fa fa-plus"></i></button>
                    <button class="btn btn-sm btn-danger d-none" id="deleteAllBtn">Hapus Semua</button>
                {{-- @endcan --}}
            </div>
        </div>
    </div>

    <div class="container">
        @include('components.alerts')
        <div class="card card-navy">
            <div class="card-header">
                <h3 class="card-title">Data Transaksi Keuangan</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive">
                <table id="data-table" class="table table-sm table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 1%">No.</th>
                            <th class="text-center"><input type="checkbox" name="main_checkbox"><label></label></th>
                            <th>Keterangan</th>
                            <th>Masuk</th>
                            <th>Keluar</th>
                            <th class="text-center" style="width: 5%"><i class="fas fa-cogs"></i> </th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>

    <!-- MODAL -->
    <div class="modal fade" id="modal-md">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" id="itemForm" name="itemForm">
                    @csrf
                    <input type="hidden" name="transaction_id" id="transaction_id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nominal">Nominal <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm mr-2" name="nominal" id="nominal"
                                required autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="transactionType" id="labelTransaction">Jenis Transaksi <span class="text-danger">*</span></label>
                            <select name="transactionType" id="transactionType" class="form-control form-control-sm mr-2">
                                <option selected disabled>Pilih Tipe Transaksi</option>
                                <option value="debit">Masuk</option>
                                <option value="credit">Keluar</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="description">Keterangan <span class="text-danger">*</span></label>
                            <textarea type="text" class="form-control form-control-sm mr-2" name="description" id="description"
                                required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-sm bg-navy" id="saveBtn" value="create">Save</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

@endsection

@section('custom-styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('asset') }}/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('asset') }}/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('asset') }}/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

    <link rel="stylesheet" href="{{ asset('asset') }}/plugins/sweetalert2/sweetalert2.min.css">
    <link rel="stylesheet" href="{{ asset('asset') }}/plugins/toastr/toastr.min.css">
@endsection
@section('custom-scripts')
    <!-- DataTables  & Plugins -->
    <script src="{{ asset('asset') }}/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ asset('asset') }}/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('asset') }}/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="{{ asset('asset') }}/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="{{ asset('asset') }}/plugins/sweetalert2/sweetalert2.min.js"></script>
    <script src="{{ asset('asset') }}/plugins/toastr/toastr.min.js"></script>
    <script src="{{ asset('asset') }}/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(function() {
            bsCustomFileInput.init();

            let table = $('#data-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,

                ajax: "{{ route('transactions.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        className: 'dt-body-center'
                    },
                    {
                        data: 'checkbox',
                        name: 'checkbox',
                        orderable: false,
                        searchable: false,
                        className: 'dt-body-center'
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'debit',
                        name: 'debit',
                        className: 'dt-body-right'
                    },
                    {
                        data: 'credit',
                        name: 'credit',
                        className: 'dt-body-right'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'dt-body-center'
                    },
                ],
            }).on('draw', function() {
                $('input[name="checkbox"]').each(function() {
                    this.checked = false;
                });
                $('input[name="main_checkbox"]').prop('checked', false);
                $('button#deleteAllBtn').addClass('d-none');
            });

            $('#createNewItem').click(function() {
                setTimeout(function() {
                    $('#nominal').focus();
                }, 500);
                $('#saveBtn').removeAttr('disabled');
                $('#saveBtn').html("Simpan");
                $('#member_id').val('');
                $('#itemForm').trigger("reset");
                $('#modal-title').html("Tambah Transaksi");
                $('#modal-md').modal('show');
                $('#transactionType').show();
                $('#labelTransaction').show();
            });

            $('body').on('click', '#editTransaction', function() {
                var transaction_id = $(this).data('id');
                $.get("{{ route('transactions.index') }}" + '/' + transaction_id + '/edit', function(data) {
                    $('#modal-md').modal('show');
                    setTimeout(function() {
                        $('#nominal').focus();
                    }, 500);
                    $('#modal-title').html("Edit Transaksi");
                    $('#saveBtn').removeAttr('disabled');
                    $('#saveBtn').html("Simpan");
                    $('#transaction_id').val(data.id);
                    // if (data.debit != NULL) {
                    //     let nominal = data.debit
                    // } else {
                    //     let nominal = data.credit
                    // }
                    $('#nominal').val(data.credit === null ? data.debit : data.credit);
                    $('#description').val(data.description);
                    $('#transactionType').val(data.debit > 0 ? 'debit' : 'credit');
                    $('#transactionType').hide();
                    $('#labelTransaction').hide();
                })
            });

            $('#saveBtn').click(function(e) {
                e.preventDefault();
                var formData = new FormData($('#itemForm')[0]);
                $.ajax({
                    data: formData,
                    url: "{{ route('transactions.store') }}",
                    contentType: false,
                    processData: false,
                    type: "POST",
                    success: function(data) {
                        $('#saveBtn').attr('disabled', 'disabled');
                        $('#saveBtn').html('Simpan ...');
                        $('#itemForm').trigger("reset");
                        $('#modal-md').modal('hide');
                        table.draw();
                    },
                    error: function(data) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Data masih kosong!',
                        });
                    }
                });
            });

            $(document).on('click', 'input[name="main_checkbox"]', function() {
                if (this.checked) {
                    $('input[name="checkbox"]').each(function() {
                        this.checked = true;
                    });
                } else {
                    $('input[name="checkbox"]').each(function() {
                        this.checked = false;
                    });
                }
                toggledeleteAllBtn();
            });

            $(document).on('change', 'input[name="checkbox"]', function() {
                if ($('input[name="checkbox"]').length == $('input[name="checkbox"]:checked').length) {
                    $('input[name="main_checkbox"]').prop('checked', true);
                } else {
                    $('input[name="main_checkbox"]').prop('checked', false);
                }
                toggledeleteAllBtn();
            });

            function toggledeleteAllBtn() {
                if ($('input[name="checkbox"]:checked').length > 0) {
                    $('button#deleteAllBtn').text('Hapus (' + $('input[name="checkbox"]:checked').length + ')')
                        .removeClass('d-none');
                } else {
                    $('button#deleteAllBtn').addClass('d-none');
                }
            }

            $(document).on('click', 'button#deleteAllBtn', function() {
                var checkedItem = [];
                $('input[name="checkbox"]:checked').each(function() {
                    checkedItem.push($(this).data('id'));
                });
                var url = '{{ route('transactions.deleteSelected') }}';
                if (checkedItem.length > 0) {
                    swal.fire({
                        title: 'Apakah yakin?',
                        html: 'Ingin menghapus <b>(' + checkedItem.length + ')</b> transaksi?',
                        showCancelButton: true,
                        showCloseButton: true,
                        confirmButtonText: 'Ya Hapus',
                        cancelButtonText: 'Tidak',
                        confirmButtonColor: '#556ee6',
                        cancelButtonColor: '#d33',
                        width: 300,
                        allowOutsideClick: false
                    }).then(function(result) {
                        if (result.value) {
                            $.post(url, {
                                id: checkedItem
                            }, function(data) {
                                if (data.code == 1) {
                                    // $('#data-table').DataTable().ajax.reload(null, true);
                                    table.draw();
                                    toastr.success(data.msg);
                                }
                            }, 'json');
                        }
                    })
                }
            });

            var masuk = document.getElementById('nominal');
            masuk.addEventListener('keyup', function (e) {
                // tambahkan 'Rp.' pada saat form di ketik
                // gunakan fungsi formatmasuk() untuk mengubah angka yang di ketik menjadi format angka
                masuk.value = formatmasuk(this.value, 'Rp ');
            });

            /* Fungsi formatmasuk */
            function formatmasuk(angka, prefix) {
                var number_string = angka.replace(/[^,\d]/g, '').toString(),
                    split = number_string.split(','),
                    sisa = split[0].length % 3,
                    masuk = split[0].substr(0, sisa),
                    ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                // tambahkan titik jika yang di input sudah menjadi angka ribuan
                if (ribuan) {
                    separator = sisa ? '.' : '';
                    masuk += separator + ribuan.join('.');
                }

                masuk = split[1] != undefined ? masuk + ',' + split[1] : masuk;
                return prefix == undefined ? masuk : (masuk ? 'Rp ' + masuk : '');
            }

        });
    </script>
@endsection
