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



}
