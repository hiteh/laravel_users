<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{

	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description',
    ];

    /**
     *
     * Relationship with users
     *
     */
    public function users() {
        return $this->belongToMany(User::class);
    }
}