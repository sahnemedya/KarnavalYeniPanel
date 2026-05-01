<?php

use App\Http\Controllers\Cms\FAQController;
use Illuminate\Support\Facades\Route;

Route::resource('faqs', FAQController::class);


