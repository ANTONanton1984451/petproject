<?php

namespace App\Http\Controllers;

use App\Models\SavedPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class SavedController extends Controller
{
    public function getPreview()
    {
        $savedPosts = Auth::user()->savedPosts()->get();

        return response($savedPosts);
    }

    public function deleteSavedPost(Request $request,$id)
    {
        $this->authorize('delete-save-post',$id);
        SavedPost::where('id',$id)->delete();
        return response('',204);
    }

    public function getSaved(Request $request,$id)
    {
        $this->authorize('get-saved',$id);

        $savedPost = SavedPost::find($id);
        return response($savedPost->only(['title','body']));
    }
}
