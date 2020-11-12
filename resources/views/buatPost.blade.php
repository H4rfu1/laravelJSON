<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
</head>
<body>
<div class="container mt-4">

    <form method="post" action="{{route('blog.store')}}">
    @csrf
    <div class="form-group">
        <label for="judul">Judul</label>
        <input type="text" class="form-control" id="judul" name="judul">
    </div>
    <div class="form-group">
        <label for="penulis">penulis</label>
        <input type="text" class="form-control" id="penulis" name="penulis">
    </div>
    <div class="form-group">
        <label for="isi">Isi</label>
        <input type="text" class="form-control" id="isi" name="isi">
    </div>
    <button type="submit" class="btn btn-primary">Buat</button>
    </form>
</div>
</body>
</html>