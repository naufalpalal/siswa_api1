<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return Siswa::all();
        } catch (\Exception $e) {
            // Menambahkan return response json untuk error handling
            return response()->json(['error' => 'Gagal mengambil data siswa.'], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Tidak digunakan, dibiarkan kosong sesuai RESTful Resource Controller
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'nama' => 'required|string|regex:/^[\pL\s]+$/u|max:255',
                'kelas' => 'required|string|regex:/^XII\sIPA\s\d$/',
                'umur' => 'required|integer|between:6,18',
            ], [
                'nama.regex' => 'Nama hanya boleh mengandung huruf dan spasi.',
                'kelas.regex' => 'Kelas harus mengikuti format "XII IPA 1".',
                'umur.between' => 'Umur harus berada dalam rentang 6 hingga 18 tahun.',
            ]);

            $siswa = Siswa::create($validatedData); // Menambahkan simpan ke database
            return response()->json($siswa, 201); // Status code untuk created
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'errors' => $e->errors() // Mengirim error validasi
            ], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menambahkan data siswa.'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            return Siswa::findOrFail($id);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Siswa tidak ditemukan.'], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Tidak digunakan, dibiarkan kosong sesuai RESTful Resource Controller
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $siswa = Siswa::findOrFail($id); // Menambahkan validasi ID sebelum update
        
            $validatedData = $request->validate([
                'nama' => 'sometimes|required|string|max:255',
                'kelas' => 'sometimes|required|string|max:10',
                'umur' => 'sometimes|required|integer|between:6,18', // Menambahkan rentang umur
            ]);
        
            $siswa->update($validatedData);
        
            return response()->json($siswa);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'errors' => $e->errors() // Menambahkan pesan validasi
            ], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal memperbarui data siswa.'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $siswa = Siswa::findOrFail($id);
            $siswa->delete();
        
            return response()->json(['message' => 'Data siswa berhasil dihapus.'], 200); // Mengirim pesan sukses
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menghapus data siswa.'], 500);
        }
    }
}