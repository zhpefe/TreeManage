laravel-admin extension
======
### laravel-admin 1.8.1
安装
```php
composer require zhpefe/treemanage
```
这个就是继承 MenuController 的一个 Controller。作者 松 写的菜单管理界面很实用。也没啥难度，自己用起来方便而已。
Model 需要是 Tree 数据模型树
```php
class CategoryController extends TreeManageController
{
    protected $model = Category::class;
    protected $title = '新闻类别';
    protected $description = '新闻类别管理';
    protected $route = 'news/category';
}
```
还可以覆盖一些方法：
```php
    public function setForm($form)
    {
        $form->select('parent_id','父类')->options($this->model::selectOptions());
        $form->text('title', '名称')->rules('required');
        return $form;
    }
    public function setEditForm($form)
    {
        return $this->setForm($form);
    }

    public function setTreeStr($branch)
    {
        return "<i class='fa fa-angle-right'></i>&nbsp;<strong>{$branch['title']}</strong>";
    }
```
