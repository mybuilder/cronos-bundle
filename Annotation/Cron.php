<?php

namespace MyBuilder\Bundle\CronosBundle\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * Cron annotation which we can parse to generate a cron file
 *
 * @Annotation
 * @Target("CLASS")
 */
class Cron extends Annotation
{
    /** @var string */
    public $minute;

    /** @var string */
    public $hour;

    /** @var string */
    public $dayOfMonth;

    /** @var string */
    public $month;

    /** @var string */
    public $dayOfWeek;

    /** @var string */
    public $comment;

    /** @var string */
    public $logFile;

    /** @var string */
    public $errorFile;

    /**
     * If true add /dev/null.
     *
     * @var boolean
     */
    public $noLogs;

    /**
     * Which server should this cron job run on.
     *
     * @var string
     */
    public $server;

    /** @var string */
    public $params;

    /** @var string */
    public $executor;
}
