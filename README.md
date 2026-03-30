# cashbookアプリケーション レポジトリ

このレポジトリは、PHPアプリケーションであるcashbookプロジェクトのレポジトリです。

このcasbookアプリケーションは、銀行CSVを加工して出納帳形式に整形するCLIツールです。

また、CSVの入出力とドメインロジックを分離するため、io / model / service の3層構成としています。

このアプリケーションでは以下のデータを扱います：

    - 入力: 銀行CSV
    - 出力: 整形済みCSV（data/output/）

## 📂 プロジェクト構成

このプロジェクトのディレクトリ構成は以下のようになっています：

```
	project-root/
			├─ cashbook.php     # 実行プログラム
			├─ src/
			│   ├─ io/
			│   ├─ model/
			│   └─ service/
			│
			├─ tests/
			│   ├─ io/
			│   ├─ model/
			│   ├─ service/
			│   ├─ phpunit.xml   # PHPUnit用設定ファイル
			│   └─ bootstrap.php
			│
			├─ bin/
			│   ├─ phpunit.bat   # Windows用テスト起動バッチ
			│   └─ phpunit.sh    # Linux用テスト起動用シェルスクリプト
			│
			├─ data/
			│   ├─ input/        # 入力CSVファイル格納ディレクトリ
			│   │   └─ sample_00.csv
			│   └─ output/       # CSVファイル出力ディレクトリ
			│
			├─ composer.json
			├─ composer.lock
			└─ vendor/           # (composer installで生成)
```

## 🚀 セットアップ手順

### 手順-1. リポジトリをクローンする

```
$ git clone <repository-url>
$ cd cashbook
```

### 手順-2. Composerパッケージをインストールする

```
$ composer install
```

手順-2でcomposerでパッケージをインストールするので、事前にComposerをインストールしておく必要があります。

composerパッケージをインストールすると、プロジェクトルートにvendor/ ディレクトリとcomposer.lockが作成されます。

## ▶️ 使用方法

### データの配置

読み込む銀行取引CSVデータをダウンロードしたあと、 data/input/ ディレクトリに配置してください。  
このディレクトリと出力先ディレクトリであるdata/output/ ディレクトリはあらかじめ用意されています。

現時点の仕様は開発者の口座がある銀行を元にしていますが、以下の形式のCSVデータを前提としています：

    - 明細通番
    - 日付
    - お引出金額
    - お預入金額
    - 残高
    - お取引内容

銀行によってデータ項目は異なる可能性があるので、アプリケーションを使用する際には、口座を開いている銀行のCSVデータを確認してください。

### 起動方法

```
$ php cashbook.php [<input_csv_file_name>]
```

プロジェクトルートで実行します。
処理が実行されて、以下のように表示されます。

```
$ php cashbook.php hoge_01.csv
処理開始：data/input/hoge_01.csv
処理完了：data/output/hoge_01.csv
```

と表示され、出力ディレクトリである data/output/ ディレクトリに入力ファイルと同名のファイルが作成されます。

[]に囲まれた入力ファイル名を省略することができます。省略した場合はデフォルトで「sample_00.csv」を読み込みます（上記ディレクトリ構成参照）。

出力ファイル項目は入力ファイルと同じ項目になっていますが、データ行以外の行はスキップされデータ行のみが出力されます。

出力形式については src/io/CsvWriter.php で制御しています。

## 🧪 テストの実行方法

### tests/ディレクトリ以下のすべてのテストを実行する

Linux:

```bash
./bin/phpunit.sh
```

Windows:

```
[.\]bin\phpunit[.bat]
```

Windowsでは、[]で囲んだ部分は省略しても構いません。
省略しても同じように動作します。

phpunit.sh / phpunit.bat は bin/ ディレクトリに配置しています。  
また、phpunit.xml と bootstrap.php は tests/ ディレクトリ直下に配置しています。

### tests/ディレクトリ以下の特定のテストを実行する

例）tests/io/CsvWriterTest.phpを実行する場合

Linux：

```
./bin/phpunit.sh tests/io/CsvWriterTest.php
```

Windows：

```
bin\phpunit tests\io\CsvWriterTest.php
```

## 🙋 使用環境要件

    - PHP 8.1+
    - Composer

## 📄 ライセンス

このプロジェクトは **MITライセンス** のもとで提供されています。  
著作権表記：&copy; 2026 villageworks

詳細は [LICENSE](./LICENSE) を参照してください。

---

This project is licensed under the MIT License.  
Copyright (c) 2026 villageworks.

See the [LICENSE](./LICENSE) file for the full text.
