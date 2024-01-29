<?php

namespace App\Http\Controllers\Admin\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Post\PostUpdateRequest;
use App\Repositories\Admin\PostRepository;
use Illuminate\Http\Request;

class PostAjaxController extends Controller
{
    protected $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function changePostPosition(Request $request)
    {
        return response()->json([
            'data' => $this->postRepository->changePostPosition($request)
        ]);
    }

    public function getPost(Request $request)
    {
        return response()->json([
            'data' => $this->postRepository->getPostById($request)
        ]);
    }

    public function updatePost(PostUpdateRequest $request)
    {
        return response()->json([
            'data' => $this->postRepository->updatePost($request)
        ]);
    }

    public function updateContentPost(Request $request)
    {
        return response()->json([
            'data' => $this->postRepository->updateContentPost($request)
        ]);
    }

    public function postScriptStore(Request $request)
    {
        return response()->json([
            'data' => $this->postRepository->postScriptStore($request)
        ]);
    }

    public function postInsidelinkStore(Request $request)
    {
        return response()->json([
            'data' => $this->postRepository->postInsidelinkStore($request)
        ]);
    }

    public function storeFaqPost(Request $request)
    {
        return response()->json([
            'data' => $this->postRepository->storeFaqPost($request)
        ]);
    }

    public function showAllNote(Request $request)
    {
        return response()->json([
            'data' => $this->postRepository->showAllNote()
        ]);
    }
    public function showNote(Request $request)
    {
        return response()->json([
            'data' => $this->postRepository->showNote($request)
        ]);
    }

//    public function postType(Request $request)
//    {
//        return response()->json([
//            'data' => $this->postRepository->getPostByType($request->value)
//        ]);
//    }

//    public function postChild(Request $request)
//    {
//        return response()->json([
//            'data' => $this->postRepository->getPostByParent($request->value)
//        ]);
//    }
}
