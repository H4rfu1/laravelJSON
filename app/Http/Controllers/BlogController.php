<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // buat data JSON -> simpan di storage
        // $data = [
        //       [
        //         'id' => 1,
        //         'judul' => 'Ngadimin',
        //         'penulis' => 'anonimous',
        //         'isi' => 'Cheat perubahan',
        //       ],
        //       [
        //         'id' => 2,
        //         'judul' => 'Ngadimin',
        //         'penulis' => 'ngadimin',
        //         'isi' => 'Cheat membawa',
        //       ],
        //     ];
        //     Storage::put('public/data.json', json_encode($data, JSON_PRETTY_PRINT));
        //     dd($data);

        $data = file_get_contents('storage/data.json');
        $data = json_decode($data);
        return view('home', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('buatPost');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = [];
        //ambil data json
        if (Storage::exists('public/data.json')) {
            $data = json_decode(Storage::get('public/data.json'));
            //untuk menambah id terakhir data json dan ditambah 1
            $id_terakhir = (int)end($data)->id + 1;
        }
        $databaru['id'] = $id_terakhir;
        $databaru = $request->only(['judul', 'penulis', 'isi']);
        array_push($data, $databaru);

        //melakukan replase file baru
        Storage::put('public/data.json', json_encode($data, JSON_PRETTY_PRINT));

        //with itu flash message
        return redirect('/blog')->with('status', 'Artikel berhasil ditambah');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = file_get_contents('storage/data.json');
        $data = json_decode($data);
        $item = null;
        foreach($data as $key => $value) {
            if ($id == $value->id) {
                $item = $value;
                Storage::put('public/data.json', json_encode($data, JSON_PRETTY_PRINT));
                break;
            }
        }
            if( isset($item) ){
            return view('detail', ['name' => 'blog', 'd' => $item]);
            }else{
            return redirect('/');
            }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = file_get_contents('storage/data.json');
        $data = json_decode($data);

        $item = null;
        foreach($data as $struct) {
            if ($id == $struct->id) {
                $item = $struct;
                break;
            }
        }
        //cek apakah data tadi idnya ada
            if( isset($item) ){
            return view('editPost', ['data' => $item]);
            }else{
            return redirect('/');
            }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = file_get_contents('storage/data.json');
        $data = json_decode($data);

        $item = null;
        foreach($data as $key => $value) {
            if ($id == $value->id) {
                $item = 'ada';
                $data[$key]->judul = $request->input('judul');
                $data[$key]->penulis = $request->input('penulis');
                $data[$key]->isi = $request->input('isi');
                break;
            }
        }

        //cek apakah data tadi idnya ada
        if(isset($item)){
            Storage::put('public/data.json', json_encode($data, JSON_PRETTY_PRINT));
            return redirect('blog/')->with('status', 'Artikel berhasil diubah');
          }else {
            return redirect('blog/'.$id.'/edit')->with('status', 'Artikel gagal diubah');
          }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Storage::get('public/data.json');
        $data = json_decode($data);

        $item = null;
        foreach($data as $key => $value) {
            if ($id == $value->id) {
                $item = 'ada';
                unset($data[$key]);
                break;
            }
        }
        //cek apakah data tadi idnya ada
        if(isset($item)){
            Storage::put('public/data.json', json_encode($data, JSON_PRETTY_PRINT));
            return redirect('blog/')->with('status', 'Artikel berhasil dihapus');
          }else {
            return redirect('blog/')->with('status', 'Artikel gagal dihapus');
          }
    }
}
