<?php

namespace App\Http\Controllers\FRONT\Employee;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /** Functions
     * index()
     * create()
     * store()
     * edit()
     * update()
     * destroy()
     * validatePost()
     */

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        $services = Service::all();
        return view('front.employee.post.index', compact(['user', 'services']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $user = Auth::user();
        return view('front.employee.post.create', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $post = new Post();
        $request->validate([
            'title' => 'required|min:3',
            'body' => 'required|min:15',
            'images' => 'required',
            'tags' => 'required'
        ]);
        $requestImages = $request->file('images');
        $images = array();
        foreach ($requestImages as $image) {
            $name = 'post_' . time() . '.' . $image->getClientOriginalExtension();
            if (!Storage::disk('public')->exists('posts')) {
                Storage::disk('public')->makeDirectory('posts');
            }
            if (Storage::disk('public')->putFileAs('posts', $image, $name)) {
                $element = 'posts/' . $name;
                array_push($images, $element);
            }
        }
        $post->title = $request->title;
        $post->body = $request->body;
        $post->images = $images;
        $post->save();
        $post->users()->attach($user->id);
        $user->posts()->attach($post->id);
        if (isset($tags)) {
            foreach ($tags as $tagId) {
                $post->tags()->attach($tagId);
            }
        }
        return redirect(route('employee.post.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        return view('front.employee.post.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function update(Request $request, $id)
    {
        $post = Post::find($id);
        $post->update($this->validatePost($request));
        return view('front.employee.post.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $post = Post::find($id);
        $post->delete();
        return redirect(route('employee.post.index'));
    }

    public function validatePost(Request $request)
    {
        return $request->validate([
            'title' => 'required|min:3|max:255',
            'body' => ['required'],
        ]);
    }

}
