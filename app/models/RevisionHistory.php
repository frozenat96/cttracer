<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class RevisionHistory extends Model
{
    protected $table = "revision_history";
    public $primaryKey = "revID";
    public $timestamps = false;
    public $incrementing = false;
    public $status = true;
}
