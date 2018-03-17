<?php

/**
 * // English
 *
 * libform is a library for PocketMine-MP for easy operation of forms
 * Copyright (c) 2018 yuko fuyutsuki < https://github.com/fuyutsuki >
 *
 * This software is distributed under "MIT license".
 * You should have received a copy of the MIT license
 * along with this program.  If not, see
 * < https://opensource.org/licenses/mit-license >.
 *
 * ---------------------------------------------------------------------
 * // 日本語
 *
 * libformは、フォームを簡単に操作するためのpocketmine-MP向けライブラリです
 * Copyright (c) 2018 yuko fuyutsuki < https://github.com/fuyutsuki >
 *
 * このソフトウェアは"MITライセンス"下で配布されています。
 * あなたはこのプログラムと共にMITライセンスのコピーを受け取ったはずです。
 * 受け取っていない場合、下記のURLからご覧ください。
 * < https://opensource.org/licenses/mit-license >
 */

namespace tokyo\pmmp\libform;

// pocketmine
use pocketmine\{
  event\Listener,
  event\player\PlayerQuitEvent,
  event\server\DataPacketReceiveEvent,
  network\mcpe\protocol\ModalFormResponsePacket,
  plugin\PluginBase
};

// libform
use tokyo\pmmp\libform\{
  form\CustomForm,
  form\ListForm,
  form\ModalForm
};

/**
 * FormApiClass
 */
class FormApi implements Listener{

  /** @var ?FormApi */
  private static $instance = null;
  /** @var ?PluginBase */
  private $plugin = null;
  /** @var Form[] */
  private $forms = [];

  public function __construct(PluginBase $plugin) {
    $this->plugin = $plugin;
    self::$instance = $plugin;
    $plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);
  }

  /**
   * @return self
   */
  public static function get(): self {
    return self::$instance;
  }

  /**
   * Generate a custom form
   * @param  callable   $callable
   * @return CustomForm
   */
  public function makeCustomForm(callable $callable = null): CustomForm {
    $formId = $this->makeRandonFormId();
    $form = new CustomForm($formId, $callable);
    if ($callable !== null) $this->forms[$formId] = $form;
    return $form;
  }

  /**
   * Generate a list form
   * @param  callable $callable
   * @return ListForm
   */
  public function makeListForm(callable $callable = null): ListForm {
    $formId = $this->makeRandonFormId();
    $form = new ListForm($formId, $callable);
    if ($callable !== null) $this->forms[$formId] = $form;
    return $form;
  }

  /**
   * Generate a modal form
   * @param  callable  $callable
   * @return ModalForm
   */
  public function makeModalForm(callable $callable = null): ModalForm {
    $formId = $this->makeRandonFormId();
    $form = new ModalForm($formId, $callable);
    if ($callable !== null) $this->forms[$formId] = $form;
    return $form;
  }

  /**
   * Generate random formId
   * @return int formId
   */
  public function makeRandonFormId(): int {
    return mt_rand(0, mt_getrandmax());
  }

  /**
   * Check if the form has been canceled
   * @param  mixed $response
   * @return bool
   */
  public static function formCancelled($response): bool {
    return $response === null? true : false;
  }

  public function onReceive(DataPacketReceiveEvent $event) {
    $pk = $event->getPacket();
    if ($pk instanceof ModalFormResponsePacket) {
      $player = $event->getPlayer();
      $formId = $pk->formId;
      $response = json_decode($pk->formData, true);
      if (array_key_exists($formId, $this->forms)) {
        $form = $this->forms[$formId];
        if (!$form->isRecipient($player)) {
          return false;
        }
        $callable = $form->getCallable();
        if ($callable !== null) {
          $callable($player, $response);
        }
        unset($this->forms[$formId]);
        $event->setCancelled();
      }
    }
  }

  public function onQuit(PlayerQuitEvent $event) {
    $player = $event->getPlayer();
    foreach ($this->forms as $formId => $form) {
      if ($form->isRecipient($player)) {
        unset($this->forms[$formId]);
        break;
      }
    }
  }
}
