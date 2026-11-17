<?php
// AdminController (Alias / Extension to UserController)
require_once __DIR__ . '/UserController.php';

use App\Controllers\UserController;

class AdminController extends UserController
{
}
if (!class_exists('App\Controllers\AdminController', false)) {
    class_alias('AdminController', 'App\Controllers\AdminController');
}
