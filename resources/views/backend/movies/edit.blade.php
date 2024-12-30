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
                <form action="{{route('backend.movies.update', $movie->id)}}" method="POST">
                    @csrf
                    <!-- Title -->
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" id="title" name="title" class="form-control" value="{{ old('title', $movie->title) }}" required>
                    </div>
                    
                    <!-- Year -->
                    <div class="mb-3">
                        <label for="year" class="form-label">Year</label>
                        <input type="number" id="year" name="year" class="form-control" value="{{ old('year', $movie->year) }}">
                    </div>
                    
                    <!-- Rated -->
                    <div class="mb-3">
                        <label for="rated" class="form-label">Rated</label>
                        <input type="text" id="rated" name="rated" class="form-control" value="{{ old('rated', $movie->rated) }}">
                    </div>
                    
                    <!-- Released -->
                    <div class="mb-3">
                        <label for="released" class="form-label">Released Date</label>
                        <input type="date" id="released" name="released" class="form-control" value="{{ old('released', $movie->released) }}">
                    </div>
                    
                    <!-- Runtime -->
                    <div class="mb-3">
                        <label for="runtime" class="form-label">Runtime</label>
                        <input type="text" id="runtime" name="runtime" class="form-control" value="{{ old('runtime', $movie->runtime) }}">
                    </div>
                    
                    <!-- Genre -->
                    <div class="mb-3">
                        <label for="genre" class="form-label">Genre</label>
                        <input type="text" id="genre" name="genre" class="form-control" value="{{ old('genre', $movie->genre) }}">
                    </div>
                    
                    <!-- Director -->
                    <div class="mb-3">
                        <label for="director" class="form-label">Director</label>
                        <input type="text" id="director" name="director" class="form-control" value="{{ old('director', $movie->director) }}">
                    </div>
                    
                    <!-- Writer -->
                    <div class="mb-3">
                        <label for="writer" class="form-label">Writer</label>
                        <textarea id="writer" name="writer" class="form-control" rows="3">{{ old('writer', $movie->writer) }}</textarea>
                    </div>
                    
                    <!-- Actors -->
                    <div class="mb-3">
                        <label for="actors" class="form-label">Actors</label>
                        <textarea id="actors" name="actors" class="form-control" rows="3">{{ old('actors', $movie->actors) }}</textarea>
                    </div>
                    
                    <!-- Plot -->
                    <div class="mb-3">
                        <label for="plot" class="form-label">Plot</label>
                        <textarea id="plot" name="plot" class="form-control" rows="4">{{ old('plot', $movie->plot) }}</textarea>
                    </div>
                    
                    <!-- Language -->
                    <div class="mb-3">
                        <label for="language" class="form-label">Language</label>
                        <input type="text" id="language" name="language" class="form-control" value="{{ old('language', $movie->language) }}">
                    </div>
                    
                    <!-- Country -->
                    <div class="mb-3">
                        <label for="country" class="form-label">Country</label>
                        <input type="text" id="country" name="country" class="form-control" value="{{ old('country', $movie->country) }}">
                    </div>
                    
                    <!-- Awards -->
                    <div class="mb-3">
                        <label for="awards" class="form-label">Awards</label>
                        <textarea id="awards" name="awards" class="form-control" rows="2">{{ old('awards', $movie->awards) }}</textarea>
                    </div>

                    
                    <!-- Metascore -->
                    <div class="mb-3">
                        <label for="metascore" class="form-label">Metascore</label>
                        <input type="number" id="metascore" name="metascore" class="form-control" value="{{ old('metascore', $movie->metascore) }}">
                    </div>
                    
                    <!-- IMDb Votes -->
                    <div class="mb-3">
                        <label for="imdb_votes" class="form-label">IMDb Votes</label>
                        <input type="number" id="imdb_votes" name="imdb_votes" class="form-control" value="{{ old('imdb_votes', $movie->imdb_votes) }}">
                    </div>
                    
                    <!-- IMDb Rating -->
                    <div class="mb-3">
                        <label for="imdb_rating" class="form-label">IMDb Rating</label>
                        <input type="text" id="imdb_rating" name="imdb_rating" class="form-control" value="{{ old('imdb_rating', $movie->imdb_rating) }}">
                    </div>
                    
                    <!-- Type -->
                    <div class="mb-3">
                        <label for="type" class="form-label">Type</label>
                        <input type="text" id="type" name="type" class="form-control" value="{{ old('type', $movie->type) }}">
                    </div>
                    
                    <!-- DVD -->
                    <div class="mb-3">
                        <label for="dvd" class="form-label">DVD Release</label>
                        <input type="text" id="dvd" name="dvd" class="form-control" value="{{ old('dvd', $movie->dvd) }}">
                    </div>
                    
                    <!-- Box Office -->
                    <div class="mb-3">
                        <label for="box_office" class="form-label">Box Office</label>
                        <input type="text" id="box_office" name="box_office" class="form-control" value="{{ old('box_office', $movie->box_office) }}">
                    </div>
                    
                    <!-- Production -->
                    <div class="mb-3">
                        <label for="production" class="form-label">Production</label>
                        <input type="text" id="production" name="production" class="form-control" value="{{ old('production', $movie->production) }}">
                    </div>
                    
                    <!-- Website -->
                    <div class="mb-3">
                        <label for="website" class="form-label">Website</label>
                        <input type="text" id="website" name="website" class="form-control" value="{{ old('website', $movie->website) }}">
                    </div>
                    
                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary">Update Movie</button>
                </form>
            </div>
        </div>
        <div class="card-footer">
        </div>
    </div>
@endsection
