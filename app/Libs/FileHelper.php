<?php 

namespace App\Libs;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Storage;
use Symfony\Component\HttpFoundation\File\File;

use App\Helper;


trait FileHelper {

    /**
     * Получение и создание деректории.
     * @param  string $directory
     * @param  boolean $make
     * @return string
     */
    protected function createDirsToFile($directory, $make = true)
    {
        $dirPath = $directory . DIRECTORY_SEPARATOR . date('Y' . DIRECTORY_SEPARATOR . 'm');
        if ($make && !Storage::exists($dirPath)) {
            Storage::makeDirectory($dirPath, 0777, true, true);
        }
        return $dirPath;
    }

    /**
     * Получение уникального имени, если файл уже есть.
     * @param  string $directory
     * @param  string $fileName
     * @return string
     */
    protected function getUniqueFileName($directory, $file)
    {
        $fileinfo = pathinfo($file);
        $fileName = Str::slug($fileinfo['filename'], '_');
        $numAdd = 0;
        $newFileName = $fileName;
        while (Storage::exists($directory . DIRECTORY_SEPARATOR . $newFileName . '.' . $fileinfo['extension'])) {
            $numAdd+=1;
            $newFileName = $fileName . '_' . $numAdd;
        }
        return $newFileName . '.' . $fileinfo['extension'];
    }

    protected function removeRootPath($path, $to = 'app/')
    {
        $root = storage_path($to);
        return (strpos($path, $root) === 0 ? substr($path, strlen($root)) : $path);
    }

    /**
     * Сохранение файла типа UploadedFile из запроса.
     * @param  UploadedFile $file
     * @return string|null
     */
    protected function saveRequestFile(UploadedFile $file, $directory = 'public')
    {
        
        $dirPath = $this->createDirsToFile($directory);
        $newFileName = $this->getUniqueFileName($dirPath, $file->getClientOriginalName());
        try {
            $newFile = $file->move(storage_path('app') . DIRECTORY_SEPARATOR . $dirPath, $newFileName);
            $newFilePath = $this->removeRootPath($newFile->getPath()) . DIRECTORY_SEPARATOR . $newFile->getFilename();
        } catch (FileException $error) {
            $newFilePath = null;
        }
        return $newFilePath;
    }

    /**
     * Копирование существующего файла.
     * @param  string $filepath
     * @param  string $directory
     * @return string|null
     */
    protected function saveExistFile($filepath, $directory = 'public')
    {
        $dirPath = $this->createDirsToFile($directory);
        $newFileName = $this->getUniqueFileName($dirPath, $filepath);
        if (Storage::exists($filepath)) {
            $newFilePath = $dirPath . DIRECTORY_SEPARATOR . $newFileName;
            Storage::copy($filepath, $newFilePath);
        }
        else {
            $newFilePath = null;
        }
        return $newFilePath;
    }
}