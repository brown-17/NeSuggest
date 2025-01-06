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
        // Validate the input fields
        $request->validate([
            'movie_count' => 'required|integer|min:1',
            'genres' => 'required|string',
            'year_range' => 'nullable|string|regex:/^\d{4}-\d{4}$/', // Optional year range in YYYY-YYYY format
        ]);

        $movieCount = $request->input('movie_count');
        $genres = explode(',', $request->input('genres'));
        $genres = array_map('trim', $genres); // Trim extra spaces from genres
        $yearRange = $request->input('year_range');

        // Initialize year range variables if provided
        $fromYear = null;
        $toYear = null;
        if ($yearRange) {
            [$fromYear, $toYear] = explode('-', $yearRange);
            $fromYear = (int) $fromYear;
            $toYear = (int) $toYear;
        }

        $apiKey = env('OMDB_API_KEY');
        $movies = [];  // To hold all the fetched movies

        // Fetch movies based on year range or without it
        if ($fromYear && $toYear) {
            $randomYear = rand($fromYear,$toYear);
            // Year range is provided, so we fetch movies from that period
            while (count($movies) < $movieCount) {
                foreach ($genres as $genre) {
                    // Fetch movies of the specified genre within the year range
                    $searchResponse = Http::get('http://www.omdbapi.com/', [
                        'apikey' => $apiKey,
                        'type' => 'movie',
                        'y' => $randomYear,
                        'page' => 1,
                    ]);

                    if ($searchResponse->successful() && $searchResponse->json('Response') === 'True') {
                        $fetchedMovies = $searchResponse->json('Search');

                        // Add the fetched movies to the main array until we hit the target count
                        foreach ($fetchedMovies as $movie) {
                            if (count($movies) < $movieCount) {
                                $movies[] = $movie;
                            } else {
                                break;
                            }
                        }
                    }

                    // Stop if we already have enough movies
                    if (count($movies) >= $movieCount) {
                        break;
                    }
                }
            }
        } else {
            // No year range, just divide the movie count evenly across the genres
            $moviesPerGenre = intdiv($movieCount, count($genres));
            $remainingMovies = $movieCount % count($genres);

            foreach ($genres as $genre) {
                // Determine how many movies to fetch for each genre
                $count = $moviesPerGenre + ($remainingMovies > 0 ? 1 : 0);
                $remainingMovies--;

                // Fetch movies for the current genre
                while (count($movies) < $movieCount && $count > 0) {
                    $searchResponse = Http::get('http://www.omdbapi.com/', [
                        'apikey' => $apiKey,
                        'type' => 'movie',
                        'page' => 1,
                    ]);

                    if ($searchResponse->successful() && $searchResponse->json('Response') === 'True') {
                        $fetchedMovies = $searchResponse->json('Search');

                        foreach ($fetchedMovies as $movie) {
                            if (count($movies) < $movieCount) {
                                $movies[] = $movie;
                            } else {
                                break;
                            }
                        }
                    }

                    // Decrease count for next round
                    $count--;
                }

                // Stop if we already have enough movies
                if (count($movies) >= $movieCount) {
                    break;
                }
            }
        }

        // Now fetch the detailed information for each movie
        foreach ($movies as $movie) {
            $movieDetailsResponse = Http::get('http://www.omdbapi.com/', [
                'apikey' => $apiKey,
                'i' => $movie['imdbID'],
            ]);

            if ($movieDetailsResponse->successful() && $movieDetailsResponse->json('Response') === 'True') {
                $details = $movieDetailsResponse->json();

                // Insert the movie into the database
                Movies::create([
                    'title' => $details['Title'] ?? null,
                    'year' => $details['Year'] ?? null,
                    'rated' => $details['Rated'] ?? null,
                    'released' => isset($details['Released']) ? date('Y-m-d', strtotime($details['Released'])) : null,
                    'runtime' => $details['Runtime'] ?? null,
                    'genre' => $details['Genre'] ?? null,
                    'director' => $details['Director'] ?? null,
                    'writer' => $details['Writer'] ?? null,
                    'actors' => $details['Actors'] ?? null,
                    'plot' => $details['Plot'] ?? null,
                    'language' => $details['Language'] ?? null,
                    'country' => $details['Country'] ?? null,
                    'awards' => $details['Awards'] ?? null,
                    'poster' => $details['Poster'] ?? null,
                    'ratings' => json_encode($details['Ratings'] ?? []),
                    'metascore' => $details['Metascore'] ?? null,
                    'imdb_rating' => $details['imdbRating'] ?? null,
                    'imdb_votes' => str_replace(',', '', $details['imdbVotes'] ?? null),
                    'imdb_id' => $details['imdbID'] ?? null,
                    'type' => $details['Type'] ?? null,
                    'dvd' => isset($details['DVD']) ? date('Y-m-d', strtotime($details['DVD'])) : null,
                    'box_office' => $details['BoxOffice'] ?? null,
                    'production' => $details['Production'] ?? null,
                    'website' => $details['Website'] ?? null,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Movies added successfully!');
    }
}
