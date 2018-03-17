## MCBEFormAPI

### How to use
#### フォームAPI登録
必要なuse
```php
use tokyo\pmmp\MCBEFormAPI\{
  FormApi
};
```
どこでもいいので以下のコードを実行します  
例ではPluginBase::onEnable()で実行しています
```php
public function onEnable() {
  // 方法1)
  $this->formApi = new FormApi($this);

  // 方法2)
  new FormApi($this);
  // 使用時に以下のように呼び出せます
  $formApi = FormApi::get();
}
```
#### モダルフォーム
必要なuse
```php
use tokyo\pmmp\MCBEFormAPI\{
  FormApi
};
```
モダルフォームは"はい"か"いいえ"のような二択で答えるものです
```php
// $this(このクラス)の"test"という名の関数をフォーム入力後に呼び出します
$modal = FormApi::get()->makeModalForm([$this, "test"]);
$modal->setTitle("モダルフォーム")
->setContents("内容")
->setButtonText(true, "はい")// true => 上側
->setButtonText(false, "いいえ")// false => 下側
->sendToPlayer($sender);// プレイヤーに送信
```

#### カスタムフォーム
必要なuse
```php
use tokyo\pmmp\MCBEFormApi\{
  FormApi,
  element\Dropdown,
  element\Input,
  element\Label,
  element\Slider,
  element\StepSlider,
  element\Toggle
};
```
カスタムフォームは多彩なエレメントを使用して様々な使用用途に適すものです
```php
// $this(このクラス)の"test"という名の関数をフォーム入力後に呼び出します
$custom = FormApi::get()->makeCustomForm([$this, "test"]);
$custom->setTitle("カスタムフォーム")
->addElement(new Dropdown("ドロップダウン", ["あ", "い", "う"]))
->addElement(new Input("インプット", "プレースホルダー", "デフォルト値"))
->addElement(new Label("ラベル"))
->addElement(new Slider("スライダー", 0, 10, 1))// 最小値, 最大値, 間隔 の順です
->addElement(new StepSlider("ステップスライダー", ["だめ", "ふつう", "イイね！"]))
->addElement(new Toggle("トグル", true))// 第二引数は初期値です
->sendToPlayer($sender);// プレイヤーに送信
```

#### リストフォーム
必要なuse
```php
use tokyo\pmmp\MCBEFormApi\{
  FormApi,
  element\Button
};
```
リストフォームは複数の選択肢の中から一つのものを選択するフォームです
```php
// $this(このクラス)の"test"という名の関数をフォーム入力後に呼び出します
$list = $this->core->getFormApi()->makeListForm([$this, "test"]);
$list->setTitle("リストフォーム")
->setContents("内容")
->addButton((new Button("ボタン1"))->setImage("画像ファイルのURL", Button::IMAGE_TYPE_URL))
->addButton((new Button("ボタン2"))->setImage("画像ファイルのパス", Button::IMAGE_TYPE_PATH))
->sendToPlayer($sender);
```

#### フォームの返り値の取得
上の例では`[$this, "test"]`を例として挙げたので、その通りにやってみましょう
```php
/**
 * @description
 * コールバック関数なのでpublicでなければなりません
 * @param  mixed $response フォーム返り値,キャンセルされた場合はnullが帰ります
 * @return void            この関数の返り値,なんでもいいです
 */
public function test($response): void {
  if (FormApi::FormCancelled($response)) {
    // formがキャンセルされていれば
  }else {
    // formがキャンセルされていなければ
  }
}
```
