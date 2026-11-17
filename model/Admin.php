<?php
// Model Admin (Alias to User Model)
require_once __DIR__ . '/User.php';

use App\Models\User;

class Admin extends User
{
}
if (!class_exists('App\Models\Admin', false)) {
    class_alias('Admin', 'App\Models\Admin');
}
