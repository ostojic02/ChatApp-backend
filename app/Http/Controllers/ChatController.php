<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\GetChatRequest;
use App\Models\Chat;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Http\Requests\StoreChatRequest;

class ChatController extends Controller
{
    public function index(GetChatRequest $request)
    {
        $data = $request->validated(); 
        $isPrivate = 1;
    
        if ($request->has(key:'is_private')) {
            $isPrivate = (int)$data['is_private']; 
        }
    
        $chats = Chat::where('is_private', $isPrivate)
            ->hasParticipant(auth('api')->user()->id) 
            ->whereHas('message')
            ->with('lastMessage.user', 'participants.user')
            ->latest('updated_at') 
            ->get();
    
        return $this->success($chats, 'Chat list retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreChatRequest $request)
{
    $data = $this->prepareStoreData($request);
    if ($data['userId'] == $data['otherUserId']) {
        return $this->error('You can not create a chat with your own');
    }

    $previousChat = $this->getPreviousChat($data['otherUserId']);

    if ($previousChat == null) {
        $chat = Chat::create($data['data']);
        $chat->participants()->createMany([
            [
                'user_id' => $data['userId']
            ],
            [
                'user_id' => $data['otherUserId']
            ]
        ]);

        $chat->refresh()->load('lastMessage.user', 'participants.user');
        return $this->success($chat);
    }

    return $this->success(data:$previousChat()->load('lastMessage.user', 'participants.user'));
}


    private function prepareStoreData(StoreChatRequest $request) : array
    {
    $data = $request->validated();
    $otherUserId = (int) $data['user_id'];
    unset($data['user_id']);
    $data['created_by'] = auth('api')->user()->id;

    return [
        'otherUserId' => $otherUserId,
        'userId' => auth('api')->user()->id,
        'data' => $data,
    ];
    }


    private function getPreviousChat(int $otherUserId) : mixed
    {
    $userId = auth('api')->user()->id;

    return Chat::where('is_private', 1)
        ->whereHas('participants', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
        ->whereHas('participants', function ($query) use ($otherUserId) {
            $query->where('user_id', $otherUserId);
        })
        ->first();
    }

    /**
     * Display the specified resource.
     */
    public function show(Chat $chat):JsonResponse
    {
        $chat->load(['lastMessage.user','participants.user']);
        return $this->success($chat);        
    }

}
