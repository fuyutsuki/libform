<?php
namespace tokyo\pmmp\libform;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\ModalFormResponsePacket;

class EventListener implements Listener {

  public function onReceive(DataPacketReceiveEvent $event) {
    $pk = $event->getPacket();
    if ($pk instanceof ModalFormResponsePacket) {
      $player = $event->getPlayer();
      $formId = $pk->formId;
      $response = json_decode($pk->formData, true);
      if (($form = FormApi::getForm($formId)) !== null) {
        if (!$form->isRecipient($player)) {
          return false;
        }
        $callable = $form->getCallable();
        if ($callable !== null) {
          $callable($player, $response);
        }
        FormApi::removeForm($formId);
        $event->setCancelled();
      }
    }
  }

  public function onQuit(PlayerQuitEvent $event) {
    $player = $event->getPlayer();
    foreach (FormApi::getForms() as $formId => $form) {
      if ($form->isRecipient($player)) {
        FormApi::removeForm($formId);
        break;
      }
    }
  }
}