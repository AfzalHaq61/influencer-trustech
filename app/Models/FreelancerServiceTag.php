<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FreelancerServiceTag extends Model
{
    use HasFactory;
    protected $table= "freelancer_service_tags";

    public function tagName(){
        return $this->belongsTo(FreelancerTag::class,'tag_id');
    }
}
