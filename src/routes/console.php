<?php

use App\Console\Commands\Api\UpdateCommand;
use Illuminate\Support\Facades\Schedule;

Schedule::command(UpdateCommand::class)->twiceDaily(8, 20);
