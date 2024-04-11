<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Repositories\PageRepository;
use App\Http\Requests\PageStoreRequest;
use App\Http\Requests\PageUpdateRequest;
use App\Models\Page;
use Illuminate\Support\Str;

class PageController extends Controller
{
    protected $pageRepository;

    public function __construct()
    {
        $this->pageRepository = app(PageRepository::class);
    }

    public function index()
    {
        return $this->pageRepository->getAll();
    }

    public function edit($id)
    {
        return $this->pageRepository->getById($id);
    }

    public function update(PageUpdateRequest $request, $id)
    {
        $slug = Str::slug($request->slug, '-');
        Page::query()->find($id)->update([
            'title' => $request->title,
            'slug' => $slug,
            'text' => $request->text,
        ]);

        return true;
    }

    public function store(PageStoreRequest $request)
    {
        $slug = Str::slug($request->slug, '-');
        $page = Page::query()->create([
            'title' => $request->title,
            'slug' => $slug,
            'text' => $request->text,
        ]);

        return $page->id;
    }
}
