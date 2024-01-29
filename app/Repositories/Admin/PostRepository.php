<?php

namespace App\Repositories\Admin;

use Illuminate\Support\Facades\Auth;

class PostRepository extends BaseRepository
{
    public function __construct(Post $model)
    {
        $this->setModel($model);
    }

    public function getAll()
    {
        return Post::query()
            ->select([
                'id',
                'title',
                'slug',
                'is_active'
            ])
            ->get();

    }

    public function getPost($post)
    {
        return Post::query()
            ->select([
                'id',
                'title',
                'description',
                'meta_title',
                'meta_description'

            ])
            ->where('id', $post)
            ->with(['images'])
            ->first();

    }

    public function getPostById($request)
    {
        return Post::query()
            ->select([
                'id',
                'title',
                'slug',
                'is_active'
            ])
            ->where('id', $request->postID)
            ->with('images')
            ->first();
    }

    public function store($request)
    {
        $userId = Auth::id();
        $item = new Post();
        $item->author_id = $userId;
        $item->title = $request->input('title');
        $item->slug = str_slug_persian($request->input('slug'));
        $item->description = $request->input('description');
        $item->meta_title = $request->input('meta_title');
        $item->meta_description = $request->input('meta_description');
        $item->is_active = $request->input('is_active');
        $item->save();
        $image = new Image();
        $image->url = $request->input('url');
        $item->images()->save($image);
        return $item;
    }

    public function updatePost($request)
    {
        $post = $this->getPostById($request);
        $post->title = $request->input('title');
        $post->is_active = $request->input('is_active');
        $post->save();
        $post->images()->update(['url' => $request->input('url')]);
        return $post;
    }

    public function updateContentPost($request)
    {
        $post = $this->getPostById($request);
        if ($request->json_ld) {
            $schema = new Schema();
            $schema->json_ld = $request->json_ld;
            $post->schemas()->updateOrCreate([], ['json_ld' => $request->json_ld]);
        }
        $post->description = $request->description;
        $post->meta_title = $request->meta_title;
        $post->meta_description = $request->meta_description;
        $post->save();
    }

    public function postScriptStore($request)
    {
        $post = $this->getPostById($request);
        $post->scripts()->updateOrCreate(
            ['scriptable_id' => $post->id], // شرط بر اساس آن رکورد بروزرسانی یا ایجاد می‌شود
            [
                'css' => $request->css,
                'html' => $request->html,
                'js' => $request->js,
            ]
        );
    }

    public function postInsidelinkStore($request)
    {
        $post = $this->getPostById($request);
        $post->insidelinks()->updateOrCreate(
            ['insidelinkable_id' => $post->id], // شرط بر اساس آن رکورد بروزرسانی یا ایجاد می‌شود
            [
                'html' => $request->html
            ]
        );
    }

    public function storeFaqPost($request)
    {
        $post = $this->getPostById($request);
        $count = count($request->faq_questions);
        for ($i = 0; $i < $count; $i++) {
            $faq = new Faq();
            $faq->question = $request['faq_questions'][$i];
            $faq->answer = $request['faq_answers'][$i];
            $post->faqs()->save($faq);
        }
    }

    public function changePostPosition($request)
    {
        $entity = $this->model->find($request->entityId);
        $positionEntity = $this->model->find($request->positionEntityId);
        $entity->moveAfter($positionEntity);
    }

    public function showAllNote()
    {

        return Note::query()
            ->select([
                'id',
                'user_id',
                'description',
                'noteable_id',
                'created_at'
            ])
            ->where('noteable_type', Post::class)
            ->with(['users', 'post'])
            ->get();

    }

    public function showNote($request)
    {
        $post = $this->model->find($request->id);
        return $post->notes()->with('users')->get();

    }

    public function noteStore($request)
    {
        $user_id = Auth::id();
        $note = new Note();
        $note->user_id = $user_id;
        $note->description = $request->description;
        $note->noteable_id = $request->id;
        $note->noteable_type = Post::class;
        $note->save();
    }

    public function destroy($post)
    {
        $post->insidelinks()->delete();
        $post->images()->delete();
        $post->delete();
    }
}
