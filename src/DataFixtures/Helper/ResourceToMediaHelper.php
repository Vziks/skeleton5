<?php

namespace App\DataFixtures\Helper;

use App\Entity\SonataMediaMedia;
use Sonata\MediaBundle\Model\MediaManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ResourceToMediaHelper
{
    public static function fromUrl(
        string $url,
        MediaManagerInterface $mediaManager,
        string $context = 'default',
        string $provider = 'sonata.media.provider.image'
    ): SonataMediaMedia {
        $bin = file_get_contents($url);

        $ext = explode('.', $url);
        $ext = $ext[\count($ext) - 1];

        $tmp = sys_get_temp_dir() . '/' . md5($url) . '.' . $ext;
        file_put_contents($tmp, $bin);

        $file = new UploadedFile($tmp, $tmp);

        $media = (new SonataMediaMedia());
        $media->setBinaryContent($file);
        $media->setContext($context);
        $media->setProviderName($provider);

        $mediaManager->save($media);

        return $media;
    }

    public static function fromFile(
        string $filename,
        MediaManagerInterface $mediaManager,
        string $context = 'default',
        string $provider = 'sonata.media.provider.image'
    ): SonataMediaMedia {
        $file = new UploadedFile($filename, $filename);

        $media = new SonataMediaMedia();
        $media->setBinaryContent($file);
        $media->setContext($context);
        $media->setProviderName($provider);

        $mediaManager->save($media);

        return $media;
    }
}
