# Level 3 最終課題：TANZAN Portfolio

作成者：渥美聡一郎 / TANZAN Books

## 現在の状態
静的版とWordPressオリジナルテーマのソースを作成済み。WordPressへの導入・PHP実行・ブラウザでの表示確認・実メールの送受信確認は未実施。本READMEのチェック完了前に「全要件確認済み」として提出しないこと。経歴・スキルの自己評価も本人確認が必要。

## ファイル
- static/：index.htmlをブラウザで開く静的版。フォームはデモ。
- tanzan-portfolio/：WordPressテーマ本体。
- tanzan-portfolio.zip：管理画面からインストールできるテーマのみのZIP。
- docs/contact-form7.txt：Contact Form 7用フォームとメール設定例。

## 導入手順（Windows / MAMP）
1. MAMPを起動し、ローカルWordPressを用意する。WordPress 6.3以上 / PHP 8.0以上を対象とする。最新の安定版で検証する。
2. 外観→テーマ→新規追加→テーマのアップロードからtanzan-portfolio.zipを選択し有効化。手動ならwp-content/themes/tanzan-portfolioに配置。
3. 設定→パーマリンク→投稿名を選択して保存。
4. トップページはfront-page.phpで表示される。固定ページをトップに設定しても同テンプレートが優先される。
5. 外観→メニューでカスタムリンクを作成し「ヘッダーナビゲーション」へ割当。各URLはサイトのトップURL+#about / #skills / #works / #contact。未設定時はフォールバック表示。
6. 制作実績→新規追加でタイトル、本文、アイキャッチ、使用技術、制作期間、担当範囲、公開URLを登録して公開。最新6件を表示。管理画面の表示オプションでアイキャッチを確認。
7. Contact Form 7をインストール・有効化。docs/contact-form7.txtのフォーム内容を貼付し、実際のサイトドメイン・送信先でメールを設定。
8. 外観→カスタマイズ→お問い合わせフォームで数字のフォームIDを設定して公開。フォーム未設定時はメール連絡案内を表示。
9. ローカル環境では通常メール到達を保証できない。SMTPを適切に設定するか検証用サーバーで実メールの受信まで確認。

## 課題との対応
|要件|実装|
|---|---|
|Hero / About / Skills / Contact・レスポンシブ|static/index.html、style.css、front-page.php|
|CSSアニメーション|本の背表紙のアニメーション、reduce-motion対応|
|テーマヘッダー・enqueue|style.css / functions.php|
|テンプレート分割・head/footer|header.php / front-page.php / index.php / footer.php|
|管理画面の動的メニュー|register_nav_menus / wp_nav_menu|
|works・アイキャッチ|register_post_type / add_theme_support|
|サブループ|WP_Query・最新6件・wp_reset_postdata|
|詳細モーダル|template + dialog + Vanilla JS（jQuery依存なし）|
|200pxで先頭ボタン|scrollY >= 200、passiveリスナー|
|稼働フォーム|CF7とテーマ設定連携。環境設定・送信確認は未実施|
|セキュリティ|出力文脈別エスケープ、nonce・権限・入力サニタイズ|

## 実装のこだわり
本と地域をつなぐ経歴を、紙色・深緑・朱色の配色と本のCSS表現で伝える。画像や外部フォントがなくても表示可能。スキルは能力の百分率を装わず4段階の自己評価を表示する。
モーダルはネイティブdialogのフォーカス制御・Escapeを利用し、閉じた後は起点へ戻す。閉じるボタン・背景クリックにも対応。JS無効時は実績詳細ページへのリンクを出す。ナビとフォームはラベル・フォーカス表示を用意。
制作実績のカスタムフィールドもプラグインなしで編集可能。保存時にnonce、edit_post権限、autosave/revisionを確認。rewriteのフラッシュはテーマ有効化時のみ。

## エスケープ方針
タイトル・文字列：esc_html。属性：esc_attr。リンク：esc_url。保存URL：esc_url_raw。本文・アイキャッチHTML：wp_kses_post。本文にesc_htmlを適用すると段落まで文字列化するため、許可されたHTMLを残す。CF7の出力・wp_nav_menu・WordPressの標準API生成部分は、それぞれの出力契約に委ねる。CF7の生成HTMLにwp_kses_postを使うとform/inputが削除されるため適用しない。任意のショートコードは設定できず、IDをabsintで制約。

## エラー・解決の記録
この作成環境ではPHPコマンドとWordPress実行環境がなく、PHP構文検査・実動作確認を実施できなかった。解決方法はMAMPのPHPとWordPressで以下のQAを実施すること。解決済みとは記載しない。
以下は想定トラブルであり実際の発生記録ではない：
- 実績URLが404：パーマリンクを再保存。
- フォームが表示されない：CF7有効化、ID、フォーム公開状態を確認。
- メール未着：Fromを設置ドメインに合わせ、SMTP・迷惑メール・DNSを確認。
実際に発生したエラーと再現手順・原因・修正・再確認結果を提出前に追記する。

## QAチェック（未実施項目をチェック済みにしない）
- [ ] MAMPのPHPで全PHPファイルにphp -lを実行しエラーなし。
- [ ] テーマ有効化後、WP_DEBUG_LOGにテーマ由来のエラーなし。
- [ ] Chrome DevToolsで幅375 / 768 / 1440px、横スクロール・文字の欠落なし。
- [ ] 実績0件・1件・6件以上、画像なし・長いタイトルで表示確認。
- [ ] 管理画面でメニュー変更が反映される。
- [ ] 実績を更新するとモーダルの本文・技術・期間・担当が反映される。
- [ ] モーダルをクリック / Escape / 背景で閉じられ、Tabが内部を移動し起点へ戻る。
- [ ] 199pxでは先頭ボタン非表示、200px以上で表示。
- [ ] OSの動きを減らす設定でアニメーション停止。
- [ ] CF7の必須項目・不正メール・同意未チェックで送信不可。
- [ ] 正常送信で受信箱への到達・Reply-To・文字化けを確認。
- [ ] タイトル/技術欄に<script>alert(1)</script>等を入れて実行されない。
- [ ] DevToolsのConsole/NetworkでJSエラー・404なし。

## 提出方法
テーマ本体、静的版、README、確認済みQAをGitHubリポジトリへ追加し、リポジトリURLと検証サイトURLを本間さんへ提出。既存サイトには直接上書きせず、ローカルまたは検証環境で導入する。
AI支援で作成したコードであることを伝え、WP_Query・フック・サニタイズ/エスケープ・イベント処理を本人が説明できる状態にする。

## 参照した公式ドキュメント
- https://developer.wordpress.org/reference/functions/register_post_type/
- https://developer.wordpress.org/reference/hooks/wp_enqueue_scripts/
- https://developer.wordpress.org/apis/security/escaping/
- https://developer.wordpress.org/reference/functions/wp_nav_menu/
- https://developer.wordpress.org/reference/classes/wp_query/
- https://contactform7.com/setting-up-mail/
- https://contactform7.com/best-practice-to-set-up-mail/
