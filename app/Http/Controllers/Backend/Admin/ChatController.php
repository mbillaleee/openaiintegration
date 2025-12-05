<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ChatAssistant;
use App\Models\ChatConversation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    public function allAssistants()
    {
        $assistants = ChatAssistant::latest()->get();
        return view('admin.backend.assistant.index', compact('assistants'));
    }

    public function addAssistants()
    {
        return view('admin.backend.assistant.create');
    }

    public function chatAssistantStore(Request $request)
    {
        $data = new ChatAssistant();
        $data->name = $request->name;
        $data->role_description = $request->role_description;
        $data->welcome_message = $request->welcome_message;
        $data->instructions = $request->instructions;
        $data->category = $request->category;
        $data->is_active = $request->is_active;
        $data->user_id = Auth::id();

        
        if ($request->hasFile('avater')) {
            $file = $request->file('avater');
            $fileName = time().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('upload/avater'), $fileName);
            $data->avater = $fileName;
        }

        $data->save();

        $notification = array(
            'message' => 'Chat assistant created successfully',
            'alert-type' =>'success'
        );
        return redirect()->route('all.assistants')->with($notification);
    }

     public function chatAssistantUpdate(Request $request)
     {

     }

     public function chatAssistantsChat($assistantId)
    {
        $assistant = ChatAssistant::findOrFail($assistantId);

        $conversations = ChatConversation::where('assistant_id', $assistantId)
            ->where('user_id', Auth::id())
            ->select('chat_conversations.*')
            ->whereIn('id', function($query) use ($assistantId) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('chat_conversations')
                    ->where('assistant_id', $assistantId)
                    ->where('user_id', Auth::id())
                    ->groupBy('conversation_id');
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($conv) {
                $conv->messages_count = ChatConversation::where('conversation_id', $conv->conversation_id)->count();
                return $conv;
            });

        $selectedConversation = $conversations->first();

        $messages = $selectedConversation
            ? ChatConversation::where('assistant_id', $assistantId)
                ->where('user_id', Auth::id())
                ->where('conversation_id', $selectedConversation->conversation_id)
                ->orderBy('created_at', 'asc')
                ->get()
            : collect();

        return view('admin.backend.assistant.chat_assistant', compact(
            'assistant',
            'conversations',
            'messages',
            'selectedConversation'
        ));
    }

   public function chatSendMessage(Request $request, $assistantId)
    {
        $request->validate([
            'message' => 'required|string'
        ]);

        $assistant = ChatAssistant::findOrFail($assistantId);
        $userMessage = $request->message;

        // Determine latest conversation
        $latestConversation = ChatConversation::where('assistant_id', $assistantId)
            ->where('user_id', Auth::id())
            ->latest('created_at')
            ->first();

        // Tracking conversation thread ID
        $conversationId = $latestConversation ? ($latestConversation->conversation_id ?? $latestConversation->id) : null;

        // API call
        $response = OpenAI::chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'system', 'content' => $assistant->instructions],
                ['role' => 'user', 'content' => $request->message],
            ],
        ]);

        $aiResponse = $response->choices[0]->message->content;

        // Create new conversation record
        $conversation = ChatConversation::create([
            'assistant_id' => $assistantId,
            'user_id' => Auth::id(),
            'message' => $userMessage,
            'response' => $aiResponse,
            'conversation_id' => $conversationId ?? null, // null হলে just null যাবে
        ]);

        // If new thread, update conversation_id with its own id
        if (!$conversationId) {
            $conversation->update(['conversation_id' => $conversation->id]);
        }

        return redirect()->route('chatassistants.chat', ['assistantId' => $assistantId]);
    }

    public function startNewConversation($assistantId)
    {
        $assistant = ChatAssistant::findOrFail($assistantId);
        $newConversation = ChatConversation::create([
            'assistant_id' => $assistantId,
            'user_id' => Auth::id(),
            'message' => 'New conversation started',
            'response' => $assistant->welcome_message,
            'conversation_id' => null// null হলে just null যাবে
        ]);

        $newConversation->conversation_id = $newConversation->id;
        $newConversation->save();

        return redirect()->route('chatassistants.chat', ['assistantId' => $assistantId]);
    }

    public function selectConversation($assistantId, $conversationId)
    {
        $assistant = ChatAssistant::findOrFail($assistantId);

        $conversations = ChatConversation::where('assistant_id', $assistantId)
            ->where('user_id', Auth::id())
            ->select('chat_conversations.*')
            ->whereIn('id', function($query) use ($assistantId) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('chat_conversations')
                    ->where('assistant_id', $assistantId)
                    ->where('user_id', Auth::id())
                    ->groupBy('conversation_id');
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($conv) {
                $conv->messages_count = ChatConversation::where('conversation_id', $conv->conversation_id)->count();
                return $conv;
            });


        $selectedConversation = ChatConversation::where('conversation_id', $conversationId)
                                                ->where('assistant_id', $assistantId)
                                                ->where('user_id', Auth::id())
                                                ->firstOrFail();
                                                
        $messages = ChatConversation::where('assistant_id', $assistantId)
                                    ->where('user_id', Auth::id())
                                    ->where('conversation_id', $selectedConversation->conversation_id ?? $selectedConversation->id)
                                    ->orderBy('created_at', 'asc')->get();


        return view('admin.backend.assistant.chat_assistant', compact(
            'assistant',
            'conversations',
            'messages',
            'selectedConversation'
        ));
    }
}
