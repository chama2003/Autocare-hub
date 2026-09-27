<?php

use App\Console\Commands\CheckServiceReminders;
use Illuminate\Support\Facades\Schedule;

// Runs daily and emails owners of any vehicle that has crossed its
// recommended mileage/time service interval (see CheckServiceReminders).
Schedule::command(CheckServiceReminders::class)->daily();
