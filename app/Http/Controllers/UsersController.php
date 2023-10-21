<?php

namespace App\Http\Controllers;

class UsersController extends Controller {
    /**
     * Create a new controller instance.
     * 
     * @return void
     */

    public function __construct() {
    // Constructor code here
    return "Lumen Controller";
    }

    public function index() {
        return "You are getting this response from <b>Controller</b>";
    }
}
