<?php

namespace MyBuilder\Bundle\CronosBundle\Exporter;

class AnnotationLineConfigurator
{
    private $line;

    public function __construct($line)
    {
        $this->line = $line;
    }

    public function configureFrom($annotation)
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