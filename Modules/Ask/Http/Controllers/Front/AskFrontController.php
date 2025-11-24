<?php

namespace Modules\Ask\Http\Controllers\Front;

use Modules\Article\Models\ArticleCategory;
use Modules\Article\Models\Tag;
use Modules\Ask\Http\Requests\AnswerRequest;
use Modules\Ask\Http\Requests\AskRequest;
use Modules\Ask\Http\Requests\QuestionRequest;
use Modules\Ask\Models\Answer;
use Modules\Ask\Models\Question;
use Modules\Generic\Http\Controllers\Front\GenericFrontController;
use Modules\Gym\Models\Category;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class AskFrontController extends GenericFrontController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function ask($id, $slug)
    {
        $article_categories = ArticleCategory::get();
        $tags = Tag::get()->pluck('name')->toArray();
        $get_tags = array_rand(array_flip($tags), 8);
        $tags = '"'.implode('", "', $tags).'"';

        $question = Question::with('user', 'category')->where('id', $id)->first();
        $answers = Answer::with('user', 'child_answers')->where('parent_id', null)->where('question_id', $id)->get();
        $question->increment('views');

        $title = $question->question;
        return view('ask::Front.ask',
            compact('title', 'question','answers','article_categories', 'get_tags', 'tags'));
    }
    public function asks()
    {
        $search = request('search');
        $tag = request('tag');
        $category_id = request('category_id');

        $page = request('page') ?? 1;
        $title = trans('global.questions').' - '.trans('global.page').' '.$page;;
        $asks = Question::with('answers')->where('published', 1)->orderBy('id', 'DESC');
        $asks->when($search, function ($query) use ($search) {
            $query->where('question','like', "%".$search."%");
        });
        $asks->when($category_id, function ($query) use ($category_id) {
            $query->where('category_id', $category_id);
        });
        $asks->when($tag, function ($query) use ($tag) {
            $query->whereHas('tags', function ($q) use ($tag) {
                $q->where('name', 'like', "%" . $tag . "%");
            });
        });
        $asks = $asks->paginate($this->limit);
        $total = $asks->total();


        $article_categories = ArticleCategory::get();
        $tags = Tag::get()->pluck('name')->toArray();
        
        // Safety check for empty tags array
        if (!empty($tags)) {
            $get_tags = array_rand(array_flip($tags), min(8, count($tags)));
        } else {
            $get_tags = [];
        }
        
        $tags = '"'.implode('", "', $tags).'"';

        return view('ask::Front.asks', compact('asks','title', 'total', 'article_categories', 'tags' , 'get_tags'));

    }
    public function createQuestion()
    {
        $title = trans('global.add_question');

        $article_categories = ArticleCategory::get();
        $tags = Tag::get()->pluck('name')->toArray();
        $get_tags = array_rand(array_flip($tags), 8);
        $tags = '"'.implode('", "', $tags).'"';

        return view('ask::Front.ask-create', ['title'=>$title, 'article_categories' => $article_categories, 'tags' => $tags, 'get_tags' => $get_tags]);
    }

    public function storeQuestion(QuestionRequest $request)
    {
        $ask_inputs = $request->except(['_token', 'tags']);
        $ask_inputs['user_id'] =  @$this->user->id;
        $ask_inputs['token'] = Str::random(40) . time();
        $question = Question::create($ask_inputs);

        if($request->tags){
            $tagArr = (explode(',', $request->tags));
            array_map(function ($q){
                Tag::updateOrCreate(['name' => $q], ['name' => $q]);
            }, $tagArr);
            $tagIds = Tag::whereIn('name', $tagArr)->get()->pluck('id');
            $question->tags()->sync($tagIds);
        }

        session(['question_token' => $ask_inputs['token']]);
        $setting = $this->mainSettings;
        if($ask_inputs['email']) {
            Mail::send('emails.ask_question', ['ask_inputs' => $ask_inputs, 'setting' => $setting, 'question' =>  $question], function ($message) use ($ask_inputs, $setting) {
                $message->from($setting->support_email, trans('global.ask_gymmawy'));
                $message->to($ask_inputs['email'], $ask_inputs['name'] ?? trans('global.guest'))->subject(trans('global.ask_question_add'));
            });
        }
        Session::flash('success', trans('global.question_add_successfully'));

        return redirect(route('ask', [$question->id, $question->slug]));
    }
    public function editQuestion(){
        $title = trans('global.edit_question');
        $token = request('token');
        $question = Question::with(['tags'])->where(['token' => $token])->first();
        $questionTags = ($question->tags->pluck('name')->implode(','));
        if(!$question)
            return redirect()->route('createAnswerAsk', $question->id);
        $article_categories = ArticleCategory::get();
        $tags = Tag::get()->pluck('name')->toArray();
        $get_tags = array_rand(array_flip($tags), 8);
        $tags = '"'.implode('", "', $tags).'"';

        return view('ask::Front.ask-edit', ['question' => $question, 'questionTags' => $questionTags, 'title'=>$title, 'article_categories' => $article_categories, 'tags' => $tags, 'get_tags' => $get_tags]);

    }

    public function updateQuestion(QuestionRequest $request, $token)
    {
        $question = Question::where(['token' => $token])->first();
        $ask_inputs = $request->except(['_token', 'token', 'tags']);
        $question->update($ask_inputs);

        if($request->tags){
            $tagArr = (explode(',', $request->tags));
            array_map(function ($q){
                Tag::updateOrCreate(['name' => $q], ['name' => $q]);
            }, $tagArr);
            $tagIds = Tag::whereIn('name', $tagArr)->get()->pluck('id');
            $question->tags()->sync($tagIds);
        }
        $setting = $this->mainSettings;
        if($question['email']) {
            Mail::send('emails.ask_question_edit', ['ask_inputs' => $ask_inputs, 'setting' => $setting, 'question' =>  $question], function ($message) use ($ask_inputs, $setting, $question) {
                $message->from($setting->support_email, trans('global.ask_gymmawy'));
                $message->to($question['email'], $question['name'] ?? trans('global.guest'))->subject(trans('global.ask_question_edit'));
            });
        }
        Session::flash('success', trans('global.question_edit_successfully'));
        return redirect(route('ask', [$question->id, $question->slug]));
    }
    public function createAnswer()
    {
        $title = 'Create Ask';
        return view('ask::Front.ask-create', ['ask' => new Ask(),'title'=>$title]);
    }

    public function storeAnswer(AnswerRequest $request, $id)
    {
        $question = Question::where('id', $id)->first();
        $ask_inputs = $request->except(['_token']);
        $ask_inputs['token'] = Str::random(40) . time();
        $ask_inputs['question_id'] = $question->id;
        session(['answer_token' => $ask_inputs['token']]);
        Answer::create($ask_inputs);
        $setting = $this->mainSettings;

        if($ask_inputs['email']){
            Mail::send('emails.ask_answer', ['ask_inputs' => $ask_inputs, 'setting' => $setting, 'question' =>  $question], function ($message) use ($ask_inputs, $setting, $question) {
                $message->from($setting->support_email, trans('global.ask_gymmawy'));
                $message->to($ask_inputs['email'], $ask_inputs['name'] ?? trans('global.guest'))->subject(trans('global.ask_answer_add'));
            });
        }
        if($question['email']){
            Mail::send('emails.ask_question_reply', ['ask_inputs' => $ask_inputs, 'setting' => $setting, 'question' =>  $question], function ($message) use ($ask_inputs, $setting, $question) {
                $message->from($setting->support_email, trans('global.ask_gymmawy'));
                $message->to($question['email'], $question['name'] ?? trans('global.guest'))->subject(trans('global.answer_on_your_question'));
            });
        }

        Session::flash('success', trans('global.answer_add_successfully'));
        return redirect()->back();
    }
    public function hideQuestion(){
        $token = request('token');
        $question = Question::where('token', $token)->first();
        if($question->published == 1){
            Session::flash('success', trans('global.question_hide_successfully'));
            $question->published = 0;
        }else{
            Session::flash('success', trans('global.question_show_successfully'));
            $question->published = 1;
        }
        $question->save();
        return redirect()->route('asks');
    }
    public function storeReply()
    {
        $ask_inputs = request()->except(['_token']);
        $answer = Answer::with('question')->where('id', $ask_inputs['answer_id'])->first();
        if($answer){
            $data = [
                        'parent_id' => $answer->id,
                        'question_id' => $answer->question_id,
                        'user_id' => @$this->user->id,
                        'answer' => $ask_inputs['answer'],
                        'name' => $ask_inputs['name'],
                        'email' => $ask_inputs['email']
                    ];
            Answer::create($data);

            if($answer['email']){
                $setting = $this->mainSettings;
                Mail::send('emails.ask_answer_reply', ['data' => $data, 'setting' => $setting, 'answer' =>  $answer], function ($message) use ($answer, $setting, $data) {
                    $message->from($setting->support_email, trans('global.ask_gymmawy'));
                    $message->to($answer['email'], $answer['name'] ?? trans('global.guest'))->subject(trans('global.reply_on_your_answer'));
                });
            }
            Session::flash('success', trans('global.answer_add_successfully'));
            return '1';
        }
        return '0';
    }
    public function askTags(){
        $page = request('page') ?? 1;
        $title = trans('global.tags').' - '.trans('global.page').' '.$page;;
        $records = Tag::where('language', $this->lang)->orderBy('id', 'DESC');
        $records = $records->paginate(40);
        $total = $records->total();

        $article_categories = ArticleCategory::get();
        $tags = Tag::get()->pluck('name')->toArray();
        $get_tags = array_rand(array_flip($tags), 8);
        $tags = '"'.implode('", "', $tags).'"';

        return view('ask::Front.ask-tags', compact('records','title', 'total', 'article_categories', 'tags' , 'get_tags'));

    }

    public function getRelatedQuestionsAjax(){
        $question = request('question');
        $questions = Question::select('id', 'question', 'details')->where('published', 1)->where('question', 'like', '%'.$question.'%')->orWhere('details', 'like', '%'.$question.'%')->limit(8)->get();
        if($questions->count() > 0)
            return ($questions->toJson());

        return '';
    }
}
