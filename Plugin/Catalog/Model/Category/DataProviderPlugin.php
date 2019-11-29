<?php
namespace SR\CategoryImage\Plugin\Catalog\Model\Category;

use Magento\Framework\Registry;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Catalog\Api\Data\CategoryInterface;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Catalog\Model\Category\DataProvider as CategoryDataProvider;
use Magento\Store\Model\Store;
use SR\CategoryImage\Controller\Adminhtml\Category\Mobile\Upload as MobileUpload;
use SR\CategoryImage\Controller\Adminhtml\Category\Thumbnail\Upload as ThumbnailUpload;
use \SR\CategoryImage\Helper\Category as CategoryHelper;

class DataProviderPlugin
{

    /**
     * Registry
     *
     * @var Registry
     */
    protected $registry;

    /**
     * Request
     *
     * @var RequestInterface
     */
    protected $request;

    /**
     * Category Factory
     *
     * @var CategoryFactory
     */
    private $categoryFactory;

    /**
     * Category Helper
     *
     * @var CategoryHelper
     */
    private $categoryHelper;

    /**
     * Request Field Name
     *
     * @var string
     */
    private $requestFieldName = 'id';

    /**
     * Request Scope Field Name
     *
     * @var string
     */
    private $requestScopeFieldName = 'store';

    /**
     * Constructor
     *
     * @param Registry $registry
     * @param RequestInterface $request
     * @param CategoryFactory $categoryFactory
     * @param CategoryHelper $categoryHelper
     */
    public function __construct(
        Registry $registry,
        RequestInterface $request,
        CategoryFactory $categoryFactory,
        CategoryHelper $categoryHelper
    ) {
        $this->registry = $registry;
        $this->request = $request;
        $this->categoryFactory = $categoryFactory;
        $this->categoryHelper = $categoryHelper;
    }

    /**
     * Add addtional data for additional image types
     *
     * @return mixed
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function afterGetData(CategoryDataProvider $subject, $result)
    {
        $category = $this->getCurrentCategory();

        if ($category && $category->getId()) {
            $categoryData = $result[$category->getId()];

            foreach ($this->getAdditionalImageTypes() as $imageType) {
                if (isset($categoryData[$imageType])) {
                    unset($categoryData[$imageType]);
                    $categoryData[$imageType][0]['name'] = $category->getData($imageType);
                    $categoryData[$imageType][0]['url'] = $this->categoryHelper->getImageUrl($category->getData($imageType));
                }
            }

            $result[$category->getId()] = $categoryData;
        }

        return $result;
    }

    /**
     * Get current category
     *
     * @return CategoryInterface
     * @throws NoSuchEntityException
     */
    private function getCurrentCategory()
    {
        $category = $this->registry->registry('category');

        if ($category) {
            return $category;
        }

        $requestId = $this->request->getParam($this->requestFieldName);
        $requestScope = $this->request->getParam($this->requestScopeFieldName, Store::DEFAULT_STORE_ID);

        if ($requestId) {
            $category = $this->categoryFactory->create();
            $category->setStoreId($requestScope);
            $category->load($requestId);
            if (!$category->getId()) {
                throw NoSuchEntityException::singleField('id', $requestId);
            }
        }

        return $category;
    }

    /**
     * Get additional images types
     *
     * @return array
     */
    private function getAdditionalImageTypes()
    {
        return [
            MobileUpload::CATEGORY_ATTRIBUTE_IMAGE,
            ThumbnailUpload::CATEGORY_ATTRIBUTE_IMAGE
        ];
    }
}