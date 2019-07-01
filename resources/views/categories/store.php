
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta http-equiv="x-ua-compatible" content="ie=edge">

<!-- Bootstrap CSS-->
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-y3tfxAZXuh4HwSYylfB+J125MxIs6mR5FOHamPBG064zB+AFeWH94NdvaCBm8qnd" crossorigin="anonymous">

<!-- Custom CSS-->
<link rel="stylesheet" href="/public/style.css">
<table class="table">
    <thead class="thead-dark">
    <tr>
        <th scope="col">#</th>
        <th scope="col">Category Title</th>
        <th scope="col">Category ID</th>
        <th scope="col">Created At</th>
    </tr>
    </thead>
    <tbody>
    @foreach($category as $category)
    <tr>
        <th scope="row">Category</th>
        <td><a href="/category/{{category->category_name}}">{{category->name}}</a></td>
        <td>{{$category->category_id}}</td>
        <!-- <td>{{$task->created_at->toFormattedDateString()}}</td> -->
        <td>
            <div class="btn-group" role="group" aria-label="Basic example">
                <a href="{{ URL::to('category/' . $category->id . '/edit') }}">
                    <button type="button" class="btn btn-warning">Edit</button>
                </a>&nbsp;
                <form action="{{url('category', [$category->id])}}" method="POST">
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="submit" class="btn btn-danger" value="Delete"/>
                </form>
            </div>
        </td>
    </tr>
    @endforeach
    </tbody>
</table>
@endsection