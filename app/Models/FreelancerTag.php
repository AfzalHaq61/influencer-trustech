<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FreelancerTag extends Model
{
    use HasFactory;
    protected $table= "freelancer_tags";

    public function serviceTag(){
        return $this->hasMany(FreelancerServiceTag::class);
    }
}
