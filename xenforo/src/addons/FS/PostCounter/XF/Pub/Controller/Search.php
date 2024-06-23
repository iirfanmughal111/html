<?php

namespace FS\PostCounter\XF\Pub\Controller;

class Search extends XFCP_Search
{
  public function actionThreadsOfMember()
  {
    $userId = $this->filter('user_id', 'uint');
    $user = $this->assertRecordExists('XF:User', $userId, null, 'requested_member_not_found');

    $searcher = $this->app->search();
    $query = $searcher->getQuery();

    $query->inType('thread')
      ->byUserId($user->user_id)
      ->orderedBy('date');

    $node = $this->filter('node', 'int');

    $query->withMetadata('node', $node);

    return $this->runSearch(
      $query,
      [
        'users' => $user->username,
        'nodes' => [$node]
      ],
      false
    );
  }
}
