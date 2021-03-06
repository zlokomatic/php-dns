<?php

namespace RemotelyLiving\PHPDNS\Observability\Subscribers;

use RemotelyLiving\PHPDNS\Observability\Events\DNSQueried;
use RemotelyLiving\PHPDNS\Observability\Events\DNSQueryFailed;
use RemotelyLiving\PHPDNS\Observability\Events\DNSQueryProfiled;
use RemotelyLiving\PHPDNS\Observability\Events\ObservableEventAbstract;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class STDIOSubscriber implements EventSubscriberInterface
{
    /**
     * @var \SplFileObject
     */
    private $STDOUT;

    /**
     * @var \SplFileObject
     */
    private $STDERR;

    public function __construct(\SplFileObject $stdOut, \SplFileObject $stdErr)
    {
        $this->STDOUT = $stdOut;
        $this->STDERR = $stdErr;
    }

    public static function getSubscribedEvents()
    {
        return [
            DNSQueryFailed::getName() => 'onDNSQueryFailed',
            DNSQueried::getName() => 'onDNSQueried',
            DNSQueryProfiled::getName() => 'onDNSQueryProfiled',
        ];
    }

    public function onDNSQueryFailed(ObservableEventAbstract $event): void
    {
        $this->STDERR->fwrite(\json_encode($event, JSON_PRETTY_PRINT) . PHP_EOL);
    }

    public function onDNSQueried(ObservableEventAbstract $event): void
    {
        $this->STDOUT->fwrite(\json_encode($event, JSON_PRETTY_PRINT) . PHP_EOL);
    }

    public function onDNSQueryProfiled(ObservableEventAbstract $event): void
    {
        $this->STDOUT->fwrite(\json_encode($event, JSON_PRETTY_PRINT) . PHP_EOL);
    }
}
