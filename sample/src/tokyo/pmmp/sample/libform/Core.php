<?php

/**
 * // English
 *
 * sample-libform, the sample of libform plugin for PocketMine-MP
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
 * sample-libformは、libformのサンプルプラグインです
 * Copyright (c) 2018 yuko fuyutsuki < https://github.com/fuyutsuki >
 *
 * このソフトウェアは"MITライセンス"下で配布されています。
 * あなたはこのプログラムと共にMITライセンスのコピーを受け取ったはずです。
 * 受け取っていない場合、下記のURLからご覧ください。
 * < https://opensource.org/licenses/mit-license >
 */

declare(strict_types = 1);

namespace tokyo\pmmp\sample\libform;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use tokyo\pmmp\libform\element\Button;
use tokyo\pmmp\libform\element\Dropdown;
use tokyo\pmmp\libform\element\Input;
use tokyo\pmmp\libform\element\Label;
use tokyo\pmmp\libform\element\Slider;
use tokyo\pmmp\libform\element\StepSlider;
use tokyo\pmmp\libform\element\Toggle;
use tokyo\pmmp\libform\form\ListForm;
use tokyo\pmmp\libform\FormApi;

/**
 * Class Core
 * @package tokyo\pmmp\sample\libform
 */
class Core extends PluginBase {

  /** @var ListForm */
  private $list;

  public function onEnable() {
  	FormApi::register($this);// Register a class that extends PluginBase before using it
  }

  public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
    if (!$command->testPermission($sender)) return false;
    if ($sender instanceof Player) {
      if (!empty($args[0])) {
        switch (strtolower($args[0])) {
          case 'custom':
            $this->customFormProcess($sender);
            return true;
          case 'list':
            $this->listFormProcess($sender);
            return true;
          case 'modal':
            $this->modalFormProcess($sender);
            return true;
        }
      }
      return false;
    }else {
      $this->getLogger()->info(TextFormat::RED . "Please use this command in game");
    }
    return true;
  }

  /**
   * Create a customizable form element.
   * In this example, the result of the form is received in closures.
   * @param Player $player
   */
  private function customFormProcess(Player $player) {
    FormApi::makeCustomForm(function (Player $player, ?array $response) {
      if (!FormApi::formCancelled($response)) {
        $this->getLogger()->info(TextFormat::GREEN . "CustomForm response:" . PHP_EOL . var_export($response, true));
      }else {
        $this->getLogger()->info(TextFormat::RED . "CustomForm is cancelled!");
      }
    })
      ->addElement(new Dropdown("Dropdown", ["Awesome!", "Soso", "Bad..."]))
      ->addElement(new Input("Input", "placeholder", "defaultText"))
      ->addElement(new Label("Label"))
      ->addElement(new Slider("Slider", 0, 64))
      ->addElement(new StepSlider("StepSlider", ["Sunny", "Cloudy", "Rainy"]))
      ->addElement(new Toggle("Toggle"))
      ->setTitle("CustomForm elements")
      ->sendToPlayer($player);
  }

  /**
   * Create a form that can list strings.
   + In this example, the result of the form is received by a callback function.
   * @param Player $player
   */
  private function listFormProcess(Player $player) {
    $this->list = FormApi::makeListForm([$this, "receiveResponse"])
      ->addButton(new Button("A"))
      ->addButton((new Button("B"))->setImage("http://example.com/example.jpg", Button::IMAGE_TYPE_URL))
      ->addButton((new Button("C"))->setImage("Doesn't work this!"))
      ->setTitle("ListForm Buttons")
      ->sendToPlayer($player);
  }

  public function receiveResponse(Player $player, ?int $key) {
    if (!FormApi::formCancelled($key)) {
      $buttons = $this->list->getButtons();
      $chosen = $buttons[$key];
      /** @var Button $chosen */
      $this->getLogger()->info(TextFormat::GREEN . "ListForm: You choose this! => {$chosen->text}");
    }
  }

  /**
   * @param Player $player
   */
  private function modalFormProcess(Player $player) {
    FormApi::makeModalForm(function (Player $player, ?bool $response) {
      if (!FormApi::formCancelled($response)) {
        if ($response) $this->listFormProcess($player);
      }
    })
      ->setButtonText(true, "true")
      ->setButtonText(false, "false")
      ->setContent("Hey! Do you want to use ListForm?")
      ->setTitle("ModalForm")
      ->sendToPlayer($player);
  }
}