@extends('layouts.app')

@section('content')
<div class="container">
   <div class="row justify-content-center">
      <div class="col-md-10">
         <div class="row">
            <div class="col">
               <h1 class="h3 mb-2 text-gray-800">Data Akun</h1>
               <p class="mb-4">Halaman ini untuk mengelola akun staff dan admin</p>
            </div>
            <div class="col text-right">
               <a href="{{ url('/home/create') }}" class="btn btn-primary mt-3 shadow-sm">Tambah Data</a>
            </div>
         </div>
         @if(session('status'))
         <div class="alert alert-success">
            {{session('status')}}
         </div>
         @endif
         @if(session('danger'))
         <div class="alert alert-danger">
            {{session('danger')}}
         </div>
         @endif
      </div>
   </div>


   <div class="row justify-content-center">
    <div class="col-md-10">
        <!-- DataTales Example -->
        <div class="card shadow-sm mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Tabel Data Akun</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>NO</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Aksi</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ ucwords($user->name) }}</td>
                                <td>{{ strtolower($user->email) }}</td>
                                <td>
                                    @switch($user->role)
                                        @case('Admin')
                                            Administrator
                                            @break
                                        @case('Staff')
                                            Staff (Hanya Melihat)
                                            @break
                                        @case('Staff2')
                                            Staff Proses
                                            @break
                                        @case('Staff3')
                                            Staff Gudang
                                            @break
                                        @case('Staff4')
                                            Staff HRD
                                            @break
                                        @case('Staff5')
                                            Staff Keuangan
                                            @break
                                        @case('Staff6')
                                            Staff IT
                                            @break
                                        @case('Staff7')
                                            Staff Purchase
                                            @break
                                        @default
                                            Role Tidak Diketahui
                                    @endswitch
                                </td>
                                <td>
                                    <a href="{{ url('/home/'.$user->id.'/edit') }}" class="btn btn-warning"><i class="fas fa-user-edit"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div> 
</div>
@endsection
