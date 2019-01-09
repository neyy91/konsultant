<?php

use Illuminate\Database\Seeder;

use App\Libs\FileHelper;
use App\Models\Question;
use App\Models\Document;
use App\Models\Call;
use App\Models\Answer;
use App\Models\File;
use App\Models\Clarify;
use App\Models\User;
use App\Models\Lawyer;

class FileSeeder extends Seeder
{
    use FileHelper;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $docs = collect(Storage::files('test/files')); // Тестовые документы

        // Вопросы
        $questions = Question::where('status', Question::STATUS_IN_WORK)->inRandomOrder()->take(15)->get();
        $directory = config('site.question.file.directory', 'private/questions');
        Storage::deleteDirectory($directory);
        foreach ($questions as $question) {
            $file = $this->saveExistFile($docs->random(), $directory);
            $fileModel = new File(['file' => $file, 'field' => 'file']);
            $fileModel->user()->associate($question->user);
            $question->file()->save($fileModel);
        }

        // Документы
        $documents = Document::where('status', Document::STATUS_IN_WORK)->inRandomOrder()->take(15)->get();
        $directory = config('site.document.file.directory', 'private/documents');
        Storage::deleteDirectory($directory);
        foreach ($documents as $document) {
            $file = $this->saveExistFile($docs->random(), $directory);
            $fileModel = new File(['file' => $file, 'field' => 'file']);
            $fileModel->user()->associate($document->user);
            $document->file()->save($fileModel);
        }

        // Звонки
        $calls = Call::where('status', Call::STATUS_IN_WORK)->inRandomOrder()->take(15)->get();
        $directory = config('site.call.file.directory', 'private/calls');
        Storage::deleteDirectory($directory);
        foreach ($calls as $call) {
            $file = $this->saveExistFile($docs->random(), $directory);
            $fileModel = new File(['file' => $file, 'field' => 'file']);
            $fileModel->user()->associate($call->user);
            $call->file()->save($fileModel);
        }

        // Ответы
        $answers = Answer::inRandomOrder()->take(25)->get();
        $directory = config('site.answer.file.directory', 'private/answers');
        Storage::deleteDirectory($directory);
        foreach ($answers as $answer) {
            $file = new File(['file' => $this->saveExistFile($docs->random(), $directory), 'field' => 'file']);
            $file->parent()->associate($answer->to);
            $file->user()->associate($answer->from_type == Lawyer::MORPH_NAME ? $answer->from->user : $answer->from);
            $answer->file()->save($file);
        }

        // Уточнения
        $clarifies = Clarify::inRandomOrder()->take(30)->get();
        $directory = config('site.clarify.file.directory', 'private/clarifies');
        Storage::deleteDirectory($directory);
        foreach ($clarifies as $clarify) {
            $file = new File(['file' => $this->saveExistFile($docs->random(), $directory), 'field' => 'file']);
            $file->parent()->associate($clarify->to_type == Answer::MORPH_NAME ? $clarify->to->to : $clarify->to);
            if ($clarify->to_type == Answer::MORPH_NAME) {
                $user = $clarify->to->from_type == Lawyer::MORPH_NAME ? $answer->from->user : $answer->from;
            }
            else {
                $user = $clarify->to->user;
            }
            $file->user()->associate($user);
            $clarify->file()->save($file);
        }

        // Фото пользователей.
        $photos = [];
        foreach (User::getGenders() as $key => $label) {
            $photos[$key] = collect(Storage::files('test/avatars/' . $key));
        }
        $users = User::inRandomOrder()->take(10)->get();
        $directory = 'public/test/avatars';
        Storage::deleteDirectory($directory);
        foreach ($users as $user) {
            $file = $this->saveExistFile($photos[$user->gender]->random(), $directory);
            $fileModel = new File(['file' => $file, 'field' => 'photo']);
            $fileModel->user()->associate($user);
            $user->photo()->save($fileModel);
        }

        // Меняем доступ
        $this->chmod('private');
        $this->chmod('public/test');

    }

    /**
     * Смена директории и файлов для папки.
     * @param  string $dirname
     */
    protected function chmod($dirname)
    {
        $objects = [
            [
                'mode' => 0777,
                'items' => Storage::allDirectories($dirname),
            ],
            [
                'mode' => 0666,
                'items' => Storage::allFiles($dirname),
            ]
        ];
        foreach ($objects as $object) {
            foreach ($object['items'] as $item) {
                $itemPath = storage_path('app') . DIRECTORY_SEPARATOR . $item;
                try {
                    chmod($itemPath, $object['mode']);
                } catch (Exception $error) {
                    print $error->getMessage() . '. ' . $itemPath . "\n";
                }
            }
        }
    }
}
