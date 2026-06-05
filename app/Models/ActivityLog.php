<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityLog extends Model
{
    protected $fillable = ['user_id', 'activity', 'model_name', 'model_id', 'ip_address'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function log($activity, $modelName = null, $modelId = null)
    {
        if (Auth::check()) {
            self::create([
                'user_id' => Auth::id(),
                'activity' => $activity,
                'model_name' => $modelName,
                'model_id' => $modelId,
                'ip_address' => request()->ip(),
            ]);
        }
    }
}
