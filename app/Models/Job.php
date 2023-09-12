<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
  
    use Searchable, GlobalStatus;

    protected $table = 'jobs';

    protected $casts = [
        'skill'       => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function influencer()
    {
        return $this->belongsTo(Influencer::class);
    }

    public function freelancer()
    {
        return $this->belongsTo(Freelancer::class);
    }

    public function jobCategory()
    {
    	return $this->belongsTo(JobCategory::class);
    }

    public function JobSubCategory()
    {
    	return $this->belongsTo(JobSubCategory::class);
    }

    public function jobApplications()
    {
    	return $this->hasMany(ApplyJobOrder::class);
    }

    public function jobSkill()
    {
    	return $this->belongsTo(JobSkill::class, 'skill');
    }
}
