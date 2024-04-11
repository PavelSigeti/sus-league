<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Repositories\PageRepository;

class PageController extends Controller
{
    protected $pageRepository;

    public function __construct()
    {
        $this->pageRepository = app(PageRepository::class);
    }

    public function show($slug)
    {
        return $this->pageRepository->getBySlug($slug);
    }
}
