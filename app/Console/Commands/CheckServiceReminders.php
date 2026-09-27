<?php

namespace App\Console\Commands;

use App\Models\ServiceReminder;
use App\Models\Vehicle;
use App\Notifications\ServiceReminderNotification;
use Illuminate\Console\Command;

/**
 * Scheduled daily (see routes/console.php) to scan every vehicle and notify
 * owners whose vehicle has crossed its mileage or time based service interval.
 * A ServiceReminder row is written so the same due-interval is not re-notified.
 */
class CheckServiceReminders extends Command
{
    protected $signature = 'autocare:check-service-reminders';

    protected $description = 'Notify vehicle owners whose vehicles are due for their next scheduled service.';

    public function handle(): int
    {
        $dueCount = 0;

        Vehicle::with('owner')->chunk(100, function ($vehicles) use (&$dueCount) {
            foreach ($vehicles as $vehicle) {
                if (! $vehicle->isServiceDue()) {
                    continue;
                }

                $alreadyNotifiedRecently = $vehicle->serviceReminders()
                    ->where('notified', true)
                    ->where('notified_at', '>=', now()->subDays(14))
                    ->exists();

                if ($alreadyNotifiedRecently) {
                    continue;
                }

                $vehicle->owner->notify(new ServiceReminderNotification($vehicle));

                ServiceReminder::create([
                    'vehicle_id' => $vehicle->id,
                    'trigger_type' => 'mileage',
                    'due_mileage_km' => $vehicle->last_service_mileage_km + $vehicle->service_interval_km,
                    'notified' => true,
                    'notified_at' => now(),
                ]);

                $dueCount++;
            }
        });

        $this->info("Service reminders sent: {$dueCount}");

        return self::SUCCESS;
    }
}
