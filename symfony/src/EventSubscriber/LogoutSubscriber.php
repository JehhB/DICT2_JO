<?php

namespace App\EventSubscriber;

use App\Entity\Account;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Event\LogoutEvent;

class LogoutSubscriber implements EventSubscriberInterface
{
  public function __construct(private UrlGeneratorInterface $urlGenerator, private EntityManagerInterface $entityManager) {}

  public function onLogoutEvent(LogoutEvent $event): void
  {
    $token = $event->getToken();
    if (is_null($token)) return;

    /** @var ?Account */
    $account = $token->getUser();
    if (is_null($account)) return;

    $currentSession = $account->getCurrentSession();

    $account->setCurrentSession(null);
    $currentSession->setLogoutAt(new DateTimeImmutable());

    $this->entityManager->flush();

    $redirect = new RedirectResponse(
      $this->urlGenerator->generate('app_login')
    );
    $event->setResponse($redirect);
  }

  public static function getSubscribedEvents(): array
  {
    return [
      LogoutEvent::class => 'onLogoutEvent',
    ];
  }
}
