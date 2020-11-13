<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Image_uploaded;
use Carbon\Carbon;

class MainController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      // Storage::delete('public/image/'.'5fa89ed72dd2f.png');
      // Storage::delete('public/image/'.'5fa89f9d8e11b.png');
      // Storage::delete('public/image/'.'5fac9bbfa6ec3.png');
      // Storage::delete('public/image/'.'5fac9bc90c93f.png');
      // Storage::delete('public/image/'.'5faca9000afbc.jpg');
      // Storage::delete('public/image/'.'5facab6f770ee.png');
      //
      // dd('del all');

      // $data = file_get_contents('storage/data.json');
      // $data = json_decode($data);
      // dd((int)end($data)->id + 1);

      // $data = [
      //   [
      //     'id' => 1,
      //     'nama' => 'Ngadimin',
      //     'datetime' => date('Y-m-d H:i:s'),
      //     'title' => 'Cheat membawa perubahan',
      //     'content' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
      //     'password' => password_hash("admin", PASSWORD_DEFAULT),
      //     'foto' => '',
      //     'click' => 100,
      //   ],
      //   [
      //     'id' => 2,
      //     'nama' => 'testing',
      //     'datetime' => date('Y-m-d H:i:s'),
      //     'title' => 'Membawa cinta',
      //     'content' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
      //     'password' => password_hash("testing", PASSWORD_DEFAULT),
      //     'foto' => '',
      //     'click' => 0,
      //   ],
      // ];
      // Storage::put('public/data.json', json_encode($data, JSON_PRETTY_PRINT));
      // dd($data);

      $data = file_get_contents('storage/data.json');
      $data = json_decode($data);
      // dd(end($data)->nama);
      return view('blog', ['name' => 'blog', 'data' => $data, 'id' => 1]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        return view('buat_artikel', ['name' => 'blog']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $request->foto->storeAs();
        $data = [];
        $id_terakhir = 1;
        $fileName = '';
        if($request->hasFile('image')){
          // //MENGAMBIL FILE IMAGE DARI FORM
          // $file = $request->file('image');
          // //MEMBUAT NAME FILE DARI GABUNGAN TIMESTAMP DAN UNIQID()
          // $fileName = Carbon::now()->timestamp . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
          // //UPLOAD ORIGINAN FILE (BELUM DIUBAH DIMENSINYA)
          // $file->storeAs('avatars', $request->user()->id)
          $file = $request->file('image');
          $fileName = uniqid(). '.' . $file->getClientOriginalExtension();
          $file->storeAs('public/image', $fileName);
        }
        if (Storage::exists('public/data.json')) {
            $data = json_decode(Storage::get('public/data.json'));
            $id_terakhir = (int)end($data)->id + 1;
        }


        $databaru = $request->only(['title', 'content']);
        $databaru['id'] = $id_terakhir;
        $databaru['nama'] = $request->input('name');
        $databaru['datetime'] = date('Y-m-d H:i:s');
        $databaru['password'] = password_hash($request->input('password'), PASSWORD_DEFAULT);
        $databaru['foto'] = $fileName;
        $databaru['click'] = 0;

        array_push($data, $databaru);
        Storage::put('public/data.json', json_encode($data, JSON_PRETTY_PRINT));
        return redirect('/blog')->with('status', 'Artikel berhasil dibuat');
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
              $data[$key]->click += 1;
              Storage::put('public/data.json', json_encode($data, JSON_PRETTY_PRINT));
              break;
          }
      }
        if( isset($item) ){
          return view('blog-details', ['name' => 'blog', 'd' => $item, 'data' => $data]);
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
        if( isset($item) ){
          return view('edit_artikel', ['name' => 'blog', 'd' => $item]);
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
      if($request->hasFile('image')){
        // //MENGAMBIL FILE IMAGE DARI FORM
        // $file = $request->file('image');
        // //MEMBUAT NAME FILE DARI GABUNGAN TIMESTAMP DAN UNIQID()
        // $fileName = Carbon::now()->timestamp . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        // //UPLOAD ORIGINAN FILE (BELUM DIUBAH DIMENSINYA)
        // $file->storeAs('avatars', $request->user()->id)
        $file = $request->file('image');
        $fileName = uniqid(). '.' . $file->getClientOriginalExtension();
        $file->storeAs('public/image', $fileName);

        foreach($data as $key => $value) {
            if ($id == $value->id) {
                if (password_verify($request->input('password'), $value->password)) {
                  $item = 'ada';
                  $data[$key]->nama = $request->input('name');
                  $data[$key]->datetime = date('Y-m-d H:i:s');
                  $data[$key]->title = $request->input('title');
                  $data[$key]->content = $request->input('content');
                  if ($value->foto != '') {
                    Storage::delete('public/image/'.$value->foto);
                  }
                  $data[$key]->foto = $fileName;
                  break;
                } else {
                  return redirect('blog/'.$id.'/edit')->with('gagal', 'Password salah, Artikel gagal diubah');
                }
            }
        }
      }else {
        foreach($data as $key => $value ) {
            if ($id == $value->id) {
                if(password_verify($request->input('password'), $value->password)) {
                  $item = 'ada';
                  $data[$key]->nama = $request->input('name');
                  $data[$key]->datetime = date('Y-m-d H:i:s');
                  $data[$key]->title = $request->input('title');
                  $data[$key]->content = $request->input('content');
                  break;
                } else {
                  return redirect('blog/'.$id.'/edit')->with('gagal', 'Password salah, Artikel gagal diubah');
                }
            }
        }
      }
      if(isset($item)){
        Storage::put('public/data.json', json_encode($data, JSON_PRETTY_PRINT));
        return redirect('blog/'.$id)->with('berhasil', 'Artikel berhasil diubah');
      }else {
        return redirect('blog/'.$id.'/edit')->with('gagal', 'Artikel gagal diubah');
      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
      $artikel = json_decode(Storage::get('public/data.json'));

        $data = collect($artikel)
            ->where('id', $id)
            ->first();

        // Checking user exist or not
        if ($data) {
            foreach ($artikel as $key => $art) {
                if ($art->id == $id) {
                  if (password_verify($request->input('password'), $value->password)) {
                    unset($artikel[$key]);
                    if ($art->foto != '') {
                      Storage::delete('public/image/'.$art->foto);
                    }
                  } else {
                    return redirect('blog/'.$id)->with('gagal', 'Password salah, Artikel gagal dihapus');
                  }
                }
            }

            Storage::put('public/data.json', json_encode(array_values($artikel)));
            return redirect('/')->with('status', 'Artikel berhasil dihapus');;
        }
        else {
            throw new \Exception('User not found');
        }
    }
}
