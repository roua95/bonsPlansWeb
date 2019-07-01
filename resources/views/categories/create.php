<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-y3tfxAZXuh4HwSYylfB+J125MxIs6mR5FOHamPBG064zB+AFeWH94NdvaCBm8qnd" crossorigin="anonymous">
<link rel="stylesheet" href="/public/style.css">

<h1>Add New Category</h1>
<hr>@extends('layout.layout') @section('content') @if (Session::has('message'))
{{ Session::get('message') }}
@
<form action="/categories" method="post">
    {{ csrf_field() }}
    <div class="form-group">
        <label for="title">Category id</label>
        <input type="text" class="form-control" id="categoryId"  name="id">
    </div>
    <div class="form-group">
        <label for="description">Category Name</label>
        <input type="text" class="form-control" id="categoryName" name="name">
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>
</form>
