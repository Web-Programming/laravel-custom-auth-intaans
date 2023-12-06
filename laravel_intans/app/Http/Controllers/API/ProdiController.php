<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Prodi;

class ProdiController extends BaseController
{
    public function index()
    {
        //mengambil data dari tabel prodis dan menyimpannya pada variabel $prodis
        $prodis = Prodi::all();
        return $this->sendResponse($prodis, "Data Prodi");
        // $success['data'] = $prodis;
        // return $this->sendResponse($success, 'Data prodi');
    }
    public function store(Request $request)
    {
        //membuat validasi semua field wajib diisi
        $validasi = $request->validate([
            'nama' => 'required|min:5|max:20',
            'foto' => 'required|file|image|max:5000'
        ]);

        $ext = $request->foto->getClientOriginalExtension();
        $nama_file = "foto-" . time() . "." . $ext;
        //namaf ile baru: foto-1234343.png
        $path = $request->foto->storeAs('public', $nama_file);

        //melakukan insert data
        $prodi = new Prodi();
        $prodi->nama = $validasi['nama'];
        $prodi->foto = $nama_file;

        //jika berhasil maka simpan data dengan methode $post->save()
        if($prodi->save()){
            $success['data'] = $prodi;
            return $this->sendResponse($success, 'Data prodi berhasil disimpan.');
        }else{
            return $this->sendError('Error.', ['error' => 'Data prodi gagal disimpan.']);
        }
    }

    public function update(Request $request, $id){
        // membuat validasi semua field wajib di isi
        $validasi = $request->validate([
            "nama"=> "required|min:5|max:20",
            "foto"=> "required|file|image|max:5000",
        ]);
        //ambil extension file
        $ext = $request->foto->getClientOriginalExtension();
        //ganti nama file
        $nama_file = "foto-" . time() .".". $ext;
        // nama file baru: foto-12344.png
        //simpan file ke dalam folder public
        $path = $request->foto->storeAs("public", $nama_file);

        //cari data berdasarkan isi
        $prodi = Prodi::find($id);
        // isi property nama dan foto
        $prodi->nama = $validasi["nama"];
        $prodi->foto = $nama_file;

        //Jika berhasil maka simpan data prodi dengan methode $prodi->save
        if( $prodi->save() ){
            $success['data'] = $prodi;
            return $this->sendResponse($success,'Data prodi berhasil diperbarui');
        } else{
            return $this->sendError('Error', ['errors'=> 'Data prodi gagal diperbarui']);
        }
    }

    public function delete($id){
        $prodis = Prodi::findOrFail($id);
        // hapus data menggunakan metode delete
        if($prodis->delete){
            $success ['data'] = [];
            return $this->sendResponse($success, "Data Prodi dengan id $id berhasil dihapus");
        }else {
            return $this->sendError('Error', ['error' => 'Data prodi gagal dihapus']);
        }
    }
}
