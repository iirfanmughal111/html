<?php

namespace Z61\Classifieds\Widget;

use XF\Widget\AbstractWidget;

class RandomListing extends AbstractWidget
{
    protected $defaultOptions = [
        'category_id' => 0
    ];

    protected function getDefaultTemplateParams($context)
    {
        $params = parent::getDefaultTemplateParams($context);
        if ($context == 'options')
        {
            $categoryRepo = $this->app->repository('Z61\Classifieds:Category');
            $params['categoryTree'] = $categoryRepo->createCategoryTree($categoryRepo->findCategoryList()->fetch());
        }
        return $params;
    }

    public function render()
    {
        // TODO: Implement render() method.
    }

    public function verifyOptions(\XF\Http\Request $request, array &$options, &$error = null)
    {
        $options = $request->filter([
            'category_id' => 'uint'
        ]);

        if ($options['category_id'] > 0)
        {
            if (!$this->findOne('Z61\Classifieds:Category', ['category_id' => $options['category_id']]))
            {
                return false;
            }
        }

        return true;
    }

}