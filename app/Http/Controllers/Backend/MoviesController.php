<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


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
        
        return view("backend.movies.movies",  compact('module_title', 'module_name', 'module_path', 'module_icon', 'module_action', 'module_name_singular'));
    }

    public function show(){
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Show';

        return view("backend.movies.add", compact('module_title', 'module_name', 'module_path', 'module_icon', 'module_action', 'module_name_singular'));
    }

    
}



