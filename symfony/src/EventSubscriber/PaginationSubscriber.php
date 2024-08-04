<?php

namespace App\EventSubscriber;

use Knp\Component\Pager\Event\AfterEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PaginationSubscriber implements EventSubscriberInterface
{
  public function addCustomData(AfterEvent $event): void
  {
    $pagination = $event->getPaginationView();

    $currentPageNumber = $pagination->getCurrentPageNumber();
    $itemNumberPerPage = $pagination->getItemNumberPerPage();
    $totalCount = $pagination->getTotalItemCount();

    $startIndex = ($currentPageNumber - 1) * $itemNumberPerPage + 1;
    $endIndex = min($startIndex + $itemNumberPerPage - 1, $totalCount);

    $pagination->setCustomParameters([
      'align' => $pagination->getCustomParameter('align') ?? 'center',
      'size' => $pagination->getPaginatorOption('size') ?? 'small',
      'startIndex' => $startIndex,
      'endIndex' => $endIndex
    ]);
  }

  public static function getSubscribedEvents(): array
  {
    return [
      'knp_pager.after' => ['addCustomData', 1],
    ];
  }
}
