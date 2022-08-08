<!DOCTYPE html>
<html>
<head>
	<title>Data Siswa</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
	<style type="text/css">
		table tr td,
		table tr th{
			font-size: 9pt;
		}
	</style>

    <h1>Laporan Keuangan {{ \App\Models\Profile::first()->name }}</h1>
    @if (!empty($period))
        <p>Periode : {{ $period[0] }} - {{ $period[1] }}</p>
    @endif

	<table class='table table-bordered'>
		<thead>
			<tr>
				<th>No</th>
				<th>Keterangan</th>
				<th>Tanggal</th>
				<th>Masuk</th>
				<th>Keluar</th>
			</tr>
		</thead>
		<tbody>
			@foreach($transactions as $transaction)
			<tr>
				<td>{{ $loop->iteration }}</td>
				<td>{{ $transaction->description }}</td>
				<td>{{ date('d-m-Y',strtotime($transaction->date)) }}</td>
				<td style="text-align: right">{{ number_format($transaction->debit) }}</td>
				<td style="text-align: right">{{ number_format($transaction->credit) }}</td>
			</tr>
			@endforeach
		</tbody>
	</table>

</body>
</html>
