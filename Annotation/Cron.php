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
    public string $minute;
    public string $hour;
    public ?string $dayOfMonth = null;
    public ?string $month = null;
    public ?string $dayOfWeek = null;
    public ?string $comment = null;
    public ?string $logFile = null;
    public ?string $errorFile = null;

    // If true add /dev/null.
    public ?bool $noLogs = null;

    // Which server should this cron job run on.
    public string $server;

    public ?string $params = null;
    public ?string $executor = null;
}
