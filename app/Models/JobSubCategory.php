<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;

class JobSubCategory extends Model
{
    use Searchable, GlobalStatus;

    protected $table = 'job_sub_categories';


    public function jobCategory()
    {
        return $this->belongsTo(JobCategory::class);
    }
}
