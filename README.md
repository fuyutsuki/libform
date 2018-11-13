## libform
Select language: [English](#english), [日本語](#日本語)

***
### English
It is a virion library that can handle various forms easily.

#### Installation
Composer:
> Please use the attached pmmp/PocketMine-MP's php binary to execute the composer command in the folder plugin you want to use this library.
```bash
composer require fuyutsuki/libform # API3.x
composer require fuyutsuki/libform:dev-4.0 # API4.x
```

Virion:
> Edit the .poggit.yml of the plugin you want to use this library as follows.
```yaml
--- # Poggit-CI Manifest. Open the CI at https://poggit.pmmp.io/ci/author/YourProject
branches:
  - master
projects:
  YourProject:
    path: ""
    icon: ""
    libs:
      - src: fuyutsuki/libform/libform
        version: 0.4.0
...
```

#### Usage
Please see the [sample plugin](https://github.com/fuyutsuki/libform/tree/master/sample).

***
### 日本語
様々なフォームを簡単に扱うためのvirionライブラリです。

#### インストール方法
Composer:
> pmmp/PocketMine-MP付属のphpバイナリを使って、このライブラリを使いたいフォルダプラグインの中でcomposerコマンドを実行して下さい。
```bash
composer require fuyutsuki/libform # API3.x用
composer require fuyutsuki/libform:dev-4.0 # API4.x用
```

Virion:
> このライブラリを使いたいプラグインの.poggit.ymlを以下のように編集します。
```yaml
--- # Poggit-CI Manifest. Open the CI at https://poggit.pmmp.io/ci/author/YourProject
branches:
  - master
projects:
  YourProject:
    path: ""
    icon: ""
    libs:
      - src: fuyutsuki/libform/libform
        version: 0.4.0
...
```

#### 使い方
[サンプルプラグイン](https://github.com/fuyutsuki/libform/tree/master/sample)をご覧ください。