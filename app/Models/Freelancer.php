<?php

namespace App\Models;
use Cache;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Freelancer extends Authenticatable {
    use HasFactory;

    protected $guarded;
    protected $table ='freelancers';

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'address'           => 'object',
        'kyc_data'          => 'object',
        'skills'            => 'object',
        'languages'         => 'array',
        'ver_code_send_at'  => 'datetime',  
    ];

    public function categories() {
        return $this->belongsToMany(Category::class, 'freelancer_categories');
    }

    public function message() {
        return $this->hasMany(Conversation::class, 'freelancer_id')->latest();
    }

    public function Hiring() {
        return $this->hasMany(Hiring::class, 'freelancer_id');
    }

    public function Order() {
        return $this->hasMany(Order::class, 'freelancer_id');
    }

    public function education() {
        return $this->hasMany(FreelancerEducation::class, 'freelancer_id')->latest();
    }

    public function qualification() {
        return $this->hasMany(FreelancerQualification::class, 'freelancer_id')->latest();
    }

    public function languages() {
        return $this->hasMany(FreelancerLanguage::class, 'freelancer_id')->latest();
    }

    public function skills() {
        return $this->hasMany(FreelancerSkill::class, 'freelancer_id');
    }

    public function services() {
        return $this->hasMany(FreelancerService::class, 'freelancer_id')->where('status', 1);
    }

    public function reviews() {
        return $this->hasMany(Review::class, 'freelancer_id');
    }

    public function orderReviews() {
        return $this->hasMany(Review::class, 'freelancer_id')->where('order_id', 0);
    }

    public function socialLink() {
        return $this->hasMany(FreelancerSocialLink::class, 'freelancer_id');
    }

    public function isOnline() {
        return Cache::has('last_seen' . $this->id);
    }

    public function fullname(): Attribute {
        return new Attribute(
            get:fn() => $this->firstname . ' ' . $this->lastname,
        );
    }
    // SCOPES
    public function scopeActive() {
        return $this->where('status', 1);
    }
    public function scopeBanned() {
        return $this->where('status', 0);
    }

    public function scopeEmailUnverified() {
        return $this->where('ev', 0);
    }

    public function scopeMobileUnverified() {
        return $this->where('sv', 0);
    }

    public function scopeKycUnverified() {
        return $this->where('kv', 0);
    }

    public function scopeKycPending() {
        return $this->where('kv', 2);
    }

    public function scopeEmailVerified() {
        return $this->where('ev', 1);
    }

    public function scopeMobileVerified() {
        return $this->where('sv', 1);
    }

    public function scopeWithBalance() {
        return $this->where('balance', '>', 0);
    }
}
