laravel-admin extension
======
这个就是继承 MenuController 的一个 Controller。作者 松 写的已经挺好了，所以 Tree 结构的Menu管理页面
看起来挺好的。也没啥难度，自己用起来方便而已。
Model 需要是 Tree 数据模型树
```php
calss CategoryController extends TreeManageController
    public $model = Category::class;
    public $title = '新闻类别';
    public $description = '新闻类别管理';
    public $route = 'news/category';
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