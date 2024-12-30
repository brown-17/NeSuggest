<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Models\Movies;
use Carbon\Carbon;


class MoviesController extends Controller
{
    public $module_title;

    public $module_name;

    public $module_path;

    public $module_icon;

    public $module_model;

    public function __construct()
    {
        // Page Title
        $this->module_title = 'Movies';

        // module name
        $this->module_name = 'movies';

        // directory path of the module
        $this->module_path = 'movies';

        // module icon
        $this->module_icon = 'fa-solid fa-video';

        // module model name, path
        $this->module_model = "App\Models\Movies";
    }

    public function index()
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'List';

        $allMovies =  Movies::all();
        
        return  view("backend.movies.movies",  compact('allMovies', 'module_title', 'module_name', 'module_path', 'module_icon', 'module_action', 'module_name_singular'));
    }

    public function add(){
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Add';

        return view("backend.movies.add", compact('module_title', 'module_name', 'module_path', 'module_icon', 'module_action', 'module_name_singular'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'movie_name' => 'required|string',
        ]);

        $movieName = $request->input('movie_name');
        $apiKey = env('OMDB_API_KEY');

        $response = Http::get("http://www.omdbapi.com/", [
            't' => $movieName,
            'apikey' => $apiKey,
        ]);

        if ($response->successful() && $response->json('Response') === 'True') {
            $movieData = $response->json();
            // dd($movieData);

            $releasedDate = Carbon::createFromFormat('d M Y', $movieData['Released'])->format('Y-m-d');
            $imdbVotes = preg_replace('/[^0-9]/', '', $movieData['imdbVotes']);

            $movie = [
                'title' => $movieData['Title'],
                'year' => $movieData['Year'],
                'rated' => $movieData['Rated'],
                'released' => $releasedDate,
                'runtime' => $movieData['Runtime'],
                'genre' => $movieData['Genre'],
                'director' => $movieData['Director'],
                'writer' => $movieData['Writer'],
                'actors' => $movieData['Actors'],
                'plot' => $movieData['Plot'],
                'language' => $movieData['Language'],
                'country' => $movieData['Country'],
                'awards' => $movieData['Awards'],
                'poster' => $movieData['Poster'],
                'ratings' => json_encode($movieData['Ratings']),
                'metascore' => $movieData['Metascore'],
                'imdb_rating' => $movieData['imdbRating'],
                'imdb_votes' => $imdbVotes,
                'imdb_id' => $movieData['imdbID'],
                'type' => $movieData['Type'],
                'dvd' => $movieData['DVD'],
                'box_office' => $movieData['BoxOffice'],
                'production' => $movieData['Production'],
                'website' => $movieData['Website'],
            ];

            Movies::create($movie);

            return redirect()->route('backend.movies.index')->with('success', 'Movie data successfully added to the database.');
        }

            return redirect()->route('backend.movies.add')->with('error', 'Movie not found or an error occurred. Please try again.');
    }

    public function edit(Request $request){
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Edit';

        $movie = Movies::where('id',$request->id)->first();

        return  view("backend.movies.edit",  compact('movie', 'module_title', 'module_name', 'module_path', 'module_icon', 'module_action', 'module_name_singular'));
    }

    public function update(Request $request, $id){
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'year' => 'required',
            'rated' => 'nullable|string|max:255',
            'released' => 'nullable|date',
            'runtime' => 'nullable|string|max:255',
            'genre' => 'nullable|string|max:255',
            'director' => 'nullable|string|max:255',
            'writer' => 'nullable|string',
            'actors' => 'nullable|string',
            'plot' => 'nullable|string',
            'language' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'awards' => 'nullable|string',
            'metascore' => 'nullable|integer|min:0|max:100',
            'imdb_votes' => 'nullable|integer|min:0',
            'type' => 'nullable|string|max:50',
            'dvd' => 'nullable|string|max:50',
            'box_office' => 'nullable|string|max:50',
            'production' => 'nullable|string|max:255',
            'website' => 'nullable|string',
        ]);

        $movie = Movies::findOrFail($id);

        $movie->update($validatedData);

        return redirect()->route('backend.movies.index')->with('success', 'Movie updated successfully.');
    }

    public function destroy($id){

        $movie = Movies::findOrFail($id);

        $movie->delete();
    
        return redirect()->route('backend.movies.index')->with('success', 'Movie deleted successfully.');
    }

}



