<?php

namespace App\Model;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Model;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;

/**
 * @property int $id
 * @property string $file
 * @property string $path
 * @property string $title
 * @property string $type
 * @property int $width
 * @property int $height
 * @property string $description
 * @property array $metadata
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class File extends Model
{
    protected $fillable = ['file', 'path', 'title', 'type', 'width', 'height', 'description', 'metadata'];
    protected $casts = [
        'metadata' => 'array',
    ];
    /** @var Filesystem $filesystem */
    protected $filesystem;
    const dir = 'upload';

    /**
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $adapter = new Local(BASE_DIR . '/' . $this::dir);
        $this->filesystem = new Filesystem($adapter);
    }

    /**
     * @param $file
     * @param array $metadata
     * @return bool|int
     */
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
        } catch (Exception $e) {
            return false;
        }

        if ($this->file) {
            $this->deleteFile();
        }

        $this->title = $file['name'];
        $this->path = $path;
        $this->file = $filename;
        $this->type = $file['type'];
        $this->metadata = $metadata;
        if (strpos($file['type'], 'image/') === 0) {
            if ($size = getimagesize($this->getFile())) {
                $this->width = $size[0];
                $this->height = $size[1];
            }
        }
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
        } catch (Exception $e) {
            return false;
        }
        if ($this->file) {
            $this->deleteFile();
        }

        $this->title = !empty($metadata['name'])
            ? $metadata['name']
            : $filename;
        $this->path = $path;
        $this->file = $filename;
        $this->type = $type;
        $this->metadata = $metadata;
        if (strpos($type, 'image/') === 0) {
            if ($size = getimagesize($this->getFile())) {
                $this->width = $size[0];
                $this->height = $size[1];
            }
        }
        $this->save();

        return $this->id;
    }

    /**
     * @return string
     */
    public function getFile()
    {
        return BASE_DIR . '/' . $this::dir . '/' . $this->path . '/' . $this->file;
    }

    /**
     * @return false|string
     */
    public function getFileContent()
    {
        try {
            return $this->filesystem->read($this->path . '/' . $this->file);
        } catch (Exception $e) {
            return false;
        }
    }


    public function getUrl()
    {
        return getenv('SITE_URL') . $this::dir . '/' . $this->path . '/' . $this->file;
    }


    public function deleteFile()
    {
        try {
            return $this->filesystem->delete($this->path . '/' . $this->file);
        } catch (Exception $e) {
            return false;
        }
    }


    /**
     * @return bool|null
     * @throws Exception
     */
    public function delete()
    {
        $this->deleteFile();

        return parent::delete();
    }

}