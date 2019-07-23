<?php

namespace Encore\TreeManage;

use Illuminate\Support\ServiceProvider;

class TreeManageServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot(TreeManage $extension)
    {
        if (! TreeManage::boot()) {
            return ;
        }
    }
}