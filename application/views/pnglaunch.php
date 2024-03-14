
<style type="text/css">
	#gameFrame {
		width: 100%;
	    height: 100%;
	    border: none;
	    margin: 0 auto;
	}
	body {
		margin: 0 auto;
	}
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-12 p-0">
            <iframe scrolling="no" width="100%"  height="100%" noresize="noresize" src="<?php echo $launchUrl;?>"
                id="gameFrame"><?php echo $launchUrl;?></iframe>
        </div>
    </div>
</div>


<script>
document.getElementById("gameFrame").onload = function () {
    GameCommunicator.init(document.getElementById("gameFrame"));
    GameCommunicator.postMessage({ messageType: "addEventListener", eventType: "reloadGame" });

}
/**
* GameCommuncator
* Basic implementation of window.postmessage communication with
Iframed PNG game.
*/
var GameCommunicator =
{
    source: undefined,
    origin: undefined,
    /**
    * Initiates the communication with the Iframe
    * @@param {iframe} element
    */
    init: function (element) {
        window.addEventListener("message", this.processGameMessage.bind(this));
        this.source = element.contentWindow;
        //this.origin = "https://bsistage.playngonetwork.com";
        this.origin = "https://bsicw.playngonetwork.com";
    },
    /**
    * Sends the message to the Iframe
    * @@param {object} data
    * Example of adding an Engage event listener: GameCommunicator.postMessage({ messageType: "addEventListener", eventType: "roundStarted" })
    * Example of calling Engage function: GameCommunicator.postMessage({ messageType: "request", eventType: "spin" })
    */
    postMessage: function (data) {
        console.log("GameCommunicator sent the following message:", data);
        this.source.postMessage(data, this.origin);
    },
    /**
    * Receives the messages the PNG game dispatches
    * @@param {object} e
    */
    processGameMessage: function (e) {
        console.log("GameCommunicator reveiced: ", e.data);
        switch (e.data.Type) {
            case "reloadGame":
                console.log("reload code");
                window.location.reload(); // stub implementation
                break;
            default:
                break;
        }
    }
}
</script>

