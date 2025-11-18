<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModuleContent extends Model
{
    //
    protected $fillable = ['course_module_id','title','type','path','url','size_bytes'];

    public function module() {
        return $this->belongsTo(CourseModule::class, 'course_module_id');
    }

    public function downloadUrl(): string {
        return $this->isFile() ? Storage::url($this->path) : ($this->url ?? '#');
    }

    public function isFile(): bool {
        return in_array($this->type, ['pdf','image','audio','file']);
    }
}
