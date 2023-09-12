<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FreelancerOrderConversation extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'freelancer_order_conversations';
}
