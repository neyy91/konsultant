<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

use App\Helper;
use App\Http\Requests;
use App\Http\Requests\ComplaintsAdminFilterRequest;
use App\Events\Complaint\ComplaintCreate;
use App\Models\Complaint;
use App\Models\Question;
use App\Models\Document;
use App\Models\Call;
use App\Models\Answer;

/**
 * Жалобы.
 */
class ComplaintController extends Controller
{

    /**
     * Получение данных для формы.
     * @return array
     */
    public function getFormVars()
    {
        $formVars = [];
        $formVars['types'] = Complaint::getTypes();
        $formVars['againsts'] = ['question' => trans('question.title'), 'document' => trans('document.title'), 'call' => trans('call.title'), 'answer' => trans('answer.title')];
        return $formVars;
    }

    /**
     * Создание жалобы.
     * @param  Request $request
     * @param  \\Illuminate\Database\Eloquent\Relations\MorphMany $complaints
     * @return Complaint
     */
    protected function complainOf(Request $request, $complaints)
    {
        $other = Complaint::TYPE_OTHER;
        $this->validate($request, [
            'type' => 'required|in:' . implode(',', Complaint::getTypeKeys()),
            'comment' => "nullable|string|max:255"
        ]);
        $user = Auth::user();
        $complaint = new Complaint($request->all());
        $complaint->user()->associate($user);

        event(new ComplaintCreate($complaint, $user));

        return $complaints->save($complaint);
    }

    /**
     * Жалоба на вопрос.
     * @param  Request $request
     * @param  integer  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function complainOfQuestion(Request $request, $id)
    {
        $question = Question::findOrFail($id);

        $this->authorize('complain', [Complaint::class, $question]);

        $complaint = $this->complainOf($request, $question->complaints());

        return response()->json([
            'type' => 'question',
            'messages' => [
                'success' => trans('complaint.message.complain'),
            ],
        ]);
    }

    /**
     * Жалоба на документ.
     * @param  Request $request
     * @param  integer  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function complainOfDocument(Request $request, $id)
    {
        $document = Document::findOrFail($id);

        $this->authorize('complain', [Complaint::class, $document]);

        $complaint = $this->complainOf($request, $document->complaints());

        return response()->json([
            'type' => 'document',
            'messages' => [
                'success' => trans('complaint.message.complain'),
            ],
        ]);
    }

    /**
     * Жалоба на запрос консультации по телефону.
     * @param  Request $request
     * @param  integer  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function complainOfCall(Request $request, $id)
    {
        $call = Call::findOrFail($id);

        $this->authorize('complain', [Complaint::class, $call]);

        $complaint = $this->complainOf($request, $call->complaints());

        return response()->json([
            'type' => 'call',
            'messages' => [
                'success' => trans('complaint.message.complain'),
            ],
        ]);
    }

    /**
     * Жалоба на ответ.
     * @param  Request $request
     * @param  integer  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function complainOfAnswer(Request $request, $id)
    {
        $answer = Answer::findOrFail($id);

        $this->authorize('complain', [Complaint::class, $answer]);

        $complaint = $this->complainOf($request, $answer->complaints());

        return response()->json([
            'type' => 'answer',
            'messages' => [
                'success' => trans('complaint.message.complain'),
            ],
        ]);
    }


    /**
     * Список жалоб для админов.
     * @return \Illuminate\Http\Response
     */
    public function complaintsAdmin(ComplaintsAdminFilterRequest $request)
    {
        $fields = ['id', 'date_from', 'date_to', 'against', 'type'];
        $complaints = Helper::getRequestModel(
            Complaint::class,
            $request->all(),
            $fields,
            [
                'date_from' => [
                    'condition' => '>=',
                    'field' => 'created_at',
                ],
                'date_to' => [
                    'condition' => '<=',
                    'field' => 'created_at',
                ],
                'against' => [
                    'field' => 'against_type',
                ]
            ]
        );
        $complaints->with('against');

        return view('complaint.admin.index', [
            'complaints' => $complaints->paginate(config('admin.page', 20))->appends($request->only($fields)),
            'request' => $request,
            'formVars' => $this->getFormVars(),
        ]);
    }

    /**
     * Отображение формы подтвержение удаления.
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function deleteFormAdmin($id)
    {
        $complaint = Complaint::findOrFail($id);
        return view('complaint.admin.delete', ['complaint' => $complaint]);
    }

    /**
     * Удаление жалобы.
     * @param  integer $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteAdmin($id)
    {
        $complaint = Complaint::findOrFail($id);
        $complaint->delete();
        return redirect()->route('complaints')->with('warning', trans('complaint.message.deleted'));
    }

}
