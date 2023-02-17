<?php

namespace SR\CategoryImage\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

class Category extends AbstractHelper
{


    /**
     * @return array
     */
    public function getAdditionalImageTypes()
    {
        return array('thumbnail');
    }

    /**
     * Retrieve image URL
     * @param $image
     * @return string
     */
    public function getImageUrl($image)
    {
        $url = false;
        //$image = $this->getImage();
        if ($image) {
            if (is_string($image)) {
                $url = $this->_urlBuilder->getBaseUrl(
                        ['_type' => \Magento\Framework\UrlInterface::URL_TYPE_WEB]
                    ) .  $image;
            } else {
                throw new \Magento\Framework\Exception\LocalizedException(
                    __('Something went wrong while getting the image url.')
                );
            }
        }
        return $url;
    }

}