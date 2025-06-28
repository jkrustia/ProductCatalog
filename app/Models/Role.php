<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use App\Models\User; // Import the User model
use Spatie\Permission\Models\Role as SpatieRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Role extends SpatieRole
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * Get the users for the role.
     */
    
}