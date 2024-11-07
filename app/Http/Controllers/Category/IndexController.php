<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Http\Resources\Category\CategoryIndexResource;
use App\Models\Category;
use Illuminate\Http\Request;

final readonly class IndexController extends Controller
{
    public function __invoke()
    {
        $categories = Category::all();

        return $this->present(qck_response(CategoryIndexResource::collection($categories)));
    }
}
