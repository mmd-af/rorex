<?php

namespace App\Http\Controllers\Admin\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Post\PostNoteStoreRequest;
use App\Http\Requests\Admin\Post\PostStoreRequest;
use App\Http\Requests\Admin\Post\PostUpdateRequest;
use App\Models\User\Post;
use App\Repositories\Admin\PostRepository;

class PostController extends Controller
{
    protected $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function index()
    {
        $posts = $this->postRepository->getAll();
        return view('admin.posts.index', compact('posts'));
    }

    public function show($post)
    {
        $post = $this->postRepository->getPost($post);
        return view('admin.posts.show', compact('post'));
    }

    public function create()
    {
        return view('admin.posts.create');
    }

    public function store(PostStoreRequest $request)
    {
        $this->postRepository->store($request);
        return redirect()->back();
    }

    public function edit(Post $post)
    {
        return view('admin.posts.edit', compact('post'));
    }

    public function update(PostUpdateRequest $request, Post $post)
    {
        $this->postRepository->update($request, $post);
        return redirect()->route('admin.posts.index');
    }

    public function noteStore(PostNoteStoreRequest $request)
    {
        $this->postRepository->noteStore($request);
        return redirect()->back();
    }

    public function destroy(Post $post)
    {
        $this->postRepository->destroy($post);
        return redirect()->back();
    }
}
