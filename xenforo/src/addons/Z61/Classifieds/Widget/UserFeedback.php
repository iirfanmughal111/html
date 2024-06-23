<?php

namespace Z61\Classifieds\Widget;


use XF\Widget\AbstractWidget;
use Z61\Classifieds\XF\Entity\User;

class UserFeedback extends AbstractWidget
{
    protected $defaultOptions = [
        'limit' => 5,
    ];

    protected function getDefaultTemplateParams($context)
    {
        $params = parent::getDefaultTemplateParams($context);

        return $params;
    }

    public function render()
    {
        /** @var \Z61\Classifieds\XF\Entity\User $visitor */
        $visitor = \XF::visitor();
        if (!$visitor->canViewClassifieds() ||  !$visitor->canViewClassifiedsFeedback())
        {
            return '';
        }

        /** @var \Z61\Classifieds\Entity\Listing $listing */
        $listing = $this->contextParams['listing'];

        $limit = $this->options['limit'];

        /** @var User $user */
        $user = $listing->User;

        if (!$user)
        {
            return '';
        }

        $title = $this->getTitle() ?: \XF::phrase('z61_classifieds_user_feedback');

        $feedbackFinder = $user->getRelationFinder('ClassifiedsFeedback')
            ->order('feedback_date', 'desc');
        $feedback = $feedbackFinder->fetch();

        $feedback = $feedback->filterViewable();

        $viewParams = [
            'user' => $user,
            'title' => $title,
            'feedbackItems' => $feedback,
        ];
        return $this->renderer('z61_classifieds_widget_user_feedback', $viewParams);
    }

    public function verifyOptions(\XF\Http\Request $request, array &$options, &$error = null)
    {
        $options = $request->filter([
            'limit' => 'uint',
        ]);

        if ($options['limit'] < 1)
        {
            $options['limit'] = 1;
        }
    }

}