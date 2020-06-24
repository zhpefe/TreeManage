<?php

namespace Encore\TreeManage\Http\Controllers;

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
    protected $model = null;
    protected $title = '';
    protected $description = '';
    protected $route = '';

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
                    $column->append((new Box(trans('admin.new'), $form))->style('success'));
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
        return redirect(admin_base_path($this->route) . "/{$id}/edit");
    }

    /**
     * @return \Encore\Admin\Tree
     */
    protected function treeView()
    {
        $model = $this->model;
        $tree = new Tree(new $model());
        $tree->disableCreate();
        $tree->branch(function ($branch) {
            $payload = $this->setTreeStr($branch);
            return $payload;
        });
        return $tree;
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
            ->description(trans('admin.edit'))
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
        $form->select('parent_id',__('parent_id'))->options($this->model::selectOptions());
        $form->text('title', __('title'))->rules('required');
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