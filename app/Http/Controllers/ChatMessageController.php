<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\StoreMessageRequest;
use App\Http\Requests\SearchMessageRequest;
use App\Events\NewMessageSent;
use App\Http\Requests\GetMessageRequest;

class ChatMessageController extends Controller
{
    public function index(GetMessageRequest $request): JsonResponse
{
    $data = $request->validated();
    $chatId = $data['chat_id'];
    $currentPage = $data['page'];
    $pageSize = $data['page_size'] ?? 20;

    $messages = ChatMessage::where('chat_id', $chatId)
        ->with('user')
        ->latest('created_at')
        ->simplePaginate(
            $pageSize,
            ['*'],
            'page',
            $currentPage
        );

    return $this->success($messages->getCollection());
}

public function store(StoreMessageRequest $request)
{
    $data = $request->validated();
    $data['user_id'] = auth('api')->user()->id;

    $chatMessage = ChatMessage::create($data);
    $chatMessage->load('user');
    /// TODO send broadcast event to pusher
    /// ovde treba dodati posle slanje poruke
    $this->sendNotificationToOther($chatMessage);

    return $this->success($chatMessage, message: 'Message has been sent successfully.');

    

}


private function sendNotificationToOther(ChatMessage $chatMessage): void
{
    //$chatId = $chatMessage->chat_id;

    broadcast(new NewMessageSent($chatMessage))->toOthers();

}

public function search(SearchMessageRequest $request){
    $query = $request->input('query');
    $chatId = $request->input('chat_id');

    $messages = ChatMessage::where('chat_id', $chatId)
        ->where('message', 'LIKE', "%{$query}%")
        ->orderBy('created_at', 'desc')
        ->get();

        if ($messages->isEmpty()) {
            return $this->error("Poruka nije pronadjena");
        }
    
        return $this->success($messages, "Poruka uspesno pronadjena");
}
}
