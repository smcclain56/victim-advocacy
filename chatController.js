//SendBird
const APP_ID = "1920207D-9A3E-4ADC-A8FF-F70D816B0E6F";

const queryString = window.location.search;
const urlParams = new URLSearchParams(queryString);
const USER_ID = urlParams.get('userid');
const TOKEN = $('#token').val();

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
    
    if (TOKEN == null) {
        connectSBServer();
    }
    else {
        connectwithtoken(TOKEN);
    }
    $(".chat").hide();
    buildChatMenu();
    setInterval(function () { buildChatMenu() }, 3000);
});
// $(window).ready(
//     function () {
//         setInterval(function () { buildChatMenu() }, 10000);
//     }
// );


// $( "#dialog" ).dialog({
//     autoOpen: false,

//   });

//   $( ".button1" ).on( "click", function() {
//     $( "#dialog" ).dialog( "open" );
//   });


function updateScrollbar() {
    $messages.mCustomScrollbar("update").mCustomScrollbar('scrollTo', 'bottom', {
        scrollInertia: 10,
        timeout: 0
    });
}

function setDate(dateInput) {
    d = new Date() || new Date(dateInput)
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
$('.chat-menu').on("click", "div.available-chats", function () {
    
    $('.chat').show();
    this.addClass(".available-chats-active");
    var channel = this.id;
    console.log("channel: " + channel);
    if(chatChannel != null){chatChannel.exit(function(response, error) {
        if(error){
            console.log( "error Exiting channel" + error);
            return;
        }
        console.log("left channel");
    });}
    
    enterChannel(channel);
    $('#'+chatChannel.url).remove();
    // var participantListQuery = chatChannel.createParticipantListQuery();
    // var particpantListString = "";
    // participantListQuery.next(function (participantList, error) {
    //     if (error) {
    //         return;
    //     }
    //     participantList.forEach(function(item, index){
    //         participantListString += item.nickname + " ";
    //     })
    //     console.log(participantList);

    // });
    $('#chatUserName').text(chatChannel.name);
    $('#chatId').text("Victim");
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
    ChannelHandler.onChannelDeleted = function (channelUrl, channelType) {
        $("#" + channelUrl).remove();
    };
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
        //disply enter message
    };
    ChannelHandler.onUserExited = function (openChannel, user) {
        //display left message
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
function connectwithtoken(accessToken) {
    console.log(accessToken);
    sb.connect(USER_ID, accessToken, function (user, error) {
        if (error) {
            return;
        }
    });
}
function connectSBServer() {
    sb.connect(USER_ID, '00dfc6422f3a8b4cf283e34e4e7f0b45f77dd179', function (user, error) {
        if (error) {
            return;
        }
        // sb.updateCurrentUserInfo(decodeURIComponent(NICKNAME), null, function (error, user) {
        //     if (error) {
        //         return;
        //     }
        // })
    });

};
function openSBChat(openChannel) {
    const NAME = USER_ID;
    const COVER_IMAGE_OR_URL = null;
    const DATA = null;
    const OPERATOR_IDS = null;
    const CUSTOM_TYPE = null;
    sb.OpenChannel.createChannel(NAME, COVER_IMAGE_OR_URL, DATA, OPERATOR_IDS, CUSTOM_TYPE, function (openChannel, error) {
        if (error) {
            return;
        }
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
/** build a menu to select a chat that has been opened by a victim */
function buildChatMenu() {

    //console.log("Building chat menu");
    var openChannelListQuery = sb.OpenChannel.createOpenChannelListQuery();

    openChannelListQuery.next(function (openChannels, error) {
        if (error) {
            return;
        }
        openChannels.forEach(function (openChannel, index) {
            var participantListQuery = openChannel.createParticipantListQuery();

            participantListQuery.next(function (participantList, error) {
                if (error) {
                    return;
                }

               // console.log("participant List: " + participantList.length);
                if (participantList.length < 1) {
                    openChannel.delete(function (response, error) {
                        if (error) {
                            console.log("error deleting channel:" + error)
                            //console.log(openChannel)
                            return;
                        }
                        $('#'+openChannel.url).remove();
                        //$('.chat').hide();
                       // console.log("Data: "  + openChannel);
                        //deleteVictimUser(openChannel.DATA);
                        console.log("Empty channel " + openChannel.name + " deleted")
                    })
                    return;
                } else if (participantList.length < 2 && $('#' +openChannel.url).length === 0) {

                    $('<div class="available-chats">' + openChannel.name + '</div>').attr("id", openChannel.url).appendTo($('.chat-menu'));

                } else {
                    return;
                }

            });

        })
    });
    //setTimeout(buildChatMenu(), 15000);
}

/* deletes the victim acount on sendbird*/
function deleteVictimUser( user_id ){
    var settings = {
        "url": "https://api-1920207D-9A3E-4ADC-A8FF-F70D816B0E6F.sendbird.com/v3/users/" + user_id,
        "method": "DELETE",
        "timeout": 0,
        "headers": {
          "Api-Token": "1378399f93c26da80722fab836b1134a545da721"
        },
      };
      
      $.ajax(settings).done(function (response) {
        console.log("deleted victim");
        console.log(response);
      });
}

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
        if (openChannel.participantCount == 2) {
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

