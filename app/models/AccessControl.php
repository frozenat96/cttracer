<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class AccessControl extends Model
{
    /*
    *** Access Control Switch ***
    Description: The access control switch control what the
    users can or cannot access in the front-end and back-end
    level of the web application.
    false = no access controls
    true = has access controls
    */
    public $status = true;
}
