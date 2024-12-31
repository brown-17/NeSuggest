@extends("backend.layouts.app")

@section("title")
    {{ __($module_action) }} {{ __($module_title) }}
@endsection

@section("breadcrumbs")
    <x-backend.breadcrumbs>
        <x-backend.breadcrumb-item type="active" icon="{{ $module_icon }}">
            {{ __($module_title) }}
        </x-backend.breadcrumb-item>
    </x-backend.breadcrumbs>
@endsection

@section("content")
    <div class="card">
        <div class="card-body">
            <x-backend.section-header
                :module_name="$module_name"
                :module_title="$module_title"
                :module_icon="$module_icon"
                :module_action="$module_action"
            />
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="container">
                <!-- Table for Movies -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Movies List</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Movie Name</th>
                                    <th>Added On</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($allMovies as $movie)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$movie->title}}</td>
                                        <td>{{\Carbon\Carbon::parse($movie->created_at)->format('d M Y')}}</td>
                                        <td>
                                            <!-- Edit Button -->
                                            <a href="{{route('backend.movies.show',$movie->id)}}" class="btn btn-warning btn-sm">
                                                <i class="fa fa-pencil"></i> <!-- Edit Icon -->
                                            </a>
            
                                            <!-- Delete Button -->
                                            <form action="{{ route('backend.movies.destroy', $movie->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this movie?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fa fa-trash"></i> <!-- Delete Icon -->
                                                </button>
                                            </form>                                            
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
        </div>
    </div>
@endsection
