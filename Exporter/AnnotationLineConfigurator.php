<?php

namespace MyBuilder\Bundle\CronosBundle\Exporter;

use MyBuilder\Bundle\CronosBundle\Annotation\Cron as CronAnnotation;
use MyBuilder\Cronos\Formatter\Job;

class AnnotationLineConfigurator
{
    public function __construct(private Job $line)
    {}

    public function configureFrom(CronAnnotation $annotation): Job
    {
        if ($annotation->minute !== null) {
            $this->line->setMinute($annotation->minute);
        }
        if ($annotation->hour !== null) {
            $this->line->setHour($annotation->hour);
        }
        if ($annotation->dayOfMonth !== null) {
            $this->line->setDayOfMonth($annotation->dayOfMonth);
        }
        if ($annotation->month !== null) {
            $this->line->setMonth($annotation->month);
        }
        if ($annotation->dayOfWeek !== null) {
            $this->line->setDayOfWeek($annotation->dayOfWeek);
        }
        if ($annotation->logFile !== null) {
            $this->line->setStandardOutFile($annotation->logFile);
        }
        if ($annotation->errorFile !== null) {
            $this->line->setStandardErrorFile($annotation->errorFile);
        }
        if ($annotation->noLogs !== null) {
            $this->line->suppressOutput();
        }

        return $this->line;
    }
}
