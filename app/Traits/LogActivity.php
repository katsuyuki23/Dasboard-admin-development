<?php

namespace App\Traits;

trait LogActivity
{
    /**
     * Log activity method stub
     * This is a placeholder to prevent errors when controllers use this trait
     */
    protected function logActivity($action, $model = null, $description = '')
    {
        // Optional: implement logging if needed
        // For now, this is just a stub to prevent "Trait not found" errors
        \Log::info("Activity: {$action}", [
            'model' => is_object($model) ? get_class($model) : $model,
            'description' => $description,
            'user_id' => auth()->id()
        ]);
    }
}
