<?php

namespace App\Models;

use App\Models\User;
use App\Models\ModuleContent;
use Illuminate\Database\Eloquent\Model;

class CourseModule extends Model
{
    //
    protected $fillable = ['user_id','code','title','description','contents_count'];

    protected static function booted(): void
    {
        static::creating(function (CourseModule $module) {
            
            if (empty($module->code)) {
                $next = (int) (self::max('id') ?? 0) + 1;
                $module->code = 'MOD-'.str_pad((string)$next, 4, '0', STR_PAD_LEFT);
                // option: garantir l’unicité robuste
                while (self::where('code', $module->code)->exists()) {
                    $next++;
                    $module->code = 'MOD-'.str_pad((string)$next, 4, '0', STR_PAD_LEFT);
                }
            }
        });
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function contents() {
        return $this->hasMany(ModuleContent::class);
    }
}
