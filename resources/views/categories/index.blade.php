@extends('layouts.app')
@section('content')

    <div class="container">
        <a href="{!! url('categories/create') !!}" class="btn btn-primary" style="margin-bottom: 10px;">New Category</a>
        <table class="table">
            <thead>
            <tr>
                <th>Name</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
            </thead>
            <tbody>
            @foreach($categories as $category)
                <tr>
                    <td>{!! implode(' - ',$category->name) !!}</td>
                    <td>
                        <a href="{!! url('categories/'.$category->id.'/edit') !!}" class="btn btn-primary">Edit</a>
                    </td>
                    <td>
                        <form action="{!! url('categories/'.$category->id) !!}" method="post">
                            @csrf
                            {!! method_field('delete') !!}
                            <input type="submit" value="Delete" class="btn btn-danger">
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection