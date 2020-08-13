//sendBird
const APP_ID = "1920207D-9A3E-4ADC-A8FF-F70D816B0E6F";

const queryString = window.location.search;
const urlParams = new URLSearchParams(queryString);
const NICKNAMES = [
    "excited-goosander",
    "emphatic-sardine",
    "energetic-mosquito",
    "delightful-dog",
    "merry-hare",
    "praiseworthy-falcon",
    "amiable-curlew",
    "vigorous-pony",
    "fabulous-elephant-seal",
    "cheery-cobra",
    "respectable-heron",
    "comfortable-tamarin",
    "sincere-rabbit",
    "kind-mandrill",
    "extraordinary-pony",
  ];

function getRandomInt(max) {
  return Math.floor(Math.random() * Math.floor(max));
}  

//var NICKNAME = urlParams.get('nickname');
//if(urlParams.get('nickname') === undefined){
var NICKNAME = NICKNAMES[getRandomInt((NICKNAMES.length)-1)];
if(urlParams.get('nickname') != ""){
    var NICKNAME = urlParams.get('nickname');
}
//console.log(NICKNAME);
const CHANNEL_ID = Math.random().toString();
const USER_ID = Math.random().toString();

var sb = new SendBird({ appId: APP_ID });
var chatChannel = null;
var msg;

chatEventHandler();
connectionEventHandler();

var $messages = $('.messages-content'),
    d, h, m,
    i = 0;




$(window).load(function () {
    $messages.mCustomScrollbar();
    connectSBServer();
    console.log("before open chat");
    openSBChat();
    console.log("after open chat")
    $(".chat").hide();
    $(".wait").show();
});


function updateScrollbar() {
    $messages.mCustomScrollbar("update").mCustomScrollbar('scrollTo', 'bottom', {
        scrollInertia: 10,
        timeout: 0
    });
}


function setDate(dateInput) {
    d = new Date() || new Date(dateInput);
    if (m != d.getMinutes()) {
        m = d.getMinutes();
        $('<div class="timestamp">' + d.getHours() + ':' + m + '</div>').appendTo($('.message:last'));
    }
}
 
function insertMessage() {
    msg = $('.message-input').val();
    if ($.trim(msg) == '') {
        return false;
    }
    $('<div class="message message-personal">' + msg + '</div>').appendTo($('.mCSB_container')).addClass('new');
    setDate();
    $('.message-input').val(null);
    updateScrollbar();
    sendMsg(msg);

    //   setTimeout(function() {
    //     fakeMessage();
    //   }, 1000 + (Math.random() * 20) * 100);
}

$('.message-submit').click(function () {
    insertMessage();
});

$(window).on('keydown', function (e) {
    if (e.which == 13) {
        insertMessage();
        return false;
    }
})

//Event Handlers
function chatEventHandler() {
    /* Add event handler to channel*/
    var ChannelHandler = new sb.ChannelHandler();

    ChannelHandler.onMessageReceived = function (channel, message) {
        $('<div class="message new">' + message.message + '</div>').appendTo($('.mCSB_container')).addClass('new');
        setDate();
        updateScrollbar();
    };
    ChannelHandler.onMessageUpdated = function (channel, message) { };
    ChannelHandler.onMessageDeleted = function (channel, messageId) { };
    ChannelHandler.onMentionReceived = function (channel, message) { };
    ChannelHandler.onChannelChanged = function (channel) { };
    ChannelHandler.onChannelDeleted = function (channelUrl, channelType) { };
    ChannelHandler.onChannelFrozen = function (channel) { };
    ChannelHandler.onChannelUnfrozen = function (channel) { };
    ChannelHandler.onMetaDataCreated = function (channel, metaData) { };
    ChannelHandler.onMetaDataUpdated = function (channel, metaData) { };
    ChannelHandler.onMetaDataDeleted = function (channel, metaDataKeys) { };
    ChannelHandler.onMetaCountersCreated = function (channel, metaCounter) { };
    ChannelHandler.onMetaCountersUpdated = function (channel, metaCounter) { };
    ChannelHandler.onMetaCountersDeleted = function (channel, metaCounterKeys) { };
    ChannelHandler.onChannelHidden = function (groupChannel) { };
    ChannelHandler.onUserReceivedInvitation = function (groupChannel, inviter, invitees) { };
    ChannelHandler.onUserDeclinedInvitation = function (groupChannel, inviter, invitee) { };
    ChannelHandler.onUserJoined = function (groupChannel, user) { };
    ChannelHandler.onUserLeft = function (groupChannel, user) { };
    ChannelHandler.onReadReceiptUpdated = function (groupChannel) { };
    ChannelHandler.onTypingStatusUpdated = function (groupChannel) { };
    ChannelHandler.onUserEntered = function (openChannel, user) {
            console.log(user);

            if(user.nickname != NICKNAME){
                $('#chatUserName').text(user.nickname);
                $('.wait').hide();
                $('.wrapper').hide();
                $('.chat').show();
            } 

    };
    ChannelHandler.onUserExited = function (openChannel, user) {
        //display left 
        $('.chat').hide();
        $('.wait').show();
        $('.wait').text("Advocate Disconnected, please wait for an Advocate!");
        $('.wrapper').show();
        
    };
    ChannelHandler.onUserMuted = function (channel, user) { };
    ChannelHandler.onUserUnmuted = function (channel, user) { };
    ChannelHandler.onUserBanned = function (channel, user) { };
    ChannelHandler.onUserUnbanned = function (channel, user) { };

    // Add this channel event handler to the SendBird object.
    sb.addChannelHandler("sbHandler", ChannelHandler);
};
function connectionEventHandler() {
    var ConnectionHandler = new sb.ConnectionHandler();

    ConnectionHandler.onReconnectStarted = function () { };
    ConnectionHandler.onReconnectSucceeded = function () { };
    ConnectionHandler.onReconnectFailed = function () { };

    sb.addConnectionHandler("sbConnectHandler", ConnectionHandler);

};


function sendMsg(msg) {
    var MESSAGE = msg;
    var DATA = null;
    var CUSTOM_TYPE = null;
    chatChannel.sendUserMessage(MESSAGE, DATA, CUSTOM_TYPE, function (message, error) {
        if (error) {
            return;
        }
    });
}

function connectSBServer() {
    sb.connect(USER_ID, function (user, error) {
        if (error) {
            return;
        }
        sb.updateCurrentUserInfo(decodeURIComponent(NICKNAME), null, function (error, user) {
            if (error) {
                return;
            }
        })
    });

};
function openSBChat(openChannel) {
    console.log("opening a channel");
    console.log(NICKNAME);
    const NAME = NICKNAME + " " + USER_ID.substr(0, 4);
    console.log(NAME);
    const COVER_IMAGE_OR_URL = null;
    const DATA = USER_ID;
    const OPERATOR_IDS = null;
    const CUSTOM_TYPE = "VICTIM_CHAT";
    sb.OpenChannel.createChannel(NAME, COVER_IMAGE_OR_URL, DATA, OPERATOR_IDS, CUSTOM_TYPE, function (openChannel, error) {
        if (error) {
            console.log("error opening channel: "+ error);
            return;
        }
        console.log(openChannel.DATA);
        sb.OpenChannel.getChannel(openChannel.url, function (openChannel, error) {
            if (error) {
                return;
            }
            openChannel.enter(function (response, error) {
                if (error) {
                    return;
                }
            })
            
            chatChannel = openChannel;
            $('#chatId').text("Chat with an Advocate");
        });
    });


};

function exitSBChat() {

    sb.OpenChannel.getChannel(chatChannel.url, function (openChannel, error) {
        if (error) {
            return;
        }

        openChannel.delete(function (response, error) {
            if (error) {
                return;
            }
        }
        );

    })
};

function renderPrevMsg(messageList) {
    messageList.forEach(function (item, index) {
        if (item._sender.userId != USER_ID) {
            $('<div class="message new">' + item.message + '</div>').appendTo($('.mCSB_container')).addClass('new');
            setDate(item.createdAt);
            updateScrollbar();
        } else if (item._sender.userId == USER_ID) {
            $('<div class="message message-personal">' + item.message + '</div>').appendTo($('.mCSB_container'));
            setDate(item.createdAt);
            updateScrollbar();
        }
    })
}

function clearChatWindow() {
    $('.message').remove();
}

function enterChannel(channel) {
    sb.OpenChannel.getChannel(channel, function (openChannel, error) {
        if (error) {
            console.log(error);
            return;
        }
        if(openChannel.participantCount==2){
            console.log("channel full");
            return;
        }
        openChannel.enter(function (response, error) {
            if (error) {
                return;
            }
            var messageListQuery = openChannel.createPreviousMessageListQuery();
            messageListQuery.load(function (messageList, error) {
                if (error) {
                    return;
                }
                console.log(messageList);
                clearChatWindow();
                renderPrevMsg(messageList);
            });


        })
        chatChannel = openChannel;
    })
}

