# Magento 2 Category Image Module

#### Purpose ####
 
Creating a new category EAV attribute is not simple as creating a new product EAV attribute.   

This module comes to answer this complexity by allowing the developer to follow a few simple steps 
in order to create new category images.
 
#### How to add new category image ? ####

The module already creates one image field called **Thumbnail**.

If you need to additional image fields follow these steps:

1. Create an upgrade data script to create the new category image attribute.
2. Create new upload class for the new image attribute by copy-pasting the file **Controller/Adminhtml/Category/Thumbnail/Upload.php**. 
Change the class name and the following line:
```PHP
  $result = $this->imageUploader->saveFileToTmpDir('{image-attribute-code}');
```
3. Add the new fields to the admin html category form at **view/adminhtml/ui_component/category_form.xml** and change:
the field name, the label and the uploader Url
```XML
 <field name="{image-attribute-code}">
            <argument name="data" xsi:type="array">
                <item name="config" xsi:type="array">
                    <item name="dataType" xsi:type="string">string</item>
                    <item name="source" xsi:type="string">category</item>
                    <item name="label" xsi:type="string" translate="true">{Image Label}</item>
                    <item name="visible" xsi:type="boolean">true</item>
                    <item name="formElement" xsi:type="string">fileUploader</item>
                    <item name="elementTmpl" xsi:type="string">ui/form/element/uploader/uploader</item>
                    <item name="previewTmpl" xsi:type="string">Magento_Catalog/image-preview</item>
                    <item name="required" xsi:type="boolean">false</item>
                    <item name="sortOrder" xsi:type="number">41</item>
                    <item name="uploaderConfig" xsi:type="array">
                        <item name="url" xsi:type="url" path="categoryimage/category_thumbnail/{upload-class-file-name}"/>
                    </item>
                </item>
            </argument>
        </field>
```
4. Add the new image attribute code to the helper class **Helper/Category.php** line 16
```PHP
public function getAdditionalImageTypes()
    {
        return array('thumbnail', '{image-attribute-code}');
    }
```
5. Repeat step 4 in the Controller class **Controller/Adminhtml/Category/Save.php** line 18
```PHP
protected function getAdditionalImages() {
        return array('thumbnail', '{image-attribute-code}');
}
```

If everything went well you should be able to see the new field on the category screen under the **Content** group. 
You should also be able to upload, save and delete the image file successfully. 


#### How to show the new image on frontend ? ####
The module comes with a block that can print any new image field on the category page frontend. 
Just add the following XML block under the container/block you would like the image to appear. 
```XML
 <block class="SR\CategoryImage\Block\Image" name="sr.category.image" template="SR_CategoryImage::image.phtml">
    <arguments>
        <argument name="image_code" xsi:type="string">{image-attribute-code}</argument>
        <argument name="css_class" xsi:type="string">{div-css-class}</argument>
    </arguments>
</block>
```
The above block can print category images ONLY on category pages cause it assumes there is already stored category model in core registry. 
If you need to print the image on other pages use the following code snippet.
```PHP
$category = 'load a category model class here'; // the decision how to load category model object is up to you.
$helper    = $this->helper('SR\CategoryImage\Helper\Category');
$imageUrl = $helper->getImageUrl($category->getData('image-attribute-code'));
```


 

