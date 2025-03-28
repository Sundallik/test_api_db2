<?php

use App\Console\Commands\UpdateCommand;
use Illuminate\Support\Facades\Schedule;

Schedule::command(UpdateCommand::class)->twiceDaily(8, 20);
