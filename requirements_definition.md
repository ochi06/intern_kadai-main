# チェックシート

## ログイン

- [ ] ユーザーがアクセスした際にログインページが表示される
- [ ] ログイン成功時にHOMEに遷移される
- [ ] ログインしていないユーザーには必ずログイン画面が表示される

## アカウント登録

- [ ] アカウント作成後にログイン画面に遷移する

## HOME

- [ ] TODOリストをクリックするとTODOリスト画面に遷移する
- [ ] 新規プロジェクト作成後、作成したプロジェクトの画面に遷移する
- [ ] プロジェクト名をクリックすると各プロジェクト画面に遷移する
- [ ] クリックでTODOリストのチェックボックス切り替えができる
- [ ] 同名のプロジェクトの作成は不可

## TODOリスト

- [ ] TODOリストの一覧が表示される
- [ ] 作成、更新、削除ができる
- [ ] 同名のタスク、無名のタスクの登録は不可


# 課題条件

- [x] サーバサイド言語はPHPで、フレームワークのFuelphpを使用すること
- [x] beforeメソッドを使う
- [x] configファイルをカスタマイズする
- [x] sessionやcookieを使う
- [ ] ネームスペースを使う（実装していなくても、ネームスペースの目的と使い方に関して、口頭で説明できればOK）
- [x] \（バックスラッシュ）を使ったグローバルな名前空間へのアクセスについて理解している
- [x] データベースとのやり取りはDBクラスを使うこと
- [x] 1:n関係のテーブル構造があること（正規化について説明できること）
- [x] CRUDの機能が網羅されている
- [ ] フロントエンドのライブラリにknockout.jsが使用されている
- [x] uxを考慮して一部動的なuiが実装されている（非同期処理）
- [x] GitHubでコードの管理を行う
- [ ] セキュリティ資料を読み必要な実装を行う

# DB

|テーブル名称|カラム名称|データ型|null|デフォルト|備考|

|Users|id|int(11)| | |ユーザーID|\
| |user_name|varchar(10)| | |ユーザー名|\
| |mail_address|varchar(50)| | |メールアドレス|\
| |password|varchar(20)| | |パスワード|\
| |created_at|datetime| | |アカウント作成日|\
| |updated_at|datetime|yes| |更新日|\

|Projects|id|int(11)| | |プロジェクトID|\
| |user_id|int(11)| | |ユーザーID|\
| |project_name|varchar(30)| | |プロジェクト名|\
| |description|varchar(100)| | |プロジェクトの説明|\
| |created_at|datetime| | |作成日時|\
| |updated_at|datetime|yes| |更新日時|\

|WorkLogs|id|int(11)| | |作業時間ID|\
| |project_id|int(11)| | |プロジェクトID|\
| |record_date|date| | |記録日|\
| |description|varchar(100)|yes| |作業内容|\
| |duration_minutes|int(3)|0| |学習時間|\
| |created_at|datetime| | |作成日時|\
| |updated_at|datetime|yes| |更新日時|

|Todos|id|int(11)| | |TodoID|\
| |project_id|int(11)| | |プロジェクトID|\
| |title|varchar(30)| | |Todoタスク名|\
| |description|varchar(100)|yes| |Todoタスクの説明|\
| |start_date|date| | |開始予定日|\
| |end_date|date| | |終了予定日|\
| |started_at|date|yes| |開始日|\
| |ended_at|date|yes| |達成日|\
| |is_completed|boolean| |0|達成状況|\
| |created_at|datetime| | |作成日時|\
| |updated_at|datetime|yes| |更新日時|\
