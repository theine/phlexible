<?php

/*
 * This file is part of the phlexible package.
 *
 * (c) Stephan Wentz <sw@brainbits.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phlexible\Bundle\MediaManagerBundle\Portlet;

use Phlexible\Bundle\DashboardBundle\Portlet\Portlet;
use Phlexible\Component\MediaCache\Model\CacheManagerInterface;
use Phlexible\Component\Volume\VolumeManager;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Latest files portlet.
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class LatestFilesPortlet extends Portlet
{
    /**
     * @var VolumeManager
     */
    private $volumeManager;

    /**
     * @var CacheManagerInterface
     */
    private $cacheManager;

    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    /**
     * @var string
     */
    private $style;

    /**
     * @var int
     */
    private $numItems;

    /**
     * @param TranslatorInterface           $translator
     * @param VolumeManager                 $volumeManager
     * @param CacheManagerInterface         $cacheManager
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param string                        $style
     * @param int                           $numItems
     */
    public function __construct(
        TranslatorInterface $translator,
        VolumeManager $volumeManager,
        CacheManagerInterface $cacheManager,
        AuthorizationCheckerInterface $authorizationChecker,
        $style,
        $numItems)
    {
        $this
            ->setId('mediamanager-portlet')
            ->setTitle($translator->trans('mediamanager.latest_files', [], 'gui'))
            ->setClass('Phlexible.mediamanager.portlet.LatestFiles')
            ->setIconClass('p-mediamanager-portlet-icon')
            ->setRole('ROLE_MEDIA');

        $this->volumeManager = $volumeManager;
        $this->cacheManager = $cacheManager;
        $this->authorizationChecker = $authorizationChecker;
        $this->style = $style;
        $this->numItems = $numItems;
    }

    /**
     * Return settings.
     *
     * @return array
     */
    public function getSettings()
    {
        return [
            'style' => $this->style,
        ];
    }

    /**
     * Return Portlet data.
     *
     * @return array
     */
    public function getData()
    {
        $data = [];

        try {
            $volumes = $this->volumeManager->all();
            $volume = current($volumes);
            $files = $volume->findLatestFiles($this->numItems);

            foreach ($files as $file) {
                $folder = $volume->findFolder($file->getFolderId());

                if (!$this->authorizationChecker->isGranted('FILE_READ', $folder)) {
                    continue;
                }

                $cacheItems = $this->cacheManager->findByFile($file);
                $cacheStatus = [];
                foreach ($cacheItems as $cacheItem) {
                    $cacheStatus[$cacheItem->getTemplateKey()] =
                        $cacheItem->getCacheStatus().';'.$cacheItem->getCreatedAt()->format('YmdHis');
                }

                $data[] = [
                    'id' => sprintf('%s___%s', $file->getId(), $file->getVersion()),
                    'file_id' => $file->getId(),
                    'file_version' => $file->getVersion(),
                    'folder_id' => $file->getFolderId(),
                    'folder_path' => $folder->getIdPath(),
                    'document_type_key' => strtolower($file->getMediaType()),
                    'time' => $file->getCreatedAt()->format('U'),
                    'title' => $file->getName(),
                    'cache' => $cacheStatus,
                ];
            }
        } catch (\Exception $e) {
            $data = [];
        }

        return $data;
    }
}
