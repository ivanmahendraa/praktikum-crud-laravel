<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Memanggil seluruh data dari table Student
        $students = Student::all();

        return view('student.index', ['students' => $students]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('student.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nim'   => 'required|unique:students,nim',
            'nama'  => 'required',
            'email' => 'required|email',
            'prodi' => 'required'
        ], [
            'nim.required'   => 'NIM harus diisi.',
            'nim.unique'     => 'NIM sudah digunakan.',
            'nama.required'  => 'Nama harus diisi.',
            'email.required' => 'Email harus diisi.',
            'email.email'    => 'Format email tidak valid.',
            'prodi.required' => 'Program studi harus diisi.'
        ]);

        $students = new Student();
        $students->nim   = $request->nim;
        $students->nama  = $request->nama;
        $students->email = $request->email;
        $students->prodi = $request->prodi;

        if ($students->save()) {
            return redirect('/student')->with([
                'type' => 'success',
                'notifikasi' => 'Data berhasil disimpan!'
            ]);
        } else {
            return redirect()->back()->with([
                'type' => 'danger',
                'notifikasi' => 'Data gagal disimpan!'
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $student = Student::where('nim', $id)->first();

        if (!$student) {
            return redirect('/student')->with([
                'type' => 'danger',
                'notifikasi' => 'Data siswa tidak ditemukan!'
            ]);
        }
        return view('student.edit', ['student' => $student]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validasi data input
        $request->validate([
            'nim'   => 'required',
            'nama'  => 'required',
            'email' => 'required|email',
            'prodi' => 'required'
        ], [
            'nim.required'   => 'NIM harus diisi.',
            'nama.required'  => 'Nama harus diisi.',
            'email.required' => 'Email harus diisi.',
            'email.email'    => 'Format email tidak valid.',
            'prodi.required' => 'Program studi harus diisi.'
        ]);

        // Cari data berdasarkan NIM lama yang dikirim dari input hidden 'old_nim'
        $student = Student::where('nim', $request->old_nim)->first();

        if (!$student) {
            return redirect('/student')->with([
                'type' => 'danger',
                'notifikasi' => 'Data tidak ditemukan!'
            ]);
        }

        // Proses Update
        $student->nim   = $request->nim;
        $student->nama  = $request->nama;
        $student->email = $request->email;
        $student->prodi = $request->prodi;

        if ($student->save()) {
            return redirect('/student')->with([
                'type' => 'success',
                'notifikasi' => 'Data berhasil diupdate!'
            ]);
        } else {
            return redirect()->back()->with([
                'type' => 'danger',
                'notifikasi' => 'Data gagal diupdate!'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $student = Student::where('nim', $id)->first();

        if ($student && $student->delete()) {
            return redirect('/student')->with([
                'type' => 'success',
                'notifikasi' => 'Data berhasil dihapus!'
            ]);
        } else {
            return redirect('/student')->with([
                'type' => 'danger',
                'notifikasi' => 'Data gagal dihapus!'
            ]);
        }
    }
}
