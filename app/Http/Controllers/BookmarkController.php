<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\Bookmark\BookmarkCreateRequest;
use App\Http\Requests\Bookmark\BookmarkCategoriesRequest;
use App\Http\Requests\Bookmark\BookmarkCategoryCreateRequest;
use App\Http\Requests\Bookmark\BookmarkCategoryUpdateRequest;
use App\Repositories\UserRepository;
use App\Models\BookmarkCategory;
use App\Models\Bookmark;
use App\Models\Question;
use App\Models\Lawyer;


class BookmarkController extends Controller
{

    /**
     * Репозиторий пользователя.
     * @var UserRepository
     */
    protected $users;

    /**
     * Конструктор.
     * @param UserRepository $users
     */
    public function __construct(UserRepository $users)
    {
        $this->users = $users;
        $this->middleware('auth');
    }

    /**
     * Категории в popover.
     * @param  BookmarkCategoriesRequest $request
     * @return \Illuminate\Http\Response
     */
    public function categoriesPopover(BookmarkCategoriesRequest $request)
    {
        $this->authorize('categories', Bookmark::class);

        $id = $request->input('id');
        $question = $request->input('question');
        $user = Auth::user();

        return view('bookmark.popover', [
            'categories' => $this->users->bookmarkCategories($user->lawyer),
            'bookmark' => $id ? Bookmark::findOrFail($id) : null,
            'question' => Question::findOrFail($question),
            'from' => $request->input('from'),
        ]);
    }

    /**
     * Получение кол-ва закладок.
     * @param  Lawyer $lawyer
     * @return array
     */
    protected function getCountBookmark(Lawyer $lawyer)
    {
        $countBookmarks = [];
        foreach ($this->users->bookmarkCategories($lawyer) as $category) {
            $countBookmarks[$category->id] = $this->users->bookmarksCount($lawyer, $category->id);
        }
        return $countBookmarks;
    }

    /**
     * Создание закладки.
     * @param  BookmarkCreateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function create(BookmarkCreateRequest $request)
    {
        $question = Question::findOrFail($request->input('question'));

        $this->authorize('bookmark', [Bookmark::class, $question]);

        $id = $request->input('id');
        $category = BookmarkCategory::findOrFail($request->input('category'));
        $user = Auth::user();
        $messages = [];

        if ($id && $category) {
            $bookmark = Bookmark::findOrFail($id);
            $bookmark->category_id = $category->id;
            $bookmark->save();
            $messages['success'] = trans('bookmark.message.bookmark_success_update');
        }
        elseif ($category && $question) {
            $bookmark = new Bookmark;
            $bookmark->category_id = $category->id;
            $bookmark->question_id = $question->id;
            $bookmark->lawyer_id = Auth::user()->lawyer->id; 
            $bookmark->save();
            $messages['success'] = trans('bookmark.message.bookmark_success_create');
        }
        

        return response()->json([
            'targetData' => json_encode(['id' => $bookmark->id, 'question' => $question->id, 'from' => $request->input('from')]),
            'type' => 'create',
            'html' => view('bookmark.popover_categories', [
                            'categories' => $this->users->bookmarkCategories($user->lawyer),
                            'bookmark' => $bookmark,
                            'question' => $question,
                        ])->render(),
            'count' => $this->getCountBookmark($user->lawyer),
            'messages' => $messages,
        ]);
    }

    /**
     * Удаление заклакди.
     * @param  Bookmark $bookmark
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Bookmark $bookmark, Request $request)
    {
        $this->authorize('delete', $bookmark);
        
        $bookmark->delete();
        // Отправка новых данных
        $question = $bookmark->question;
        $user = Auth::user();
        return response()->json([
            'type' => 'delete',
            'targetData' => json_encode(['id' => 0, 'question' => $question->id, 'from' => $request->input('from')]),
            'html' => view('bookmark.popover_categories', [
                            'categories' => $this->users->bookmarkCategories($user->lawyer),
                            'bookmark' => null,
                            'question' => $question,
                        ])->render(),
            'count' => $this->getCountBookmark($user->lawyer),
            'messages' => [
                'success' => trans('bookmark.message.bookmark_success_remove'),
            ],
        ]);
    }

    /**
     * Создание категории.
     * @param  BookmarkCategoryCreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function categoryCreate(BookmarkCategoryCreateRequest $request)
    {
        $this->authorize('category-create', Bookmark::class);

        $user = Auth::user();
        $category = new BookmarkCategory;
        $category->fill($request->all());
        $category->lawyer_id = $user->lawyer->id;
        $category->save();
        // отправка новых данных
        $question = Question::find($request->input('question'));
        $bookmark = Bookmark::find($request->input('bookmark'));
        $type = $request->input('type');
        if ($type == 'popover') {
            $categoriesHtml = view('bookmark.popover_categories', [
                'categories' => $this->users->bookmarkCategories($user->lawyer),
                'bookmark' => $bookmark,
                'question' => $question,
            ])->render();
        }
        else {
            $categoriesHtml = view('bookmark.category_list', [
                'categories' => $this->users->bookmarkCategories($user->lawyer),
                'lawyer' => $user->lawyer,
                'userRepository' => $this->users,
            ])->render();
        }
        return response()->json([
            'type' => $type,
            'html' => $categoriesHtml,
            'messages' => [
                'success' => trans('bookmark.message.bookmark_category_success_create'),
            ],
        ]);
    }

    /**
     * Удаление категории.
     * @param  BookmarkCategory $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function categoryDelete(BookmarkCategory $category)
    {
        $this->authorize('category-modify', [Bookmark::class, $category]);

        $user = Auth::user();
        $category->delete();
        // Обновляем новые данные
        return response()->json([
            'html' => view('bookmark.category_list', [
                            'categories' => $this->users->bookmarkCategories($user->lawyer),
                            'lawyer' => $user->lawyer,
                            'userRepository' => $this->users,
                        ])->render(),
            'messages' => [
                'success' => trans('bookmark.message.bookmark_category_success_delete'),
            ],
        ]);
    }

    /**
     * Обновление категории.
     * @param  BookmarkCategory  $category
     * @param  BookmarkCategoryUpdateRequest    $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function categoryUpdate(BookmarkCategory $category, BookmarkCategoryUpdateRequest $request)
    {
        $this->authorize('category-modify', [Bookmark::class, $category]);

        $user = Auth::user();
        $category->update($request->all());
        // Обновляем новые данные
        return response()->json([
            'html' => view('bookmark.category_list', [
                            'categories' => $this->users->bookmarkCategories($user->lawyer),
                            'lawyer' => $user->lawyer,
                            'userRepository' => $this->users,
                        ])->render(),
            'messages' => [
                'success' => trans('bookmark.message.bookmark_category_success_update'),
            ],
        ]);
    }

}
