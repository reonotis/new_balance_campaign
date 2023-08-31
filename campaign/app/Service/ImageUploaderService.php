<?php

namespace App\Service;

use Illuminate\Support\Facades\{Log};
use Illuminate\Http\UploadedFile;
use InterventionImage;
use Exception;

class ImageUploaderService
{
    private array $_fileExtension = ['jpg', 'jpeg', 'png'];
    private string $_resize_maxWidth = '400';

    function __construct()
    {
    }

    /**
     * 画像のバリデーションを確認してアップロードする
     * @param UploadedFile $file
     * @param string $dirName
     * @return string
     * @throws Exception
     */
    public function imgCheckAndUpload(UploadedFile $file, string $dirName): string
    {
        Log::info('imgCheckAndUpload');

        // 登録可能な拡張子か確認して取得する
        $extension = $this->checkFileExtension($file);

        // ファイル名の作成 => TO_ {日時} . {拡張子}
        $fileName = sprintf(
            '%s_%s.%s',
            $dirName,
            time(),
            $extension
        );

        // 指定されたディレクトリが存在するか確認
        $this->makeDirectory($dirName);
        $this->makeDirectory($dirName . '/resize/');
        // 画像を保存する
        $this->imgStore($file, 'public/' . $dirName, $fileName);
        return $fileName;
    }

    /**
     * 渡されたファイルが登録可能な拡張子か確認するしてOKなら拡張子を返す
     * @param UploadedFile $file
     * @return string
     * @throws Exception
     */
    public function checkFileExtension(UploadedFile $file): string
    {
        Log::info('checkFileExtension');
        if(!$file)throw new Exception("画像が選択されていません");

        // 渡された拡張子を取得
        $extension = $file->extension();
        if(! in_array($extension, $this->_fileExtension)){
            $fileExtension = json_encode($this->_fileExtension);
            throw new Exception("登録できる画像の拡張子は". $fileExtension ."のみです。");
        }
        return $extension;
    }

    /**
     * ファイルを保存する
     * @param UploadedFile $file
     * @param string $dir
     * @param string $FileName
     * @throws Exception
     */
    public function imgStore(UploadedFile $file, string $dir, string $FileName)
    {
        Log::info('imgStore');
        // 画像を保存する
        $file->storeAs($dir, $FileName);

        // リサイズして保存する
        $resizeImg = InterventionImage::make($file)
            ->resize($this->_resize_maxWidth, null, function ($constraint) {
                $constraint->aspectRatio();
            })
            ->orientate()
            ->save(storage_path('app/'. $dir. '/resize/') . $FileName);
    }

    /**
     * ディレクトリが無ければ作成する
     * @param string $directoryName
     */
    public function makeDirectory(string $directoryName)
    {
        Log::info('makeDirectory');
        $dir = storage_path('app/public/'. $directoryName);
        if(!file_exists($dir)){
            // ディレクトリが無ければ作成する
            if(mkdir('storage/app/public/'. $directoryName, 0777)){
                // 作成したディレクトリのパーミッションを確実に変更
                chmod('storage/app/public/'. $directoryName, 0777);
            }
        }
    }

}
