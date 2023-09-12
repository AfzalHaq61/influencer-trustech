<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;

class JobSkill extends Model
{
    use Searchable, GlobalStatus;

    protected $table = 'job_skills';

}
