<?php

namespace Encore\NewTree\Http\Controllers;

use Encore\Admin\Controllers\MenuController;
use Encore\Admin\Form;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Tree;
use Encore\Admin\Widgets\Box;
use Illuminate\Routing\Controller;

class TreeManageController extends MenuController
{
    public $model = null;
    public $title = '';
    public $description = '';
    public $route = '';

    public function index(Content $content)
    {
        if (!$this->model) {
            return $content->body('错误的Model');
        }
        return $content
            ->title($this->title)
            ->description($this->description)
            ->row(function (Row $row) {
                $row->column(6, $this->treeView()->render());

                $row->column(6, function (Column $column) {
                    $form = new \Encore\Admin\Widgets\Form();
                    $form = $this->setEditForm($form);
                    $form->action(admin_base_path($this->route));
                    $form->hidden('_token')->default(csrf_token());
                    $column->append((new Box('新增', $form))->style('success'));
                });
            });
    }

    /**
     * Redirect to edit page.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        return redirect(admin_base_path($route) . "/{$id}/edit");
    }

    /**
     * @return \Encore\Admin\Tree
     */
    protected function treeView()
    {
        $menuModel = $this->model;

        return $menuModel::tree(function (Tree $tree) {
            $tree->disableCreate();
            $tree->branch(function ($branch) {
                return $this->setTreeStr($branch);
            });
        });
    }

    /**
     * Edit interface.
     *
     * @param string  $id
     * @param Content $content
     *
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->title($this->title)
            ->description('编辑 ' . $this->description)
            ->row($this->form()->edit($id));
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    public function form()
    {
        $menuModel = $this->model;

        $form = new Form(new $menuModel());
        $form->display('id', 'ID');
        $form = $this->setForm($form);
        return $form;
    }

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
}