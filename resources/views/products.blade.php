@extends('layouts.app')

@section('content')

<div class="card-header">{{ __('Dashboard') }}</div>

<div class="card-body">
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif



    <h4 class="mb-4">{{ __('You are logged in!') }}</h4>
    <table class="table table-bordered" id="products-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Title</th>
                <th>Description</th>
                <th>Price</th>
                <th>Discount (Percentage)</th>
                <th>Rating</th>
                <th>Stock</th>
                <th>Brand</th>
                <th>Category</th>
                <th>Thumbnail</th>
                <th>Images</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        var table = $('#products-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('products') }}",
            pageLength: 5,
            searchable: true,
            columns: [
                { data: 'id', name: 'id', orderBy:'desc' },
                { data: 'title', name: 'title'},
                { data: 'description', name: 'description'},
                { data: 'price', name: 'price'},
                { data: 'discountPercentage', name: 'discountPercentage'},
                { data: 'rating', name: 'rating'},
                { data: 'stock', name: 'stock'},
                { data: 'brand', name: 'brand'},
                { data: 'category', name: 'category' },
                {
                    data: 'thumbnail',
                    name: 'thumbnail',
                    render: function (data) {
                        var thumbnail = data;
                        thumbnailTags = '<img src="' + thumbnail + '" width="50" height="50">';
                        return thumbnailTags;
                    }
                },
                {
                    data: 'images',
                    name: 'images',
                    render: function (data) {
                        var images = data;
                        var imageTags = '';
                        images.forEach(function (image) {
                            imageTags += '<img src="' + image + '" width="50" height="50">';
                        });
                        return imageTags;
                    }
                }
            ],
            columnDefs: [
                {
                    targets: [9, 10],
                    orderable: false,
                    searchable: false,
                }
            ],
        });
    });
  </script>

@endsection
