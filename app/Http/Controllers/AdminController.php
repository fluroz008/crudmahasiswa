<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Mahasiswa;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class AdminController extends Controller
{
    public function cek(){
        $mahasiswa = Mahasiswa::with(['user'])->get();
        $a = 0;
        return view('cek', compact('mahasiswa', 'a'));
    }

    public function tambah(){
        return view('tambah', [ 'jurusan' => ['Sistem Informasi','Teknik Informatika','Ilmu Komputer'] ]);
    }

    public function edit($id){
        $jurusan = [ 'Sistem Informasi','Teknik Informatika','Ilmu Komputer' ];
        $mahasiswa = Mahasiswa::with(['user'])->where('id', $id)->first();
        return view('edit', compact('mahasiswa', 'jurusan'));
    }

    public function tambah_data(Request $request){
        $validated = $request->validate([
            'nama' => 'required',
            'foto' => 'nullable',
            'email' => 'required|email',
            'password' => 'required',
            'nim' => 'required',
            'jurusan' => 'required',
            'kelas' => 'required',
            'angkatan' => 'required'
        ]);

        $count = Mahasiswa::where('nim', $request->input('nim'))->count();
        if($count > 0){
            Alert::error('Gagal menambahkan data', 'NIM sudah terdaftar!');
            return redirect('/mahasiswa/tambah');
        }

        $filename = false;
        $file = $request->file('foto');
        if ($file){
            $convert = explode('/' , $file->getMimeType());
            if ($convert[0] == 'image'){
                $filename = 'foto-mahasiswa' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('public/', $filename);
            }
            else{
                Alert::error('Gagal tambah data', 'Format file bukan foto.');
                return redirect('/mahasiswa/tambah');
            }
        }
        
        $tambahuser = User::create([
            'nama' => $request->input('nama'),
            'password' => Hash::make($request->input('password')),
            'email' => $request->input('email'),
            'foto' => $filename,
        ])->id;

        Mahasiswa::create([
            'user_id' => $tambahuser,
            'nim' => $request->input('nim'),
            'jurusan' => $request->input('jurusan'),
            'kelas' => $request->input('kelas'),
            'angkatan' => $request->input('angkatan')
        ]);
        Alert::success('Berhasil tambah data', 'Berhasil menambahkan data mahasiswa ke database');
        return redirect('/mahasiswa/tambah');
    }

    public function edit_data(Request $request, $id){
        $validated = $request->validate([
            'nama' => 'nullable',
            'foto' => 'nullable',
            'email' => 'nullable|email',
            'password' => 'nullable',
            'jurusan' => 'nullable',
            'kelas' => 'nullable',
            'angkatan' => 'nullable'
        ]);

        $check = [];

        foreach($request->input() as $key => $value){
            if ($key != '_method' && $key != '_token'){
                if ($value && strlen($value) > 0){
                    if ($key == 'password'){
                        $check[$key] = Hash::make($value);
                    }
                    else{
                        $check[$key] = $value;
                    }
                }
            }
        }

        $filename = false;
        $file = $request->file('foto');
        if ($file){
            $convert = explode('/' , $file->getMimeType());
            if ($convert[0] == 'image'){
                $filename = 'foto-mahasiswa' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('public/', $filename);
            }
            else{
                Alert::error('Gagal tambah data', 'Format file bukan foto.');
                return redirect('/mahasiswa/tambah');
            }
        }

        $datauser = [];
        $datamhs = [];
        $filteruser = ['nama', 'email', 'password'];
        $filtermhs = [ 'jurusan', 'kelas', 'angkatan'];

        foreach($check as $key => $value){
            foreach($filteruser as $user_key){
                if ($key == $user_key){
                    $datauser[$key] = $value;
                }
            }
            foreach($filtermhs as $mhs_key){
                if ($key == $mhs_key){
                    $datamhs[$key] = $value;
                }
            }
        }

        if ($filename){
            $datauser['foto'] = $filename;
        }

        $id_user = Mahasiswa::where('id', $id)->first()->id;
        User::where('id', $id_user)->update($datauser);
        Mahasiswa::where('id', $id)->update($datamhs);

        Alert::success('Berhasil edit data', 'Berhasil mengedit data mahasiswa ke database');
        return redirect('/mahasiswa');
    }

    public function hapus_data(Response $response, $id){
        $id_user = Mahasiswa::where('id', $id)->first()->id;

        Mahasiswa::where('id', $id)->delete();
        User::where('id', $id_user)->delete();
        return response()->json([
            'id' => $id
        ]);
    }
}
