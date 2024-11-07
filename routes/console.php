<?php


use App\Console\Commands\CheckCamera;
use Illuminate\Support\Facades\Schedule;

Schedule::command(CheckCamera::class)->daily()->onOneServer()->withoutOverlapping();
