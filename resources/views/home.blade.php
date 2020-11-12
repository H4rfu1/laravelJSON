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
    <a class="btn btn-success" href="{{url('blog/create')}}">Tambah Artikel</a>
    <!-- flash message -->
    @if (session('status'))
    <div class="alert alert-success alert-dismissible " role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
        {{ session('status') }}
    </div>
    @endif

    @foreach($data as $d)
    <div class="card m-2" style="width: 18rem;">
        <div class="card-body">
            <h5 class="card-title">{{$d->judul}}</h5>
            <p class="card-text">{{$d->penulis}}</p>
        </div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item">{{$d->isi}}</li>
        </ul>
        <div class="card-body">
            
            <form action="{{route('blog.destroy', $d->id)}}" method="post" class="d-inline">
                @csrf
                @method('delete')
                <button  type="submit" class="btn btn-danger">Hapus</button>
            </form>
            <a href="{{url('blog/'.$d->id.'/edit')}}" class="btn btn-warning d-inline">Edit</a>
        </div>
    </div>
    @endforeach
</div>

</body>
</html>