<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\ConversationMessage;
use App\Models\Influencer;
use App\Models\Job;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ConversationController extends Controller {

    public function create($id, $jobid=0) {

        $pageTitle    = "Conversation";
        $message    = null;
        $influencer   = Influencer::select('id', 'username', 'status', 'last_seen')->find($id);
        $conversation = Conversation::where('user_id', auth()->id())->where('influencer_id', $id)->first();

        if (!$conversation) {
            $conversation                = new Conversation();
            $conversation->user_id       = auth()->id();
            $conversation->influencer_id = $id;
            $conversation->save();
        }

        if($jobid != 0){
            $job = Job::find($jobid);
            $message    = "Hello ". $influencer->username." I have seen your job posting about ' ".$job->name." ' and I want to disscuss something about it.";
        }
        
        $conversationMessage = ConversationMessage::where('conversation_id', $conversation->id)->latest()->take(10)->get();
        return view($this->activeTemplate . 'user.conversation.view', compact('pageTitle', 'conversationMessage', 'influencer', 'conversation','message'));
    }

    public function index(Request $request) {

        $pageTitle     = 'Conversations List';
        $conversations = Conversation::where('user_id', auth()->id());
        if($request->search){
            $search = $request->search;
            $conversations = $conversations->WhereHas('influencer', function ($influencer) use ($search) {
                $influencer->where('username', 'like', "%$search%")->orWhere('firstname', 'like', "%$search%")->orWhere('lastname', 'like', "%$search%");
            });
        }
        $conversations = $conversations->with(['influencer', 'lastMessage'])->whereHas('lastMessage')->latest()->paginate(getPaginate());
        return view($this->activeTemplate . 'user.conversation.index', compact('pageTitle', 'conversations'));
    }

    public function store(Request $request, $id) {

        $validator = Validator::make($request->all(), [
            'message'       => 'required',
            'attachments'   => 'nullable|array',
            'attachments.*' => ['required', new FileTypeValidate(['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx', 'txt'])],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $message                  = new ConversationMessage();
        $message->conversation_id = $id;
        $message->sender          = 'client';
        $message->message         = $request->message;

        if ($request->hasFile('attachments')) {

            foreach ($request->file('attachments') as $file) {
                try {
                    $arrFile[] = fileUploader($file, getFilePath('conversation'));
                } catch (\Exception$exp) {
                    return response()->json(['error' => 'Couldn\'t upload your image']);
                }

            }
            $message->attachments = json_encode($arrFile);
        }

        $message->save();
        return view($this->activeTemplate.'user.conversation.last_message',compact('message'));
    }

    public function view($id) {
        $message    = null;
        $pageTitle           = 'Conversation with Influencer';
        $conversation        = Conversation::where('user_id', auth()->id())->where('id', $id)->with('influencer', 'messages')->first();
        $influencer          = $conversation->influencer;
        $conversationMessage = $conversation->messages->take(10);
        return view($this->activeTemplate . 'user.conversation.view', compact('pageTitle', 'conversation', 'conversationMessage', 'influencer','message'));
    }

    public function message(Request $request){
        $conversationMessage = ConversationMessage::where('conversation_id',$request->conversation_id)->take($request->messageCount)->latest()->get();
        return view($this->activeTemplate . 'user.conversation.message', compact('conversationMessage'));
    }
}
