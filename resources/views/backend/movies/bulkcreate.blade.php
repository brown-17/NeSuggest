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
                <form action="{{route('backend.moviesbulk.store')}}" method="POST">
                    @csrf
                    <!-- Number of Movies -->
                    <div class="mb-3">
                        <label for="movieCount" class="form-label">Number of Movies to Add</label>
                        <input 
                            type="number" 
                            class="form-control" 
                            id="movieCount" 
                            name="movie_count" 
                            placeholder="Enter the number of movies" 
                            required
                        >
                    </div>
                    
                    <!-- Genre -->
                    <div class="mb-3">
                        <label for="genre" class="form-label">Genres (Comma Separated)</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="genre" 
                            name="genres" 
                            placeholder="e.g., Action, Comedy, Drama" 
                            required
                        >
                    </div>

                    <!-- Year Range -->
                    <div class="mb-3">
                        <label for="yearRange" class="form-label">From Which Year (Optional)</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="yearRange" 
                            name="year_range" 
                            placeholder="e.g., 2010-2015"
                        >
                    </div>
                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary">Add Movies</button>
                </form>
            </div>
            </div>
        </div>
        <div class="card-footer">
        </div>
    </div>
@endsection
