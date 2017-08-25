# What's this?
Post a Slack message by "GET" URL.
You can post 1-tap with Homescreen icon.
[Online version here](https://one-tap.l2tp.org)

# Homescreen
You can add the URL into your iPhone as "Homescreen" shortcut.
Then you can post a message with 1-tap.

# Warning
"Webhook URL" is really important to post message in Slack.
You must protect your URL as **PASSWORD**.

# How to install

1. Clone this repo.
```
$ cd /path/to/webhost
$ git clone https://github.com/yousan/post-slack-message/
```
2. Get Webhook URL from Slack. 
3. Access by browser e.g.) http://exmaple.com/post-slack-message/post1.php
4. Fill informations.
5. Post!

# GETでSlackを送ります
Webhookを使ったSlackメッセージはPOSTでリクエストを送信しますが、GETで受け取ってPOSTに変換します。


# iPhoneで1タップ送信
iPhoneの「ホーム画面へ追加」を行うと、定型文を1タップで送信できます。
