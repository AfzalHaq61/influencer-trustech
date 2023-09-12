<?php

namespace App\Http\Controllers\Influencer;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Job;
use App\Models\User;
use App\Models\Influencer;
use App\Models\Freelancer;
use App\Models\ConversationMessage;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ConversationController extends Controller {

    public function clientCreate($id, $jobid=0){
        
        $pageTitle    = "Conversation";
        $message    = null;
        $user   = User::select('id', 'username', 'status')->find($id);
        $conversation = Conversation::where('influencer_id', authInfluencerId())->where('user_id', $id)->first();

        if (!$conversation) {
            $conversation                = new Conversation();
            $conversation->user_id       = $id;
            $conversation->influencer_id = authInfluencerId();
            $conversation->save();
        }

        if($jobid != 0){
            $job = Job::find($jobid);
            $message    = "Hello ". $user->username." I have seen your job posting about ' ".$job->name." ' and I want to disscuss something about it.";
        }
        
        $conversationMessage = ConversationMessage::where('conversation_id', $conversation->id)->latest()->take(10)->get();
        return view($this->activeTemplate . 'influencer.conversation.view', compact('pageTitle', 'conversation', 'conversationMessage', 'user','message'));
    }

    public function influenceCreate($id, $jobid=0){

        // if(authInfluencerId() == $id){
        //     $notify[] = ['warning', 'You posted this job'];
        //     return back()->withNotify($notify);
        // }
        
        $pageTitle    = "Conversation";
        $message    = null;
        $user   = Influencer::select('id', 'username', 'status')->find($id);
        $conversation = Conversation::where('influencer_id', authInfluencerId())->where('user_id', $id)->first();

        if (!$conversation) {
            $conversation                = new Conversation();
            $conversation->user_id       = $id;
            $conversation->influencer_id = authInfluencerId();
            $conversation->save();
        }

        if($jobid != 0){
            $job = Job::find($jobid);
            $message    = "Hello ". $user->username." I have seen your job posting about ' ".$job->name." ' and I want to disscuss something about it.";
        }
        
        $conversationMessage = ConversationMessage::where('conversation_id', $conversation->id)->latest()->take(10)->get();
        return view($this->activeTemplate . 'influencer.conversation.view', compact('pageTitle', 'conversation', 'conversationMessage', 'user','message'));
    }

    public function freelancerCreate($id, $jobid=0){
        
        $pageTitle    = "Conversation";
        $message    = null;
        $user   = Freelancer::select('id', 'username', 'status')->find($id);
        $conversation = Conversation::where('influencer_id', authInfluencerId())->where('user_id', $id)->first();

        if (!$conversation) {
            $conversation                = new Conversation();
            $conversation->user_id       = $id;
            $conversation->influencer_id = authInfluencerId();
            $conversation->save();
        }

        if($jobid != 0){
            $job = Job::find($jobid);
            $message    = "Hello ". $user->username." I have seen your job posting about ' ".$job->name." ' and I want to disscuss something about it.";
        }
        
        $conversationMessage = ConversationMessage::where('conversation_id', $conversation->id)->latest()->take(10)->get();
        return view($this->activeTemplate . 'influencer.conversation.view', compact('pageTitle', 'conversation', 'conversationMessage', 'user','message'));
    }

    public function index(Request $request) {
        $pageTitle     = 'Conversation List';
        $conversations = Conversation::where('influencer_id', authInfluencerId());

        if ($request->search) {
            $search        = $request->search;
            $conversations = $conversations->WhereHas('user', function ($user) use ($search) {
                $user->where('username', 'like', "%$search%")->orWhere('firstname', 'like', "%$search%")->orWhere('lastname', 'like', "%$search%");
            });
        }

        $conversations = $conversations->with(['user', 'lastMessage'])->whereHas('lastMessage')->latest()->paginate(getPaginate());
        return view($this->activeTemplate . 'influencer.conversation.index', compact('pageTitle', 'conversations'));
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
        $message->sender          = 'influencer';
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
        $pageTitle           = 'Conversation with Client';
        $conversation        = Conversation::where('influencer_id', authInfluencerId())->where('id', $id)->with('user', 'messages')->first();
        $user                = $conversation->user;
        $conversationMessage = $conversation->messages->take(10);
        return view($this->activeTemplate . 'influencer.conversation.view', compact('pageTitle', 'conversation', 'conversationMessage', 'user','message'));
    }

    public function message(Request $request){
        $conversationMessage = ConversationMessage::where('conversation_id',$request->conversation_id)->take($request->messageCount)->latest()->get();
        return view($this->activeTemplate . 'influencer.conversation.message', compact('conversationMessage'));
    }

}
