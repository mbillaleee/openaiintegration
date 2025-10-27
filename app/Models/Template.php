<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    protected $guarded = [];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function inputFields()
    {
        return $this->hasMany(TemplateInputField::class, 'template_id');
    }
}
