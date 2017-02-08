<?php
/**
 * DIE KAVALLERIE GmbH
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@diekavallerie.de so we can send you a copy immediately.
 */
namespace DieKavallerie\ProductImageFix\Model\Catalog;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\ProductRepository as ParentProductRepository;

/**
 * Class ProductRepository
 * @package DieKavallerie\ProductImageFix\Model\Catalog
 */
class ProductRepository extends ParentProductRepository
{
    /**
     * @param ProductInterface $product
     * @param array $mediaGalleryEntries
     * @return $this
     * @throws InputException
     * @throws StateException
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    protected function processMediaGallery(ProductInterface $product, $mediaGalleryEntries)
    {
        $existingMediaGallery = $product->getMediaGallery('images');
        $newEntries = [];
        if (!empty($existingMediaGallery)) {
            $entriesById = [];
            foreach ($mediaGalleryEntries as $entry) {
                if (isset($entry['id'])) {
                    $entry['value_id'] = $entry['id'];
                    $entriesById[$entry['value_id']] = $entry;
                } else {
                    $newEntries[] = $entry;
                }
            }
            foreach ($existingMediaGallery as $key => &$existingEntry) {
                if (isset($entriesById[$existingEntry['value_id']])) {
                    $updatedEntry = $entriesById[$existingEntry['value_id']];
                    $existingMediaGallery[$key] = array_merge($existingEntry, $updatedEntry);
                } else {
                    //set the removed flag
                    $existingEntry['removed'] = true;
                }
            }
            $product->setData('media_gallery', ["images" => $existingMediaGallery]);
        } else {
            $newEntries = $mediaGalleryEntries;
        }

        $this->getMediaGalleryProcessor()->clearMediaAttribute($product, array_keys($product->getMediaAttributes()));
        $images = $product->getMediaGallery('images');
        if ($images) {
            foreach ($images as $image) {
                if (!isset($image['removed']) && !empty($image['types'])) {
                    $this->getMediaGalleryProcessor()->setMediaAttribute($product, $image['types'], $image['file']);
                }
            }
        }

        // The processing of already ( newly ) set images is very buggy. If we had an correctly set media_gallery entry in product it will be removed and reset in save method.
        // But here it seems as only base 64 encoded images are wanted so we need to re set the ( already ) correct media_gallery array.
        $mediaGalleryData['images'] = [];
        foreach ($newEntries as $newEntry) {

            if (isset($newEntry['file'])) {
                $this->getMediaGalleryProcessor()->setMediaAttribute($product, $newEntry['types'], $newEntry['file']);
                $mediaGalleryData['images'][] = $newEntry;
                continue;
            }

            if (!isset($newEntry['content'])) {
                throw new InputException(__('The image content is not valid.'));
            }
            /** @var ImageContentInterface $contentDataObject */
            $contentDataObject = $this->contentFactory->create()
                ->setName($newEntry['content'][ImageContentInterface::NAME])
                ->setBase64EncodedData($newEntry['content'][ImageContentInterface::BASE64_ENCODED_DATA])
                ->setType($newEntry['content'][ImageContentInterface::TYPE]);
            $newEntry['content'] = $contentDataObject;
            $this->processNewMediaGalleryEntry($product, $newEntry);
        }

        // Just set the missing data again
        if (count($mediaGalleryData['images']) > 0) {
            $product->setData($this->getMediaGalleryProcessor()->getAttribute()->getAttributeCode(), $mediaGalleryData);
        }

        return $this;
    }

    /**
     * @return Product\Gallery\Processor
     */
    private function getMediaGalleryProcessor()
    {
        if (null === $this->mediaGalleryProcessor) {
            $this->mediaGalleryProcessor = \Magento\Framework\App\ObjectManager::getInstance()
                ->get('Magento\Catalog\Model\Product\Gallery\Processor');
        }
        return $this->mediaGalleryProcessor;
    }
}
