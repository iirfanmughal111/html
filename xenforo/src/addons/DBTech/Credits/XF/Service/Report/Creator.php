<?php /** @noinspection PhpMissingReturnTypeInspection */

namespace DBTech\Credits\XF\Service\Report;

class Creator extends XFCP_Creator
{
	/**
	 * @return array
	 * @throws \XF\PrintableException
	 * @throws \Exception
	 */
	protected function _validate()
	{
		$previous = parent::_validate();
		
		if (empty($previous) && !$this->threadCreator)
		{
			/** @var \DBTech\Credits\Repository\EventTrigger $eventTriggerRepo */
			$eventTriggerRepo = $this->repository('DBTech\Credits:EventTrigger');
			
			$eventTriggerRepo->getHandler('report')
				->testApply([], $this->user)
			;
			
			$eventTriggerRepo->getHandler('reported')
				->testApply([], $this->report->User)
			;
		}
		
		return $previous;
	}
	
	/**
	 * @return \XF\Entity\Report
	 * @throws \XF\PrintableException
	 * @throws \Exception
	 */
	protected function _save()
	{
		$report = parent::_save();
		
		if (!$this->threadCreator)
		{
			/** @var \DBTech\Credits\Repository\EventTrigger $eventTriggerRepo */
			$eventTriggerRepo = $this->repository('DBTech\Credits:EventTrigger');
			
			$eventTriggerRepo->getHandler('report')
				->apply($report->report_id, [
					'content_type' => $report->content_type,
					'content_id' => $report->content_id
				], $this->user)
			;
			
			$eventTriggerRepo->getHandler('reported')
				->apply($report->report_id, [
					'content_type' => $report->content_type,
					'content_id' => $report->content_id
				], $this->report->User)
			;
		}
		
		return $report;
	}
}