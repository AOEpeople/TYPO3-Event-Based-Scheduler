<?php
namespace Aoe\EventBasedScheduler\Scheduler;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2015 AOE GmbH <dev@aoe.com>
 *  All rights reserved
 *
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Scheduler\FieldProvider as ExtbaseFieldProvider;
use TYPO3\CMS\Scheduler\Controller\SchedulerModuleController;
use TYPO3\CMS\Extbase\SignalSlot\Dispatcher;

class FieldProvider extends ExtbaseFieldProvider
{
    /**
     * @param array $taskInfo
     * @param mixed $task
     * @param SchedulerModuleController $schedulerModule
     * @return array
     */
    public function getAdditionalFields(array &$taskInfo, $task, SchedulerModuleController $schedulerModule)
    {
        /** @var Dispatcher $dispatcher */
        $dispatcher = GeneralUtility::makeInstance(Dispatcher::class);
        $fields = parent::getAdditionalFields($taskInfo, $task, $schedulerModule);
        return array_merge(
            $fields,
            array(
                'event' => array(
                    'code' => $this->buildSelect($this->getSlots($dispatcher)),
                    'label' => 'label',
                    'cshKey' => '',
                    'cshLabel' => ''
                )
            )
        );
    }

    /**
     * @param array $slots
     * @return string
     */
    protected function buildSelect(array $slots)
    {
        $format = '<select name="tx_scheduler[event]">%s</select>';
        return sprintf($format, $this->buildOptions($slots));
    }

    /**
     * @param array $slots
     * @return string
     */
    protected function buildOptions(array $slots)
    {
        $options = array();
        $format = '<option value="%s">%s</option>';
        foreach ($slots as $signalClass => $events) {
            foreach ($events as $signalMethod => $event) {
                $options[] = sprintf($format, "$signalClass:$signalMethod");
            }
        }
        return implode('', $options);
    }

    /**
     * @param Dispatcher $dispatcher
     * @return array
     */
    protected function getSlots(Dispatcher $dispatcher)
    {
        $class = new \ReflectionClass($dispatcher);
        $property = $class->getProperty('slots');
        $property->setAccessible(true);
        return $property->getValue($dispatcher);
    }
}
