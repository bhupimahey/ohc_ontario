<?php
namespace App\Models;

use CodeIgniter\Model;

class LoginModel extends Model{

    protected $table = 'users';
    protected $allowedFields = ['email_id','account_key','entry_time','account_type','account_id'];
}