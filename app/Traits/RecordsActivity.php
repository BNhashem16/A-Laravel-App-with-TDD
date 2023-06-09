<?php  

namespace App\Traits;

use App\Models\Activity;
use Illuminate\Support\Arr;

/**
* record activity trait
*/
trait RecordsActivity
{
    public $oldAttributes = [];
    public static $recordableEvents;



    /**
     * [boot the trait]
     */
    public static function bootRecordsActivity()
    {
        // if (isset(static::$recordableEvents)) {
        //     $recordableEvents = static::$recordableEvents;
        // } else {
        //     $recordableEvents = ['created', 'updated', 'deleted'];
        // }

        // foreach ($recordableEvents as $event) {
        foreach (static::recordableEvents() as $event) { 
            static::$event(function ($model) use ($event) {
                // $description = $event;
                // if (class_basename($model) !== 'Project') {
                //     $description = $event . '_' . strtolower(class_basename($model));
                // }

                // $model->recordActivity($description);
                $model->recordActivity($model->activityDescription($event));
            });

            if ($event === 'updated') {
                static::updating(function ($model) {
                    $model->oldAttributes = $model->getOriginal();
                });
            }
        }
    }

    protected function activityDescription($description)
    {
        // if (class_basename($this) !== 'Project') {
        //     return "${description}_" . strtolower(class_basename($this));
        // }
        // return $description;

        return "${description}_" . strtolower(class_basename($this));    
    }

    protected static function recordableEvents()
    {
        if (isset(static::$recordableEvents)) {
            return static::$recordableEvents;
        } 
        return ['created', 'updated'];
    }

    public function recordActivity($description)
    {
        $this->activity()->create([
            'user_id' => $this->activityOwner()->id,
            'description' => $description,
            'changes' => $this->activityChanges($description),
            'project_id' => class_basename($this)==='Project' ? $this->id : $this->project_id
        ]);
    }

    public function activityOwner()
    {
        return ($this->project ?? $this)->owner;
    }

    public function activity()
    {
        return $this->morphMany(Activity::class, 'subject')->latest();
    }

    public function activityChanges()
    {
        if ($this->wasChanged()) {
            return [
                'before' => Arr::except(array_diff($this->oldAttributes, $this->getAttributes()), 'updated_at'),
                'after' => Arr::except($this->getChanges(), 'updated_at')
            ];
        }
    }
}