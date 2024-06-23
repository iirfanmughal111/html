<?php /** @noinspection PhpMissingReturnTypeInspection */

namespace DBTech\Credits\XF\Entity;

class ReportComment extends XFCP_ReportComment
{
	/**
	 * @throws \Exception
	 */
	protected function _preDelete()
	{
		// Do parent stuff
		$previous = parent::_preDelete();

		if ($this->is_report)
		{
			/** @var \DBTech\Credits\Repository\EventTrigger $eventTriggerRepo */
			$eventTriggerRepo = $this->repository('DBTech\Credits:EventTrigger');
			
			$eventTriggerRepo->getHandler('report')
				->testUndo([], $this->User)
			;
			
			$eventTriggerRepo->getHandler('reported')
				->testUndo([], $this->Report->User)
			;
		}

		return $previous;
	}
	
	/**
	 * @throws \Exception
	 */
	protected function _postDelete()
	{
		// Do parent stuff
		$previous = parent::_postDelete();

		if ($this->is_report)
		{
			/** @var \DBTech\Credits\Repository\EventTrigger $eventTriggerRepo */
			$eventTriggerRepo = $this->repository('DBTech\Credits:EventTrigger');
			
			$eventTriggerRepo->getHandler('report')
				->undo($this->report_id, [
					'content_type' => $this->Report->content_type,
					'content_id'   => $this->Report->content_id
				], $this->User)
			;
			
			$eventTriggerRepo->getHandler('reported')
				->undo($this->report_id, [
					'content_type' => $this->Report->content_type,
					'content_id'   => $this->Report->content_id
				], $this->Report->User)
			;
		}

		return $previous;
	}
}