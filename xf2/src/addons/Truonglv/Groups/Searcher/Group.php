<?php

namespace Truonglv\Groups\Searcher;

use XF;
use Truonglv\Groups\App;
use XF\Mvc\Entity\Finder;
use XF\Searcher\AbstractSearcher;

class Group extends AbstractSearcher
{
    /**
     * @var string[]
     */
    protected $allowedRelations = ['Category'];
    /**
     * @var array
     */
    protected $formats = [
        'name' => 'like',
        'owner_username' => 'like',
        'created_date' => 'date',
    ];

    /**
     * @var array
     */
    protected $order = [['created_date', 'desc']];

    /**
     * @return string
     */
    protected function getEntityType()
    {
        return 'Truonglv\Groups:Group';
    }

    /**
     * @return array
     */
    protected function getDefaultOrderOptions()
    {
        return [
            // @phpstan-ignore-next-line
            'member_count' => XF::phrase('tlg_sort_member_count'),
            // @phpstan-ignore-next-line
            'event_count' => XF::phrase('tlg_sort_event_count'),
            // @phpstan-ignore-next-line
            'view_count' => XF::phrase('tlg_sort_view_count'),
            'created_date' => XF::phrase('tlg_submission_date')
        ];
    }

    /**
     * @param mixed $key
     * @param mixed $value
     * @param mixed $column
     * @param mixed $format
     * @param mixed $relation
     * @return null|bool
     */
    protected function validateSpecialCriteriaValueAfter($key, & $value, $column, $format, $relation)
    {
        if ($key == 'category_id') {
            if (
                $value == 0
                || (is_array($value) && isset($value[0]) && $value[0] == 0)
            ) {
                return false;
            }
        }

        return null;
    }

    /**
     * @param Finder $finder
     * @param mixed $key
     * @param mixed $value
     * @param mixed $column
     * @param mixed $format
     * @param mixed $relation
     * @return bool
     */
    protected function applySpecialCriteriaValue(Finder $finder, $key, $value, $column, $format, $relation)
    {
        if ($key == 'category_id') {
            if (!is_array($value)) {
                $value = [$value];
            }

            if (isset($value['search_type']) && $value['search_type'] === 'exclude') {
                $matchInForums = false;
            } else {
                $matchInForums = true;
            }
            unset($value['search_type']);

            if (count($value) > 0) {
                $finder->where('category_id', $matchInForums ? '=' : '<>', $value);
            }

            return true;
        }

        if ($key === 'privacy') {
            $finder->where('privacy', $value);
        }

        if ($key == 'group_field') {
            $exactMatchFields = is_array($value['exact']) && count($value['exact']) > 0 ? $value['exact'] : [];
            $customFields = array_merge($value, $exactMatchFields);
            unset($customFields['exact']);

            foreach ($customFields as $fieldId => $fieldValue) {
                if ($fieldValue === '' || (is_array($fieldValue) && count($fieldValue) === 0)) {
                    continue;
                }

                $finder->with('CustomFields|' . $fieldId);
                $isExact = isset($exactMatchFields[$fieldId]) && strlen($exactMatchFields[$fieldId]) > 0;
                $conditions = [];
                $valueAsArr = (array) $fieldValue;

                foreach ($valueAsArr as $possible) {
                    $columnName = 'CustomFields|' . $fieldId . '.field_value';
                    if ($isExact) {
                        $conditions[] = [$columnName, '=', $possible];
                    } else {
                        $conditions[] = [$columnName, 'LIKE', $finder->escapeLike($possible, '%?%')];
                    }
                }

                if (count($conditions) > 0) {
                    $finder->whereOr($conditions);
                }
            }
        }

        if ($key == 'tags') {
            /** @var \XF\Repository\Tag $tagRepo */
            $tagRepo = $this->em->getRepository('XF:Tag');

            $tags = $tagRepo->splitTagList($value);
            if ($tags) {
                $validTags = $tagRepo->getTags($tags, $notFound);
                if ($notFound) {
                    // if they entered an unknown tag, we don't want to ignore it, so we need to force no results
                    $finder->whereImpossible();
                } else {
                    foreach (array_keys($validTags) as $tagId) {
                        $finder->with('Tags|' . $tagId, true);
                    }
                }

                return true;
            }
        }

        return false;
    }

    /**
     * @return array
     */
    public function getFormData()
    {
        return [
            'categories' => App::categoryRepo()->getCategoryOptionsData(false),
        ];
    }
}
