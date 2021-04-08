var timeout;
var poll_rate = 1000;

// adds event listener for new message form to post_msg
// sets timeout to poll for messages every 1sec 
function setup() {
	document.getElementById("submit_message").addEventListener("click", post_msg, true);
	timeout = window.setTimeout(poll_msgs, poll_rate);
	var input = document.getElementById("new_message");
	input.addEventListener('keyup', (e) => {
		if (e.key == "Enter") {
			event.preventDefault();
			document.getElementById("submit_message").click();
		}
	});
}

// fetches messages for the room and send them to handle_poll
function poll_msgs() {
	console.log("Polling for new items");
	fetch("/messages")
	.then((response) => {
		return response.json();
	})
	.then((result) => {
		handle_poll(JSON.parse(JSON.stringify(result)));
	})
	.catch(() => {
		console.log("Error fetching messages");
	})
}

// first, checks if returned the message indicating that the room has been closed
// and alerts and redirects the user if so
// otherwise, sends the messages to add_msgs
function handle_poll(result) {
	if(JSON.stringify(result) == "{\"0\":\"room is now empty\"}") {
		alert("This room has been closed");
		window.location.href = "/room_list_False";	
	}
	else{
		add_msgs(JSON.parse(JSON.stringify(result)));
	}
	timeout = window.setTimeout(poll_msgs, poll_rate);
} 

// runs when a user posts a message:
// gets the message text from input element and sends it to new_message through fetch
// gets a response of a json format of the message (with the message added to the db)
// sends that to add_msgs and clears form input
function post_msg() {
	console.log("Posting new message");
	var message = document.getElementById("new_message").value;

	fetch("/new_message", {
		method: "post",
		headers: { "Content-type": "application/x-www-form-urlencoded; charset=UFT-8" },
		body: `message=${message}`
	})
	.then((response) => {
		return response.json();
	})
	.then((result) => {
		add_msgs(result, true);
		clear_input();
	})
	.catch(() => {
		console.log("Error posting new message");
	});
}

// isPost indicates whether a new message has been added (true), or it is just a polling update (false)
// if isPost, simply adds each message as a new element to the list of messages on the page
// if not, compare the num of messages on the page vs. the num of messages sent to the function
// and adjust so the elements on the page are correct
function add_msgs(messages, isPost) {
	var ul = document.getElementById("message_list");
	var li = document.createElement("li");
	var num_elems = document.getElementById("message_list").getElementsByTagName("li").length;
	var num_messages = Object.keys(messages).length;
	
	if (isPost) {
 		for (m in messages) {
				var bdi = document.createElement("bdi");
				bdi.appendChild(document.createTextNode(m + ' ' + String.fromCharCode(187) + ' '));
				bdi.className = "msgSender";
				li.appendChild(bdi);
				li.appendChild(document.createTextNode(messages[m].toString()));
				li.className = "roomItem";
				ul.appendChild(li);
			}
	}
	else {
		if (num_messages > num_elems) {
			for (m in messages) {
				if (m < num_elems)
					delete messages[m];
			}
			for (m in messages) {
				for (user in messages[m]) {
					var msg = "";
					for (var key in messages[m]) {
						msg = messages[m][key].toString();
					}
					var bdi = document.createElement("bdi");
					bdi.appendChild(document.createTextNode(user + ' ' + String.fromCharCode(187) + ' '));
					bdi.className = "msgSender";
					li.appendChild(bdi);
					li.appendChild(document.createTextNode(msg));
					li.className = "roomItem";
					ul.appendChild(li);
				}
			}
		}
	}
}

// clears the new message form after a message has been sent
function clear_input() {
	console.log("Clearing input");
	document.getElementById("new_message").value = "";
}

// runs setup on page load
window.addEventListener("load", setup, true);