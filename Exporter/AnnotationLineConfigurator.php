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
        if ($annotation->minute) {
            $this->line->setMinute($annotation->minute);
        }
        if ($annotation->hour) {
            $this->line->setHour($annotation->hour);
        }
        if ($annotation->dayOfMonth) {
            $this->line->setDayOfMonth($annotation->dayOfMonth);
        }
        if ($annotation->month) {
            $this->line->setMonth($annotation->month);
        }
        if ($annotation->dayOfWeek) {
            $this->line->setDayOfWeek($annotation->dayOfWeek);
        }
        if ($annotation->comment) {
            $this->line->addComment($annotation->comment);
        }
        if ($annotation->logFile) {
            $this->line->setStandardOutFile($annotation->logFile);
        }
        if ($annotation->errorFile) {
            $this->line->setStandardErrorFile($annotation->errorFile);
        }
        if ($annotation->noLogs) {
            $this->line->suppressOutput();
        }

        return $this->line;
    }
}