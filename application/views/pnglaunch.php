<style type="text/css">
    #gameFrame {
        width: 100%;
        height: 100vh;
        border: none;
        margin: 0 auto;
    }

    body {
        margin: 0 auto;
    }

    #iframediv {
        min-height: 750px;
    }
</style>
<div id="iframediv">
    <iframe scrolling="no" width="100%" noresize="noresize" src="<?php echo $launchUrl; ?>" id="gameFrame"><?php echo $launchUrl; ?></iframe>
</div>


<script>
    document.getElementById("gameFrame").onload = function() {
        GameCommunicator.init(document.getElementById("gameFrame"));
        GameCommunicator.postMessage({
            messageType: "addEventListener",
            eventType: "gameReady"
        });
        GameCommunicator.postMessage({
            messageType: "addEventListener",
            eventType: "gameError"
        });
        GameCommunicator.postMessage({
            messageType: "addEventListener",
            eventType: "running"
        });
        GameCommunicator.postMessage({
            messageType: "addEventListener",
            eventType: "roundStarted"
        });
        GameCommunicator.postMessage({
            messageType: "addEventListener",
            eventType: "roundEnded"
        });
        GameCommunicator.postMessage({
            messageType: "addEventListener",
            eventType: "gameEnabled"
        });
        GameCommunicator.postMessage({
            messageType: "addEventListener",
            eventType: "gameDisabled"
        });
        GameCommunicator.postMessage({
            messageType: "addEventListener",
            eventType: "gameIdle"
        });
        GameCommunicator.postMessage({
            messageType: "addEventListener",
            eventType: "logout"
        });
        GameCommunicator.postMessage({
            messageType: "addEventListener",
            eventType: "balanceUpdate"
        });
        GameCommunicator.postMessage({
            messageType: "addEventListener",
            eventType: "roundWin"
        });
        GameCommunicator.postMessage({
            messageType: "addEventListener",
            eventType: "backToLobby"
        });
        GameCommunicator.postMessage({
            messageType: "addEventListener",
            eventType: "reloadGame"
        });
        GameCommunicator.postMessage({
            messageType: "addEventListener",
            eventType: "playForReal"
        });

    }
    /**
    * GameCommuncator
    * Basic implementation of window.postmessage communication with
    Iframed PNG game.
    */
    var GameCommunicator = {
        source: undefined,
        origin: undefined,

        init: function(element) {
            this.source = element.contentWindow;
            this.origin = "<?php echo $pparam['png_base_url']; ?>"; //origin of PNG container launcher. iframe origin
            window.addEventListener("message", this.processGameMessage.bind(this));
        },

        postMessage: function(data) {
            console.log("GameCommunicator sent the following message:", data);
            this.source.postMessage(data, this.origin);
        },
        /**
         * Receives the messages the PNG game dispatches
         * @@param {object} e
         */
        processGameMessage: function(e) {
            console.log("GameCommunicator received: ", e.data);
            switch (e.data.type) {
                case "reloadGame":
                    window.location.reload();
                    //You can add whatever code you want to use here.
                    break;
                case "backToLobby":
                    window.location.replace("<?php echo ($returnUrl)?$returnUrl:'www.google.com'; ?>");
                    break;

                default:
                    break;
            }


        }

    }
</script>