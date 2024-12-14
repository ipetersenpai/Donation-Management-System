@php
    use Carbon\Carbon;
@endphp

@extends('layouts.app')

@section('content')
    @include('layouts.navbar')
    <div class="container-fluid">
        <div class="row" style="height: 100%; overflow-y: hidden !important;">
            @include('layouts.sidebar')
            <!-- Main Content -->
            <div class="relative col-md-9 col-lg-10 content" style="height: 100%; display: flex; flex-direction: column; ">

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="header" style="margin-top: {{ session('success') || session('error') ? '0' : '20px' }};">
                    <h3>Donation Categories</h3>
                </div>

                <div class="d-flex w-full flex-md-row flex-column justify-content-between">
                    <!-- Search Input -->
                    <form action="{{ route('categories.search') }}" method="GET"
                        class="d-flex flex-sm-col flex-md-row gap-2 justify-content-start my-3">
                        <div class="form-group">
                            <input type="text" style="width: 250px" name="search" class="form-control"
                                placeholder="Search...">
                        </div>
                        <button type="submit" class="btn btn-primary">Search</button>
                    </form>
                    <button type="button" class="btn btn-primary my-md-3 my-2" data-toggle="modal"
                        data-target="#createCategoryModal">
                        Create Category
                    </button>
                </div>

                <!-- Responsive DataTable -->
                <div class="table-responsive" style="overflow-y: auto; height: 65vh; border: 1px solid #f2f2f2;">
                    <table id="categoryTable" class="table table-striped table-bordered table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th style="min-width: 300px">Category Name</th>
                                <th style="min-width: 350px">Description</th>
                                <th style="min-width: 150px">Created At</th>
                                <th style="min-width: 150px">Updated At</th>
                                <th style="min-width: 150px">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $category)
                                <tr>
                                    <td>{{ $category->category_name }}</td>
                                    <td>{{ $category->description }}</td>
                                    <td>{{ Carbon::parse($category->created_at)->format('m-d-Y') }}</td>
                                    <td>{{ Carbon::parse($category->updated_at)->format('m-d-Y') }}</td>
                                    <td>
                                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                            data-target="#updateCategoryModal-{{ $category->id }}">Update</button>
                                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST"
                                            style="display:inline-block;"
                                            onsubmit="return confirm('Are you sure you want to delete this category?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Update Category Modal -->
                                <div class="modal fade" id="updateCategoryModal-{{ $category->id }}" tabindex="-1"
                                    role="dialog" aria-labelledby="updateCategoryModalLabel-{{ $category->id }}"
                                    aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <form id="updateCategoryForm-{{ $category->id }}" method="POST"
                                                action="{{ route('categories.update', $category->id) }}">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title"
                                                        id="updateCategoryModalLabel-{{ $category->id }}">
                                                        Update Category: {{ $category->category_name }}</h5>
                                                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="category_name-{{ $category->id }}">Category
                                                            Name</label>
                                                        <input type="text" class="form-control"
                                                            id="category_name-{{ $category->id }}" name="category_name"
                                                            value="{{ $category->category_name }}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="description-{{ $category->id }}">Description</label>
                                                        <textarea class="form-control" id="description-{{ $category->id }}" name="description" rows="2">{{ $category->description }}</textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="about-{{ $category->id }}">About</label>
                                                        <textarea class="form-control" id="about-{{ $category->id }}" name="about" rows="7">{{ $category->about }}</textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="link-{{ $category->id }}">Link</label>
                                                        <input type="text" class="form-control"
                                                            id="link-{{ $category->id }}" name="link"
                                                            value="{{ $category->link }}">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Create Category Modal -->
                <div class="modal fade" id="createCategoryModal" tabindex="-1" role="dialog"
                    aria-labelledby="createCategoryModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <form id="createCategoryForm" method="POST" action="{{ route('categories.store') }}">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title" id="createCategoryModalLabel">Create Category</h5>
                                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="category_name">Category Name</label>
                                        <input type="text" class="form-control" id="category_name"
                                            name="category_name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea class="form-control" id="description" name="description" rows="2"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="about">About</label>
                                        <textarea class="form-control" id="about" name="about" rows="7"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="link">Link</label>
                                        <input type="text" class="form-control" id="link" name="link">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>


                <!-- Pagination -->
                <div class="d-flex justify-content-end mt-3">
                    {{ $categories->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>

    <style>
        .header {
            padding: 7px;
            color: white;
            border-radius: 5px;
            padding-left: 10px;
            background: linear-gradient(to bottom,
                    rgba(127, 6, 14, 0.944),
                    rgba(160, 8, 36, 0.911));

        }
    </style>

    <script>
        $(document).ready(function() {
            var table = $('#categoryTable').DataTable({
                "paging": true,
                "info": true
            });

            $('#searchInput').on('keyup', function() {
                table.search(this.value).draw();
            });
        });
    </script>
@endsection
