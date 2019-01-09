<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

use App\Libs\FileHelper;
use App\Http\Requests;
use App\Http\Requests\ClarifyRequest;
use App\Events\Clarify\ClarifyCreate;
use App\Models\Clarify;
use App\Models\Question;
use App\Models\Document;
use App\Models\Call;
use App\Models\Answer;
use App\Models\File;

class ClarifyController extends Controller
{

    use FileHelper;

    /**
     * Переадресация к уточнению на страницу услуги.
     * @param  integer $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToClarify($id)
    {
        $clarify = Clarify::findOrFail($id);

        return redirect()->route($clarify->to_type . '.clarify', [$clarify->to_type => $clarify->to, 'clarify' => $clarify]);
    }

    /**
     * Создание уточнения.
     * @param  mixed $element
     * @param  ClarifyRequest $request
     * @param  mixed $parent
     * @return Clarify
     */
    protected function createFor($element, ClarifyRequest $request, $parent = null)
    {
        $clarifies = $element->clarifies();
        $clarify = $clarifies->create($request->all());
        $user = Auth::user();
        if ($request->hasFile('file') && $file = $this->saveRequestFile($request->file('file'), config('site.clarify.file.directory', 'private/clarifies'))) {
            $fileModel = new File(['file' => $file, 'field' => 'file']);
            $fileModel->user()->associate($user);
            $fileModel->parent()->associate($parent ?: $element);
            $clarify->file()->save($fileModel);
        }
        return $clarify;
    }

    /**
     * Уточнения вопроса.
     * @param  integer        $id
     * @param  ClarifyRequest $request 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createForQuestion(ClarifyRequest $request, $id)
    {
        $question = Question::findOrFail($id);

        $this->authorize('clarify', [Clarify::class, $question]);

        $clarify = $this->createFor($question, $request);

        event(new ClarifyCreate($clarify, $question, $question->user));

        $count = $question->clarifies->count();
        
        return view('clarify.iframe', [
            'type' => 'question',
            'model' => $question,
            'title' => trans_choice('clarify.count_clarifies', $count, ['count' => $count]),
            'messages' => [
                'success' => trans('clarify.message.created'),
            ],
        ]);
    }

    /**
     * Уточнения к запросу на документ.
     * @param  integer        $id
     * @param  ClarifyRequest $request 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createForDocument(ClarifyRequest $request, $id)
    {
        $document = Document::findOrFail($id);

        $this->authorize('clarify', [Clarify::class, $document]);

        $clarify = $this->createFor($document, $request);

        event(new ClarifyCreate($clarify, $document, $document->user));

        $count = $document->clarifies->count();

        return view('clarify.iframe', [
            'type' => 'document',
            'model' => $document,
            'title' => trans_choice('clarify.count_clarifies', $count, ['count' => $count]),
            'messages' => [
                'success' => trans('clarify.message.created'),
            ],
        ]);
        // return redirect()->route('document.view', ['document' => $document, 'answer'])->with('success', trans('clarify.message.created'));
    }

    /**
     * Уточнения для заказа звонков.
     * @param  integer        $id
     * @param  ClarifyRequest $request 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createForCall(ClarifyRequest $request, $id)
    {
        $call = Call::findOrFail($id);

        $this->authorize('clarify', [Clarify::class, $call]);

        $clarify = $this->createFor($call, $request);

        event(new ClarifyCreate($clarify, $call, $call->user));

        $count = $call->clarifies->count();

        return view('clarify.iframe', [
            'type' => 'call',
            'model' => $call,
            'title' => trans_choice('clarify.count_clarifies', $count, ['count' => $count]),
            'messages' => [
                'success' => trans('clarify.message.created'),
            ],
        ]);
        // return redirect()->route('document.view', ['document' => $document, 'answer'])->with('success', trans('clarify.message.created'));
    }

    /**
     * Уточнения для заказа звонков.
     * @param  integer        $id
     * @param  ClarifyRequest $request 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createForAnswer(ClarifyRequest $request, $id)
    {
        $answer = Answer::findOrFail($id);

        $this->authorize('clarify-answer', [Clarify::class, $answer]);

        $clarify = $this->createFor($answer, $request, $answer->to);

        event(new ClarifyCreate($clarify, $answer, $answer->from->user));

        $count = $answer->clarifies->count();

        return view('clarify.iframe', [
            'type' => 'answer',
            'model' => $answer,
            'title' => trans_choice('clarify.count_clarifies', $count, ['count' => $count]),
            'messages' => [
                'success' => trans('clarify.message.created'),
            ],
        ]);
    }

}
