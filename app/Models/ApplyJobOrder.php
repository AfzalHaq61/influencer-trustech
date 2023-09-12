<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplyJobOrder extends Model
{

    protected $table = 'apply_job_orders';
    
    protected $guarded ;

    public function appliedUser()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function appliedInfluencer()
    {
        return $this->belongsTo(Influencer::class, 'influencer_id');
    }

    public function appliedFreelancer()
    {
        return $this->belongsTo(Freelancer::class, 'freelancer_id');
    }

}
