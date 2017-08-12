# ChatToDiscord
send messages from your PMMP server to Discord

[View on Poggit](https://poggit.pmmp.io/ci/eDroiid/ChatToDiscord/ChatToDiscord)

# Setting It Up
1. Download from [Poggit](https://poggit.pmmp.io/ci/eDroiid/ChatToDiscord/ChatToDiscord).
2. Add plugin to your `plugins` folder.
3. Start and stop your server (so config is created).
4. Open `plugins\ChatToDiscord\config.yml` and start customizing.
  - add your webhooks `webhooks: ['https://discordapp.com/api/webhooks/123/456789']`. [How do I get a webhook?](https://support.discordapp.com/hc/en-us/articles/228383668-Intro-to-Webhooks) (skip the Github Integration at the end of the article)
  - change the username `username: 'My Server'`.
  - change the avatar/icon `avatar: 'http://example.com/myserverslogo.png'`.
  - change the format `message_format: '{player} said {message}'`.
5. Save `plugins\ChatToDiscord\config.yml`.
6. Restart your server

# My Setup
Config:

![](http://puu.sh/wCTUs/2fe1737ded.png)

Discord:

![](http://puu.sh/wCTWY/73535fb931.png)

# To Do
- [x] Add async so it doesn't lag main thread
- [x] Add multiple webhook support
- [ ] Add ability to send messages of events
 - [ ] on join
 - [ ] on quit
 - [ ] on death
