<?php

namespace App\Http\Controllers;

use App\Models\Reply;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReplyRequest;
use Illuminate\Support\Facades\Auth;

class RepliesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


	public function store(ReplyRequest $request,Reply $reply)
	{
	    $reply->topic_id = $request->topic_id;
	    $reply->user_id = Auth::id();
	    $reply->content = $request->post('content');
        $reply->save();
		return redirect()->back()->with('success', 'コメント出来ました');

	}

	public function destroy(Reply $reply)
	{
		$this->authorize('destroy', $reply);
		$reply->delete();

		return redirect()->back()->with('success', '削除出来ました');
	}
}
