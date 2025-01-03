<?php


namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Models\Movies;
use Carbon\Carbon;

class BulkController extends Controller
{
    public $module_title;

    public $module_name;

    public $module_path;

    public $module_icon;

    public $module_model;

    public function __construct()
    {
        // Page Title
        $this->module_title = 'Movies Bulk Upload';

        // module name
        $this->module_name = 'moviesbulk';

        // directory path of the module
        $this->module_path = 'moviesbulk';

        // module icon
        $this->module_icon = 'fa-solid fa-cubes';

        // module model name, path
        $this->module_model = "App\Models\Movies";
    }

    public function bulkIndex(){
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'List';

        $allMovies =  Movies::all();

        return  view("backend.movies.bulkindex",  compact('allMovies', 'module_title', 'module_name', 'module_path', 'module_icon', 'module_action', 'module_name_singular'));   
    }

    public function create(){
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Create';

        return view("backend.movies.bulkcreate", compact('module_title', 'module_name', 'module_path', 'module_icon', 'module_action', 'module_name_singular'));

    }

    public function bulkUpload(Request $request)
    {
        $request->validate([
            'movie_count' => 'required|integer|min:1',
            'genres' => 'required|string',
        ]);

        $movieCount = $request->input('movie_count');
        $genres = $request->input('genres');

        // Explode genres by commas and trim spaces
        $genreArray = array_map('trim', explode(',', $genres));

        // Calculate how many movies per genre
        $moviesPerGenre = intval($movieCount / count($genreArray));
        $remainingMovies = $movieCount % count($genreArray);

        $apiKey = env('OMDB_API_KEY');
        $moviesAdded = [];

        foreach ($genreArray as $genre) {
            for ($i = 0; $i < $moviesPerGenre; $i++) {
                $response = Http::get("http://www.omdbapi.com/", [
                    's' => $genre, // Search by genre
                    'apikey' => $apiKey,
                    'page' => rand(1, 5), // Random page for variety
                ]);

                if ($response->successful() && $response->json('Response') === 'True') {
                    $searchResults = $response->json('Search');
                    if (is_array($searchResults) && count($searchResults) > 0) {
                        // Pick a random movie from search results
                        $randomMovie = $searchResults[array_rand($searchResults)];

                        // Get full movie details by IMDb ID
                        $movieDetails = Http::get("http://www.omdbapi.com/", [
                            'i' => $randomMovie['imdbID'],
                            'apikey' => $apiKey,
                        ])->json();

                        if ($movieDetails && $movieDetails['Response'] === 'True') {
                            // Save the movie details in the database
                            Movies::create([
                                'title' => $movieDetails['Title'],
                                'year' => isset($movieDetails['Year']) ? explode('–', $movieDetails['Year'])[0] : null,
                                'rated' => $movieDetails['Rated'] ?? null,
                                'released' => date('Y-m-d', strtotime($movieDetails['Released'] ?? '')),
                                'runtime' => $movieDetails['Runtime'] ?? null,
                                'genre' => $movieDetails['Genre'] ?? null,
                                'director' => $movieDetails['Director'] ?? null,
                                'writer' => $movieDetails['Writer'] ?? null,
                                'actors' => $movieDetails['Actors'] ?? null,
                                'plot' => $movieDetails['Plot'] ?? null,
                                'language' => $movieDetails['Language'] ?? null,
                                'country' => $movieDetails['Country'] ?? null,
                                'awards' => $movieDetails['Awards'] ?? null,
                                'poster' => $movieDetails['Poster'] ?? null,
                                'ratings' => json_encode($movieDetails['Ratings'] ?? []),
                                'metascore' => $movieDetails['Metascore'] ?? null,
                                'imdb_rating' => $movieDetails['imdbRating'] ?? null,
                                'imdb_votes' => str_replace(',', '', $movieDetails['imdbVotes'] ?? ''),
                                'imdb_id' => $movieDetails['imdbID'] ?? null,
                                'type' => $movieDetails['Type'] ?? null,
                                'dvd' => $movieDetails['DVD'] ?? null,
                                'box_office' => $movieDetails['BoxOffice'] ?? null,
                                'production' => $movieDetails['Production'] ?? null,
                                'website' => $movieDetails['Website'] ?? null,
                            ]);

                            $moviesAdded[] = $movieDetails['Title'];
                        }
                    }
                }
            }
        }

        // If remaining movies are present, add them to the first genre
        if ($remainingMovies > 0 && isset($genreArray[0])) {
            for ($i = 0; $i < $remainingMovies; $i++) {
                $response = Http::get("http://www.omdbapi.com/", [
                    's' => $genreArray[0], // First genre
                    'apikey' => $apiKey,
                    'page' => rand(1, 5),
                ]);

                if ($response->successful() && $response->json('Response') === 'True') {
                    $searchResults = $response->json('Search');
                    if (is_array($searchResults) && count($searchResults) > 0) {
                        $randomMovie = $searchResults[array_rand($searchResults)];

                        $movieDetails = Http::get("http://www.omdbapi.com/", [
                            'i' => $randomMovie['imdbID'],
                            'apikey' => $apiKey,
                        ])->json();

                        if ($movieDetails && $movieDetails['Response'] === 'True') {
                            Movie::create([
                                // Same as above fields
                            ]);
                            $moviesAdded[] = $movieDetails['Title'];
                        }
                    }
                }
            }
        }

        return redirect()->back()->with('success', 'Successfully added ' . count($moviesAdded) . ' movies!');
    }




}
