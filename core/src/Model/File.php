<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;

/**
 * @property int $id
 * @property string $file
 * @property string $path
 * @property string $title
 * @property array $preview
 * @property string $type
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 *
 */
class File extends Model
{
    public $timestamps = true;
    protected $fillable = ['file', 'path', 'title', 'preview', 'type', 'description'];
    protected $casts = [
        'preview' => 'array',
    ];
    /** @var Filesystem $filesystem */
    protected $filesystem;
    const dir = 'upload';


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $adapter = new Local(BASE_DIR . '/' . $this::dir);
        $this->filesystem = new Filesystem($adapter);
    }


    public function uploadFile($file, array $metadata = [])
    {
        $stream = fopen($file['tmp_name'], 'r+');

        $ext = explode('/', $file['type'])[1];
        if ($ext == 'jpeg') {
            $ext = 'jpg';
        }
        $filename = bin2hex(openssl_random_pseudo_bytes(12)) . '.' . $ext;
        $path = implode('/', [$filename[0], $filename[1], $filename[2]]);

        try {
            $this->filesystem->writeStream($path . '/' . $filename, $stream);
            if (is_resource($stream)) {
                fclose($stream);
            }
        } catch (\Exception $e) {
            return false;
        }

        if ($this->file) {
            $this->deleteFile();
        }

        $this->title = $file['name'];
        $this->path = $path;
        $this->file = $filename;
        $this->type = $file['type'];
        $this->save();

        return $this->id;
    }


    /**
     * @param string $file
     * @param array $metadata
     *
     * @return bool|int
     */
    public function uploadBase64($file, array $metadata = [])
    {
        $file = explode(',', $file);
        $type = str_replace(['data:', ';base64'], '', $file[0]);
        $ext = explode('/', $type)[1];
        if ($ext == 'jpeg') {
            $ext = 'jpg';
        }
        $filename = bin2hex(openssl_random_pseudo_bytes(12)) . '.' . $ext;
        $path = implode('/', [$filename[0], $filename[1], $filename[2]]);

        try {
            $this->filesystem->write($path . '/' . $filename, base64_decode($file[1]));
        } catch (\Exception $e) {
            return false;
        }

        if ($this->file) {
            $this->deleteFile();
        }

        $this->title = $filename;
        $this->path = $path;
        $this->file = $filename;
        $this->type = $type;
        $this->save();

        return $this->id;
    }


    public function getFile()
    {
        return BASE_DIR . '/' . $this::dir . '/' . $this->path . '/' . $this->file;
    }


    public function getUrl()
    {
        return getenv('SITE_URL') . $this::dir . '/' . $this->path . '/' . $this->file;
    }


    public function deleteFile()
    {
        try {
            return $this->filesystem->delete($this->path . '/' . $this->file);
        } catch (\Exception $e) {
            return false;
        }
    }


    /**
     * @return bool|null
     * @throws \Exception
     */
    public function delete()
    {
        $this->deleteFile();

        return parent::delete();
    }

}