<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApprovedJobOrder extends Model
{

    protected $table = 'approved_job_orders';
    
    protected $guarded ;

    public function job()
    {
    	return $this->belongsTo(Job::class);
    }

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

}
