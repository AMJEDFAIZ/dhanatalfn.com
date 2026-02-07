<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'all');
        $query = Message::query();
        if ($status === 'read') {
            $query->where('is_read', true);
        } elseif ($status === 'unread') {
            $query->where('is_read', false);
        }
        $messages = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        $counts = [
            'all' => Message::count(),
            'read' => Message::where('is_read', true)->count(),
            'unread' => Message::where('is_read', false)->count(),
        ];

        return view('admin.messages.index', compact('messages', 'counts', 'status'));
    }

    public function show(string $id)
    {
        $message = Message::findOrFail($id);
        if (! $message->is_read) {
            $message->is_read = true;
            $message->save();
        }

        return view('admin.messages.show', compact('message'));
    }

    public function update(Request $request, string $id)
    {
        $message = Message::findOrFail($id);
        $action = $request->input('action');
        if ($action === 'mark_read') {
            $message->is_read = true;
            $message->save();
        } elseif ($action === 'mark_unread') {
            $message->is_read = false;
            $message->save();
        } elseif ($action === 'reply') {
            $request->validate([
                'reply_content' => ['required', 'string', 'max:2000'],
            ]);
            Mail::raw($request->input('reply_content'), function ($m) use ($message) {
                $m->to($message->email)->subject('رد على رسالتك: '.$message->subject);
            });
            $message->reply_content = $request->input('reply_content');
            $message->replied_at = now();
            $message->save();
        }

        return redirect()->back();
    }

    public function destroy(string $id)
    {
        $message = Message::findOrFail($id);
        $message->delete();

        return redirect()->route('admin.messages.index');
    }

    public function markAllRead()
    {
        Message::where('is_read', false)->update(['is_read' => true]);

        return redirect()->route('admin.messages.index')->with('success', 'تم تمييز جميع الرسائل كمقروءة');
    }
}
