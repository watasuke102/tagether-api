# API for TAGether

以下URLを`http://localhost:8000`とします

## 事前準備
以下のようにデータベースを作成
```
$ CREATE DATABASE tagether;
$ CREATE TABLE tagether.exam (id INT PRIMARY KEY AUTO_INCREMENT, updated_at TIMESTAMP default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP, title TEXT, description TEXT, tag TEXT, list JSON);
```
ユーザー名やパラメータはまだ変えてない

## GET
URLにidクエリを含めると、特定のidを取得できます  
例: `http://localhost:8000?id=5`→`SELECT * FROM exam WHERE id=5`の結果が返ります  
### パラメータ
| Name       | Type   | Description                                 |
| ---------- | ------ | ------------------------------------------- |
| id         | int    | id                                          |
| updated_at | string | 更新(作成)日                                |
| title      | string | 問題カテゴリのタイトル                      |
| desc       | string | 問題カテゴリの説明                          |
| tag        | string | 問題カテゴリに付けられたタグ (カンマ区切り) |
| list       | json   | 問題の中身                                  |
### 例 (curl)
jsonの中身は実際のものとは仕様が異なります
```
curl -X GET "http://localhost:8000"

↓

[{"id":1,"updated_at":"2021-01-05 17:09:04","title":"TEST","desc":"THIS IS TEST DESC","tag":"","list":"{\"test\":\"hoge\",\"list\":[{\"id\":\"1\"},{\"id\":\"2\"}]}"},{"id":2,"updated_at":"2021-01-05 18:49:50","title":"WORD TEST","desc":"eigo no tesuto","tag":"english,exam","list":"{\"test\":\"hoge\",\"list\":[{\"id\":\"1\"},{\"id\":\"2\"}]}"}]
```

## POST
### 送信すべきパラメータ
| Name       | Type   | Description                                 |
| ---------- | ------ | ------------------------------------------- |
| title      | string | 問題カテゴリのタイトル                      |
| desc       | string | 問題カテゴリの説明                          |
| tag        | string | 問題カテゴリに付けられたタグ (カンマ区切り) |
| list       | json   | 問題の中身                                  |
### 注意事項
id及びupdated_atを含まないjsonを送信してください  
未実装です（常に`{"status":"ok"}`が返ってきます）
### 例 (curl)
```
curl -X POST -H "Content-Type: application/json" "http://localhost:8000" -d '{"title": "TITLE","desc": "THIS IS DESCRIPTION","tag": "tag1,tag2","list": "[{\\"question\\": \\"1+1=?\\",\\"answer\\": \\"2\\"},{\\"question\\": \\"5+5=?\\",\\"answer\\": \\"10\\"}]"}'

↓

{"status":"ok"}
```
